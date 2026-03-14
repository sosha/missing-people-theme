<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$high_risk_cases = function_exists('mp_theme_get_high_risk_cases') ? mp_theme_get_high_risk_cases() : [];
if (!empty($high_risk_cases)): ?>
    <div class="urgent-alert-bar">
        <div class="container">
            <strong><?php _e('High Risk Alerts:', 'mp-theme'); ?></strong>
            <?php foreach ($high_risk_cases as $case): ?>
                <a href="<?php echo esc_url($case['url']); ?>" class="urgent-alert-link">
                    <?php echo esc_html($case['title']); ?>
                    <?php if (!empty($case['location'])): ?>
                        <span class="urgent-alert-location"><?php echo esc_html($case['location']); ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<header id="masthead" class="site-header">
    <div class="container header-container">
        <div class="site-branding">
            <?php
if (has_custom_logo()) {
    the_custom_logo();
}
else {
    echo '<a href="' . esc_url(home_url('/')) . '" rel="home" class="site-title">' . get_bloginfo('name') . '</a>';
}
?>
        </div>

        <nav id="site-navigation" class="main-navigation">
            <?php
wp_nav_menu([
    'theme_location' => 'primary',
    'menu_id' => 'primary-menu',
    'container' => false,
]);
?>
            <div class="header-actions">
                <a href="<?php echo esc_url(home_url('/report-a-case')); ?>" class="btn-urgent"><?php _e('Report a Case', 'mp-theme'); ?></a>
            </div>
        </nav>
        
        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
            <span class="dashicons dashicons-menu"></span>
        </button>
    </div>
</header>

<main id="primary" class="site-main">
