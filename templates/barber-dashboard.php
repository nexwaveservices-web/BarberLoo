<?php
/**
 * Template: Barber PRO Hub & Queue Desk
 */
get_header();
?>

<main class="container" style="padding-top: 40px; padding-bottom: 80px;">
  
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 12px;">
    <div>
      <div style="display: flex; align-items: center; gap: 10px;">
        <h1 style="font-size: 2rem; font-weight: 800; color: var(--text-main);">Barber Operations Desk</h1>
        <span class="badge badge-open">Realtime Sync Active</span>
      </div>
      <p style="color: var(--text-muted); font-size: 0.95rem;">Manage active seats, call waiting clients, and trigger automatic queue progression.</p>
    </div>

    <div style="display: flex; gap: 10px;">
      <a href="<?php echo esc_url(home_url('/customer/discover')); ?>" class="btn btn-secondary btn-sm" target="_blank">Storefront ↗</a>
      <a href="<?php echo esc_url(home_url('/admin/dashboard')); ?>" class="btn btn-outline btn-sm">Platform Admin</a>
    </div>
  </div>

  <!-- KPI Row -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 32px;">
    
    <div class="card" style="border-radius: var(--radius-xl); background: #ffffff; padding: 24px;">
      <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">WAITING IN LINE</span>
      <div style="font-size: 2.4rem; font-weight: 800; color: var(--primary); margin-top: 6px;">2</div>
      <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">Live clients in line</div>
    </div>

    <div class="card" style="border-radius: var(--radius-xl); background: #ffffff; padding: 24px;">
      <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">IN CHAIR</span>
      <div style="font-size: 2.4rem; font-weight: 800; color: #059669; margin-top: 6px;">1</div>
      <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">Currently serving</div>
    </div>

    <div class="card" style="border-radius: var(--radius-xl); background: #ffffff; padding: 24px;">
      <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">TODAY'S APPOINTMENTS</span>
      <div style="font-size: 2.4rem; font-weight: 800; color: #2563eb; margin-top: 6px;">4</div>
      <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">Guaranteed bookings</div>
    </div>

    <div class="card" style="border-radius: var(--radius-xl); background: #ffffff; padding: 24px;">
      <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">ESTIMATED WAIT</span>
      <div style="font-size: 2.4rem; font-weight: 800; color: #d97706; margin-top: 6px;">~15m</div>
      <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">Average per turn</div>
    </div>
  </div>

  <!-- Active Chair Spotlight -->
  <section class="card" style="border: 2px solid var(--primary); background: #ffffff; border-radius: var(--radius-xl); box-shadow: var(--shadow-md); margin-bottom: 32px; padding: 28px;">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-subtle); padding-bottom: 16px; margin-bottom: 20px;">
      <div>
        <span style="font-size: 0.75rem; color: var(--primary); font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">ACTIVE CHAIR #1</span>
        <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--text-main); margin-top: 2px;">David Miller</h2>
      </div>
      <span class="badge badge-open" style="background: #ecfdf5; color: #059669; border-color: #a7f3d0; font-size: 0.85rem; padding: 6px 14px;">✂ Currently in Chair</span>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">REQUESTED TREATMENT</span>
        <div style="font-size: 1.15rem; font-weight: 700; color: var(--text-main); margin-top: 2px;">Master Precision Haircut ($45)</div>
      </div>

      <div style="display: flex; gap: 10px;">
        <button class="btn btn-secondary" onclick="alert('Notification sent to next client in line!')">🔔 Alert Next Client</button>
        <button class="btn btn-primary" onclick="alert('Service marked completed. Chair open!')">✓ Mark Cut Complete</button>
      </div>
    </div>
  </section>

</main>

<?php
get_footer();
