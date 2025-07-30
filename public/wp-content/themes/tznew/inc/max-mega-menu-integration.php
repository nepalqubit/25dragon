<?php
/**
 * Max Mega Menu Integration for TZnew Theme
 * 
 * This file provides better integration between Max Mega Menu plugin
 * and the TZnew theme, including styling, functionality, and compatibility.
 *
 * @package TZnew
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class TZnew_Max_Mega_Menu_Integration {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
    }
    
    public function init() {
        // Only proceed if Max Mega Menu is active
        if (!class_exists('Mega_Menu')) {
            return;
        }
        
        // Hook into Max Mega Menu
        add_action('wp_enqueue_scripts', array($this, 'enqueue_integration_styles'), 15);
        add_filter('megamenu_themes', array($this, 'add_custom_theme'));
        add_action('megamenu_output_public_css', array($this, 'add_custom_css'));
        add_filter('megamenu_nav_menu_objects_after', array($this, 'add_theme_classes'), 10, 2);
        add_action('wp_head', array($this, 'add_custom_css_variables'));
        
        // Disable theme's custom mega menu when Max Mega Menu is active
        add_filter('wp_nav_menu_args', array($this, 'disable_custom_walker'));
        
        // Add theme compatibility
        add_action('after_setup_theme', array($this, 'add_theme_support'));
    }
    
    /**
     * Enqueue integration styles
     */
    public function enqueue_integration_styles() {
        wp_enqueue_style(
            'tznew-megamenu-integration',
            TZNEW_THEME_URI . '/assets/css/megamenu-integration.css',
            array('megamenu'),
            TZNEW_VERSION
        );
    }
    
    /**
     * Add custom theme to Max Mega Menu
     */
    public function add_custom_theme($themes) {
        $themes['tznew_theme'] = array(
            'title' => 'TZnew Theme Style',
            'container_background_from' => 'rgba(255, 255, 255, 0)',
            'container_background_to' => 'rgba(255, 255, 255, 0)',
            'container_padding_left' => '0px',
            'container_padding_right' => '0px',
            'container_padding_top' => '0px',
            'container_padding_bottom' => '0px',
            'container_border_radius_top_left' => '0px',
            'container_border_radius_top_right' => '0px',
            'container_border_radius_bottom_left' => '0px',
            'container_border_radius_bottom_right' => '0px',
            'menu_item_background_from' => 'rgba(0, 0, 0, 0)',
            'menu_item_background_to' => 'rgba(0, 0, 0, 0)',
            'menu_item_background_hover_from' => 'rgba(22, 163, 74, 0.1)',
            'menu_item_background_hover_to' => 'rgba(22, 163, 74, 0.1)',
            'menu_item_link_color' => '#1f2937',
            'menu_item_link_color_hover' => '#16a34a',
            'menu_item_link_font_size' => '16px',
            'menu_item_link_font_weight' => '500',
            'menu_item_link_text_transform' => 'none',
            'menu_item_link_text_decoration' => 'none',
            'menu_item_link_text_decoration_hover' => 'none',
            'menu_item_link_padding_left' => '20px',
            'menu_item_link_padding_right' => '20px',
            'menu_item_link_padding_top' => '15px',
            'menu_item_link_padding_bottom' => '15px',
            'menu_item_border_color' => 'rgba(0, 0, 0, 0)',
            'menu_item_border_color_hover' => 'rgba(0, 0, 0, 0)',
            'menu_item_highlight_current' => 'on',
            'menu_item_align' => 'left',
            'panel_background_from' => '#ffffff',
            'panel_background_to' => '#ffffff',
            'panel_width' => '100%',
            'panel_border_color' => '#e5e7eb',
            'panel_border_left' => '1px',
            'panel_border_right' => '1px',
            'panel_border_top' => '1px',
            'panel_border_bottom' => '1px',
            'panel_border_radius_top_left' => '8px',
            'panel_border_radius_top_right' => '8px',
            'panel_border_radius_bottom_left' => '8px',
            'panel_border_radius_bottom_right' => '8px',
            'panel_box_shadow' => '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1)',
            'panel_padding_left' => '20px',
            'panel_padding_right' => '20px',
            'panel_padding_top' => '20px',
            'panel_padding_bottom' => '20px',
            'panel_header_color' => '#16a34a',
            'panel_header_text_transform' => 'uppercase',
            'panel_header_font_weight' => '600',
            'panel_header_font_size' => '14px',
            'panel_header_margin_bottom' => '10px',
            'panel_header_border_color' => '#16a34a',
            'panel_header_border_bottom' => '2px',
            'flyout_background_from' => '#ffffff',
            'flyout_background_to' => '#ffffff',
            'flyout_border_color' => '#e5e7eb',
            'flyout_border_left' => '1px',
            'flyout_border_right' => '1px',
            'flyout_border_top' => '1px',
            'flyout_border_bottom' => '1px',
            'flyout_border_radius_top_left' => '8px',
            'flyout_border_radius_top_right' => '8px',
            'flyout_border_radius_bottom_left' => '8px',
            'flyout_border_radius_bottom_right' => '8px',
            'flyout_box_shadow' => '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1)',
            'flyout_link_color' => '#1f2937',
            'flyout_link_color_hover' => '#16a34a',
            'flyout_link_font_size' => '14px',
            'flyout_link_font_weight' => '400',
            'flyout_link_padding_left' => '15px',
            'flyout_link_padding_right' => '15px',
            'flyout_link_padding_top' => '10px',
            'flyout_link_padding_bottom' => '10px',
            'responsive_breakpoint' => '768px',
            'responsive_text' => 'Menu',
            'line_height' => '1.7',
            'z_index' => '999999',
            'shadow_horizontal' => '0px',
            'shadow_vertical' => '10px',
            'shadow_blur' => '15px',
            'shadow_spread' => '-3px',
            'shadow_color' => 'rgba(0, 0, 0, 0.1)',
            'transitions' => 'on'
        );
        
        return $themes;
    }
    
    /**
     * Add custom CSS for better integration
     */
    public function add_custom_css() {
        echo "\n/* TZnew Theme Max Mega Menu Integration */\n";
        echo "#mega-menu-wrap-primary { font-family: inherit; }\n";
        echo "#mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item > a.mega-menu-link { transition: all 0.3s ease; }\n";
        echo "#mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item:hover > a.mega-menu-link { transform: translateY(-2px); }\n";
        echo "#mega-menu-wrap-primary #mega-menu-primary li.mega-menu-megamenu > ul.mega-sub-menu { margin-top: 10px; }\n";
        echo "#mega-menu-wrap-primary #mega-menu-primary li.mega-menu-megamenu > ul.mega-sub-menu:before { content: ''; position: absolute; top: -10px; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 10px solid transparent; border-right: 10px solid transparent; border-bottom: 10px solid #ffffff; }\n";
        echo "@media (max-width: 768px) { #mega-menu-wrap-primary { background: #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); } }\n";
    }
    
    /**
     * Add theme-specific classes to menu items
     */
    public function add_theme_classes($items, $args) {
        if ($args->theme_location !== 'primary') {
            return $items;
        }
        
        foreach ($items as $item) {
            // Add trekking-related icons
            $title_lower = strtolower($item->title);
            if (strpos($title_lower, 'trek') !== false || strpos($title_lower, 'hiking') !== false) {
                $item->classes[] = 'tznew-trek-item';
            } elseif (strpos($title_lower, 'tour') !== false || strpos($title_lower, 'travel') !== false) {
                $item->classes[] = 'tznew-tour-item';
            } elseif (strpos($title_lower, 'expedition') !== false) {
                $item->classes[] = 'tznew-expedition-item';
            } elseif (strpos($title_lower, 'culture') !== false) {
                $item->classes[] = 'tznew-culture-item';
            }
        }
        
        return $items;
    }
    
    /**
     * Add CSS variables for theme integration
     */
    public function add_custom_css_variables() {
        echo "<style>\n";
        echo ":root {\n";
        echo "  --megamenu-primary-color: var(--primary-color, #16a34a);\n";
        echo "  --megamenu-secondary-color: var(--secondary-color, #2563eb);\n";
        echo "  --megamenu-text-color: var(--text-primary, #1f2937);\n";
        echo "  --megamenu-border-color: var(--border-color, #e5e7eb);\n";
        echo "  --megamenu-shadow: var(--shadow-lg, 0 10px 15px -3px rgb(0 0 0 / 0.1));\n";
        echo "}\n";
        echo "</style>\n";
    }
    
    /**
     * Disable theme's custom walker when Max Mega Menu is active
     */
    public function disable_custom_walker($args) {
        if (isset($args['theme_location']) && $args['theme_location'] === 'primary') {
            // Remove custom walker to let Max Mega Menu handle it
            if (isset($args['walker']) && $args['walker'] instanceof TZnew_Mega_Menu_Walker) {
                unset($args['walker']);
            }
        }
        return $args;
    }
    
    /**
     * Add theme support for Max Mega Menu features
     */
    public function add_theme_support() {
        // Add support for Max Mega Menu
        add_theme_support('megamenu');
        
        // Register Max Mega Menu location if not already registered
        if (!has_nav_menu('primary')) {
            register_nav_menus(array(
                'primary' => __('Primary Menu (Max Mega Menu)', 'tznew')
            ));
        }
    }
    
    /**
     * Get recommended Max Mega Menu settings for the theme
     */
    public static function get_recommended_settings() {
        return array(
            'theme' => 'tznew_theme',
            'effect' => 'fade_up',
            'effect_speed' => 200,
            'effect_mobile' => 'disabled',
            'mobile_force_width' => 'on',
            'mobile_background' => '#ffffff',
            'mobile_text' => 'Menu',
            'responsive_breakpoint' => 768,
            'descriptions' => 'on',
            'icons' => 'on',
            'icon_position' => 'left',
            'second_click' => 'go',
            'document_click' => 'collapse',
            'hover_intent' => 'on',
            'hover_intent_timeout' => 300,
            'hover_intent_interval' => 100
        );
    }
}

