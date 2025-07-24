<?php
/**
 * Plugin Name: Create Required Pages
 * Description: Creates booking and inquiry pages for the theme
 * Version: 1.0
 * Author: TechZen Inc
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Hook into plugin activation
register_activation_hook(__FILE__, 'create_required_pages_activation');

function create_required_pages_activation() {
    // Create Booking Page
    $booking_page = get_page_by_path('booking');
    if (!$booking_page) {
        $booking_page_data = array(
            'post_title'    => 'Booking',
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'booking',
            'post_author'   => 1,
            'comment_status' => 'closed',
            'ping_status'   => 'closed'
        );
        
        $booking_id = wp_insert_post($booking_page_data);
        
        if ($booking_id && !is_wp_error($booking_id)) {
            // Set the page template
            update_post_meta($booking_id, '_wp_page_template', 'page-booking.php');
            error_log('Booking page created with ID: ' . $booking_id);
        } else {
            error_log('Failed to create booking page: ' . print_r($booking_id, true));
        }
    } else {
        error_log('Booking page already exists with ID: ' . $booking_page->ID);
    }
    
    // Create Inquiry Page
    $inquiry_page = get_page_by_path('inquiry');
    if (!$inquiry_page) {
        $inquiry_page_data = array(
            'post_title'    => 'Inquiry',
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'inquiry',
            'post_author'   => 1,
            'comment_status' => 'closed',
            'ping_status'   => 'closed'
        );
        
        $inquiry_id = wp_insert_post($inquiry_page_data);
        
        if ($inquiry_id && !is_wp_error($inquiry_id)) {
            // Set the page template
            update_post_meta($inquiry_id, '_wp_page_template', 'page-inquiry.php');
            error_log('Inquiry page created with ID: ' . $inquiry_id);
        } else {
            error_log('Failed to create inquiry page: ' . print_r($inquiry_id, true));
        }
    } else {
        error_log('Inquiry page already exists with ID: ' . $inquiry_page->ID);
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
    
    error_log('Page creation plugin activated successfully');
}

// Add admin notice
add_action('admin_notices', 'create_pages_admin_notice');

function create_pages_admin_notice() {
    $booking_page = get_page_by_path('booking');
    $inquiry_page = get_page_by_path('inquiry');
    
    if ($booking_page && $inquiry_page) {
        echo '<div class="notice notice-success is-dismissible">';
        echo '<p><strong>Success!</strong> Booking and Inquiry pages have been created successfully.</p>';
        echo '<p>Booking Page: <a href="' . get_permalink($booking_page->ID) . '" target="_blank">' . get_permalink($booking_page->ID) . '</a></p>';
        echo '<p>Inquiry Page: <a href="' . get_permalink($inquiry_page->ID) . '" target="_blank">' . get_permalink($inquiry_page->ID) . '</a></p>';
        echo '</div>';
    }
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'create_required_pages_deactivation');

function create_required_pages_deactivation() {
    // Optionally remove pages on deactivation
    // Commented out to preserve pages
    /*
    $booking_page = get_page_by_path('booking');
    if ($booking_page) {
        wp_delete_post($booking_page->ID, true);
    }
    
    $inquiry_page = get_page_by_path('inquiry');
    if ($inquiry_page) {
        wp_delete_post($inquiry_page->ID, true);
    }
    */
}
?>