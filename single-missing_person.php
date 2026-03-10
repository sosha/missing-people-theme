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
                    <span class="mpr-badge status-badge status-<?php echo strtolower(str_replace(' ', '-', $status)); ?>">
                        <?php echo esc_html($status); ?>
                    </span>
                    <span class="mpr-badge risk-badge risk-<?php echo strtolower($risk_level); ?> <?php echo($risk_level === 'High') ? 'pulse' : ''; ?>">
                        <?php printf(__('%s Risk', 'mp-theme'), esc_html($risk_level)); ?>
                    </span>
                </div>
                <h1 class="profile-name"><?php the_title(); ?></h1>
                <p class="profile-subtitle"><?php printf(__('Last seen on %s in %s', 'mp-theme'), esc_html($meta['mpr_date_last_seen'][0] ?? __('Unknown Date', 'mp-theme')), esc_html($meta['mpr_last_seen_location'][0] ?? __('Unknown', 'mp-theme'))); ?></p>
            </div>
            <div class="hero-profile-actions">
                <button onclick="window.print();" class="btn-print-poster">
                    <span class="dashicons dashicons-printer"></span> <?php _e('Print Missing Poster', 'mp-theme'); ?>
                </button>
            </div>
        </div>
    </div>

    <div class="container profile-main-grid">
        <aside class="profile-sidebar">
            <div class="profile-photo-card card">
                <img src="<?php echo esc_url($first_image_src); ?>" alt="<?php the_title_attribute(); ?>" class="main-photo">
                <div class="photo-footer">
                    <p><?php printf(__('Report ID: #%d', 'mp-theme'), get_the_ID()); ?></p>
                </div>
            </div>

            <div class="contact-card card urgent-card">
                <h3><?php _e('Have you seen them?', 'mp-theme'); ?></h3>
                <p><?php _e('If you have any information, please contact the authorities immediately.', 'mp-theme'); ?></p>
                <div class="contact-info">
                    <p><strong><?php _e('Police Station:', 'mp-theme'); ?></strong> <?php echo esc_html($meta['mpr_police_station'][0] ?? __('Not Specified', 'mp-theme')); ?></p>
                    <p><strong><?php _e('OB Number:', 'mp-theme'); ?></strong> <?php echo esc_html($meta['mpr_ob_number'][0] ?? __('Pending', 'mp-theme')); ?></p>
                    <p class="phone-highlight"><strong><?php printf(__('Call: %s', 'mp-theme'), esc_html($meta['mpr_police_phone'][0] ?? '999')); ?></strong></p>
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
                <p class="small-text"><?php _e('Follow this case for real-time updates.', 'mp-theme'); ?></p>
                
                <button id="mpr-push-subscribe" class="btn-subscribe-alerts" data-case-id="<?php the_ID(); ?>" style="margin-top: 15px; width: 100%;">
                    <span class="dashicons dashicons-bell"></span> <?php _e('Subscribe to Alerts', 'mp-theme'); ?>
                </button>
            </div>
        </aside>

        <section class="profile-content">
            <div class="facts-grid card">
                <div class="fact-item">
                    <span class="label"><?php _e('Age', 'mp-theme'); ?></span>
                    <span class="value"><?php echo esc_html($meta['mpr_age'][0] ?? 'N/A'); ?></span>
                </div>
                <div class="fact-item">
                    <span class="label"><?php _e('Gender', 'mp-theme'); ?></span>
                    <span class="value"><?php echo esc_html($meta['mpr_gender'][0] ?? 'N/A'); ?></span>
                </div>
                <div class="fact-item">
                    <span class="label"><?php _e('Height', 'mp-theme'); ?></span>
                    <span class="value"><?php echo esc_html($meta['mpr_height'][0] ?? 'N/A'); ?></span>
                </div>
                <div class="fact-item">
                    <span class="label"><?php _e('Ethnicity', 'mp-theme'); ?></span>
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
            
            <div id="mpr-lead-section" class="lead-submission-section card urgent-card">
                <h3><span class="dashicons dashicons-shield"></span> <?php _e('Submit a Secure Lead', 'mp-theme'); ?></h3>
                <p><?php _e('If you have seen this person or have sensitive information, please fill out the form below. This goes directly to the investigation team and is NOT public.', 'mp-theme'); ?></p>
                <form id="mpr-lead-form">
                    <input type="hidden" name="case_id" value="<?php the_ID(); ?>">
                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('mpr_lead_nonce'); ?>">
                    <div class="grid-2 gap-15 mb-15">
                        <input type="text" name="name" placeholder="<?php esc_attr_e('Your Name', 'mp-theme'); ?>" required>
                        <input type="email" name="email" placeholder="<?php esc_attr_e('Your Email', 'mp-theme'); ?>" required>
                    </div>
                    <div class="mb-15">
                        <input type="text" name="phone" placeholder="<?php esc_attr_e('Your Phone (Optional)', 'mp-theme'); ?>">
                    </div>
                    <textarea name="lead" placeholder="<?php esc_attr_e('Describe your lead in detail (Location, time, circumstances...)', 'mp-theme'); ?>" required></textarea>
                    <button type="submit" class="btn-submit-lead"><?php _e('Submit Secure Lead', 'mp-theme'); ?></button>
                    <div class="form-status"></div>
                </form>
            </div>

            <div class="map-section card">
                <h2><?php _e('Last Known Location', 'mp-theme'); ?></h2>
                <div id="mpr-frontend-map"></div>
                <p class="map-caption"><?php echo esc_html($meta['mpr_last_seen_location'][0] ?? ''); ?></p>
            </div>

            <div class="social-share card">
                <h3><?php _e('Share this report', 'mp-theme'); ?></h3>
                <p><?php _e('Dissemination is key. Help us spread the word.', 'mp-theme'); ?></p>
                <div class="share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" class="share-fb" target="_blank"><?php _e('Share on Facebook', 'mp-theme'); ?></a>
                    <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=HELP+FIND+<?php echo urlencode(get_the_title()); ?>" class="share-x" target="_blank"><?php _e('Post on X', 'mp-theme'); ?></a>
                </div>
            </div>

            <div class="discussion-section card">
                <h2><?php _e('Public Discussion', 'mp-theme'); ?></h2>
                <?php
    if (comments_open() || get_comments_number()):
        comments_template();
    endif;
