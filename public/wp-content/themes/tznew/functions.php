<?php
/**
 * TZnew functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package TZnew
 * @version 2.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('TZNEW_VERSION', '2.0.0');
define('TZNEW_THEME_DIR', get_template_directory());
define('TZNEW_THEME_URI', get_template_directory_uri());
define('TZNEW_INC_DIR', TZNEW_THEME_DIR . '/inc');

// Include floating contact buttons
require_once TZNEW_INC_DIR . '/floating-contact-buttons.php';

// Include booking system
require_once TZNEW_INC_DIR . '/booking-system.php';

// Include main customizer first (contains shared functions)
require_once TZNEW_INC_DIR . '/customizer.php';

// Include front page customizer
require_once TZNEW_INC_DIR . '/customizer-front-page.php';

// Include group page customizer
require_once TZNEW_INC_DIR . '/customizer-group-page.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function tznew_setup() {
    // Make theme available for translation
    load_theme_textdomain('tznew', TZNEW_THEME_DIR . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for wide alignment
    add_theme_support('align-wide');

    // Add support for custom line height
    add_theme_support('custom-line-height');

    // Add support for custom units
    add_theme_support('custom-units');

    // Add support for custom spacing
    add_theme_support('custom-spacing');

    // Add support for HTML5 markup
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    // Set up the WordPress core custom background feature
    add_theme_support('custom-background', [
        'default-color' => 'ffffff',
        'default-image' => '',
    ]);

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for core custom logo
    add_theme_support('custom-logo', [
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ]);

    // Register navigation menus
    register_nav_menus([
        'primary' => esc_html__('Primary Menu', 'tznew'),
        'footer'  => esc_html__('Footer Menu', 'tznew'),
    ]);

    // Add image sizes
    add_image_size('tznew-featured', 800, 600, true);
    add_image_size('tznew-thumbnail', 400, 300, true);
    add_image_size('tznew-gallery', 600, 400, true);
}
add_action('after_setup_theme', 'tznew_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function tznew_content_width() {
    $GLOBALS['content_width'] = apply_filters('tznew_content_width', 1200);
}
add_action('after_setup_theme', 'tznew_content_width', 0);

/**
 * Dequeue Elementor's Font Awesome to prevent conflicts
 */
function tznew_dequeue_elementor_fontawesome() {
    // Dequeue all possible Font Awesome handles from Elementor
    wp_dequeue_style('font-awesome');
    wp_dequeue_style('elementor-icons-fa-solid');
    wp_dequeue_style('elementor-icons-fa-regular');
    wp_dequeue_style('elementor-icons-fa-brands');
    wp_dequeue_style('elementor-icons');
    wp_dequeue_style('elementor-frontend');
    
    // Also deregister to prevent re-enqueuing
    wp_deregister_style('font-awesome');
    wp_deregister_style('elementor-icons-fa-solid');
    wp_deregister_style('elementor-icons-fa-regular');
    wp_deregister_style('elementor-icons-fa-brands');
}
add_action('wp_enqueue_scripts', 'tznew_dequeue_elementor_fontawesome', 20);
add_action('elementor/frontend/after_enqueue_styles', 'tznew_dequeue_elementor_fontawesome', 20);

/**
 * Add custom CSS to ensure Font Awesome 6 icons display properly
 */
function tznew_fontawesome_fix() {
    echo '<style>
    /* Force Font Awesome 6 styles */
    .fa, .fas, .far, .fal, .fad, .fab {
        font-family: "Font Awesome 6 Free" !important;
        font-weight: 900 !important;
        font-style: normal !important;
        font-variant: normal !important;
        text-rendering: auto !important;
        line-height: 1 !important;
        display: inline-block !important;
    }
    
    .far {
        font-weight: 400 !important;
    }
    
    .fab {
        font-family: "Font Awesome 6 Brands" !important;
        font-weight: 400 !important;
    }
    
    /* Ensure icons are visible */
    .fa:before, .fas:before, .far:before, .fal:before, .fad:before, .fab:before {
        display: inline-block !important;
        text-rendering: auto !important;
        -webkit-font-smoothing: antialiased !important;
    }
    </style>';
}
add_action('wp_head', 'tznew_fontawesome_fix', 999);

/**
 * Enqueue scripts and styles.
 */
