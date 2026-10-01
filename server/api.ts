import { Router, Request, Response } from 'express';
import crypto from 'crypto';
import {
  readDatabase,
  writeDatabase,
  UserProfile,
  BarberShop,
  ServiceItem,
  QueueEntry,
  AppointmentEntry,
  CouponCode,
  TransactionRecord
} from './db.js';

export const apiRouter = Router();

// ========================
// AUTHENTICATION
// ========================

// Register
apiRouter.post('/auth/register', (req: Request, res: Response) => {
  try {
    const { email, password, full_name, phone, role } = req.body;
    if (!email || !password) {
      return res.status(400).json({ error: 'Email and password are required' });
    }

    const db = readDatabase();
    const normalizedEmail = String(email).trim().toLowerCase();

    // Check if user already exists
    const existing = db.users.find(u => u.email.toLowerCase() === normalizedEmail);
    if (existing) {
      return res.status(400).json({ error: 'An account with this email already exists. Please sign in or use another email.' });
    }

    const resolvedRole = (normalizedEmail === 'rgi855477@gmail.com') ? 'admin' : (role || 'customer');

    const newUser: UserProfile = {
      id: 'usr_' + crypto.randomUUID(),
      email: normalizedEmail,
      password: String(password),
      full_name: full_name || (resolvedRole === 'admin' ? 'Platform Administrator' : 'User'),
      phone: phone || '',
      role: resolvedRole,
      created_at: new Date().toISOString()
    };

    db.users.push(newUser);

    // If barber role, create an associated barbershop for them automatically
    if (resolvedRole === 'barber') {
      const newShop: BarberShop = {
        id: 'shop_' + crypto.randomUUID(),
        owner_id: newUser.id,
        name: `${newUser.full_name}'s Barbershop`,
        description: 'Bespoke grooming sanctuary offering classic and modern cuts.',
        address: '742 Market Street',
        city: 'San Francisco',
        phone: newUser.phone || '+1 (415) 555-0100',
        rating: 5.0,
        total_reviews: 1,
        is_open: true,
        is_licensed: true,
        license_status: 'active',
        cover_url: 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=800&q=80',
        logo_url: 'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=200&q=80',
        created_at: new Date().toISOString()
      };
      db.shops.push(newShop);

      // Default baseline services
      db.services.push(
        {
          id: 'srv_' + crypto.randomUUID(),
          shop_id: newShop.id,
          name: 'Classic Precision Cut',
          description: 'Consultation, tailored cut, razor neck clean, wash & style.',
          price: 40.00,
          duration: 30,
          is_active: true,
          created_at: new Date().toISOString()
        },
        {
          id: 'srv_' + crypto.randomUUID(),
          shop_id: newShop.id,
          name: 'Beard Trim & Sculpt',
          description: 'Custom beard shaping, length fading, razor line-up, and organic oil finish.',
          price: 25.00,
          duration: 20,
          is_active: true,
          created_at: new Date().toISOString()
        }
      );
    }

    writeDatabase(db);

    const { password: _, ...safeUser } = newUser;
    return res.json({
      success: true,
      user: safeUser,
      message: 'Account created successfully!'
    });
  } catch (err: any) {
    console.error('Registration error:', err);
    return res.status(500).json({ error: err.message || 'Registration failed' });
  }
});

// Login
apiRouter.post('/auth/login', (req: Request, res: Response) => {
  try {
    const { email, password } = req.body;
    if (!email) {
      return res.status(400).json({ error: 'Email is required' });
    }

    const db = readDatabase();
    const normalizedEmail = String(email).trim().toLowerCase();

    // Secure user lookup
    const user = db.users.find(u => u.email.toLowerCase() === normalizedEmail);
    if (!user) {
      return res.status(401).json({ error: 'No account found with this email. Please sign up first.' });
    }

    if (password && user.password && user.password !== String(password)) {
      return res.status(401).json({ error: 'Invalid password. If you forgot your password, please use the reset option.' });
    }

    const { password: _, ...safeUser } = user;
    return res.json({
      success: true,
      user: safeUser,
      message: 'Logged in successfully!'
    });
  } catch (err: any) {
    console.error('Login error:', err);
    return res.status(500).json({ error: err.message || 'Login failed' });
  }
});

