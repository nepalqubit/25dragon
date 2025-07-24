<?php
/**
 * Custom Post Types for TZnew Theme
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
 * Register Custom Post Types
 */
function tznew_register_post_types() {
    
    // Trekking Post Type
    $trekking_labels = array(
        'name'                  => _x('Trekking', 'Post type general name', 'tznew'),
        'singular_name'         => _x('Trekking', 'Post type singular name', 'tznew'),
        'menu_name'             => _x('Trekking', 'Admin Menu text', 'tznew'),
        'name_admin_bar'        => _x('Trekking', 'Add New on Toolbar', 'tznew'),
        'add_new'               => __('Add New', 'tznew'),
        'add_new_item'          => __('Add New Trekking', 'tznew'),
        'new_item'              => __('New Trekking', 'tznew'),
        'edit_item'             => __('Edit Trekking', 'tznew'),
        'view_item'             => __('View Trekking', 'tznew'),
        'all_items'             => __('All Trekking', 'tznew'),
        'search_items'          => __('Search Trekking', 'tznew'),
        'parent_item_colon'     => __('Parent Trekking:', 'tznew'),
        'not_found'             => __('No trekking found.', 'tznew'),
        'not_found_in_trash'    => __('No trekking found in Trash.', 'tznew'),
        'featured_image'        => __('Trekking Cover Image', 'tznew'),
        'set_featured_image'    => __('Set cover image', 'tznew'),
        'remove_featured_image' => __('Remove cover image', 'tznew'),
        'use_featured_image'    => __('Use as cover image', 'tznew'),
        'archives'              => __('Trekking archives', 'tznew'),
        'insert_into_item'      => __('Insert into trekking', 'tznew'),
        'uploaded_to_this_item' => __('Uploaded to this trekking', 'tznew'),
        'filter_items_list'     => __('Filter trekking list', 'tznew'),
        'items_list_navigation' => __('Trekking list navigation', 'tznew'),
        'items_list'            => __('Trekking list', 'tznew'),
    );

    $trekking_args = array(
        'labels'             => $trekking_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'trekking'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-location-alt',
        'supports'           => array('title', 'editor', 'thumbnail', 'elementor'),
        'show_in_rest'       => true,
        'rest_base'          => 'trekking',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    );

    register_post_type('trekking', $trekking_args);

    // Tours Post Type
    $tours_labels = array(
        'name'                  => _x('Tours', 'Post type general name', 'tznew'),
        'singular_name'         => _x('Tour', 'Post type singular name', 'tznew'),
        'menu_name'             => _x('Tours', 'Admin Menu text', 'tznew'),
        'name_admin_bar'        => _x('Tour', 'Add New on Toolbar', 'tznew'),
        'add_new'               => __('Add New', 'tznew'),
        'add_new_item'          => __('Add New Tour', 'tznew'),
        'new_item'              => __('New Tour', 'tznew'),
        'edit_item'             => __('Edit Tour', 'tznew'),
        'view_item'             => __('View Tour', 'tznew'),
        'all_items'             => __('All Tours', 'tznew'),
        'search_items'          => __('Search Tours', 'tznew'),
        'parent_item_colon'     => __('Parent Tour:', 'tznew'),
        'not_found'             => __('No tours found.', 'tznew'),
        'not_found_in_trash'    => __('No tours found in Trash.', 'tznew'),
        'featured_image'        => __('Tour Cover Image', 'tznew'),
        'set_featured_image'    => __('Set cover image', 'tznew'),
        'remove_featured_image' => __('Remove cover image', 'tznew'),
        'use_featured_image'    => __('Use as cover image', 'tznew'),
        'archives'              => __('Tour archives', 'tznew'),
        'insert_into_item'      => __('Insert into tour', 'tznew'),
        'uploaded_to_this_item' => __('Uploaded to this tour', 'tznew'),
        'filter_items_list'     => __('Filter tours list', 'tznew'),
        'items_list_navigation' => __('Tours list navigation', 'tznew'),
        'items_list'            => __('Tours list', 'tznew'),
    );

    $tours_args = array(
        'labels'             => $tours_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'tours'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-car',
        'supports'           => array('title', 'editor', 'thumbnail', 'elementor'),
        'show_in_rest'       => true,
        'rest_base'          => 'tours',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    );

    register_post_type('tours', $tours_args);

    // FAQ Post Type
    $faq_labels = array(
        'name'                  => _x('FAQs', 'Post type general name', 'tznew'),
        'singular_name'         => _x('FAQ', 'Post type singular name', 'tznew'),
        'menu_name'             => _x('FAQs', 'Admin Menu text', 'tznew'),
        'name_admin_bar'        => _x('FAQ', 'Add New on Toolbar', 'tznew'),
        'add_new'               => __('Add New', 'tznew'),
        'add_new_item'          => __('Add New FAQ', 'tznew'),
        'new_item'              => __('New FAQ', 'tznew'),
        'edit_item'             => __('Edit FAQ', 'tznew'),
        'view_item'             => __('View FAQ', 'tznew'),
        'all_items'             => __('All FAQs', 'tznew'),
        'search_items'          => __('Search FAQs', 'tznew'),
        'parent_item_colon'     => __('Parent FAQ:', 'tznew'),
        'not_found'             => __('No FAQs found.', 'tznew'),
        'not_found_in_trash'    => __('No FAQs found in Trash.', 'tznew'),
        'archives'              => __('FAQ archives', 'tznew'),
        'insert_into_item'      => __('Insert into FAQ', 'tznew'),
        'uploaded_to_this_item' => __('Uploaded to this FAQ', 'tznew'),
        'filter_items_list'     => __('Filter FAQs list', 'tznew'),
        'items_list_navigation' => __('FAQs list navigation', 'tznew'),
        'items_list'            => __('FAQs list', 'tznew'),
    );

    $faq_args = array(
        'labels'             => $faq_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'faqs'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 7,
        'menu_icon'          => 'dashicons-format-chat',
        'supports'           => array('title', 'editor', 'elementor'),
        'show_in_rest'       => true,
        'rest_base'          => 'faq',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    );

    register_post_type('faq', $faq_args);

    // Blog Post Type
    $blog_labels = array(
        'name'                  => _x('Blogs', 'Post type general name', 'tznew'),
        'singular_name'         => _x('Blog', 'Post type singular name', 'tznew'),
        'menu_name'             => _x('Blogs', 'Admin Menu text', 'tznew'),
        'name_admin_bar'        => _x('Blog', 'Add New on Toolbar', 'tznew'),
        'add_new'               => __('Add New', 'tznew'),
        'add_new_item'          => __('Add New Blog', 'tznew'),
        'new_item'              => __('New Blog', 'tznew'),
        'edit_item'             => __('Edit Blog', 'tznew'),
        'view_item'             => __('View Blog', 'tznew'),
        'all_items'             => __('All Blogs', 'tznew'),
        'search_items'          => __('Search Blogs', 'tznew'),
        'parent_item_colon'     => __('Parent Blog:', 'tznew'),
        'not_found'             => __('No blogs found.', 'tznew'),
        'not_found_in_trash'    => __('No blogs found in Trash.', 'tznew'),
        'featured_image'        => __('Blog Cover Image', 'tznew'),
        'set_featured_image'    => __('Set cover image', 'tznew'),
        'remove_featured_image' => __('Remove cover image', 'tznew'),
        'use_featured_image'    => __('Use as cover image', 'tznew'),
        'archives'              => __('Blog archives', 'tznew'),
        'insert_into_item'      => __('Insert into blog', 'tznew'),
        'uploaded_to_this_item' => __('Uploaded to this blog', 'tznew'),
        'filter_items_list'     => __('Filter blogs list', 'tznew'),
        'items_list_navigation' => __('Blogs list navigation', 'tznew'),
        'items_list'            => __('Blogs list', 'tznew'),
    );

    $blog_args = array(
        'labels'             => $blog_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'blogs'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 8,
        'menu_icon'          => 'dashicons-welcome-write-blog',
        'supports'           => array('title', 'editor', 'thumbnail', 'elementor'),
        'show_in_rest'       => true,
        'rest_base'          => 'blog',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    );

    register_post_type('blog', $blog_args);
}
add_action('init', 'tznew_register_post_types');

