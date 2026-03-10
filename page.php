<?php
/**
 * The template for displaying all pages
 * Missing People Custom Theme
 */

get_header();
?>

<div class="page-header-standard">
    <div class="container">
        <?php the_title('<h1 class="page-title">', '</h1>'); ?>
    </div>
</div>

<div class="container page-content-container">
    <div class="card page-card">
        <?php
while (have_posts()):
    the_post();
    the_content();

    wp_link_pages([
        'before' => '<div class="page-links">' . esc_html__('Pages:', 'mp-theme'),
        'after' => '</div>',
    ]);
endwhile;
?>
    </div>
</div>

<?php
get_footer();
