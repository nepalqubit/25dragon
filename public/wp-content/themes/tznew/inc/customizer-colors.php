<?php
/**
 * Color and Style Customizer for Trekking and Tour Pages
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
 * Add color and style customization options for trekking and tour pages
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function tznew_customize_colors_register($wp_customize) {
    
    // Add Trekking & Tours Colors Panel
    $wp_customize->add_panel('tznew_colors_panel', [
        'title'       => __('Trekking & Tours Colors', 'tznew'),
        'description' => __('Customize colors and styles for trekking and tour pages', 'tznew'),
        'priority'    => 25,
    ]);

    // ==========================================================================
    // PRIMARY COLOR SCHEME SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_primary_colors', [
        'title'    => __('Primary Color Scheme', 'tznew'),
        'panel'    => 'tznew_colors_panel',
        'priority' => 10,
    ]);

    // Primary Color
    $wp_customize->add_setting('tznew_primary_color', [
        'default'           => '#3b82f6',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_primary_color', [
        'label'       => __('Primary Color', 'tznew'),
        'description' => __('Main brand color used for buttons, links, and accents', 'tznew'),
        'section'     => 'tznew_primary_colors',
        'priority'    => 10,
    ]));

    // Primary Hover Color
    $wp_customize->add_setting('tznew_primary_hover', [
        'default'           => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_primary_hover', [
        'label'       => __('Primary Hover Color', 'tznew'),
        'description' => __('Color when hovering over primary elements', 'tznew'),
        'section'     => 'tznew_primary_colors',
        'priority'    => 20,
    ]));

    // Primary Light Color
    $wp_customize->add_setting('tznew_primary_light', [
        'default'           => '#dbeafe',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_primary_light', [
        'label'       => __('Primary Light Color', 'tznew'),
        'description' => __('Light version of primary color for backgrounds', 'tznew'),
        'section'     => 'tznew_primary_colors',
        'priority'    => 30,
    ]));

    // Primary Dark Color
    $wp_customize->add_setting('tznew_primary_dark', [
        'default'           => '#1d4ed8',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_primary_dark', [
        'label'       => __('Primary Dark Color', 'tznew'),
        'description' => __('Dark version of primary color for contrast', 'tznew'),
        'section'     => 'tznew_primary_colors',
        'priority'    => 40,
    ]));

    // ==========================================================================
    // SECONDARY COLOR SCHEME SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_secondary_colors', [
        'title'    => __('Secondary Color Scheme', 'tznew'),
        'panel'    => 'tznew_colors_panel',
        'priority' => 20,
    ]);

    // Secondary Color
    $wp_customize->add_setting('tznew_secondary_color', [
        'default'           => '#64748b',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_secondary_color', [
        'label'       => __('Secondary Color', 'tznew'),
        'description' => __('Secondary color for supporting elements', 'tznew'),
        'section'     => 'tznew_secondary_colors',
        'priority'    => 10,
    ]));

    // Secondary Hover Color
    $wp_customize->add_setting('tznew_secondary_hover', [
        'default'           => '#475569',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_secondary_hover', [
        'label'       => __('Secondary Hover Color', 'tznew'),
        'description' => __('Color when hovering over secondary elements', 'tznew'),
        'section'     => 'tznew_secondary_colors',
        'priority'    => 20,
    ]));

    // ==========================================================================
    // ACCENT COLORS SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_accent_colors', [
        'title'    => __('Accent Colors', 'tznew'),
        'panel'    => 'tznew_colors_panel',
        'priority' => 30,
    ]);

    // Accent Color
    $wp_customize->add_setting('tznew_accent_color', [
        'default'           => '#f59e0b',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_accent_color', [
        'label'       => __('Accent Color', 'tznew'),
        'description' => __('Accent color for highlights and special elements', 'tznew'),
        'section'     => 'tznew_accent_colors',
        'priority'    => 10,
    ]));

    // Success Color
    $wp_customize->add_setting('tznew_success_color', [
        'default'           => '#10b981',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_success_color', [
        'label'       => __('Success Color', 'tznew'),
        'description' => __('Color for success messages and positive actions', 'tznew'),
        'section'     => 'tznew_accent_colors',
        'priority'    => 20,
    ]));

    // Warning Color
    $wp_customize->add_setting('tznew_warning_color', [
        'default'           => '#f59e0b',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_warning_color', [
        'label'       => __('Warning Color', 'tznew'),
        'description' => __('Color for warnings and caution messages', 'tznew'),
        'section'     => 'tznew_accent_colors',
        'priority'    => 30,
    ]));

    // Danger Color
    $wp_customize->add_setting('tznew_danger_color', [
        'default'           => '#ef4444',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_danger_color', [
        'label'       => __('Danger Color', 'tznew'),
        'description' => __('Color for errors and dangerous actions', 'tznew'),
        'section'     => 'tznew_accent_colors',
        'priority'    => 40,
    ]));

    // ==========================================================================
    // TEXT COLORS SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_text_colors', [
        'title'    => __('Text Colors', 'tznew'),
        'panel'    => 'tznew_colors_panel',
        'priority' => 40,
    ]);

    // Primary Text Color
    $wp_customize->add_setting('tznew_text_primary', [
        'default'           => '#1f2937',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_text_primary', [
        'label'       => __('Primary Text Color', 'tznew'),
        'description' => __('Main text color for headings and important content', 'tznew'),
        'section'     => 'tznew_text_colors',
        'priority'    => 10,
    ]));

    // Secondary Text Color
    $wp_customize->add_setting('tznew_text_secondary', [
        'default'           => '#6b7280',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_text_secondary', [
        'label'       => __('Secondary Text Color', 'tznew'),
        'description' => __('Secondary text color for descriptions and labels', 'tznew'),
        'section'     => 'tznew_text_colors',
        'priority'    => 20,
    ]));

    // Muted Text Color
    $wp_customize->add_setting('tznew_text_muted', [
        'default'           => '#9ca3af',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_text_muted', [
        'label'       => __('Muted Text Color', 'tznew'),
        'description' => __('Muted text color for less important content', 'tznew'),
        'section'     => 'tznew_text_colors',
        'priority'    => 30,
    ]));

    // ==========================================================================
    // BACKGROUND COLORS SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_background_colors', [
        'title'    => __('Background Colors', 'tznew'),
        'panel'    => 'tznew_colors_panel',
        'priority' => 50,
    ]);

    // Primary Background Color
    $wp_customize->add_setting('tznew_bg_primary', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_bg_primary', [
        'label'       => __('Primary Background', 'tznew'),
        'description' => __('Main background color for cards and content areas', 'tznew'),
        'section'     => 'tznew_background_colors',
        'priority'    => 10,
    ]));

    // Secondary Background Color
    $wp_customize->add_setting('tznew_bg_secondary', [
        'default'           => '#f8fafc',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_bg_secondary', [
        'label'       => __('Secondary Background', 'tznew'),
        'description' => __('Secondary background color for sections', 'tznew'),
        'section'     => 'tznew_background_colors',
        'priority'    => 20,
    ]));

    // Muted Background Color
    $wp_customize->add_setting('tznew_bg_muted', [
        'default'           => '#f1f5f9',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_bg_muted', [
        'label'       => __('Muted Background', 'tznew'),
        'description' => __('Muted background color for subtle sections', 'tznew'),
        'section'     => 'tznew_background_colors',
        'priority'    => 30,
    ]));

    // ==========================================================================
    // TYPOGRAPHY SETTINGS SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_typography_settings', [
        'title'    => __('Typography Settings', 'tznew'),
        'panel'    => 'tznew_colors_panel',
        'priority' => 60,
    ]);

    // Primary Font Family
    $wp_customize->add_setting('tznew_font_primary', [
        'default'           => 'Inter',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_font_primary', [
        'label'       => __('Primary Font Family', 'tznew'),
        'description' => __('Font family for body text and general content', 'tznew'),
        'section'     => 'tznew_typography_settings',
        'type'        => 'select',
        'choices'     => [
            'Inter'      => 'Inter',
            'Roboto'     => 'Roboto',
            'Open Sans'  => 'Open Sans',
            'Lato'       => 'Lato',
            'Poppins'    => 'Poppins',
            'Nunito'     => 'Nunito',
            'Source Sans Pro' => 'Source Sans Pro',
        ],
        'priority'    => 10,
    ]);

    // Heading Font Family
    $wp_customize->add_setting('tznew_font_heading', [
        'default'           => 'Montserrat',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_font_heading', [
        'label'       => __('Heading Font Family', 'tznew'),
        'description' => __('Font family for headings and titles', 'tznew'),
        'section'     => 'tznew_typography_settings',
        'type'        => 'select',
        'choices'     => [
            'Montserrat' => 'Montserrat',
            'Playfair Display' => 'Playfair Display',
            'Merriweather' => 'Merriweather',
            'Oswald'     => 'Oswald',
            'Raleway'    => 'Raleway',
            'Lora'       => 'Lora',
            'Crimson Text' => 'Crimson Text',
        ],
        'priority'    => 20,
    ]);

    // Base Font Size
    $wp_customize->add_setting('tznew_font_size_base', [
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_font_size_base', [
        'label'       => __('Base Font Size (px)', 'tznew'),
        'description' => __('Base font size for body text', 'tznew'),
        'section'     => 'tznew_typography_settings',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 12,
            'max'  => 24,
            'step' => 1,
        ],
        'priority'    => 30,
    ]);

    // ==========================================================================
    // BUTTON STYLES SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_button_styles', [
        'title'    => __('Button Styles', 'tznew'),
        'panel'    => 'tznew_colors_panel',
        'priority' => 70,
    ]);

    // Button Border Radius
    $wp_customize->add_setting('tznew_button_radius', [
        'default'           => '8',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_button_radius', [
        'label'       => __('Button Border Radius (px)', 'tznew'),
        'description' => __('Border radius for buttons and interactive elements', 'tznew'),
        'section'     => 'tznew_button_styles',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ],
        'priority'    => 10,
    ]);

    // Button Padding
    $wp_customize->add_setting('tznew_button_padding', [
        'default'           => '12',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_button_padding', [
        'label'       => __('Button Padding (px)', 'tznew'),
        'description' => __('Vertical padding for buttons', 'tznew'),
        'section'     => 'tznew_button_styles',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 8,
            'max'  => 24,
            'step' => 1,
        ],
        'priority'    => 20,
    ]);

    // ==========================================================================
    // CARD STYLES SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_card_styles', [
        'title'    => __('Card Styles', 'tznew'),
        'panel'    => 'tznew_colors_panel',
        'priority' => 80,
    ]);

    // Card Border Radius
    $wp_customize->add_setting('tznew_card_radius', [
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_card_radius', [
        'label'       => __('Card Border Radius (px)', 'tznew'),
        'description' => __('Border radius for cards and content containers', 'tznew'),
        'section'     => 'tznew_card_styles',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 32,
            'step' => 1,
        ],
        'priority'    => 10,
    ]);

    // Card Shadow Intensity
    $wp_customize->add_setting('tznew_card_shadow', [
        'default'           => 'medium',
        'sanitize_callback' => 'tznew_sanitize_select',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_card_shadow', [
        'label'       => __('Card Shadow Intensity', 'tznew'),
        'description' => __('Shadow intensity for cards and elevated elements', 'tznew'),
        'section'     => 'tznew_card_styles',
        'type'        => 'select',
        'choices'     => [
            'none'   => __('No Shadow', 'tznew'),
            'small'  => __('Small Shadow', 'tznew'),
            'medium' => __('Medium Shadow', 'tznew'),
            'large'  => __('Large Shadow', 'tznew'),
            'xl'     => __('Extra Large Shadow', 'tznew'),
        ],
        'priority'    => 20,
    ]);

    // ==========================================================================
    // ANIMATION SETTINGS SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_animation_settings', [
        'title'    => __('Animation Settings', 'tznew'),
        'panel'    => 'tznew_colors_panel',
        'priority' => 90,
    ]);

    // Enable Animations
    $wp_customize->add_setting('tznew_enable_animations', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_enable_animations', [
        'label'       => __('Enable Animations', 'tznew'),
        'description' => __('Enable hover effects and animations throughout the site', 'tznew'),
        'section'     => 'tznew_animation_settings',
        'type'        => 'checkbox',
        'priority'    => 10,
    ]);

    // Animation Speed
    $wp_customize->add_setting('tznew_animation_speed', [
        'default'           => 'normal',
        'sanitize_callback' => 'tznew_sanitize_select',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_animation_speed', [
        'label'       => __('Animation Speed', 'tznew'),
        'description' => __('Speed of hover effects and transitions', 'tznew'),
        'section'     => 'tznew_animation_settings',
        'type'        => 'select',
        'choices'     => [
            'fast'   => __('Fast (0.15s)', 'tznew'),
            'normal' => __('Normal (0.3s)', 'tznew'),
            'slow'   => __('Slow (0.5s)', 'tznew'),
        ],
        'priority'    => 20,
    ]);
}
add_action('customize_register', 'tznew_customize_colors_register');

/**
 * Generate custom CSS based on customizer settings
 */
