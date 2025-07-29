<?php
/**
 * Updated Testimonial Usage Examples
 * 
 * This file demonstrates how to use the enhanced testimonial system
 * with package assignments and homepage featuring.
 *
 * @package TZnew
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SHORTCODE EXAMPLES FOR ENHANCED TESTIMONIAL SYSTEM
 * 
 * The testimonial system now supports:
 * - Assigning testimonials to specific trekking packages
 * - Assigning testimonials to specific tour packages  
 * - Featuring testimonials on the homepage
 * - All previous filtering options (featured, rating, source, etc.)
 */

// Example 1: Display testimonials for a specific trekking package
// [testimonials trekking_id="123" limit="3" layout="grid"]

// Example 2: Display testimonials for a specific tour package
// [testimonials tour_id="456" limit="4" layout="slider"]

// Example 3: Display homepage featured testimonials
// [testimonials homepage_featured="true" limit="5" layout="slider" autoplay="true"]

// Example 4: Display testimonials assigned to multiple criteria
// [testimonials trekking_id="123" featured="true" rating="4" limit="6"]

// Example 5: Display testimonials for tours with high ratings
// [testimonials tour_id="456" rating="5" show_rating="true" show_source="true"]

/**
 * PROGRAMMATIC USAGE EXAMPLES
 */

/**
 * Get testimonials for a specific trekking package
 */
function get_trekking_testimonials($trekking_id, $limit = 3) {
    return do_shortcode('[testimonials trekking_id="' . intval($trekking_id) . '" limit="' . intval($limit) . '" layout="grid"]');
}

/**
 * Get testimonials for a specific tour package
 */
function get_tour_testimonials($tour_id, $limit = 3) {
    return do_shortcode('[testimonials tour_id="' . intval($tour_id) . '" limit="' . intval($limit) . '" layout="grid"]');
}

/**
 * Get homepage featured testimonials
 */
function get_homepage_testimonials($limit = 5) {
    return do_shortcode('[testimonials homepage_featured="true" limit="' . intval($limit) . '" layout="slider" autoplay="true"]');
}

/**
 * Display testimonials in single trekking page template
 */
function display_trekking_testimonials() {
    global $post;
    if (get_post_type() === 'trekking') {
        echo '<div class="trekking-testimonials">';
        echo '<h3>' . __('What Our Guests Say', 'tznew') . '</h3>';
        echo get_trekking_testimonials($post->ID, 4);
        echo '</div>';
    }
}

/**
 * Display testimonials in single tour page template
 */
function display_tour_testimonials() {
    global $post;
    if (get_post_type() === 'tours') {
        echo '<div class="tour-testimonials">';
        echo '<h3>' . __('Customer Reviews', 'tznew') . '</h3>';
        echo get_tour_testimonials($post->ID, 4);
        echo '</div>';
    }
}

/**
 * Display homepage testimonials section
 */
function display_homepage_testimonials_section() {
    if (is_front_page()) {
        echo '<section class="homepage-testimonials">';
        echo '<div class="container">';
        echo '<h2>' . __('What Our Travelers Say', 'tznew') . '</h2>';
        echo get_homepage_testimonials(6);
        echo '</div>';
        echo '</section>';
    }
}

/**
 * TEMPLATE INTEGRATION EXAMPLES
 */

/**
 * Add testimonials to single trekking template
 * Add this to your single-trekking.php template
 */
/*
// In single-trekking.php
if (function_exists('display_trekking_testimonials')) {
    display_trekking_testimonials();
}
*/

/**
 * Add testimonials to single tour template  
 * Add this to your single-tours.php template
 */
/*
// In single-tours.php
if (function_exists('display_tour_testimonials')) {
    display_tour_testimonials();
}
*/

/**
 * Add testimonials to homepage
 * Add this to your front-page.php or index.php template
 */
/*
// In front-page.php or index.php
if (function_exists('display_homepage_testimonials_section')) {
    display_homepage_testimonials_section();
}
*/

/**
 * WIDGET AREA EXAMPLES
 */

/**
 * Register testimonial widget areas
 */
function register_testimonial_widget_areas() {
    // Trekking sidebar testimonials
    register_sidebar(array(
        'name' => __('Trekking Testimonials', 'tznew'),
        'id' => 'trekking-testimonials',
        'description' => __('Testimonials widget area for trekking pages', 'tznew'),
        'before_widget' => '<div class="widget testimonial-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    // Tour sidebar testimonials
    register_sidebar(array(
        'name' => __('Tour Testimonials', 'tznew'),
        'id' => 'tour-testimonials', 
        'description' => __('Testimonials widget area for tour pages', 'tznew'),
        'before_widget' => '<div class="widget testimonial-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'register_testimonial_widget_areas');

/**
 * ELEMENTOR INTEGRATION EXAMPLES
 */

/**
 * Custom Elementor widget for package testimonials
 * This would be added to your Elementor widgets
 */
/*
class Package_Testimonials_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'package-testimonials';
    }
    
    public function get_title() {
        return __('Package Testimonials', 'tznew');
    }
    
    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'tznew'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'package_type',
            [
                'label' => __('Package Type', 'tznew'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'trekking' => __('Trekking', 'tznew'),
                    'tour' => __('Tour', 'tznew'),
                ],
                'default' => 'trekking',
            ]
        );
        
        $this->add_control(
            'package_id',
            [
                'label' => __('Package ID', 'tznew'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '',
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $package_type = $settings['package_type'];
        $package_id = $settings['package_id'];
        
        if ($package_type === 'trekking') {
            echo get_trekking_testimonials($package_id);
        } else {
            echo get_tour_testimonials($package_id);
        }
    }
}
*/

/**
 * ADVANCED QUERY EXAMPLES
 */

/**
 * Get testimonials with custom WP_Query
 */
function get_custom_testimonials($args = array()) {
    $defaults = array(
        'post_type' => 'testimonial',
        'post_status' => 'publish',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $args = wp_parse_args($args, $defaults);
    $query = new WP_Query($args);
    
    return $query->posts;
}

/**
 * Get testimonials for specific package with meta query
 */
function get_package_testimonials_advanced($package_id, $package_type = 'trekking') {
    $meta_key = ($package_type === 'trekking') ? '_assigned_trekking' : '_assigned_tours';
    
    $args = array(
        'post_type' => 'testimonial',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => $meta_key,
                'value' => serialize(strval($package_id)),
                'compare' => 'LIKE'
            )
        )
    );
    
    return get_custom_testimonials($args);
}

/**
 * Get homepage featured testimonials with high ratings
 */
function get_homepage_featured_high_rated() {
    $args = array(
        'post_type' => 'testimonial',
        'post_status' => 'publish', 
        'posts_per_page' => 5,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_homepage_featured',
                'value' => '1',
                'compare' => '='
            ),
            array(
                'key' => '_rating',
                'value' => 4,
                'compare' => '>=',
                'type' => 'NUMERIC'
            )
        )
    );
    
    return get_custom_testimonials($args);
}