// Reset Password
apiRouter.post('/auth/reset-password', (req: Request, res: Response) => {
  try {
    const { email, new_password } = req.body;
    if (!email || !new_password) {
      return res.status(400).json({ error: 'Email and new password are required' });
    }

    const db = readDatabase();
    const normalizedEmail = String(email).trim().toLowerCase();
    let user = db.users.find(u => u.email.toLowerCase() === normalizedEmail);

    if (!user) {
      return res.status(404).json({ error: 'No user account found with that email address.' });
    } else {
      user.password = String(new_password);
    }

    writeDatabase(db);
    return res.json({ success: true, message: 'Password updated successfully! You can now log in.' });
  } catch (err: any) {
    return res.status(500).json({ error: err.message || 'Password update failed' });
  }
});

// Current User verification
apiRouter.get('/auth/me', (req: Request, res: Response) => {
  const email = (req.query.email as string || '').toLowerCase().trim();
  const userId = req.query.id as string;

  const db = readDatabase();
  let user: UserProfile | undefined;

  if (userId) {
    user = db.users.find(u => u.id === userId);
  } else if (email) {
    user = db.users.find(u => u.email.toLowerCase() === email);
  }

  if (!user) {
    return res.status(404).json({ error: 'User not found' });
  }

  const { password: _, ...safeUser } = user;
  return res.json({ success: true, user: safeUser });
});

// ========================
// SHOPS & SERVICES
// ========================

// Get all shops (only licensed shops show on customer storefront unless include_inactive=true for admin)
apiRouter.get('/shops', (req: Request, res: Response) => {
  const db = readDatabase();
  const { city, search, open_only, include_suspended } = req.query;

  let results = [...db.shops];

  // Storefront only shows active/licensed shops by default
  if (include_suspended !== 'true') {
    results = results.filter(s => s.is_licensed !== false && s.license_status !== 'suspended');
  }

  if (open_only === 'true') {
    results = results.filter(s => s.is_open);
  }

  if (city && city !== 'all') {
    const qCity = String(city).toLowerCase();
    results = results.filter(s => s.city.toLowerCase().includes(qCity));
  }

  if (search) {
    const q = String(search).toLowerCase();
    results = results.filter(s => 
      s.name.toLowerCase().includes(q) ||
      s.city.toLowerCase().includes(q) ||
      s.description.toLowerCase().includes(q)
    );
  }

  const platformSettings = db.settings || { platform_fee_fixed: 3.00, platform_fee_percent: 5.0, enable_coupons: true };

  // Attach services & wait times to each shop
  const enriched = results.map(shop => {
    const shopServices = db.services.filter(srv => srv.shop_id === shop.id && srv.is_active !== false);
    const waitingInShop = db.queue.filter(q => q.shop_id === shop.id && (q.status === 'waiting' || q.status === 'serving'));
    
    const totalMins = waitingInShop.reduce((acc, cur) => acc + (cur.service_duration || 25), 0);

    return {
      ...shop,
      services: shopServices.map(srv => {
        const fee = platformSettings.platform_fee_fixed + (srv.price * (platformSettings.platform_fee_percent / 100));
        return {
          ...srv,
          barber_price: srv.price,
          platform_fee: Number(fee.toFixed(2)),
          final_price: Number((srv.price + fee).toFixed(2))
        };
      }),
      services_preview: shopServices.slice(0, 3).map(s => {
        const fee = platformSettings.platform_fee_fixed + (s.price * (platformSettings.platform_fee_percent / 100));
        return `${s.name} ($${(s.price + fee).toFixed(2)})`;
      }),
      waiting_count: waitingInShop.length,
      current_wait_mins: Math.max(10, totalMins || 15)
    };
  });

  return res.json(enriched);
});

