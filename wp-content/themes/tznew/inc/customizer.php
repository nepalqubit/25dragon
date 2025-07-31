<?php
/**
 * Theme Customizer Setup
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
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function tznew_customize_register($wp_customize) {
    // Add postMessage support for site title and tagline
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('blogname', [
            'selector'        => '.site-title a',
            'render_callback' => 'tznew_customize_partial_blogname',
        ]);
        $wp_customize->selective_refresh->add_partial('blogdescription', [
            'selector'        => '.site-description',
            'render_callback' => 'tznew_customize_partial_blogdescription',
        ]);
    }

    // Theme Options Panel
    $wp_customize->add_panel('tznew_theme_options', [
        'title'       => __('TZnew Theme Options', 'tznew'),
        'description' => __('Customize various theme settings', 'tznew'),
        'priority'    => 30,
    ]);

    // Header Section
    $wp_customize->add_section('tznew_header_section', [
        'title'    => __('Header Settings', 'tznew'),
        'panel'    => 'tznew_theme_options',
        'priority' => 10,
    ]);

    // Header Layout
    $wp_customize->add_setting('tznew_header_layout', [
        'default'           => 'default',
        'sanitize_callback' => 'tznew_sanitize_select',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_header_layout', [
        'label'    => __('Header Layout', 'tznew'),
        'section'  => 'tznew_header_section',
        'type'     => 'select',
        'choices'  => [
            'default' => __('Default', 'tznew'),
            'centered' => __('Centered', 'tznew'),
            'minimal' => __('Minimal', 'tznew'),
        ],
    ]);

    // Show Search in Header
    $wp_customize->add_setting('tznew_header_search', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_header_search', [
        'label'   => __('Show Search in Header', 'tznew'),
        'section' => 'tznew_header_section',
        'type'    => 'checkbox',
    ]);

    // Contact Information Section
    $wp_customize->add_section('tznew_contact_section', [
        'title'    => __('Contact Information', 'tznew'),
        'panel'    => 'tznew_theme_options',
        'priority' => 20,
    ]);

    // Phone Number
    $wp_customize->add_setting('tznew_phone', [
        'default'           => '+977 1234567890',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_phone', [
        'label'   => __('Phone Number', 'tznew'),
        'section' => 'tznew_contact_section',
        'type'    => 'text',
    ]);

    // Email Address
    $wp_customize->add_setting('tznew_email', [
        'default'           => 'web@techzeninc.com',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_email', [
        'label'   => __('Email Address', 'tznew'),
        'section' => 'tznew_contact_section',
        'type'    => 'email',
    ]);

    // WhatsApp Number
    $wp_customize->add_setting('tznew_whatsapp', [
        'default'           => '+977 9841234567',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_whatsapp', [
        'label'   => __('WhatsApp Number', 'tznew'),
        'section' => 'tznew_contact_section',
        'type'    => 'text',
    ]);

    // Address
    $wp_customize->add_setting('tznew_address', [
        'default'           => 'Sherpa Mall, Durbarmarg, Kathmandu, Nepal',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_address', [
        'label'   => __('Address', 'tznew'),
        'section' => 'tznew_contact_section',
        'type'    => 'textarea',
    ]);

    // Social Media Section
    $wp_customize->add_section('tznew_social_section', [
        'title'    => __('Social Media Links', 'tznew'),
        'panel'    => 'tznew_theme_options',
        'priority' => 30,
    ]);

    // Social Media Links
    $social_networks = [
        'facebook'  => __('Facebook', 'tznew'),
        'twitter'   => __('Twitter', 'tznew'),
        'instagram' => __('Instagram', 'tznew'),
        'linkedin'  => __('LinkedIn', 'tznew'),
        'youtube'   => __('YouTube', 'tznew'),
        'tripadvisor' => __('TripAdvisor', 'tznew'),
    ];

    foreach ($social_networks as $network => $label) {
        $wp_customize->add_setting("tznew_social_{$network}", [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        ]);

        $wp_customize->add_control("tznew_social_{$network}", [
            'label'   => $label . ' ' . __('URL', 'tznew'),
            'section' => 'tznew_social_section',
            'type'    => 'url',
        ]);
    }

    // Section Backgrounds Section
    $wp_customize->add_section('tznew_section_backgrounds', [
        'title'    => __('Section Backgrounds', 'tznew'),
        'panel'    => 'tznew_theme_options',
        'priority' => 35,
        'description' => __('Customize gradient backgrounds for different sections of your site. Enter CSS gradient values like: linear-gradient(135deg, rgba(255,0,0,0.8), rgba(0,255,255,0.6))', 'tznew'),
    ]);
    
    // Header Gradient Background
    $wp_customize->add_setting('tznew_header_gradient_bg', [
        'default'           => 'linear-gradient(135deg, rgba(59, 130, 246, 0.8), rgba(16, 185, 129, 0.6))',
        'sanitize_callback' => 'tznew_sanitize_gradient_css',
        'transport'         => 'postMessage',
    ]);
    
    $wp_customize->add_control('tznew_header_gradient_bg', [
        'label'       => __('Header Gradient Background', 'tznew'),
        'section'     => 'tznew_section_backgrounds',
        'type'        => 'textarea',
        'description' => __('Enter CSS gradient, e.g. linear-gradient(90deg, rgba(0,0,0,0.7), rgba(255,255,255,0.7))', 'tznew'),
    ]);
    
    // Footer Gradient Background
    $wp_customize->add_setting('tznew_footer_gradient_bg', [
        'default'           => 'linear-gradient(90deg, rgba(31, 41, 55, 0.9), rgba(17, 24, 39, 0.9))',
        'sanitize_callback' => 'tznew_sanitize_gradient_css',
        'transport'         => 'postMessage',
    ]);
    
    $wp_customize->add_control('tznew_footer_gradient_bg', [
        'label'       => __('Footer Gradient Background', 'tznew'),
        'section'     => 'tznew_section_backgrounds',
        'type'        => 'textarea',
        'description' => __('Enter CSS gradient, e.g. linear-gradient(90deg, rgba(0,0,0,0.7), rgba(255,255,255,0.7))', 'tznew'),
    ]);
    
    // Sidebar Gradient Background
    $wp_customize->add_setting('tznew_sidebar_gradient_bg', [
        'default'           => 'linear-gradient(180deg, rgba(243, 244, 246, 0.7), rgba(229, 231, 235, 0.7))',
        'sanitize_callback' => 'tznew_sanitize_gradient_css',
        'transport'         => 'postMessage',
    ]);
    
    $wp_customize->add_control('tznew_sidebar_gradient_bg', [
        'label'       => __('Sidebar Gradient Background', 'tznew'),
        'section'     => 'tznew_section_backgrounds',
        'type'        => 'textarea',
        'description' => __('Enter CSS gradient, e.g. linear-gradient(90deg, rgba(0,0,0,0.7), rgba(255,255,255,0.7))', 'tznew'),
    ]);
    
    // Content Section Gradient Background
    $wp_customize->add_setting('tznew_content_gradient_bg', [
        'default'           => 'linear-gradient(180deg, rgba(255, 255, 255, 1), rgba(249, 250, 251, 1))',
        'sanitize_callback' => 'tznew_sanitize_gradient_css',
        'transport'         => 'postMessage',
    ]);
    
    $wp_customize->add_control('tznew_content_gradient_bg', [
        'label'       => __('Content Section Gradient Background', 'tznew'),
        'section'     => 'tznew_section_backgrounds',
        'type'        => 'textarea',
        'description' => __('Enter CSS gradient, e.g. linear-gradient(90deg, rgba(0,0,0,0.7), rgba(255,255,255,0.7))', 'tznew'),
    ]);
    
    // Hero/Banner Section Gradient Background
    $wp_customize->add_setting('tznew_hero_gradient_bg', [
        'default'           => 'linear-gradient(135deg, rgba(245, 158, 11, 0.8), rgba(239, 68, 68, 0.7))',
        'sanitize_callback' => 'tznew_sanitize_gradient_css',
        'transport'         => 'postMessage',
    ]);
    
    $wp_customize->add_control('tznew_hero_gradient_bg', [
        'label'       => __('Hero/Banner Gradient Background', 'tznew'),
        'section'     => 'tznew_section_backgrounds',
        'type'        => 'textarea',
        'description' => __('Enter CSS gradient, e.g. linear-gradient(90deg, rgba(0,0,0,0.7), rgba(255,255,255,0.7))', 'tznew'),
    ]);
    
    // Footer Section
    $wp_customize->add_section('tznew_footer_section', [
        'title'    => __('Footer Settings', 'tznew'),
        'panel'    => 'tznew_theme_options',
        'priority' => 40,
    ]);

    // Footer Logo
    $wp_customize->add_setting('tznew_footer_logo', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'tznew_footer_logo', [
        'label'       => __('Footer Logo', 'tznew'),
        'section'     => 'tznew_footer_section',
        'description' => __('Upload a logo to display in the footer. Recommended size: 150x150px', 'tznew'),
    ]));

    // Footer Copyright Text
    $wp_customize->add_setting('tznew_footer_copyright', [
        'default'           => sprintf(__('© %s %s. All rights reserved.', 'tznew'), date('Y'), get_bloginfo('name')),
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('tznew_footer_copyright', [
        'label'   => __('Copyright Text', 'tznew'),
        'section' => 'tznew_footer_section',
        'type'    => 'textarea',
    ]);

    // Show Footer Social Links
    $wp_customize->add_setting('tznew_footer_social', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_footer_social', [
        'label'   => __('Show Social Links in Footer', 'tznew'),
        'section' => 'tznew_footer_section',
        'type'    => 'checkbox',
    ]);

    // Blog Settings Section
    $wp_customize->add_section('tznew_blog_section', [
        'title'    => __('Blog Settings', 'tznew'),
        'panel'    => 'tznew_theme_options',
        'priority' => 50,
    ]);

    // Blog Layout
    $wp_customize->add_setting('tznew_blog_layout', [
        'default'           => 'grid',
        'sanitize_callback' => 'tznew_sanitize_select',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_blog_layout', [
        'label'    => __('Blog Layout', 'tznew'),
        'section'  => 'tznew_blog_section',
        'type'     => 'select',
        'choices'  => [
            'grid' => __('Grid Layout', 'tznew'),
            'list' => __('List Layout', 'tznew'),
            'masonry' => __('Masonry Layout', 'tznew'),
        ],
    ]);

    // Show Excerpt
    $wp_customize->add_setting('tznew_blog_excerpt', [
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_blog_excerpt', [
        'label'   => __('Show Post Excerpt', 'tznew'),
        'section' => 'tznew_blog_section',
        'type'    => 'checkbox',
    ]);

    // Excerpt Length
    $wp_customize->add_setting('tznew_excerpt_length', [
        'default'           => 20,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('tznew_excerpt_length', [
        'label'       => __('Excerpt Length (words)', 'tznew'),
        'section'     => 'tznew_blog_section',
        'type'        => 'number',
        'input_attrs' => [
            'min'  => 10,
            'max'  => 100,
            'step' => 1,
        ],
    ]);

    // Colors Section
    $wp_customize->add_section('tznew_colors_section', [
        'title'    => __('Theme Colors', 'tznew'),
        'panel'    => 'tznew_theme_options',
        'priority' => 60,
    ]);

    // Primary Color
    $wp_customize->add_setting('tznew_primary_color', [
        'default'           => '#3b82f6',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_primary_color', [
        'label'   => __('Primary Color', 'tznew'),
        'section' => 'tznew_colors_section',
    ]));

    // Secondary Color
    $wp_customize->add_setting('tznew_secondary_color', [
        'default'           => '#10b981',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_secondary_color', [
        'label'   => __('Secondary Color', 'tznew'),
        'section' => 'tznew_colors_section',
    ]));

    // Accent Color
    $wp_customize->add_setting('tznew_accent_color', [
        'default'           => '#f59e0b',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_accent_color', [
        'label'   => __('Accent Color', 'tznew'),
        'section' => 'tznew_colors_section',
    ]));
}
add_action('customize_register', 'tznew_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function tznew_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function tznew_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Sanitize select fields
 */
