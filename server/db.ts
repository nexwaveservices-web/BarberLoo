import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const DATA_DIR = path.resolve(__dirname, 'data');
const DB_FILE = path.join(DATA_DIR, 'barberloo_db.json');

export interface UserProfile {
  id: string;
  email: string;
  password?: string;
  full_name: string;
  phone: string;
  role: 'customer' | 'barber' | 'admin';
  created_at: string;
}

export interface BarberShop {
  id: string;
  owner_id: string;
  name: string;
  description: string;
  address: string;
  city: string;
  phone: string;
  rating: number;
  total_reviews: number;
  is_open: boolean;
  cover_url: string;
  logo_url: string;
  created_at: string;
}

export interface ServiceItem {
  id: string;
  shop_id: string;
  name: string;
  description: string;
  price: number;
  duration: number;
  created_at: string;
}

export interface QueueEntry {
  id: string;
  shop_id: string;
  service_id: string;
  customer_id: string;
  customer_name: string;
  customer_phone?: string;
  service_name: string;
  service_duration: number;
  queue_number: number;
  status: 'waiting' | 'serving' | 'completed' | 'cancelled';
  joined_at: string;
  estimated_wait: number;
  people_ahead: number;
}

export interface AppointmentEntry {
  id: string;
  shop_id: string;
  service_id: string;
  customer_id: string;
  customer_name: string;
  customer_phone: string;
  date: string;
  time_slot: string;
  notes?: string;
  status: 'confirmed' | 'completed' | 'cancelled';
  price: number;
  service_name: string;
  shop_name: string;
  created_at: string;
}

export interface DatabaseSchema {
  users: UserProfile[];
  shops: BarberShop[];
  services: ServiceItem[];
  queue: QueueEntry[];
  appointments: AppointmentEntry[];
}

