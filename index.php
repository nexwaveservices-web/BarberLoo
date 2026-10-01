<?php
/**
 * BarberLoo Main Template File & Homepage
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
        <div style="display: inline-flex; align-items: center; gap: 8px; background: var(--primary-light); color: var(--primary); padding: 6px 14px; border-radius: var(--radius-full); font-size: 0.8rem; font-weight: 700; margin-bottom: 16px;">
          <span>★</span> MODERN GROOMING CONCIERGE
        </div>

        <h1 style="font-size: 3.25rem; font-weight: 800; line-height: 1.15; letter-spacing: -0.03em; margin-bottom: 18px; color: var(--text-main);">
          Elevate Your Style with <span style="color: var(--primary);">Premier Barbers</span>
        </h1>

        <p style="color: var(--text-muted); font-size: 1.15rem; line-height: 1.6; margin-bottom: 32px; max-width: 520px;">
          Discover handpicked neighborhood barbershops designed for craft, precision, and everyday confidence. Join a live queue remotely or reserve a guaranteed chair slot.
        </p>

        <div style="display: flex; gap: 14px; align-items: center; flex-wrap: wrap;">
          <a href="<?php echo esc_url(home_url('/customer/discover')); ?>" class="btn btn-primary btn-lg">
            Explore Barbershops →
          </a>
          <a href="<?php echo esc_url(home_url('/signup')); ?>" class="btn btn-secondary btn-lg">
            Register Shop
          </a>
        </div>

        <!-- Trust Badges Row -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-top: 48px; padding-top: 32px; border-top: 1px solid var(--border-subtle);">
          <div>
            <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-main);">Live Queue</div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">Track position from phone</div>
          </div>
          <div>
            <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-main);">Guaranteed</div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">No double bookings</div>
          </div>
          <div>
            <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-main);">24/7 Access</div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">Instant slot confirmation</div>
          </div>
        </div>
      </div>

      <!-- Hero Visual Card & Product Highlight -->
      <div style="position: relative;">
        <div style="background: #f8f9fa; border-radius: var(--radius-xl); overflow: hidden; border: 1px solid var(--border-subtle); padding: 18px; box-shadow: var(--shadow-md);">
          <img src="https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=1000&q=80" alt="Premier Grooming" style="width: 100%; height: 380px; object-fit: cover; border-radius: var(--radius-lg);">
          
          <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding: 4px 6px;">
            <div>
              <span class="badge badge-open">Realtime Sync Active</span>
              <h3 style="font-size: 1.15rem; font-weight: 700; margin-top: 6px;">BarberLoo Heritage Lounge</h3>
              <p style="font-size: 0.85rem; color: var(--text-muted);">Premier Grooming Experience</p>
            </div>
            <div style="text-align: right;">
              <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">~15m</div>
              <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Current Wait</div>
            </div>
          </div>
        </div>

        <!-- Floating Badge Chip -->
        <div style="position: absolute; top: -14px; right: -14px; background: #ffffff; padding: 10px 18px; border-radius: var(--radius-full); box-shadow: var(--shadow-lg); border: 1px solid var(--border-subtle); display: flex; align-items: center; gap: 8px;">
          <span style="color: #f59e0b; font-size: 1.1rem;">★</span>
          <span style="font-weight: 700; font-size: 0.9rem;">4.95 Rating</span>
        </div>
      </div>

    </div>
  </section>

  <!-- Service Categories Grid -->
  <section class="container" style="padding: 56px 16px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 28px;">
      <div>
        <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main);">Browse by Treatment</h2>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Select a specialty service to find certified barbers near you</p>
      </div>
      <a href="<?php echo esc_url(home_url('/customer/discover.html')); ?>" style="font-weight: 600; color: var(--primary); font-size: 0.9rem;">View all services →</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 16px;">
      <a href="<?php echo esc_url(home_url('/customer/discover.html?cat=fades')); ?>" class="category-pill active" style="text-decoration:none;">
        <div class="category-icon">💈</div>
        <div class="category-title">Skin Fades</div>
        <div class="category-count">18 Barbers</div>
      </a>

      <a href="<?php echo esc_url(home_url('/customer/discover.html?cat=beards')); ?>" class="category-pill" style="text-decoration:none;">
        <div class="category-icon">🧔🏻</div>
        <div class="category-title">Beard Sculpt</div>
        <div class="category-count">24 Barbers</div>
      </a>

      <a href="<?php echo esc_url(home_url('/customer/discover.html?cat=shave')); ?>" class="category-pill" style="text-decoration:none;">
        <div class="category-icon">🪒</div>
        <div class="category-title">Hot Shaves</div>
        <div class="category-count">12 Barbers</div>
      </a>

      <a href="<?php echo esc_url(home_url('/customer/discover.html?cat=scissor')); ?>" class="category-pill" style="text-decoration:none;">
        <div class="category-icon">✂️</div>
        <div class="category-title">Scissor Cuts</div>
        <div class="category-count">30 Barbers</div>
      </a>

      <a href="<?php echo esc_url(home_url('/customer/discover.html?cat=styling')); ?>" class="category-pill" style="text-decoration:none;">
        <div class="category-icon">🧴</div>
        <div class="category-title">Scalp Spa</div>
        <div class="category-count">9 Barbers</div>
      </a>

      <a href="<?php echo esc_url(home_url('/customer/discover.html?cat=executive')); ?>" class="category-pill" style="text-decoration:none;">
        <div class="category-icon">👑</div>
        <div class="category-title">Executive Combo</div>
        <div class="category-count">15 Barbers</div>
      </a>
    </div>
  </section>

  <!-- Live Featured Shops Spotlight -->
  <section style="background: #ffffff; padding: 64px 0; border-top: 1px solid var(--border-subtle); border-bottom: 1px solid var(--border-subtle);">
    <div class="container">
      <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px;">
        <div>
          <h2 style="font-size: 1.85rem; font-weight: 800; color: var(--text-main);">Trending Barbershops</h2>
          <p style="color: var(--text-muted); font-size: 0.95rem;">Verified partner shops with active real-time queueing</p>
        </div>
        <a href="<?php echo esc_url(home_url('/customer/discover')); ?>" class="btn btn-secondary btn-sm">Explore All Shops →</a>
      </div>

      <div id="landing-featured-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
        <!-- Barber Shop Card 1 -->
        <div class="card" style="padding:0; overflow:hidden; border-radius:var(--radius-xl); transition: transform 0.2s, box-shadow 0.2s;">
          <img src="https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=600&q=80" style="width:100%; height:200px; object-fit:cover;" alt="Crown & Blade Parlour">
          <div style="padding:20px;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <h3 style="font-size:1.15rem; font-weight:800; color:var(--text-main);">Crown & Blade Parlour</h3>
              <span class="badge badge-open">Open</span>
            </div>
            <p style="color:var(--text-muted); font-size:0.875rem; margin-top:4px;">Downtown San Francisco • ★ 4.9 (128 reviews)</p>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; padding-top:14px; border-top:1px solid var(--border-subtle);">
              <div>
                <span style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase;">Live Wait</span>
                <div style="font-size:1.1rem; font-weight:800; color:var(--primary);">~10 mins (2 in line)</div>
              </div>
              <a href="<?php echo esc_url(home_url('/customer/shop-details?id=00000000-0000-0000-0000-000000000001')); ?>" class="btn btn-primary btn-sm">Enter Queue</a>
            </div>
          </div>
        </div>

        <!-- Barber Shop Card 2 -->
        <div class="card" style="padding:0; overflow:hidden; border-radius:var(--radius-xl); transition: transform 0.2s, box-shadow 0.2s;">
          <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=600&q=80" style="width:100%; height:200px; object-fit:cover;" alt="The Vintage Razor">
          <div style="padding:20px;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <h3 style="font-size:1.15rem; font-weight:800; color:var(--text-main);">The Vintage Razor</h3>
              <span class="badge badge-open">Open</span>
            </div>
            <p style="color:var(--text-muted); font-size:0.875rem; margin-top:4px;">Mission District • ★ 4.8 (94 reviews)</p>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; padding-top:14px; border-top:1px solid var(--border-subtle);">
              <div>
                <span style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase;">Live Wait</span>
                <div style="font-size:1.1rem; font-weight:800; color:var(--primary);">~25 mins (4 in line)</div>
              </div>
              <a href="<?php echo esc_url(home_url('/customer/shop-details?id=00000000-0000-0000-0000-000000000002')); ?>" class="btn btn-primary btn-sm">Enter Queue</a>
            </div>
          </div>
        </div>

        <!-- Barber Shop Card 3 -->
        <div class="card" style="padding:0; overflow:hidden; border-radius:var(--radius-xl); transition: transform 0.2s, box-shadow 0.2s;">
          <img src="https://images.unsplash.com/photo-1622286342621-4bd786c2447c?auto=format&fit=crop&w=600&q=80" style="width:100%; height:200px; object-fit:cover;" alt="Apex Atelier Gentlemen">
          <div style="padding:20px;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <h3 style="font-size:1.15rem; font-weight:800; color:var(--text-main);">Apex Atelier Gentlemen</h3>
              <span class="badge badge-open">Open</span>
            </div>
            <p style="color:var(--text-muted); font-size:0.875rem; margin-top:4px;">Financial District • ★ 5.0 (62 reviews)</p>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; padding-top:14px; border-top:1px solid var(--border-subtle);">
              <div>
                <span style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase;">Live Wait</span>
                <div style="font-size:1.1rem; font-weight:800; color:var(--primary);">No Wait (Chair Open)</div>
              </div>
              <a href="<?php echo esc_url(home_url('/customer/shop-details?id=00000000-0000-0000-0000-000000000003')); ?>" class="btn btn-primary btn-sm">Enter Queue</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Brand Trust & Perks Banner -->
  <section class="container" style="padding: 56px 16px;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; background: #ffffff; padding: 32px; border-radius: var(--radius-xl); border: 1px solid var(--border-subtle); box-shadow: var(--shadow-sm); text-align: center;">
      <div>
        <div style="font-size: 2rem; margin-bottom: 8px;">⚡</div>
        <h4 style="font-weight: 700; margin-bottom: 4px;">Remote Walk-Ins</h4>
        <p style="font-size: 0.85rem; color: var(--text-muted);">Hop into line from home, coffee shop, or office.</p>
      </div>

      <div>
        <div style="font-size: 2rem; margin-bottom: 8px;">⏱</div>
        <h4 style="font-weight: 700; margin-bottom: 4px;">Dynamic Wait Timing</h4>
        <p style="font-size: 0.85rem; color: var(--text-muted);">Accurately based on services ahead, not guesses.</p>
      </div>

      <div>
        <div style="font-size: 2rem; margin-bottom: 8px;">🛡</div>
        <h4 style="font-weight: 700; margin-bottom: 4px;">Verified Masters</h4>
        <p style="font-size: 0.85rem; color: var(--text-muted);">Licensed barbers with proven reviews & photos.</p>
      </div>

      <div>
        <div style="font-size: 2rem; margin-bottom: 8px;">💳</div>
        <h4 style="font-weight: 700; margin-bottom: 4px;">Instant Confirmation</h4>
        <p style="font-size: 0.85rem; color: var(--text-muted);">Real-time SMS & web ticket progression.</p>
      </div>
    </div>
  </section>

<?php
get_footer();