function tznew_scripts() {
    // Enqueue styles
    wp_enqueue_style('tznew-style', get_stylesheet_uri(), [], TZNEW_VERSION);
    
    // Enqueue Tailwind CSS
    wp_enqueue_style('tailwindcss', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css', [], '2.2.19');
    
    // Enqueue Font Awesome 6 with high priority
    wp_enqueue_style('tznew-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', [], '6.4.0');
    
    // Enqueue custom styles
    wp_enqueue_style('tznew-custom', TZNEW_THEME_URI . '/assets/css/custom.css', ['tznew-style'], TZNEW_VERSION);
    
    // Enqueue customizable styles for trekking and tours
    wp_enqueue_style('tznew-customizable', TZNEW_THEME_URI . '/assets/css/customizable-styles.css', ['tznew-custom'], TZNEW_VERSION);

    // Enqueue scripts
    // Enqueue jQuery if not already loaded
    wp_enqueue_script('jquery');
    
    // Enqueue Chart.js for elevation graphs (only on tours and trekking pages)
    if (is_singular(['tours', 'trekking']) || is_post_type_archive(['tours', 'trekking'])) {
        wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js', [], '3.9.1', true);
        
        // Add Chart.js loading verification
        wp_add_inline_script('chartjs', '
            window.chartJsLoaded = false;
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof Chart !== "undefined") {
                    window.chartJsLoaded = true;
                    console.log("Chart.js loaded successfully");
                } else {
                    console.error("Chart.js failed to load");
                }
            });
        ');
    }
    
    // Enqueue Leaflet.js for interactive maps
    wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], '1.9.4');
    wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], '1.9.4', true);
    
    // Enqueue main scripts
    wp_enqueue_script('tznew-scripts', TZNEW_THEME_URI . '/assets/js/scripts.js', ['jquery'], TZNEW_VERSION, true);
    
    // Localize script for AJAX
    wp_localize_script('tznew-scripts', 'tznew_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('tznew_nonce'),
    ]);

    // AJAX handler for getting trek locations (legacy)
    add_action('wp_ajax_get_trek_locations', 'get_trek_locations_ajax');
    add_action('wp_ajax_nopriv_get_trek_locations', 'get_trek_locations_ajax');

    // AJAX handler for getting all tour and trek locations
    add_action('wp_ajax_get_all_locations', 'get_all_locations_ajax');
    add_action('wp_ajax_nopriv_get_all_locations', 'get_all_locations_ajax');

    // Register AJAX actions for PDF generation
    add_action('wp_ajax_generate_pdf_itinerary', 'generate_itinerary_pdf_ajax');
    add_action('wp_ajax_nopriv_generate_pdf_itinerary', 'generate_itinerary_pdf_ajax');

    /**
     * Generate and download itinerary PDF
     */
    function generate_itinerary_pdf_ajax() {
        // Add error logging
        error_log('PDF AJAX called with data: ' . print_r($_POST, true));
        
        // Verify nonce for security
        if (!wp_verify_nonce($_POST['nonce'], 'pdf_itinerary_nonce')) {
            error_log('PDF AJAX: Nonce verification failed');
            wp_send_json_error('Security check failed');
            return;
        }
        
        $post_id = intval($_POST['post_id']);
        $post_type = get_post_type($post_id);
        
        error_log('PDF AJAX: Post ID: ' . $post_id . ', Post type: ' . $post_type);
        
        // Verify post exists and is trekking or tours
        if (!$post_id || !in_array($post_type, ['trekking', 'tours'])) {
            error_log('PDF AJAX: Invalid post or post type');
            wp_send_json_error('Invalid post');
            return;
        }
        
        // Use HTML-based PDF generation for better compatibility
        generate_simple_pdf($post_id, $post_type);
    }



    /**
     * Generate PDF using HTML (compatible with all environments)
     */
    function generate_simple_pdf($post_id, $post_type) {
        $post = get_post($post_id);
        $title = get_the_title($post_id);
        
        // Generate HTML content
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?php echo esc_html($title); ?> - Itinerary</title>
            <style>
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 20px; 
                    line-height: 1.6;
                    color: #333;
                }
                h1 { 
                    color: #2563eb; 
                    text-align: center; 
                    border-bottom: 3px solid #3b82f6;
                    padding-bottom: 10px;
                }
                h2 { 
                    color: #1e40af; 
                    border-bottom: 2px solid #3b82f6; 
                    padding-bottom: 5px; 
                    margin-top: 30px;
                }
                h3 { 
                    color: #1e3a8a; 
                    margin-top: 20px;
                }
                .meta-info { 
                    background: #f3f4f6; 
                    padding: 20px; 
                    border-radius: 8px; 
                    margin: 20px 0;
                    border-left: 5px solid #3b82f6;
                }
                .day-item { 
                    margin: 20px 0; 
                    padding: 20px; 
                    border-left: 4px solid #3b82f6; 
                    background: #f8fafc;
                    border-radius: 5px;
                }
                .highlight { 
                    margin: 8px 0; 
                    padding: 5px 0;
                }
                ul { 
                    list-style-type: disc; 
                    margin-left: 20px; 
                }
                .includes-excludes {
                    background: #fef3c7;
                    padding: 15px;
                    border-radius: 8px;
                    margin: 20px 0;
                    border-left: 4px solid #f59e0b;
                }
                .cost-info {
                    background: #ecfdf5;
                    padding: 15px;
                    border-radius: 8px;
                    margin: 20px 0;
                    border-left: 4px solid #10b981;
                }
                .print-instruction {
                    background: #dbeafe;
                    padding: 15px;
                    border-radius: 8px;
                    margin: 20px 0;
                    text-align: center;
                    border: 2px dashed #3b82f6;
                }
                @page {
                    margin: 1in;
                }
            </style>
            <script>
                window.onload = function() {
                    setTimeout(function() {
                        window.print();
                    }, 1000);
                };
            </script>
        </head>
        <body>
            <div class="print-instruction no-print">
                <strong>Instructions:</strong> This page will automatically open the print dialog. 
                Choose "Save as PDF" or "Microsoft Print to PDF" to save as a PDF file.
            </div>
            
            <h1><?php echo esc_html($title); ?></h1>
            
            <?php
            // Get post data
            $overview = tznew_get_field_safe('overview');
            $duration = tznew_get_field_safe('duration');
            $difficulty = tznew_get_field_safe('difficulty');
            $max_altitude = tznew_get_field_safe('max_altitude');
            $best_season = tznew_get_field_safe('best_season');
            $group_size = tznew_get_field_safe('group_size');
            ?>
            
            <div class="meta-info">
                <h2>Trip Information</h2>
                <?php if ($duration) : ?><p><strong>Duration:</strong> <?php echo esc_html($duration); ?> Days</p><?php endif; ?>
                <?php if ($difficulty) : ?><p><strong>Difficulty:</strong> <?php echo esc_html(ucfirst($difficulty)); ?></p><?php endif; ?>
                <?php if ($max_altitude) : ?><p><strong>Max Altitude:</strong> <?php echo esc_html($max_altitude); ?>m</p><?php endif; ?>
                <?php if ($best_season) : ?><p><strong>Best Season:</strong> <?php echo esc_html($best_season); ?></p><?php endif; ?>
                <?php if ($group_size) : ?><p><strong>Group Size:</strong> <?php echo esc_html($group_size); ?></p><?php endif; ?>
            </div>
            
            <?php if ($overview) : ?>
                <h2>Overview</h2>
                <div><?php echo wp_kses_post($overview); ?></div>
            <?php endif; ?>
            
            <?php if (tznew_have_rows_safe('highlights')) : ?>
                <h2>Highlights</h2>
                <ul>
                    <?php while (tznew_have_rows_safe('highlights')) : tznew_the_row_safe(); ?>
                        <?php $highlight = tznew_get_sub_field_safe('highlight'); ?>
                        <?php if ($highlight) : ?>
                            <li class="highlight"><?php echo wp_kses_post($highlight); ?></li>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>
            
            <?php if (tznew_have_rows_safe('itinerary')) : ?>
                <h2>Detailed Itinerary</h2>
                <?php 
                $day_count = 1;
                while (tznew_have_rows_safe('itinerary')) : 
                    tznew_the_row_safe();
                    $day_title = tznew_get_sub_field_safe('title');
                    $day_description = tznew_get_sub_field_safe('description');
                    $place_name = tznew_get_sub_field_safe('place_name');
                    $altitude = tznew_get_sub_field_safe('altitude');
                    $walking_time = tznew_get_sub_field_safe('walking_time');
                    $distance = tznew_get_sub_field_safe('distance');
                    $accommodation = tznew_get_sub_field_safe('accommodation');
                    $meals = tznew_get_sub_field_safe('meals');
                ?>
                    <div class="day-item">
                        <h3>Day <?php echo esc_html($day_count); ?>: <?php echo esc_html($day_title); ?></h3>
                        <?php if ($place_name) : ?><p><strong>Location:</strong> <?php echo esc_html($place_name); ?></p><?php endif; ?>
                        <?php if ($altitude) : ?><p><strong>Altitude:</strong> <?php echo esc_html(number_format($altitude)); ?>m</p><?php endif; ?>
                        <?php if ($walking_time) : ?><p><strong>Walking Time:</strong> <?php echo esc_html($walking_time); ?></p><?php endif; ?>
                        <?php if ($distance) : ?><p><strong>Distance:</strong> <?php echo esc_html($distance); ?></p><?php endif; ?>
                        <?php if ($accommodation) : ?><p><strong>Accommodation:</strong> <?php echo esc_html($accommodation); ?></p><?php endif; ?>
                        <?php if ($meals) : ?><p><strong>Meals:</strong> <?php echo esc_html($meals); ?></p><?php endif; ?>
                        <?php if ($day_description) : ?>
                            <div><?php echo wp_kses_post($day_description); ?></div>
                        <?php endif; ?>
                    </div>
                <?php 
                    $day_count++;
                endwhile; 
                ?>
            <?php endif; ?>
            
            <?php
            // Includes/Excludes
            $includes = tznew_get_field_safe('includes');
            $excludes = tznew_get_field_safe('excludes');
            ?>
            
            <?php if ($includes) : ?>
                <div class="includes-excludes">
                    <h2>What's Included</h2>
                    <div><?php echo wp_kses_post($includes); ?></div>
                </div>
            <?php endif; ?>
            
            <?php if ($excludes) : ?>
                <div class="includes-excludes">
                    <h2>What's Not Included</h2>
                    <div><?php echo wp_kses_post($excludes); ?></div>
                </div>
            <?php endif; ?>
            
            <?php
            // Cost Information
            $cost_info = tznew_get_field_safe('cost_info');
            if ($cost_info && isset($cost_info['price_usd']) && $cost_info['price_usd']) :
            ?>
                <div class="cost-info">
                    <h2>Cost Information</h2>
                    <p><strong>Price:</strong> $<?php echo esc_html(number_format($cost_info['price_usd'])); ?></p>
                    <?php if (isset($cost_info['pricing_type']) && $cost_info['pricing_type']) : ?>
                        <p><strong>Pricing Type:</strong> <?php echo esc_html($cost_info['pricing_type']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <p style="text-align: center; margin-top: 40px; font-size: 12px; color: #666; border-top: 1px solid #ddd; padding-top: 20px;">
                Generated by Dragon Holidays - <?php echo date('Y-m-d H:i:s'); ?><br>
                <em>For the latest information, visit our website</em>
            </p>
        </body>
        </html>
        <?php
        $html = ob_get_clean();
        
        error_log('PDF Generation: HTML length: ' . strlen($html));
        error_log('PDF Generation: Title: ' . $title);
        
        // Return JSON response with the HTML content for opening in new window
        wp_send_json_success(array(
            'html' => $html,
            'filename' => sanitize_file_name($title . '-itinerary')
        ));
    }

    function get_trek_locations_ajax() {
        $trek_locations = array();
        
        // Query all published trekking posts
        $treks = get_posts(array(
            'post_type' => 'trekking',
            'post_status' => 'publish',
            'numberposts' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'itinerary',
                    'compare' => 'EXISTS'
                )
            )
        ));
        
        foreach ($treks as $trek) {
            $itinerary = tznew_get_field_safe('itinerary', '', $trek->ID);
            
            if ($itinerary && is_array($itinerary)) {
                // Get the first day with coordinates as the main location
                foreach ($itinerary as $day) {
                    $coordinates = isset($day['coordinates']) ? $day['coordinates'] : null;
                    
                    if ($coordinates && isset($coordinates['latitude']) && isset($coordinates['longitude']) && 
                        !empty($coordinates['latitude']) && !empty($coordinates['longitude'])) {
                        
                        $trek_data = array(
                            'title' => get_the_title($trek->ID),
                            'url' => get_permalink($trek->ID),
                            'latitude' => floatval($coordinates['latitude']),
                            'longitude' => floatval($coordinates['longitude']),
                            'duration' => tznew_get_field_safe('duration', '', $trek->ID),
                            'difficulty' => tznew_get_field_safe('difficulty', '', $trek->ID),
                            'max_altitude' => tznew_get_field_safe('max_altitude', '', $trek->ID),
                            'rating' => tznew_get_field_safe('rating', '', $trek->ID)
                        );
                        
                        $trek_locations[] = $trek_data;
                        break; // Use only the first location with coordinates
                    }
                }
            }
        }
        
        wp_send_json_success($trek_locations);
    }

    // AJAX handler for getting all tour and trek locations
    add_action('wp_ajax_get_all_locations', 'get_all_locations_ajax');
    add_action('wp_ajax_nopriv_get_all_locations', 'get_all_locations_ajax');

    function get_all_locations_ajax() {
        $regional_destinations = array();
        
        // Get all regions with their center coordinates
        $regions = get_terms(array(
            'taxonomy' => 'region',
            'hide_empty' => false
        ));
        
        foreach ($regions as $region) {
            $region_slug = $region->slug;
            $region_name = $region->name;
            
            // Check if this region should be shown on the map
            $show_on_map = tznew_get_field_safe('show_on_regional_adventure_map', false, 'region_' . $region->term_id);
            if (!$show_on_map) {
                continue;
            }
            
            // Get coordinates from ACF fields
            $region_coordinates = tznew_get_field_safe('region_coordinates', array(), 'region_' . $region->term_id);
            if (!$region_coordinates || empty($region_coordinates['latitude']) || empty($region_coordinates['longitude'])) {
                continue;
            }
            
            $latitude = floatval($region_coordinates['latitude']);
            $longitude = floatval($region_coordinates['longitude']);
            
            // Get all trekking posts in this region
            $treks = get_posts(array(
                'post_type' => 'trekking',
                'post_status' => 'publish',
                'numberposts' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'region',
                        'field' => 'term_id',
                        'terms' => $region->term_id
                    )
                )
            ));
            
            // Get all tour posts in this region
            $tours = get_posts(array(
                'post_type' => 'tours',
                'post_status' => 'publish',
                'numberposts' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'region',
                        'field' => 'term_id',
                        'terms' => $region->term_id
                    )
                )
            ));
            
            // Prepare routes data for this region
            $routes = array();
            
            // Add trekking routes
            foreach ($treks as $trek) {
                $routes[] = array(
                    'id' => $trek->ID,
                    'title' => get_the_title($trek->ID),
                    'url' => get_permalink($trek->ID),
                    'type' => 'trekking',
                    'duration' => tznew_get_field_safe('duration', '', $trek->ID),
                    'difficulty' => tznew_get_field_safe('difficulty', '', $trek->ID),
                    'max_altitude' => tznew_get_field_safe('max_altitude', '', $trek->ID),
                    'rating' => tznew_get_field_safe('rating', '', $trek->ID),
                    'overview' => wp_trim_words(tznew_get_field_safe('overview', '', $trek->ID), 15),
                    'thumbnail' => get_the_post_thumbnail_url($trek->ID, 'medium')
                );
            }
            
            // Add tour routes
            foreach ($tours as $tour) {
                $routes[] = array(
                    'id' => $tour->ID,
                    'title' => get_the_title($tour->ID),
                    'url' => get_permalink($tour->ID),
                    'type' => 'tours',
                    'duration' => tznew_get_field_safe('duration', '', $tour->ID),
                    'tour_type' => tznew_get_field_safe('tour_type', '', $tour->ID),
                    'cost' => tznew_get_field_safe('cost', '', $tour->ID),
                    'rating' => tznew_get_field_safe('rating', '', $tour->ID),
                    'overview' => wp_trim_words(tznew_get_field_safe('overview', '', $tour->ID), 15),
                    'thumbnail' => get_the_post_thumbnail_url($tour->ID, 'medium')
                );
            }
            
            // Only include regions that have routes
            if (!empty($routes)) {
                $regional_destinations[] = array(
                    'region_id' => $region->term_id,
                    'region_name' => $region_name,
                    'region_slug' => $region_slug,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'routes_count' => count($routes),
                    'trekking_count' => count($treks),
                    'tours_count' => count($tours),
                    'routes' => $routes
                );
            }
        }
        
        wp_send_json_success($regional_destinations);
    }

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'tznew_scripts');