/**
 * Register Custom Taxonomies
 */
function tznew_register_taxonomies() {
    // Region Taxonomy for Trekking and Tours
    $region_labels = array(
        'name'              => _x('Regions', 'taxonomy general name', 'tznew'),
        'singular_name'     => _x('Region', 'taxonomy singular name', 'tznew'),
        'search_items'      => __('Search Regions', 'tznew'),
        'all_items'         => __('All Regions', 'tznew'),
        'parent_item'       => __('Parent Region', 'tznew'),
        'parent_item_colon' => __('Parent Region:', 'tznew'),
        'edit_item'         => __('Edit Region', 'tznew'),
        'update_item'       => __('Update Region', 'tznew'),
        'add_new_item'      => __('Add New Region', 'tznew'),
        'new_item_name'     => __('New Region Name', 'tznew'),
        'menu_name'         => __('Regions', 'tznew'),
    );

    $region_args = array(
        'hierarchical'      => true,
        'labels'            => $region_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'region'),
        'show_in_rest'      => true,
        'rest_base'         => 'region',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
    );

    register_taxonomy('region', array('trekking', 'tours'), $region_args);

    // Difficulty Taxonomy for Trekking
    $difficulty_labels = array(
        'name'              => _x('Difficulties', 'taxonomy general name', 'tznew'),
        'singular_name'     => _x('Difficulty', 'taxonomy singular name', 'tznew'),
        'search_items'      => __('Search Difficulties', 'tznew'),
        'all_items'         => __('All Difficulties', 'tznew'),
        'parent_item'       => __('Parent Difficulty', 'tznew'),
        'parent_item_colon' => __('Parent Difficulty:', 'tznew'),
        'edit_item'         => __('Edit Difficulty', 'tznew'),
        'update_item'       => __('Update Difficulty', 'tznew'),
        'add_new_item'      => __('Add New Difficulty', 'tznew'),
        'new_item_name'     => __('New Difficulty Name', 'tznew'),
        'menu_name'         => __('Difficulties', 'tznew'),
    );

    $difficulty_args = array(
        'hierarchical'      => false,
        'labels'            => $difficulty_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'difficulty'),
        'show_in_rest'      => true,
        'rest_base'         => 'difficulty',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
    );

    register_taxonomy('difficulty', array('trekking'), $difficulty_args);

    // Tour Type Taxonomy for Tours
    $tour_type_labels = array(
        'name'              => _x('Tour Types', 'taxonomy general name', 'tznew'),
        'singular_name'     => _x('Tour Type', 'taxonomy singular name', 'tznew'),
        'search_items'      => __('Search Tour Types', 'tznew'),
        'all_items'         => __('All Tour Types', 'tznew'),
        'parent_item'       => __('Parent Tour Type', 'tznew'),
        'parent_item_colon' => __('Parent Tour Type:', 'tznew'),
        'edit_item'         => __('Edit Tour Type', 'tznew'),
        'update_item'       => __('Update Tour Type', 'tznew'),
        'add_new_item'      => __('Add New Tour Type', 'tznew'),
        'new_item_name'     => __('New Tour Type Name', 'tznew'),
        'menu_name'         => __('Tour Types', 'tznew'),
    );

    $tour_type_args = array(
        'hierarchical'      => false,
        'labels'            => $tour_type_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'tour-type'),
        'show_in_rest'      => true,
        'rest_base'         => 'tour_type',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
    );

    register_taxonomy('tour_type', array('tours'), $tour_type_args);

    // Tags Taxonomy for Blog
    $tag_labels = array(
        'name'              => _x('Tags', 'taxonomy general name', 'tznew'),
        'singular_name'     => _x('Tag', 'taxonomy singular name', 'tznew'),
        'search_items'      => __('Search Tags', 'tznew'),
        'all_items'         => __('All Tags', 'tznew'),
        'parent_item'       => __('Parent Tag', 'tznew'),
        'parent_item_colon' => __('Parent Tag:', 'tznew'),
        'edit_item'         => __('Edit Tag', 'tznew'),
        'update_item'       => __('Update Tag', 'tznew'),
        'add_new_item'      => __('Add New Tag', 'tznew'),
        'new_item_name'     => __('New Tag Name', 'tznew'),
        'menu_name'         => __('Tags', 'tznew'),
    );

    $tag_args = array(
        'hierarchical'      => false,
        'labels'            => $tag_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'blog-tag'),
        'show_in_rest'      => true,
        'rest_base'         => 'acf_tag',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
    );

    register_taxonomy('acf_tag', array('blog'), $tag_args);

    // FAQ Category Taxonomy for FAQ
    $faq_category_labels = array(
        'name'              => _x('FAQ Categories', 'taxonomy general name', 'tznew'),
        'singular_name'     => _x('FAQ Category', 'taxonomy singular name', 'tznew'),
        'search_items'      => __('Search FAQ Categories', 'tznew'),
        'all_items'         => __('All FAQ Categories', 'tznew'),
        'parent_item'       => __('Parent FAQ Category', 'tznew'),
        'parent_item_colon' => __('Parent FAQ Category:', 'tznew'),
        'edit_item'         => __('Edit FAQ Category', 'tznew'),
        'update_item'       => __('Update FAQ Category', 'tznew'),
        'add_new_item'      => __('Add New FAQ Category', 'tznew'),
        'new_item_name'     => __('New FAQ Category Name', 'tznew'),
        'menu_name'         => __('FAQ Categories', 'tznew'),
    );

    $faq_category_args = array(
        'hierarchical'      => true,
        'labels'            => $faq_category_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'faq-category'),
        'show_in_rest'      => true,
        'rest_base'         => 'faq_category',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
    );

    register_taxonomy('faq_category', array('faq'), $faq_category_args);
}
add_action('init', 'tznew_register_taxonomies');

/**
 * Flush rewrite rules on theme activation
 */
function tznew_rewrite_flush() {
    tznew_register_post_types();
    tznew_register_taxonomies();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'tznew_rewrite_flush');