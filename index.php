<?php get_header(); ?>

<section class="hero-section">
    <div class="container hero-container text-center">
        <h1 class="hero-title"><?php _e('Help Us Bring Them Home', 'mp-theme'); ?></h1>
        <p class="hero-subtitle"><?php _e('Every second counts. Search the national database or report a disappearance immediately.', 'mp-theme'); ?></p>
        
        <div class="hero-search-container">
            <form role="search" method="get" class="hero-search-form" action="<?php echo esc_url(home_url('/')); ?>">
                <input type="search" class="search-field" placeholder="<?php esc_attr_e('Search by name, location, or OB number...', 'mp-theme'); ?>" value="<?php echo get_search_query(); ?>" name="s">
                <input type="hidden" name="post_type" value="missing_person">
                <button type="submit" class="search-submit"><?php _e('Search Database', 'mp-theme'); ?></button>
            </form>
        </div>
        
        <div class="hero-stats">
            <div class="stat-item">
                <span class="stat-number"><?php echo wp_count_posts('missing_person')->publish; ?></span>
                <span class="stat-label"><?php _e('Active Cases', 'mp-theme'); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php _e('24/7', 'mp-theme'); ?></span>
                <span class="stat-label"><?php _e('Support Available', 'mp-theme'); ?></span>
            </div>
        </div>
    </div>
</section>

<section class="latest-reports">
    <div class="container">
        <div class="section-header">
            <h2><?php _e('Recent Reports', 'mp-theme'); ?></h2>
            <a href="<?php echo esc_url(home_url('/missing-person/')); ?>" class="view-all"><?php _e('View All Cases &rarr;', 'mp-theme'); ?></a>
        </div>
        
        <div class="mpr-summary-wrapper">
             <?php echo do_shortcode('[missing_people_summary limit="6"]'); ?>
        </div>
    </div>
</section>

<section class="how-it-works">
    <div class="container">
        <div class="text-center mb-60">
            <h2 class="section-title"><?php _e('How it Works', 'mp-theme'); ?></h2>
            <p class="section-subtitle"><?php _e('A coordinated effort to bring missing persons home safe.', 'mp-theme'); ?></p>
        </div>
        
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-icon"><span class="dashicons dashicons-edit"></span></div>
                <h3><?php _e('1. Report', 'mp-theme'); ?></h3>
                <p><?php _e('Submit a detailed report via our secure public form or through local authorities.', 'mp-theme'); ?></p>
            </div>
            <div class="step-card">
                <div class="step-icon"><span class="dashicons dashicons-megaphone"></span></div>
                <h3><?php _e('2. Disseminate', 'mp-theme'); ?></h3>
                <p><?php _e('Our system generates posters and alerts our national network of volunteers and partners.', 'mp-theme'); ?></p>
            </div>
            <div class="step-card">
                <div class="step-icon"><span class="dashicons dashicons-groups"></span></div>
                <h3><?php _e('3. Locate', 'mp-theme'); ?></h3>
                <p><?php _e('Using interactive maps and community leads, we coordinate with police to locate the individual.', 'mp-theme'); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="cta-banner">
    <div class="container cta-container">
        <div class="cta-content">
            <h2><?php _e('Did someone go missing?', 'mp-theme'); ?></h2>
            <p><?php _e('Your report could be the breakthrough needed to find them. Submit a public report now and alert our national network.', 'mp-theme'); ?></p>
        </div>
        <div class="cta-action">
            <a href="<?php echo esc_url(home_url('/report-a-case')); ?>" class="btn-urgent-large"><?php _e('Start Public Report', 'mp-theme'); ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
