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
  is_licensed: boolean;
  license_status: 'active' | 'suspended' | 'pending';
  cover_url: string;
  logo_url: string;
  created_at: string;
}

export interface ServiceItem {
  id: string;
  shop_id: string;
  name: string;
  description: string;
  price: number; // Base barber price in INR (₹)
  duration: number;
  is_active?: boolean;
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
  barber_price: number;
  platform_fee: number;
  discount_amount: number;
  total_paid: number;
  service_name: string;
  shop_name: string;
  payment_method: 'upi' | 'card' | 'cash';
  payment_status: 'paid' | 'pending_cash' | 'refunded';
  coupon_code?: string;
  created_at: string;
}

export interface CouponCode {
  id: string;
  code: string;
  discount_type: 'percent' | 'fixed';
  discount_value: number;
  min_order?: number;
  max_discount?: number;
  is_active: boolean;
  usage_count: number;
  created_at: string;
}

export interface TransactionRecord {
  id: string;
  appointment_id?: string;
  shop_id: string;
  shop_name: string;
  customer_id: string;
  customer_name: string;
  amount: number;
  barber_earning: number;
  platform_fee: number;
  payment_method: 'upi' | 'card' | 'cash';
  payment_status: 'completed' | 'pending' | 'refunded';
  created_at: string;
}

export interface PlatformSettings {
  currency: string; // 'INR'
  currency_symbol: string; // '₹'
  platform_fee_fixed: number; // e.g. ₹20 flat fee
  platform_fee_percent: number; // e.g. 5%
  enable_coupons: boolean;
}

export interface DatabaseSchema {
  users: UserProfile[];
  shops: BarberShop[];
  services: ServiceItem[];
  queue: QueueEntry[];
  appointments: AppointmentEntry[];
  coupons: CouponCode[];
  transactions: TransactionRecord[];
  settings: PlatformSettings;
}

