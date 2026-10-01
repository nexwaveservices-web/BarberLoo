<?php
/**
 * Plugin Name: BarberLoo Live Queue & Booking
 * Plugin URI: https://BarberLoo.in
 * Description: Realtime Barbershop Queue System, Appointments, and Chair Line Tracker powered by BarberLoo.
 * Version: 1.0.0
 * Author: NexWave Services
 * Author URI: https://BarberLoo.in
 * License: GPL2
 * Text Domain: barberloo
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class BarberLooPlugin {
    const APP_URL = 'https://ais-pre-zucdqeuhtt6wm7af2uhsfi-587795275357.asia-east1.run.app';

    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_shortcode('barberloo_queue', array($this, 'render_queue_shortcode'));
        add_shortcode('barberloo_booking', array($this, 'render_booking_shortcode'));
        add_shortcode('barberloo_discover', array($this, 'render_discover_shortcode'));
    }

    public function enqueue_scripts() {
        // Enqueue wppusher.js client
        wp_enqueue_script(
            'wppusher-js',
            self::APP_URL . '/wppusher.js',
            array(),
            '1.0.0',
            true
        );
    }

    /**
     * [barberloo_queue shop_id="..."]
     */
    public function render_queue_shortcode($atts) {
        $atts = shortcode_atts(array(
            'shop_id' => '00000000-0000-0000-0000-000000000001',
            'height'  => '750px',
        ), $atts, 'barberloo_queue');

        $url = esc_url(self::APP_URL . '/customer/queue.html?shop_id=' . $atts['shop_id']);

        return sprintf(
            '<div class="barberloo-embed-wrapper" style="width:100%%; max-width:800px; margin:0 auto;">
                <iframe src="%s" style="width:100%%; height:%s; border:none; border-radius:16px; box-shadow:0 10px 25px rgba(0,0,0,0.08);" allow="clipboard-write"></iframe>
            </div>',
            $url,
            esc_attr($atts['height'])
        );
    }

    /**
     * [barberloo_booking shop_id="..." service_id="..."]
     */
    public function render_booking_shortcode($atts) {
        $atts = shortcode_atts(array(
            'shop_id'    => '00000000-0000-0000-0000-000000000001',
            'service_id' => 'srv-001',
            'height'     => '850px',
        ), $atts, 'barberloo_booking');

        $url = esc_url(self::APP_URL . '/booking.html?shop_id=' . $atts['shop_id'] . '&service_id=' . $atts['service_id']);

        return sprintf(
            '<div class="barberloo-embed-wrapper" style="width:100%%; max-width:800px; margin:0 auto;">
                <iframe src="%s" style="width:100%%; height:%s; border:none; border-radius:16px; box-shadow:0 10px 25px rgba(0,0,0,0.08);" allow="clipboard-write"></iframe>
            </div>',
            $url,
            esc_attr($atts['height'])
        );
    }

    /**
     * [barberloo_discover]
     */
    public function render_discover_shortcode($atts) {
        $atts = shortcode_atts(array(
            'height' => '900px',
        ), $atts, 'barberloo_discover');

        $url = esc_url(self::APP_URL . '/customer/discover.html');

        return sprintf(
            '<div class="barberloo-embed-wrapper" style="width:100%%; max-width:1200px; margin:0 auto;">
                <iframe src="%s" style="width:100%%; height:%s; border:none; border-radius:16px; box-shadow:0 10px 25px rgba(0,0,0,0.08);" allow="clipboard-write"></iframe>
            </div>',
            $url,
            esc_attr($atts['height'])
        );
    }
}

new BarberLooPlugin();