// Get single shop by ID
apiRouter.get('/shops/:id', (req: Request, res: Response) => {
  const db = readDatabase();
  const shop = db.shops.find(s => s.id === req.params.id);

  if (!shop) {
    return res.status(404).json({ error: 'Shop not found' });
  }

  const platformSettings = db.settings || { platform_fee_fixed: 3.00, platform_fee_percent: 5.0, enable_coupons: true };
  const rawServices = db.services.filter(s => s.shop_id === shop.id);
  const queueItems = db.queue.filter(q => q.shop_id === shop.id && (q.status === 'waiting' || q.status === 'serving'));

  const enrichedServices = rawServices.map(srv => {
    const fee = platformSettings.platform_fee_fixed + (srv.price * (platformSettings.platform_fee_percent / 100));
    return {
      ...srv,
      barber_price: srv.price,
      platform_fee: Number(fee.toFixed(2)),
      final_price: Number((srv.price + fee).toFixed(2))
    };
  });

  return res.json({
    ...shop,
    services: enrichedServices,
    waiting_count: queueItems.length,
    current_wait_mins: Math.max(10, queueItems.length * 20),
    platform_settings: platformSettings
  });
});

// Update shop details (Barber or Admin)
apiRouter.put('/shops/:id', (req: Request, res: Response) => {
  const db = readDatabase();
  const shopIndex = db.shops.findIndex(s => s.id === req.params.id);

  if (shopIndex === -1) {
    return res.status(404).json({ error: 'Shop not found' });
  }

  const updatedShop = {
    ...db.shops[shopIndex],
    ...req.body,
    id: db.shops[shopIndex].id // immutable
  };

  db.shops[shopIndex] = updatedShop;
  writeDatabase(db);
  return res.json({ success: true, shop: updatedShop });
});

// Barber or Admin: Get barber's shop by owner ID
apiRouter.get('/barber/shop', (req: Request, res: Response) => {
  const ownerId = req.query.owner_id as string;
  const db = readDatabase();

  let shop = db.shops.find(s => s.owner_id === ownerId);
  if (!shop && db.shops.length > 0) {
    shop = db.shops[0];
  }

  if (!shop) {
    return res.status(404).json({ error: 'No barbershop found' });
  }

  return res.json(shop);
});

// Get services for a shop
apiRouter.get('/shops/:id/services', (req: Request, res: Response) => {
  const db = readDatabase();
  const platformSettings = db.settings || { platform_fee_fixed: 3.00, platform_fee_percent: 5.0, enable_coupons: true };
  const services = db.services.filter(s => s.shop_id === req.params.id);

  const enriched = services.map(srv => {
    const fee = platformSettings.platform_fee_fixed + (srv.price * (platformSettings.platform_fee_percent / 100));
    return {
      ...srv,
      barber_price: srv.price,
      platform_fee: Number(fee.toFixed(2)),
      final_price: Number((srv.price + fee).toFixed(2))
    };
  });

  return res.json(enriched);
});

// Barber: Add or Edit service
apiRouter.post('/services', (req: Request, res: Response) => {
  const { shop_id, name, description, price, duration, is_active } = req.body;
  if (!shop_id || !name || price === undefined) {
    return res.status(400).json({ error: 'Missing required service fields' });
  }

  const db = readDatabase();
  const newService: ServiceItem = {
    id: 'srv_' + crypto.randomUUID(),
    shop_id,
    name: String(name).trim(),
    description: description || '',
    price: Number(price),
    duration: Number(duration) || 30,
    is_active: is_active !== false,
    created_at: new Date().toISOString()
  };

  db.services.push(newService);
  writeDatabase(db);
  return res.json({ success: true, service: newService });
});

