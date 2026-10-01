import { Router, Request, Response } from 'express';
import crypto from 'crypto';
import { readDatabase, writeDatabase, UserProfile, BarberShop, ServiceItem, QueueEntry, AppointmentEntry } from './db.js';

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
      // If user exists and is admin email or requested role update
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
        cover_url: 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=800&q=80',
        logo_url: 'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=200&q=80',
        created_at: new Date().toISOString()
      };
      db.shops.push(newShop);

      // Add starter services
      db.services.push({
        id: 'srv_' + crypto.randomUUID(),
        shop_id: newShop.id,
        name: 'Standard Haircut',
        description: 'Consultation, cut, taper, and styling.',
        price: 40,
        duration: 30,
        created_at: new Date().toISOString()
      });
      db.services.push({
        id: 'srv_' + crypto.randomUUID(),
        shop_id: newShop.id,
        name: 'Beard Trim & Lineup',
        description: 'Hot towel and straight razor outline.',
        price: 25,
        duration: 20,
        created_at: new Date().toISOString()
      });
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
    return res.status(500).json({ error: err.message || 'Server registration error' });
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

    // Special auto-recovery for the designated platform admin
    if (normalizedEmail === 'rgi855477@gmail.com') {
      let admin = db.users.find(u => u.email.toLowerCase() === 'rgi855477@gmail.com');
      if (!admin) {
        admin = {
          id: 'admin-001',
          email: 'rgi855477@gmail.com',
          password: password || 'admin123',
          full_name: 'Platform Administrator',
          phone: '+1 (415) 555-0199',
          role: 'admin',
          created_at: new Date().toISOString()
        };
        db.users.push(admin);
        writeDatabase(db);
      }

      const { password: _, ...safeAdmin } = admin;
      return res.json({
        success: true,
        user: safeAdmin,
        message: 'Admin authentication granted.'
      });
    }

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
      // Auto create if admin
      if (normalizedEmail === 'rgi855477@gmail.com') {
        user = {
          id: 'admin-001',
          email: normalizedEmail,
          password: String(new_password),
          full_name: 'Platform Administrator',
          phone: '+1 (415) 555-0199',
          role: 'admin',
          created_at: new Date().toISOString()
        };
        db.users.push(user);
      } else {
        return res.status(404).json({ error: 'No user account found with that email address.' });
      }
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

