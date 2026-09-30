/**
 * BarberLoo Discovery Engine
 * Handles shop queries, location search, filtering, and dynamic card generation
 */

import { getSupabase } from './supabase.js';

// Pre-seeded high quality fallback shops in case database hasn't been seeded yet
export const DEFAULT_SHOPS = [
  {
    id: 'a1b2c3d4-e5f6-4a1b-8c2d-111111111111',
    name: 'The Golden Razor Lounge',
    description: 'Award-winning heritage barbershop specializing in precision fades, traditional hot towel shaves, and executive grooming.',
    address: '442 Market Street, Downtown',
    city: 'San Francisco',
    rating: 4.95,
    total_reviews: 128,
    is_open: true,
    cover_url: 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=800&q=80',
    logo_url: 'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=200&q=80',
    services_preview: ['Signature Cut ($45)', 'Hot Towel Shave ($40)', 'Beard Sculpt ($28)'],
    current_wait_mins: 15,
    waiting_count: 2
  },
  {
    id: 'b2c3d4e5-f6a1-4b2c-9d3e-222222222222',
    name: 'Crown & Blade Studio',
    description: 'Contemporary urban salon offering luxury scissor cuts, textured styling, and beard contouring in an upscale lounge.',
    address: '880 Valencia Street, Mission District',
    city: 'San Francisco',
    rating: 4.88,
    total_reviews: 94,
    is_open: true,
    cover_url: 'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=800&q=80',
    logo_url: 'https://images.unsplash.com/photo-1621605815971-fbc98d665033?auto=format&fit=crop&w=200&q=80',
    services_preview: ['Skin Fade ($50)', 'Express Buzz ($25)', 'Scalp Treatment ($65)'],
    current_wait_mins: 35,
    waiting_count: 4
  },
  {
    id: 'c3d4e5f6-a1b2-4c3d-0e4f-333333333333',
    name: 'Artisan Fade Lab',
    description: 'Specialist barber crew dedicated to modern tapering, hair tattoo detailing, and organic scalp maintenance.',
    address: '1204 Broadway Avenue',
    city: 'Oakland',
    rating: 4.92,
    total_reviews: 76,
    is_open: true,
    cover_url: 'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?auto=format&fit=crop&w=800&q=80',
    logo_url: 'https://images.unsplash.com/photo-1599566150163-29194dcaad36?auto=format&fit=crop&w=200&q=80',
    services_preview: ['Artisan Taper ($42)', 'Beard Lineup ($25)', 'Father & Son ($80)'],
    current_wait_mins: 0,
    waiting_count: 0
  }
];

export async function fetchShops(searchQuery = '', city = '', filterOpenOnly = false) {
  const sb = getSupabase();
  if (!sb) return DEFAULT_SHOPS;

  try {
    let query = sb.from('shops').select(`
      *,
      services:services(*)
    `);

    if (city && city !== 'all') {
      query = query.ilike('city', `%${city}%`);
    }

    if (filterOpenOnly) {
      query = query.eq('is_open', true);
    }

    const { data, error } = await query;

    if (error || !data || data.length === 0) {
      // Fallback to default shops filtered
      return DEFAULT_SHOPS.filter(shop => {
        const matchesQuery = !searchQuery || 
          shop.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
          shop.city.toLowerCase().includes(searchQuery.toLowerCase());
        const matchesCity = !city || city === 'all' || shop.city.toLowerCase().includes(city.toLowerCase());
        const matchesOpen = !filterOpenOnly || shop.is_open;
        return matchesQuery && matchesCity && matchesOpen;
      });
    }

    let results = data;
    if (searchQuery) {
      const q = searchQuery.toLowerCase();
      results = results.filter(s => s.name.toLowerCase().includes(q) || s.city.toLowerCase().includes(q));
    }

    return results;
  } catch (err) {
    console.error('Error fetching shops from Supabase:', err);
    return DEFAULT_SHOPS;
  }
}

export function renderShopCard(shop) {
  const isOpen = shop.is_open !== false;
  const statusBadge = isOpen 
    ? `<span class="badge badge-open">Open Now</span>` 
    : `<span class="badge badge-closed">Closed</span>`;

  return `
    <div class="card card-clickable" style="padding:0; overflow:hidden; display:flex; flex-direction:column; cursor:pointer;" onclick="window.location.href='/customer/shop-details.html?id=${shop.id}'">
      <div style="height: 180px; width: 100%; position: relative; background: #1e293b;">
        <img src="${shop.cover_url || 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=600&q=80'}" alt="${shop.name}" style="width: 100%; height: 100%; object-fit: cover;">
        <div style="position: absolute; top: 12px; right: 12px;">
          ${statusBadge}
        </div>
        <div style="position: absolute; bottom: -20px; left: 16px; width: 52px; height: 52px; border-radius: var(--radius-md); border: 3px solid var(--bg-card); overflow: hidden; background: #000; box-shadow: var(--shadow-md);">
          <img src="${shop.logo_url || 'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=150&q=80'}" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
      </div>

      <div style="padding: 28px 18px 18px 18px; display:flex; flex-direction:column; flex: 1;">
        <div style="display:flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px;">
          <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-main);">${shop.name}</h3>
          <div style="display:flex; align-items:center; gap: 4px; font-weight: 600; font-size: 0.9rem; color: #f59e0b;">
            <span>★</span>
            <span>${Number(shop.rating || 5.0).toFixed(1)}</span>
            <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: 400;">(${shop.total_reviews || 0})</span>
          </div>
        </div>

        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 12px; display:flex; align-items:center; gap: 4px;">
          <span>📍</span> ${shop.address}, ${shop.city}
        </p>

        <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 16px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
          ${shop.description || 'Premium grooming and modern styling.'}
        </p>

        <!-- Live Queue Highlight -->
        <div style="background: rgba(217, 119, 6, 0.08); border: 1px solid rgba(217, 119, 6, 0.2); border-radius: var(--radius-sm); padding: 8px 12px; margin-bottom: 16px; display:flex; justify-content: space-between; align-items: center; font-size: 0.825rem;">
          <div style="display:flex; align-items:center; gap:6px;">
            <span style="color: var(--primary); font-weight: 700;">Live Queue:</span>
            <span>${shop.waiting_count !== undefined ? shop.waiting_count + ' waiting' : 'Active'}</span>
          </div>
          <div style="color: var(--text-muted);">
            Est. Wait: <strong style="color: var(--text-main);">${shop.current_wait_mins !== undefined ? shop.current_wait_mins + 'm' : '~15m'}</strong>
          </div>
        </div>

        <div style="margin-top: auto; display: flex; gap: 8px;">
          <a href="/customer/shop-details.html?id=${shop.id}" class="btn btn-secondary btn-sm" style="flex: 1;" onclick="event.stopPropagation();">
            View Details
          </a>
          <a href="/customer/shop-details.html?id=${shop.id}&action=queue" class="btn btn-primary btn-sm" style="flex: 1;" onclick="event.stopPropagation();">
            Join Queue ⚡
          </a>
        </div>
      </div>
    </div>
  `;
}
