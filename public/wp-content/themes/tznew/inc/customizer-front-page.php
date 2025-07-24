<?php
/**
 * Front Page Customizer Settings
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
 * Add front page customization options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function tznew_front_page_customize_register($wp_customize) {
    
    // Front Page Panel
    $wp_customize->add_panel('tznew_front_page_panel', [
        'title'       => __('Front Page Settings', 'tznew'),
        'description' => __('Customize all front page sections including hero, featured treks, regions, and destinations', 'tznew'),
        'priority'    => 25,
    ]);

    // ==========================================================================
    // HERO SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_hero_section', [
        'title'    => __('Hero Section', 'tznew'),
        'panel'    => 'tznew_front_page_panel',
        'priority' => 10,
    ]);

    // Hero Title
    $wp_customize->add_setting('tznew_hero_title', [
        'default'           => 'Explore Nepal',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_hero_title', [
        'label'   => __('Hero Title', 'tznew'),
        'section' => 'tznew_hero_section',
        'type'    => 'text',
    ]);

    // Hero Subtitle
    $wp_customize->add_setting('tznew_hero_subtitle', [
        'default'           => 'Essential information about your upcoming adventure',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_hero_subtitle', [
        'label'   => __('Hero Subtitle', 'tznew'),
        'section' => 'tznew_hero_section',
        'type'    => 'textarea',
    ]);

    // Hero Background Image
    $wp_customize->add_setting('tznew_hero_background', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'tznew_hero_background', [
        'label'    => __('Hero Background Image', 'tznew'),
        'section'  => 'tznew_hero_section',
        'settings' => 'tznew_hero_background',
    ]));

    // Hero Overlay Opacity
    $wp_customize->add_setting('tznew_hero_overlay_opacity', [
        'default'           => 0.4,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_hero_overlay_opacity', [
        'label'       => __('Overlay Opacity', 'tznew'),
        'description' => __('Adjust the darkness of the overlay (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_hero_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

    // Hero Text Color
    $wp_customize->add_setting('tznew_hero_text_color', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_hero_text_color', [
        'label'   => __('Hero Text Color', 'tznew'),
        'section' => 'tznew_hero_section',
    ]));

    // Show Hero Search Form
    $wp_customize->add_setting('tznew_hero_show_search', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_hero_show_search', [
        'label'   => __('Show Search Form', 'tznew'),
        'section' => 'tznew_hero_section',
        'type'    => 'checkbox',
    ]);

    // ==========================================================================
    // FEATURED TREKS SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_featured_treks_section', [
        'title'    => __('Featured Treks Section', 'tznew'),
        'panel'    => 'tznew_front_page_panel',
        'priority' => 20,
    ]);

    // Show Featured Treks Section
    $wp_customize->add_setting('tznew_show_featured_treks', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_show_featured_treks', [
        'label'   => __('Show Featured Treks Section', 'tznew'),
        'section' => 'tznew_featured_treks_section',
        'type'    => 'checkbox',
    ]);

    // Featured Treks Title
    $wp_customize->add_setting('tznew_featured_treks_title', [
        'default'           => 'Popular Trek Packages',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_featured_treks_title', [
        'label'   => __('Section Title', 'tznew'),
        'section' => 'tznew_featured_treks_section',
        'type'    => 'text',
    ]);

    // Featured Treks Subtitle
    $wp_customize->add_setting('tznew_featured_treks_subtitle', [
        'default'           => 'Choose from our carefully curated selection of the most sought-after trekking adventures in Nepal.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_featured_treks_subtitle', [
        'label'   => __('Section Subtitle', 'tznew'),
        'section' => 'tznew_featured_treks_section',
        'type'    => 'textarea',
    ]);

    // Featured Treks Count
    $wp_customize->add_setting('tznew_featured_treks_count', [
        'default'           => 6,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_featured_treks_count', [
        'label'       => __('Number of Featured Treks', 'tznew'),
        'description' => __('How many featured treks to display', 'tznew'),
        'section'     => 'tznew_featured_treks_section',
        'type'        => 'number',
        'input_attrs' => [
            'min' => 1,
            'max' => 12,
        ],
    ]);

    // Featured Treks Background Color
    $wp_customize->add_setting('tznew_featured_treks_bg_color', [
        'default'           => '#f9fafb',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_featured_treks_bg_color', [
        'label'   => __('Section Background Color', 'tznew'),
        'section' => 'tznew_featured_treks_section',
    ]));

    // ==========================================================================
    // TREKKING REGIONS SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_regions_section', [
        'title'    => __('Trekking Regions Section', 'tznew'),
        'panel'    => 'tznew_front_page_panel',
        'priority' => 30,
    ]);

    // Show Regions Section
    $wp_customize->add_setting('tznew_show_regions', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_show_regions', [
        'label'   => __('Show Trekking Regions Section', 'tznew'),
        'section' => 'tznew_regions_section',
        'type'    => 'checkbox',
    ]);

    // Regions Title
    $wp_customize->add_setting('tznew_regions_title', [
        'default'           => 'Popular Trekking Regions',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_regions_title', [
        'label'   => __('Section Title', 'tznew'),
        'section' => 'tznew_regions_section',
        'type'    => 'text',
    ]);

    // Regions Subtitle
    $wp_customize->add_setting('tznew_regions_subtitle', [
        'default'           => 'Discover the most spectacular trekking regions in Nepal, each offering unique landscapes and cultural experiences.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_regions_subtitle', [
        'label'   => __('Section Subtitle', 'tznew'),
        'section' => 'tznew_regions_section',
        'type'    => 'textarea',
    ]);

    // Regions Background Color
    $wp_customize->add_setting('tznew_regions_bg_color', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_regions_bg_color', [
        'label'   => __('Section Background Color', 'tznew'),
        'section' => 'tznew_regions_section',
    ]));

    // ==========================================================================
    // DESTINATIONS SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_destinations_section', [
        'title'    => __('Destinations Section', 'tznew'),
        'panel'    => 'tznew_front_page_panel',
        'priority' => 40,
    ]);

    // Show Destinations Section
    $wp_customize->add_setting('tznew_show_destinations', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_show_destinations', [
        'label'   => __('Show Destinations Section', 'tznew'),
        'section' => 'tznew_destinations_section',
        'type'    => 'checkbox',
    ]);

    // Destinations Title
    $wp_customize->add_setting('tznew_destinations_title', [
        'default'           => 'Explore Amazing Destinations',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_destinations_title', [
        'label'   => __('Section Title', 'tznew'),
        'section' => 'tznew_destinations_section',
        'type'    => 'text',
    ]);

    // Destinations Subtitle
    $wp_customize->add_setting('tznew_destinations_subtitle', [
        'default'           => 'Discover breathtaking landscapes and immerse yourself in diverse cultures across our carefully curated destinations.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_destinations_subtitle', [
        'label'   => __('Section Subtitle', 'tznew'),
        'section' => 'tznew_destinations_section',
        'type'    => 'textarea',
    ]);

    // Destinations Background Color
    $wp_customize->add_setting('tznew_destinations_bg_color', [
        'default'           => '#f8fafc',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_destinations_bg_color', [
        'label'   => __('Section Background Color', 'tznew'),
        'section' => 'tznew_destinations_section',
    ]));

    // ==========================================================================
    // GLOBAL STYLING OPTIONS
    // ==========================================================================
    
    $wp_customize->add_section('tznew_global_styling', [
        'title'    => __('Global Styling', 'tznew'),
        'panel'    => 'tznew_front_page_panel',
        'priority' => 50,
    ]);

    // Primary Color
    $wp_customize->add_setting('tznew_primary_color', [
        'default'           => '#059669',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_primary_color', [
        'label'   => __('Primary Color', 'tznew'),
        'section' => 'tznew_global_styling',
    ]));

    // Secondary Color
    $wp_customize->add_setting('tznew_secondary_color', [
        'default'           => '#0891b2',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_secondary_color', [
        'label'   => __('Secondary Color', 'tznew'),
        'section' => 'tznew_global_styling',
    ]));

    // Accent Color
    $wp_customize->add_setting('tznew_accent_color', [
        'default'           => '#dc2626',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_accent_color', [
        'label'   => __('Accent Color', 'tznew'),
        'section' => 'tznew_global_styling',
    ]));

    // Button Border Radius
    $wp_customize->add_setting('tznew_button_border_radius', [
        'default'           => 12,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_button_border_radius', [
        'label'       => __('Button Border Radius (px)', 'tznew'),
        'description' => __('Adjust the roundness of buttons', 'tznew'),
        'section'     => 'tznew_global_styling',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ],
    ]);

    // Card Border Radius
    $wp_customize->add_setting('tznew_card_border_radius', [
        'default'           => 24,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_card_border_radius', [
        'label'       => __('Card Border Radius (px)', 'tznew'),
        'description' => __('Adjust the roundness of cards', 'tznew'),
        'section'     => 'tznew_global_styling',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ],
    ]);
}
add_action('customize_register', 'tznew_front_page_customize_register');

/**
 * Sanitize number range
 */
