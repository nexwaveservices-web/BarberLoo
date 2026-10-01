<?php
/**
 * BarberLoo Main Template File & Homepage (Indian Edition)
 * 
 * @package BarberLoo
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

  <!-- Hero Section -->
  <section style="background: #ffffff; padding: 48px 0 64px 0; border-bottom: 1px solid var(--border-subtle);">
    <div class="container" style="display: grid; grid-template-columns: 1.15fr 1fr; gap: 48px; align-items: center;">
      
      <div>
        <div style="display: inline-flex; align-items: center; gap: 8px; background: #fff7ed; color: #c2410c; padding: 6px 14px; border-radius: var(--radius-full); font-size: 0.8rem; font-weight: 700; margin-bottom: 16px; border: 1px solid #ffedd5;">
          <span>🇮🇳</span> INDIA'S NO. 1 SALON & BARBER QUEUE PLATFORM
        </div>

        <h1 style="font-size: 3.25rem; font-weight: 800; line-height: 1.15; letter-spacing: -0.03em; margin-bottom: 18px; color: var(--text-main);">
          Skip The Wait at Top <span style="color: var(--primary);">Salons in India</span>
        </h1>

        <p style="color: var(--text-muted); font-size: 1.15rem; line-height: 1.6; margin-bottom: 32px; max-width: 520px;">
          Find verified neighborhood barbers, join live digital queues from your phone, or book confirmed slots. Pay with UPI, Google Pay, PhonePe, Cards, or Cash.
        </p>

        <div style="display: flex; gap: 14px; align-items: center; flex-wrap: wrap;">
          <a href="<?php echo esc_url(home_url('/customer/discover')); ?>" class="btn btn-primary btn-lg">
            Find Salons Near You →
          </a>
          <a href="<?php echo esc_url(home_url('/signup')); ?>" class="btn btn-secondary btn-lg">
            Register Your Salon
          </a>
        </div>

        <!-- Trust Badges Row -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-top: 48px; padding-top: 32px; border-top: 1px solid var(--border-subtle);">
          <div>
            <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-main);">Zero Line Waiting</div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">Track position via live SMS</div>
          </div>
          <div>
            <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-main);">Instant UPI Pay</div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">GPay, PhonePe & Paytm</div>
          </div>
          <div>
            <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-main);">100% Verified</div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">Licensed master barbers</div>
          </div>
        </div>
      </div>

      <!-- Hero Visual Card & Product Highlight -->
      <div style="position: relative;">
        <div style="background: #f8f9fa; border-radius: var(--radius-xl); overflow: hidden; border: 1px solid var(--border-subtle); padding: 18px; box-shadow: var(--shadow-md);">
          <img src="https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=800&q=80" alt="Royal Heritage Salon & Barbers" style="width: 100%; height: 320px; object-fit: cover; border-radius: var(--radius-lg);">
          
          <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center;">
            <div>
              <span class="badge badge-open">Live Queue Active</span>
              <h3 style="font-size: 1.2rem; font-weight: 800; margin-top: 6px; color: var(--text-main);">Royal Heritage Salon & Barbers</h3>
              <p style="color: var(--text-muted); font-size: 0.875rem;">Connaught Place, Delhi NCR • ★ 4.95 (142 reviews)</p>
            </div>
            <div style="text-align: right;">
              <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Live Wait</span>
              <div style="font-size: 1.4rem; font-weight: 900; color: var(--primary);">~10 mins</div>
            </div>
          </div>

          <div style="margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center;">
            <div style="font-size: 0.85rem; color: var(--text-muted);">
              Services start from <strong>₹150</strong>
            </div>
            <a href="<?php echo esc_url(home_url('/customer/shop-details?id=00000000-0000-0000-0000-000000000001')); ?>" class="btn btn-primary btn-sm">Join Queue Now</a>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- Popular Services in India Strip -->
  <section style="background: #ffffff; padding: 48px 0; border-bottom: 1px solid var(--border-subtle);">
    <div class="container">
      <div style="text-align: center; max-width: 600px; margin: 0 auto 36px auto;">
        <span class="badge badge-waiting">Indian Men Grooming</span>
        <h2 style="font-size: 1.85rem; font-weight: 800; color: var(--text-main); margin-top: 6px;">Popular Salon Treatments</h2>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Book trending haircut styles, beard sculpting, and wellness treatments</p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
        <div class="card" style="padding: 20px; border-radius: var(--radius-xl); text-align: center; background: #ffffff;">
          <div style="font-size: 2.2rem; margin-bottom: 8px;">✂️</div>
          <h3 style="font-size: 1.1rem; font-weight: 800;">Precision Haircut</h3>
          <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 4px;">Classic scissor cut, fades, and hair wash</p>
          <div style="font-size: 1.1rem; font-weight: 800; color: var(--primary); margin-top: 10px;">From ₹250</div>
        </div>

        <div class="card" style="padding: 20px; border-radius: var(--radius-xl); text-align: center; background: #ffffff;">
          <div style="font-size: 2.2rem; margin-bottom: 8px;">🧔</div>
          <h3 style="font-size: 1.1rem; font-weight: 800;">Beard Sculpt & Trim</h3>
          <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 4px;">Razor edge lineup, fading & organic oil</p>
          <div style="font-size: 1.1rem; font-weight: 800; color: var(--primary); margin-top: 10px;">From ₹150</div>
        </div>

        <div class="card" style="padding: 20px; border-radius: var(--radius-xl); text-align: center; background: #ffffff;">
          <div style="font-size: 2.2rem; margin-bottom: 8px;">💆‍♂️</div>
          <h3 style="font-size: 1.1rem; font-weight: 800;">Ayurvedic Head Champi</h3>
          <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 4px;">20-min soothing hot oil acupressure massage</p>
          <div style="font-size: 1.1rem; font-weight: 800; color: var(--primary); margin-top: 10px;">From ₹200</div>
        </div>

        <div class="card" style="padding: 20px; border-radius: var(--radius-xl); text-align: center; background: #ffffff;">
          <div style="font-size: 2.2rem; margin-bottom: 8px;">✨</div>
          <h3 style="font-size: 1.1rem; font-weight: 800;">D-Tan & Charcoal Cleanup</h3>
          <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 4px;">Instant glow, sun tan removal & facial steam</p>
          <div style="font-size: 1.1rem; font-weight: 800; color: var(--primary); margin-top: 10px;">From ₹400</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Trending Salons Across India -->
  <section style="padding: 64px 0; background: var(--bg-main);">
    <div class="container">
      <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; flex-wrap: wrap; gap: 12px;">
        <div>
          <h2 style="font-size: 1.85rem; font-weight: 800; color: var(--text-main);">Top Salons Across Major Cities</h2>
          <p style="color: var(--text-muted); font-size: 0.95rem;">Delhi NCR, Mumbai, Bengaluru, Hyderabad, and Pune</p>
        </div>
        <a href="<?php echo esc_url(home_url('/customer/discover')); ?>" class="btn btn-secondary btn-sm">Explore All Salons →</a>
      </div>

      <div id="landing-featured-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
        <!-- Salon Card 1 -->
        <div class="card" style="padding:0; overflow:hidden; border-radius:var(--radius-xl); background: #ffffff;">
          <img src="https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=600&q=80" style="width:100%; height:200px; object-fit:cover;" alt="Royal Heritage Salon & Barbers">
          <div style="padding:20px;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <h3 style="font-size:1.15rem; font-weight:800; color:var(--text-main);">Royal Heritage Salon & Barbers</h3>
              <span class="badge badge-open">Open</span>
            </div>
            <p style="color:var(--text-muted); font-size:0.875rem; margin-top:4px;">Connaught Place, Delhi NCR • ★ 4.95 (142 reviews)</p>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; padding-top:14px; border-top:1px solid var(--border-subtle);">
              <div>
                <span style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase;">Live Queue</span>
                <div style="font-size:1.1rem; font-weight:800; color:var(--primary);">~10 mins wait</div>
              </div>
              <a href="<?php echo esc_url(home_url('/customer/shop-details?id=00000000-0000-0000-0000-000000000001')); ?>" class="btn btn-primary btn-sm">Enter Queue</a>
            </div>
          </div>
        </div>

        <!-- Salon Card 2 -->
        <div class="card" style="padding:0; overflow:hidden; border-radius:var(--radius-xl); background: #ffffff;">
          <img src="https://images.unsplash.com/photo-1512690459411-b9245aed614b?auto=format&fit=crop&w=600&q=80" style="width:100%; height:200px; object-fit:cover;" alt="The Crown & Scissor Mens Lounge">
          <div style="padding:20px;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <h3 style="font-size:1.15rem; font-weight:800; color:var(--text-main);">The Crown & Scissor Mens Lounge</h3>
              <span class="badge badge-open">Open</span>
            </div>
            <p style="color:var(--text-muted); font-size:0.875rem; margin-top:4px;">100ft Road, Indiranagar, Bengaluru • ★ 4.92 (98 reviews)</p>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; padding-top:14px; border-top:1px solid var(--border-subtle);">
              <div>
                <span style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase;">Live Queue</span>
                <div style="font-size:1.1rem; font-weight:800; color:var(--primary);">~15 mins wait</div>
              </div>
              <a href="<?php echo esc_url(home_url('/customer/shop-details?id=shop-002')); ?>" class="btn btn-primary btn-sm">Enter Queue</a>
            </div>
          </div>
        </div>

        <!-- Salon Card 3 -->
        <div class="card" style="padding:0; overflow:hidden; border-radius:var(--radius-xl); background: #ffffff;">
          <img src="https://images.unsplash.com/photo-1622286342621-4bd786c2447c?auto=format&fit=crop&w=600&q=80" style="width:100%; height:200px; object-fit:cover;" alt="Urban Cutters & Beard Studio">
          <div style="padding:20px;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <h3 style="font-size:1.15rem; font-weight:800; color:var(--text-main);">Urban Cutters & Beard Studio</h3>
              <span class="badge badge-open">Open</span>
            </div>
            <p style="color:var(--text-muted); font-size:0.875rem; margin-top:4px;">Linking Road, Bandra West, Mumbai • ★ 4.88 (76 reviews)</p>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; padding-top:14px; border-top:1px solid var(--border-subtle);">
              <div>
                <span style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase;">Live Queue</span>
                <div style="font-size:1.1rem; font-weight:800; color:var(--primary);">No Wait (Chair Open)</div>
              </div>
              <a href="<?php echo esc_url(home_url('/customer/shop-details?id=shop-003')); ?>" class="btn btn-primary btn-sm">Enter Queue</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php
get_footer();
