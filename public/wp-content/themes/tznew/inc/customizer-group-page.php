<?php
/**
 * Group/Archive Page Customizer Settings
 *
 * @package TZnew
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Group Page customizer settings
 */
function tznew_customize_group_page_register($wp_customize) {
    
    // GROUP PAGE PANEL
    $wp_customize->add_panel('tznew_group_page_panel', array(
        'title'    => __('Group/Archive Pages', 'tznew'),
        'priority' => 161,
    ));
    
    // ARCHIVE HEADER SECTION
    $wp_customize->add_section('tznew_archive_header_section', array(
        'title'    => __('Archive Header', 'tznew'),
        'panel'    => 'tznew_group_page_panel',
        'priority' => 10,
    ));
    
    // Show Archive Header
    $wp_customize->add_setting('tznew_archive_header_show', array(
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('tznew_archive_header_show', array(
        'label'   => __('Show Archive Header', 'tznew'),
        'section' => 'tznew_archive_header_section',
        'type'    => 'checkbox',
    ));
    
    // Archive Header Background Image
    $wp_customize->add_setting('tznew_archive_header_bg', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'tznew_archive_header_bg', array(
        'label'    => __('Header Background Image', 'tznew'),
        'section'  => 'tznew_archive_header_section',
        'settings' => 'tznew_archive_header_bg',
    )));
    
    // Archive Header Overlay Opacity
    $wp_customize->add_setting('tznew_archive_header_overlay', array(
        'default'           => 0.4,
        'sanitize_callback' => 'tznew_sanitize_float',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('tznew_archive_header_overlay', array(
        'label'       => __('Header Overlay Opacity', 'tznew'),
        'section'     => 'tznew_archive_header_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ),
    ));
    
    // TREKKING ARCHIVE SECTION
    $wp_customize->add_section('tznew_trekking_archive_section', array(
        'title'    => __('Trekking Archive', 'tznew'),
        'panel'    => 'tznew_group_page_panel',
        'priority' => 20,
    ));
    
    // Trekking Archive Title
    $wp_customize->add_setting('tznew_trekking_archive_title', array(
        'default'           => 'Trekking Adventures',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('tznew_trekking_archive_title', array(
        'label'   => __('Archive Title', 'tznew'),
        'section' => 'tznew_trekking_archive_section',
        'type'    => 'text',
    ));
    
    // Trekking Archive Subtitle
    $wp_customize->add_setting('tznew_trekking_archive_subtitle', array(
        'default'           => 'Discover amazing trekking adventures in the Himalayas',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('tznew_trekking_archive_subtitle', array(
        'label'   => __('Archive Subtitle', 'tznew'),
        'section' => 'tznew_trekking_archive_section',
        'type'    => 'textarea',
    ));
    
    // Posts Per Page
    $wp_customize->add_setting('tznew_trekking_posts_per_page', array(
        'default'           => 12,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('tznew_trekking_posts_per_page', array(
        'label'   => __('Posts Per Page', 'tznew'),
        'section' => 'tznew_trekking_archive_section',
        'type'    => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 50,
        ),
    ));
    
    // TOURS ARCHIVE SECTION
    $wp_customize->add_section('tznew_tours_archive_section', array(
        'title'    => __('Tours Archive', 'tznew'),
        'panel'    => 'tznew_group_page_panel',
        'priority' => 30,
    ));
    
    // Tours Archive Title
    $wp_customize->add_setting('tznew_tours_archive_title', array(
        'default'           => 'Tour Packages',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('tznew_tours_archive_title', array(
        'label'   => __('Archive Title', 'tznew'),
        'section' => 'tznew_tours_archive_section',
        'type'    => 'text',
    ));
    
    // Tours Archive Subtitle
    $wp_customize->add_setting('tznew_tours_archive_subtitle', array(
        'default'           => 'Explore cultural and adventure tours across Nepal',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('tznew_tours_archive_subtitle', array(
        'label'   => __('Archive Subtitle', 'tznew'),
        'section' => 'tznew_tours_archive_section',
        'type'    => 'textarea',
    ));
    
    // Tours Posts Per Page
    $wp_customize->add_setting('tznew_tours_posts_per_page', array(
        'default'           => 12,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('tznew_tours_posts_per_page', array(
        'label'   => __('Posts Per Page', 'tznew'),
        'section' => 'tznew_tours_archive_section',
        'type'    => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 50,
        ),
    ));
    
    // FILTER SECTION
    $wp_customize->add_section('tznew_archive_filter_section', array(
        'title'    => __('Archive Filters', 'tznew'),
        'panel'    => 'tznew_group_page_panel',
        'priority' => 40,
    ));
    
    // Show Filters
    $wp_customize->add_setting('tznew_archive_filters_show', array(
        'default'           => true,
        'sanitize_callback' => 'tznew_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('tznew_archive_filters_show', array(
        'label'   => __('Show Archive Filters', 'tznew'),
        'section' => 'tznew_archive_filter_section',
        'type'    => 'checkbox',
    ));
    
    // Filter Background Color
    $wp_customize->add_setting('tznew_filter_bg_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tznew_filter_bg_color', array(
        'label'    => __('Filter Background Color', 'tznew'),
        'section'  => 'tznew_archive_filter_section',
        'settings' => 'tznew_filter_bg_color',
    )));
    
    // LAYOUT SECTION
    $wp_customize->add_section('tznew_archive_layout_section', array(
        'title'    => __('Archive Layout', 'tznew'),
        'panel'    => 'tznew_group_page_panel',
        'priority' => 50,
    ));
    
    // Default View
    $wp_customize->add_setting('tznew_archive_default_view', array(
        'default'           => 'grid',
        'sanitize_callback' => 'tznew_sanitize_select',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('tznew_archive_default_view', array(
        'label'   => __('Default View', 'tznew'),
        'section' => 'tznew_archive_layout_section',
        'type'    => 'select',
        'choices' => array(
            'grid' => __('Grid View', 'tznew'),
            'list' => __('List View', 'tznew'),
        ),
    ));
    
    // Grid Columns
    $wp_customize->add_setting('tznew_archive_grid_columns', array(
        'default'           => 3,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('tznew_archive_grid_columns', array(
        'label'   => __('Grid Columns (Desktop)', 'tznew'),
        'section' => 'tznew_archive_layout_section',
        'type'    => 'select',
        'choices' => array(
            '2' => __('2 Columns', 'tznew'),
            '3' => __('3 Columns', 'tznew'),
            '4' => __('4 Columns', 'tznew'),
        ),
    ));
    
    // Card Style
    $wp_customize->add_setting('tznew_archive_card_style', array(
        'default'           => 'modern',
        'sanitize_callback' => 'tznew_sanitize_select',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('tznew_archive_card_style', array(
        'label'   => __('Card Style', 'tznew'),
        'section' => 'tznew_archive_layout_section',
        'type'    => 'select',
        'choices' => array(
            'modern'  => __('Modern', 'tznew'),
            'classic' => __('Classic', 'tznew'),
            'minimal' => __('Minimal', 'tznew'),
        ),
    ));
}
add_action('customize_register', 'tznew_customize_group_page_register');

/**
 * Sanitization functions
 */
function tznew_sanitize_float($input) {
    return floatval($input);
}

/**
 * Generate dynamic CSS for group pages
 */
function tznew_group_page_dynamic_css() {
    $css = '';
    
    // Archive header styles
    $header_bg = get_theme_mod('tznew_archive_header_bg');
    $header_overlay = get_theme_mod('tznew_archive_header_overlay', 0.4);
    
    if ($header_bg) {
        $css .= '.archive-header { background-image: url(' . esc_url($header_bg) . '); }';
    }
    
    $css .= '.archive-header .overlay { background-color: rgba(0, 0, 0, ' . $header_overlay . '); }';
    
    // Filter styles
    $filter_bg = get_theme_mod('tznew_filter_bg_color', '#ffffff');
    $css .= '.archive-filters { background-color: ' . sanitize_hex_color($filter_bg) . '; }';
    
    // Grid columns
    $grid_columns = get_theme_mod('tznew_archive_grid_columns', 3);
    $css .= '@media (min-width: 1024px) { .archive-grid { grid-template-columns: repeat(' . $grid_columns . ', 1fr); } }';
    
    return $css;
}

/**
 * Enqueue dynamic CSS
 */
function tznew_group_page_enqueue_dynamic_css() {
    $css = tznew_group_page_dynamic_css();
    if (!empty($css)) {
        wp_add_inline_style('tznew-style', $css);
    }
}
add_action('wp_enqueue_scripts', 'tznew_group_page_enqueue_dynamic_css');

/**
 * Modify archive query based on customizer settings
 */
function tznew_modify_archive_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_post_type_archive('trekking')) {
            $posts_per_page = get_theme_mod('tznew_trekking_posts_per_page', 12);
            $query->set('posts_per_page', $posts_per_page);
        } elseif (is_post_type_archive('tours')) {
            $posts_per_page = get_theme_mod('tznew_tours_posts_per_page', 12);
            $query->set('posts_per_page', $posts_per_page);
        }
    }
}
add_action('pre_get_posts', 'tznew_modify_archive_query');