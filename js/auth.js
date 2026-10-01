/**
 * BarberLoo Auth Manager
 * Seamless dual authentication: Primary backend REST API with Supabase session sync & local persistence
 */

import { apiClient } from './api.js';
import { getSupabase } from './supabase.js';

export function showToast(message, type = 'info') {
  let container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.textContent = message;
  container.appendChild(toast);

  setTimeout(() => {
    toast.remove();
  }, 4000);
}

export const ADMIN_EMAILS = [
  'rgi855477@gmail.com'
];

// Get current logged-in user
export async function getCurrentUser() {
  // Check local active session first
  try {
    const sessionStr = localStorage.getItem('barberloo_session');
    if (sessionStr) {
      const parsed = JSON.parse(sessionStr);
      if (parsed && parsed.email) {
        const isAdmin = ADMIN_EMAILS.includes(parsed.email.toLowerCase());
        const role = isAdmin ? 'admin' : (parsed.role || 'customer');
        return {
          id: parsed.id,
          email: parsed.email,
          profile: {
            role,
            full_name: parsed.full_name || (isAdmin ? 'Platform Administrator' : 'User'),
            phone: parsed.phone || ''
          }
        };
      }
    }
  } catch (e) {
    console.warn('Local session parse error:', e);
  }

  // Check Supabase session as secondary sync
  const sb = getSupabase();
  if (sb) {
    try {
      const { data: { session } } = await sb.auth.getSession();
      if (session) {
        const isAdmin = ADMIN_EMAILS.includes(session.user.email?.toLowerCase());
        return {
          id: session.user.id,
          email: session.user.email,
          profile: {
            role: isAdmin ? 'admin' : 'customer',
            full_name: session.user.user_metadata?.full_name || 'User',
            phone: session.user.user_metadata?.phone || ''
          }
        };
      }
    } catch (e) {}
  }

  return null;
}

// Route protector for pages requiring specific roles
export async function requireAuth(allowedRoles = []) {
  const user = await getCurrentUser();

  if (!user) {
    sessionStorage.setItem('redirect_after_login', window.location.pathname + window.location.search);
    window.location.href = '/login.html';
    return null;
  }

  if (allowedRoles.length > 0 && !allowedRoles.includes(user.profile.role)) {
    if (user.profile.role === 'barber') {
      window.location.href = '/barber/dashboard.html';
    } else if (user.profile.role === 'admin') {
      window.location.href = '/admin/dashboard.html';
    } else {
      window.location.href = '/customer/discover.html';
    }
    return null;
  }

  return user;
}

// Redirect user after login based on role
export function redirectByRole(role) {
  const pendingRedirect = sessionStorage.getItem('redirect_after_login');
  if (pendingRedirect) {
    sessionStorage.removeItem('redirect_after_login');
    window.location.href = pendingRedirect;
    return;
  }

  if (role === 'barber') {
    window.location.href = '/barber/dashboard.html';
  } else if (role === 'admin') {
    window.location.href = '/admin/dashboard.html';
  } else {
    window.location.href = '/customer/discover.html';
  }
}

// Sign up
export async function signUp(email, password, fullName, phone, role = 'customer') {
  const normalizedEmail = email.trim().toLowerCase();
  const isAdmin = ADMIN_EMAILS.includes(normalizedEmail);
  const resolvedRole = isAdmin ? 'admin' : role;

  // 1. Register with backend API
  const response = await apiClient.register(normalizedEmail, password, fullName, phone, resolvedRole);
  
  if (response && response.user) {
    localStorage.setItem('barberloo_session', JSON.stringify({
      id: response.user.id,
      email: response.user.email,
      role: response.user.role,
      full_name: response.user.full_name,
      phone: response.user.phone
    }));

    // Secondary async sync to Supabase without blocking user flow
    const sb = getSupabase();
    if (sb) {
      sb.auth.signUp({
        email: normalizedEmail,
        password: password,
        options: { data: { full_name: fullName, phone, role: resolvedRole } }
      }).catch(() => {});
    }

    return response;
  }

  throw new Error('Registration failed');
}

// Sign in
export async function signIn(email, password) {
  const normalizedEmail = email.trim().toLowerCase();
  
  // 1. Authenticate with backend API
  const response = await apiClient.login(normalizedEmail, password);

  if (response && response.user) {
    localStorage.setItem('barberloo_session', JSON.stringify({
      id: response.user.id,
      email: response.user.email,
      role: response.user.role,
      full_name: response.user.full_name,
      phone: response.user.phone
    }));

    // Secondary Supabase login attempt
    const sb = getSupabase();
    if (sb) {
      sb.auth.signInWithPassword({ email: normalizedEmail, password }).catch(() => {});
    }

    return response;
  }

  throw new Error('Login failed');
}

// Reset Password
export async function resetPasswordDirectly(email, newPassword) {
  const normalizedEmail = email.trim().toLowerCase();
  return await apiClient.resetPassword(normalizedEmail, newPassword);
}

// Sign out
export async function signOut() {
  localStorage.removeItem('barberloo_session');
  localStorage.removeItem('barberloo_local_session');

  const sb = getSupabase();
  if (sb) {
    try {
      await sb.auth.signOut();
    } catch (e) {}
  }
  window.location.href = '/login.html';
}

// Inject user navigation actions
export async function setupNavigation() {
  const navContainer = document.getElementById('nav-actions');
  if (!navContainer) return;

  const user = await getCurrentUser();

  if (user) {
    let dashboardLink = '/customer/discover.html';
    if (user.profile?.role === 'barber') dashboardLink = '/barber/dashboard.html';
    if (user.profile?.role === 'admin') dashboardLink = '/admin/dashboard.html';

    navContainer.innerHTML = `
      <div style="display: flex; align-items: center; gap: 12px;">
        <a href="${dashboardLink}" class="btn btn-secondary btn-sm" style="display: flex; align-items: center; gap: 6px;">
          <span>👤</span>
          <span>${user.profile?.full_name || 'My Portal'}</span>
        </a>
        <button id="btn-global-logout" class="btn btn-outline btn-sm">Sign Out</button>
      </div>
    `;

    document.getElementById('btn-global-logout')?.addEventListener('click', signOut);
  } else {
    navContainer.innerHTML = `
      <a href="/login.html" class="btn btn-outline btn-sm">Log In</a>
      <a href="/signup.html" class="btn btn-primary btn-sm">Sign Up</a>
    `;
  }
}