function tznew_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Bind JS handlers to instantly live-preview changes.
 */
function tznew_customize_preview_js() {
    wp_enqueue_script('tznew-customizer', TZNEW_THEME_URI . '/assets/js/customizer.js', ['customize-preview'], TZNEW_VERSION, true);
    
    // Enqueue color customizer preview script
    wp_enqueue_script(
        'tznew-customizer-preview',
        get_template_directory_uri() . '/assets/js/customizer-preview.js',
        ['customize-preview'],
        defined('TZNEW_VERSION') ? TZNEW_VERSION : '1.0.0',
        true
    );
}
add_action('customize_preview_init', 'tznew_customize_preview_js');

/**
 * Enqueue customizer control scripts
 */
function tznew_customize_controls_js() {
    // Enqueue jQuery UI for sortable functionality
    wp_enqueue_script('jquery-ui-sortable');
    
    // Enqueue FontAwesome for icon picker
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');
    
    // Register an empty script handle that we'll add our inline code to
    wp_register_script('tznew-customizer-controls-base', '', ['customize-controls', 'jquery-ui-sortable', 'jquery'], TZNEW_VERSION, true);
    wp_enqueue_script('tznew-customizer-controls-base');
    
    // Add our custom controls JavaScript as inline script
    $custom_js = "(function($) {\n"
    . "    'use strict';\n\n"
    . "    // Initialize when DOM is ready\n"
    . "    $(document).ready(function() {\n"
    . "        initializeCustomControls();\n"
    . "    });\n\n"
    . "    // Initialize custom controls\n"
    . "    function initializeCustomControls() {\n"
    . "        // Initialize section order drag-and-drop\n"
    . "        initSectionOrderControl();\n\n"
    . "        // Initialize icon picker\n"
    . "        initIconPicker();\n\n"
    . "        // Initialize taxonomy grid toggle\n"
    . "        initTaxonomyGridToggle();\n\n"
    . "        console.log('TZnew Customizer Controls Initialized');\n"
    . "    }\n\n"
    . "    /**\n     * Initialize section order drag-and-drop control\n     */\n"
    . "    function initSectionOrderControl() {\n"
    . "        var sectionOrderControl = $('#customize-control-tznew_section_order');\n\n"
    . "        if (sectionOrderControl.length) {\n"
    . "            var orderInput = sectionOrderControl.find('input');\n"
    . "            var orderValue = JSON.parse(orderInput.val());\n"
    . "            var orderList = $('<ul class=\"section-order-list\"></ul>');\n\n"
    . "            // Create UI for drag-and-drop\n"
    . "            sectionOrderControl.append(orderList);\n\n"
    . "            // Add style for the drag-and-drop UI\n"
    . "            $('<style>\\n' +\n"
    . "              '.section-order-list { margin: 10px 0; padding: 0; }\\n' +\n"
    . "              '.section-order-list li { background: #fff; border: 1px solid #ddd; padding: 10px; margin-bottom: 5px; cursor: move; list-style: none; }\\n' +\n"
    . "              '.section-order-list li:hover { background: #f9f9f9; }\\n' +\n"
    . "              '.section-order-list li.ui-sortable-helper { box-shadow: 0 2px 5px rgba(0,0,0,0.2); }\\n' +\n"
    . "              '</style>').appendTo('head');\n\n"
    . "            // Add items to the list\n"
    . "            $.each(orderValue, function(index, sectionId) {\n"
    . "                var sectionName = getSectionName(sectionId);\n"
    . "                orderList.append('<li data-section-id=\"' + sectionId + '\">' + sectionName + '</li>');\n"
    . "            });\n\n"
    . "            // Make the list sortable (requires jQuery UI)\n"
    . "            if ($.fn.sortable) {\n"
    . "                orderList.sortable({\n"
    . "                    update: function() {\n"
    . "                        var newOrder = [];\n"
    . "                        orderList.find('li').each(function() {\n"
    . "                            newOrder.push($(this).data('section-id'));\n"
    . "                        });\n"
    . "                        orderInput.val(JSON.stringify(newOrder)).trigger('change');\n"
    . "                    }\n"
    . "                });\n"
    . "            } else {\n"
    . "                console.warn('jQuery UI Sortable is required for section ordering');\n"
    . "                orderList.append('<li class=\"error\">jQuery UI Sortable is required for drag-and-drop functionality</li>');\n"
    . "            }\n"
    . "        }\n"
    . "    }\n\n"
    . "    /**\n     * Get human-readable section name from section ID\n     */\n"
    . "    function getSectionName(sectionId) {\n"
    . "        var sectionNames = {\n"
    . "            'hero': 'Hero Section',\n"
    . "            'featured_treks': 'Featured Treks',\n"
    . "            'regions': 'Trekking Regions',\n"
    . "            'trek_blocks': 'Interesting Trek Blocks',\n"
    . "            'why_choose': 'Why Choose Nepal',\n"
    . "            'statistics': 'Statistics',\n"
    . "            'popular_tours': 'Popular Tours',\n"
    . "            'popular_trips': 'Popular Trips',\n"
    . "            'destinations': 'Destinations',\n"
    . "            'blog': 'Blog',\n"
    . "            'testimonials': 'Testimonials',\n"
    . "            'cta': 'Call to Action',\n"
    . "            'footer': 'Footer'\n"
    . "        };\n\n"
    . "        return sectionNames[sectionId] || sectionId.replace('_', ' ');\n"
    . "    }\n\n"
    . "    /**\n     * Initialize icon picker for hero section\n     */\n"
    . "    function initIconPicker() {\n"
    . "        var iconControl = $('#customize-control-tznew_hero_icon');\n\n"
    . "        if (iconControl.length) {\n"
    . "            var iconInput = iconControl.find('input');\n"
    . "            var currentIcon = iconInput.val();\n"
    . "            var previewArea = $('<div class=\"icon-preview\"></div>');\n"
    . "            var iconSelector = $('<div class=\"icon-selector\"></div>');\n\n"
    . "            // Add style for the icon picker\n"
    . "            $('<style>\\n' +\n"
    . "              '.icon-preview { margin: 10px 0; font-size: 24px; }\\n' +\n"
    . "              '.icon-selector { margin: 10px 0; display: grid; grid-template-columns: repeat(5, 1fr); gap: 5px; }\\n' +\n"
    . "              '.icon-option { padding: 8px; text-align: center; cursor: pointer; border: 1px solid #ddd; }\\n' +\n"
    . "              '.icon-option:hover { background: #f9f9f9; }\\n' +\n"
    . "              '.icon-option.selected { background: #0073aa; color: #fff; }\\n' +\n"
    . "              '</style>').appendTo('head');\n\n"
    . "            // Add preview area\n"
    . "            iconControl.append(previewArea);\n"
    . "            updateIconPreview(currentIcon, previewArea);\n\n"
    . "            // Add icon selector\n"
    . "            iconControl.append(iconSelector);\n\n"
    . "            // Popular FontAwesome icons for trekking/travel\n"
    . "            var popularIcons = [\n"
    . "                'fa-mountain', 'fa-hiking', 'fa-campground', 'fa-map-marker-alt', 'fa-compass',\n"
    . "                'fa-route', 'fa-map', 'fa-binoculars', 'fa-tree', 'fa-sun',\n"
    . "                'fa-snowflake', 'fa-cloud', 'fa-water', 'fa-fire', 'fa-camera'\n"
    . "            ];\n\n"
    . "            // Add icon options\n"
    . "            $.each(popularIcons, function(index, icon) {\n"
    . "                var isSelected = (icon === currentIcon) ? ' selected' : '';\n"
    . "                var iconOption = $('<div class=\"icon-option' + isSelected + '\" data-icon=\"' + icon + '\">' +\n"
    . "                                  '<i class=\"fas ' + icon + '\"></i></div>');\n"
    . "                iconSelector.append(iconOption);\n\n"
    . "                // Handle icon selection\n"
    . "                iconOption.on('click', function() {\n"
    . "                    var selectedIcon = $(this).data('icon');\n"
    . "                    iconInput.val(selectedIcon).trigger('change');\n"
    . "                    updateIconPreview(selectedIcon, previewArea);\n"
    . "                    iconSelector.find('.icon-option').removeClass('selected');\n"
    . "                    $(this).addClass('selected');\n"
    . "                });\n"
    . "            });\n"
    . "        }\n"
    . "    }\n\n"
    . "    /**\n     * Update icon preview\n     */\n"
    . "    function updateIconPreview(icon, previewArea) {\n"
    . "        if (icon && icon.startsWith('<svg')) {\n"
    . "            // Handle SVG code\n"
    . "            previewArea.html(icon);\n"
    . "        } else if (icon) {\n"
    . "            // Handle FontAwesome class\n"
    . "            previewArea.html('<i class=\"fas ' + icon + '\"></i>');\n"
    . "        } else {\n"
    . "            previewArea.html('');\n"
    . "        }\n"
    . "    }\n\n"
    . "    /**\n     * Initialize taxonomy grid toggle\n     */\n"
    . "    function initTaxonomyGridToggle() {\n"
    . "        var gridToggleControl = $('#customize-control-tznew_destinations_grid');\n\n"
    . "        if (gridToggleControl.length) {\n"
    . "            var toggleInput = gridToggleControl.find('input');\n"
    . "            var previewArea = $('<div class=\"grid-toggle-preview\"></div>');\n\n"
    . "            // Add style for the grid toggle preview\n"
    . "            $('<style>\\n' +\n"
    . "              '.grid-toggle-preview { margin: 10px 0; }\\n' +\n"
    . "              '.grid-preview-grid, .grid-preview-list { padding: 10px; border: 1px solid #ddd; margin-bottom: 5px; }\\n' +\n"
    . "              '.grid-preview-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 5px; }\\n' +\n"
    . "              '.grid-preview-list { display: flex; flex-direction: column; gap: 5px; }\\n' +\n"
    . "              '.grid-preview-item { background: #f1f1f1; padding: 5px; text-align: center; font-size: 10px; }\\n' +\n"
    . "              '.grid-preview-active { border: 2px solid #0073aa; }\\n' +\n"
    . "              '</style>').appendTo('head');\n\n"
    . "            // Create grid preview\n"
    . "            var gridPreview = $('<div class=\"grid-preview-grid' + (toggleInput.prop('checked') ? ' grid-preview-active' : '') + '\">Grid View</div>');\n"
    . "            for (var i = 0; i < 4; i++) {\n"
    . "                gridPreview.append('<div class=\"grid-preview-item\">Item ' + (i + 1) + '</div>');\n"
    . "            }\n\n"
    . "            // Create list preview\n"
    . "            var listPreview = $('<div class=\"grid-preview-list' + (!toggleInput.prop('checked') ? ' grid-preview-active' : '') + '\">List View</div>');\n"
    . "            for (var j = 0; j < 4; j++) {\n"
    . "                listPreview.append('<div class=\"grid-preview-item\">Item ' + (j + 1) + '</div>');\n"
    . "            }\n\n"
    . "            // Add previews to control\n"
    . "            previewArea.append(gridPreview).append(listPreview);\n"
    . "            gridToggleControl.append(previewArea);\n\n"
    . "            // Update preview when toggle changes\n"
    . "            toggleInput.on('change', function() {\n"
    . "                if ($(this).prop('checked')) {\n"
    . "                    gridPreview.addClass('grid-preview-active');\n"
    . "                    listPreview.removeClass('grid-preview-active');\n"
    . "                } else {\n"
    . "                    listPreview.addClass('grid-preview-active');\n"
    . "                    gridPreview.removeClass('grid-preview-active');\n"
    . "                }\n"
    . "            });\n"
    . "        }\n"
    . "    }\n\n"
    . "})(jQuery);"
    ;
    
    wp_add_inline_script('tznew-customizer-controls-base', $custom_js);
}
add_action('customize_controls_enqueue_scripts', 'tznew_customize_controls_js');