/**
 * Check if ACF is active and display admin notice if not
 */
function tznew_check_acf_dependency() {
    if (!class_exists('ACF')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>';
            echo esc_html__('TZnew Theme requires Advanced Custom Fields (ACF) plugin to be installed and activated.', 'tznew');
            echo '</p></div>';
        });
    }
}
add_action('admin_init', 'tznew_check_acf_dependency');

/**
 * Include required files
 */
require_once TZNEW_INC_DIR . '/post-types.php';
require_once TZNEW_INC_DIR . '/acf-integration.php';
require_once TZNEW_INC_DIR . '/theme-settings.php';
require_once TZNEW_INC_DIR . '/theme-functions.php';
require_once TZNEW_INC_DIR . '/rest-api.php';
require_once TZNEW_INC_DIR . '/customizer-colors.php';
require_once TZNEW_INC_DIR . '/theme-settings-notice.php';

// Include WooCommerce support if WooCommerce is active
if (class_exists('WooCommerce')) {
    require_once TZNEW_INC_DIR . '/woocommerce.php';
}

// Include Elementor support if Elementor is active
if (defined('ELEMENTOR_VERSION')) {
    require_once TZNEW_INC_DIR . '/elementor.php';
    require_once TZNEW_INC_DIR . '/elementor-integration.php';
    require_once TZNEW_THEME_DIR . '/elementor-activation-notice.php';
}

