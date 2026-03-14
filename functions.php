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
    wp_enqueue_style('mp-theme-style', get_template_directory_uri() . '/assets/css/main.css', [], '0.1.0');

    $script_deps = [];
    $map_data = null;

    // Archive map assets (Leaflet + MarkerCluster)
    if (is_post_type_archive('missing_person')) {
        wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], '1.9.4');
        wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], '1.9.4', true);

        wp_enqueue_style('leaflet-markercluster-css', 'https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css', [], '1.5.3');
        wp_enqueue_style('leaflet-markercluster-default-css', 'https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css', [], '1.5.3');
        wp_enqueue_script('leaflet-markercluster-js', 'https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js', ['leaflet-js'], '1.5.3', true);

        $script_deps = ['leaflet-js', 'leaflet-markercluster-js'];
        $map_data = mp_theme_get_map_cases();
    }

    // Theme JS
    wp_enqueue_script('mp-theme-js', get_template_directory_uri() . '/assets/js/main.js', $script_deps, '0.1.0', true);
    wp_localize_script('mp-theme-js', 'mp_theme_vars', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
    if ($map_data) {
        wp_localize_script('mp-theme-js', 'mp_theme_map_data', $map_data);
    }
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

    // "Get Alerts" page
    $alerts_page_slug = 'alerts';
    if (!get_page_by_path($alerts_page_slug)) {
        wp_insert_post([
            'post_title' => 'Get Alerts',
            'post_content' => '[mp_theme_alerts_landing]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => $alerts_page_slug,
        ]);
    }

    // "Volunteer" page
    $volunteer_page_slug = 'volunteer';
    if (!get_page_by_path($volunteer_page_slug)) {
        wp_insert_post([
            'post_title' => 'Volunteer',
            'post_content' => '[mp_theme_volunteer_signup][mp_theme_partners_directory]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => $volunteer_page_slug,
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
 * Output Open Graph / Twitter cards for missing person cases.
 */
function mp_theme_output_social_meta()
{
    if (!is_singular('missing_person')) {
        return;
    }

    $post_id = get_the_ID();
    $title = get_the_title($post_id);
    $url = get_permalink($post_id);
    $description = wp_strip_all_tags(get_the_excerpt($post_id));
    if (!$description) {
        $description = wp_trim_words(wp_strip_all_tags(get_the_content($post_id)), 28, '...');
    }

    if (function_exists('mpr_get_case_image_url')) {
        $image = mpr_get_case_image_url($post_id, 'large');
    }
    else {
        $image = get_the_post_thumbnail_url($post_id, 'large');
    }
    if (!$image) {
        $image = MPR_PLUGIN_URL . 'assets/images/placeholder.svg';
    }
?>
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php echo esc_attr($title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <meta property="og:url" content="<?php echo esc_url($url); ?>">
    <meta property="og:image" content="<?php echo esc_url($image); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
    <meta name="twitter:image" content="<?php echo esc_url($image); ?>">
<?php
}
add_action('wp_head', 'mp_theme_output_social_meta', 5);

/**
 * Alerts landing page shortcode.
 */
function mp_theme_alerts_landing_shortcode()
{
    ob_start();
?>
    <div class="alerts-landing card">
        <h2><?php _e('Stay Updated on Missing Persons Cases', 'mp-theme'); ?></h2>
        <p><?php _e('Subscribe to alerts that matter most to you. You can receive updates for all cases, new cases only, weekly good‑news digests, or high‑risk alerts.', 'mp-theme'); ?></p>
        <div class="alerts-subscribe-box">
            <select id="mpr-global-sub-type" class="mpr-select">
                <option value="all_updates"><?php _e('All Status Updates', 'mp-theme'); ?></option>
                <option value="new_cases"><?php _e('New Cases Only', 'mp-theme'); ?></option>
                <option value="weekly_digest"><?php _e('Weekly Good News Digest', 'mp-theme'); ?></option>
                <option value="risk" data-filter="High"><?php _e('High Risk Only', 'mp-theme'); ?></option>
            </select>
            <button id="mpr-push-subscribe-global" class="btn-subscribe-alerts">
                <span class="dashicons dashicons-bell"></span> <?php _e('Subscribe to Alerts', 'mp-theme'); ?>
            </button>
        </div>
        <p class="small-text"><?php _e('You can unsubscribe at any time in your browser notification settings.', 'mp-theme'); ?></p>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('mp_theme_alerts_landing', 'mp_theme_alerts_landing_shortcode');

/**
 * Partners directory shortcode (reads JSON file).
 */
function mp_theme_partners_directory_shortcode()
{
    $path = get_template_directory() . '/assets/data/partners.json';
    $items = [];
    if (file_exists($path)) {
        $raw = file_get_contents($path);
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            $items = $decoded;
        }
    }

    ob_start();
?>
    <div class="partners-directory card">
        <h2><?php _e('Partner & Volunteer Network', 'mp-theme'); ?></h2>
        <p><?php _e('Organizations and volunteers working together to support investigations.', 'mp-theme'); ?></p>
        <?php if (!empty($items)): ?>
            <div class="partners-grid">
                <?php foreach ($items as $item): ?>
                    <div class="partner-card">
                        <h3><?php echo esc_html($item['name'] ?? ''); ?></h3>
                        <?php if (!empty($item['region'])): ?>
                            <p class="partner-meta"><?php echo esc_html($item['region']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($item['focus'])): ?>
                            <p><?php echo esc_html($item['focus']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($item['link'])): ?>
                            <a class="partner-link" href="<?php echo esc_url($item['link']); ?>" target="_blank" rel="noreferrer"><?php _e('Learn more', 'mp-theme'); ?></a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p><?php _e('Partners will appear here once listed by the administrators.', 'mp-theme'); ?></p>
        <?php endif; ?>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('mp_theme_partners_directory', 'mp_theme_partners_directory_shortcode');

/**
 * Volunteer signup shortcode + AJAX handler.
 */
function mp_theme_volunteer_signup_shortcode()
{
    $nonce = wp_create_nonce('mp_theme_volunteer_nonce');
    ob_start();
?>
    <div class="volunteer-signup card">
        <h2><?php _e('Volunteer to Help', 'mp-theme'); ?></h2>
        <p><?php _e('Join the network to help distribute alerts, posters, and verified information.', 'mp-theme'); ?></p>
        <form id="mp-volunteer-form">
            <input type="hidden" name="action" value="mp_theme_volunteer_signup">
            <input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>">
            <div class="grid-2 gap-15 mb-15">
                <input type="text" name="name" placeholder="<?php esc_attr_e('Full Name', 'mp-theme'); ?>" required>
                <input type="email" name="email" placeholder="<?php esc_attr_e('Email Address', 'mp-theme'); ?>" required>
            </div>
            <div class="grid-2 gap-15 mb-15">
                <input type="text" name="phone" placeholder="<?php esc_attr_e('Phone Number', 'mp-theme'); ?>">
                <input type="text" name="location" placeholder="<?php esc_attr_e('County / City', 'mp-theme'); ?>">
            </div>
            <textarea name="skills" placeholder="<?php esc_attr_e('How can you help? (e.g. search, printing, legal, counseling)', 'mp-theme'); ?>" required></textarea>
            <button type="submit" class="btn-submit-lead"><?php _e('Submit Volunteer Request', 'mp-theme'); ?></button>
            <div class="form-status"></div>
        </form>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('mp_theme_volunteer_signup', 'mp_theme_volunteer_signup_shortcode');

function mp_theme_handle_volunteer_signup()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mp_theme_volunteer_nonce')) {
        wp_send_json_error(['message' => __('Security check failed.', 'mp-theme')]);
    }

    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $location = sanitize_text_field($_POST['location'] ?? '');
    $skills = sanitize_textarea_field($_POST['skills'] ?? '');

    if (!$name || !$email || !$skills) {
        wp_send_json_error(['message' => __('Please fill in all required fields.', 'mp-theme')]);
    }

    $admin_email = get_option('admin_email');
    $subject = sprintf(__('New Volunteer Signup: %s', 'mp-theme'), $name);
    $message = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\nLocation: {$location}\nSkills: {$skills}\n";

    wp_mail($admin_email, $subject, $message);
    wp_send_json_success(['message' => __('Thank you! We will contact you soon.', 'mp-theme')]);
}
add_action('wp_ajax_mp_theme_volunteer_signup', 'mp_theme_handle_volunteer_signup');
add_action('wp_ajax_nopriv_mp_theme_volunteer_signup', 'mp_theme_handle_volunteer_signup');

/**
 * Fetch map data for archive clustering.
 */
function mp_theme_get_map_cases()
{
    $args = [
        'post_type' => 'missing_person',
        'post_status' => 'publish',
        'posts_per_page' => 500,
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'mpr_latitude',
                'compare' => 'EXISTS',
            ],
            [
                'key' => 'mpr_longitude',
                'compare' => 'EXISTS',
            ],
        ],
    ];

    $query = new WP_Query($args);
    $cases = [];
    while ($query->have_posts()) {
        $query->the_post();
        $id = get_the_ID();
        $lat = get_post_meta($id, 'mpr_latitude', true);
        $lng = get_post_meta($id, 'mpr_longitude', true);
        if ($lat === '' || $lng === '')
            continue;

        $status = get_post_meta($id, 'mpr_case_status', true) ?: 'Missing';
        $risk = get_post_meta($id, 'mpr_risk_level', true) ?: 'Low';
        $location = get_post_meta($id, 'mpr_last_seen_location', true);

        if (function_exists('mpr_get_case_image_url')) {
            $image = mpr_get_case_image_url($id, 'medium');
        }
        else {
            $image = get_the_post_thumbnail_url($id, 'medium');
        }

        $cases[] = [
            'id' => $id,
            'title' => get_the_title(),
            'lat' => (float)$lat,
            'lng' => (float)$lng,
            'status' => $status,
            'risk' => $risk,
            'location' => $location,
            'url' => get_permalink(),
            'image' => $image ? $image : MPR_PLUGIN_URL . 'assets/images/placeholder.svg',
        ];
    }
    wp_reset_postdata();

    return [
        'cases' => $cases,
        'filters' => [
            'search' => isset($_GET['mpr_search']) ? sanitize_text_field($_GET['mpr_search']) : '',
            'status' => isset($_GET['mpr_status']) ? sanitize_text_field($_GET['mpr_status']) : '',
            'risk' => isset($_GET['mpr_risk']) ? sanitize_text_field($_GET['mpr_risk']) : '',
            'loc' => isset($_GET['mpr_loc']) ? sanitize_text_field($_GET['mpr_loc']) : '',
        ],
    ];
}

/**
 * Get high-risk cases for the alert banner.
 */
function mp_theme_get_high_risk_cases()
{
    $cached = get_transient('mp_theme_high_risk_cases');
    if ($cached !== false) {
        return $cached;
    }

    $args = [
        'post_type' => 'missing_person',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'mpr_risk_level',
                'value' => 'High',
                'compare' => '=',
            ],
            [
                'key' => 'mpr_case_status',
                'value' => 'Missing',
                'compare' => '=',
            ],
        ],
    ];

    $query = new WP_Query($args);
    $items = [];
    while ($query->have_posts()) {
        $query->the_post();
        $items[] = [
            'title' => get_the_title(),
            'url' => get_permalink(),
            'location' => get_post_meta(get_the_ID(), 'mpr_last_seen_location', true),
        ];
    }
    wp_reset_postdata();

    set_transient('mp_theme_high_risk_cases', $items, 10 * MINUTE_IN_SECONDS);
    return $items;
}

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
