/**
 * BarberLoo - Centralized Supabase Client Instance
 * Configured with user's Supabase project credentials.
 * Uses official ES module import from @supabase/supabase-js with fallback to window.supabase.
 */

import { createClient } from '@supabase/supabase-js';

// User's Live Supabase Configuration
export const SUPABASE_URL = "https://ihmbxkliilxxsghnlqwt.supabase.co";
export const SUPABASE_ANON_KEY = "sb_publishable_ZLgrNDGgs29r2dhV0yLFVQ_4Vx9cbFL";

let supabaseClient = null;

export function getSupabase() {
  if (supabaseClient) return supabaseClient;

  try {
    if (typeof createClient === 'function') {
      supabaseClient = createClient(SUPABASE_URL, SUPABASE_ANON_KEY, {
        auth: {
          persistSession: true,
          autoRefreshToken: true,
          detectSessionInUrl: true,
        },
      });
      if (typeof window !== 'undefined') {
        window.barberLooSupabase = supabaseClient;
      }
      return supabaseClient;
    }
  } catch (err) {
    console.warn("Module createClient failed, falling back to window.supabase", err);
  }

  if (typeof window !== "undefined" && window.supabase && typeof window.supabase.createClient === 'function') {
    supabaseClient = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY, {
      auth: {
        persistSession: true,
        autoRefreshToken: true,
        detectSessionInUrl: true,
      },
    });
    window.barberLooSupabase = supabaseClient;
    return supabaseClient;
  }

  return null;
}

// Auto-initialize
if (typeof window !== "undefined") {
  getSupabase();
}

export default getSupabase;
