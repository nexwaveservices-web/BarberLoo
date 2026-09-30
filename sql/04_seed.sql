-- ============================================================================
-- BARBERLOO SAMPLE SEED DATA
-- Pre-populates premium barbershops, services, and realistic queue items
-- Note: Replace mock user IDs with real auth user UUIDs once authenticated,
-- or run this in the Supabase SQL editor for initial demonstration.
-- ============================================================================

-- Insert sample shop records (can be claimed or linked to barber user)
-- Note: Replace '00000000-0000-0000-0000-000000000001' with your barber auth user ID
DO $$
DECLARE
    v_owner_id UUID;
    v_shop1_id UUID;
    v_shop2_id UUID;
    v_shop3_id UUID;
BEGIN
    SELECT id INTO v_owner_id FROM public.profiles LIMIT 1;

    -- If no profile exists yet, seed will run once a user signs up.
    IF v_owner_id IS NOT NULL THEN
        -- Shop 1
        INSERT INTO public.shops (id, owner_id, name, description, address, city, phone, is_open, rating, total_reviews, cover_url, logo_url)
        VALUES (
            'a1b2c3d4-e5f6-4a1b-8c2d-111111111111',
            v_owner_id,
            'The Golden Razor Lounge',
            'Award-winning heritage barbershop specializing in precision fades, traditional hot towel shaves, and executive beard grooming.',
            '442 Market Street, Downtown',
            'San Francisco',
            '+1 (415) 555-0192',
            true,
            4.95,
            128,
            'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=200&q=80'
        ) ON CONFLICT (id) DO NOTHING;

        -- Shop 2
        INSERT INTO public.shops (id, owner_id, name, description, address, city, phone, is_open, rating, total_reviews, cover_url, logo_url)
        VALUES (
            'b2c3d4e5-f6a1-4b2c-9d3e-222222222222',
            v_owner_id,
            'Crown & Blade Studio',
            'Contemporary urban salon and barbershop offering luxury scissor cuts, skin fades, and textured styling in an upscale lounge.',
            '880 Valencia Street, Mission District',
            'San Francisco',
            '+1 (415) 555-0144',
            true,
            4.88,
            94,
            'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1621605815971-fbc98d665033?auto=format&fit=crop&w=200&q=80'
        ) ON CONFLICT (id) DO NOTHING;

        -- Shop 3
        INSERT INTO public.shops (id, owner_id, name, description, address, city, phone, is_open, rating, total_reviews, cover_url, logo_url)
        VALUES (
            'c3d4e5f6-a1b2-4c3d-0e4f-333333333333',
            v_owner_id,
            'Artisan Fade Lab',
            'Specialist barber crew dedicated to modern tapering, hair tattoo detailing, beard sculpting, and organic scalp treatments.',
            '1204 Broadway Avenue',
            'Oakland',
            '+1 (510) 555-0873',
            true,
            4.92,
            76,
            'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1599566150163-29194dcaad36?auto=format&fit=crop&w=200&q=80'
        ) ON CONFLICT (id) DO NOTHING;

        -- Services for Shop 1
        INSERT INTO public.services (shop_id, name, description, price, duration, is_active)
        VALUES 
            ('a1b2c3d4-e5f6-4a1b-8c2d-111111111111', 'Signature Precision Haircut', 'Consultation, precision scissor & clipper cut, neck shave, and premium styling finish.', 45.00, 30, true),
            ('a1b2c3d4-e5f6-4a1b-8c2d-111111111111', 'Hot Towel Royal Shave', 'Pre-shave essential oils, 3 hot towel steams, straight-razor shave, and soothing balm.', 40.00, 30, true),
            ('a1b2c3d4-e5f6-4a1b-8c2d-111111111111', 'The Executive Cut & Beard Combo', 'Complete signature haircut plus full beard shaping, contouring, and hot oil treatment.', 75.00, 50, true),
            ('a1b2c3d4-e5f6-4a1b-8c2d-111111111111', 'Beard Sculpt & Razor Lineup', 'Detailed beard trimming, clipper work, straight razor edging, and organic balm.', 28.00, 20, true)
        ON CONFLICT DO NOTHING;

        -- Services for Shop 2
        INSERT INTO public.services (shop_id, name, description, price, duration, is_active)
        VALUES 
            ('b2c3d4e5-f6a1-4b2c-9d3e-222222222222', 'Skin Fade & Razor Finish', 'Seamless zero/skin fade, top texturizing, foil shaver clean up, and pomade styling.', 50.00, 35, true),
            ('b2c3d4e5-f6a1-4b2c-9d3e-222222222222', 'Express Buzz & Edge', 'Uniform clipper cut with razor lineup and neck clean.', 25.00, 15, true),
            ('b2c3d4e5-f6a1-4b2c-9d3e-222222222222', 'Deluxe Scalp & Fade Treatment', 'Exfoliating tea-tree scalp massage, shampoo rinse, and precision fade.', 65.00, 45, true)
        ON CONFLICT DO NOTHING;

        -- Services for Shop 3
        INSERT INTO public.services (shop_id, name, description, price, duration, is_active)
        VALUES 
            ('c3d4e5f6-a1b2-4c3d-0e4f-333333333333', 'Artisan Taper & Texture', 'Modern low/mid taper fade with shears texturing and matte clay style.', 42.00, 30, true),
            ('c3d4e5f6-a1b2-4c3d-0e4f-333333333333', 'Father & Son Duo Cut', 'Two signature cuts scheduled together.', 80.00, 55, true)
        ON CONFLICT DO NOTHING;

    END IF;
END $$;
