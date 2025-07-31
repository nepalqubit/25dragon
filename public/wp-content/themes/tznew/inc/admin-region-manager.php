<?php
/**
 * Region Manager Admin Interface
 * Allows administrators to define regions, draw polygons, and assign trips
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
 * Add Region Manager admin menu
 */
function tznew_add_region_manager_menu() {
    add_menu_page(
        __('Region Manager', 'tznew'),
        __('Region Manager', 'tznew'),
        'manage_options',
        'region-manager',
        'tznew_region_manager_page',
        'dashicons-location',
        25
    );
}
add_action('admin_menu', 'tznew_add_region_manager_menu');

/**
 * Enqueue admin scripts and styles for region manager
 */
function tznew_region_manager_admin_scripts($hook) {
    if ($hook !== 'toplevel_page_region-manager') {
        return;
    }
    
    // Enqueue WordPress media library for ACF image fields
    wp_enqueue_media();
    
    // Leaflet CSS and JS
    wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
    wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true);
    
    // Leaflet Draw for polygon drawing
    wp_enqueue_style('leaflet-draw-css', 'https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css');
    wp_enqueue_script('leaflet-draw-js', 'https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js', array('leaflet-js'), '1.0.4', true);
    
    // Custom admin styles
    wp_enqueue_style('region-manager-admin', get_template_directory_uri() . '/assets/css/region-manager-admin.css', array(), '1.0.0');
    
    // Custom admin script
    wp_enqueue_script('region-manager-admin', get_template_directory_uri() . '/assets/js/region-manager-admin.js', array('jquery', 'leaflet-js', 'leaflet-draw-js'), '1.0.0', true);
    
    // Localize script with AJAX data
    wp_localize_script('region-manager-admin', 'regionManagerAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('region_manager_nonce'),
        'regions' => tznew_get_all_regions_with_polygons(),
        'trips' => tznew_get_all_trips_for_assignment()
    ));
}
add_action('admin_enqueue_scripts', 'tznew_region_manager_admin_scripts');

/**
 * Fix ACF textarea field array issue
 * Prevents arrays from being passed to textarea fields
 */
function tznew_fix_acf_textarea_array_issue($value, $post_id, $field) {
    // Only apply to polygon_coordinates field
    if ($field['name'] === 'polygon_coordinates' && is_array($value)) {
        return json_encode($value);
    }
    return $value;
}
add_filter('acf/load_value/name=polygon_coordinates', 'tznew_fix_acf_textarea_array_issue', 10, 3);

/**
 * Region Manager admin page content
 */
