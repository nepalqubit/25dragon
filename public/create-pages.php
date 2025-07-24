<?php
/**
 * Simple script to create booking and inquiry pages
 * Run this script directly to create the required pages
 */

// Load WordPress without themes
define('WP_USE_THEMES', false);
require_once('wp-load.php');

echo '<h1>Creating Required Pages</h1>';

// Create Booking Page
$booking_page = get_page_by_path('booking');
if (!$booking_page) {
    $booking_page_id = wp_insert_post(array(
        'post_title' => 'Booking',
        'post_content' => '',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_name' => 'booking',
        'post_author' => 1
    ));
    
    if ($booking_page_id && !is_wp_error($booking_page_id)) {
        update_post_meta($booking_page_id, '_wp_page_template', 'page-booking.php');
        echo '<p style="color: green;">✓ Booking page created successfully (ID: ' . $booking_page_id . ')</p>';
    } else {
        echo '<p style="color: red;">✗ Failed to create booking page: ' . (is_wp_error($booking_page_id) ? $booking_page_id->get_error_message() : 'Unknown error') . '</p>';
    }
} else {
    echo '<p style="color: blue;">ℹ Booking page already exists (ID: ' . $booking_page->ID . ')</p>';
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
        'post_author' => 1
    ));
    
    if ($inquiry_page_id && !is_wp_error($inquiry_page_id)) {
        update_post_meta($inquiry_page_id, '_wp_page_template', 'page-inquiry.php');
        echo '<p style="color: green;">✓ Inquiry page created successfully (ID: ' . $inquiry_page_id . ')</p>';
    } else {
        echo '<p style="color: red;">✗ Failed to create inquiry page: ' . (is_wp_error($inquiry_page_id) ? $inquiry_page_id->get_error_message() : 'Unknown error') . '</p>';
    }
} else {
    echo '<p style="color: blue;">ℹ Inquiry page already exists (ID: ' . $inquiry_page->ID . ')</p>';
}

// Flush rewrite rules
flush_rewrite_rules();
echo '<p style="color: green;">✓ Rewrite rules flushed</p>';

echo '<h2>Test Links:</h2>';
echo '<ul>';
echo '<li><a href="' . home_url('/booking') . '" target="_blank">Test Booking Page</a></li>';
echo '<li><a href="' . home_url('/inquiry') . '" target="_blank">Test Inquiry Page</a></li>';
echo '</ul>';

echo '<p><strong>Note:</strong> If pages still show 404, go to WordPress Admin → Settings → Permalinks and click "Save Changes".</p>';
?>