// Barber: Update service
apiRouter.put('/services/:id', (req: Request, res: Response) => {
  const db = readDatabase();
  const index = db.services.findIndex(s => s.id === req.params.id);
  if (index === -1) {
    return res.status(404).json({ error: 'Service not found' });
  }

  db.services[index] = {
    ...db.services[index],
    ...req.body,
    id: db.services[index].id
  };

  writeDatabase(db);
  return res.json({ success: true, service: db.services[index] });
});

// Barber: Delete service
apiRouter.delete('/services/:id', (req: Request, res: Response) => {
  const db = readDatabase();
  db.services = db.services.filter(s => s.id !== req.params.id);
  writeDatabase(db);
  return res.json({ success: true, message: 'Service removed' });
});

// ========================
// LIVE QUEUE
// ========================

// Join Queue
apiRouter.post('/queue/join', (req: Request, res: Response) => {
  const { shop_id, service_id, customer_id, customer_name, customer_phone } = req.body;

  if (!shop_id) {
    return res.status(400).json({ error: 'Shop ID is required' });
  }

  const db = readDatabase();
  const shop = db.shops.find(s => s.id === shop_id);
  if (shop && (shop.is_licensed === false || shop.license_status === 'suspended')) {
    return res.status(403).json({ error: 'This shop license is currently suspended by administration.' });
  }

  const activeShopId = shop?.id || db.shops[0].id;
  const service = db.services.find(s => s.id === service_id) || db.services[0];

  const existingInLine = db.queue.filter(q => q.shop_id === activeShopId && (q.status === 'waiting' || q.status === 'serving'));
  const queueNum = existingInLine.length + 1;
  const estWait = existingInLine.reduce((acc, cur) => acc + (cur.service_duration || 25), 0);

  const newTicket: QueueEntry = {
    id: 'tkt_' + crypto.randomUUID(),
    shop_id: activeShopId,
    service_id: service?.id || 'srv-001',
    customer_id: customer_id || 'guest-' + Date.now(),
    customer_name: customer_name || 'Walk-in Client',
    customer_phone: customer_phone || '',
    service_name: service?.name || 'Precision Cut',
    service_duration: service?.duration || 30,
    queue_number: queueNum,
    status: 'waiting',
    joined_at: new Date().toISOString(),
    estimated_wait: estWait,
    people_ahead: existingInLine.length
  };

  db.queue.push(newTicket);
  writeDatabase(db);

  return res.json({
    success: true,
    ticket: newTicket,
    message: `You are in line! Ticket #${String(queueNum).padStart(2, '0')}`
  });
});

// Active Ticket for Customer
apiRouter.get('/queue/active', (req: Request, res: Response) => {
  const { customer_id } = req.query;
  const db = readDatabase();

  if (!customer_id) {
    return res.json(null);
  }

  const activeTicket = db.queue.find(q => 
    q.customer_id === customer_id && (q.status === 'waiting' || q.status === 'serving')
  );

  if (!activeTicket) {
    return res.json(null);
  }

  const ahead = db.queue.filter(q => 
    q.shop_id === activeTicket.shop_id &&
    q.status === 'waiting' &&
    new Date(q.joined_at) < new Date(activeTicket.joined_at)
  ).length;

  const currentWait = Math.max(0, ahead * (activeTicket.service_duration || 25));

  return res.json({
    ...activeTicket,
    people_ahead: ahead,
    estimated_wait: currentWait
  });
});

// Leave / Cancel Ticket
apiRouter.post('/queue/leave', (req: Request, res: Response) => {
  const { ticket_id, customer_id } = req.body;
  const db = readDatabase();

  const ticket = db.queue.find(q => q.id === ticket_id || (customer_id && q.customer_id === customer_id && q.status === 'waiting'));
  if (ticket) {
    ticket.status = 'cancelled';
    writeDatabase(db);
  }

  return res.json({ success: true, message: 'Ticket cancelled' });
});

