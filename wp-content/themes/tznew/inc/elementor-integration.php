<?php
/**
 * Elementor Integration for TZnew Theme
 * 
 * This file integrates Elementor with custom fields for tours and trekking post types,
 * enabling flexible template design and layout customization.
 *
 * @package TZnew
 */

if (!defined('ABSPATH')) {
    exit;
}

class TZnew_Elementor_Integration {
    
    public function __construct() {
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'register_categories']);
    }
    
    /**
     * Register custom Elementor categories
     */
    public function register_categories($elements_manager) {
        $elements_manager->add_category(
            'tznew-tours-trekking',
            [
                'title' => __('Tours & Trekking', 'tznew'),
                'icon' => 'fa fa-mountain',
            ]
        );
    }
    
    /**
     * Register custom Elementor widgets
     */
    public function register_widgets() {
        // Include widget files
        $widget_files = [
            'tour-overview-widget.php',
            'trek-overview-widget.php',
            'itinerary-widget.php',
            'cost-info-widget.php',
            'permits-widget.php',
            'includes-excludes-widget.php',
            'faq-widget.php',
            'gallery-widget.php',
            'meta-info-widget.php',
        ];
        
        foreach ($widget_files as $file) {
            $file_path = get_template_directory() . '/inc/elementor-widgets/' . $file;
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
        
        // Register widgets
        if (class_exists('TZnew_Tour_Overview_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new TZnew_Tour_Overview_Widget());
        }
        
        if (class_exists('TZnew_Trek_Overview_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new TZnew_Trek_Overview_Widget());
        }
        
        if (class_exists('TZnew_Itinerary_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new TZnew_Itinerary_Widget());
        }
        
        if (class_exists('TZnew_Cost_Info_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new TZnew_Cost_Info_Widget());
        }
        
        if (class_exists('TZnew_Permits_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new TZnew_Permits_Widget());
        }
        
        if (class_exists('TZnew_Includes_Excludes_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new TZnew_Includes_Excludes_Widget());
        }
        
        if (class_exists('TZnew_FAQ_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new TZnew_FAQ_Widget());
        }
        
        if (class_exists('TZnew_Gallery_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new TZnew_Gallery_Widget());
        }
        
        if (class_exists('TZnew_Meta_Info_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new TZnew_Meta_Info_Widget());
        }
    }
}

// Initialize the integration
new TZnew_Elementor_Integration();

/**
 * Helper function to safely get ACF field values
 */
function tznew_get_elementor_field($field_name, $post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (function_exists('get_field')) {
        return get_field($field_name, $post_id);
    }
    
    return get_post_meta($post_id, $field_name, true);
}

/**
 * Get post type specific fields
 */
function tznew_get_post_type_fields($post_type) {
    $fields = array();
    
    if ($post_type === 'tours') {
        $fields = array(
            'name_of_the_tour' => 'Tour Name',
            'tour_number' => 'Tour Number',
            'overview' => 'Overview',
            'places_covered' => 'Places Covered',
            'type' => 'Tour Type',
            'best_season' => 'Best Season',
            'duration' => 'Duration',
            'group_size' => 'Group Size',
            'languages' => 'Languages',
            'permits_regulations' => 'Permits & Regulations',
            'cost_information' => 'Cost Information',
            'inclusion' => 'Inclusion',
            'exclusion' => 'Exclusion',
            'group_discount' => 'Group Discount',
            'itinerary_details' => 'Itinerary Details',
            'recommendation' => 'Recommendation',
            'selected_faqs' => 'Selected FAQs',
            'show_general_faqs' => 'Show General FAQs'
        );
    } elseif ($post_type === 'trekking') {
        $fields = array(
            'name_of_the_trek' => 'Trek Name',
            'trek_number' => 'Trek Number',
            'overview' => 'Overview',
            'region' => 'Region',
            'difficulty' => 'Difficulty',
            'best_season' => 'Best Season',
            'duration' => 'Duration',
            'max_altitude' => 'Max Altitude',
            'permits_regulations' => 'Permits & Regulations',
            'cost_information' => 'Cost Information',
            'inclusion' => 'Inclusion',
            'exclusion' => 'Exclusion',
            'hashtags' => 'Hashtags',
            'group_discount' => 'Group Discount',
            'itinerary_details' => 'Itinerary Details',
            'recommendation' => 'Recommendation',
            'includes' => 'Includes',
            'excludes' => 'Excludes',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'selected_faqs' => 'Selected FAQs',
            'show_general_faqs' => 'Show General FAQs'
        );
    }
    
    return $fields;
}

/**
 * Check if current post is tours or trekking
 */
function tznew_is_tours_or_trekking($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post_type = get_post_type($post_id);
    return in_array($post_type, array('tours', 'trekking'));
}