-- ============================================================================
-- BARBERLOO ROW LEVEL SECURITY (RLS) POLICIES
-- Phase 3: Zero-Trust Security Policies for all Tables
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 1. PROFILES RLS
-- ----------------------------------------------------------------------------
ALTER TABLE public.profiles ENABLE ROW LEVEL SECURITY;

-- Anyone authenticated can view barber and customer profiles (e.g., displaying barber name & customer on queue)
CREATE POLICY "Public profiles are viewable by everyone" 
ON public.profiles FOR SELECT 
USING (true);

-- Users can update only their own profile
CREATE POLICY "Users can update own profile" 
ON public.profiles FOR UPDATE 
USING (auth.uid() = id)
WITH CHECK (auth.uid() = id);

-- ----------------------------------------------------------------------------
-- 2. SHOPS RLS
-- ----------------------------------------------------------------------------
ALTER TABLE public.shops ENABLE ROW LEVEL SECURITY;

-- Anyone (guest or authenticated) can view shops
CREATE POLICY "Shops are viewable by everyone" 
ON public.shops FOR SELECT 
USING (true);

-- Only registered barbers or admins can create a shop
CREATE POLICY "Barbers can insert shops" 
ON public.shops FOR INSERT 
TO authenticated 
WITH CHECK (
    auth.uid() = owner_id AND
    EXISTS (SELECT 1 FROM public.profiles WHERE id = auth.uid() AND role IN ('barber', 'admin'))
);

-- Only the shop owner can update their shop
CREATE POLICY "Barbers can update own shop" 
ON public.shops FOR UPDATE 
TO authenticated 
USING (auth.uid() = owner_id)
WITH CHECK (auth.uid() = owner_id);

-- Shop owner or admin can delete
CREATE POLICY "Barbers can delete own shop" 
ON public.shops FOR DELETE 
TO authenticated 
USING (auth.uid() = owner_id);

-- ----------------------------------------------------------------------------
-- 3. SERVICES RLS
-- ----------------------------------------------------------------------------
ALTER TABLE public.services ENABLE ROW LEVEL SECURITY;

-- Anyone can view active services; shop owner can view all services (including inactive)
CREATE POLICY "Services are viewable by everyone" 
ON public.services FOR SELECT 
USING (
    is_active = true OR 
    EXISTS (SELECT 1 FROM public.shops WHERE id = services.shop_id AND owner_id = auth.uid())
);

-- Only shop owner can insert services
CREATE POLICY "Shop owners can insert services" 
ON public.services FOR INSERT 
TO authenticated 
WITH CHECK (
    EXISTS (SELECT 1 FROM public.shops WHERE id = shop_id AND owner_id = auth.uid())
);

-- Only shop owner can update services
CREATE POLICY "Shop owners can update services" 
ON public.services FOR UPDATE 
TO authenticated 
USING (
    EXISTS (SELECT 1 FROM public.shops WHERE id = services.shop_id AND owner_id = auth.uid())
);

-- Only shop owner can delete services
CREATE POLICY "Shop owners can delete services" 
ON public.services FOR DELETE 
TO authenticated 
USING (
    EXISTS (SELECT 1 FROM public.shops WHERE id = services.shop_id AND owner_id = auth.uid())
);

-- ----------------------------------------------------------------------------
-- 4. BOOKINGS RLS
-- ----------------------------------------------------------------------------
ALTER TABLE public.bookings ENABLE ROW LEVEL SECURITY;

-- Customers see their own bookings; Barbers see bookings for their shops
CREATE POLICY "Bookings viewable by participant" 
ON public.bookings FOR SELECT 
TO authenticated 
USING (
    customer_id = auth.uid() OR
    EXISTS (SELECT 1 FROM public.shops WHERE id = bookings.shop_id AND owner_id = auth.uid())
);

-- Authenticated customers can insert bookings
CREATE POLICY "Customers can create bookings" 
ON public.bookings FOR INSERT 
TO authenticated 
WITH CHECK (
    customer_id = auth.uid() AND
    EXISTS (SELECT 1 FROM public.services WHERE id = service_id AND shop_id = bookings.shop_id AND is_active = true)
);

-- Customer can cancel own booking; Barber can update status
CREATE POLICY "Update bookings policy" 
ON public.bookings FOR UPDATE 
TO authenticated 
USING (
    customer_id = auth.uid() OR
    EXISTS (SELECT 1 FROM public.shops WHERE id = bookings.shop_id AND owner_id = auth.uid())
);

-- ----------------------------------------------------------------------------
-- 5. QUEUE RLS
-- ----------------------------------------------------------------------------
ALTER TABLE public.queue ENABLE ROW LEVEL SECURITY;

-- Queue viewable by public/customers for queue calculation and barbers for management
CREATE POLICY "Queue items viewable by all authenticated" 
ON public.queue FOR SELECT 
TO authenticated 
USING (true);

-- Direct insertions & status updates should run via SECURITY DEFINER functions (RPCs)
-- but allow fallback for authenticated customer insert if needed
CREATE POLICY "Customer can insert queue entry" 
ON public.queue FOR INSERT 
TO authenticated 
WITH CHECK (customer_id = auth.uid());

CREATE POLICY "Shop owner and customer can update queue entry" 
ON public.queue FOR UPDATE 
TO authenticated 
USING (
    customer_id = auth.uid() OR
    EXISTS (SELECT 1 FROM public.shops WHERE id = queue.shop_id AND owner_id = auth.uid())
);

-- ----------------------------------------------------------------------------
-- 6. REVIEWS RLS
-- ----------------------------------------------------------------------------
ALTER TABLE public.reviews ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Reviews viewable by everyone" 
ON public.reviews FOR SELECT 
USING (true);

CREATE POLICY "Customers can insert reviews" 
ON public.reviews FOR INSERT 
TO authenticated 
WITH CHECK (customer_id = auth.uid());

-- ----------------------------------------------------------------------------
-- 7. NOTIFICATIONS RLS
-- ----------------------------------------------------------------------------
ALTER TABLE public.notifications ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view own notifications" 
ON public.notifications FOR SELECT 
TO authenticated 
USING (user_id = auth.uid());

CREATE POLICY "Users can update own notifications" 
ON public.notifications FOR UPDATE 
TO authenticated 
USING (user_id = auth.uid());