/**
 * Add Elementor page template support
 */
function tznew_add_elementor_page_templates( $page_templates ) {
    $page_templates['page-canvas.php'] = esc_html__( 'Elementor Canvas', 'tznew' );
    $page_templates['page-elementor_header_footer.php'] = esc_html__( 'Elementor Full Width', 'tznew' );
    return $page_templates;
}
add_filter( 'theme_page_templates', 'tznew_add_elementor_page_templates' );

/**
 * Ensure Elementor content area is properly configured
 */
function tznew_elementor_content_area_support() {
    // Add theme support for Elementor
    add_theme_support( 'elementor' );
    
    // Add support for Elementor Pro features
    add_theme_support( 'elementor-pro' );
    
    // Ensure content width is set for Elementor
    if ( ! isset( $GLOBALS['content_width'] ) ) {
        $GLOBALS['content_width'] = 1200;
    }
    
    // Add post type support for Elementor
    add_post_type_support( 'page', 'elementor' );
    add_post_type_support( 'post', 'elementor' );
    add_post_type_support( 'trekking', 'elementor' );
    add_post_type_support( 'tours', 'elementor' );
    add_post_type_support( 'blog', 'elementor' );
    add_post_type_support( 'faq', 'elementor' );
}
add_action( 'after_setup_theme', 'tznew_elementor_content_area_support', 20 );

/**
 * Fix Elementor content area detection
 */
function tznew_elementor_content_area_fix() {
    if ( ! class_exists( '\Elementor\Plugin' ) ) {
        return;
    }
    
    // Ensure the_content filter is properly applied
    add_filter( 'the_content', function( $content ) {
        if ( \Elementor\Plugin::$instance->documents->get( get_the_ID() )->is_built_with_elementor() ) {
            return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( get_the_ID() );
        }
        return $content;
    }, 999 );
}
add_action( 'wp', 'tznew_elementor_content_area_fix' );

/**
 * AJAX handler for live search
 */
function tznew_live_search() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'tznew_nonce')) {
        wp_die('Security check failed');
    }

    $search_term = sanitize_text_field($_POST['query'] ?? '');
    
    if (empty($search_term)) {
        wp_send_json_error('Empty search term');
    }

    $args = [
        'post_type'      => ['post', 'trekking', 'tours'],
        'post_status'    => 'publish',
        'posts_per_page' => 10,
        's'              => $search_term,
        'meta_query'     => [
            'relation' => 'OR',
            [
                'key'     => 'overview',
                'value'   => $search_term,
                'compare' => 'LIKE'
            ],
            [
                'key'     => 'highlights',
                'value'   => $search_term,
                'compare' => 'LIKE'
            ]
        ]
    ];

    $query = new WP_Query($args);
    $results = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $results[] = [
                'id'        => get_the_ID(),
                'title'     => get_the_title(),
                'permalink' => get_permalink(),
                'excerpt'   => wp_trim_words(get_the_excerpt(), 20),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'),
                'post_type' => get_post_type()
            ];
        }
        wp_reset_postdata();
    }

    wp_send_json_success($results);
}
add_action('wp_ajax_tznew_live_search', 'tznew_live_search');
add_action('wp_ajax_nopriv_tznew_live_search', 'tznew_live_search');

/**
 * AJAX handler for booking form submission
 */
