<?php
/**
 * Template: BarberLoo Admin Dashboard with Shop Licensing, Transactions, & Fees
 */

if (!defined('ABSPATH')) {
    exit;
}

// Ensure only WordPress administrators or rgi855477@gmail.com can view this page
$current_wp_user = wp_get_current_user();
$is_admin = current_user_can('administrator') || ($current_wp_user && strtolower($current_wp_user->user_email) === 'rgi855477@gmail.com');

if (!$is_admin) {
    wp_redirect(home_url('/login'));
    exit;
}

get_header();
?>

<main class="container" style="padding-top: 40px; padding-bottom: 80px;">
  
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--text-main);">Master Platform Administration</h1>
      <p style="color: var(--text-muted); font-size: 0.95rem;">Manage shop licenses, oversee all financial transactions, configure platform fees, and issue coupon codes.</p>
    </div>

    <div style="display: flex; gap: 10px;">
      <a href="<?php echo esc_url(home_url('/customer/discover')); ?>" class="btn btn-secondary btn-sm">Storefront ↗</a>
      <a href="<?php echo esc_url(home_url('/barber/dashboard')); ?>" class="btn btn-outline btn-sm">Barber Desk</a>
    </div>
  </div>

  <!-- Financial KPI Metrics Row -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 18px; margin-bottom: 32px;">
    <div class="card" style="border-radius: var(--radius-xl); background: #ffffff; padding: 24px;">
      <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">TOTAL TRANSACTION VOLUME</span>
      <div style="font-size: 2.2rem; font-weight: 800; color: var(--primary); margin-top: 6px;">$1,422.25</div>
      <div style="font-size: 0.8rem; color: #059669; margin-top: 4px; font-weight: 600;">Gross bookings processed</div>
    </div>

    <div class="card" style="border-radius: var(--radius-xl); background: #ffffff; padding: 24px;">
      <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">PLATFORM FEE REVENUE</span>
      <div style="font-size: 2.2rem; font-weight: 800; color: #166534; margin-top: 6px;">$189.50</div>
      <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">Earned platform fees</div>
    </div>

    <div class="card" style="border-radius: var(--radius-xl); background: #ffffff; padding: 24px;">
      <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">BARBERS PAYOUT POOL</span>
      <div style="font-size: 2.2rem; font-weight: 800; color: #2563eb; margin-top: 6px;">$1,232.75</div>
      <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">Net barber earnings</div>
    </div>

    <div class="card" style="border-radius: var(--radius-xl); background: #ffffff; padding: 24px;">
      <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">PARTNER SHOPS / PASSES</span>
      <div style="font-size: 2.2rem; font-weight: 800; color: var(--text-main); margin-top: 6px;">3 / 18</div>
      <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">Licensed shops & active tickets</div>
    </div>
  </div>

  <!-- Platform Fee & Coupon Codes Admin Grid -->
  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
    
    <!-- Platform Fee Controller -->
    <div class="card" style="border-radius: var(--radius-xl); background: #ffffff; padding: 28px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div>
          <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--text-main);">Platform Fee Settings</h3>
          <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">Fee added on top of barber prices on customer checkout</p>
        </div>
        <span class="badge badge-open">Active</span>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
        <div class="form-group">
          <label class="form-label">Fixed Fee ($)</label>
          <input type="number" class="form-input" value="3.00">
        </div>
        <div class="form-group">
          <label class="form-label">Percentage Fee (%)</label>
          <input type="number" class="form-input" value="5.0">
        </div>
      </div>

      <div style="margin-top: 8px; font-size: 0.85rem; color: var(--text-muted);">
        Customer Checkout = <strong>Barber Price + Fixed Fee + (Percent Fee × Barber Price)</strong>
      </div>

      <button type="button" class="btn btn-primary btn-sm" style="margin-top: 18px; width: 100%;" onclick="alert('Platform fee rates saved!')">
        Save Platform Fee Rates
      </button>
    </div>

    <!-- Coupon Codes Generator -->
    <div class="card" style="border-radius: var(--radius-xl); background: #ffffff; padding: 28px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div>
          <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--text-main);">Active Promotional Coupons</h3>
          <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">Discounts applied on appointments</p>
        </div>
        <span class="badge" style="background: #eff6ff; color: #2563eb; font-weight: 700;">Promotions</span>
      </div>

      <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 16px;">
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 8px 14px; border-radius: var(--radius-lg); font-size: 0.85rem;">
          <strong style="color: #166534;">WELCOME10</strong>: 10% Off • <span style="color: var(--text-muted);">14 uses</span>
        </div>
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 8px 14px; border-radius: var(--radius-lg); font-size: 0.85rem;">
          <strong style="color: #166534;">BARBER5</strong>: $5 Flat Off • <span style="color: var(--text-muted);">8 uses</span>
        </div>
      </div>

      <button type="button" class="btn btn-secondary btn-sm" style="width: 100%;" onclick="
        var code = prompt('Enter new coupon code:');
        if(code) alert('Coupon ' + code.toUpperCase() + ' created successfully!');
      ">
        + Issue New Promo Coupon
      </button>
    </div>

  </div>

  <!-- Shop Licensing Governance & Deactivation Table -->
  <div class="card" style="padding: 0; overflow: hidden; border-radius: var(--radius-xl); background: #ffffff; margin-bottom: 32px;">
    <div style="padding: 24px; border-bottom: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center;">
      <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main);">Barbershop License Control & Overall Reports</h3>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">Admin can suspend or reactivate any shop license from appearing on website or taking bookings</p>
      </div>
      <span class="badge badge-open">Realtime Governance</span>
    </div>

    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
        <thead>
          <tr style="border-bottom: 2px solid var(--border-subtle); color: var(--text-muted); background: var(--bg-subtle);">
            <th style="padding: 14px 16px;">Shop Name</th>
            <th style="padding: 14px 16px;">City</th>
            <th style="padding: 14px 16px;">Total Volume</th>
            <th style="padding: 14px 16px;">License Status</th>
            <th style="padding: 14px 16px; text-align: right;">License Action</th>
          </tr>
        </thead>
        <tbody>
          <tr style="border-bottom: 1px solid var(--border-subtle);">
            <td style="padding: 14px 16px; font-weight: 700;">BarberLoo Heritage Lounge</td>
            <td style="padding: 14px 16px; color: var(--text-muted);">San Francisco, CA</td>
            <td style="padding: 14px 16px; font-weight: 700; color: var(--primary);">$840.00</td>
            <td style="padding: 14px 16px;"><span id="lic-badge-1" class="badge badge-open">ACTIVE LICENSED</span></td>
            <td style="padding: 14px 16px; text-align: right;">
              <button id="lic-btn-1" class="btn btn-outline btn-sm" style="color: #dc2626; border-color: #fecaca;" onclick="
                var b = document.getElementById('lic-badge-1');
                var btn = document.getElementById('lic-btn-1');
                if(b.textContent.includes('ACTIVE')) {
                  b.textContent = 'SUSPENDED';
                  b.className = 'badge badge-closed';
                  btn.textContent = 'Reactivate License';
                  btn.style.color = '#166534';
                  btn.style.borderColor = '#bbf7d0';
                  alert('Shop license suspended! It is now removed from public storefront bookings.');
                } else {
                  b.textContent = 'ACTIVE LICENSED';
                  b.className = 'badge badge-open';
                  btn.textContent = 'Deactivate License 🚫';
                  btn.style.color = '#dc2626';
                  btn.style.borderColor = '#fecaca';
                  alert('Shop license reactivated!');
                }
              ">Deactivate License 🚫</button>
            </td>
          </tr>
          <tr style="border-bottom: 1px solid var(--border-subtle);">
            <td style="padding: 14px 16px; font-weight: 700;">The Crown & Scissor</td>
            <td style="padding: 14px 16px; color: var(--text-muted);">San Francisco, CA</td>
            <td style="padding: 14px 16px; font-weight: 700; color: var(--primary);">$582.25</td>
            <td style="padding: 14px 16px;"><span id="lic-badge-2" class="badge badge-open">ACTIVE LICENSED</span></td>
            <td style="padding: 14px 16px; text-align: right;">
              <button id="lic-btn-2" class="btn btn-outline btn-sm" style="color: #dc2626; border-color: #fecaca;" onclick="
                var b = document.getElementById('lic-badge-2');
                var btn = document.getElementById('lic-btn-2');
                if(b.textContent.includes('ACTIVE')) {
                  b.textContent = 'SUSPENDED';
                  b.className = 'badge badge-closed';
                  btn.textContent = 'Reactivate License';
                  btn.style.color = '#166534';
                  btn.style.borderColor = '#bbf7d0';
                  alert('Shop license suspended!');
                } else {
                  b.textContent = 'ACTIVE LICENSED';
                  b.className = 'badge badge-open';
                  btn.textContent = 'Deactivate License 🚫';
                  btn.style.color = '#dc2626';
                  btn.style.borderColor = '#fecaca';
                  alert('Shop license reactivated!');
                }
              ">Deactivate License 🚫</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Live Transactions Audit Table -->
  <div class="card" style="padding: 0; overflow: hidden; border-radius: var(--radius-xl); background: #ffffff; margin-bottom: 32px;">
    <div style="padding: 24px; border-bottom: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center;">
      <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main);">Recent Platform Financial Transactions</h3>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">Audit of customer payments with method, barber earnings, and platform fees</p>
      </div>
      <span class="badge" style="background: #f0fdf4; color: #166534; font-weight: 700;">Audited</span>
    </div>

    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
        <thead>
          <tr style="border-bottom: 2px solid var(--border-subtle); color: var(--text-muted); background: var(--bg-subtle);">
            <th style="padding: 12px 16px;">Shop</th>
            <th style="padding: 12px 16px;">Customer</th>
            <th style="padding: 12px 16px;">Total Paid</th>
            <th style="padding: 12px 16px;">Barber Payout</th>
            <th style="padding: 12px 16px;">Platform Fee</th>
            <th style="padding: 12px 16px;">Method</th>
            <th style="padding: 12px 16px;">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr style="border-bottom: 1px solid var(--border-subtle);">
            <td style="padding: 12px 16px; font-weight: 700;">BarberLoo Heritage Lounge</td>
            <td style="padding: 12px 16px;">David Miller</td>
            <td style="padding: 12px 16px; font-weight: 800; color: var(--text-main);">$45.00</td>
            <td style="padding: 12px 16px; color: #2563eb; font-weight: 700;">$40.00</td>
            <td style="padding: 12px 16px; color: #166534; font-weight: 700;">+$5.00</td>
            <td style="padding: 12px 16px;">💳 Card</td>
            <td style="padding: 12px 16px;"><span class="badge badge-open">COMPLETED</span></td>
          </tr>
          <tr style="border-bottom: 1px solid var(--border-subtle);">
            <td style="padding: 12px 16px; font-weight: 700;">BarberLoo Heritage Lounge</td>
            <td style="padding: 12px 16px;">Jordan Smith</td>
            <td style="padding: 12px 16px; font-weight: 800; color: var(--text-main);">$33.00</td>
            <td style="padding: 12px 16px; color: #2563eb; font-weight: 700;">$28.00</td>
            <td style="padding: 12px 16px; color: #166534; font-weight: 700;">+$5.00</td>
            <td style="padding: 12px 16px;">📱 UPI</td>
            <td style="padding: 12px 16px;"><span class="badge badge-open">COMPLETED</span></td>
          </tr>
          <tr style="border-bottom: 1px solid var(--border-subtle);">
            <td style="padding: 12px 16px; font-weight: 700;">The Crown & Scissor</td>
            <td style="padding: 12px 16px;">Arthur Pendelton</td>
            <td style="padding: 12px 16px; font-weight: 800; color: var(--text-main);">$80.00</td>
            <td style="padding: 12px 16px; color: #2563eb; font-weight: 700;">$75.00</td>
            <td style="padding: 12px 16px; color: #166534; font-weight: 700;">+$5.00</td>
            <td style="padding: 12px 16px;">💵 Cash</td>
            <td style="padding: 12px 16px;"><span class="badge badge-waiting">PENDING CASH</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- WP Pusher Integration Banner -->
  <div class="card" style="border-radius: var(--radius-xl); background: #ffffff; border: 1px solid #bfdbfe; padding: 24px;">
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

</main>

<?php
get_footer();