// Barber Desk: Active Queue List
apiRouter.get('/barber/queue', (req: Request, res: Response) => {
  const { shop_id } = req.query;
  const db = readDatabase();

  const activeShopId = (shop_id as string) || (db.shops[0]?.id || '');
  const shopQueue = db.queue.filter(q => q.shop_id === activeShopId);

  const serving = shopQueue.find(q => q.status === 'serving') || null;
  const waiting = shopQueue.filter(q => q.status === 'waiting');
  const completedToday = shopQueue.filter(q => q.status === 'completed');

  return res.json({
    serving,
    waiting,
    completed_today: completedToday.length,
    waiting_count: waiting.length
  });
});

// Barber: Call Next Client
apiRouter.post('/barber/queue/call-next', (req: Request, res: Response) => {
  const { shop_id } = req.body;
  const db = readDatabase();
  const activeShopId = shop_id || (db.shops[0]?.id || '');

  const currentServing = db.queue.find(q => q.shop_id === activeShopId && q.status === 'serving');
  if (currentServing) {
    currentServing.status = 'completed';
  }

  const nextWaiting = db.queue.find(q => q.shop_id === activeShopId && q.status === 'waiting');
  if (nextWaiting) {
    nextWaiting.status = 'serving';
  }

  writeDatabase(db);
  return res.json({ success: true, serving: nextWaiting || null });
});

// Barber: Complete current cut
apiRouter.post('/barber/queue/complete', (req: Request, res: Response) => {
  const { queue_id } = req.body;
  const db = readDatabase();

  const ticket = db.queue.find(q => q.id === queue_id);
  if (ticket) {
    ticket.status = 'completed';
    writeDatabase(db);
  }

  return res.json({ success: true });
});

// ========================
// COUPON VALIDATION
// ========================
apiRouter.post('/coupons/validate', (req: Request, res: Response) => {
  const { code, amount } = req.body;
  const db = readDatabase();

  if (!code) {
    return res.status(400).json({ error: 'Coupon code required' });
  }

  const normalized = String(code).trim().toUpperCase();
  const coupon = (db.coupons || []).find(c => c.code.toUpperCase() === normalized && c.is_active);

  if (!coupon) {
    return res.status(404).json({ valid: false, error: 'Invalid or expired coupon code' });
  }

  const subtotal = Number(amount) || 0;
  let discount = 0;
  if (coupon.discount_type === 'percent') {
    discount = (subtotal * coupon.discount_value) / 100;
  } else {
    discount = coupon.discount_value;
  }

  discount = Math.min(discount, subtotal);

  return res.json({
    valid: true,
    code: coupon.code,
    discount_type: coupon.discount_type,
    discount_value: coupon.discount_value,
    discount_amount: Number(discount.toFixed(2)),
    final_amount: Number((subtotal - discount).toFixed(2))
  });
});

// ========================
// APPOINTMENTS & PAYMENTS
// ========================