function tznew_handle_booking_submission() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['booking_nonce'] ?? '', 'tznew_booking_nonce')) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    // Sanitize and validate form data
    $booking_data = [
        'first_name' => sanitize_text_field($_POST['first_name'] ?? ''),
        'last_name' => sanitize_text_field($_POST['last_name'] ?? ''),
        'email' => sanitize_email($_POST['email'] ?? ''),
        'phone' => sanitize_text_field($_POST['phone'] ?? ''),
        'country' => sanitize_text_field($_POST['country'] ?? ''),
        'preferred_date' => sanitize_text_field($_POST['preferred_date'] ?? ''),
        'group_size' => intval($_POST['group_size'] ?? 0),
        'accommodation_preference' => sanitize_text_field($_POST['accommodation_preference'] ?? ''),
        'dietary_requirements' => sanitize_textarea_field($_POST['dietary_requirements'] ?? ''),
        'special_requests' => sanitize_textarea_field($_POST['special_requests'] ?? ''),
        'post_id' => intval($_POST['post_id'] ?? 0),
        'post_title' => sanitize_text_field($_POST['post_title'] ?? ''),
        'post_type' => sanitize_text_field($_POST['post_type'] ?? '')
    ];

    // Validate required fields
    $required_fields = ['first_name', 'last_name', 'email', 'phone', 'country', 'preferred_date', 'group_size'];
    foreach ($required_fields as $field) {
        if (empty($booking_data[$field])) {
            wp_send_json_error(['message' => 'Please fill in all required fields']);
        }
    }

    // Validate email
    if (!is_email($booking_data['email'])) {
        wp_send_json_error(['message' => 'Please enter a valid email address']);
    }

    // Validate date (must be at least 1 week from now)
    $min_date = date('Y-m-d', strtotime('+1 week'));
    if ($booking_data['preferred_date'] < $min_date) {
        wp_send_json_error(['message' => 'Please select a date at least one week from today']);
    }

    // Prepare email content
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    
    $subject = sprintf('[%s] New Booking Request - %s', $site_name, $booking_data['post_title']);
    
    $message = "New booking request received:\n\n";
    $message .= "Trip: {$booking_data['post_title']}\n";
    $message .= "Name: {$booking_data['first_name']} {$booking_data['last_name']}\n";
    $message .= "Email: {$booking_data['email']}\n";
    $message .= "Phone: {$booking_data['phone']}\n";
    $message .= "Country: {$booking_data['country']}\n";
    $message .= "Preferred Date: {$booking_data['preferred_date']}\n";
    $message .= "Group Size: {$booking_data['group_size']} people\n";
    
    if (!empty($booking_data['accommodation_preference'])) {
        $message .= "Accommodation: {$booking_data['accommodation_preference']}\n";
    }
    
    if (!empty($booking_data['dietary_requirements'])) {
        $message .= "Dietary Requirements: {$booking_data['dietary_requirements']}\n";
    }
    
    if (!empty($booking_data['special_requests'])) {
        $message .= "Special Requests: {$booking_data['special_requests']}\n";
    }
    
    $message .= "\nSubmitted on: " . current_time('mysql') . "\n";
    $message .= "Post URL: " . get_permalink($booking_data['post_id']) . "\n";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . $admin_email . '>',
        'Reply-To: ' . $booking_data['email']
    ];

    // Send email
    $email_sent = wp_mail($admin_email, $subject, $message, $headers);
    
    // Send confirmation email to customer
    $customer_subject = sprintf('[%s] Booking Request Confirmation', $site_name);
    $customer_message = "Dear {$booking_data['first_name']},\n\n";
    $customer_message .= "Thank you for your booking request for '{$booking_data['post_title']}'.\n\n";
    $customer_message .= "We have received your request and will get back to you within 24 hours with detailed information and next steps.\n\n";
    $customer_message .= "Booking Details:\n";
    $customer_message .= "- Trip: {$booking_data['post_title']}\n";
    $customer_message .= "- Preferred Date: {$booking_data['preferred_date']}\n";
    $customer_message .= "- Group Size: {$booking_data['group_size']} people\n\n";
    $customer_message .= "If you have any immediate questions, please don't hesitate to contact us.\n\n";
    $customer_message .= "Best regards,\n";
    $customer_message .= "The {$site_name} Team";
    
    $customer_headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . $admin_email . '>'
    ];
    
    wp_mail($booking_data['email'], $customer_subject, $customer_message, $customer_headers);

    if ($email_sent) {
        wp_send_json_success([
            'message' => 'Thank you! Your booking request has been submitted successfully. We will contact you within 24 hours.'
        ]);
    } else {
        wp_send_json_error([
            'message' => 'There was an error sending your request. Please try again or contact us directly.'
        ]);
    }
}
add_action('wp_ajax_tznew_submit_booking', 'tznew_handle_booking_submission');
add_action('wp_ajax_nopriv_tznew_submit_booking', 'tznew_handle_booking_submission');

/**
 * AJAX handler for inquiry form submission
 */
