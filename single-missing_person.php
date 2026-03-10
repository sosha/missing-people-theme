<?php
/**
 * Custom Single Template for Missing Persons
 * Missing People Custom Theme
 */

if (function_exists('mpr_track_view')) {
    mpr_track_view(get_the_ID());
}

get_header();

while (have_posts()):
    the_post();
    $meta = get_post_custom(get_the_ID());
    $status = $meta['mpr_case_status'][0] ?? 'Missing';
    $risk_level = $meta['mpr_risk_level'][0] ?? 'Low';
    $first_image_src = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: MPR_PLUGIN_URL . 'assets/images/placeholder.jpg';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-missing-person'); ?>>
    <div class="profile-hero">
        <div class="container hero-profile-container">
            <div class="hero-profile-content">
                <div class="badge-row">
                    <span class="mpr-status-badge status-<?php echo strtolower(str_replace(' ', '-', $status)); ?>">
                        <?php echo esc_html($status); ?>
                    </span>
                    <span class="mpr-risk-badge risk-<?php echo strtolower($risk_level); ?> <?php echo($risk_level === 'High') ? 'pulse' : ''; ?>">
                        <?php echo esc_html($risk_level); ?> Risk
                    </span>
                </div>
                <h1 class="profile-name"><?php the_title(); ?></h1>
                <p class="profile-subtitle">Last seen on <?php echo esc_html($meta['mpr_date_last_seen'][0] ?? 'Unknown Date'); ?> in <?php echo esc_html($meta['mpr_last_seen_location'][0] ?? 'Unknown'); ?></p>
            </div>
            <div class="hero-profile-actions">
                <button onclick="window.print();" class="btn-print-poster">
                    <span class="dashicons dashicons-printer"></span> Print Missing Poster
                </button>
            </div>
        </div>
    </div>

    <div class="container profile-main-grid">
        <aside class="profile-sidebar">
            <div class="profile-photo-card card">
                <img src="<?php echo esc_url($first_image_src); ?>" alt="<?php the_title_attribute(); ?>" class="main-photo">
                <div class="photo-footer">
                    <p>Report ID: #<?php the_ID(); ?></p>
                </div>
            </div>

            <div class="contact-card card urgent-card">
                <h3>Have you seen them?</h3>
                <p>If you have any information, please contact the authorities immediately.</p>
                <div class="contact-info">
                    <p><strong>Police Station:</strong> <?php echo esc_html($meta['mpr_police_station'][0] ?? 'Not Specified'); ?></p>
                    <p><strong>OB Number:</strong> <?php echo esc_html($meta['mpr_ob_number'][0] ?? 'Pending'); ?></p>
                    <p class="phone-highlight"><strong>Call: <?php echo esc_html($meta['mpr_police_phone'][0] ?? '999'); ?></strong></p>
                </div>
                <?php
    $agency_name = get_option('mpr_agency_name');
    if ($agency_name):
