<?php
/**
 * BarberLoo Main Template File
 * 
 * @package BarberLoo
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main" style="min-height: 80vh; padding: 20px 0;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 16px;">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
        else :
            ?>
            <div style="text-align: center; padding: 60px 20px;">
                <h1 style="font-size: 2.2rem; font-weight: 800; color: #0f172a; margin-bottom: 12px;">Welcome to BarberLoo</h1>
                <p style="color: #64748b; font-size: 1.1rem; max-width: 600px; margin: 0 auto 30px auto;">
                    Premier Barber Appointments, Live Queue Tracking, and Chair Management.
                </p>
                <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
                    <a href="<?php echo esc_url(home_url('/customer/discover.html')); ?>" style="background: #0f172a; color: #ffffff; padding: 12px 24px; border-radius: 9999px; text-decoration: none; font-weight: 700;">Find Barbers</a>
                    <a href="<?php echo esc_url(home_url('/customer/queue.html')); ?>" style="background: #f1f5f9; color: #0f172a; padding: 12px 24px; border-radius: 9999px; text-decoration: none; font-weight: 700;">Live Queue</a>
                </div>
            </div>
            <?php
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