function tznew_handle_inquiry_submission() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['inquiry_nonce'] ?? '', 'tznew_inquiry_nonce')) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    // Sanitize and validate form data
    $inquiry_data = [
        'inquiry_name' => sanitize_text_field($_POST['inquiry_name'] ?? ''),
        'inquiry_email' => sanitize_email($_POST['inquiry_email'] ?? ''),
        'inquiry_phone' => sanitize_text_field($_POST['inquiry_phone'] ?? ''),
        'inquiry_country' => sanitize_text_field($_POST['inquiry_country'] ?? ''),
        'inquiry_subject' => sanitize_text_field($_POST['inquiry_subject'] ?? ''),
        'inquiry_message' => sanitize_textarea_field($_POST['inquiry_message'] ?? ''),
        'travel_dates' => sanitize_text_field($_POST['travel_dates'] ?? ''),
        'group_size_inquiry' => sanitize_text_field($_POST['group_size_inquiry'] ?? ''),
        'budget_range' => sanitize_text_field($_POST['budget_range'] ?? ''),
        'contact_preference' => sanitize_text_field($_POST['contact_preference'] ?? ''),
        'response_urgency' => sanitize_text_field($_POST['response_urgency'] ?? ''),
        'newsletter_subscription' => isset($_POST['newsletter_subscription']) ? 1 : 0,
        'post_id' => intval($_POST['post_id'] ?? 0),
        'post_title' => sanitize_text_field($_POST['post_title'] ?? ''),
        'post_type' => sanitize_text_field($_POST['post_type'] ?? '')
    ];

    // Validate required fields
    if (empty($inquiry_data['inquiry_name']) || empty($inquiry_data['inquiry_email']) || 
        empty($inquiry_data['inquiry_subject']) || empty($inquiry_data['inquiry_message'])) {
        wp_send_json_error(['message' => 'Please fill in all required fields']);
    }

    // Validate email
    if (!is_email($inquiry_data['inquiry_email'])) {
        wp_send_json_error(['message' => 'Please enter a valid email address']);
    }

    // Validate message length
    if (strlen($inquiry_data['inquiry_message']) < 10) {
        wp_send_json_error(['message' => 'Please provide a more detailed message (minimum 10 characters)']);
    }

    // Prepare email content
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    
    $subject_types = [
        'general' => 'General Information',
        'booking' => 'Booking Inquiry',
        'customization' => 'Trip Customization',
        'pricing' => 'Pricing Information',
        'group' => 'Group Booking',
        'permits' => 'Permits & Documentation',
        'equipment' => 'Equipment & Gear',
        'weather' => 'Weather & Best Time',
        'support' => 'Customer Support',
        'other' => 'Other'
    ];
    
    $subject_text = isset($subject_types[$inquiry_data['inquiry_subject']]) ? 
                   $subject_types[$inquiry_data['inquiry_subject']] : 'General Inquiry';
    
    $subject = sprintf('[%s] %s - %s', $site_name, $subject_text, $inquiry_data['inquiry_name']);
    
    $message = "New inquiry received:\n\n";
    $message .= "Name: {$inquiry_data['inquiry_name']}\n";
    $message .= "Email: {$inquiry_data['inquiry_email']}\n";
    
    if (!empty($inquiry_data['inquiry_phone'])) {
        $message .= "Phone: {$inquiry_data['inquiry_phone']}\n";
    }
    
    if (!empty($inquiry_data['inquiry_country'])) {
        $message .= "Country: {$inquiry_data['inquiry_country']}\n";
    }
    
    $message .= "Subject: {$subject_text}\n";
    $message .= "Contact Preference: {$inquiry_data['contact_preference']}\n";
    $message .= "Response Urgency: {$inquiry_data['response_urgency']}\n\n";
    
    if (!empty($inquiry_data['post_title'])) {
        $message .= "Related to: {$inquiry_data['post_title']}\n";
    }
    
    if (!empty($inquiry_data['travel_dates'])) {
        $message .= "Travel Dates: {$inquiry_data['travel_dates']}\n";
    }
    
    if (!empty($inquiry_data['group_size_inquiry'])) {
        $message .= "Group Size: {$inquiry_data['group_size_inquiry']}\n";
    }
    
    if (!empty($inquiry_data['budget_range'])) {
        $message .= "Budget Range: {$inquiry_data['budget_range']}\n";
    }
    
    $message .= "\nMessage:\n{$inquiry_data['inquiry_message']}\n\n";
    
    if ($inquiry_data['newsletter_subscription']) {
        $message .= "Newsletter subscription: Yes\n";
    }
    
    $message .= "\nSubmitted on: " . current_time('mysql') . "\n";
    
    if (!empty($inquiry_data['post_id'])) {
        $message .= "Post URL: " . get_permalink($inquiry_data['post_id']) . "\n";
    }

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . $admin_email . '>',
        'Reply-To: ' . $inquiry_data['inquiry_email']
    ];

    // Send email
    $email_sent = wp_mail($admin_email, $subject, $message, $headers);
    
    // Send confirmation email to customer
    $customer_subject = sprintf('[%s] Inquiry Confirmation', $site_name);
    $customer_message = "Dear {$inquiry_data['inquiry_name']},\n\n";
    $customer_message .= "Thank you for contacting us. We have received your inquiry regarding '{$subject_text}'.\n\n";
    
    $response_time = [
        'normal' => '24 hours',
        'urgent' => '4 hours',
        'flexible' => '48 hours'
    ];
    
    $expected_response = isset($response_time[$inquiry_data['response_urgency']]) ? 
                        $response_time[$inquiry_data['response_urgency']] : '24 hours';
    
    $customer_message .= "We will respond to your inquiry within {$expected_response} via your preferred contact method.\n\n";
    $customer_message .= "Your inquiry details:\n";
    $customer_message .= "- Subject: {$subject_text}\n";
    $customer_message .= "- Contact preference: {$inquiry_data['contact_preference']}\n\n";
    $customer_message .= "If you have any urgent questions, please don't hesitate to contact us directly.\n\n";
    $customer_message .= "Best regards,\n";
    $customer_message .= "The {$site_name} Team";
    
    $customer_headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . $admin_email . '>'
    ];
    
    wp_mail($inquiry_data['inquiry_email'], $customer_subject, $customer_message, $customer_headers);

    if ($email_sent) {
        wp_send_json_success([
            'message' => 'Thank you! Your inquiry has been submitted successfully. We will respond within ' . $expected_response . '.'
        ]);
    } else {
        wp_send_json_error([
            'message' => 'There was an error sending your inquiry. Please try again or contact us directly.'
        ]);
    }
}
add_action('wp_ajax_tznew_submit_inquiry', 'tznew_handle_inquiry_submission');
add_action('wp_ajax_nopriv_tznew_submit_inquiry', 'tznew_handle_inquiry_submission');

/**
 * AJAX handler for contact form submission
 */
function tznew_handle_contact_submission() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['contact_nonce'] ?? '', 'tznew_contact_nonce')) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    // Sanitize and validate form data
    $contact_data = [
        'name' => sanitize_text_field($_POST['name'] ?? ''),
        'email' => sanitize_email($_POST['email'] ?? ''),
        'subject' => sanitize_text_field($_POST['subject'] ?? ''),
        'message' => sanitize_textarea_field($_POST['message'] ?? '')
    ];

    // Validate required fields
    if (empty($contact_data['name']) || empty($contact_data['email']) || 
        empty($contact_data['subject']) || empty($contact_data['message'])) {
        wp_send_json_error(['message' => 'Please fill in all required fields']);
    }

    // Validate email
    if (!is_email($contact_data['email'])) {
        wp_send_json_error(['message' => 'Please enter a valid email address']);
    }

    // Validate message length
    if (strlen($contact_data['message']) < 10) {
        wp_send_json_error(['message' => 'Please provide a more detailed message (minimum 10 characters)']);
    }

    // Prepare email content
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    
    $subject = sprintf('[%s] Contact Form: %s', $site_name, $contact_data['subject']);
    
    $message = "New contact form submission:\n\n";
    $message .= "Name: {$contact_data['name']}\n";
    $message .= "Email: {$contact_data['email']}\n";
    $message .= "Subject: {$contact_data['subject']}\n\n";
    $message .= "Message:\n{$contact_data['message']}\n\n";
    $message .= "Submitted on: " . current_time('mysql') . "\n";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . $admin_email . '>',
        'Reply-To: ' . $contact_data['email']
    ];

    // Send email
    $email_sent = wp_mail($admin_email, $subject, $message, $headers);
    
    // Send confirmation email to customer
    $customer_subject = sprintf('[%s] Message Received', $site_name);
    $customer_message = "Dear {$contact_data['name']},\n\n";
    $customer_message .= "Thank you for contacting us. We have received your message and will get back to you as soon as possible.\n\n";
    $customer_message .= "Your message details:\n";
    $customer_message .= "Subject: {$contact_data['subject']}\n\n";
    $customer_message .= "We typically respond within 24 hours during business days.\n\n";
    $customer_message .= "Best regards,\n";
    $customer_message .= "The {$site_name} Team";
    
    $customer_headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . $admin_email . '>'
    ];
    
    wp_mail($contact_data['email'], $customer_subject, $customer_message, $customer_headers);

    if ($email_sent) {
        wp_send_json_success([
            'message' => 'Thank you! Your message has been sent successfully. We will get back to you soon.'
        ]);
    } else {
        wp_send_json_error([
            'message' => 'There was an error sending your message. Please try again or contact us directly.'
        ]);
    }
}
add_action('wp_ajax_tznew_submit_contact', 'tznew_handle_contact_submission');
add_action('wp_ajax_nopriv_tznew_submit_contact', 'tznew_handle_contact_submission');

/**
 * AJAX handler for post filtering
 */
