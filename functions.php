<?php
/**
 * BarberLoo Functions and Definitions
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