/**
 * Output custom CSS based on customizer settings
 */
function tznew_customizer_css() {
    // Color settings
    $primary_color = get_theme_mod('tznew_primary_color', '#3b82f6');
    $secondary_color = get_theme_mod('tznew_secondary_color', '#10b981');
    $accent_color = get_theme_mod('tznew_accent_color', '#f59e0b');
    
    // Gradient background settings
    $header_gradient = get_theme_mod('tznew_header_gradient_bg', '');
    $footer_gradient = get_theme_mod('tznew_footer_gradient_bg', '');
    $sidebar_gradient = get_theme_mod('tznew_sidebar_gradient_bg', '');
    $content_gradient = get_theme_mod('tznew_content_gradient_bg', '');
    $hero_gradient = get_theme_mod('tznew_hero_gradient_bg', '');

    $css = "
    <style type='text/css'>
    :root {
        --primary-color: {$primary_color};
        --secondary-color: {$secondary_color};
        --accent-color: {$accent_color};
    }
    
    .btn-primary, .button-primary {
        background-color: {$primary_color};
        border-color: {$primary_color};
    }
    
    .btn-primary:hover, .button-primary:hover {
        background-color: {$primary_color}dd;
        border-color: {$primary_color}dd;
    }
    
    .btn-secondary, .button-secondary {
        background-color: {$secondary_color};
        border-color: {$secondary_color};
    }
    
    .btn-secondary:hover, .button-secondary:hover {
        background-color: {$secondary_color}dd;
        border-color: {$secondary_color}dd;
    }
    
    .text-primary {
        color: {$primary_color} !important;
    }
    
    .text-secondary {
        color: {$secondary_color} !important;
    }
    
    .text-accent {
        color: {$accent_color} !important;
    }
    
    .bg-primary {
        background-color: {$primary_color} !important;
    }
    
    .bg-secondary {
        background-color: {$secondary_color} !important;
    }
    
    .bg-accent {
        background-color: {$accent_color} !important;
    }
    
    a {
        color: {$primary_color};
    }
    
    a:hover {
        color: {$primary_color}dd;
    }
    
    .site-header .main-navigation a:hover {
        color: {$primary_color};
    }
    
    .post-meta a {
        color: {$secondary_color};
    }
    
    .booking-cta .btn {
        background-color: {$accent_color};
        border-color: {$accent_color};
    }
    
    .booking-cta .btn:hover {
        background-color: {$accent_color}dd;
        border-color: {$accent_color}dd;
    }
    
    /* Gradient Background Styles */
    ";
    
    // Add header gradient if set
    if (!empty($header_gradient)) {
        $css .= "
    .site-header {
        background: {$header_gradient};
    }
    ";
    }
    
    // Add footer gradient if set
    if (!empty($footer_gradient)) {
        $css .= "
    .site-footer {
        background: {$footer_gradient};
    }
    ";
    }
    
    // Add sidebar gradient if set
    if (!empty($sidebar_gradient)) {
        $css .= "
    .sidebar {
        background: {$sidebar_gradient};
    }
    ";
    }
    
    // Add content section gradient if set
    if (!empty($content_gradient)) {
        $css .= "
    .site-content {
        background: {$content_gradient};
    }
    ";
    }
    
    // Add hero/banner section gradient if set
    if (!empty($hero_gradient)) {
        $css .= "
    .hero-section, .banner-section, .page-banner {
        background: {$hero_gradient};
    }
    ";
    }
    
    $css .= "</style>
    ";

    echo $css;
}
add_action('wp_head', 'tznew_customizer_css');