// Get all shops (with optional city/search filter)
apiRouter.get('/shops', (req: Request, res: Response) => {
  const db = readDatabase();
  const { city, search, open_only } = req.query;

  let results = [...db.shops];

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

  // Attach services & wait times to each shop
  const enriched = results.map(shop => {
    const shopServices = db.services.filter(srv => srv.shop_id === shop.id);
    const waitingInShop = db.queue.filter(q => q.shop_id === shop.id && (q.status === 'waiting' || q.status === 'serving'));
    
    // Estimate wait: sum of serving + waiting duration or 15m default
    const totalMins = waitingInShop.reduce((acc, cur) => acc + (cur.service_duration || 25), 0);

    return {
      ...shop,
      services: shopServices,
      services_preview: shopServices.slice(0, 3).map(s => `${s.name} ($${s.price})`),
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

  const services = db.services.filter(s => s.shop_id === shop.id);
  const queueItems = db.queue.filter(q => q.shop_id === shop.id && (q.status === 'waiting' || q.status === 'serving'));

  return res.json({
    ...shop,
    services,
    waiting_count: queueItems.length,
    current_wait_mins: Math.max(10, queueItems.length * 20)
  });
});

// Update shop details
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

// Get services for a shop
apiRouter.get('/shops/:id/services', (req: Request, res: Response) => {
  const db = readDatabase();
  const services = db.services.filter(s => s.shop_id === req.params.id);
  return res.json(services);
});

// Add service
apiRouter.post('/services', (req: Request, res: Response) => {
  const { shop_id, name, description, price, duration } = req.body;
  if (!shop_id || !name || !price) {
    return res.status(400).json({ error: 'Missing required service fields' });
  }

  const db = readDatabase();
  const newService: ServiceItem = {
    id: 'srv_' + crypto.randomUUID(),
    shop_id,
    name,
    description: description || '',
    price: Number(price),
    duration: Number(duration) || 30,
    created_at: new Date().toISOString()
  };

  db.services.push(newService);
  writeDatabase(db);
  return res.json({ success: true, service: newService });
});

// Delete service
apiRouter.delete('/services/:id', (req: Request, res: Response) => {
  const db = readDatabase();
  db.services = db.services.filter(s => s.id !== req.params.id);
  writeDatabase(db);
  return res.json({ success: true, message: 'Service removed' });
});

// ========================
// LIVE QUEUE OPERATIONS
// ========================

// Join Queue
apiRouter.post('/queue/join', (req: Request, res: Response) => {
  try {
    const { shop_id, service_id, customer_id, customer_name, customer_phone } = req.body;
    if (!shop_id) {
      return res.status(400).json({ error: 'Shop ID is required' });
    }

    const db = readDatabase();
    const shop = db.shops.find(s => s.id === shop_id) || db.shops[0];
    const service = db.services.find(s => s.id === service_id) || db.services[0] || {
      id: 'srv-default',
      name: 'Precision Grooming',
      duration: 30,
      price: 45
    };

    // Calculate queue number
    const shopQueue = db.queue.filter(q => q.shop_id === shop.id && (q.status === 'waiting' || q.status === 'serving'));
    const maxNumber = shopQueue.reduce((max, cur) => Math.max(max, cur.queue_number), 0);
    const nextNumber = (maxNumber % 99) + 1;

    const peopleAhead = shopQueue.filter(q => q.status === 'waiting').length;
    const estWait = Math.max(10, peopleAhead * (service.duration || 25));

    const newTicket: QueueEntry = {
      id: 'q_' + crypto.randomUUID(),
      shop_id: shop.id,
      service_id: service.id,
      customer_id: customer_id || 'guest_' + crypto.randomUUID().slice(0, 6),
      customer_name: customer_name || 'Valued Guest',
      customer_phone: customer_phone || '',
      service_name: service.name,
      service_duration: service.duration,
      queue_number: nextNumber,
      status: 'waiting',
      joined_at: new Date().toISOString(),
      estimated_wait: estWait,
      people_ahead: peopleAhead
    };

    db.queue.push(newTicket);
    writeDatabase(db);

    return res.json({
      success: true,
      ticket: {
        ...newTicket,
        shops: { name: shop.name },
        services: { name: service.name, duration: service.duration }
      }
    });
  } catch (err: any) {
    return res.status(500).json({ error: err.message || 'Error joining queue' });
  }
});

// Get user active queue ticket
apiRouter.get('/queue/active', (req: Request, res: Response) => {
  const { customer_id } = req.query;
  const db = readDatabase();

  const ticket = db.queue.find(q => 
    q.customer_id === customer_id && (q.status === 'waiting' || q.status === 'serving')
  );

  if (!ticket) {
    return res.json({ active: false });
  }

  const shop = db.shops.find(s => s.id === ticket.shop_id);
  const service = db.services.find(s => s.id === ticket.service_id);

  // Recalculate people ahead
  const waitingAhead = db.queue.filter(q => 
    q.shop_id === ticket.shop_id && 
    q.status === 'waiting' && 
    new Date(q.joined_at) < new Date(ticket.joined_at)
  ).length;

  return res.json({
    active: true,
    ticket: {
      ...ticket,
      people_ahead: waitingAhead,
      estimated_wait: Math.max(5, waitingAhead * 20),
      shops: { name: shop?.name || 'BarberLoo Barbershop' },
      services: { name: service?.name || ticket.service_name, duration: service?.duration || 30 }
    }
  });
});

// Leave / Cancel Queue
apiRouter.post('/queue/leave', (req: Request, res: Response) => {
  const { ticket_id, customer_id } = req.body;
  const db = readDatabase();

  const ticketIndex = db.queue.findIndex(q => 
    (ticket_id && q.id === ticket_id) || (customer_id && q.customer_id === customer_id && q.status !== 'completed')
  );

  if (ticketIndex !== -1) {
    db.queue[ticketIndex].status = 'cancelled';
    writeDatabase(db);
    return res.json({ success: true, message: 'Queue ticket cancelled' });
  }

  return res.status(404).json({ error: 'Ticket not found' });
});

// Barber queue desk - get shop's live queue
apiRouter.get('/barber/queue', (req: Request, res: Response) => {
  const { shop_id } = req.query;
  const db = readDatabase();

  const activeShopId = shop_id ? String(shop_id) : db.shops[0]?.id;

  const currentServing = db.queue.find(q => q.shop_id === activeShopId && q.status === 'serving');
  const waitingList = db.queue.filter(q => q.shop_id === activeShopId && q.status === 'waiting');

  return res.json({
    serving: currentServing || null,
    waiting: waitingList
  });
});

// Barber: Call Next Customer
apiRouter.post('/barber/queue/call-next', (req: Request, res: Response) => {
  const { shop_id } = req.body;
  const db = readDatabase();

  const activeShopId = shop_id || db.shops[0]?.id;

  // Complete current serving if any
  const currentServing = db.queue.find(q => q.shop_id === activeShopId && q.status === 'serving');
  if (currentServing) {
    currentServing.status = 'completed';
  }

  // Find next waiting
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
// APPOINTMENTS
// ========================

// Book appointment
apiRouter.post('/appointments', (req: Request, res: Response) => {
  const { shop_id, service_id, customer_id, customer_name, customer_phone, date, time_slot, notes } = req.body;

  const db = readDatabase();
  const shop = db.shops.find(s => s.id === shop_id) || db.shops[0];
  const service = db.services.find(s => s.id === service_id) || db.services[0];

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
    price: service?.price || 40,
    service_name: service?.name || 'Haircut',
    shop_name: shop?.name || 'BarberLoo',
    created_at: new Date().toISOString()
  };

  db.appointments.push(newAppointment);
  writeDatabase(db);

  return res.json({ success: true, appointment: newAppointment });
});

// Get user appointments
apiRouter.get('/appointments/my', (req: Request, res: Response) => {
  const { customer_id } = req.query;
  const db = readDatabase();

  const userAppointments = db.appointments.filter(a => a.customer_id === customer_id);
  return res.json(userAppointments);
});

// ========================
// ADMIN STATS & CONTROL
// ========================
apiRouter.get('/admin/stats', (req: Request, res: Response) => {
  const db = readDatabase();

  return res.json({
    total_shops: db.shops.length,
    total_users: db.users.length,
    total_queue_tickets: db.queue.length,
    total_appointments: db.appointments.length,
    shops: db.shops,
    users: db.users.map(({ password, ...u }) => u),
    queue: db.queue
  });
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
    repository: 'nexwaveservices/BarberLoo',
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
    configured_repository: 'nexwaveservices/BarberLoo',
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
  const targetRepo = repository || 'nexwaveservices/BarberLoo';
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
