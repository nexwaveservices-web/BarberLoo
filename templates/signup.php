<?php
/**
 * Template: User & Barber Sign Up Page
 */
get_header();

$role = sanitize_text_field($_GET['role'] ?? 'customer');
?>

<main class="container" style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 48px 16px 80px 16px;">
  <div class="card" style="width: 100%; max-width: 480px; box-shadow: var(--shadow-md); border-radius: var(--radius-xl); padding: 36px 32px; background: #ffffff;">
    
    <div style="text-align: center; margin-bottom: 24px;">
      <h1 style="font-size: 1.85rem; font-weight: 800; margin-bottom: 6px; color: var(--text-main);">Create an Account</h1>
      <p style="color: var(--text-muted); font-size: 0.95rem;">Join BarberLoo for real-time salon queues and appointment scheduling</p>
    </div>

    <!-- Quick Role Tabs -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; background: var(--bg-subtle); padding: 6px; border-radius: var(--radius-full); margin-bottom: 24px;">
      <a href="<?php echo esc_url(home_url('/signup?role=customer')); ?>" class="btn <?php echo ($role !== 'barber') ? 'btn-primary' : 'btn-secondary'; ?> btn-sm" style="text-align: center; border-radius: var(--radius-full);">
        Customer
      </a>
      <a href="<?php echo esc_url(home_url('/signup?role=barber')); ?>" class="btn <?php echo ($role === 'barber') ? 'btn-primary' : 'btn-secondary'; ?> btn-sm" style="text-align: center; border-radius: var(--radius-full);">
        Salon Partner
      </a>
    </div>

    <form method="post" action="<?php echo esc_url(wp_registration_url()); ?>">
      <div class="form-group">
        <label class="form-label" for="user_name">Full Name</label>
        <input type="text" name="full_name" id="user_name" class="form-input" placeholder="e.g. Rahul Sharma" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="user_phone">Mobile Number (For WhatsApp / SMS live queue updates)</label>
        <input type="tel" name="phone" id="user_phone" class="form-input" placeholder="+91 98765 43210" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="user_email">Email Address</label>
        <input type="email" name="user_email" id="user_email" class="form-input" placeholder="you@example.com" required autocomplete="email">
      </div>

      <div class="form-group">
        <label class="form-label" for="user_password">Password (Minimum 6 characters)</label>
        <input type="password" name="user_pass" id="user_password" class="form-input" placeholder="••••••••" minlength="6" required autocomplete="new-password">
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 20px;">
        <button type="submit" class="btn btn-primary" style="padding: 12px; font-weight: 700;">
          Sign Up
        </button>
        <a href="<?php echo esc_url(home_url('/login')); ?>" class="btn btn-secondary" style="padding: 12px; font-weight: 700; text-align: center;">
          Sign In
        </a>
      </div>
    </form>

    <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-subtle); text-align: center; font-size: 0.875rem; color: var(--text-muted);">
      Already have an account? <a href="<?php echo esc_url(home_url('/login')); ?>" style="color: var(--primary); font-weight: 700;">Sign in directly</a>
    </div>

  </div>
</main>

<?php
get_footer();
