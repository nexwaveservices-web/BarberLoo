<?php
/**
 * Template: BarberLoo Admin Dashboard
 */
get_header();
?>

<main class="container" style="padding-top: 40px; padding-bottom: 80px;">
  
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h1 style="font-size: 2rem; font-weight: 800; color: var(--text-main);">Platform Administration</h1>
      <p style="color: var(--text-muted); font-size: 0.95rem;">BarberLoo Master Control Center & WP Pusher Engine</p>
    </div>

    <div style="display: flex; gap: 10px;">
      <a href="<?php echo esc_url(home_url('/customer/discover')); ?>" class="btn btn-secondary btn-sm">Storefront ↗</a>
      <a href="<?php echo esc_url(home_url('/barber/dashboard')); ?>" class="btn btn-outline btn-sm">Barber Desk</a>
    </div>
  </div>

  <!-- WP Pusher Integration Banner -->
  <div class="card" style="margin-bottom: 32px; background: #ffffff; border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div style="display: flex; gap: 16px; align-items: center;">
        <div style="width: 44px; height: 44px; border-radius: 12px; background: #eff6ff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #2563eb;">
          🚀
        </div>
        <div>
          <div style="display: flex; align-items: center; gap: 8px;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-main); margin: 0;">WP Pusher Live Bridge</h3>
            <span class="badge badge-open">Active on BarberLoo.in</span>
          </div>
          <p style="font-size: 0.875rem; color: var(--text-muted); margin: 4px 0 0 0;">
            Repository: <code style="color: #2563eb; font-weight: 600;">nexwaveservices-web/BarberLoo (main)</code>
          </p>
        </div>
      </div>

      <button class="btn btn-primary btn-sm" onclick="alert('WP Pusher Git sync triggered successfully!')">
        ⚡ Trigger WP Pusher Deploy
      </button>
    </div>
  </div>

  <!-- Registered Shops Table -->
  <div class="card" style="padding: 24px; border-radius: var(--radius-xl); background: #ffffff;">
    <h3 style="font-size: 1.2rem; font-weight: 800; margin-bottom: 16px; color: var(--text-main);">Partner Barbershops</h3>

    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
        <thead>
          <tr style="border-bottom: 2px solid var(--border-subtle); color: var(--text-muted);">
            <th style="padding: 12px 16px;">Shop Name</th>
            <th style="padding: 12px 16px;">Location</th>
            <th style="padding: 12px 16px;">Rating</th>
            <th style="padding: 12px 16px;">Live Queue</th>
            <th style="padding: 12px 16px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr style="border-bottom: 1px solid var(--border-subtle);">
            <td style="padding: 14px 16px; font-weight: 700;">BarberLoo Heritage Lounge</td>
            <td style="padding: 14px 16px; color: var(--text-muted);">San Francisco, CA</td>
            <td style="padding: 14px 16px;">★ 4.95</td>
            <td style="padding: 14px 16px;"><span class="badge badge-open">2 Waiting</span></td>
            <td style="padding: 14px 16px;"><a href="<?php echo esc_url(home_url('/customer/shop-details?id=00000000-0000-0000-0000-000000000001')); ?>" class="btn btn-outline btn-sm">View</a></td>
          </tr>
          <tr style="border-bottom: 1px solid var(--border-subtle);">
            <td style="padding: 14px 16px; font-weight: 700;">The Crown & Scissor</td>
            <td style="padding: 14px 16px; color: var(--text-muted);">San Francisco, CA</td>
            <td style="padding: 14px 16px;">★ 4.92</td>
            <td style="padding: 14px 16px;"><span class="badge badge-open">Open</span></td>
            <td style="padding: 14px 16px;"><a href="<?php echo esc_url(home_url('/customer/shop-details?id=shop-002')); ?>" class="btn btn-outline btn-sm">View</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

</main>

<?php
get_footer();
