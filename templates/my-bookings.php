<?php
/**
 * Template: My Bookings & Queue Passes
 */
get_header();
?>

<main class="container" style="max-width: 760px; padding-top: 48px; padding-bottom: 80px;">
  
  <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 28px;">
    <div>
      <h1 style="font-size: 2rem; font-weight: 800; color: var(--text-main);">My Bookings & Passes</h1>
      <p style="color: var(--text-muted); font-size: 0.95rem;">Track upcoming chair reservations and active live queue tickets.</p>
    </div>
    <a href="<?php echo esc_url(home_url('/customer/discover')); ?>" class="btn btn-primary btn-sm">+ New Booking</a>
  </div>

  <div style="display: flex; flex-direction: column; gap: 18px;">
    
    <!-- Active Booking Card -->
    <div class="card" style="padding: 24px; border-radius: var(--radius-xl); border-left: 4px solid var(--primary); background: #ffffff;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
        <div>
          <span class="badge badge-open" style="margin-bottom: 8px;">Confirmed Appointment</span>
          <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main);">Master Precision Haircut</h3>
          <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 2px;">BarberLoo Heritage Lounge • 500 Howard Street</p>
        </div>
        <div style="text-align: right;">
          <div style="font-size: 1.2rem; font-weight: 800; color: var(--primary);">Today @ 2:00 PM</div>
          <div style="font-size: 0.8rem; color: var(--text-muted);">Duration: 30 mins</div>
        </div>
      </div>

      <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-subtle); flex-wrap: wrap; gap: 10px;">
        <span style="font-size: 0.85rem; color: #059669; font-weight: 700;">✓ Chair Reserved & Guaranteed</span>
        <div style="display: flex; gap: 8px;">
          <a href="<?php echo esc_url(home_url('/customer/queue')); ?>" class="btn btn-secondary btn-sm">Check Live Line</a>
          <a href="<?php echo esc_url(home_url('/customer/discover')); ?>" class="btn btn-outline btn-sm">Book Another</a>
        </div>
      </div>
    </div>

  </div>

</main>

<?php
get_footer();
