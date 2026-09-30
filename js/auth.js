/**
 * BarberLoo Auth Manager
 * Manages Supabase session tracking, role-based page guards, login, signup, logout
 */

import { getSupabase } from './supabase.js';

// Show notification toast
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

// Get current logged-in user
export async function getCurrentUser() {
  const sb = getSupabase();
  if (!sb) return null;

  try {
    const { data: { session }, error } = await sb.auth.getSession();
    if (error || !session) return null;

    // Fetch user profile to retrieve role
    const { data: profile } = await sb
      .from('profiles')
      .select('*')
      .eq('id', session.user.id)
      .single();

    return {
      ...session.user,
      profile: profile || { role: 'customer', full_name: 'Customer' }
    };
  } catch (err) {
    console.error('Error fetching current user:', err);
    return null;
  }
}

// Route protector for pages requiring specific roles
export async function requireAuth(allowedRoles = []) {
  const user = await getCurrentUser();

  if (!user) {
    // Save return path
    sessionStorage.setItem('redirect_after_login', window.location.pathname + window.location.search);
    window.location.href = '/login.html';
    return null;
  }

  if (allowedRoles.length > 0 && !allowedRoles.includes(user.profile.role)) {
    // Redirect to correct dashboard based on role
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

// Redirect user after login based on their role
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
  const sb = getSupabase();
  if (!sb) throw new Error('Supabase client not initialized');

  const { data, error } = await sb.auth.signUp({
    email,
    password,
    options: {
      data: {
        full_name: fullName,
        phone,
        role
      }
    }
  });

  if (error) throw error;
  return data;
}

// Sign in
export async function signIn(email, password) {
  const sb = getSupabase();
  if (!sb) throw new Error('Supabase client not initialized');

  const { data, error } = await sb.auth.signInWithPassword({
    email,
    password
  });

  if (error) throw error;
  return data;
}

// Sign out
export async function signOut() {
  const sb = getSupabase();
  if (sb) {
    await sb.auth.signOut();
  }
  window.location.href = '/login.html';
}

// Helper to update navigation bar based on auth state
export async function setupNavigation() {
  const user = await getCurrentUser();
  const navActions = document.getElementById('nav-actions');
  if (!navActions) return;

  if (user) {
    const role = user.profile?.role || 'customer';
    let dashboardLink = '/customer/discover.html';
    if (role === 'barber') dashboardLink = '/barber/dashboard.html';
    if (role === 'admin') dashboardLink = '/admin/dashboard.html';

    navActions.innerHTML = `
      <a href="${dashboardLink}" class="btn btn-secondary btn-sm">
        <span>Dashboard</span>
      </a>
      <a href="/customer/profile.html" class="nav-link" style="display:flex; align-items:center; gap:6px;">
        <span style="font-weight:600; color:var(--text-main);">${user.profile?.full_name || 'Account'}</span>
      </a>
      <button id="btn-logout" class="btn btn-outline btn-sm">Sign Out</button>
    `;

    document.getElementById('btn-logout')?.addEventListener('click', () => {
      signOut();
    });
  } else {
    navActions.innerHTML = `
      <a href="/login.html" class="btn btn-outline btn-sm">Log In</a>
      <a href="/signup.html" class="btn btn-primary btn-sm">Get Started</a>
    `;
  }
}

// Auto-run navigation state
if (typeof document !== 'undefined') {
  document.addEventListener('DOMContentLoaded', () => {
    setupNavigation();
  });
}
