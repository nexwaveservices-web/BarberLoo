/**
 * BarberLoo - Centralized Supabase Client Instance
 * Configured with user's Supabase project credentials.
 * Exposes window.barberLooSupabase for all modules.
 */

// Supabase configuration
export const SUPABASE_URL = "https://ihmbxkliilxxsghnlqwt.supabase.co";
export const SUPABASE_ANON_KEY = "sb_publishable_ZLgrNDGgs29r2dhV0yLFVQ_4Vx9cbFL";

let supabaseClient = null;

export function getSupabase() {
  if (supabaseClient) return supabaseClient;

  if (typeof window !== "undefined" && window.supabase) {
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

// Auto-initialize when available
if (typeof window !== "undefined") {
  if (window.supabase) {
    getSupabase();
  } else {
    window.addEventListener("DOMContentLoaded", () => {
      if (window.supabase) getSupabase();
    });
  }
}

export default getSupabase;
