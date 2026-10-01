<?php
/**
 * Template: Customer Discover Barbers
 */
get_header();
?>

<!-- Filter & Discovery Header -->
<section style="background: #ffffff; padding: 40px 0 32px 0; border-bottom: 1px solid var(--border-subtle);">
  <div class="container">
    <div style="max-width: 680px; margin-bottom: 24px;">
      <span class="badge badge-waiting" style="margin-bottom: 10px;">★ Handpicked Neighborhood Barbers</span>
      <h1 style="font-size: 2.25rem; font-weight: 800; letter-spacing: -0.02em; color: var(--text-main); margin-bottom: 6px;">
        Find & Queue With Master Barbers
      </h1>
      <p style="color: var(--text-muted); font-size: 1rem;">
        Track waiting line depth in real time or reserve an exact chair slot with no wait.
      </p>
    </div>

    <!-- Modern Search Box -->
    <div class="card" style="padding: 14px 18px; border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); display: flex; flex-direction: column; gap: 14px;">
      <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <div style="flex: 2; min-width: 240px; position: relative;">
          <input type="text" id="search-input" class="form-input" placeholder="Search by shop name, fade style, or street..." style="border-radius: var(--radius-full); padding-left: 42px;">
          <span style="position: absolute; left: 16px; top: 12px; color: var(--text-dim); font-size: 1.1rem;">🔍</span>
        </div>

        <div style="flex: 1; min-width: 170px;">
          <select id="filter-city" class="form-select" style="border-radius: var(--radius-full);">
            <option value="all">All Locations</option>
            <option value="San Francisco">San Francisco, CA</option>
            <option value="Oakland">Oakland, CA</option>
          </select>
        </div>

        <button id="btn-search" class="btn btn-primary" style="padding: 12px 28px;">
          Search Barbers
        </button>
      </div>
    </div>
  </div>
</section>

<!-- Shop Cards Grid Container -->
<main class="container" style="padding: 48px 16px 80px 16px;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
      <span id="shops-count" style="font-size: 0.9rem; font-weight: 700; color: var(--text-muted);">Available Barbershops</span>
    </div>
  </div>

  <div id="shops-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
    <!-- Featured Barber Shop 1 -->
    <div class="card" style="padding:0; overflow:hidden; border-radius:var(--radius-xl);">
      <img src="https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=600&q=80" style="width:100%; height:200px; object-fit:cover;" alt="Crown & Blade Parlour">
      <div style="padding:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <h3 style="font-size:1.15rem; font-weight:800; color:var(--text-main);">Crown & Blade Parlour</h3>
          <span class="badge badge-open">Open</span>
        </div>
        <p style="color:var(--text-muted); font-size:0.875rem; margin-top:4px;">Downtown San Francisco • ★ 4.9 (128 reviews)</p>
        <p style="color:#64748b; font-size:0.85rem; margin-top:8px;">Bespoke scissor work, precision razor fades, and warm steam lather treatments.</p>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; padding-top:14px; border-top:1px solid var(--border-subtle);">
          <div>
            <span style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase;">Live Wait</span>
            <div style="font-size:1.1rem; font-weight:800; color:var(--primary);">~10 mins (2 in line)</div>
          </div>
          <div style="display:flex; gap:8px;">
            <a href="<?php echo esc_url(home_url('/customer/shop-details?id=00000000-0000-0000-0000-000000000001')); ?>" class="btn btn-primary btn-sm">Enter Queue</a>
            <a href="<?php echo esc_url(home_url('/booking?shop_id=00000000-0000-0000-0000-000000000001')); ?>" class="btn btn-secondary btn-sm">Book</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Featured Barber Shop 2 -->
    <div class="card" style="padding:0; overflow:hidden; border-radius:var(--radius-xl);">
      <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=600&q=80" style="width:100%; height:200px; object-fit:cover;" alt="The Vintage Razor">
      <div style="padding:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <h3 style="font-size:1.15rem; font-weight:800; color:var(--text-main);">The Vintage Razor</h3>
          <span class="badge badge-open">Open</span>
        </div>
        <p style="color:var(--text-muted); font-size:0.875rem; margin-top:4px;">Mission District • ★ 4.8 (94 reviews)</p>
        <p style="color:#64748b; font-size:0.85rem; margin-top:8px;">Authentic retro barbershop atmosphere with modern precision grooming techniques.</p>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; padding-top:14px; border-top:1px solid var(--border-subtle);">
          <div>
            <span style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase;">Live Wait</span>
            <div style="font-size:1.1rem; font-weight:800; color:var(--primary);">~25 mins (4 in line)</div>
          </div>
          <div style="display:flex; gap:8px;">
            <a href="<?php echo esc_url(home_url('/customer/shop-details?id=00000000-0000-0000-0000-000000000002')); ?>" class="btn btn-primary btn-sm">Enter Queue</a>
            <a href="<?php echo esc_url(home_url('/booking?shop_id=00000000-0000-0000-0000-000000000002')); ?>" class="btn btn-secondary btn-sm">Book</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Featured Barber Shop 3 -->
    <div class="card" style="padding:0; overflow:hidden; border-radius:var(--radius-xl);">
      <img src="https://images.unsplash.com/photo-1622286342621-4bd786c2447c?auto=format&fit=crop&w=600&q=80" style="width:100%; height:200px; object-fit:cover;" alt="Apex Atelier Gentlemen">
      <div style="padding:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <h3 style="font-size:1.15rem; font-weight:800; color:var(--text-main);">Apex Atelier Gentlemen</h3>
          <span class="badge badge-open">Open</span>
        </div>
        <p style="color:var(--text-muted); font-size:0.875rem; margin-top:4px;">Financial District • ★ 5.0 (62 reviews)</p>
        <p style="color:#64748b; font-size:0.85rem; margin-top:8px;">Executive grooming lounge catering to busy professionals with zero waiting.</p>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; padding-top:14px; border-top:1px solid var(--border-subtle);">
          <div>
            <span style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase;">Live Wait</span>
            <div style="font-size:1.1rem; font-weight:800; color:var(--primary);">No Wait (Chair Open)</div>
          </div>
          <div style="display:flex; gap:8px;">
            <a href="<?php echo esc_url(home_url('/customer/shop-details?id=00000000-0000-0000-0000-000000000003')); ?>" class="btn btn-primary btn-sm">Enter Queue</a>
            <a href="<?php echo esc_url(home_url('/booking?shop_id=00000000-0000-0000-0000-000000000003')); ?>" class="btn btn-secondary btn-sm">Book</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php
get_footer();