const DEFAULT_DB: DatabaseSchema = {
  settings: {
    currency: 'INR',
    currency_symbol: '₹',
    platform_fee_fixed: 20.00,
    platform_fee_percent: 5.0,
    enable_coupons: true
  },
  coupons: [
    {
      id: 'cpn-001',
      code: 'DESI50',
      discount_type: 'fixed',
      discount_value: 50,
      is_active: true,
      usage_count: 32,
      created_at: new Date().toISOString()
    },
    {
      id: 'cpn-002',
      code: 'BARBER15',
      discount_type: 'percent',
      discount_value: 15,
      is_active: true,
      usage_count: 18,
      created_at: new Date().toISOString()
    }
  ],
  transactions: [
    {
      id: 'tx-ind-001',
      appointment_id: 'app-seed-01',
      shop_id: '00000000-0000-0000-0000-000000000001',
      shop_name: 'Royal Heritage Salon & Barbers',
      customer_id: 'cust-001',
      customer_name: 'Rahul Sharma',
      amount: 320,
      barber_earning: 300,
      platform_fee: 20,
      payment_method: 'upi',
      payment_status: 'completed',
      created_at: new Date(Date.now() - 3600000 * 2).toISOString()
    },
    {
      id: 'tx-ind-002',
      appointment_id: 'app-seed-02',
      shop_id: 'shop-002',
      shop_name: 'The Crown & Scissor Mens Lounge',
      customer_id: 'cust-002',
      customer_name: 'Aman Verma',
      amount: 470,
      barber_earning: 450,
      platform_fee: 20,
      payment_method: 'upi',
      payment_status: 'completed',
      created_at: new Date(Date.now() - 3600000 * 6).toISOString()
    },
    {
      id: 'tx-ind-003',
      appointment_id: 'app-seed-03',
      shop_id: 'shop-003',
      shop_name: 'Urban Cutters & Beard Studio',
      customer_id: 'cust-003',
      customer_name: 'Vikram Malhotra',
      amount: 220,
      barber_earning: 200,
      platform_fee: 20,
      payment_method: 'cash',
      payment_status: 'completed',
      created_at: new Date(Date.now() - 3600000 * 18).toISOString()
    }
  ],
  users: [
    {
      id: 'admin-001',
      email: 'rgi855477@gmail.com',
      password: 'password123',
      full_name: 'Platform Administrator',
      phone: '+91 98765 43210',
      role: 'admin',
      created_at: new Date().toISOString()
    },
    {
      id: 'barber-001',
      email: 'barber@barberloo.com',
      password: 'password123',
      full_name: 'Arjun Mehta',
      phone: '+91 98111 22334',
      role: 'barber',
      created_at: new Date().toISOString()
    },
    {
      id: 'customer-001',
      email: 'customer@barberloo.com',
      password: 'password123',
      full_name: 'Rahul Sharma',
      phone: '+91 99887 76655',
      role: 'customer',
      created_at: new Date().toISOString()
    }
  ],
  shops: [
    {
      id: '00000000-0000-0000-0000-000000000001',
      owner_id: 'barber-001',
      name: 'Royal Heritage Salon & Barbers',
      description: 'Premier Indian men grooming salon specializing in precision fades, herbal face de-tan, traditional hot towel straight razor shaves, and Ayurvedic head massage.',
      address: 'Shop 14, Connaught Place, Inner Circle',
      city: 'Delhi NCR',
      phone: '+91 98111 22334',
      rating: 4.95,
      total_reviews: 142,
      is_open: true,
      is_licensed: true,
      license_status: 'active',
      cover_url: 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=800&q=80',
      logo_url: 'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=200&q=80',
      created_at: new Date().toISOString()
    },
    {
      id: 'shop-002',
      owner_id: 'barber-001',
      name: 'The Crown & Scissor Mens Lounge',
      description: 'Modern luxury grooming destination in Indiranagar. Clean tapers, beard grooming, organic charcoal facial cleanup, and head massage.',
      address: '100ft Road, Indiranagar',
      city: 'Bengaluru',
      phone: '+91 98450 12345',
      rating: 4.92,
      total_reviews: 98,
      is_open: true,
      is_licensed: true,
      license_status: 'active',
      cover_url: 'https://images.unsplash.com/photo-1512690459411-b9245aed614b?auto=format&fit=crop&w=800&q=80',
      logo_url: 'https://images.unsplash.com/photo-1599566150163-29194dcaad36?auto=format&fit=crop&w=200&q=80',
      created_at: new Date().toISOString()
    },
    {
      id: 'shop-003',
      owner_id: 'barber-001',
      name: 'Urban Cutters & Beard Studio',
      description: 'Bespoke styling studio offering Korean-style scissor cuts, skin fade, D-Tan express glow, and beard shaping.',
      address: 'Linking Road, Bandra West',
      city: 'Mumbai',
      phone: '+91 98200 54321',
      rating: 4.88,
      total_reviews: 76,
      is_open: true,
      is_licensed: true,
      license_status: 'active',
      cover_url: 'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?auto=format&fit=crop&w=800&q=80',
      logo_url: 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=200&q=80',
      created_at: new Date().toISOString()
    }
  ],
  services: [
    {
      id: 'srv-001',
      shop_id: '00000000-0000-0000-0000-000000000001',
      name: 'Classic Precision Haircut & Wash',
      description: 'Personalized haircut consultation, wash, neck shave clean, and styling clay.',
      price: 250,
      duration: 30,
      is_active: true,
      created_at: new Date().toISOString()
    },
    {
      id: 'srv-002',
      shop_id: '00000000-0000-0000-0000-000000000001',
      name: 'Beard Trim, Razor Edge & Shaping',
      description: 'Custom beard fading, trimmer edge alignment, and warm herbal balm finish.',
      price: 150,
      duration: 20,
      is_active: true,
      created_at: new Date().toISOString()
    },
    {
      id: 'srv-003',
      shop_id: '00000000-0000-0000-0000-000000000001',
      name: 'Ayurvedic Hot Oil Head Massage (Champi)',
      description: 'Traditional soothing 20-min Indian head, neck and shoulder acupressure massage.',
      price: 200,
      duration: 20,
      is_active: true,
      created_at: new Date().toISOString()
    },
    {
      id: 'srv-004',
      shop_id: '00000000-0000-0000-0000-000000000001',
      name: 'Royal Grooming Combo (Cut + Beard + D-Tan)',
      description: 'Complete grooming package: Precision cut, beard styling, herbal face D-Tan, and steam.',
      price: 550,
      duration: 50,
      is_active: true,
      created_at: new Date().toISOString()
    },
    {
      id: 'srv-005',
      shop_id: 'shop-002',
      name: 'Executive Haircut & Styling',
      description: 'Precision scissor and taper fade with hair wash and blowout.',
      price: 350,
      duration: 30,
      is_active: true,
      created_at: new Date().toISOString()
    },
    {
      id: 'srv-006',
      shop_id: 'shop-002',
      name: 'Charcoal Face Cleanup & Scrub',
      description: 'Deep pore cleansing, blackhead removal, steam, and mint hydration pack.',
      price: 400,
      duration: 35,
      is_active: true,
      created_at: new Date().toISOString()
    }
  ],
  queue: [
    {
      id: 'q-001',
      shop_id: '00000000-0000-0000-0000-000000000001',
      service_id: 'srv-001',
      customer_id: 'cust-walkin-1',
      customer_name: 'Sameer Khan',
      customer_phone: '+91 98111 98765',
      service_name: 'Classic Precision Haircut & Wash',
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
      service_id: 'srv-002',
      customer_id: 'cust-walkin-2',
      customer_name: 'Karan Patel',
      customer_phone: '+91 97222 33445',
      service_name: 'Beard Trim, Razor Edge & Shaping',
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

  // Force re-seed with Indian data if old DB has US dollars/cities
  let needsSeed = !fs.existsSync(DB_FILE);
  if (!needsSeed) {
    try {
      const raw = fs.readFileSync(DB_FILE, 'utf-8');
      if (raw.includes('San Francisco') || !raw.includes('Delhi NCR') || !raw.includes('INR')) {
        needsSeed = true;
      }
    } catch {
      needsSeed = true;
    }
  }

  if (needsSeed) {
    fs.writeFileSync(DB_FILE, JSON.stringify(DEFAULT_DB, null, 2), 'utf-8');
    return DEFAULT_DB;
  }

  try {
    const raw = fs.readFileSync(DB_FILE, 'utf-8');
    const parsed = JSON.parse(raw);
    return {
      users: parsed.users || DEFAULT_DB.users,
      shops: (parsed.shops || DEFAULT_DB.shops).map((s: BarberShop) => ({
        ...s,
        is_licensed: s.is_licensed !== false,
        license_status: s.license_status || 'active'
      })),
      services: parsed.services || DEFAULT_DB.services,
      queue: parsed.queue || DEFAULT_DB.queue,
      appointments: parsed.appointments || DEFAULT_DB.appointments,
      coupons: parsed.coupons || DEFAULT_DB.coupons,
      transactions: parsed.transactions || DEFAULT_DB.transactions,
      settings: parsed.settings || DEFAULT_DB.settings
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