function tznew_filter_posts() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'tznew_nonce')) {
        wp_die('Security check failed');
    }

    $post_type = sanitize_text_field($_POST['post_type'] ?? 'post');
    $filters = $_POST['filters'] ?? [];
    $page = intval($_POST['page'] ?? 1);
    $posts_per_page = intval($_POST['posts_per_page'] ?? 12);

    $args = [
        'post_type'      => $post_type,
        'post_status'    => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged'          => $page,
        'meta_query'     => ['relation' => 'AND'],
        'tax_query'      => ['relation' => 'AND']
    ];

    // Apply filters based on post type
    if ($post_type === 'trekking') {
        if (!empty($filters['difficulty'])) {
            $args['meta_query'][] = [
                'key'     => 'difficulty',
                'value'   => sanitize_text_field($filters['difficulty']),
                'compare' => '='
            ];
        }
        
        if (!empty($filters['duration_min']) || !empty($filters['duration_max'])) {
            $duration_query = ['key' => 'duration', 'type' => 'NUMERIC'];
            
            if (!empty($filters['duration_min'])) {
                $duration_query['value'][] = intval($filters['duration_min']);
                $duration_query['compare'] = '>=';
            }
            
            if (!empty($filters['duration_max'])) {
                if (isset($duration_query['value'])) {
                    $duration_query['value'][] = intval($filters['duration_max']);
                    $duration_query['compare'] = 'BETWEEN';
                } else {
                    $duration_query['value'] = intval($filters['duration_max']);
                    $duration_query['compare'] = '<=';
                }
            }
            
            $args['meta_query'][] = $duration_query;
        }
        
        if (!empty($filters['region'])) {
            $args['tax_query'][] = [
                'taxonomy' => 'region',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($filters['region'])
            ];
        }
    } elseif ($post_type === 'tours') {
        if (!empty($filters['tour_type'])) {
            $args['meta_query'][] = [
                'key'     => 'tour_type',
                'value'   => sanitize_text_field($filters['tour_type']),
                'compare' => '='
            ];
        }
        
        if (!empty($filters['duration_min']) || !empty($filters['duration_max'])) {
            $duration_query = ['key' => 'duration', 'type' => 'NUMERIC'];
            
            if (!empty($filters['duration_min'])) {
                $duration_query['value'][] = intval($filters['duration_min']);
                $duration_query['compare'] = '>=';
            }
            
            if (!empty($filters['duration_max'])) {
                if (isset($duration_query['value'])) {
                    $duration_query['value'][] = intval($filters['duration_max']);
                    $duration_query['compare'] = 'BETWEEN';
                } else {
                    $duration_query['value'] = intval($filters['duration_max']);
                    $duration_query['compare'] = '<=';
                }
            }
            
            $args['meta_query'][] = $duration_query;
        }
    }

    $query = new WP_Query($args);
    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            ob_start();
            get_template_part('template-parts/content', get_post_type());
            $content = ob_get_clean();
            
            $posts[] = [
                'id'      => get_the_ID(),
                'content' => $content
            ];
        }
        wp_reset_postdata();
    }

    wp_send_json_success([
        'posts'      => $posts,
        'found_posts' => $query->found_posts,
        'max_pages'   => $query->max_num_pages,
        'current_page' => $page
    ]);
}
add_action('wp_ajax_tznew_filter_posts', 'tznew_filter_posts');
add_action('wp_ajax_nopriv_tznew_filter_posts', 'tznew_filter_posts');

/**
 * Fallback menu for footer
 */
function tznew_footer_menu_fallback() {
    echo '<ul class="footer-menu-fallback">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'tznew') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/about/')) . '">' . esc_html__('About', 'tznew') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/contact/')) . '">' . esc_html__('Contact', 'tznew') . '</a></li>';
    echo '</ul>';
}

/**
 * Register widget areas
 */
