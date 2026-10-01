<?php
/**
 * BarberLoo Header Template
 *
 * @package BarberLoo
 */

if (!defined('ABSPATH')) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?> BarberLoo - Premium Barber Appointments & Live Queue</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/css/style.css'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

  <!-- Top Announcement Bar (Indian Market Highlight) -->
  <div class="announcement-bar">
    <div class="inner">
      <div style="display: flex; align-items: center; gap: 8px;">
        <span>🇮🇳</span>
        <span>India's Live Digital Salon Queue • Instant UPI & Cash At Salon Supported</span>
      </div>
      <div style="display: flex; gap: 18px; align-items: center;">
        <span>⭐️ 4.95 Rating Across Delhi NCR, Mumbai & Bengaluru</span>
        <span>•</span>
        <span>📍 50,000+ Appointments Booked</span>
      </div>
    </div>
  </div>

  <!-- Primary Navbar -->
  <header class="navbar">
    <div class="container nav-inner">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="brand-logo">
        <div class="brand-icon">✂</div>
        Barber<span class="accent">Loo</span>
      </a>

      <ul class="nav-links">
        <li><a href="<?php echo esc_url(home_url('/customer/discover')); ?>" class="nav-link">Find Barbers</a></li>
        <li><a href="<?php echo esc_url(home_url('/customer/queue')); ?>" class="nav-link">Live Queue</a></li>
        <li><a href="<?php echo esc_url(home_url('/my-bookings')); ?>" class="nav-link">My Bookings</a></li>
        <li><a href="<?php echo esc_url(home_url('/barber/dashboard')); ?>" class="nav-link">For Barbershops</a></li>
      </ul>

      <div class="nav-actions">
        <a href="<?php echo esc_url(home_url('/login')); ?>" class="btn btn-outline btn-sm">Sign In</a>
        <a href="<?php echo esc_url(home_url('/signup')); ?>" class="btn btn-primary btn-sm">Sign Up</a>
      </div>
    </div>
  </header>
