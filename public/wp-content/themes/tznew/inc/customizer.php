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

    // Footer Section
    $wp_customize->add_section('tznew_footer_section', [
        'title'    => __('Footer Settings', 'tznew'),
        'panel'    => 'tznew_theme_options',
        'priority' => 40,
    ]);

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
    wp_enqueue_script('tznew-customizer-controls', TZNEW_THEME_URI . '/assets/js/customizer-controls.js', ['customize-controls'], TZNEW_VERSION, true);
}
add_action('customize_controls_enqueue_scripts', 'tznew_customize_controls_js');

/**
 * Output custom CSS based on customizer settings
 */
function tznew_customizer_css() {
    $primary_color = get_theme_mod('tznew_primary_color', '#3b82f6');
    $secondary_color = get_theme_mod('tznew_secondary_color', '#10b981');
    $accent_color = get_theme_mod('tznew_accent_color', '#f59e0b');

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
    </style>
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