/**
 * Helper function to get theme mod with fallback
 */
function tznew_get_theme_mod($setting, $default = '') {
    return get_theme_mod($setting, $default);
}

/**
 * Get social media links
 */
function tznew_get_social_links() {
    $social_networks = [
        'facebook'    => ['icon' => 'fab fa-facebook-f', 'label' => __('Facebook', 'tznew')],
        'twitter'     => ['icon' => 'fab fa-twitter', 'label' => __('Twitter', 'tznew')],
        'instagram'   => ['icon' => 'fab fa-instagram', 'label' => __('Instagram', 'tznew')],
        'linkedin'    => ['icon' => 'fab fa-linkedin-in', 'label' => __('LinkedIn', 'tznew')],
        'youtube'     => ['icon' => 'fab fa-youtube', 'label' => __('YouTube', 'tznew')],
        'tripadvisor' => ['icon' => 'fab fa-tripadvisor', 'label' => __('TripAdvisor', 'tznew')],
    ];

    $links = [];
    foreach ($social_networks as $network => $data) {
        $url = get_theme_mod("tznew_social_{$network}");
        if (!empty($url)) {
            $links[$network] = [
                'url'   => esc_url($url),
                'icon'  => $data['icon'],
                'label' => $data['label'],
            ];
        }
    }

    return $links;
}

