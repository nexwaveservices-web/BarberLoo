<?php
/**
 * Template: Customer Shop Details & Menu (Indian Edition)
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
        <h1 style="font-size: 2rem; font-weight: 800; color: var(--text-main);">Royal Heritage Salon & Barbers</h1>
        <span class="badge badge-open">Open Now</span>
      </div>
      <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 8px;">
        📍 Connaught Place, Inner Circle, New Delhi • ★ 4.95 (142 reviews) • 📞 +91 98111 22334
      </p>
      <p style="color: #4b5563; font-size: 0.925rem; max-width: 720px; line-height: 1.5;">
        Premier Indian men grooming salon specializing in master scissor fades, herbal face de-tan, traditional hot towel straight razor shaves, and Ayurvedic head champi.
      </p>
    </div>
  </div>
</div>

<main class="container" style="padding-top: 36px; padding-bottom: 80px;">
  <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px; align-items: flex-start;">
    
    <!-- Left Column: Services Menu -->
    <div>
      <h2 style="font-size: 1.4rem; font-weight: 800; margin-bottom: 20px; color: var(--text-main);">Services & Treatment Menu</h2>

      <div style="display: flex; flex-direction: column; gap: 16px;">
        <!-- Service Card 1 -->
        <div class="card" style="padding: 20px; border-radius: var(--radius-xl); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
          <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main);">Classic Precision Haircut & Wash</h3>
            <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 4px;">Tailored haircut consultation, hair wash, razor neck clean, and styling clay finish.</p>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 8px;">⏱ 30 mins</div>
          </div>
          <div style="text-align: right; margin-left: 20px; flex-shrink: 0;">
            <div style="font-size: 1.4rem; font-weight: 800; color: var(--primary);">₹250</div>
            <div style="display: flex; gap: 8px; margin-top: 8px;">
              <a href="<?php echo esc_url(home_url('/customer/queue?shop_id=' . $shop_id . '&srv=srv-001')); ?>" class="btn btn-primary btn-sm">Queue In</a>
              <a href="<?php echo esc_url(home_url('/booking?shop_id=' . $shop_id . '&service_id=srv-001')); ?>" class="btn btn-secondary btn-sm">Book Slot</a>
            </div>
          </div>
        </div>

        <!-- Service Card 2 -->
        <div class="card" style="padding: 20px; border-radius: var(--radius-xl); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
          <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main);">Beard Trim, Razor Edge & Shaping</h3>
            <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 4px;">Custom beard fading, trimmer edge alignment, and warm herbal balm treatment.</p>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 8px;">⏱ 20 mins</div>
          </div>
          <div style="text-align: right; margin-left: 20px; flex-shrink: 0;">
            <div style="font-size: 1.4rem; font-weight: 800; color: var(--primary);">₹150</div>
            <div style="display: flex; gap: 8px; margin-top: 8px;">
              <a href="<?php echo esc_url(home_url('/customer/queue?shop_id=' . $shop_id . '&srv=srv-002')); ?>" class="btn btn-primary btn-sm">Queue In</a>
              <a href="<?php echo esc_url(home_url('/booking?shop_id=' . $shop_id . '&service_id=srv-002')); ?>" class="btn btn-secondary btn-sm">Book Slot</a>
            </div>
          </div>
        </div>

        <!-- Service Card 3 -->
        <div class="card" style="padding: 20px; border-radius: var(--radius-xl); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
          <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main);">Ayurvedic Hot Oil Head Champi</h3>
            <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 4px;">Traditional calming Indian head and neck acupressure therapy using medicated herbs.</p>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 8px;">⏱ 20 mins</div>
          </div>
          <div style="text-align: right; margin-left: 20px; flex-shrink: 0;">
            <div style="font-size: 1.4rem; font-weight: 800; color: var(--primary);">₹200</div>
            <div style="display: flex; gap: 8px; margin-top: 8px;">
              <a href="<?php echo esc_url(home_url('/customer/queue?shop_id=' . $shop_id . '&srv=srv-003')); ?>" class="btn btn-primary btn-sm">Queue In</a>
              <a href="<?php echo esc_url(home_url('/booking?shop_id=' . $shop_id . '&service_id=srv-003')); ?>" class="btn btn-secondary btn-sm">Book Slot</a>
            </div>
          </div>
        </div>

        <!-- Service Card 4 -->
        <div class="card" style="padding: 20px; border-radius: var(--radius-xl); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
          <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main);">Royal Grooming Combo (Cut + Beard + D-Tan)</h3>
            <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 4px;">Full signature package: Precision cut, beard styling, herbal face D-Tan, and steam towel.</p>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 8px;">⏱ 50 mins</div>
          </div>
          <div style="text-align: right; margin-left: 20px; flex-shrink: 0;">
            <div style="font-size: 1.4rem; font-weight: 800; color: var(--primary);">₹550</div>
            <div style="display: flex; gap: 8px; margin-top: 8px;">
              <a href="<?php echo esc_url(home_url('/customer/queue?shop_id=' . $shop_id . '&srv=srv-004')); ?>" class="btn btn-primary btn-sm">Queue In</a>
              <a href="<?php echo esc_url(home_url('/booking?shop_id=' . $shop_id . '&service_id=srv-004')); ?>" class="btn btn-secondary btn-sm">Book Slot</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Live Queue Status Card -->
    <div>
      <div class="card" style="padding: 24px; border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); position: sticky; top: 90px; background: #ffffff;">
        <span class="badge badge-open" style="margin-bottom: 12px;">Live Queue Status</span>
        <div style="font-size: 2.2rem; font-weight: 800; color: var(--primary); margin-top: 6px;">~10 mins</div>
        <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 2px;">2 customers currently in digital line</p>
        
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-subtle);">
          <a href="<?php echo esc_url(home_url('/customer/queue?shop_id=' . $shop_id)); ?>" class="btn btn-primary" style="width: 100%; text-align: center;">Join Live Queue</a>
          <a href="<?php echo esc_url(home_url('/booking?shop_id=' . $shop_id)); ?>" class="btn btn-secondary" style="width: 100%; text-align: center; margin-top: 10px;">Advance Booking (UPI/Cash)</a>
        </div>
      </div>
    </div>

  </div>
</main>

<?php
get_footer();
