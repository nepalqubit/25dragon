<?php
/**
 * Theme Settings Admin Notice
 *
 * @package TZnew
 * @author Santosh Baral
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin notice for theme settings configuration
 */
function tznew_theme_settings_admin_notice() {
    // Only show to administrators
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Don't show on theme settings page
    $current_screen = get_current_screen();
    if ($current_screen && strpos($current_screen->id, 'theme-general-settings') !== false) {
        return;
    }
    
    // Check if user has dismissed this notice
    $dismissed = get_user_meta(get_current_user_id(), 'tznew_theme_settings_notice_dismissed', true);
    if ($dismissed) {
        return;
    }
    
    // Check theme configuration status
    $config_status = tznew_get_theme_config_status();
    
    if (!$config_status['configured']) {
        $settings_url = admin_url('admin.php?page=theme-general-settings');
        $dismiss_url = wp_nonce_url(
            add_query_arg('tznew_dismiss_settings_notice', '1'),
            'tznew_dismiss_settings_notice'
        );
        
        echo '<div class="notice notice-warning is-dismissible tznew-theme-settings-notice">';
        echo '<h3>' . esc_html__('Theme Settings Configuration Required', 'tznew') . '</h3>';
        echo '<p>' . esc_html__('Your theme settings are not fully configured. Please complete the setup to ensure your website displays correctly.', 'tznew') . '</p>';
        
        if (!empty($config_status['missing'])) {
            echo '<p><strong>' . esc_html__('Missing required settings:', 'tznew') . '</strong></p>';
            echo '<ul style="list-style: disc; margin-left: 20px;">';
            foreach ($config_status['missing'] as $missing) {
                echo '<li>' . esc_html($missing) . '</li>';
            }
            echo '</ul>';
        }
        
        echo '<p>';
        echo '<a href="' . esc_url($settings_url) . '" class="button button-primary">' . esc_html__('Configure Theme Settings', 'tznew') . '</a> ';
        echo '<a href="' . esc_url($dismiss_url) . '" class="button button-secondary">' . esc_html__('Dismiss Notice', 'tznew') . '</a>';
        echo '</p>';
        echo '</div>';
        
        // Add JavaScript for dismiss functionality
        echo '<script>
        jQuery(document).ready(function($) {
            $(".tznew-theme-settings-notice .notice-dismiss").on("click", function() {
                $.post(ajaxurl, {
                    action: "tznew_dismiss_settings_notice",
                    nonce: "' . wp_create_nonce('tznew_dismiss_settings_notice') . '"
                });
            });
        });
        </script>';
    }
}
add_action('admin_notices', 'tznew_theme_settings_admin_notice');

/**
 * Handle dismiss notice request
 */
function tznew_handle_dismiss_settings_notice() {
    if (isset($_GET['tznew_dismiss_settings_notice']) && wp_verify_nonce($_GET['_wpnonce'], 'tznew_dismiss_settings_notice')) {
        update_user_meta(get_current_user_id(), 'tznew_theme_settings_notice_dismissed', true);
        wp_redirect(remove_query_arg(['tznew_dismiss_settings_notice', '_wpnonce']));
        exit;
    }
}
add_action('admin_init', 'tznew_handle_dismiss_settings_notice');

/**
 * AJAX handler for dismiss notice
 */
function tznew_ajax_dismiss_settings_notice() {
    if (!wp_verify_nonce($_POST['nonce'], 'tznew_dismiss_settings_notice')) {
        wp_die('Security check failed');
    }
    
    update_user_meta(get_current_user_id(), 'tznew_theme_settings_notice_dismissed', true);
    wp_die('success');
}
add_action('wp_ajax_tznew_dismiss_settings_notice', 'tznew_ajax_dismiss_settings_notice');

/**
 * Reset dismiss notice when theme settings are updated
 */
function tznew_reset_settings_notice_on_update($post_id) {
    // Reset the dismissed notice for all users when settings are updated
    if (get_post_type($post_id) === 'acf-field-group') {
        delete_metadata('user', 0, 'tznew_theme_settings_notice_dismissed', '', true);
    }
}
add_action('acf/save_post', 'tznew_reset_settings_notice_on_update');

/**
 * Add theme settings link to admin bar
 */
function tznew_add_theme_settings_admin_bar($wp_admin_bar) {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $wp_admin_bar->add_node([
        'id' => 'tznew-theme-settings',
        'title' => '<span class="ab-icon dashicons-admin-generic"></span> Theme Settings',
        'href' => admin_url('admin.php?page=theme-general-settings'),
        'meta' => [
            'title' => __('Configure Theme Settings', 'tznew')
        ]
    ]);
}
add_action('admin_bar_menu', 'tznew_add_theme_settings_admin_bar', 100);

/**
 * Add theme settings quick link to appearance menu
 */
function tznew_add_appearance_menu_link() {
    if (function_exists('acf_add_options_page')) {
        add_theme_page(
            __('Theme Settings', 'tznew'),
            __('Theme Settings', 'tznew'),
            'manage_options',
            'theme-general-settings',
            function() {
                // Redirect to ACF options page
                wp_redirect(admin_url('admin.php?page=theme-general-settings'));
                exit;
            }
        );
    }
}
add_action('admin_menu', 'tznew_add_appearance_menu_link');

/**
 * Add settings link to theme actions
 */
function tznew_add_theme_settings_link($actions, $theme) {
    if ($theme->get_stylesheet() === get_stylesheet()) {
        $settings_url = admin_url('admin.php?page=theme-general-settings');
        $actions['settings'] = '<a href="' . esc_url($settings_url) . '">' . __('Settings', 'tznew') . '</a>';
    }
    return $actions;
}
add_filter('theme_action_links', 'tznew_add_theme_settings_link', 10, 2);

/**
 * Display theme configuration status in dashboard
 */
function tznew_dashboard_theme_status_widget() {
    $config_status = tznew_get_theme_config_status();
    
    echo '<div class="tznew-dashboard-status">';
    
    if ($config_status['configured']) {
        echo '<div class="notice notice-success inline"><p>';
        echo '<span class="dashicons dashicons-yes-alt"></span> ';
        echo esc_html__('Theme is properly configured!', 'tznew');
        echo '</p></div>';
    } else {
        echo '<div class="notice notice-warning inline"><p>';
        echo '<span class="dashicons dashicons-warning"></span> ';
        echo esc_html__('Theme configuration incomplete.', 'tznew');
        echo ' <a href="' . esc_url(admin_url('admin.php?page=theme-general-settings')) . '">';
        echo esc_html__('Complete Setup', 'tznew') . '</a>';
        echo '</p></div>';
    }
    
    if (!empty($config_status['warnings'])) {
        echo '<div class="notice notice-info inline"><p>';
        echo '<span class="dashicons dashicons-info"></span> ';
        echo esc_html__('Recommendations:', 'tznew');
        echo '</p><ul style="margin-left: 20px;">';
        foreach (array_slice($config_status['warnings'], 0, 3) as $warning) {
            echo '<li>' . esc_html($warning) . '</li>';
        }
        echo '</ul></div>';
    }
    
    echo '</div>';
}

/**
 * Add dashboard widget for theme status
 */
function tznew_add_dashboard_widget() {
    if (current_user_can('manage_options')) {
        wp_add_dashboard_widget(
            'tznew_theme_status',
            __('TZnew Theme Status', 'tznew'),
            'tznew_dashboard_theme_status_widget'
        );
    }
}
add_action('wp_dashboard_setup', 'tznew_add_dashboard_widget');