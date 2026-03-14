<?php
/**
 * Custom Archive Template for Missing Persons
 * Missing People Custom Theme
 */

get_header(); ?>

<section class="archive-header">
    <div class="container archive-header-container">
        <h1 class="archive-title"><?php _e('Search Missing Persons', 'mp-theme'); ?></h1>
        <p class="archive-subtitle"><?php _e('Browse all active investigations in our national database.', 'mp-theme'); ?></p>
    </div>
</section>

<section class="mpr-archive-content">
    <div class="container">
        <div class="mpr-archive-map card">
            <div class="archive-map-header">
                <h2><?php _e('Map View', 'mp-theme'); ?></h2>
                <p><?php _e('Explore cases by last known location.', 'mp-theme'); ?></p>
            </div>
            <div id="mpr-archive-map"></div>
        </div>
        <?php
// We use the shortcode logic for filtering and layout for consistency.
echo do_shortcode('[missing_people_summary layout="grid"]');
?>
    </div>
</section>

<section class="archive-cta">
    <div class="container text-center">
        <h2><?php _e("Can't find a report?", 'mp-theme'); ?></h2>
        <p><?php _e("If you're looking for a specific case that isn't listed, or if you need to submit a new report, please use our public reporting tool.", 'mp-theme'); ?></p>
        <div class="cta-actions">
            <a href="<?php echo esc_url(home_url('/report-a-case')); ?>" class="btn-urgent-large"><?php _e('Start Public Report', 'mp-theme'); ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