function tznew_widgets_init() {
    register_sidebar([
        'name'          => esc_html__('Sidebar', 'tznew'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'tznew'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);

    register_sidebar([
        'name'          => esc_html__('Footer Widget Area 1', 'tznew'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here for footer column 1.', 'tznew'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);

    register_sidebar([
        'name'          => esc_html__('Footer Widget Area 2', 'tznew'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets here for footer column 2.', 'tznew'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);

    register_sidebar([
        'name'          => esc_html__('Footer Widget Area 3', 'tznew'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets here for footer column 3.', 'tznew'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);

    register_sidebar([
        'name'          => esc_html__('Footer Widget Area 4', 'tznew'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Add widgets here for footer column 4.', 'tznew'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
}
add_action('widgets_init', 'tznew_widgets_init');

/**
 * Customize excerpt length
 */
function tznew_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'tznew_excerpt_length');

/**
 * Customize excerpt more text
 */
function tznew_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'tznew_excerpt_more');

/**
 * Add ACF options page
 */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title' => 'Theme General Settings',
        'menu_title' => 'Theme Settings',
        'menu_slug'  => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect'   => false
    ]);
}

/**
 * Set ACF JSON save and load points
 */
function tznew_acf_json_save_point($path) {
    return TZNEW_THEME_DIR . '/acf-json';
}
add_filter('acf/settings/save_json', 'tznew_acf_json_save_point');

function tznew_acf_json_load_point($paths) {
    unset($paths[0]);
    $paths[] = TZNEW_THEME_DIR . '/acf-json';
    return $paths;
}
add_filter('acf/settings/load_json', 'tznew_acf_json_load_point');

/**
 * Disable Gutenberg editor for custom post types
 */
function tznew_disable_gutenberg($current_status, $post_type) {
    $disabled_post_types = ['trekking', 'tours'];
    
    if (in_array($post_type, $disabled_post_types, true)) {
        return false;
    }
    
    return $current_status;
}
add_filter('use_block_editor_for_post_type', 'tznew_disable_gutenberg', 10, 2);

/**
 * Add custom columns to admin for trekking posts
 */
function tznew_trekking_columns($columns) {
    $new_columns = [];
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        if ($key === 'title') {
            $new_columns['duration'] = esc_html__('Duration', 'tznew');
            $new_columns['difficulty'] = esc_html__('Difficulty', 'tznew');
            $new_columns['region'] = esc_html__('Region', 'tznew');
        }
    }
    
    return $new_columns;
}
add_filter('manage_trekking_posts_columns', 'tznew_trekking_columns');

function tznew_trekking_column_content($column, $post_id) {
    switch ($column) {
        case 'duration':
            $duration = tznew_get_field_safe('duration', $post_id);
            echo $duration ? esc_html($duration . ' Days') : '—';
            break;
            
        case 'difficulty':
            $difficulty = tznew_get_field_safe('difficulty', $post_id);
            echo $difficulty ? esc_html(ucfirst($difficulty)) : '—';
            break;
            
        case 'region':
            $regions = get_the_terms($post_id, 'region');
            if ($regions && !is_wp_error($regions)) {
                $region_names = wp_list_pluck($regions, 'name');
                echo esc_html(implode(', ', $region_names));
            } else {
                echo '—';
            }
            break;
    }
}
add_action('manage_trekking_posts_custom_column', 'tznew_trekking_column_content', 10, 2);

/**
 * Add custom columns to admin for tours posts
 */
function tznew_tours_columns($columns) {
    $new_columns = [];
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        if ($key === 'title') {
            $new_columns['duration'] = esc_html__('Duration', 'tznew');
            $new_columns['tour_type'] = esc_html__('Tour Type', 'tznew');
            $new_columns['region'] = esc_html__('Region', 'tznew');
        }
    }
    
    return $new_columns;
}
add_filter('manage_tours_posts_columns', 'tznew_tours_columns');

function tznew_tours_column_content($column, $post_id) {
    switch ($column) {
        case 'duration':
            $duration = tznew_get_field_safe('duration', $post_id);
            echo $duration ? esc_html($duration . ' Days') : '—';
            break;
            
        case 'tour_type':
            $tour_type = tznew_get_field_safe('tour_type', $post_id);
            echo $tour_type ? esc_html(ucfirst($tour_type)) : '—';
            break;
            
        case 'region':
            $regions = get_the_terms($post_id, 'region');
            if ($regions && !is_wp_error($regions)) {
                $region_names = wp_list_pluck($regions, 'name');
                echo esc_html(implode(', ', $region_names));
            } else {
                echo '—';
            }
            break;
    }
}
add_action('manage_tours_posts_custom_column', 'tznew_tours_column_content', 10, 2);

/**
 * Security enhancements
 */

// Remove WordPress version from head
remove_action('wp_head', 'wp_generator');

// Remove version from scripts and styles
function tznew_remove_version_scripts_styles($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'tznew_remove_version_scripts_styles', 9999);
add_filter('script_loader_src', 'tznew_remove_version_scripts_styles', 9999);

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Remove RSD link
remove_action('wp_head', 'rsd_link');

// Remove Windows Live Writer link
remove_action('wp_head', 'wlwmanifest_link');

// Remove shortlink
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * Performance optimizations
 */

// Disable emoji scripts
function tznew_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'tznew_disable_emojis');

// Remove query strings from static resources
function tznew_remove_query_strings($src) {
    $parts = explode('?ver', $src);
    return $parts[0];
}
add_filter('script_loader_src', 'tznew_remove_query_strings', 15, 1);
add_filter('style_loader_src', 'tznew_remove_query_strings', 15, 1);

/**
 * Error handling and logging
 */
function tznew_log_error($message, $context = []) {
    if (WP_DEBUG && WP_DEBUG_LOG) {
        $log_message = '[TZnew Theme] ' . $message;
        if (!empty($context)) {
            $log_message .= ' Context: ' . wp_json_encode($context);
        }
        error_log($log_message);
    }
}

/**
 * Create required pages
 */
function tznew_create_required_pages() {
    // Create Booking Page
    $booking_page = get_page_by_path('booking');
    if (!$booking_page) {
        $booking_page_id = wp_insert_post(array(
            'post_title' => 'Booking',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => 'booking',
            'page_template' => 'page-booking.php'
        ));
        
        if ($booking_page_id && !is_wp_error($booking_page_id)) {
            update_post_meta($booking_page_id, '_wp_page_template', 'page-booking.php');
        }
    }
    
    // Create Inquiry Page
    $inquiry_page = get_page_by_path('inquiry');
    if (!$inquiry_page) {
        $inquiry_page_id = wp_insert_post(array(
            'post_title' => 'Inquiry',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => 'inquiry',
            'page_template' => 'page-inquiry.php'
        ));
        
        if ($inquiry_page_id && !is_wp_error($inquiry_page_id)) {
            update_post_meta($inquiry_page_id, '_wp_page_template', 'page-inquiry.php');
        }
    }
    
    // Create Contact Page
    $contact_page = get_page_by_path('contact');
    if (!$contact_page) {
        $contact_page_id = wp_insert_post(array(
            'post_title' => 'Contact',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => 'contact',
            'page_template' => 'page-contact.php'
        ));
        
        if ($contact_page_id && !is_wp_error($contact_page_id)) {
            update_post_meta($contact_page_id, '_wp_page_template', 'page-contact.php');
        }
    }
}

/**
 * Theme activation hook
 */
function tznew_theme_activation() {
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Create required pages
    tznew_create_required_pages();
    
    // Set default options
    if (!get_option('tznew_theme_activated')) {
        update_option('tznew_theme_activated', true);
        
        // Set default customizer options here if needed
    }
}
add_action('after_switch_theme', 'tznew_theme_activation');

/**
 * Force create pages on init if they don't exist
 */
function tznew_ensure_pages_exist() {
    // Only run this once per day to avoid performance issues
    $last_check = get_option('tznew_last_page_check', 0);
    if (time() - $last_check < DAY_IN_SECONDS) {
        return;
    }
    
    // Check if pages exist
    $booking_page = get_page_by_path('booking');
    $inquiry_page = get_page_by_path('inquiry');
    
    if (!$booking_page || !$inquiry_page) {
        tznew_create_required_pages();
    }
    
    update_option('tznew_last_page_check', time());
}
add_action('init', 'tznew_ensure_pages_exist');



/**
 * Theme deactivation hook
 */
function tznew_theme_deactivation() {
    // Cleanup if needed
    flush_rewrite_rules();
}
add_action('switch_theme', 'tznew_theme_deactivation');

/**
 * Add theme support for WooCommerce
 */
function tznew_woocommerce_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'tznew_woocommerce_support');

/**
 * Compatibility with PHP 8.2+
 */

// Handle deprecated dynamic properties
if (version_compare(PHP_VERSION, '8.2.0', '>=')) {
    // Add any PHP 8.2+ specific compatibility code here
    
    // Example: Handle dynamic properties warnings
    function tznew_handle_dynamic_properties($class_name) {
        if (property_exists($class_name, '__dynamic_properties')) {
            return;
        }
        
        // Add #[AllowDynamicProperties] attribute equivalent handling
        // This is a placeholder for any dynamic property handling needed
    }
}

/**
 * Helper function to display FAQs on tour and trekking pages
 */
function tznew_display_faqs() {
    get_template_part('template-parts/faq-section');
}

/**
 * Initialize theme
 */
function tznew_init() {
    // Any initialization code that needs to run on init
    
    // Log theme initialization if debug is enabled
    if (WP_DEBUG) {
        tznew_log_error('Theme initialized successfully', [
            'php_version' => PHP_VERSION,
            'wp_version'  => get_bloginfo('version'),
            'theme_version' => TZNEW_VERSION
        ]);
    }
}
add_action('init', 'tznew_init');

/**
 * Calculate reading time for content
 */
if (!function_exists('tznew_get_reading_time')) {
    function tznew_get_reading_time($content) {
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
        return max(1, $reading_time);
    }
}



/**
 * Helper functions for contact information
 */
function tznew_get_contact_phone() {
    return get_theme_mod('tznew_phone', '+977 1234567890');
}

function tznew_get_whatsapp_number() {
    return get_theme_mod('tznew_whatsapp', '+977 9841234567');
}

function tznew_get_contact_email() {
    return get_theme_mod('tznew_email', 'web@techzeninc.com');
}