// Initialize the integration
new TZnew_Max_Mega_Menu_Integration();

/**
 * Helper function to apply recommended settings
 * Call this function to automatically configure Max Mega Menu with theme-optimized settings
 */
function tznew_apply_megamenu_settings() {
    if (!class_exists('Mega_Menu')) {
        return false;
    }
    
    $settings = TZnew_Max_Mega_Menu_Integration::get_recommended_settings();
    
    // Apply settings to primary menu location
    $locations = get_nav_menu_locations();
    if (isset($locations['primary'])) {
        $menu_id = $locations['primary'];
        foreach ($settings as $key => $value) {
            update_option("megamenu_settings_{$menu_id}_{$key}", $value);
        }
        
        // Clear cache
        delete_transient('megamenu_css');
        
        return true;
    }
    
    return false;
}

/**
 * Add admin notice for Max Mega Menu integration
 */
function tznew_megamenu_admin_notice() {
    if (!class_exists('Mega_Menu')) {
        return;
    }
    
    $screen = get_current_screen();
    if ($screen->id !== 'nav-menus') {
        return;
    }
    
    $configured = get_option('tznew_megamenu_configured', false);
    
    echo '<div class="notice notice-info">';
    echo '<p><strong>TZnew Theme:</strong> Max Mega Menu integration is active. Use the "TZnew Theme Style" theme for best results.';
    
    if (!$configured) {
        echo ' <a href="#" onclick="tznewConfigureMegaMenu(); return false;" class="button button-secondary">Auto-Configure</a>';
    }
    
    echo '</p>';
    echo '</div>';
    
    if (!$configured) {
        echo '<script>
        function tznewConfigureMegaMenu() {
            if (confirm("This will apply TZnew theme optimized settings to your Max Mega Menu. Continue?")) {
                jQuery.post(ajaxurl, {
                    action: "tznew_configure_megamenu",
                    nonce: "' . wp_create_nonce('tznew_megamenu_config') . '"
                }, function(response) {
                    if (response.success) {
                        alert("Max Mega Menu has been configured for TZnew theme!");
                        location.reload();
                    } else {
                        alert("Configuration failed: " + response.data);
                    }
                });
            }
        }
        </script>';
    }
}
add_action('admin_notices', 'tznew_megamenu_admin_notice');

