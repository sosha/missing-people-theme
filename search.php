<?php get_header(); ?>

<section class="search-results-header archive-header">
    <div class="container">
        <?php if (have_posts()): ?>
            <h1 class="page-title">
                <?php
    printf(esc_html__('Search Results for: %s', 'mp-theme'), '<span>' . get_search_query() . '</span>');
?>
            </h1>
        <?php
else: ?>
            <h1 class="page-title"><?php esc_html_e('Nothing Found', 'mp-theme'); ?></h1>
        <?php
endif; ?>
    </div>
</section>

<section class="search-results-content">
    <div class="container" style="padding: 60px 0;">
        <?php if (have_posts()): ?>
            <div class="mpr-summary-wrapper">
                <?php
    // We use the plugin's grid logic for search results consistency
    echo do_shortcode('[missing_people_summary layout="grid"]');
?>
            </div>
        <?php
else: ?>
            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'mp-theme'); ?></p>
            <div class="hero-search-container" style="max-width: 600px; margin: 40px 0;">
                <?php get_search_form(); ?>
            </div>
        <?php
endif; ?>
    </div>
</section>

<?php get_footer(); ?>
