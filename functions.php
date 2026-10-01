<?php
/**
 * BarberLoo Functions and Custom Rewrite Rules
 * Ensures all BarberLoo app pages (/customer/discover, /login, /booking, etc.)
 * render seamlessly within the WordPress site structure without 404s.
 *
 * @package BarberLoo
 */

if (!defined('ABSPATH')) {
    exit;
}

function barberloo_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
}
add_action('after_setup_theme', 'barberloo_theme_setup');

function barberloo_enqueue_theme_scripts() {
    // Theme stylesheet
    wp_enqueue_style('barberloo-theme-style', get_stylesheet_uri(), array(), '1.0.0');

    // Google Fonts
    wp_enqueue_style(
        'barberloo-fonts',
        'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );

    // Enqueue wppusher client
    wp_enqueue_script(
        'barberloo-wppusher',
        get_template_directory_uri() . '/wppusher.js',
        array(),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'barberloo_enqueue_theme_scripts');

/**
 * Register query vars for BarberLoo app routes
 */
function barberloo_register_query_vars($vars) {
    $vars[] = 'barberloo_route';
    $vars[] = 'shop_id';
    $vars[] = 'service_id';
    $vars[] = 'cat';
    $vars[] = 'role';
    return $vars;
}
add_filter('query_vars', 'barberloo_register_query_vars');

/**
 * Add custom rewrite rules so links like /customer/discover.html, /booking.html, /login.html, /barber/dashboard.html
 * are routed directly by WordPress without 404 errors.
 */
function barberloo_add_rewrite_rules() {
    // Customer routes
    add_rewrite_rule('^customer/discover(\.html)?/?$', 'index.php?barberloo_route=customer-discover', 'top');
    add_rewrite_rule('^customer/queue(\.html)?/?$', 'index.php?barberloo_route=customer-queue', 'top');
    add_rewrite_rule('^customer/shop-details(\.html)?/?$', 'index.php?barberloo_route=customer-shop-details', 'top');
    add_rewrite_rule('^customer/profile(\.html)?/?$', 'index.php?barberloo_route=customer-profile', 'top');

    // Booking & Auth routes
    add_rewrite_rule('^booking(\.html)?/?$', 'index.php?barberloo_route=booking', 'top');
    add_rewrite_rule('^my-bookings(\.html)?/?$', 'index.php?barberloo_route=my-bookings', 'top');
    add_rewrite_rule('^login(\.html)?/?$', 'index.php?barberloo_route=login', 'top');
    add_rewrite_rule('^signup(\.html)?/?$', 'index.php?barberloo_route=signup', 'top');

    // Barber PRO routes
    add_rewrite_rule('^barber/dashboard(\.html)?/?$', 'index.php?barberloo_route=barber-dashboard', 'top');
    add_rewrite_rule('^barber/queue(\.html)?/?$', 'index.php?barberloo_route=barber-queue', 'top');
    add_rewrite_rule('^barber/appointments(\.html)?/?$', 'index.php?barberloo_route=barber-appointments', 'top');
    add_rewrite_rule('^barber/shop(\.html)?/?$', 'index.php?barberloo_route=barber-shop', 'top');
    add_rewrite_rule('^barber/services(\.html)?/?$', 'index.php?barberloo_route=barber-services', 'top');
    add_rewrite_rule('^barber/analytics(\.html)?/?$', 'index.php?barberloo_route=barber-analytics', 'top');

    // Admin route
    add_rewrite_rule('^admin/dashboard(\.html)?/?$', 'index.php?barberloo_route=admin-dashboard', 'top');
}
add_action('init', 'barberloo_add_rewrite_rules');

/**
 * Template include handler: Intercepts custom routes and renders the page directly
 */
function barberloo_template_redirect() {
    $route = get_query_var('barberloo_route');
    if (!$route) {
        // Also check REQUEST_URI directly in case rewrite rules need flush
        $uri = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
        $map = array(
            'customer/discover.html' => 'customer-discover',
            'customer/discover'      => 'customer-discover',
            'customer/queue.html'    => 'customer-queue',
            'customer/queue'         => 'customer-queue',
            'customer/shop-details.html' => 'customer-shop-details',
            'customer/shop-details'      => 'customer-shop-details',
            'customer/profile.html'  => 'customer-profile',
            'customer/profile'       => 'customer-profile',
            'booking.html'           => 'booking',
            'booking'                => 'booking',
            'my-bookings.html'       => 'my-bookings',
            'my-bookings'            => 'my-bookings',
            'login.html'             => 'login',
            'login'                  => 'login',
            'signup.html'            => 'signup',
            'signup'                 => 'signup',
            'barber/dashboard.html'  => 'barber-dashboard',
            'barber/dashboard'       => 'barber-dashboard',
            'barber/queue.html'      => 'barber-queue',
            'barber/queue'           => 'barber-queue',
            'barber/appointments.html' => 'barber-appointments',
            'barber/appointments'      => 'barber-appointments',
            'barber/shop.html'       => 'barber-shop',
            'barber/shop'            => 'barber-shop',
            'barber/services.html'   => 'barber-services',
            'barber/services'        => 'barber-services',
            'barber/analytics.html'  => 'barber-analytics',
            'barber/analytics'       => 'barber-analytics',
            'admin/dashboard.html'   => 'admin-dashboard',
            'admin/dashboard'        => 'admin-dashboard',
        );

        if (isset($map[$uri])) {
            $route = $map[$uri];
        }
    }

    if ($route) {
        $template_file = get_template_directory() . '/templates/' . $route . '.php';
        if (file_exists($template_file)) {
            status_header(200);
            include $template_file;
            exit;
        }
    }
}
add_action('template_redirect', 'barberloo_template_redirect');
