</main><!-- #primary -->

<footer id="colophon" class="site-footer">
    <div class="container footer-container">
        <div class="footer-widgets">
            <div class="footer-info">
                <h3><?php bloginfo('name'); ?></h3>
                <p><?php bloginfo('description'); ?></p>
            </div>
        <div class="footer-links">
            <h4><?php _e('Quick Links', 'mp-theme'); ?></h4>
            <ul>
                <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('Home', 'mp-theme'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/missing-person/')); ?>"><?php _e('All Missing Persons', 'mp-theme'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>"><?php _e('Privacy Policy', 'mp-theme'); ?></a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4><?php _e('Stay Informed', 'mp-theme'); ?></h4>
            <p><?php _e('Get real-time alerts on missing person cases.', 'mp-theme'); ?></p>
            <div class="footer-subscription-box" style="margin-top: 20px;">
                <select id="mpr-global-sub-type" class="mpr-select" style="width: 100%; margin-bottom: 10px; padding: 10px; border-radius: 5px; background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2);">
                    <option value="all_updates"><?php _e('All Status Updates', 'mp-theme'); ?></option>
                    <option value="new_cases"><?php _e('New Cases Only', 'mp-theme'); ?></option>
                    <option value="weekly_digest"><?php _e('Weekly Good News Digest', 'mp-theme'); ?></option>
                    <option value="risk" data-filter="High"><?php _e('High Risk Only', 'mp-theme'); ?></option>
                </select>
                <button id="mpr-push-subscribe-global" class="btn-subscribe-alerts" style="width: 100%;">
                    <span class="dashicons dashicons-bell"></span> <?php _e('Subscribe to Alerts', 'mp-theme'); ?>
                </button>
            </div>
        </div>
        <div class="footer-contact">
            <h4><?php _e('Contact Us', 'mp-theme'); ?></h4>
            <p><?php printf(__('Email: %s', 'mp-theme'), 'help@missingpeople.co.ke'); ?></p>
            <p><?php _e('Support: 24/7 Crisis Line', 'mp-theme'); ?></p>
        </div>
    </div>
    <div class="site-info">
        <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All Rights Reserved.', 'mp-theme'); ?></p>
    </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