?>
                <div class="agency-contact-info" style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed rgba(255,255,255,0.2);">
                    <p><strong>Report to:</strong> <?php echo esc_html($agency_name); ?></p>
                    <?php if ($phone = get_option('mpr_agency_phone')): ?>
                        <p><strong>Hotline:</strong> <?php echo esc_html($phone); ?></p>
                    <?php
        endif; ?>
                </div>
                <?php
    endif; ?>
            </div>
            
            <div class="follow-card card">
                <?php if (function_exists('mpr_follow_button'))
        echo mpr_follow_button(get_the_ID()); ?>
                <p class="small-text">Follow this case for real-time updates.</p>
            </div>
        </aside>

        <section class="profile-content">
            <div class="facts-grid card">
                <div class="fact-item">
                    <span class="label">Age</span>
                    <span class="value"><?php echo esc_html($meta['mpr_age'][0] ?? 'N/A'); ?></span>
                </div>
                <div class="fact-item">
                    <span class="label">Gender</span>
                    <span class="value"><?php echo esc_html($meta['mpr_gender'][0] ?? 'N/A'); ?></span>
                </div>
                <div class="fact-item">
                    <span class="label">Height</span>
                    <span class="value"><?php echo esc_html($meta['mpr_height'][0] ?? 'N/A'); ?></span>
                </div>
                <div class="fact-item">
                    <span class="label">Ethnicity</span>
                    <span class="value"><?php echo esc_html($meta['mpr_ethnicity'][0] ?? 'N/A'); ?></span>
                </div>
            </div>

            <div class="description-section card">
                <h2>Circumstances of Disappearance</h2>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
                
                <?php if (!empty($meta['mpr_medical_conditions'][0])): ?>
                    <div class="medical-alert">
                        <h4><span class="dashicons dashicons-warning"></span> Medical / Vulnerability Notes</h4>
                        <p><?php echo esc_html($meta['mpr_medical_conditions'][0]); ?></p>
                    </div>
                <?php
    endif; ?>
            </div>

            <div class="map-section card">
                <h2>Last Known Location</h2>
                <div id="mpr-frontend-map" style="height: 400px; border-radius: 10px; margin-top: 20px;"></div>
                <p class="map-caption"><?php echo esc_html($meta['mpr_last_seen_location'][0] ?? ''); ?></p>
            </div>

            <div class="social-share card">
                <h3>Share this report</h3>
                <p>Dissemination is key. Help us spread the word.</p>
                <div class="share-buttons">
                    <?php // The plugin might handle this, or we add custom theme buttons here ?>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" class="share-fb" target="_blank">Share on Facebook</a>
                    <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=HELP+FIND+<?php echo urlencode(get_the_title()); ?>" class="share-x" target="_blank">Post on X</a>
                </div>
            </div>
        </section>
    </div>
</article>

<!-- Hidden Poster Template for Printing -->
<div id="mpr-poster-template" class="mpr-poster-wrapper">
    <div class="mpr-poster-header"><h1>MISSING</h1></div>
    <div class="mpr-poster-image">
        <img src="<?php echo esc_url($first_image_src); ?>" alt="Missing Person Poster">
    </div>
    <div class="mpr-poster-content">
        <h2 class="mpr-poster-name"><?php the_title(); ?></h2>
        <div class="mpr-poster-risk">
            <span class="badge"><?php echo esc_html($status); ?></span>
            <span class="badge"><?php echo esc_html($risk_level); ?> RISK</span>
        </div>
        <div class="mpr-poster-grid">
            <div>
                <h3>Personal Info</h3>
                <p><strong>Age:</strong> <?php echo esc_html($meta['mpr_age'][0] ?? 'N/A'); ?></p>
                <p><strong>Height:</strong> <?php echo esc_html($meta['mpr_height'][0] ?? 'N/A'); ?></p>
                <p><strong>Last Seen:</strong> <?php echo esc_html($meta['mpr_date_last_seen'][0] ?? 'N/A'); ?></p>
            </div>
            <div>
                <h3>Location</h3>
                <p><?php echo esc_html($meta['mpr_last_seen_location'][0] ?? 'N/A'); ?></p>
                <p><strong>Wearing:</strong> <?php echo esc_html($meta['mpr_what_they_were_wearing'][0] ?? 'N/A'); ?></p>
            </div>
        </div>
        <div class="mpr-poster-contact">
            <h2>HAVE YOU SEEN THEM?</h2>
            <p>Report to: <?php echo esc_html($meta['mpr_police_station'][0] ?? 'Local Police'); ?></p>
            <p>Call: <?php echo esc_html($meta['mpr_police_phone'][0] ?? '999'); ?></p>
        </div>
    </div>
    <div class="mpr-poster-footer">
        <p>Generated by <?php echo esc_html(get_option('mpr_agency_name', 'Missing People Reporter')); ?> - <?php echo esc_html(get_option('mpr_agency_website', 'missingpeople.co.ke')); ?></p>
    </div>
</div>

<?php
endwhile; ?>

<?php get_footer(); ?>