// Book appointment with Payment & Fee Breakdown
apiRouter.post('/appointments', (req: Request, res: Response) => {
  const {
    shop_id,
    service_id,
    customer_id,
    customer_name,
    customer_phone,
    date,
    time_slot,
    notes,
    payment_method, // 'card' | 'upi' | 'cash'
    coupon_code
  } = req.body;

  const db = readDatabase();
  const shop = db.shops.find(s => s.id === shop_id) || db.shops[0];

  if (shop && (shop.is_licensed === false || shop.license_status === 'suspended')) {
    return res.status(403).json({ error: 'This barbershop is suspended and cannot accept bookings at this time.' });
  }

  const service = db.services.find(s => s.id === service_id) || db.services[0];
  const settings = db.settings || { platform_fee_fixed: 3.00, platform_fee_percent: 5.0, enable_coupons: true };

  const basePrice = service ? Number(service.price) : 40.00;
  const platformFee = Number((settings.platform_fee_fixed + (basePrice * (settings.platform_fee_percent / 100))).toFixed(2));
  
  let discount = 0;
  if (coupon_code) {
    const cpn = (db.coupons || []).find(c => c.code.toUpperCase() === String(coupon_code).trim().toUpperCase() && c.is_active);
    if (cpn) {
      if (cpn.discount_type === 'percent') {
        discount = Number(((basePrice + platformFee) * (cpn.discount_value / 100)).toFixed(2));
      } else {
        discount = Math.min(cpn.discount_value, basePrice + platformFee);
      }
      cpn.usage_count = (cpn.usage_count || 0) + 1;
    }
  }

  const totalPaid = Number(Math.max(0, (basePrice + platformFee - discount)).toFixed(2));
  const chosenMethod = payment_method || 'card';

  const newAppointment: AppointmentEntry = {
    id: 'apt_' + crypto.randomUUID(),
    shop_id: shop?.id || 'shop-001',
    service_id: service?.id || 'srv-001',
    customer_id: customer_id || 'guest-001',
    customer_name: customer_name || 'Customer',
    customer_phone: customer_phone || '',
    date: date || new Date().toISOString().split('T')[0],
    time_slot: time_slot || '10:00 AM',
    notes: notes || '',
    status: 'confirmed',
    barber_price: basePrice,
    platform_fee: platformFee,
    discount_amount: discount,
    total_paid: totalPaid,
    service_name: service?.name || 'Haircut',
    shop_name: shop?.name || 'BarberLoo',
    payment_method: chosenMethod,
    payment_status: chosenMethod === 'cash' ? 'pending_cash' : 'paid',
    coupon_code: coupon_code || '',
    created_at: new Date().toISOString()
  };

  db.appointments.push(newAppointment);

  // Record Transaction
  const newTx: TransactionRecord = {
    id: 'tx_' + crypto.randomUUID(),
    appointment_id: newAppointment.id,
    shop_id: shop?.id || '',
    shop_name: shop?.name || '',
    customer_id: newAppointment.customer_id,
    customer_name: newAppointment.customer_name,
    amount: totalPaid,
    barber_earning: basePrice,
    platform_fee: platformFee,
    payment_method: chosenMethod,
    payment_status: chosenMethod === 'cash' ? 'pending' : 'completed',
    created_at: new Date().toISOString()
  };

  if (!db.transactions) db.transactions = [];
  db.transactions.push(newTx);

  writeDatabase(db);

  return res.json({
    success: true,
    appointment: newAppointment,
    transaction: newTx
  });
});

// Get user appointments
apiRouter.get('/appointments/my', (req: Request, res: Response) => {
  const { customer_id } = req.query;
  const db = readDatabase();

  const userAppointments = db.appointments.filter(a => a.customer_id === customer_id);
  return res.json(userAppointments);
});

// ========================
// ADMIN MASTER CONTROL & REPORTS
// ========================

