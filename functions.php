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

    // Create required pages if they don't exist
    mp_theme_create_required_pages();
}
add_action('after_setup_theme', 'mp_theme_setup');

/**
 * Programmatically create required pages for the plugin.
 */
function mp_theme_create_required_pages()
{
    // Only run if we are in the admin and on theme activation or if explicitly needed
    if (!is_admin())
        return;

    // "Report a Case" page
    $report_page_slug = 'report-a-case';
    if (!get_page_by_path($report_page_slug)) {
        wp_insert_post([
            'post_title' => 'Report a Case',
            'post_content' => '[mpr_public_report_form]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => $report_page_slug,
        ]);
    }
}

/**
 * Customizer settings.
 */
function mp_theme_customize_register($wp_customize)
{
    // Add Brand Colors Section
    $wp_customize->add_section('mp_theme_colors', [
        'title' => __('Brand Colors', 'mp-theme'),
        'priority' => 30,
    ]);

    // Primary Color
    $wp_customize->add_setting('primary_color', [
        'default' => '#D32F2F',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', [
        'label' => __('Primary Accent Color', 'mp-theme'),
        'section' => 'mp_theme_colors',
    ]));

    // Secondary Color
    $wp_customize->add_setting('secondary_color', [
        'default' => '#1976D2',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', [
        'label' => __('Secondary Branding Color', 'mp-theme'),
        'section' => 'mp_theme_colors',
    ]));
}
add_action('customize_register', 'mp_theme_customize_register');

/**
 * Inject Customizer CSS.
 */
function mp_theme_customizer_css()
{
    $primary = get_theme_mod('primary_color', '#D32F2F');
    $secondary = get_theme_mod('secondary_color', '#1976D2');
?>
    <style type="text/css">
        :root {
            --primary: <?php echo esc_attr($primary); ?>;
            --secondary: <?php echo esc_attr($secondary); ?>;
            --primary-dark: <?php echo esc_attr(mpr_adjust_brightness($primary, -20)); ?>;
            --secondary-dark: <?php echo esc_attr(mpr_adjust_brightness($secondary, -20)); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'mp_theme_customizer_css');

/**
 * Helper to adjust hex brightness.
 */
function mpr_adjust_brightness($hex, $steps)
{
    $steps = max(-255, min(255, $steps));
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
    }
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));
    $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
    $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
    $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
    return '#' . $r_hex . $g_hex . $b_hex;
}
