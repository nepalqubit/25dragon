<?php
/**
 * Elementor Custom Post Type Activation Notice
 * 
 * This file creates an admin notice to help users activate Elementor support
 * for custom post types after theme updates.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin notice for Elementor CPT activation
 */
function tznew_elementor_cpt_activation_notice() {
    // Only show to administrators
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Check if notice has been dismissed
    if (get_option('tznew_elementor_cpt_notice_dismissed')) {
        return;
    }
    
    // Check if Elementor is active
    if (!class_exists('\Elementor\Plugin')) {
        return;
    }
    
    $current_screen = get_current_screen();
    
    // Show notice on relevant admin pages
    if ($current_screen && in_array($current_screen->id, ['dashboard', 'edit-trekking', 'edit-tours', 'edit-blog', 'edit-faq', 'elementor_page_elementor-tools'])) {
        ?>
        <div class="notice notice-info is-dismissible" id="tznew-elementor-notice">
            <h3><?php _e('Elementor Custom Post Types Support', 'tznew'); ?></h3>
            <p><?php _e('Your custom post types (Trekking, Tours, Blog, FAQ) have been configured for Elementor support.', 'tznew'); ?></p>
            <p>
                <strong><?php _e('To activate Elementor support:', 'tznew'); ?></strong><br>
                1. <?php printf(__('Go to <a href="%s">Elementor > Tools > General</a>', 'tznew'), admin_url('admin.php?page=elementor-tools#tab-general')); ?><br>
                2. <?php _e('Scroll down to "Post Types" section', 'tznew'); ?><br>
                3. <?php _e('Check the boxes for: Trekking, Tours, Blog, FAQ', 'tznew'); ?><br>
                4. <?php _e('Click "Save Changes"', 'tznew'); ?>
            </p>
            <p>
                <a href="<?php echo admin_url('admin.php?page=elementor-tools#tab-general'); ?>" class="button button-primary">
                    <?php _e('Go to Elementor Settings', 'tznew'); ?>
                </a>
                <button type="button" class="button button-secondary" id="tznew-dismiss-notice">
                    <?php _e('Dismiss Notice', 'tznew'); ?>
                </button>
            </p>
        </div>
        
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#tznew-dismiss-notice, .notice-dismiss').on('click', function() {
                $.post(ajaxurl, {
                    action: 'tznew_dismiss_elementor_notice',
                    nonce: '<?php echo wp_create_nonce('tznew_dismiss_notice'); ?>'
                });
            });
        });
        </script>
        <?php
    }
}
add_action('admin_notices', 'tznew_elementor_cpt_activation_notice');

/**
 * Handle notice dismissal
 */
function tznew_dismiss_elementor_notice() {
    if (!wp_verify_nonce($_POST['nonce'], 'tznew_dismiss_notice')) {
        wp_die('Security check failed');
    }
    
    update_option('tznew_elementor_cpt_notice_dismissed', true);
    wp_die();
}
add_action('wp_ajax_tznew_dismiss_elementor_notice', 'tznew_dismiss_elementor_notice');

/**
 * Reset notice when theme is activated
 */
function tznew_reset_elementor_notice() {
    delete_option('tznew_elementor_cpt_notice_dismissed');
}
add_action('after_switch_theme', 'tznew_reset_elementor_notice');

/**
 * Auto-check if Elementor settings need to be updated
 */
function tznew_check_elementor_settings() {
    if (!class_exists('\Elementor\Plugin')) {
        return;
    }
    
    $supported_post_types = get_option('elementor_cpt_support', []);
    $custom_types = ['trekking', 'tours', 'blog', 'faq'];
    
    $missing_types = array_diff($custom_types, $supported_post_types);
    
    if (empty($missing_types)) {
        // All custom post types are supported, dismiss the notice
        update_option('tznew_elementor_cpt_notice_dismissed', true);
    }
}
add_action('admin_init', 'tznew_check_elementor_settings');
?>