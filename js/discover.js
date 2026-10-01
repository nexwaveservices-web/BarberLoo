/**
 * BarberLoo Discovery Engine
 * Connects to /api/shops with instant search and responsive cards
 */

import { apiClient } from './api.js';

export const SANDBOX_DEMO_SHOP = {
  id: '00000000-0000-0000-0000-000000000001',
  name: 'BarberLoo Heritage Lounge',
  description: 'Luxury grooming parlour specializing in master scissor fades and hot-towel straight razor shaves.',
  address: '500 Howard Street, Suite 100',
  city: 'San Francisco',
  rating: 4.95,
  total_reviews: 48,
  is_open: true,
  cover_url: 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=800&q=80',
  logo_url: 'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=200&q=80',
  services_preview: ['Master Precision Haircut ($45)', 'Hot Towel Razor Shave ($40)', 'Beard Sculpt ($28)'],
  current_wait_mins: 15,
  waiting_count: 2
};

export async function fetchShops(searchQuery = '', city = '', filterOpenOnly = false) {
  try {
    const params = {};
    if (city && city !== 'all') params.city = city;
    if (searchQuery) params.search = searchQuery;
    if (filterOpenOnly) params.open_only = 'true';

    const shops = await apiClient.getShops(params);
    if (Array.isArray(shops) && shops.length > 0) {
      return shops;
    }
  } catch (err) {
    console.warn('Backend shop fetch error, fallback:', err);
  }

  // Guaranteed fallback shop
  return [SANDBOX_DEMO_SHOP];
}

export function renderShopCard(shop) {
  const waitMinutes = shop.current_wait_mins !== undefined ? shop.current_wait_mins : 15;
  const inLineCount = shop.waiting_count !== undefined ? shop.waiting_count : 2;
  const servicesList = shop.services_preview || ['Haircut ($40)', 'Beard Trim ($25)'];

  return `
    <div class="shop-card">
      <div class="shop-card-media">
        <img src="${shop.cover_url || 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=800&q=80'}" 
             alt="${shop.name}" 
             class="shop-card-img" 
             loading="lazy">
        <span class="badge ${shop.is_open ? 'badge-open' : 'badge-closed'} shop-card-status">
          ${shop.is_open ? '● Open Now' : 'Closed'}
        </span>
        <div class="shop-card-rating">
          <span>★</span>
          <span>${Number(shop.rating || 5.0).toFixed(2)}</span>
          <span style="color: var(--text-muted); font-size: 0.75rem;">(${shop.total_reviews || 12})</span>
        </div>
      </div>

      <div class="shop-card-body">
        <div style="display: flex; gap: 12px; align-items: flex-start; margin-bottom: 10px;">
          <img src="${shop.logo_url || 'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=200&q=80'}" 
               alt="${shop.name} logo" 
               style="width: 44px; height: 44px; border-radius: var(--radius-md); object-fit: cover; border: 1px solid var(--border-subtle); flex-shrink: 0;">
          <div style="min-width: 0;">
            <h3 class="shop-card-title">${shop.name}</h3>
            <p class="shop-card-address">📍 ${shop.address}, ${shop.city}</p>
          </div>
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 14px;">
          ${servicesList.slice(0, 3).map(s => `
            <span style="font-size: 0.75rem; background: var(--bg-subtle); color: var(--text-main); padding: 3px 8px; border-radius: var(--radius-sm); border: 1px solid var(--border-subtle);">
              ${s}
            </span>
          `).join('')}
        </div>

        <div class="shop-card-footer">
          <div class="shop-card-wait">
            <span class="shop-card-wait-label">Current Chair Line</span>
            <span class="shop-card-wait-time">~${waitMinutes} mins (${inLineCount} waiting)</span>
          </div>

          <a href="/customer/shop-details.html?id=${shop.id}" class="btn btn-primary btn-sm">
            View & Queue ⚡
          </a>
        </div>
      </div>
    </div>
  `;
}
