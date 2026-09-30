-- ============================================================================
-- BARBERLOO PRODUCTION DATABASE FUNCTIONS & RPCs
-- Phase 2.1: Concurrency-Safe Queue Generation & Atomic Queue Management
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 1. JOIN LIVE QUEUE (CONCURRENCY-SAFE)
-- Prevents race conditions on queue_number and accurately calculates estimated wait.
-- ----------------------------------------------------------------------------
CREATE OR REPLACE FUNCTION public.join_live_queue(
    p_shop_id UUID,
    p_service_id UUID
)
RETURNS JSONB AS $$
DECLARE
    v_user_id UUID;
    v_next_number INTEGER;
    v_service_duration INTEGER;
    v_current_wait INTEGER;
    v_new_queue_id UUID;
    v_existing_active UUID;
BEGIN
    v_user_id := auth.uid();
    IF v_user_id IS NULL THEN
        RAISE EXCEPTION 'Authentication required to join the queue';
    END IF;

    -- Verify shop is open
    IF NOT EXISTS (SELECT 1 FROM public.shops WHERE id = p_shop_id AND is_open = true) THEN
        RAISE EXCEPTION 'Shop is currently closed';
    END IF;

    -- Fetch and verify service
    SELECT duration INTO v_service_duration 
    FROM public.services 
    WHERE id = p_service_id AND shop_id = p_shop_id AND is_active = true;

    IF v_service_duration IS NULL THEN
        RAISE EXCEPTION 'Selected service is invalid or inactive';
    END IF;

    -- Prevent duplicate active queues in the same shop
    SELECT id INTO v_existing_active 
    FROM public.queue 
    WHERE shop_id = p_shop_id AND customer_id = v_user_id AND status IN ('waiting', 'serving');

    IF v_existing_active IS NOT NULL THEN
        RAISE EXCEPTION 'You already have an active ticket in this queue';
    END IF;

    -- Acquire an advisory xact lock per shop to ensure zero race conditions on queue numbering
    PERFORM pg_advisory_xact_lock(hashtext(p_shop_id::text));

    -- Determine next queue number for today
    SELECT COALESCE(MAX(queue_number), 0) + 1
    INTO v_next_number
    FROM public.queue
    WHERE shop_id = p_shop_id
      AND joined_at >= CURRENT_DATE;

    -- Calculate total estimated wait:
    -- Sum duration of currently 'serving' customer + all 'waiting' customers ahead
    SELECT COALESCE(SUM(s.duration), 0)
    INTO v_current_wait
    FROM public.queue q
    JOIN public.services s ON q.service_id = s.id
    WHERE q.shop_id = p_shop_id
      AND q.status IN ('waiting', 'serving');

    -- Insert new queue ticket
    INSERT INTO public.queue (
        shop_id,
        customer_id,
        service_id,
        queue_number,
        status,
        estimated_wait,
        joined_at
    )
    VALUES (
        p_shop_id,
        v_user_id,
        p_service_id,
        v_next_number,
        'waiting',
        v_current_wait,
        timezone('utc'::text, now())
    )
    RETURNING id INTO v_new_queue_id;

    RETURN jsonb_build_object(
        'queue_id', v_new_queue_id,
        'queue_number', v_next_number,
        'estimated_wait', v_current_wait,
        'status', 'waiting'
    );
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- ----------------------------------------------------------------------------
-- 2. GET QUEUE POSITION & PEOPLE AHEAD
-- Dynamic calculation for live ticket tracking
-- ----------------------------------------------------------------------------
CREATE OR REPLACE FUNCTION public.get_customer_queue_status(p_queue_id UUID)
RETURNS JSONB AS $$
DECLARE
    v_queue RECORD;
    v_people_ahead INTEGER;
    v_estimated_wait INTEGER;