/**
 * Display social media links
 */
function tznew_social_links($class = '') {
    $links = tznew_get_social_links();
    
    if (empty($links)) {
        return;
    }

    echo '<div class="social-links ' . esc_attr($class) . '">';
    foreach ($links as $network => $data) {
        echo '<a href="' . $data['url'] . '" target="_blank" rel="noopener noreferrer" class="social-link social-' . esc_attr($network) . '" aria-label="' . esc_attr($data['label']) . '">';
        echo '<i class="' . esc_attr($data['icon']) . '"></i>';
        echo '</a>';
    }
    echo '</div>';
}

/**
 * Sanitize gradient CSS input
 *
 * Validates that the input contains valid CSS gradient syntax
 * and removes any potentially harmful content
 *
 * @param string $input The gradient CSS to sanitize
 * @return string Sanitized gradient CSS
 */
function tznew_sanitize_gradient_css($input) {
    // First apply basic sanitization
    $input = sanitize_text_field($input);
    
    // Only allow specific gradient functions
    if (!empty($input)) {
        // Check if input starts with a valid gradient function
        $valid_starts = ['linear-gradient', 'radial-gradient', 'conic-gradient', 'repeating-linear-gradient', 'repeating-radial-gradient'];
        $is_valid = false;
        
        foreach ($valid_starts as $valid_start) {
            if (strpos($input, $valid_start . '(') === 0) {
                $is_valid = true;
                break;
            }
        }
        
        // If not a valid gradient function, return empty string
        if (!$is_valid) {
            return '';
        }
        
        // Check for balanced parentheses
        $open_count = substr_count($input, '(');
        $close_count = substr_count($input, ')');
        
        if ($open_count !== $close_count) {
            return '';
        }
        
        // Check for potentially harmful content
        $disallowed = ['javascript:', 'data:', 'vbscript:', 'expression', 'behavior', '-moz-binding'];
        foreach ($disallowed as $term) {
            if (stripos($input, $term) !== false) {
                return '';
            }
        }
    }
    
    return $input;
}