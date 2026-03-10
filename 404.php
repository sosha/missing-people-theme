<?php get_header(); ?>

<section class="error-404 not-found">
    <div class="container text-center" style="padding: 100px 0;">
        <h1 class="page-title" style="font-size: 8rem; font-weight: 900; color: var(--primary);">404</h1>
        <header class="page-header">
            <h2 class="page-title">Oops! That page can&rsquo;t be found.</h2>
        </header>

        <div class="page-content">
            <p>It looks like nothing was found at this location. Perhaps try a search?</p>
            <div class="hero-search-container" style="max-width: 600px; margin: 40px auto;">
                <?php get_search_form(); ?>
            </div>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-urgent" style="display: inline-block; margin-top: 20px;">Return to Homepage</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
