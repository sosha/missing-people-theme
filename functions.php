<?php
/**
 * Missing People Theme functions and definitions
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Enqueue scripts and styles.
 */
function mp_theme_enqueue_assets()
{
    // Google Fonts: Inter
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap', [], null);

    // Main Theme CSS
    wp_enqueue_style('mp-theme-style', get_template_directory_uri() . '/assets/css/main.css', [], '1.0.0');

    // Theme JS
    wp_enqueue_script('mp-theme-js', get_template_directory_uri() . '/assets/js/main.js', [], '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'mp_theme_enqueue_assets');

/**
 * Theme setup.
 */
function mp_theme_setup()
{
    // Add support for professional features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('custom-logo');

    // Register primary menu
    register_nav_menus([
        'primary' => __('Primary Menu', 'mp-theme'),
    ]);
}
add_action('after_setup_theme', 'mp_theme_setup');

/**
 * Filter the search to only show missing_person posts on specific triggers if needed.
 */
function mp_theme_filter_search($query)
{
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
    // Optional: prioritize missing_person posts
    }
}
add_action('pre_get_posts', 'mp_theme_filter_search');