function tznew_sanitize_number_range($number, $setting) {
    $number = floatval($number);
    $atts = $setting->manager->get_control($setting->id)->input_attrs;
    $min = isset($atts['min']) ? $atts['min'] : $number;
    $max = isset($atts['max']) ? $atts['max'] : $number;
    return ($number >= $min && $number <= $max) ? $number : $setting->default;
}

/**
 * Sanitize checkbox
 */
function tznew_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Generate dynamic CSS for front page customizations
 */
function tznew_front_page_customizer_css() {
    $primary_color = get_theme_mod('tznew_primary_color', '#059669');
    $secondary_color = get_theme_mod('tznew_secondary_color', '#0891b2');
    $accent_color = get_theme_mod('tznew_accent_color', '#dc2626');
    $hero_text_color = get_theme_mod('tznew_hero_text_color', '#ffffff');
    $hero_overlay_opacity = get_theme_mod('tznew_hero_overlay_opacity', 0.4);
    $featured_treks_bg = get_theme_mod('tznew_featured_treks_bg_color', '#f9fafb');
    $regions_bg = get_theme_mod('tznew_regions_bg_color', '#ffffff');
    $destinations_bg = get_theme_mod('tznew_destinations_bg_color', '#f8fafc');
    $button_radius = get_theme_mod('tznew_button_border_radius', 12);
    $card_radius = get_theme_mod('tznew_card_border_radius', 24);
    $hero_bg = get_theme_mod('tznew_hero_background', '');

    $css = "<style type='text/css'>";
    
    // CSS Custom Properties
    $css .= ":root {";
    $css .= "--tznew-primary-color: {$primary_color};";
    $css .= "--tznew-secondary-color: {$secondary_color};";
    $css .= "--tznew-accent-color: {$accent_color};";
    $css .= "--tznew-button-radius: {$button_radius}px;";
    $css .= "--tznew-card-radius: {$card_radius}px;";
    $css .= "}";
    
    // Hero Section
    if ($hero_bg) {
        $css .= ".hero-section { background-image: linear-gradient(rgba(0,0,0,{$hero_overlay_opacity}), rgba(0,0,0,{$hero_overlay_opacity})), url('{$hero_bg}') !important; }";
    } else {
        $css .= ".hero-section { background-image: linear-gradient(rgba(0,0,0,{$hero_overlay_opacity}), rgba(0,0,0,{$hero_overlay_opacity})), url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') !important; }";
    }
    
    $css .= ".hero-section h1, .hero-section p { color: {$hero_text_color} !important; }";
    
    // Section Backgrounds
    $css .= ".popular-treks { background-color: {$featured_treks_bg} !important; }";
    $css .= ".trekking-regions { background-color: {$regions_bg} !important; }";
    $css .= ".destinations { background-color: {$destinations_bg} !important; }";
    
    // Primary Color Applications
    $css .= ".bg-green-600, .bg-green-500 { background-color: {$primary_color} !important; }";
    $css .= ".text-green-600, .text-green-500 { color: {$primary_color} !important; }";
    $css .= ".border-green-600, .border-green-500 { border-color: {$primary_color} !important; }";
    $css .= ".from-green-600, .from-green-500 { --tw-gradient-from: {$primary_color} !important; }";
    $css .= ".to-green-700, .to-green-600 { --tw-gradient-to: {$primary_color} !important; }";
    
    // Secondary Color Applications
    $css .= ".bg-teal-500, .bg-cyan-500 { background-color: {$secondary_color} !important; }";
    $css .= ".text-teal-500, .text-cyan-500 { color: {$secondary_color} !important; }";
    
    // Border Radius
    $css .= ".rounded-xl, .rounded-2xl { border-radius: var(--tznew-card-radius) !important; }";
    $css .= ".rounded-lg { border-radius: var(--tznew-button-radius) !important; }";
    
    $css .= "</style>";
    
    echo $css;
}
add_action('wp_head', 'tznew_front_page_customizer_css');

