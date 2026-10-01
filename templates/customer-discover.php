<?php
/**
 * Template: Customer Discover Barbers & Salons (Indian Edition)
 */
get_header();
?>

<!-- Filter & Discovery Header -->
<section style="background: #ffffff; padding: 40px 0 32px 0; border-bottom: 1px solid var(--border-subtle);">
  <div class="container">
    <div style="max-width: 680px; margin-bottom: 24px;">
      <span class="badge badge-waiting" style="margin-bottom: 10px;">★ Verified Salons & Barbershops in India</span>
      <h1 style="font-size: 2.25rem; font-weight: 800; letter-spacing: -0.02em; color: var(--text-main); margin-bottom: 6px;">
        Find Salons & Queue Live from Phone
      </h1>
      <p style="color: var(--text-muted); font-size: 1rem;">
        Check live chair availability, track waiting line in real-time, or book an advance slot with UPI.
      </p>
    </div>

    <!-- Modern Search Box with Indian Cities -->
    <div class="card" style="padding: 14px 18px; border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); display: flex; flex-direction: column; gap: 14px;">
      <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <div style="flex: 2; min-width: 240px; position: relative;">
          <input type="text" id="search-input" class="form-input" placeholder="Search by salon name, area, or cut (e.g. Connaught Place, Fade, Champi)..." style="border-radius: var(--radius-full); padding-left: 42px;">
          <span style="position: absolute; left: 16px; top: 12px; color: var(--text-dim); font-size: 1.1rem;">🔍</span>
        </div>

        <div style="flex: 1; min-width: 170px;">
          <select id="filter-city" class="form-select" style="border-radius: var(--radius-full);">
            <option value="all">All Cities (India)</option>
            <option value="Delhi NCR">Delhi NCR</option>
            <option value="Bengaluru">Bengaluru</option>
            <option value="Mumbai">Mumbai</option>
            <option value="Hyderabad">Hyderabad</option>
            <option value="Pune">Pune</option>
          </select>
        </div>

        <button id="btn-search" class="btn btn-primary" style="padding: 12px 28px;">
          Search Salons
        </button>
      </div>
    </div>
  </div>
</section>

<!-- Shop Cards Grid Container -->
<main class="container" style="padding: 48px 16px 80px 16px;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
      <span id="shops-count" style="font-size: 0.9rem; font-weight: 700; color: var(--text-muted);">Verified Salons in India</span>
    </div>
  </div>

  <div id="shops-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
    <!-- Featured Barber Shop 1 -->
    <div class="card" style="padding:0; overflow:hidden; border-radius:var(--radius-xl); background: #ffffff;">
      <img src="https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=600&q=80" style="width:100%; height:200px; object-fit:cover;" alt="Royal Heritage Salon & Barbers">
      <div style="padding:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <h3 style="font-size:1.15rem; font-weight:800; color:var(--text-main);">Royal Heritage Salon & Barbers</h3>
          <span class="badge badge-open">Open</span>
        </div>
        <p style="color:var(--text-muted); font-size:0.875rem; margin-top:4px;">Connaught Place, Delhi NCR • ★ 4.95 (142 reviews)</p>
        <p style="color:#64748b; font-size:0.85rem; margin-top:8px;">Bespoke scissor fades, herbal face de-tan, Ayurvedic head massage, and hot towel beard shaves.</p>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; padding-top:14px; border-top:1px solid var(--border-subtle);">
          <div>
            <span style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase;">Live Wait</span>
            <div style="font-size:1.1rem; font-weight:800; color:var(--primary);">~10 mins (2 in line)</div>
          </div>
          <div style="display:flex; gap:8px;">
            <a href="<?php echo esc_url(home_url('/customer/shop-details?id=00000000-0000-0000-0000-000000000001')); ?>" class="btn btn-primary btn-sm">Enter Queue</a>
            <a href="<?php echo esc_url(home_url('/booking?shop_id=00000000-0000-0000-0000-000000000001')); ?>" class="btn btn-secondary btn-sm">Book Slot</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Featured Barber Shop 2 -->
    <div class="card" style="padding:0; overflow:hidden; border-radius:var(--radius-xl); background: #ffffff;">
      <img src="https://images.unsplash.com/photo-1512690459411-b9245aed614b?auto=format&fit=crop&w=600&q=80" style="width:100%; height:200px; object-fit:cover;" alt="The Crown & Scissor Mens Lounge">
      <div style="padding:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <h3 style="font-size:1.15rem; font-weight:800; color:var(--text-main);">The Crown & Scissor Mens Lounge</h3>
          <span class="badge badge-open">Open</span>
        </div>
        <p style="color:var(--text-muted); font-size:0.875rem; margin-top:4px;">100ft Road, Indiranagar, Bengaluru • ★ 4.92 (98 reviews)</p>
        <p style="color:#64748b; font-size:0.85rem; margin-top:8px;">Modern luxury grooming studio offering taper fades, beard lineup, and organic cleanup.</p>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; padding-top:14px; border-top:1px solid var(--border-subtle);">
          <div>
            <span style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase;">Live Wait</span>
            <div style="font-size:1.1rem; font-weight:800; color:var(--primary);">~15 mins (3 in line)</div>
          </div>
          <div style="display:flex; gap:8px;">
            <a href="<?php echo esc_url(home_url('/customer/shop-details?id=shop-002')); ?>" class="btn btn-primary btn-sm">Enter Queue</a>
            <a href="<?php echo esc_url(home_url('/booking?shop_id=shop-002')); ?>" class="btn btn-secondary btn-sm">Book Slot</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Featured Barber Shop 3 -->
    <div class="card" style="padding:0; overflow:hidden; border-radius:var(--radius-xl); background: #ffffff;">
      <img src="https://images.unsplash.com/photo-1622286342621-4bd786c2447c?auto=format&fit=crop&w=600&q=80" style="width:100%; height:200px; object-fit:cover;" alt="Urban Cutters & Beard Studio">
      <div style="padding:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <h3 style="font-size:1.15rem; font-weight:800; color:var(--text-main);">Urban Cutters & Beard Studio</h3>
          <span class="badge badge-open">Open</span>
        </div>
        <p style="color:var(--text-muted); font-size:0.875rem; margin-top:4px;">Linking Road, Bandra West, Mumbai • ★ 4.88 (76 reviews)</p>
        <p style="color:#64748b; font-size:0.85rem; margin-top:8px;">Bespoke salon catering to professionals with zero waiting, Korean texture cuts & beard sculpt.</p>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; padding-top:14px; border-top:1px solid var(--border-subtle);">
          <div>
            <span style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase;">Live Wait</span>
            <div style="font-size:1.1rem; font-weight:800; color:var(--primary);">No Wait (Chair Open)</div>
          </div>
          <div style="display:flex; gap:8px;">
            <a href="<?php echo esc_url(home_url('/customer/shop-details?id=shop-003')); ?>" class="btn btn-primary btn-sm">Enter Queue</a>
            <a href="<?php echo esc_url(home_url('/booking?shop_id=shop-003')); ?>" class="btn btn-secondary btn-sm">Book Slot</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php
get_footer();