function tznew_region_manager_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Region Manager', 'tznew'); ?></h1>
        <p><?php _e('Define regions, draw polygons on the map, and assign trekking and tours to regions.', 'tznew'); ?></p>
        
        <div id="region-manager-container">
            <!-- Region List Panel -->
            <div id="region-panel" class="region-panel">
                <h2><?php _e('Regions', 'tznew'); ?></h2>
                
                <div class="region-controls">
                    <button id="add-new-region" class="button button-primary">
                        <?php _e('Add New Region', 'tznew'); ?>
                    </button>
                    <button id="save-all-regions" class="button button-secondary">
                        <?php _e('Save All Changes', 'tznew'); ?>
                    </button>
                </div>
                
                <div id="regions-list">
                    <!-- Regions will be loaded here via JavaScript -->
                </div>
            </div>
            
            <!-- Map Panel -->
            <div id="map-panel" class="map-panel">
                <div class="map-header">
                    <h3><?php _e('Region Manager Map', 'tznew'); ?></h3>
                    <div class="map-actions">
                        <button id="reset-map-view" class="button button-secondary">
                            <?php _e('Reset View', 'tznew'); ?>
                        </button>
                        <button id="toggle-all-regions" class="button button-secondary">
                            <?php _e('Toggle All Regions', 'tznew'); ?>
                        </button>
                    </div>
                </div>
                
                <div id="region-map" class="region-manager-map" style="height: 600px; width: 100%; position: relative;" data-map-type="region-manager">
                    <div class="map-loading" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000;">
                        <div class="spinner"></div>
                        <p><?php _e('Loading Region Manager Map...', 'tznew'); ?></p>
                    </div>
                </div>
                
                <div class="map-controls">
                    <div class="drawing-tools">
                        <h4><?php _e('Drawing Tools', 'tznew'); ?></h4>
                        <p><?php _e('Use the drawing tools on the map to create and edit region polygons.', 'tznew'); ?></p>
                        <div class="tool-buttons">
                            <button id="enable-drawing" class="button button-primary">
                                <?php _e('Enable Drawing', 'tznew'); ?>
                            </button>
                            <button id="disable-drawing" class="button button-secondary">
                                <?php _e('Disable Drawing', 'tznew'); ?>
                            </button>
                        </div>
                    </div>
                    
                    <div class="map-info">
                        <h4><?php _e('Map Information', 'tznew'); ?></h4>
                        <div id="map-stats">
                            <span id="total-regions">0</span> <?php _e('regions defined', 'tznew'); ?><br>
                            <span id="visible-regions">0</span> <?php _e('regions visible', 'tznew'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Region Edit Modal -->
        <div id="region-edit-modal" class="region-modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="modal-title"><?php _e('Edit Region', 'tznew'); ?></h2>
                    <span class="close-modal">&times;</span>
                </div>
                
                <div class="modal-body">
                    <form id="region-form">
                        <input type="hidden" id="region-id" name="region_id" value="">
                        
                        <div class="form-group">
                            <label for="region-name"><?php _e('Region Name', 'tznew'); ?></label>
                            <input type="text" id="region-name" name="region_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="region-description"><?php _e('Description', 'tznew'); ?></label>
                            <textarea id="region-description" name="region_description" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="region-color"><?php _e('Polygon Color', 'tznew'); ?></label>
                            <input type="color" id="region-color" name="region_color" value="#3388ff">
                        </div>
                        
                        <div class="form-group">
                            <label for="show-on-map"><?php _e('Show on Map', 'tznew'); ?></label>
                            <input type="checkbox" id="show-on-map" name="show_on_map" checked>
                        </div>
                        
                        <div class="form-group">
                            <label><?php _e('Assigned Trekking', 'tznew'); ?></label>
                            <div id="assigned-trekking" class="trip-assignment">
                                <!-- Trekking checkboxes will be loaded here -->
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label><?php _e('Assigned Tours', 'tznew'); ?></label>
                            <div id="assigned-tours" class="trip-assignment">
                                <!-- Tours checkboxes will be loaded here -->
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" id="save-region" class="button button-primary">
                        <?php _e('Save Region', 'tznew'); ?>
                    </button>
                    <button type="button" id="delete-region" class="button button-link-delete">
                        <?php _e('Delete Region', 'tznew'); ?>
                    </button>
                    <button type="button" class="button close-modal">
                        <?php _e('Cancel', 'tznew'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Get all regions with their polygon data
 */
function tznew_get_all_regions_with_polygons() {
    $regions = get_terms(array(
        'taxonomy' => 'region',
        'hide_empty' => false,
    ));
    
    $regions_data = array();
    
    if (!is_wp_error($regions) && !empty($regions)) {
        foreach ($regions as $region) {
            $coordinates = get_field('region_coordinates', 'region_' . $region->term_id);
            $polygon_data_raw = get_field('polygon_coordinates', 'region_' . $region->term_id);
            // Handle both JSON string and array formats for polygon coordinates
    if (is_string($polygon_data_raw)) {
        $polygon_data = json_decode($polygon_data_raw, true);
    } elseif (is_array($polygon_data_raw)) {
        $polygon_data = $polygon_data_raw;
    } else {
        $polygon_data = array();
    }
            $show_on_map = get_field('show_on_map', 'region_' . $region->term_id);
            $description = get_field('region_description', 'region_' . $region->term_id);
            $color = get_field('polygon_color', 'region_' . $region->term_id);
            $region_image = get_field('region_image', 'region_' . $region->term_id);
            
            // Get assigned trekking with full data
            $assigned_trekking_ids = get_field('assigned_trekking', 'region_' . $region->term_id) ?: array();
            $assigned_trekking = array();
            if (!empty($assigned_trekking_ids)) {
                foreach ($assigned_trekking_ids as $trek_id) {
                    $trek_post = get_post($trek_id);
                    if ($trek_post && $trek_post->post_status === 'publish') {
                        $assigned_trekking[] = array(
                            'id' => $trek_post->ID,
                            'title' => $trek_post->post_title,
                            'region' => $region->name,
                            'duration' => get_field('duration', $trek_post->ID) ?: 0,
                            'difficulty' => get_field('difficulty', $trek_post->ID) ?: 'Moderate',
                            'price' => get_field('price', $trek_post->ID) ?: 0,
                            'image' => get_the_post_thumbnail_url($trek_post->ID, 'medium') ?: '',
                            'rating' => get_field('rating', $trek_post->ID) ?: 5,
                            'reviews' => get_field('reviews_count', $trek_post->ID) ?: 0,
                            'highlights' => get_field('highlights', $trek_post->ID) ?: array(),
                            'bestSeason' => get_field('best_season', $trek_post->ID) ?: array(),
                            'permalink' => get_permalink($trek_post->ID),
                            'post_type' => 'trekking'
                        );
                    }
                }
            }
            
            // Get assigned tours with full data
            $assigned_tours_ids = get_field('assigned_tours', 'region_' . $region->term_id) ?: array();
            $assigned_tours = array();
            if (!empty($assigned_tours_ids)) {
                foreach ($assigned_tours_ids as $tour_id) {
                    $tour_post = get_post($tour_id);
                    if ($tour_post && $tour_post->post_status === 'publish') {
                        $assigned_tours[] = array(
                            'id' => $tour_post->ID,
                            'title' => $tour_post->post_title,
                            'region' => $region->name,
                            'duration' => get_field('duration', $tour_post->ID) ?: 0,
                            'difficulty' => get_field('difficulty', $tour_post->ID) ?: 'Easy',
                            'price' => get_field('price', $tour_post->ID) ?: 0,
                            'image' => get_the_post_thumbnail_url($tour_post->ID, 'medium') ?: '',
                            'rating' => get_field('rating', $tour_post->ID) ?: 5,
                            'reviews' => get_field('reviews_count', $tour_post->ID) ?: 0,
                            'highlights' => get_field('highlights', $tour_post->ID) ?: array(),
                            'bestSeason' => get_field('best_season', $tour_post->ID) ?: array(),
                            'permalink' => get_permalink($tour_post->ID),
                            'post_type' => 'tours'
                        );
                    }
                }
            }
            
            $regions_data[] = array(
                'id' => $region->term_id,
                'name' => $region->name,
                'slug' => $region->slug,
                'description' => $description ?: '',
                'coordinates' => $coordinates ?: array('latitude' => '', 'longitude' => ''),
                'polygon_coordinates' => $polygon_data ?: array(),
                'polygon_color' => $color ?: '#3388ff',
                'show_on_map' => $show_on_map !== false,
                'region_image' => $region_image ?: null,
                'assigned_trekking' => $assigned_trekking,
                'assigned_tours' => $assigned_tours
            );
        }
    }
    
    return $regions_data;
}

/**
 * Get all trips for assignment
 */
function tznew_get_all_trips_for_assignment() {
    $trips = array(
        'trekking' => array(),
        'tours' => array()
    );
    
    // Get all trekking
    $trekking_posts = get_posts(array(
        'post_type' => 'trekking',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));
    
    foreach ($trekking_posts as $post) {
        $trips['trekking'][] = array(
            'id' => $post->ID,
            'title' => $post->post_title
        );
    }
    
    // Get all tours
    $tour_posts = get_posts(array(
        'post_type' => 'tours',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));
    
    foreach ($tour_posts as $post) {
        $trips['tours'][] = array(
            'id' => $post->ID,
            'title' => $post->post_title
        );
    }
    
    return $trips;
}

/**
 * AJAX handler to save region data
 */
function tznew_save_region_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'region_manager_nonce')) {
        wp_die('Security check failed');
    }
    
    // Check permissions
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient permissions');
    }
    
    $region_id = intval($_POST['region_id']);
    $region_name = sanitize_text_field($_POST['region_name']);
    $region_description = sanitize_textarea_field($_POST['region_description']);
    $region_color = sanitize_hex_color($_POST['region_color']);
    $show_on_map = isset($_POST['show_on_map']) ? true : false;
    $polygon_coordinates = json_decode(stripslashes($_POST['polygon_coordinates']), true);
    $assigned_trekking = isset($_POST['assigned_trekking']) ? array_map('intval', $_POST['assigned_trekking']) : array();
    $assigned_tours = isset($_POST['assigned_tours']) ? array_map('intval', $_POST['assigned_tours']) : array();
    
    // Create or update region term
    if ($region_id > 0) {
        // Update existing region
        $result = wp_update_term($region_id, 'region', array(
            'name' => $region_name,
            'slug' => sanitize_title($region_name)
        ));
    } else {
        // Create new region
        $result = wp_insert_term($region_name, 'region', array(
            'slug' => sanitize_title($region_name)
        ));
        
        if (!is_wp_error($result)) {
            $region_id = $result['term_id'];
        }
    }
    
    if (is_wp_error($result)) {
        wp_send_json_error('Failed to save region: ' . $result->get_error_message());
    }
    
    // Update ACF fields
    update_field('region_description', $region_description, 'region_' . $region_id);
    update_field('polygon_color', $region_color, 'region_' . $region_id);
    update_field('show_on_map', $show_on_map, 'region_' . $region_id);
    // Convert polygon coordinates array to JSON string for textarea field
    // Ensure we always save as a JSON string, even if empty
    $polygon_json = is_array($polygon_coordinates) ? json_encode($polygon_coordinates) : '[]';
    update_field('polygon_coordinates', $polygon_json, 'region_' . $region_id);
    update_field('assigned_trekking', $assigned_trekking, 'region_' . $region_id);
    update_field('assigned_tours', $assigned_tours, 'region_' . $region_id);
    
    wp_send_json_success(array(
        'region_id' => $region_id,
        'message' => 'Region saved successfully'
    ));
}
add_action('wp_ajax_save_region', 'tznew_save_region_ajax');

