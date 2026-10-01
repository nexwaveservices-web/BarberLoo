<?php
/**
 * Template: Customer Shop Details & Menu
 */
get_header();

$shop_id = sanitize_text_field($_GET['id'] ?? '00000000-0000-0000-0000-000000000001');
?>

<!-- Clean Shop Header Profile -->
<div style="background: #ffffff; border-bottom: 1px solid var(--border-subtle); padding: 32px 0 24px 0;">
  <div class="container" style="display: flex; gap: 24px; align-items: center; flex-wrap: wrap;">
    <div style="width: 100px; height: 100px; border-radius: var(--radius-xl); overflow: hidden; border: 1px solid var(--border-subtle); background: #f3f4f6; box-shadow: var(--shadow-sm); flex-shrink: 0;">
      <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=200&q=80" style="width: 100%; height: 100%; object-fit: cover;">
    </div>

    <div style="flex: 1; min-width: 260px;">
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px; flex-wrap: wrap;">
        <h1 style="font-size: 2rem; font-weight: 800; color: var(--text-main);">BarberLoo Heritage Lounge</h1>
        <span class="badge badge-open">Open Now</span>
      </div>
      <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 8px;">
        📍 500 Howard Street, Suite 100, San Francisco • ★ 4.95 (48 reviews)
      </p>
      <p style="color: #4b5563; font-size: 0.925rem; max-width: 720px; line-height: 1.5;">
        Luxury grooming parlour specializing in master scissor fades, traditional Japanese hot-towel straight razor shaves, and artisanal beard grooming.
      </p>
    </div>
  </div>
</div>

<main class="container" style="padding-top: 36px; padding-bottom: 80px;">
  <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px; align-items: flex-start;">
    
    <!-- Left Column: Services Menu -->
    <div>
      <h2 style="font-size: 1.4rem; font-weight: 800; margin-bottom: 20px; color: var(--text-main);">Specialty Services Menu</h2>

      <div style="display: flex; flex-direction: column; gap: 16px;">
        <!-- Service Card 1 -->
        <div class="card" style="padding: 20px; border-radius: var(--radius-xl); display: flex; justify-content: space-between; align-items: center;">
          <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main);">Master Precision Haircut</h3>
            <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 4px;">Tailored haircut with consultation, razor neck clean, wash, and premium clay finish.</p>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 8px;">⏱ 30 mins</div>
          </div>
          <div style="text-align: right; margin-left: 20px; flex-shrink: 0;">
            <div style="font-size: 1.4rem; font-weight: 800; color: var(--primary);">$45</div>
            <div style="display: flex; gap: 8px; margin-top: 8px;">
              <a href="<?php echo esc_url(home_url('/customer/queue?shop_id=' . $shop_id . '&srv=1')); ?>" class="btn btn-primary btn-sm">Queue In</a>
              <a href="<?php echo esc_url(home_url('/booking?shop_id=' . $shop_id . '&srv=1')); ?>" class="btn btn-secondary btn-sm">Book</a>
            </div>
          </div>
        </div>

        <!-- Service Card 2 -->
        <div class="card" style="padding: 20px; border-radius: var(--radius-xl); display: flex; justify-content: space-between; align-items: center;">
          <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main);">Japanese Hot Towel Straight Razor Shave</h3>
            <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 4px;">Pre-shave eucalyptus oil, 2 hot steam towels, lather massage, and ultra-smooth straight blade shave.</p>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 8px;">⏱ 30 mins</div>
          </div>
          <div style="text-align: right; margin-left: 20px; flex-shrink: 0;">
            <div style="font-size: 1.4rem; font-weight: 800; color: var(--primary);">$40</div>
            <div style="display: flex; gap: 8px; margin-top: 8px;">
              <a href="<?php echo esc_url(home_url('/customer/queue?shop_id=' . $shop_id . '&srv=2')); ?>" class="btn btn-primary btn-sm">Queue In</a>
              <a href="<?php echo esc_url(home_url('/booking?shop_id=' . $shop_id . '&srv=2')); ?>" class="btn btn-secondary btn-sm">Book</a>
            </div>
          </div>
        </div>

        <!-- Service Card 3 -->
        <div class="card" style="padding: 20px; border-radius: var(--radius-xl); display: flex; justify-content: space-between; align-items: center;">
          <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main);">Beard Sculpt & Razor Line-up</h3>
            <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 4px;">Custom beard shaping, length fading, trimmer edge alignment, and warm balm treatment.</p>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 8px;">⏱ 20 mins</div>
          </div>
          <div style="text-align: right; margin-left: 20px; flex-shrink: 0;">
            <div style="font-size: 1.4rem; font-weight: 800; color: var(--primary);">$28</div>
            <div style="display: flex; gap: 8px; margin-top: 8px;">
              <a href="<?php echo esc_url(home_url('/customer/queue?shop_id=' . $shop_id . '&srv=3')); ?>" class="btn btn-primary btn-sm">Queue In</a>
              <a href="<?php echo esc_url(home_url('/booking?shop_id=' . $shop_id . '&srv=3')); ?>" class="btn btn-secondary btn-sm">Book</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Live Queue Status Card -->
    <div>
      <div class="card" style="padding: 24px; border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); position: sticky; top: 90px;">
        <span class="badge badge-open" style="margin-bottom: 12px;">Live Queue Status</span>
        <div style="font-size: 2.2rem; font-weight: 800; color: var(--primary); margin-top: 6px;">~10 mins</div>
        <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 2px;">2 clients currently waiting in line</p>
        
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-subtle);">
          <a href="<?php echo esc_url(home_url('/customer/queue?shop_id=' . $shop_id)); ?>" class="btn btn-primary" style="width: 100%; text-align: center;">Enter Live Queue</a>
          <a href="<?php echo esc_url(home_url('/booking?shop_id=' . $shop_id)); ?>" class="btn btn-secondary" style="width: 100%; text-align: center; margin-top: 10px;">Reserve Guaranteed Time</a>
        </div>
      </div>
    </div>

  </div>
</main>

<?php
get_footer();