function tznew_customize_css() {
    $css = '';
    
    // Get customizer values
    $primary_color = get_theme_mod('tznew_primary_color', '#3b82f6');
    $primary_hover = get_theme_mod('tznew_primary_hover', '#2563eb');
    $primary_light = get_theme_mod('tznew_primary_light', '#dbeafe');
    $primary_dark = get_theme_mod('tznew_primary_dark', '#1d4ed8');
    
    $secondary_color = get_theme_mod('tznew_secondary_color', '#64748b');
    $secondary_hover = get_theme_mod('tznew_secondary_hover', '#475569');
    
    $accent_color = get_theme_mod('tznew_accent_color', '#f59e0b');
    $success_color = get_theme_mod('tznew_success_color', '#10b981');
    $warning_color = get_theme_mod('tznew_warning_color', '#f59e0b');
    $danger_color = get_theme_mod('tznew_danger_color', '#ef4444');
    
    $text_primary = get_theme_mod('tznew_text_primary', '#1f2937');
    $text_secondary = get_theme_mod('tznew_text_secondary', '#6b7280');
    $text_muted = get_theme_mod('tznew_text_muted', '#9ca3af');
    
    $bg_primary = get_theme_mod('tznew_bg_primary', '#ffffff');
    $bg_secondary = get_theme_mod('tznew_bg_secondary', '#f8fafc');
    $bg_muted = get_theme_mod('tznew_bg_muted', '#f1f5f9');
    
    $font_primary = get_theme_mod('tznew_font_primary', 'Inter');
    $font_heading = get_theme_mod('tznew_font_heading', 'Montserrat');
    $font_size_base = get_theme_mod('tznew_font_size_base', 16);
    
    $button_radius = get_theme_mod('tznew_button_radius', 8);
    $button_padding = get_theme_mod('tznew_button_padding', 12);
    
    $card_radius = get_theme_mod('tznew_card_radius', 16);
    $card_shadow = get_theme_mod('tznew_card_shadow', 'medium');
    
    $enable_animations = get_theme_mod('tznew_enable_animations', true);
    $animation_speed = get_theme_mod('tznew_animation_speed', 'normal');
    
    // Map animation speed to CSS values
    $speed_map = [
        'fast' => '0.15s',
        'normal' => '0.3s',
        'slow' => '0.5s',
    ];
    $transition_speed = $speed_map[$animation_speed] ?? '0.3s';
    
    // Map shadow intensity to CSS values
    $shadow_map = [
        'none' => 'none',
        'small' => '0 1px 2px 0 rgb(0 0 0 / 0.05)',
        'medium' => '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
        'large' => '0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1)',
        'xl' => '0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)',
    ];
    $shadow_value = $shadow_map[$card_shadow] ?? $shadow_map['medium'];
    
    // Generate CSS
    $css .= ':root {';
    $css .= '--trek-primary-color: ' . $primary_color . ';';
    $css .= '--trek-primary-hover: ' . $primary_hover . ';';
    $css .= '--trek-primary-light: ' . $primary_light . ';';
    $css .= '--trek-primary-dark: ' . $primary_dark . ';';
    
    $css .= '--trek-secondary-color: ' . $secondary_color . ';';
    $css .= '--trek-secondary-hover: ' . $secondary_hover . ';';
    
    $css .= '--trek-accent-color: ' . $accent_color . ';';
    $css .= '--trek-success-color: ' . $success_color . ';';
    $css .= '--trek-warning-color: ' . $warning_color . ';';
    $css .= '--trek-danger-color: ' . $danger_color . ';';
    
    $css .= '--trek-text-primary: ' . $text_primary . ';';
    $css .= '--trek-text-secondary: ' . $text_secondary . ';';
    $css .= '--trek-text-muted: ' . $text_muted . ';';
    
    $css .= '--trek-bg-primary: ' . $bg_primary . ';';
    $css .= '--trek-bg-secondary: ' . $bg_secondary . ';';
    $css .= '--trek-bg-muted: ' . $bg_muted . ';';
    
    $css .= '--trek-font-family-primary: "' . $font_primary . '", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;';
    $css .= '--trek-font-family-heading: "' . $font_heading . '", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;';
    $css .= '--trek-font-size-base: ' . $font_size_base . 'px;';
    
    $css .= '--trek-radius-lg: ' . $button_radius . 'px;';
    $css .= '--trek-radius-2xl: ' . $card_radius . 'px;';
    $css .= '--trek-shadow-md: ' . $shadow_value . ';';
    
    $css .= '--trek-transition-normal: ' . $transition_speed . ' ease-in-out;';
    $css .= '}';
    
    // Disable animations if requested
    if (!$enable_animations) {
        $css .= '*, *::before, *::after { transition: none !important; animation: none !important; }';
    }
    
    return $css;
}