/**
 * Customizer partial refresh callbacks
 */
function tznew_customize_partial_hero_title() {
    return get_theme_mod('tznew_hero_title', 'Explore Nepal');
}

function tznew_customize_partial_hero_subtitle() {
    return get_theme_mod('tznew_hero_subtitle', 'Essential information about your upcoming adventure');
}

function tznew_customize_partial_featured_treks_title() {
    return get_theme_mod('tznew_featured_treks_title', 'Popular Trek Packages');
}

function tznew_customize_partial_featured_treks_subtitle() {
    return get_theme_mod('tznew_featured_treks_subtitle', 'Choose from our carefully curated selection of the most sought-after trekking adventures in Nepal.');
}

function tznew_customize_partial_regions_title() {
    return get_theme_mod('tznew_regions_title', 'Popular Trekking Regions');
}

function tznew_customize_partial_regions_subtitle() {
    return get_theme_mod('tznew_regions_subtitle', 'Discover the most spectacular trekking regions in Nepal, each offering unique landscapes and cultural experiences.');
}

function tznew_customize_partial_destinations_title() {
    return get_theme_mod('tznew_destinations_title', 'Explore Amazing Destinations');
}

function tznew_customize_partial_destinations_subtitle() {
    return get_theme_mod('tznew_destinations_subtitle', 'Discover breathtaking landscapes and immerse yourself in diverse cultures across our carefully curated destinations.');
}