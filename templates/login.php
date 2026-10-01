<?php
/**
 * Template: Login Page
 */
get_header();
?>

<main class="container" style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 48px 16px 80px 16px;">
  <div class="card" style="width: 100%; max-width: 460px; box-shadow: var(--shadow-md); border-radius: var(--radius-xl); padding: 36px 32px; background: #ffffff;">
    
    <div style="text-align: center; margin-bottom: 28px;">
      <h1 style="font-size: 1.85rem; font-weight: 800; margin-bottom: 6px; color: var(--text-main);">Welcome to BarberLoo</h1>
      <p style="color: var(--text-muted); font-size: 0.95rem;">Sign in to manage appointments, shop desks, and live queues</p>
    </div>

    <form method="post" action="<?php echo esc_url(wp_login_url()); ?>">
      <div class="form-group">
        <label class="form-label" for="user_login">Username or Email</label>
        <input type="text" name="log" id="user_login" class="form-input" required autocomplete="username">
      </div>

      <div class="form-group">
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <label class="form-label" for="user_pass">Password</label>
          <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" style="font-size: 0.8rem; color: var(--primary); font-weight: 600;">Forgot Password?</a>
        </div>
        <input type="password" name="pwd" id="user_pass" class="form-input" required autocomplete="current-password">
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 14px; padding: 12px;">
        Sign In to BarberLoo
      </button>
    </form>

    <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem; color: var(--text-muted);">
      <span>New client? <a href="<?php echo esc_url(home_url('/signup')); ?>" style="color: var(--primary); font-weight: 700;">Sign Up</a></span>
      <span><a href="<?php echo esc_url(home_url('/barber/dashboard')); ?>" style="color: var(--text-main); font-weight: 600;">Barber PRO Desk</a></span>
    </div>

  </div>
</main>

<?php
get_footer();