/**
 * Output custom CSS in the head
 */
function tznew_customize_css_output() {
    $css = tznew_customize_css();
    if (!empty($css)) {
        echo '<style type="text/css" id="tznew-custom-colors">' . $css . '</style>';
    }
}
add_action('wp_head', 'tznew_customize_css_output');

// Note: Sanitization functions are already defined in customizer.php

/**
 * Enqueue Google Fonts based on customizer settings with proper CORS handling
 */
function tznew_customize_google_fonts() {
    $font_primary = get_theme_mod('tznew_font_primary', 'Inter');
    $font_heading = get_theme_mod('tznew_font_heading', 'Montserrat');
    
    $fonts = [];
    
    // Add primary font with proper URL encoding
    if ($font_primary !== 'inherit') {
        $fonts[] = urlencode($font_primary) . ':wght@300;400;500;600;700';
    }
    
    // Add heading font if different from primary
    if ($font_heading !== 'inherit' && $font_heading !== $font_primary) {
        $fonts[] = urlencode($font_heading) . ':wght@300;400;500;600;700;800';
    }
    
    if (!empty($fonts)) {
        // Construct proper Google Fonts CSS2 API URL
        $font_url = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $fonts) . '&display=swap';
        
        // Enqueue Google Fonts with proper crossorigin attribute
        wp_enqueue_style(
            'tznew-google-fonts',
            $font_url,
            [],
            null
        );
        
        // Add crossorigin attribute to prevent ORB errors
        add_filter('style_loader_tag', function($html, $handle) {
            if ($handle === 'tznew-google-fonts') {
                return str_replace('rel=\'stylesheet\'', 'rel="stylesheet" crossorigin="anonymous"', $html);
            }
            return $html;
        }, 10, 2);
    }
}
add_action('wp_enqueue_scripts', 'tznew_customize_google_fonts');

/**
 * Add preconnect links for Google Fonts in head
 */
function tznew_google_fonts_preconnect() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
}
add_action('wp_head', 'tznew_google_fonts_preconnect', 1);

// Note: tznew_customize_preview_js() function is already defined in customizer.php