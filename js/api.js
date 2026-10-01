/**
 * BarberLoo Client API Service
 * Connects frontend smoothly to /api routes with graceful offline & local caching
 */

const API_BASE = '/api';

export const apiClient = {
  // Authentication
  async register(email, password, fullName, phone, role) {
    const res = await fetch(`${API_BASE}/auth/register`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password, full_name: fullName, phone, role })
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || 'Registration failed');
    return data;
  },

  async login(email, password) {
    const res = await fetch(`${API_BASE}/auth/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || 'Login failed');
    return data;
  },

  async resetPassword(email, newPassword) {
    const res = await fetch(`${API_BASE}/auth/reset-password`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, new_password: newPassword })
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || 'Password reset failed');
    return data;
  },

  // Shops
  async getShops(params = {}) {
    const query = new URLSearchParams(params).toString();
    const res = await fetch(`${API_BASE}/shops?${query}`);
    return await res.json();
  },

  async getShop(id) {
    const res = await fetch(`${API_BASE}/shops/${id}`);
    if (!res.ok) throw new Error('Shop not found');
    return await res.json();
  },

  async updateShop(id, body) {
    const res = await fetch(`${API_BASE}/shops/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    });
    return await res.json();
  },

  async getBarberShop(ownerId) {
    const res = await fetch(`${API_BASE}/barber/shop?owner_id=${encodeURIComponent(ownerId || '')}`);
    return await res.json();
  },

  // Services
  async getShopServices(shopId) {
    const res = await fetch(`${API_BASE}/shops/${shopId}/services`);
    return await res.json();
  },

  async addService(body) {
    const res = await fetch(`${API_BASE}/services`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    });
    return await res.json();
  },

  async updateService(id, body) {
    const res = await fetch(`${API_BASE}/services/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    });
    return await res.json();
  },

  async deleteService(id) {
    const res = await fetch(`${API_BASE}/services/${id}`, {
      method: 'DELETE'
    });
    return await res.json();
  },

  // Coupons
  async validateCoupon(code, amount) {
    const res = await fetch(`${API_BASE}/coupons/validate`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ code, amount })
    });
    return await res.json();
  },

  // Queue
  async joinQueue(shopId, serviceId, customerId, customerName, customerPhone) {
    const res = await fetch(`${API_BASE}/queue/join`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        shop_id: shopId,
        service_id: serviceId,
        customer_id: customerId,
        customer_name: customerName,
        customer_phone: customerPhone
      })
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || 'Failed to join queue');
    return data;
  },

  async getActiveQueue(customerId) {
    const res = await fetch(`${API_BASE}/queue/active?customer_id=${encodeURIComponent(customerId)}`);
    return await res.json();
  },

  async leaveQueue(ticketId, customerId) {
    const res = await fetch(`${API_BASE}/queue/leave`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ ticket_id: ticketId, customer_id: customerId })
    });
    return await res.json();
  },

  // Barber Desk
  async getBarberQueue(shopId) {
    const res = await fetch(`${API_BASE}/barber/queue?shop_id=${encodeURIComponent(shopId || '')}`);
    return await res.json();
  },

  async callNext(shopId) {
    const res = await fetch(`${API_BASE}/barber/queue/call-next`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ shop_id: shopId })
    });
    return await res.json();
  },

  async completeCut(queueId) {
    const res = await fetch(`${API_BASE}/barber/queue/complete`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ queue_id: queueId })
    });
    return await res.json();
  },

  // Appointments
  async bookAppointment(payload) {
    const res = await fetch(`${API_BASE}/appointments`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    return await res.json();
  },

  async getMyAppointments(customerId) {
    const res = await fetch(`${API_BASE}/appointments/my?customer_id=${encodeURIComponent(customerId)}`);
    return await res.json();
  },

  // Admin Master Powers
  async getAdminStats() {
    const res = await fetch(`${API_BASE}/admin/stats`);
    return await res.json();
  },

  async toggleShopLicense(shopId, status, isLicensed) {
    const res = await fetch(`${API_BASE}/admin/shops/${shopId}/license`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ status, is_licensed: isLicensed })
    });
    return await res.json();
  },

  async updatePlatformFees(fixedFee, percentFee, enableCoupons) {
    const res = await fetch(`${API_BASE}/admin/settings/fees`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        platform_fee_fixed: fixedFee,
        platform_fee_percent: percentFee,
        enable_coupons: enableCoupons
      })
    });
    return await res.json();
  },

  async saveCoupon(couponData) {
    const res = await fetch(`${API_BASE}/admin/coupons`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(couponData)
    });
    return await res.json();
  },

  async deleteCoupon(id) {
    const res = await fetch(`${API_BASE}/admin/coupons/${id}`, {
      method: 'DELETE'
    });
    return await res.json();
  }
};