const DEFAULT_DB: DatabaseSchema = {
  users: [
    {
      id: 'admin-001',
      email: 'rgi855477@gmail.com',
      password: 'password123',
      full_name: 'Platform Administrator',
      phone: '+1 (415) 555-0199',
      role: 'admin',
      created_at: new Date().toISOString()
    },
    {
      id: 'barber-001',
      email: 'barber@barberloo.com',
      password: 'password123',
      full_name: 'Marcus Vance',
      phone: '+1 (415) 555-0144',
      role: 'barber',
      created_at: new Date().toISOString()
    },
    {
      id: 'customer-001',
      email: 'customer@barberloo.com',
      password: 'password123',
      full_name: 'David Miller',
      phone: '+1 (415) 555-0188',
      role: 'customer',
      created_at: new Date().toISOString()
    }
  ],
  shops: [
    {
      id: '00000000-0000-0000-0000-000000000001',
      owner_id: 'barber-001',
      name: 'BarberLoo Heritage Lounge',
      description: 'Luxury grooming parlour specializing in master scissor fades, traditional Japanese hot-towel straight razor shaves, and artisanal beard grooming.',
      address: '500 Howard Street, Suite 100',
      city: 'San Francisco',
      phone: '+1 (415) 555-0192',
      rating: 4.95,
      total_reviews: 48,
      is_open: true,
      cover_url: 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=800&q=80',
      logo_url: 'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=200&q=80',
      created_at: new Date().toISOString()
    },
    {
      id: 'shop-002',
      owner_id: 'barber-001',
      name: 'The Crown & Scissor',
      description: 'Award-winning Downtown aesthetic salon offering precision taper fades, texture crop cuts, and facial steam treatments.',
      address: '124 Post Street',
      city: 'San Francisco',
      phone: '+1 (415) 555-0181',
      rating: 4.92,
      total_reviews: 36,
      is_open: true,
      cover_url: 'https://images.unsplash.com/photo-1512690459411-b9245aed614b?auto=format&fit=crop&w=800&q=80',
      logo_url: 'https://images.unsplash.com/photo-1599566150163-29194dcaad36?auto=format&fit=crop&w=200&q=80',
      created_at: new Date().toISOString()
    }
  ],
  services: [
    {
      id: 'srv-001',
      shop_id: '00000000-0000-0000-0000-000000000001',
      name: 'Master Precision Haircut',
      description: 'Tailored haircut with consultation, razor neck clean, wash, and premium clay finish.',
      price: 45,
      duration: 30,
      created_at: new Date().toISOString()
    },
    {
      id: 'srv-002',
      shop_id: '00000000-0000-0000-0000-000000000001',
      name: 'Japanese Hot Towel Straight Razor Shave',
      description: 'Pre-shave eucalyptus oil, 2 hot steam towels, lather massage, and ultra-smooth straight blade shave.',
      price: 40,
      duration: 30,
      created_at: new Date().toISOString()
    },
    {
      id: 'srv-003',
      shop_id: '00000000-0000-0000-0000-000000000001',
      name: 'Beard Sculpt & Razor Line-up',
      description: 'Custom beard shaping, length fading, trimmer edge alignment, and warm balm treatment.',
      price: 28,
      duration: 20,
      created_at: new Date().toISOString()
    },
    {
      id: 'srv-004',
      shop_id: '00000000-0000-0000-0000-000000000001',
      name: 'The Executive Royal Package (Cut + Shave)',
      description: 'Complete signature treatment: Precision cut, hot towel shave, beard oil, and facial tonic refresh.',
      price: 75,
      duration: 50,
      created_at: new Date().toISOString()
    }
  ],
  queue: [
    {
      id: 'q-001',
      shop_id: '00000000-0000-0000-0000-000000000001',
      service_id: 'srv-001',
      customer_id: 'cust-walkin-1',
      customer_name: 'Arthur Pendelton',
      customer_phone: '+1 (415) 555-0911',
      service_name: 'Master Precision Haircut',
      service_duration: 30,
      queue_number: 1,
      status: 'serving',
      joined_at: new Date(Date.now() - 15 * 60000).toISOString(),
      estimated_wait: 0,
      people_ahead: 0
    },
    {
      id: 'q-002',
      shop_id: '00000000-0000-0000-0000-000000000001',
      service_id: 'srv-003',
      customer_id: 'cust-walkin-2',
      customer_name: 'Leo Chen',
      customer_phone: '+1 (415) 555-0422',
      service_name: 'Beard Sculpt & Razor Line-up',
      service_duration: 20,
      queue_number: 2,
      status: 'waiting',
      joined_at: new Date(Date.now() - 8 * 60000).toISOString(),
      estimated_wait: 15,
      people_ahead: 1
    }
  ],
  appointments: []
};

function ensureDbFile(): DatabaseSchema {
  if (!fs.existsSync(DATA_DIR)) {
    fs.mkdirSync(DATA_DIR, { recursive: true });
  }

  if (!fs.existsSync(DB_FILE)) {
    fs.writeFileSync(DB_FILE, JSON.stringify(DEFAULT_DB, null, 2), 'utf-8');
    return DEFAULT_DB;
  }

  try {
    const raw = fs.readFileSync(DB_FILE, 'utf-8');
    const parsed = JSON.parse(raw);
    return {
      users: parsed.users || DEFAULT_DB.users,
      shops: parsed.shops || DEFAULT_DB.shops,
      services: parsed.services || DEFAULT_DB.services,
      queue: parsed.queue || DEFAULT_DB.queue,
      appointments: parsed.appointments || DEFAULT_DB.appointments
    };
  } catch (err) {
    console.error('Failed reading DB file, recreating default:', err);
    fs.writeFileSync(DB_FILE, JSON.stringify(DEFAULT_DB, null, 2), 'utf-8');
    return DEFAULT_DB;
  }
}

export function readDatabase(): DatabaseSchema {
  return ensureDbFile();
}

export function writeDatabase(db: DatabaseSchema): void {
  ensureDbFile();
  fs.writeFileSync(DB_FILE, JSON.stringify(db, null, 2), 'utf-8');
}