/**
 * AJAX handler to delete region
 */
function tznew_delete_region_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'region_manager_nonce')) {
        wp_die('Security check failed');
    }
    
    // Check permissions
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient permissions');
    }
    
    $region_id = intval($_POST['region_id']);
    
    if ($region_id <= 0) {
        wp_send_json_error('Invalid region ID');
    }
    
    $result = wp_delete_term($region_id, 'region');
    
    if (is_wp_error($result)) {
        wp_send_json_error('Failed to delete region: ' . $result->get_error_message());
    }
    
    wp_send_json_success('Region deleted successfully');
}
add_action('wp_ajax_delete_region', 'tznew_delete_region_ajax');

/**
 * AJAX handler to get regions for map display
 */
function tznew_get_regions_for_map_ajax() {
    $regions_data = tznew_get_all_regions_with_polygons();
    wp_send_json_success($regions_data);
}
add_action('wp_ajax_tznew_get_regions_for_map', 'tznew_get_regions_for_map_ajax');
add_action('wp_ajax_nopriv_tznew_get_regions_for_map', 'tznew_get_regions_for_map_ajax');

/**
 * Fix ACF textarea field array issue for polygon_coordinates
 * This prevents the "htmlspecialchars(): Argument #1 ($string) must be of type string, array given" error
 */
