<?php
/**
 * BarberLoo Footer Template
 *
 * @package BarberLoo
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
  <!-- Minimal Footer -->
  <footer class="footer">
    <div class="container footer-inner">
      <div style="display: flex; align-items: center; gap: 12px;">
        <div class="brand-icon" style="width: 28px; height: 28px; font-size: 0.9rem;">✂</div>
        <span style="font-weight: 800; color: var(--text-main); font-size: 1.1rem;">BarberLoo</span>
        <span style="color: var(--text-dim);">© <?php echo date('Y'); ?> BarberLoo.in. All rights reserved.</span>
      </div>

      <div style="display: flex; gap: 20px;">
        <a href="<?php echo esc_url(home_url('/customer/discover')); ?>" style="color: var(--text-muted); font-size: 0.875rem;">Discover</a>
        <a href="<?php echo esc_url(home_url('/login')); ?>" style="color: var(--text-muted); font-size: 0.875rem;">Log In</a>
        <a href="<?php echo esc_url(home_url('/barber/dashboard')); ?>" style="color: var(--text-muted); font-size: 0.875rem;">Barber Hub</a>
        <a href="<?php echo esc_url(home_url('/admin/dashboard')); ?>" style="color: var(--text-dim); font-size: 0.875rem;">Admin</a>
      </div>
    </div>
  </footer>

<?php wp_footer(); ?>
</body>
</html>