/**
 * AJAX handler for auto-configuration
 */
function tznew_configure_megamenu_ajax() {
    if (!wp_verify_nonce($_POST['nonce'], 'tznew_megamenu_config')) {
        wp_send_json_error('Invalid nonce');
    }
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions');
    }
    
    $result = tznew_apply_megamenu_settings();
    
    if ($result) {
        update_option('tznew_megamenu_configured', true);
        wp_send_json_success('Max Mega Menu configured successfully');
    } else {
        wp_send_json_error('Failed to configure Max Mega Menu');
    }
}
add_action('wp_ajax_tznew_configure_megamenu', 'tznew_configure_megamenu_ajax');

/**
 * Add Max Mega Menu configuration to theme activation
 */
function tznew_megamenu_theme_activation() {
    if (class_exists('Mega_Menu')) {
        // Auto-configure on theme activation
        tznew_apply_megamenu_settings();
        update_option('tznew_megamenu_configured', true);
    }
}
add_action('after_switch_theme', 'tznew_megamenu_theme_activation');

/**
 * Add custom CSS for Max Mega Menu admin interface
 */
function tznew_megamenu_admin_css() {
    $screen = get_current_screen();
    if ($screen->id === 'nav-menus' || strpos($screen->id, 'megamenu') !== false) {
        echo '<style>
        .tznew-megamenu-notice {
            background: linear-gradient(135deg, #16a34a, #2563eb);
            color: white;
            border: none;
            border-radius: 8px;
        }
        .tznew-megamenu-notice p {
            color: white;
        }
        .tznew-megamenu-notice .button {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }
        .tznew-megamenu-notice .button:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        </style>';
    }
}
add_action('admin_head', 'tznew_megamenu_admin_css');