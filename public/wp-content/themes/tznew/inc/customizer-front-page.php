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

    // === Icon Picker for Hero Section ===
    $wp_customize->add_setting('tznew_hero_icon', [
        'default'           => 'fa-mountain',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('tznew_hero_icon', [
        'label'       => __('Hero Section Icon', 'tznew'),
        'description' => __('FontAwesome class (e.g., fa-mountain) or SVG code', 'tznew'),
        'section'     => 'tznew_hero_section',
        'type'        => 'text',
    ]);
// Hero Section Title
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

    // Featured Treks Content Ordering
    $wp_customize->add_setting('tznew_featured_treks_order', [
        'default'           => 'latest',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_featured_treks_order', [
        'label'   => __('Featured Treks Order', 'tznew'),
        'section' => 'tznew_featured_treks_section',
        'type'    => 'select',
        'choices' => [
            'latest' => __('Latest Treks', 'tznew'),
            'random' => __('Random Treks', 'tznew'),
            'popular' => __('Most Popular', 'tznew'),
            'featured' => __('Featured Only', 'tznew'),
        ],
    ]);

    // Featured Treks Background Color 1 (Gradient Start)
    $wp_customize->add_setting('tznew_featured_treks_bg_color_1', [
        'default'           => '#f9fafb',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_featured_treks_bg_color_1', [
        'label'   => __('Background Color 1 (Gradient Start)', 'tznew'),
        'section' => 'tznew_featured_treks_section',
    ]));

    // Featured Treks Background Color 2 (Gradient End)
    $wp_customize->add_setting('tznew_featured_treks_bg_color_2', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_featured_treks_bg_color_2', [
        'label'   => __('Background Color 2 (Gradient End)', 'tznew'),
        'section' => 'tznew_featured_treks_section',
    ]));

    // Featured Treks Background Opacity
    $wp_customize->add_setting('tznew_featured_treks_bg_opacity', [
        'default'           => 1.0,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_featured_treks_bg_opacity', [
        'label'       => __('Background Opacity', 'tznew'),
        'description' => __('Adjust the opacity of the background (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_featured_treks_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

    // Featured Treks Image Overlay Color
    $wp_customize->add_setting('tznew_featured_treks_overlay_color', [
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_featured_treks_overlay_color', [
        'label'   => __('Image Overlay Color', 'tznew'),
        'section' => 'tznew_featured_treks_section',
    ]));

    // Featured Treks Image Overlay Opacity
    $wp_customize->add_setting('tznew_featured_treks_overlay_opacity', [
        'default'           => 0.2,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_featured_treks_overlay_opacity', [
        'label'       => __('Image Overlay Opacity', 'tznew'),
        'description' => __('Adjust the opacity of the image overlay (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_featured_treks_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

    // === Section Order (Drag-and-Drop) ===
$wp_customize->add_setting('tznew_section_order', [
    'default'           => json_encode([
        'hero', 'featured_treks', 'regions', 'trek_blocks', 'why_choose', 'statistics', 'popular_tours', 'popular_trips', 'destinations', 'blog', 'testimonials', 'cta', 'footer'
    ]),
    'sanitize_callback' => 'sanitize_text_field',
    'transport'         => 'refresh',
]);
$wp_customize->add_control('tznew_section_order', [
    'label'       => __('Homepage Section Order', 'tznew'),
    'description' => __('Drag and drop to reorder homepage sections (requires custom JS for UI)', 'tznew'),
    'section'     => 'tznew_front_page_panel',
    'type'        => 'hidden', // Placeholder for custom JS UI
]);
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

    // Number of Regions to Display
    $wp_customize->add_setting('tznew_regions_count', [
        'default'           => 6,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_regions_count', [
        'label'       => __('Number of Regions to Display', 'tznew'),
        'description' => __('Choose how many regions to show on the homepage', 'tznew'),
        'section'     => 'tznew_regions_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 1,
            'max'  => 12,
            'step' => 1,
        ],
    ]);

    // Regions Background Color 1 (Gradient Start)
    $wp_customize->add_setting('tznew_regions_bg_color_1', [
        'default'           => '#f0fdf4',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_regions_bg_color_1', [
        'label'   => __('Background Color 1 (Gradient Start)', 'tznew'),
        'section' => 'tznew_regions_section',
    ]));

    // Regions Background Color 2 (Gradient Middle)
    $wp_customize->add_setting('tznew_regions_bg_color_2', [
        'default'           => '#ecfdf5',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_regions_bg_color_2', [
        'label'   => __('Background Color 2 (Gradient Middle)', 'tznew'),
        'section' => 'tznew_regions_section',
    ]));

    // Regions Background Color 3 (Gradient End)
    $wp_customize->add_setting('tznew_regions_bg_color_3', [
        'default'           => '#f0fdfa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_regions_bg_color_3', [
        'label'   => __('Background Color 3 (Gradient End)', 'tznew'),
        'section' => 'tznew_regions_section',
    ]));

    // Regions Background Opacity
    $wp_customize->add_setting('tznew_regions_bg_opacity', [
        'default'           => 1.0,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_regions_bg_opacity', [
        'label'       => __('Background Opacity', 'tznew'),
        'description' => __('Adjust the opacity of the background (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_regions_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

    // Regions Image Overlay Color
    $wp_customize->add_setting('tznew_regions_overlay_color', [
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_regions_overlay_color', [
        'label'   => __('Image Overlay Color', 'tznew'),
        'section' => 'tznew_regions_section',
    ]));

    // Regions Image Overlay Opacity
    $wp_customize->add_setting('tznew_regions_overlay_opacity', [
        'default'           => 0.2,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_regions_overlay_opacity', [
        'label'       => __('Image Overlay Opacity', 'tznew'),
        'description' => __('Adjust the opacity of the image overlay (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_regions_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

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

    // === Taxonomy Grid Toggle for Destinations Section ===
    $wp_customize->add_setting('tznew_destinations_grid', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('tznew_destinations_grid', [
        'label'   => __('Display Destinations as Grid', 'tznew'),
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

    // Number of Destinations to Display
    $wp_customize->add_setting('tznew_destinations_count', [
        'default'           => 6,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_destinations_count', [
        'label'       => __('Number of Destinations to Display', 'tznew'),
        'description' => __('Choose how many destinations to show on the homepage', 'tznew'),
        'section'     => 'tznew_destinations_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 1,
            'max'  => 12,
            'step' => 1,
        ],
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
    // STATISTICS SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_statistics_section', [
        'title'    => __('Statistics Section', 'tznew'),
        'panel'    => 'tznew_front_page_panel',
        'priority' => 45,
    ]);

    // Show Statistics Section
    $wp_customize->add_setting('tznew_statistics_show', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_statistics_show', [
        'label'   => __('Show Statistics Section', 'tznew'),
        'section' => 'tznew_statistics_section',
        'type'    => 'checkbox',
    ]);

    // Statistics Background Color 1 (Gradient Start)
    $wp_customize->add_setting('tznew_statistics_bg_color_1', [
        'default'           => '#059669',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_statistics_bg_color_1', [
        'label'   => __('Background Color 1 (Gradient Start)', 'tznew'),
        'section' => 'tznew_statistics_section',
    ]));

    // Statistics Background Color 2 (Gradient End)
    $wp_customize->add_setting('tznew_statistics_bg_color_2', [
        'default'           => '#0891b2',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_statistics_bg_color_2', [
        'label'   => __('Background Color 2 (Gradient End)', 'tznew'),
        'section' => 'tznew_statistics_section',
    ]));

    // Statistics Background Opacity
    $wp_customize->add_setting('tznew_statistics_bg_opacity', [
        'default'           => 1.0,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_statistics_bg_opacity', [
        'label'       => __('Background Opacity', 'tznew'),
        'description' => __('Adjust the opacity of the background (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_statistics_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

    // Statistics Image Overlay Color
    $wp_customize->add_setting('tznew_statistics_overlay_color', [
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_statistics_overlay_color', [
        'label'   => __('Image Overlay Color', 'tznew'),
        'section' => 'tznew_statistics_section',
    ]));

    // Statistics Image Overlay Opacity
    $wp_customize->add_setting('tznew_statistics_overlay_opacity', [
        'default'           => 0.2,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_statistics_overlay_opacity', [
        'label'       => __('Image Overlay Opacity', 'tznew'),
        'description' => __('Adjust the opacity of the image overlay (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_statistics_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

    // ==========================================================================
    // PLAN YOUR ADVENTURE SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_adventure_section', [
        'title'    => __('Plan Your Adventure Section', 'tznew'),
        'panel'    => 'tznew_front_page_panel',
        'priority' => 46,
    ]);

    // Show Adventure Section
    $wp_customize->add_setting('tznew_adventure_show', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_adventure_show', [
        'label'   => __('Show Plan Your Adventure Section', 'tznew'),
        'section' => 'tznew_adventure_section',
        'type'    => 'checkbox',
    ]);

    // Adventure Background Color 1 (Gradient Start)
    $wp_customize->add_setting('tznew_adventure_bg_color_1', [
        'default'           => '#059669',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_adventure_bg_color_1', [
        'label'   => __('Background Color 1 (Gradient Start)', 'tznew'),
        'section' => 'tznew_adventure_section',
    ]));

    // Adventure Background Color 2 (Gradient End)
    $wp_customize->add_setting('tznew_adventure_bg_color_2', [
        'default'           => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_adventure_bg_color_2', [
        'label'   => __('Background Color 2 (Gradient End)', 'tznew'),
        'section' => 'tznew_adventure_section',
    ]));

    // Adventure Background Opacity
    $wp_customize->add_setting('tznew_adventure_bg_opacity', [
        'default'           => 1.0,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_adventure_bg_opacity', [
        'label'       => __('Background Opacity', 'tznew'),
        'description' => __('Adjust the opacity of the background (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_adventure_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

    // Adventure Image Overlay Color
    $wp_customize->add_setting('tznew_adventure_overlay_color', [
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_adventure_overlay_color', [
        'label'   => __('Image Overlay Color', 'tznew'),
        'section' => 'tznew_adventure_section',
    ]));

    // Adventure Image Overlay Opacity
    $wp_customize->add_setting('tznew_adventure_overlay_opacity', [
        'default'           => 0.2,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_adventure_overlay_opacity', [
        'label'       => __('Image Overlay Opacity', 'tznew'),
        'description' => __('Adjust the opacity of the image overlay (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_adventure_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

    // ==========================================================================
    // POPULAR TOURS SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_popular_tours_section', [
        'title'    => __('Popular Tours Section', 'tznew'),
        'panel'    => 'tznew_front_page_panel',
        'priority' => 47,
    ]);

    // Show Popular Tours Section
    $wp_customize->add_setting('tznew_popular_tours_show', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_popular_tours_show', [
        'label'   => __('Show Popular Tours Section', 'tznew'),
        'section' => 'tznew_popular_tours_section',
        'type'    => 'checkbox',
    ]);

    // Popular Tours Count
    $wp_customize->add_setting('tznew_popular_tours_count', [
        'default'           => 6,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_popular_tours_count', [
        'label'       => __('Number of Popular Tours to Display', 'tznew'),
        'description' => __('Choose how many popular tours to show on the homepage', 'tznew'),
        'section'     => 'tznew_popular_tours_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 1,
            'max'  => 12,
            'step' => 1,
        ],
    ]);

    // Popular Tours Content Ordering
    $wp_customize->add_setting('tznew_popular_tours_order', [
        'default'           => 'latest',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_popular_tours_order', [
        'label'   => __('Popular Tours Order', 'tznew'),
        'section' => 'tznew_popular_tours_section',
        'type'    => 'select',
        'choices' => [
            'latest' => __('Latest Tours', 'tznew'),
            'random' => __('Random Tours', 'tznew'),
            'popular' => __('Most Popular', 'tznew'),
            'featured' => __('Featured Only', 'tznew'),
        ],
    ]);

    // Popular Tours Background Color 1 (Gradient Start)
    $wp_customize->add_setting('tznew_popular_tours_bg_color_1', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_popular_tours_bg_color_1', [
        'label'   => __('Background Color 1 (Gradient Start)', 'tznew'),
        'section' => 'tznew_popular_tours_section',
    ]));

    // Popular Tours Background Color 2 (Gradient Middle)
    $wp_customize->add_setting('tznew_popular_tours_bg_color_2', [
        'default'           => '#dbeafe',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_popular_tours_bg_color_2', [
        'label'   => __('Background Color 2 (Gradient Middle)', 'tznew'),
        'section' => 'tznew_popular_tours_section',
    ]));

    // Popular Tours Background Color 3 (Gradient End)
    $wp_customize->add_setting('tznew_popular_tours_bg_color_3', [
        'default'           => '#e0e7ff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_popular_tours_bg_color_3', [
        'label'   => __('Background Color 3 (Gradient End)', 'tznew'),
        'section' => 'tznew_popular_tours_section',
    ]));

    // Popular Tours Background Opacity
    $wp_customize->add_setting('tznew_popular_tours_bg_opacity', [
        'default'           => 1.0,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_popular_tours_bg_opacity', [
        'label'       => __('Background Opacity', 'tznew'),
        'description' => __('Adjust the opacity of the background (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_popular_tours_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

    // Popular Tours Image Overlay Color
    $wp_customize->add_setting('tznew_popular_tours_overlay_color', [
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_popular_tours_overlay_color', [
        'label'   => __('Image Overlay Color', 'tznew'),
        'section' => 'tznew_popular_tours_section',
    ]));

    // Popular Tours Image Overlay Opacity
    $wp_customize->add_setting('tznew_popular_tours_overlay_opacity', [
        'default'           => 0.2,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_popular_tours_overlay_opacity', [
        'label'       => __('Image Overlay Opacity', 'tznew'),
        'description' => __('Adjust the opacity of the image overlay (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_popular_tours_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

    // ==========================================================================
    // INTERESTING TREK BLOCKS SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_trek_blocks_section', [
        'title'    => __('Interesting Trek Blocks Section', 'tznew'),
        'panel'    => 'tznew_front_page_panel',
        'priority' => 45,
    ]);

    // Show Trek Blocks Section
    $wp_customize->add_setting('tznew_show_trek_blocks', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_show_trek_blocks', [
        'label'   => __('Show Interesting Trek Blocks Section', 'tznew'),
        'section' => 'tznew_trek_blocks_section',
        'type'    => 'checkbox',
    ]);

    // ==========================================================================
    // WHY CHOOSE NEPAL SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_why_choose_section', [
        'title'    => __('Why Choose Nepal Section', 'tznew'),
        'panel'    => 'tznew_front_page_panel',
        'priority' => 46,
    ]);

    // Show Why Choose Section
    $wp_customize->add_setting('tznew_show_why_choose', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_show_why_choose', [
        'label'   => __('Show Why Choose Nepal Section', 'tznew'),
        'section' => 'tznew_why_choose_section',
        'type'    => 'checkbox',
    ]);

    // ==========================================================================
    // POPULAR TRIPS SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_popular_trips_section', [
        'title'    => __('Popular Trips Section', 'tznew'),
        'panel'    => 'tznew_front_page_panel',
        'priority' => 47,
    ]);

    // Show Popular Trips Section
    $wp_customize->add_setting('tznew_show_popular_trips', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_show_popular_trips', [
        'label'   => __('Show Popular Trips Section', 'tznew'),
        'section' => 'tznew_popular_trips_section',
        'type'    => 'checkbox',
    ]);

    // Popular Trips Count
    $wp_customize->add_setting('tznew_popular_trips_count', [
        'default'           => 3,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_popular_trips_count', [
        'label'       => __('Number of Popular Trips to Display', 'tznew'),
        'description' => __('How many popular trips to display', 'tznew'),
        'section'     => 'tznew_popular_trips_section',
        'type'        => 'number',
        'input_attrs' => [
            'min' => 1,
            'max' => 12,
        ],
    ]);

    // Popular Trips Content Ordering
    $wp_customize->add_setting('tznew_popular_trips_order', [
        'default'           => 'latest',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_popular_trips_order', [
        'label'   => __('Popular Trips Order', 'tznew'),
        'section' => 'tznew_popular_trips_section',
        'type'    => 'select',
        'choices' => [
            'latest' => __('Latest Trips', 'tznew'),
            'random' => __('Random Trips', 'tznew'),
            'popular' => __('Most Popular', 'tznew'),
            'featured' => __('Featured Only', 'tznew'),
        ],
    ]);

    // ==========================================================================
    // TESTIMONIALS SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_testimonials_section', [
        'title'    => __('Testimonials Section', 'tznew'),
        'panel'    => 'tznew_front_page_panel',
        'priority' => 48,
    ]);

    // Show Testimonials Section
    $wp_customize->add_setting('tznew_testimonials_show', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_testimonials_show', [
        'label'   => __('Show Testimonials Section', 'tznew'),
        'section' => 'tznew_testimonials_section',
        'type'    => 'checkbox',
    ]);

    // Testimonials Count
    $wp_customize->add_setting('tznew_testimonials_count', [
        'default'           => 6,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_testimonials_count', [
        'label'       => __('Number of Testimonials to Display', 'tznew'),
        'description' => __('Choose how many testimonials to show on the homepage', 'tznew'),
        'section'     => 'tznew_testimonials_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 1,
            'max'  => 12,
            'step' => 1,
        ],
    ]);

    // Testimonials Content Ordering
    $wp_customize->add_setting('tznew_testimonials_order', [
        'default'           => 'latest',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_testimonials_order', [
        'label'   => __('Testimonials Order', 'tznew'),
        'section' => 'tznew_testimonials_section',
        'type'    => 'select',
        'choices' => [
            'latest' => __('Latest Testimonials', 'tznew'),
            'random' => __('Random Testimonials', 'tznew'),
            'rating' => __('Highest Rated', 'tznew'),
            'featured' => __('Featured Only', 'tznew'),
        ],
    ]);

    // Testimonials Background Color 1 (Gradient Start)
    $wp_customize->add_setting('tznew_testimonials_bg_color_1', [
        'default'           => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_testimonials_bg_color_1', [
        'label'   => __('Background Color 1 (Gradient Start)', 'tznew'),
        'section' => 'tznew_testimonials_section',
    ]));

    // Testimonials Background Color 2 (Gradient Middle)
    $wp_customize->add_setting('tznew_testimonials_bg_color_2', [
        'default'           => '#4338ca',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_testimonials_bg_color_2', [
        'label'   => __('Background Color 2 (Gradient Middle)', 'tznew'),
        'section' => 'tznew_testimonials_section',
    ]));

    // Testimonials Background Color 3 (Gradient End)
    $wp_customize->add_setting('tznew_testimonials_bg_color_3', [
        'default'           => '#7c3aed',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_testimonials_bg_color_3', [
        'label'   => __('Background Color 3 (Gradient End)', 'tznew'),
        'section' => 'tznew_testimonials_section',
    ]));

    // Testimonials Background Opacity
    $wp_customize->add_setting('tznew_testimonials_bg_opacity', [
        'default'           => 1.0,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_testimonials_bg_opacity', [
        'label'       => __('Background Opacity', 'tznew'),
        'description' => __('Adjust the opacity of the background (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_testimonials_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

    // Testimonials Image Overlay Color
    $wp_customize->add_setting('tznew_testimonials_overlay_color', [
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_testimonials_overlay_color', [
        'label'   => __('Image Overlay Color', 'tznew'),
        'section' => 'tznew_testimonials_section',
    ]));

    // Testimonials Image Overlay Opacity
    $wp_customize->add_setting('tznew_testimonials_overlay_opacity', [
        'default'           => 0.2,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_testimonials_overlay_opacity', [
        'label'       => __('Image Overlay Opacity', 'tznew'),
        'description' => __('Adjust the opacity of the image overlay (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_testimonials_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

    // ==========================================================================
    // BLOG SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_blog_section', [
        'title'    => __('Blog Section', 'tznew'),
        'panel'    => 'tznew_front_page_panel',
        'priority' => 45,
    ]);

    // Blog Section Show/Hide
    $wp_customize->add_setting('tznew_blog_show', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_blog_show', [
        'label'   => __('Show Blog Section', 'tznew'),
        'section' => 'tznew_blog_section',
        'type'    => 'checkbox',
    ]);

    // Blog Section Title
    $wp_customize->add_setting('tznew_blog_title', [
        'default'           => 'Latest Blog Posts',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_blog_title', [
        'label'   => __('Blog Section Title', 'tznew'),
        'section' => 'tznew_blog_section',
        'type'    => 'text',
    ]);

    // Blog Section Subtitle
    $wp_customize->add_setting('tznew_blog_subtitle', [
        'default'           => 'Stay updated with our latest adventures and travel tips',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_blog_subtitle', [
        'label'   => __('Blog Section Subtitle', 'tznew'),
        'section' => 'tznew_blog_section',
        'type'    => 'textarea',
    ]);

    // Blog Posts Count
    $wp_customize->add_setting('tznew_blog_count', [
        'default'           => 3,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_blog_count', [
        'label'       => __('Number of Blog Posts', 'tznew'),
        'description' => __('How many blog posts to display', 'tznew'),
        'section'     => 'tznew_blog_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 1,
            'max'  => 12,
            'step' => 1,
        ],
    ]);

    // Blog Content Ordering
    $wp_customize->add_setting('tznew_blog_order', [
        'default'           => 'latest',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_blog_order', [
        'label'   => __('Blog Posts Order', 'tznew'),
        'section' => 'tznew_blog_section',
        'type'    => 'select',
        'choices' => [
            'latest' => __('Latest Posts', 'tznew'),
            'random' => __('Random Posts', 'tznew'),
            'popular' => __('Most Popular', 'tznew'),
        ],
    ]);

    // Blog Background Color 1
    $wp_customize->add_setting('tznew_blog_bg_color_1', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_blog_bg_color_1', [
        'label'   => __('Background Color 1', 'tznew'),
        'section' => 'tznew_blog_section',
    ]));

    // Blog Background Color 2
    $wp_customize->add_setting('tznew_blog_bg_color_2', [
        'default'           => '#f8fafc',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_blog_bg_color_2', [
        'label'   => __('Background Color 2 (Gradient)', 'tznew'),
        'section' => 'tznew_blog_section',
    ]));

    // Blog Background Opacity
    $wp_customize->add_setting('tznew_blog_bg_opacity', [
        'default'           => 1.0,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_blog_bg_opacity', [
        'label'       => __('Background Opacity', 'tznew'),
        'description' => __('Adjust the opacity of the background (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_blog_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

    // Blog Image Overlay Color
    $wp_customize->add_setting('tznew_blog_overlay_color', [
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_blog_overlay_color', [
        'label'   => __('Image Overlay Color', 'tznew'),
        'section' => 'tznew_blog_section',
    ]));

    // Blog Image Overlay Opacity
    $wp_customize->add_setting('tznew_blog_overlay_opacity', [
        'default'           => 0.2,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_blog_overlay_opacity', [
        'label'       => __('Image Overlay Opacity', 'tznew'),
        'description' => __('Adjust the opacity of the image overlay (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_blog_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

    // ==========================================================================
    // CTA SECTION
    // ==========================================================================
    
    $wp_customize->add_section('tznew_cta_section', [
        'title'    => __('Call to Action Section', 'tznew'),
        'panel'    => 'tznew_front_page_panel',
        'priority' => 46,
    ]);

    // CTA Section Show/Hide
    $wp_customize->add_setting('tznew_cta_show', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_cta_show', [
        'label'   => __('Show CTA Section', 'tznew'),
        'section' => 'tznew_cta_section',
        'type'    => 'checkbox',
    ]);

    // CTA Title
    $wp_customize->add_setting('tznew_cta_title', [
        'default'           => 'Ready for Your Next Adventure?',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_cta_title', [
        'label'   => __('CTA Title', 'tznew'),
        'section' => 'tznew_cta_section',
        'type'    => 'text',
    ]);

    // CTA Content
    $wp_customize->add_setting('tznew_cta_content', [
        'default'           => 'Join thousands of adventurers who have discovered the magic of Nepal with us.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_cta_content', [
        'label'   => __('CTA Content', 'tznew'),
        'section' => 'tznew_cta_section',
        'type'    => 'textarea',
    ]);

    // CTA Button Text
    $wp_customize->add_setting('tznew_cta_button_text', [
        'default'           => 'Start Planning',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_cta_button_text', [
        'label'   => __('CTA Button Text', 'tznew'),
        'section' => 'tznew_cta_section',
        'type'    => 'text',
    ]);

    // CTA Button URL
    $wp_customize->add_setting('tznew_cta_button_url', [
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_cta_button_url', [
        'label'   => __('CTA Button URL', 'tznew'),
        'section' => 'tznew_cta_section',
        'type'    => 'url',
    ]);

    // CTA Background Image
    $wp_customize->add_setting('tznew_cta_background', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'tznew_cta_background', [
        'label'   => __('CTA Background Image', 'tznew'),
        'section' => 'tznew_cta_section',
    ]));

    // CTA Overlay Opacity
    $wp_customize->add_setting('tznew_cta_overlay_opacity', [
        'default'           => 0.6,
        'sanitize_callback' => 'tznew_sanitize_number_range',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_cta_overlay_opacity', [
        'label'       => __('Background Overlay Opacity', 'tznew'),
        'description' => __('Adjust the opacity of the background overlay (0 = transparent, 1 = opaque)', 'tznew'),
        'section'     => 'tznew_cta_section',
        'type'        => 'range',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ],
    ]);

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
    
    // Featured Treks Section
    $featured_treks_bg_1 = get_theme_mod('tznew_featured_treks_bg_color_1', '#f9fafb');
    $featured_treks_bg_2 = get_theme_mod('tznew_featured_treks_bg_color_2', '#ffffff');
    $featured_treks_bg_opacity = get_theme_mod('tznew_featured_treks_bg_opacity', 1.0);
    $featured_treks_overlay_color = get_theme_mod('tznew_featured_treks_overlay_color', '#000000');
    $featured_treks_overlay_opacity = get_theme_mod('tznew_featured_treks_overlay_opacity', 0.2);
    
    // Regions Section
    $regions_bg_1 = get_theme_mod('tznew_regions_bg_color_1', '#ffffff');
    $regions_bg_2 = get_theme_mod('tznew_regions_bg_color_2', '#f8fafc');
    $regions_bg_3 = get_theme_mod('tznew_regions_bg_color_3', '#f1f5f9');
    $regions_bg_opacity = get_theme_mod('tznew_regions_bg_opacity', 1.0);
    $regions_overlay_color = get_theme_mod('tznew_regions_overlay_color', '#000000');
    $regions_overlay_opacity = get_theme_mod('tznew_regions_overlay_opacity', 0.2);
    
    // Destinations Section
    $destinations_bg = get_theme_mod('tznew_destinations_bg_color', '#f8fafc');
    
    // Statistics Section
    $statistics_bg_1 = get_theme_mod('tznew_statistics_bg_color_1', '#059669');
    $statistics_bg_2 = get_theme_mod('tznew_statistics_bg_color_2', '#0891b2');
    $statistics_bg_opacity = get_theme_mod('tznew_statistics_bg_opacity', 1.0);
    $statistics_overlay_color = get_theme_mod('tznew_statistics_overlay_color', '#000000');
    $statistics_overlay_opacity = get_theme_mod('tznew_statistics_overlay_opacity', 0.2);
    
    // Adventure Section
    $adventure_bg_1 = get_theme_mod('tznew_adventure_bg_color_1', '#059669');
    $adventure_bg_2 = get_theme_mod('tznew_adventure_bg_color_2', '#2563eb');
    $adventure_bg_opacity = get_theme_mod('tznew_adventure_bg_opacity', 1.0);
    $adventure_overlay_color = get_theme_mod('tznew_adventure_overlay_color', '#000000');
    $adventure_overlay_opacity = get_theme_mod('tznew_adventure_overlay_opacity', 0.2);
    
    // Popular Tours Section
    $popular_tours_bg_1 = get_theme_mod('tznew_popular_tours_bg_color_1', '#ffffff');
    $popular_tours_bg_2 = get_theme_mod('tznew_popular_tours_bg_color_2', '#dbeafe');
    $popular_tours_bg_3 = get_theme_mod('tznew_popular_tours_bg_color_3', '#e0e7ff');
    $popular_tours_bg_opacity = get_theme_mod('tznew_popular_tours_bg_opacity', 1.0);
    $popular_tours_overlay_color = get_theme_mod('tznew_popular_tours_overlay_color', '#000000');
    $popular_tours_overlay_opacity = get_theme_mod('tznew_popular_tours_overlay_opacity', 0.2);
    
    // Testimonials Section
    $testimonials_bg_1 = get_theme_mod('tznew_testimonials_bg_color_1', '#2563eb');
    $testimonials_bg_2 = get_theme_mod('tznew_testimonials_bg_color_2', '#4338ca');
    $testimonials_bg_3 = get_theme_mod('tznew_testimonials_bg_color_3', '#7c3aed');
    $testimonials_bg_opacity = get_theme_mod('tznew_testimonials_bg_opacity', 1.0);
    $testimonials_overlay_color = get_theme_mod('tznew_testimonials_overlay_color', '#000000');
    $testimonials_overlay_opacity = get_theme_mod('tznew_testimonials_overlay_opacity', 0.2);
    
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
    
    // Section Backgrounds with Opacity and Overlays
    $css .= ".popular-treks { background: linear-gradient(rgba(" . implode(',', sscanf($featured_treks_overlay_color, '#%02x%02x%02x')) . ",{$featured_treks_overlay_opacity}), rgba(" . implode(',', sscanf($featured_treks_overlay_color, '#%02x%02x%02x')) . ",{$featured_treks_overlay_opacity})), linear-gradient(to bottom right, rgba(" . implode(',', sscanf($featured_treks_bg_1, '#%02x%02x%02x')) . ",{$featured_treks_bg_opacity}), rgba(" . implode(',', sscanf($featured_treks_bg_2, '#%02x%02x%02x')) . ",{$featured_treks_bg_opacity})) !important; }";
    
    $css .= ".trekking-regions { background: linear-gradient(rgba(" . implode(',', sscanf($regions_overlay_color, '#%02x%02x%02x')) . ",{$regions_overlay_opacity}), rgba(" . implode(',', sscanf($regions_overlay_color, '#%02x%02x%02x')) . ",{$regions_overlay_opacity})), linear-gradient(to bottom right, rgba(" . implode(',', sscanf($regions_bg_1, '#%02x%02x%02x')) . ",{$regions_bg_opacity}), rgba(" . implode(',', sscanf($regions_bg_2, '#%02x%02x%02x')) . ",{$regions_bg_opacity}), rgba(" . implode(',', sscanf($regions_bg_3, '#%02x%02x%02x')) . ",{$regions_bg_opacity})) !important; }";
    
    $css .= ".destinations { background-color: {$destinations_bg} !important; }";
    
    $css .= ".statistics-section { background: linear-gradient(rgba(" . implode(',', sscanf($statistics_overlay_color, '#%02x%02x%02x')) . ",{$statistics_overlay_opacity}), rgba(" . implode(',', sscanf($statistics_overlay_color, '#%02x%02x%02x')) . ",{$statistics_overlay_opacity})), linear-gradient(to right, rgba(" . implode(',', sscanf($statistics_bg_1, '#%02x%02x%02x')) . ",{$statistics_bg_opacity}), rgba(" . implode(',', sscanf($statistics_bg_2, '#%02x%02x%02x')) . ",{$statistics_bg_opacity})) !important; }";
    
    $css .= ".adventure-section { background: linear-gradient(rgba(" . implode(',', sscanf($adventure_overlay_color, '#%02x%02x%02x')) . ",{$adventure_overlay_opacity}), rgba(" . implode(',', sscanf($adventure_overlay_color, '#%02x%02x%02x')) . ",{$adventure_overlay_opacity})), linear-gradient(to bottom right, rgba(" . implode(',', sscanf($adventure_bg_1, '#%02x%02x%02x')) . ",{$adventure_bg_opacity}), rgba(" . implode(',', sscanf($adventure_bg_2, '#%02x%02x%02x')) . ",{$adventure_bg_opacity})) !important; }";
    
    $css .= ".popular-tours-section { background: linear-gradient(rgba(" . implode(',', sscanf($popular_tours_overlay_color, '#%02x%02x%02x')) . ",{$popular_tours_overlay_opacity}), rgba(" . implode(',', sscanf($popular_tours_overlay_color, '#%02x%02x%02x')) . ",{$popular_tours_overlay_opacity})), linear-gradient(to bottom right, rgba(" . implode(',', sscanf($popular_tours_bg_1, '#%02x%02x%02x')) . ",{$popular_tours_bg_opacity}), rgba(" . implode(',', sscanf($popular_tours_bg_2, '#%02x%02x%02x')) . ",{$popular_tours_bg_opacity}), rgba(" . implode(',', sscanf($popular_tours_bg_3, '#%02x%02x%02x')) . ",{$popular_tours_bg_opacity})) !important; }";
    
    $css .= ".testimonials-section { background: linear-gradient(rgba(" . implode(',', sscanf($testimonials_overlay_color, '#%02x%02x%02x')) . ",{$testimonials_overlay_opacity}), rgba(" . implode(',', sscanf($testimonials_overlay_color, '#%02x%02x%02x')) . ",{$testimonials_overlay_opacity})), linear-gradient(to bottom right, rgba(" . implode(',', sscanf($testimonials_bg_1, '#%02x%02x%02x')) . ",{$testimonials_bg_opacity}), rgba(" . implode(',', sscanf($testimonials_bg_2, '#%02x%02x%02x')) . ",{$testimonials_bg_opacity}), rgba(" . implode(',', sscanf($testimonials_bg_3, '#%02x%02x%02x')) . ",{$testimonials_bg_opacity})) !important; }";
    
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