function tznew_fix_acf_polygon_coordinates_field($value, $post_id, $field) {
    // Only apply to polygon_coordinates field
    if (isset($field['name']) && $field['name'] === 'polygon_coordinates') {
        // If value is an array, convert to JSON string
        if (is_array($value)) {
            return json_encode($value);
        }
        // If value is empty or null, return empty JSON array
        if (empty($value)) {
            return '[]';
        }
        // If it's already a string, return as is
        return $value;
    }
    return $value;
}

// Apply the filter to multiple ACF hooks to ensure it catches all cases
add_filter('acf/load_value/name=polygon_coordinates', 'tznew_fix_acf_polygon_coordinates_field', 5, 3);
add_filter('acf/format_value/name=polygon_coordinates', 'tznew_fix_acf_polygon_coordinates_field', 5, 3);
add_filter('acf/update_value/name=polygon_coordinates', 'tznew_fix_acf_polygon_coordinates_field', 5, 3);

// Additional filter to handle field preparation
add_filter('acf/prepare_field/name=polygon_coordinates', function($field) {
    // Ensure the field value is always a string for textarea rendering
    if (isset($field['value']) && is_array($field['value'])) {
        $field['value'] = json_encode($field['value']);
    } elseif (empty($field['value'])) {
        $field['value'] = '[]';
    }
    return $field;
}, 5);

// Early filter to catch any remaining array values before they reach htmlspecialchars
add_filter('acf/render_field/type=textarea', function($field) {
    if (isset($field['name']) && $field['name'] === 'polygon_coordinates' && is_array($field['value'])) {
        $field['value'] = json_encode($field['value']);
    }
    return $field;
}, 1);