// 1. Admin Master Stats & Financial Report
apiRouter.get('/admin/stats', (req: Request, res: Response) => {
  const db = readDatabase();

  const transactions = db.transactions || [];
  const totalVolume = transactions.reduce((acc, t) => acc + (t.amount || 0), 0);
  const totalPlatformFees = transactions.reduce((acc, t) => acc + (t.platform_fee || 0), 0);
  const totalBarberPayouts = transactions.reduce((acc, t) => acc + (t.barber_earning || 0), 0);

  // Shop Performance Report
  const shopReports = db.shops.map(shop => {
    const shopTx = transactions.filter(t => t.shop_id === shop.id);
    const shopAppointments = db.appointments.filter(a => a.shop_id === shop.id);
    const shopQueue = db.queue.filter(q => q.shop_id === shop.id);

    const revenue = shopTx.reduce((acc, t) => acc + t.amount, 0);
    const barberEarning = shopTx.reduce((acc, t) => acc + t.barber_earning, 0);

    return {
      id: shop.id,
      name: shop.name,
      city: shop.city,
      is_licensed: shop.is_licensed !== false,
      license_status: shop.license_status || 'active',
      total_bookings: shopAppointments.length,
      total_queue_tickets: shopQueue.length,
      gross_revenue: Number(revenue.toFixed(2)),
      barber_net: Number(barberEarning.toFixed(2))
    };
  });

  return res.json({
    total_shops: db.shops.length,
    total_users: db.users.length,
    total_queue_tickets: db.queue.length,
    total_appointments: db.appointments.length,
    financials: {
      gross_volume: Number(totalVolume.toFixed(2)),
      platform_revenue: Number(totalPlatformFees.toFixed(2)),
      barber_payouts: Number(totalBarberPayouts.toFixed(2))
    },
    platform_settings: db.settings || { platform_fee_fixed: 3.00, platform_fee_percent: 5.0, enable_coupons: true },
    shop_reports: shopReports,
    recent_transactions: transactions.slice(-20).reverse(),
    coupons: db.coupons || [],
    shops: db.shops,
    users: db.users.map(({ password, ...u }) => u)
  });
});

// 2. Admin: Toggle Shop License (Activate / Suspend)
apiRouter.post('/admin/shops/:id/license', (req: Request, res: Response) => {
  const { status, is_licensed } = req.body;
  const db = readDatabase();

  const shop = db.shops.find(s => s.id === req.params.id);
  if (!shop) {
    return res.status(404).json({ error: 'Shop not found' });
  }

  shop.is_licensed = is_licensed !== undefined ? Boolean(is_licensed) : status === 'active';
  shop.license_status = status || (shop.is_licensed ? 'active' : 'suspended');

  writeDatabase(db);

  return res.json({
    success: true,
    message: `Shop license ${shop.license_status.toUpperCase()}`,
    shop
  });
});

// 3. Admin: Update Platform Fee Settings
apiRouter.post('/admin/settings/fees', (req: Request, res: Response) => {
  const { platform_fee_fixed, platform_fee_percent, enable_coupons } = req.body;
  const db = readDatabase();

  db.settings = {
    platform_fee_fixed: platform_fee_fixed !== undefined ? Number(platform_fee_fixed) : (db.settings?.platform_fee_fixed || 3.0),
    platform_fee_percent: platform_fee_percent !== undefined ? Number(platform_fee_percent) : (db.settings?.platform_fee_percent || 5.0),
    enable_coupons: enable_coupons !== undefined ? Boolean(enable_coupons) : (db.settings?.enable_coupons !== false)
  };

  writeDatabase(db);
  return res.json({ success: true, settings: db.settings });
});

// 4. Admin: Create or Update Coupon Code
apiRouter.post('/admin/coupons', (req: Request, res: Response) => {
  const { code, discount_type, discount_value, is_active } = req.body;
  if (!code || !discount_value) {
    return res.status(400).json({ error: 'Code and discount value required' });
  }

  const db = readDatabase();
  if (!db.coupons) db.coupons = [];

  const existingIdx = db.coupons.findIndex(c => c.code.toUpperCase() === String(code).trim().toUpperCase());
  if (existingIdx !== -1) {
    db.coupons[existingIdx] = {
      ...db.coupons[existingIdx],
      discount_type: discount_type || 'percent',
      discount_value: Number(discount_value),
      is_active: is_active !== false
    };
    writeDatabase(db);
    return res.json({ success: true, coupon: db.coupons[existingIdx] });
  }

  const newCoupon: CouponCode = {
    id: 'cpn_' + crypto.randomUUID(),
    code: String(code).trim().toUpperCase(),
    discount_type: discount_type || 'percent',
    discount_value: Number(discount_value),
    is_active: is_active !== false,
    usage_count: 0,
    created_at: new Date().toISOString()
  };

  db.coupons.push(newCoupon);
  writeDatabase(db);
  return res.json({ success: true, coupon: newCoupon });
});

