<?php
/**
 * Template: Booking Appointment & Checkout Wizard (WordPress Theme)
 */
get_header();

$shop_id = sanitize_text_field($_GET['shop_id'] ?? '00000000-0000-0000-0000-000000000001');
?>

<main class="container" style="max-width: 680px; padding-top: 48px; padding-bottom: 80px;">
  
  <div style="margin-bottom: 24px;">
    <h1 style="font-size: 2rem; font-weight: 800; color: var(--text-main); margin-bottom: 6px;">Reserve Appointment</h1>
    <p style="color: var(--text-muted); font-size: 0.95rem;">Select your chair slot, payment method, and complete guaranteed booking.</p>
  </div>

  <!-- Booking Summary Card with Barber Rate + Platform Fee Breakdown -->
  <div class="card" style="margin-bottom: 24px; background: #ffffff; border-radius: var(--radius-xl); border-left: 4px solid var(--primary); padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
      <div>
        <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Selected Service</span>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main); margin-top: 2px;">Master Precision Haircut</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 2px;">BarberLoo Heritage Lounge</p>
      </div>
      <div style="text-align: right;">
        <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">⏱ 30 mins</div>
      </div>
    </div>

    <!-- Fee Calculation Breakdown -->
    <div style="background: var(--bg-subtle); padding: 14px 18px; border-radius: var(--radius-lg); font-size: 0.9rem; display: flex; flex-direction: column; gap: 8px;">
      <div style="display: flex; justify-content: space-between; color: var(--text-muted);">
        <span>Barber Rate</span>
        <span style="font-weight: 600; color: var(--text-main);">$40.00</span>
      </div>
      <div style="display: flex; justify-content: space-between; color: var(--text-muted);">
        <span>Platform & Concierge Fee</span>
        <span style="font-weight: 600; color: var(--text-main);">+$5.00</span>
      </div>
      <div id="wp-discount-row" style="display: none; justify-content: space-between; color: #059669; font-weight: 700;">
        <span>Promo Discount</span>
        <span id="wp-discount-val">-$5.00</span>
      </div>
      <div style="display: flex; justify-content: space-between; font-size: 1.15rem; font-weight: 800; border-top: 1px dashed var(--border-strong); padding-top: 8px; margin-top: 4px; color: var(--text-main);">
        <span>Total Checkout</span>
        <span id="wp-total-checkout" style="color: var(--primary);">$45.00</span>
      </div>
    </div>
  </div>

  <!-- Form Wizard Card -->
  <div class="card" style="box-shadow: var(--shadow-md); border-radius: var(--radius-xl); padding: 32px; background: #ffffff;">
    <form id="wp-booking-form" onsubmit="event.preventDefault(); document.getElementById('booking-success-box').style.display='block'; document.getElementById('wp-booking-form').style.display='none';">
      
      <!-- Step 1: Select Date -->
      <div class="form-group">
        <label class="form-label" for="booking-date">1. Choose Date</label>
        <input type="date" id="booking-date" class="form-input" required value="<?php echo date('Y-m-d'); ?>">
      </div>

      <!-- Step 2: Time Slot -->
      <div class="form-group" style="margin-top: 20px;">
        <label class="form-label">2. Available Chair Times</label>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 8px;">
          <label style="border: 1px solid var(--border-strong); padding: 10px; border-radius: var(--radius-md); text-align: center; cursor: pointer;">
            <input type="radio" name="slot" value="10:00 AM" checked> 10:00 AM
          </label>
          <label style="border: 1px solid var(--border-strong); padding: 10px; border-radius: var(--radius-md); text-align: center; cursor: pointer;">
            <input type="radio" name="slot" value="11:30 AM"> 11:30 AM
          </label>
          <label style="border: 1px solid var(--border-strong); padding: 10px; border-radius: var(--radius-md); text-align: center; cursor: pointer;">
            <input type="radio" name="slot" value="02:00 PM"> 02:00 PM
          </label>
        </div>
      </div>

      <!-- Step 3: Promo / Coupon Code -->
      <div class="form-group" style="margin-top: 20px;">
        <label class="form-label">3. Promo / Coupon Code</label>
        <div style="display: flex; gap: 8px;">
          <input type="text" id="wp-coupon-input" class="form-input" placeholder="e.g. WELCOME10, BARBER5" style="text-transform: uppercase;">
          <button type="button" class="btn btn-secondary" onclick="
            var code = document.getElementById('wp-coupon-input').value.trim().toUpperCase();
            if(code === 'WELCOME10' || code === 'BARBER5') {
              document.getElementById('wp-discount-row').style.display='flex';
              document.getElementById('wp-total-checkout').textContent='$40.00';
              alert('Coupon applied! $5 discount activated.');
            } else {
              alert('Invalid coupon code');
            }
          ">Apply</button>
        </div>
      </div>

      <!-- Step 4: Payment Method -->
      <div class="form-group" style="margin-top: 20px;">
        <label class="form-label">4. Select Payment Method</label>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 8px;">
          <label style="border: 2px solid var(--primary); padding: 14px 10px; border-radius: var(--radius-lg); text-align: center; cursor: pointer; background: var(--primary-light);">
            <input type="radio" name="payment_method" value="card" checked>
            <div style="font-weight: 700; margin-top: 4px;">💳 Credit Card</div>
          </label>

          <label style="border: 1px solid var(--border-strong); padding: 14px 10px; border-radius: var(--radius-lg); text-align: center; cursor: pointer;">
            <input type="radio" name="payment_method" value="upi">
            <div style="font-weight: 700; margin-top: 4px;">📱 UPI / GPay</div>
          </label>

          <label style="border: 1px solid var(--border-strong); padding: 14px 10px; border-radius: var(--radius-lg); text-align: center; cursor: pointer;">
            <input type="radio" name="payment_method" value="cash">
            <div style="font-weight: 700; margin-top: 4px;">💵 Cash in Shop</div>
          </label>
        </div>
      </div>

      <!-- Step 5: Customer Details -->
      <div class="form-group" style="margin-top: 20px;">
        <label class="form-label" for="client-name">5. Full Name</label>
        <input type="text" id="client-name" class="form-input" placeholder="Your name" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="client-phone">6. Mobile Phone (for SMS updates)</label>
        <input type="tel" id="client-phone" class="form-input" placeholder="+1 (555) 000-0000" required>
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 24px; padding: 14px; font-weight: 700;">
        Confirm & Pay Appointment ⚡
      </button>
    </form>

    <div id="booking-success-box" style="display: none; text-align: center; padding: 20px;">
      <div style="font-size: 3rem; margin-bottom: 12px;">🎉</div>
      <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main);">Appointment Confirmed & Paid!</h2>
      <p style="color: var(--text-muted); font-size: 0.95rem; margin: 8px 0 20px 0;">Your chair slot is guaranteed at BarberLoo Heritage Lounge.</p>
      <a href="<?php echo esc_url(home_url('/my-bookings')); ?>" class="btn btn-primary">View My Bookings</a>
    </div>
  </div>

</main>

<?php
get_footer();