BEGIN
    SELECT q.*, s.name as service_name, s.duration as service_duration, sh.name as shop_name
    INTO v_queue
    FROM public.queue q
    JOIN public.services s ON q.service_id = s.id
    JOIN public.shops sh ON q.shop_id = sh.id
    WHERE q.id = p_queue_id;

    IF v_queue.id IS NULL THEN
        RAISE EXCEPTION 'Queue ticket not found';
    END IF;

    IF v_queue.status = 'waiting' THEN
        -- Count people ahead who are waiting or serving
        SELECT COUNT(*) INTO v_people_ahead
        FROM public.queue
        WHERE shop_id = v_queue.shop_id
          AND status IN ('waiting', 'serving')
          AND (joined_at < v_queue.joined_at OR status = 'serving')
          AND id != v_queue.id;

        -- Sum duration of everyone ahead
        SELECT COALESCE(SUM(s.duration), 0) INTO v_estimated_wait
        FROM public.queue q
        JOIN public.services s ON q.service_id = s.id
        WHERE q.shop_id = v_queue.shop_id
          AND q.status IN ('waiting', 'serving')
          AND (q.joined_at < v_queue.joined_at OR q.status = 'serving')
          AND q.id != v_queue.id;
    ELSIF v_queue.status = 'serving' THEN
        v_people_ahead := 0;
        v_estimated_wait := 0;
    ELSE
        v_people_ahead := 0;
        v_estimated_wait := 0;
    END IF;

    RETURN jsonb_build_object(
        'queue_id', v_queue.id,
        'queue_number', v_queue.queue_number,
        'shop_name', v_queue.shop_name,
        'service_name', v_queue.service_name,
        'service_duration', v_queue.service_duration,
        'status', v_queue.status,
        'people_ahead', v_people_ahead,
        'estimated_wait', v_estimated_wait,
        'joined_at', v_queue.joined_at
    );
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- ----------------------------------------------------------------------------
-- 3. BARBER ACTION: START SERVING CUSTOMER
-- ----------------------------------------------------------------------------
CREATE OR REPLACE FUNCTION public.start_serving_customer(
    p_queue_id UUID
)
RETURNS JSONB AS $$
DECLARE
    v_user_id UUID;
    v_shop_id UUID;
BEGIN
    v_user_id := auth.uid();
    
    -- Verify caller owns the shop
    SELECT q.shop_id INTO v_shop_id
    FROM public.queue q
    JOIN public.shops sh ON q.shop_id = sh.id
    WHERE q.id = p_queue_id AND sh.owner_id = v_user_id;

    IF v_shop_id IS NULL THEN
        RAISE EXCEPTION 'Unauthorized: You do not own this barber shop';
    END IF;

    UPDATE public.queue
    SET status = 'serving', updated_at = timezone('utc'::text, now())
    WHERE id = p_queue_id;

    RETURN jsonb_build_object('success', true, 'queue_id', p_queue_id, 'status', 'serving');
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- ----------------------------------------------------------------------------
-- 4. BARBER ACTION: COMPLETE SERVICE
-- ----------------------------------------------------------------------------
CREATE OR REPLACE FUNCTION public.complete_customer_service(
    p_queue_id UUID
)
RETURNS JSONB AS $$
DECLARE
    v_user_id UUID;
    v_shop_id UUID;
BEGIN
    v_user_id := auth.uid();
    
    SELECT q.shop_id INTO v_shop_id
    FROM public.queue q
    JOIN public.shops sh ON q.shop_id = sh.id
    WHERE q.id = p_queue_id AND sh.owner_id = v_user_id;

    IF v_shop_id IS NULL THEN
        RAISE EXCEPTION 'Unauthorized: You do not own this barber shop';
    END IF;

    UPDATE public.queue
    SET status = 'completed', updated_at = timezone('utc'::text, now())
    WHERE id = p_queue_id;

    RETURN jsonb_build_object('success', true, 'queue_id', p_queue_id, 'status', 'completed');
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- ----------------------------------------------------------------------------
-- 5. CANCEL / LEAVE QUEUE (Customer or Barber)
-- ----------------------------------------------------------------------------
CREATE OR REPLACE FUNCTION public.leave_or_cancel_queue(
    p_queue_id UUID
)
RETURNS JSONB AS $$
DECLARE
    v_user_id UUID;
    v_authorized BOOLEAN;
BEGIN
    v_user_id := auth.uid();

    SELECT (q.customer_id = v_user_id OR sh.owner_id = v_user_id) INTO v_authorized
    FROM public.queue q
    JOIN public.shops sh ON q.shop_id = sh.id
    WHERE q.id = p_queue_id;

    IF NOT COALESCE(v_authorized, false) THEN
        RAISE EXCEPTION 'Unauthorized to cancel this queue ticket';
    END IF;

    UPDATE public.queue
    SET status = 'cancelled', updated_at = timezone('utc'::text, now())
    WHERE id = p_queue_id;

    RETURN jsonb_build_object('success', true, 'queue_id', p_queue_id, 'status', 'cancelled');
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;