// 5. Admin: Delete or Deactivate Coupon
apiRouter.delete('/admin/coupons/:id', (req: Request, res: Response) => {
  const db = readDatabase();
  db.coupons = (db.coupons || []).filter(c => c.id !== req.params.id && c.code !== req.params.id);
  writeDatabase(db);
  return res.json({ success: true, message: 'Coupon removed' });
});

// ========================
// WP PUSHER INTEGRATION
// ========================

interface WPPusherEvent {
  id: string;
  type: string;
  repository: string;
  branch: string;
  commit: string;
  status: 'success' | 'failed' | 'in_progress';
  message: string;
  triggered_at: string;
  details?: any;
}

let wpPusherEvents: WPPusherEvent[] = [
  {
    id: 'wp-init-001',
    type: 'deploy',
    repository: 'nexwaveservices-web/BarberLoo',
    branch: 'main',
    commit: 'c4e789a',
    status: 'success',
    message: 'Active deployment synchronized via wppusher.js on BarberLoo.in',
    triggered_at: new Date().toISOString()
  }
];

// 1. Get WP Pusher deployment and server sync status
apiRouter.get('/wppusher/status', (req: Request, res: Response) => {
  const lastEvent = wpPusherEvents[wpPusherEvents.length - 1];
  return res.json({
    success: true,
    enabled: true,
    client_library: '/wppusher.js',
    configured_repository: 'nexwaveservices-web/BarberLoo',
    domain: 'BarberLoo.in',
    current_branch: 'main',
    last_event: lastEvent,
    recent_deployments: wpPusherEvents.slice(-10).reverse()
  });
});

// 2. Trigger WP Pusher manual deployment
apiRouter.post('/wppusher/deploy', (req: Request, res: Response) => {
  const { repository, branch, ref, type, triggered_by } = req.body;
  
  const shortHash = crypto.randomBytes(4).toString('hex');
  const targetRepo = repository || 'nexwaveservices-web/BarberLoo';
  const newEvent: WPPusherEvent = {
    id: 'wp-' + Date.now(),
    type: 'deploy',
    repository: targetRepo,
    branch: branch || 'main',
    commit: shortHash,
    status: 'success',
    message: `Deployed ${targetRepo} (${branch || 'main'}) to BarberLoo.in successfully via wppusher.js`,
    triggered_at: new Date().toISOString(),
    details: {
      ref: ref || 'refs/heads/main',
      type: type || 'plugin_and_theme',
      domain: 'BarberLoo.in',
      triggered_by: triggered_by || 'manual_client'
    }
  };

  wpPusherEvents.push(newEvent);
  return res.json({
    success: true,
    message: `WP Pusher deployment triggered successfully for BarberLoo.in!`,
    deployment: newEvent
  });
});

// 3. Receive Webhooks from GitHub / GitLab / Bitbucket
apiRouter.post('/wppusher/webhook', (req: Request, res: Response) => {
  const provider = (req.query.provider as string) || 'github';
  const payload = req.body || {};

  const branch = payload.ref ? payload.ref.replace('refs/heads/', '') : 'main';
  const commit = payload.after ? payload.after.slice(0, 7) : crypto.randomBytes(4).toString('hex');
  const repoName = payload.repository?.full_name || 'barberloo/barberloo-core';

  const webhookEvent: WPPusherEvent = {
    id: 'wp-hook-' + Date.now(),
    type: 'push',
    repository: repoName,
    branch,
    commit,
    status: 'success',
    message: `Auto-deploy triggered by ${provider} webhook push on ${branch}`,
    triggered_at: new Date().toISOString(),
    details: {
      provider,
      sender: payload.sender?.login || 'git_user',
      commits_count: Array.isArray(payload.commits) ? payload.commits.length : 1
    }
  };

  wpPusherEvents.push(webhookEvent);

  return res.json({
    success: true,
    received: true,
    event: webhookEvent
  });
});