?>
            </div>
        </section>
    </div>
</article>

<!-- Hidden Poster Template for Printing -->
<div id="mpr-poster-template" class="mpr-poster-wrapper">
    <div class="mpr-poster-header"><h1><?php _e('MISSING', 'mp-theme'); ?></h1></div>
    <div class="mpr-poster-image">
        <img src="<?php echo esc_url($first_image_src); ?>" alt="<?php esc_attr_e('Missing Person Poster', 'mp-theme'); ?>">
    </div>
    <div class="mpr-poster-content">
        <h2 class="mpr-poster-name"><?php the_title(); ?></h2>
        <div class="mpr-poster-risk">
            <span class="badge"><?php echo esc_html($status); ?></span>
            <span class="badge"><?php printf(__('%s RISK', 'mp-theme'), esc_html($risk_level)); ?></span>
        </div>
        <div class="mpr-poster-grid">
            <div>
                <h3><?php _e('Personal Info', 'mp-theme'); ?></h3>
                <p><strong><?php _e('Age:', 'mp-theme'); ?></strong> <?php echo esc_html($meta['mpr_age'][0] ?? 'N/A'); ?></p>
                <p><strong><?php _e('Height:', 'mp-theme'); ?></strong> <?php echo esc_html($meta['mpr_height'][0] ?? 'N/A'); ?></p>
                <p><strong><?php _e('Last Seen:', 'mp-theme'); ?></strong> <?php echo esc_html($meta['mpr_date_last_seen'][0] ?? 'N/A'); ?></p>
            </div>
            <div>
                <h3><?php _e('Location', 'mp-theme'); ?></h3>
                <p><?php echo esc_html($meta['mpr_last_seen_location'][0] ?? 'N/A'); ?></p>
                <p><strong><?php _e('Wearing:', 'mp-theme'); ?></strong> <?php echo esc_html($meta['mpr_what_they_were_wearing'][0] ?? 'N/A'); ?></p>
            </div>
        </div>
        <div class="mpr-poster-contact">
            <h2><?php _e('HAVE YOU SEEN THEM?', 'mp-theme'); ?></h2>
            <p><?php printf(__('Report to: %s', 'mp-theme'), esc_html($meta['mpr_police_station'][0] ?? __('Local Police', 'mp-theme'))); ?></p>
            <p><?php printf(__('Call: %s', 'mp-theme'), esc_html($meta['mpr_police_phone'][0] ?? '999')); ?></p>
        </div>
    </div>
    <div class="mpr-poster-footer">
        <p><?php printf(__('Generated by %s - %s', 'mp-theme'), esc_html(get_option('mpr_agency_name', 'Missing People Reporter')), esc_html(get_option('mpr_agency_website', 'missingpeople.co.ke'))); ?></p>
    </div>
</div>

<?php
endwhile; ?>

<?php get_footer(); ?>
