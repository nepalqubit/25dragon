<?php
/**
 * REST API Integration for TZnew Theme
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
 * Enable REST API for custom post types
 */
function tznew_enable_rest_api_for_post_types() {
    // Get all registered post types
    $post_types = get_post_types(array('_builtin' => false), 'objects');
    
    // Loop through each post type and update show_in_rest
    foreach ($post_types as $post_type) {
        // Skip post types that are not from our theme
        if (!in_array($post_type->name, array('trekking', 'tours', 'faq', 'blog'))) {
            continue;
        }
        
        // Update post type to show in REST API
        $post_type->show_in_rest = true;
        $post_type->rest_base = $post_type->name;
        $post_type->rest_controller_class = 'WP_REST_Posts_Controller';
    }
}
add_action('init', 'tznew_enable_rest_api_for_post_types', 11); // Priority 11 to run after post types are registered

/**
 * Enable REST API for custom taxonomies
 */
function tznew_enable_rest_api_for_taxonomies() {
    // Get all registered taxonomies
    $taxonomies = get_taxonomies(array('_builtin' => false), 'objects');
    
    // Loop through each taxonomy and update show_in_rest
    foreach ($taxonomies as $taxonomy) {
        // Skip taxonomies that are not from our theme
        if (!in_array($taxonomy->name, array('region', 'difficulty', 'tour_type', 'acf_tag', 'faq_category'))) {
            continue;
        }
        
        // Update taxonomy to show in REST API
        $taxonomy->show_in_rest = true;
        $taxonomy->rest_base = $taxonomy->name;
        $taxonomy->rest_controller_class = 'WP_REST_Terms_Controller';
    }
}
add_action('init', 'tznew_enable_rest_api_for_taxonomies', 11); // Priority 11 to run after taxonomies are registered

/**
 * Register ACF fields to REST API
 */
function tznew_register_acf_fields_to_rest_api() {
    // Check if ACF is active
    if (!function_exists('get_fields')) {
        return;
    }
    
    // Register ACF fields for Trekking post type
    register_rest_field('trekking', 'acf', array(
        'get_callback' => 'tznew_get_acf_fields',
        'schema' => null,
    ));
    
    // Register ACF fields for Tours post type
    register_rest_field('tours', 'acf', array(
        'get_callback' => 'tznew_get_acf_fields',
        'schema' => null,
    ));
    
    // Register ACF fields for FAQ post type
    register_rest_field('faq', 'acf', array(
        'get_callback' => 'tznew_get_acf_fields',
        'schema' => null,
    ));
    
    // Register ACF fields for Blog post type
    register_rest_field('blog', 'acf', array(
        'get_callback' => 'tznew_get_acf_fields',
        'schema' => null,
    ));
}
add_action('rest_api_init', 'tznew_register_acf_fields_to_rest_api');

/**
 * Get ACF fields for REST API
 *
 * @param array $object The object details
 * @return array ACF fields
 */
function tznew_get_acf_fields($object) {
    $post_id = $object['id'];
    
    // Get all ACF fields for the post
    $fields = get_fields($post_id);
    
    // If no fields, return empty array
    if (!$fields) {
        return array();
    }
    
    // Process fields to ensure they're properly formatted for REST API
    foreach ($fields as $key => $value) {
        // Handle image fields (convert to URL and metadata)
        if (is_array($value) && isset($value['type']) && $value['type'] === 'image') {
            $fields[$key] = array(
                'url' => $value['url'],
                'alt' => $value['alt'],
                'title' => $value['title'],
                'width' => $value['width'],
                'height' => $value['height'],
            );
        }
        
        // Handle WYSIWYG fields (apply filters)
        if (is_string($value) && (
            strpos($key, 'overview') !== false || 
            strpos($key, 'includes') !== false || 
            strpos($key, 'excludes') !== false || 
            strpos($key, 'content') !== false || 
            strpos($key, 'answer') !== false
        )) {
            $fields[$key] = apply_filters('the_content', $value);
        }
    }
    
    return $fields;
}

/**
 * Add featured image to REST API
 */
function tznew_register_featured_image_to_rest_api() {
    // Register for all custom post types
    $post_types = array('trekking', 'tours', 'blog');
    
    foreach ($post_types as $post_type) {
        register_rest_field($post_type, 'featured_image', array(
            'get_callback' => 'tznew_get_featured_image',
            'schema' => null,
        ));
    }
}
add_action('rest_api_init', 'tznew_register_featured_image_to_rest_api');

/**
 * Get featured image for REST API
 *
 * @param array $object The object details
 * @return array Featured image details
 */
function tznew_get_featured_image($object) {
    $post_id = $object['id'];
    
    // Get featured image ID
    $featured_image_id = get_post_thumbnail_id($post_id);
    
    if (!$featured_image_id) {
        return null;
    }
    
    // Get image sizes
    $image_sizes = get_intermediate_image_sizes();
    $image_data = array();
    
    // Get URL for each image size
    foreach ($image_sizes as $size) {
        $image = wp_get_attachment_image_src($featured_image_id, $size);
        if ($image) {
            $image_data[$size] = array(
                'url' => $image[0],
                'width' => $image[1],
                'height' => $image[2],
            );
        }
    }
    
    // Get full size image
    $full_image = wp_get_attachment_image_src($featured_image_id, 'full');
    if ($full_image) {
        $image_data['full'] = array(
            'url' => $full_image[0],
            'width' => $full_image[1],
            'height' => $full_image[2],
        );
    }
    
    // Get alt text
    $image_data['alt'] = get_post_meta($featured_image_id, '_wp_attachment_image_alt', true);
    
    return $image_data;
}