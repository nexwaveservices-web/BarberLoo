<?php
/**
 * Template: Customer Live Queue Boarding Pass
 */
get_header();

$shop_id = sanitize_text_field($_GET['shop_id'] ?? '00000000-0000-0000-0000-000000000001');
?>

<main class="container" style="max-width: 640px; padding-top: 48px; padding-bottom: 80px;">
  
  <div style="text-align: center; margin-bottom: 28px;">
    <div style="display: inline-flex; align-items: center; gap: 8px; background: var(--primary-light); padding: 6px 16px; border-radius: var(--radius-full); color: var(--primary); font-size: 0.825rem; font-weight: 700; text-transform: uppercase; margin-bottom: 12px;">
      <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #059669;"></span>
      Live Queue Active
    </div>
    <h1 style="font-size: 2rem; font-weight: 800; color: var(--text-main);">BarberLoo Heritage Lounge</h1>
    <p style="color: var(--text-muted); font-size: 1rem; margin-top: 2px;">Master Precision Haircut (30 mins)</p>
  </div>

  <!-- Live Boarding Pass / Ticket Card -->
  <div class="card" style="background: #ffffff; border-radius: var(--radius-xl); border: 1px solid var(--border-subtle); box-shadow: var(--shadow-lg); overflow: hidden; padding: 0;">
    
    <!-- Top Ticket Header -->
    <div style="background: #ffffff; padding: 28px; border-bottom: 1px dashed var(--border-strong); display: flex; justify-content: space-between; align-items: center;">
      <div>
        <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">YOUR TICKET NUMBER</span>
        <div style="font-size: 3.8rem; font-weight: 900; line-height: 1; color: var(--primary); margin-top: 6px;">#03</div>
      </div>
      <div style="text-align: right;">
        <span class="badge badge-waiting" style="font-size: 0.85rem; padding: 6px 14px;">Next In Line</span>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 6px;">Est. Call: ~10 mins</div>
      </div>
    </div>

    <!-- Ticket Details Body -->
    <div style="padding: 28px;">
      <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 24px;">
        <div style="background: var(--bg-subtle); padding: 14px 18px; border-radius: var(--radius-lg);">
          <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">PEOPLE AHEAD OF YOU</span>
          <div style="font-size: 1.8rem; font-weight: 800; color: var(--text-main); margin-top: 4px;">1 Client</div>
        </div>

        <div style="background: var(--bg-subtle); padding: 14px 18px; border-radius: var(--radius-lg);">
          <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">ESTIMATED WAIT</span>
          <div style="font-size: 1.8rem; font-weight: 800; color: var(--primary); margin-top: 4px;">~10 mins</div>
        </div>
      </div>

      <div style="background: #eff6ff; border-left: 4px solid #2563eb; padding: 14px 18px; border-radius: 0 var(--radius-md) var(--radius-md) 0; margin-bottom: 24px;">
        <p style="font-size: 0.875rem; color: #1e40af; margin: 0; line-height: 1.5;">
          <strong>Notification Active:</strong> You will receive an SMS and screen flash alert when the barber prepares the chair for you.
        </p>
      </div>

      <div style="display: flex; gap: 12px; justify-content: center;">
        <a href="<?php echo esc_url(home_url('/customer/discover')); ?>" class="btn btn-secondary">Explore Other Shops</a>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-outline">Back to Home</a>
      </div>
    </div>
  </div>

</main>

<?php
get_footer();
