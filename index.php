<?php get_header(); ?>

<section class="hero-section">
    <div class="container hero-container text-center">
        <h1 class="hero-title">Help Us Bring Them Home</h1>
        <p class="hero-subtitle">Every second counts. Search the national database or report a disappearance immediately.</p>
        
        <div class="hero-search-container">
            <form role="search" method="get" class="hero-search-form" action="<?php echo esc_url(home_url('/')); ?>">
                <input type="search" class="search-field" placeholder="Search by name, location, or OB number..." value="<?php echo get_search_query(); ?>" name="s">
                <input type="hidden" name="post_type" value="missing_person">
                <button type="submit" class="search-submit">Search Database</button>
            </form>
        </div>
        
        <div class="hero-stats">
            <div class="stat-item">
                <span class="stat-number"><?php echo wp_count_posts('missing_person')->publish; ?></span>
                <span class="stat-label">Active Cases</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">24/7</span>
                <span class="stat-label">Support Available</span>
            </div>
        </div>
    </div>
</section>

<section class="latest-reports">
    <div class="container">
        <div class="section-header">
            <h2>Recent Reports</h2>
            <a href="<?php echo esc_url(home_url('/missing-person/')); ?>" class="view-all">View All Cases &rarr;</a>
        </div>
        
        <div class="mpr-summary-wrapper">
             <?php echo do_shortcode('[missing_people_summary limit="6"]'); ?>
        </div>
    </div>
</section>

<section class="how-it-works">
    <div class="container">
        <div class="text-center mb-60">
            <h2 class="section-title">How it Works</h2>
            <p class="section-subtitle">A coordinated effort to bring missing persons home safe.</p>
        </div>
        
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-icon"><span class="dashicons dashicons-edit"></span></div>
                <h3>1. Report</h3>
                <p>Submit a detailed report via our secure public form or through local authorities.</p>
            </div>
            <div class="step-card">
                <div class="step-icon"><span class="dashicons dashicons- megaphone"></span></div>
                <h3>2. Disseminate</h3>
                <p>Our system generates posters and alerts our national network of volunteers and partners.</p>
            </div>
            <div class="step-card">
                <div class="step-icon"><span class="dashicons dashicons-groups"></span></div>
                <h3>3. Locate</h3>
                <p>Using interactive maps and community leads, we coordinate with police to locate the individual.</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-banner">
    <div class="container cta-container">
        <div class="cta-content">
            <h2>Did someone go missing?</h2>
            <p>Your report could be the breakthrough needed to find them. Submit a public report now and alert our national network.</p>
        </div>
        <div class="cta-action">
            <a href="<?php echo esc_url(home_url('/report-a-case')); ?>" class="btn-urgent-large">Start Public Report</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
