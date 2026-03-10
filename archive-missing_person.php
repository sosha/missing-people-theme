<?php
/**
 * Custom Archive Template for Missing Persons
 * Missing People Custom Theme
 */

get_header(); ?>

<section class="archive-header">
    <div class="container archive-header-container">
        <h1 class="archive-title">Search Missing Persons</h1>
        <p class="archive-subtitle">Browse all active investigations in our national database.</p>
    </div>
</section>

<section class="mpr-archive-content">
    <div class="container">
        <?php
// We use the shortcode logic for filtering and layout for consistency,
// but we wrap it in theme-specific styling.
echo do_shortcode('[missing_people_summary layout="grid"]');
?>
    </div>
</section>

<section class="archive-cta">
    <div class="container text-center">
        <h2>Can't find a report?</h2>
        <p>If you're looking for a specific case that isn't listed, or if you need to submit a new report, please use our public reporting tool.</p>
        <div class="cta-actions">
            <a href="<?php echo esc_url(home_url('/report-a-case')); ?>" class="btn-urgent-large">Start Public Report</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
