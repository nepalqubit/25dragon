<?php
/**
 * Complete Booking System for Trekking and Tours
 *
 * @package TZnew
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class TZnew_Booking_System {
    
    public function __construct() {
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_booking_meta'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_update_booking_status', array($this, 'update_booking_status'));
        add_action('wp_ajax_send_booking_email', array($this, 'send_booking_email'));
        add_filter('manage_booking_posts_columns', array($this, 'booking_columns'));
        add_action('manage_booking_posts_custom_column', array($this, 'booking_column_content'), 10, 2);
        add_filter('manage_inquiry_posts_columns', array($this, 'inquiry_columns'));
        add_action('manage_inquiry_posts_custom_column', array($this, 'inquiry_column_content'), 10, 2);
        
        // Enhanced booking submission handler
        add_action('wp_ajax_tznew_submit_booking', array($this, 'handle_booking_submission'));
        add_action('wp_ajax_nopriv_tznew_submit_booking', array($this, 'handle_booking_submission'));
        
        // Enhanced inquiry submission handler
        add_action('wp_ajax_tznew_submit_inquiry', array($this, 'handle_inquiry_submission'));
        add_action('wp_ajax_nopriv_tznew_submit_inquiry', array($this, 'handle_inquiry_submission'));
        
        // Booking review submission handler
        add_action('admin_post_submit_booking_review', array($this, 'handle_booking_review'));
    }
    
    /**
     * Register custom post types for bookings and inquiries
     */
    public function register_post_types() {
        // Booking post type
        register_post_type('booking', array(
            'labels' => array(
                'name' => __('Bookings', 'tznew'),
                'singular_name' => __('Booking', 'tznew'),
                'menu_name' => __('Bookings', 'tznew'),
                'add_new' => __('Add New Booking', 'tznew'),
                'add_new_item' => __('Add New Booking', 'tznew'),
                'edit_item' => __('Edit Booking', 'tznew'),
                'new_item' => __('New Booking', 'tznew'),
                'view_item' => __('View Booking', 'tznew'),
                'search_items' => __('Search Bookings', 'tznew'),
                'not_found' => __('No bookings found', 'tznew'),
                'not_found_in_trash' => __('No bookings found in trash', 'tznew'),
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false, // We'll add custom menu
            'capability_type' => 'post',
            'capabilities' => array(
                'create_posts' => 'manage_options',
                'edit_posts' => 'manage_options',
                'edit_others_posts' => 'manage_options',
                'publish_posts' => 'manage_options',
                'read_private_posts' => 'manage_options',
            ),
            'supports' => array('title', 'editor', 'custom-fields'),
            'has_archive' => false,
            'rewrite' => false,
        ));
        
        // Inquiry post type
        register_post_type('inquiry', array(
            'labels' => array(
                'name' => __('Inquiries', 'tznew'),
                'singular_name' => __('Inquiry', 'tznew'),
                'menu_name' => __('Inquiries', 'tznew'),
                'add_new' => __('Add New Inquiry', 'tznew'),
                'add_new_item' => __('Add New Inquiry', 'tznew'),
                'edit_item' => __('Edit Inquiry', 'tznew'),
                'new_item' => __('New Inquiry', 'tznew'),
                'view_item' => __('View Inquiry', 'tznew'),
                'search_items' => __('Search Inquiries', 'tznew'),
                'not_found' => __('No inquiries found', 'tznew'),
                'not_found_in_trash' => __('No inquiries found in trash', 'tznew'),
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false, // We'll add custom menu
            'capability_type' => 'post',
            'capabilities' => array(
                'create_posts' => 'manage_options',
                'edit_posts' => 'manage_options',
                'edit_others_posts' => 'manage_options',
                'publish_posts' => 'manage_options',
                'read_private_posts' => 'manage_options',
            ),
            'supports' => array('title', 'editor', 'custom-fields'),
            'has_archive' => false,
            'rewrite' => false,
        ));
    }
    
    /**
     * Register taxonomies for booking status and inquiry types
     */
    public function register_taxonomies() {
        // Booking status taxonomy
        register_taxonomy('booking_status', 'booking', array(
            'labels' => array(
                'name' => __('Booking Status', 'tznew'),
                'singular_name' => __('Status', 'tznew'),
            ),
            'public' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'hierarchical' => false,
            'rewrite' => false,
        ));
        
        // Create default booking statuses
        $this->create_default_booking_statuses();
        
        // Inquiry status taxonomy
        register_taxonomy('inquiry_status', 'inquiry', array(
            'labels' => array(
                'name' => __('Inquiry Status', 'tznew'),
                'singular_name' => __('Status', 'tznew'),
            ),
            'public' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'hierarchical' => false,
            'rewrite' => false,
        ));
        
        // Create default inquiry statuses
        $this->create_default_inquiry_statuses();
    }
    
    /**
     * Create default booking statuses
     */
    private function create_default_booking_statuses() {
        $statuses = array(
            'pending' => __('Pending Review', 'tznew'),
            'confirmed' => __('Confirmed', 'tznew'),
            'cancelled' => __('Cancelled', 'tznew'),
            'completed' => __('Completed', 'tznew'),
            'in_progress' => __('In Progress', 'tznew'),
            'payment_pending' => __('Payment Pending', 'tznew'),
        );
        
        foreach ($statuses as $slug => $name) {
            if (!term_exists($slug, 'booking_status')) {
                wp_insert_term($name, 'booking_status', array('slug' => $slug));
            }
        }
    }
    
    /**
     * Create default inquiry statuses
     */
    private function create_default_inquiry_statuses() {
        $statuses = array(
            'new' => __('New', 'tznew'),
            'replied' => __('Replied', 'tznew'),
            'closed' => __('Closed', 'tznew'),
            'follow_up' => __('Follow Up Required', 'tznew'),
        );
        
        foreach ($statuses as $slug => $name) {
            if (!term_exists($slug, 'inquiry_status')) {
                wp_insert_term($name, 'inquiry_status', array('slug' => $slug));
            }
        }
    }
    
    /**
     * Add meta boxes for booking and inquiry details
     */
    public function add_meta_boxes() {
        // Booking meta boxes
        add_meta_box(
            'booking_details',
            __('Booking Details', 'tznew'),
            array($this, 'booking_details_meta_box'),
            'booking',
            'normal',
            'high'
        );
        
        add_meta_box(
            'booking_customer',
            __('Customer Information', 'tznew'),
            array($this, 'booking_customer_meta_box'),
            'booking',
            'side',
            'high'
        );
        
        add_meta_box(
            'booking_actions',
            __('Booking Actions', 'tznew'),
            array($this, 'booking_actions_meta_box'),
            'booking',
            'side',
            'default'
        );
        
        // Inquiry meta boxes
        add_meta_box(
            'inquiry_details',
            __('Inquiry Details', 'tznew'),
            array($this, 'inquiry_details_meta_box'),
            'inquiry',
            'normal',
            'high'
        );
        
        add_meta_box(
            'inquiry_customer',
            __('Customer Information', 'tznew'),
            array($this, 'inquiry_customer_meta_box'),
            'inquiry',
            'side',
            'high'
        );
        
        add_meta_box(
            'inquiry_actions',
            __('Inquiry Actions', 'tznew'),
            array($this, 'inquiry_actions_meta_box'),
            'inquiry',
            'side',
            'default'
        );
    }
    
    /**
     * Booking details meta box
     */
    public function booking_details_meta_box($post) {
        wp_nonce_field('booking_meta_nonce', 'booking_meta_nonce');
        
        $trip_title = get_post_meta($post->ID, '_trip_title', true);
        $trip_id = get_post_meta($post->ID, '_trip_id', true);
        $trip_type = get_post_meta($post->ID, '_trip_type', true);
        $preferred_date = get_post_meta($post->ID, '_preferred_date', true);
        $group_size = get_post_meta($post->ID, '_group_size', true);
        $accommodation = get_post_meta($post->ID, '_accommodation_preference', true);
        $dietary_requirements = get_post_meta($post->ID, '_dietary_requirements', true);
        $special_requests = get_post_meta($post->ID, '_special_requests', true);
        $total_cost = get_post_meta($post->ID, '_total_cost', true);
        $deposit_paid = get_post_meta($post->ID, '_deposit_paid', true);
        $balance_due = get_post_meta($post->ID, '_balance_due', true);
        
        echo '<table class="form-table">';
        echo '<tr><th><label for="trip_title">' . __('Trip Title', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="trip_title" name="trip_title" value="' . esc_attr($trip_title) . '" class="regular-text" readonly /></td></tr>';
        
        echo '<tr><th><label for="trip_type">' . __('Trip Type', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="trip_type" name="trip_type" value="' . esc_attr($trip_type) . '" class="regular-text" readonly /></td></tr>';
        
        echo '<tr><th><label for="preferred_date">' . __('Preferred Date', 'tznew') . '</label></th>';
        echo '<td><input type="date" id="preferred_date" name="preferred_date" value="' . esc_attr($preferred_date) . '" class="regular-text" /></td></tr>';
        
        echo '<tr><th><label for="group_size">' . __('Group Size', 'tznew') . '</label></th>';
        echo '<td><input type="number" id="group_size" name="group_size" value="' . esc_attr($group_size) . '" class="small-text" min="1" max="50" /></td></tr>';
        
        echo '<tr><th><label for="accommodation">' . __('Accommodation Preference', 'tznew') . '</label></th>';
        echo '<td><select id="accommodation" name="accommodation" class="regular-text">';
        $accommodations = array('standard' => 'Standard', 'deluxe' => 'Deluxe', 'luxury' => 'Luxury', 'camping' => 'Camping');
        foreach ($accommodations as $value => $label) {
            echo '<option value="' . esc_attr($value) . '"' . selected($accommodation, $value, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select></td></tr>';
        
        echo '<tr><th><label for="dietary_requirements">' . __('Dietary Requirements', 'tznew') . '</label></th>';
        echo '<td><textarea id="dietary_requirements" name="dietary_requirements" rows="3" class="large-text">' . esc_textarea($dietary_requirements) . '</textarea></td></tr>';
        
        echo '<tr><th><label for="special_requests">' . __('Special Requests', 'tznew') . '</label></th>';
        echo '<td><textarea id="special_requests" name="special_requests" rows="3" class="large-text">' . esc_textarea($special_requests) . '</textarea></td></tr>';
        
        echo '<tr><th><label for="total_cost">' . __('Total Cost (USD)', 'tznew') . '</label></th>';
        echo '<td><input type="number" id="total_cost" name="total_cost" value="' . esc_attr($total_cost) . '" class="regular-text" step="0.01" /></td></tr>';
        
        echo '<tr><th><label for="deposit_paid">' . __('Deposit Paid (USD)', 'tznew') . '</label></th>';
        echo '<td><input type="number" id="deposit_paid" name="deposit_paid" value="' . esc_attr($deposit_paid) . '" class="regular-text" step="0.01" /></td></tr>';
        
        echo '<tr><th><label for="balance_due">' . __('Balance Due (USD)', 'tznew') . '</label></th>';
        echo '<td><input type="number" id="balance_due" name="balance_due" value="' . esc_attr($balance_due) . '" class="regular-text" step="0.01" /></td></tr>';
        
        echo '</table>';
    }
    
    /**
     * Booking customer meta box
     */
    public function booking_customer_meta_box($post) {
        $first_name = get_post_meta($post->ID, '_first_name', true);
        $last_name = get_post_meta($post->ID, '_last_name', true);
        $email = get_post_meta($post->ID, '_email', true);
        $phone = get_post_meta($post->ID, '_phone', true);
        $country = get_post_meta($post->ID, '_country', true);
        $submission_date = get_post_meta($post->ID, '_submission_date', true);
        
        echo '<table class="form-table">';
        echo '<tr><th><label for="first_name">' . __('First Name', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="first_name" name="first_name" value="' . esc_attr($first_name) . '" class="widefat" /></td></tr>';
        
        echo '<tr><th><label for="last_name">' . __('Last Name', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="last_name" name="last_name" value="' . esc_attr($last_name) . '" class="widefat" /></td></tr>';
        
        echo '<tr><th><label for="email">' . __('Email', 'tznew') . '</label></th>';
        echo '<td><input type="email" id="email" name="email" value="' . esc_attr($email) . '" class="widefat" /></td></tr>';
        
        echo '<tr><th><label for="phone">' . __('Phone', 'tznew') . '</label></th>';
        echo '<td><input type="tel" id="phone" name="phone" value="' . esc_attr($phone) . '" class="widefat" /></td></tr>';
        
        echo '<tr><th><label for="country">' . __('Country', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="country" name="country" value="' . esc_attr($country) . '" class="widefat" /></td></tr>';
        
        if ($submission_date) {
            echo '<tr><th>' . __('Submitted', 'tznew') . '</th>';
            echo '<td>' . esc_html(date('F j, Y g:i A', strtotime($submission_date))) . '</td></tr>';
        }
        
        echo '</table>';
    }
    
    /**
     * Booking actions meta box
     */
    public function booking_actions_meta_box($post) {
        $status_terms = wp_get_post_terms($post->ID, 'booking_status');
        $current_status = !empty($status_terms) ? $status_terms[0]->slug : 'pending';
        
        echo '<div class="booking-actions">';
        echo '<p><strong>' . __('Quick Actions', 'tznew') . '</strong></p>';
        
        // Status update
        echo '<p><label for="booking_status_select">' . __('Update Status:', 'tznew') . '</label><br>';
        echo '<select id="booking_status_select" name="booking_status" class="widefat">';
        $statuses = get_terms(array('taxonomy' => 'booking_status', 'hide_empty' => false));
        foreach ($statuses as $status) {
            echo '<option value="' . esc_attr($status->slug) . '"' . selected($current_status, $status->slug, false) . '>' . esc_html($status->name) . '</option>';
        }
        echo '</select></p>';
        
        // Email actions
        echo '<p><strong>' . __('Email Actions', 'tznew') . '</strong></p>';
        echo '<button type="button" class="button send-confirmation-email" data-booking-id="' . $post->ID . '">' . __('Send Confirmation', 'tznew') . '</button><br><br>';
        echo '<button type="button" class="button send-quote-email" data-booking-id="' . $post->ID . '">' . __('Send Quote', 'tznew') . '</button><br><br>';
        echo '<button type="button" class="button send-reminder-email" data-booking-id="' . $post->ID . '">' . __('Send Reminder', 'tznew') . '</button>';
        
        echo '</div>';
    }
    
    /**
     * Inquiry details meta box
     */
    public function inquiry_details_meta_box($post) {
        wp_nonce_field('inquiry_meta_nonce', 'inquiry_meta_nonce');
        
        $subject = get_post_meta($post->ID, '_inquiry_subject', true);
        $travel_dates = get_post_meta($post->ID, '_travel_dates', true);
        $group_size = get_post_meta($post->ID, '_group_size_inquiry', true);
        $budget_range = get_post_meta($post->ID, '_budget_range', true);
        $contact_preference = get_post_meta($post->ID, '_contact_preference', true);
        $response_urgency = get_post_meta($post->ID, '_response_urgency', true);
        $newsletter_subscription = get_post_meta($post->ID, '_newsletter_subscription', true);
        $related_trip = get_post_meta($post->ID, '_related_trip', true);
        
        echo '<table class="form-table">';
        echo '<tr><th><label for="inquiry_subject">' . __('Subject', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="inquiry_subject" name="inquiry_subject" value="' . esc_attr($subject) . '" class="regular-text" readonly /></td></tr>';
        
        echo '<tr><th><label for="travel_dates">' . __('Travel Dates', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="travel_dates" name="travel_dates" value="' . esc_attr($travel_dates) . '" class="regular-text" /></td></tr>';
        
        echo '<tr><th><label for="group_size_inquiry">' . __('Group Size', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="group_size_inquiry" name="group_size_inquiry" value="' . esc_attr($group_size) . '" class="regular-text" /></td></tr>';
        
        echo '<tr><th><label for="budget_range">' . __('Budget Range', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="budget_range" name="budget_range" value="' . esc_attr($budget_range) . '" class="regular-text" /></td></tr>';
        
        echo '<tr><th><label for="contact_preference">' . __('Contact Preference', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="contact_preference" name="contact_preference" value="' . esc_attr($contact_preference) . '" class="regular-text" readonly /></td></tr>';
        
        echo '<tr><th><label for="response_urgency">' . __('Response Urgency', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="response_urgency" name="response_urgency" value="' . esc_attr($response_urgency) . '" class="regular-text" readonly /></td></tr>';
        
        echo '<tr><th><label for="related_trip">' . __('Related Trip', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="related_trip" name="related_trip" value="' . esc_attr($related_trip) . '" class="regular-text" readonly /></td></tr>';
        
        echo '<tr><th>' . __('Newsletter Subscription', 'tznew') . '</th>';
        echo '<td>' . ($newsletter_subscription ? __('Yes', 'tznew') : __('No', 'tznew')) . '</td></tr>';
        
        echo '</table>';
    }
    
    /**
     * Inquiry customer meta box
     */
    public function inquiry_customer_meta_box($post) {
        $name = get_post_meta($post->ID, '_inquiry_name', true);
        $email = get_post_meta($post->ID, '_inquiry_email', true);
        $phone = get_post_meta($post->ID, '_inquiry_phone', true);
        $country = get_post_meta($post->ID, '_inquiry_country', true);
        $submission_date = get_post_meta($post->ID, '_submission_date', true);
        
        echo '<table class="form-table">';
        echo '<tr><th><label for="inquiry_name">' . __('Name', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="inquiry_name" name="inquiry_name" value="' . esc_attr($name) . '" class="widefat" /></td></tr>';
        
        echo '<tr><th><label for="inquiry_email">' . __('Email', 'tznew') . '</label></th>';
        echo '<td><input type="email" id="inquiry_email" name="inquiry_email" value="' . esc_attr($email) . '" class="widefat" /></td></tr>';
        
        echo '<tr><th><label for="inquiry_phone">' . __('Phone', 'tznew') . '</label></th>';
        echo '<td><input type="tel" id="inquiry_phone" name="inquiry_phone" value="' . esc_attr($phone) . '" class="widefat" /></td></tr>';
        
        echo '<tr><th><label for="inquiry_country">' . __('Country', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="inquiry_country" name="inquiry_country" value="' . esc_attr($country) . '" class="widefat" /></td></tr>';
        
        if ($submission_date) {
            echo '<tr><th>' . __('Submitted', 'tznew') . '</th>';
            echo '<td>' . esc_html(date('F j, Y g:i A', strtotime($submission_date))) . '</td></tr>';
        }
        
        echo '</table>';
    }
    
    /**
     * Inquiry actions meta box
     */
    public function inquiry_actions_meta_box($post) {
        $status_terms = wp_get_post_terms($post->ID, 'inquiry_status');
        $current_status = !empty($status_terms) ? $status_terms[0]->slug : 'new';
        
        echo '<div class="inquiry-actions">';
        echo '<p><strong>' . __('Quick Actions', 'tznew') . '</strong></p>';
        
        // Status update
        echo '<p><label for="inquiry_status_select">' . __('Update Status:', 'tznew') . '</label><br>';
        echo '<select id="inquiry_status_select" name="inquiry_status" class="widefat">';
        $statuses = get_terms(array('taxonomy' => 'inquiry_status', 'hide_empty' => false));
        foreach ($statuses as $status) {
            echo '<option value="' . esc_attr($status->slug) . '"' . selected($current_status, $status->slug, false) . '>' . esc_html($status->name) . '</option>';
        }
        echo '</select></p>';
        
        // Email actions
        echo '<p><strong>' . __('Email Actions', 'tznew') . '</strong></p>';
        echo '<button type="button" class="button send-reply-email" data-inquiry-id="' . $post->ID . '">' . __('Send Reply', 'tznew') . '</button><br><br>';
        echo '<button type="button" class="button convert-to-booking" data-inquiry-id="' . $post->ID . '">' . __('Convert to Booking', 'tznew') . '</button>';
        
        echo '</div>';
    }
    
    /**
     * Save booking meta data
     */
    public function save_booking_meta($post_id) {
        if (!isset($_POST['booking_meta_nonce']) || !wp_verify_nonce($_POST['booking_meta_nonce'], 'booking_meta_nonce')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $post_type = get_post_type($post_id);
        
        if ($post_type === 'booking') {
            $fields = array(
                'trip_title', 'trip_type', 'preferred_date', 'group_size',
                'accommodation', 'dietary_requirements', 'special_requests',
                'total_cost', 'deposit_paid', 'balance_due',
                'first_name', 'last_name', 'email', 'phone', 'country'
            );
            
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
                }
            }
            
            // Update booking status
            if (isset($_POST['booking_status'])) {
                wp_set_post_terms($post_id, array($_POST['booking_status']), 'booking_status');
            }
        }
        
        if ($post_type === 'inquiry') {
            $fields = array(
                'inquiry_subject', 'travel_dates', 'group_size_inquiry',
                'budget_range', 'contact_preference', 'response_urgency',
                'related_trip', 'inquiry_name', 'inquiry_email',
                'inquiry_phone', 'inquiry_country'
            );
            
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
                }
            }
            
            // Update inquiry status
            if (isset($_POST['inquiry_status'])) {
                wp_set_post_terms($post_id, array($_POST['inquiry_status']), 'inquiry_status');
            }
        }
    }
    
    /**
     * Add admin menu for booking management
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Booking Management', 'tznew'),
            __('Bookings', 'tznew'),
            'manage_options',
            'booking-management',
            array($this, 'admin_dashboard'),
            'dashicons-calendar-alt',
            30
        );
        
        add_submenu_page(
            'booking-management',
            __('Dashboard', 'tznew'),
            __('Dashboard', 'tznew'),
            'manage_options',
            'booking-management',
            array($this, 'admin_dashboard')
        );
        
        add_submenu_page(
            'booking-management',
            __('Manage Bookings', 'tznew'),
            __('Manage Bookings', 'tznew'),
            'manage_options',
            'booking-bookings',
            array($this, 'admin_bookings')
        );
        
        add_submenu_page(
            'booking-management',
            __('Manage Inquiries', 'tznew'),
            __('Manage Inquiries', 'tznew'),
            'manage_options',
            'booking-inquiries',
            array($this, 'admin_inquiries')
        );
        
        add_submenu_page(
            'booking-management',
            __('All Bookings', 'tznew'),
            __('All Bookings', 'tznew'),
            'manage_options',
            'edit.php?post_type=booking'
        );
        
        add_submenu_page(
            'booking-management',
            __('All Inquiries', 'tznew'),
            __('All Inquiries', 'tznew'),
            'manage_options',
            'edit.php?post_type=inquiry'
        );
        
        add_submenu_page(
            'booking-management',
            __('Settings', 'tznew'),
            __('Settings', 'tznew'),
            'manage_options',
            'booking-settings',
            array($this, 'admin_settings')
        );
    }
    
    /**
     * Admin dashboard
     */
    public function admin_dashboard() {
        // Get statistics
        $total_bookings = wp_count_posts('booking')->publish;
        $total_inquiries = wp_count_posts('inquiry')->publish;
        
        $pending_bookings = get_posts(array(
            'post_type' => 'booking',
            'tax_query' => array(
                array(
                    'taxonomy' => 'booking_status',
                    'field' => 'slug',
                    'terms' => 'pending'
                )
            ),
            'numberposts' => -1
        ));
        
        $new_inquiries = get_posts(array(
            'post_type' => 'inquiry',
            'tax_query' => array(
                array(
                    'taxonomy' => 'inquiry_status',
                    'field' => 'slug',
                    'terms' => 'new'
                )
            ),
            'numberposts' => -1
        ));
        
        include dirname(__FILE__) . '/admin-templates/dashboard.php';
    }
    
    /**
     * Admin bookings page
     */
    public function admin_bookings() {
        include dirname(__FILE__) . '/admin-templates/bookings.php';
    }
    
    /**
     * Admin inquiries page
     */
    public function admin_inquiries() {
        include dirname(__FILE__) . '/admin-templates/inquiries.php';
    }
    
    /**
     * Admin settings
     */
    public function admin_settings() {
        if (isset($_POST['save_settings'])) {
            $settings = array(
                'auto_confirm_bookings' => isset($_POST['auto_confirm_bookings']),
                'email_notifications' => isset($_POST['email_notifications']),
                'admin_email' => sanitize_email($_POST['admin_email']),
                'booking_email_template' => wp_kses_post($_POST['booking_email_template']),
                'inquiry_email_template' => wp_kses_post($_POST['inquiry_email_template']),
            );
            
            update_option('tznew_booking_settings', $settings);
            echo '<div class="notice notice-success"><p>' . __('Settings saved successfully!', 'tznew') . '</p></div>';
        }
        
        $settings = get_option('tznew_booking_settings', array());
        include dirname(__FILE__) . '/admin-templates/settings.php';
    }
    
    /**
     * Enhanced booking submission handler
     */
    public function handle_booking_submission() {
        // Verify nonce for security
        if (!wp_verify_nonce($_POST['booking_nonce'] ?? '', 'tznew_booking_nonce')) {
            wp_send_json_error(['message' => 'Security check failed']);
        }

        // Sanitize and validate form data
        $booking_data = array(
            'first_name' => sanitize_text_field($_POST['first_name'] ?? ''),
            'last_name' => sanitize_text_field($_POST['last_name'] ?? ''),
            'email' => sanitize_email($_POST['email'] ?? ''),
            'phone' => sanitize_text_field($_POST['phone'] ?? ''),
            'country' => sanitize_text_field($_POST['country'] ?? ''),
            'preferred_date' => sanitize_text_field($_POST['preferred_date'] ?? ''),
            'group_size' => intval($_POST['group_size'] ?? 1),
            'accommodation_preference' => sanitize_text_field($_POST['accommodation_preference'] ?? ''),
            'dietary_requirements' => sanitize_textarea_field($_POST['dietary_requirements'] ?? ''),
            'special_requests' => sanitize_textarea_field($_POST['special_requests'] ?? ''),
            'post_id' => intval($_POST['post_id'] ?? 0),
            'post_title' => sanitize_text_field($_POST['post_title'] ?? ''),
            'post_type' => sanitize_text_field($_POST['post_type'] ?? '')
        );

        // Validate required fields
        if (empty($booking_data['first_name']) || empty($booking_data['last_name']) || 
            empty($booking_data['email']) || empty($booking_data['phone']) || 
            empty($booking_data['preferred_date']) || empty($booking_data['group_size'])) {
            wp_send_json_error(['message' => 'Please fill in all required fields']);
        }

        // Validate email
        if (!is_email($booking_data['email'])) {
            wp_send_json_error(['message' => 'Please enter a valid email address']);
        }

        // Create booking post
        $booking_id = wp_insert_post(array(
            'post_title' => sprintf('%s %s - %s', $booking_data['first_name'], $booking_data['last_name'], $booking_data['post_title']),
            'post_content' => sprintf('Booking request for %s by %s %s', $booking_data['post_title'], $booking_data['first_name'], $booking_data['last_name']),
            'post_status' => 'publish',
            'post_type' => 'booking'
        ));

        if (is_wp_error($booking_id)) {
            wp_send_json_error(['message' => 'Failed to create booking record']);
        }

        // Save booking meta data
        foreach ($booking_data as $key => $value) {
            update_post_meta($booking_id, '_' . $key, $value);
        }
        
        // Set submission date
        update_post_meta($booking_id, '_submission_date', current_time('mysql'));
        
        // Set initial status
        wp_set_post_terms($booking_id, array('pending'), 'booking_status');

        // Send emails (admin and customer)
        $this->send_booking_emails($booking_id, $booking_data);

        wp_send_json_success([
            'message' => 'Thank you! Your booking request has been submitted successfully. We will contact you within 24 hours.',
            'booking_id' => $booking_id
        ]);
    }
    
    /**
     * Enhanced inquiry submission handler
     */
    public function handle_inquiry_submission() {
        // Verify nonce for security
        if (!wp_verify_nonce($_POST['inquiry_nonce'] ?? '', 'tznew_inquiry_nonce')) {
            wp_send_json_error(['message' => 'Security check failed']);
        }

        // Sanitize and validate form data
        $inquiry_data = array(
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
        );

        // Validate required fields
        if (empty($inquiry_data['inquiry_name']) || empty($inquiry_data['inquiry_email']) || 
            empty($inquiry_data['inquiry_subject']) || empty($inquiry_data['inquiry_message'])) {
            wp_send_json_error(['message' => 'Please fill in all required fields']);
        }

        // Validate email
        if (!is_email($inquiry_data['inquiry_email'])) {
            wp_send_json_error(['message' => 'Please enter a valid email address']);
        }

        // Create inquiry post
        $inquiry_id = wp_insert_post(array(
            'post_title' => sprintf('%s - %s', $inquiry_data['inquiry_name'], $inquiry_data['inquiry_subject']),
            'post_content' => $inquiry_data['inquiry_message'],
            'post_status' => 'publish',
            'post_type' => 'inquiry'
        ));

        if (is_wp_error($inquiry_id)) {
            wp_send_json_error(['message' => 'Failed to create inquiry record']);
        }

        // Save inquiry meta data
        foreach ($inquiry_data as $key => $value) {
            update_post_meta($inquiry_id, '_' . $key, $value);
        }
        
        // Set submission date
        update_post_meta($inquiry_id, '_submission_date', current_time('mysql'));
        
        // Set initial status
        wp_set_post_terms($inquiry_id, array('new'), 'inquiry_status');

        // Send emails (admin and customer)
        $this->send_inquiry_emails($inquiry_id, $inquiry_data);

        wp_send_json_success([
            'message' => 'Thank you! Your inquiry has been submitted successfully. We will get back to you soon.',
            'inquiry_id' => $inquiry_id
        ]);
    }
    
    /**
     * Send booking emails
     */
    private function send_booking_emails($booking_id, $booking_data) {
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        
        // Admin email
        $admin_subject = sprintf('[%s] New Booking Request #%d - %s', $site_name, $booking_id, $booking_data['post_title']);
        $admin_message = $this->get_admin_booking_email_template($booking_id, $booking_data);
        
        $admin_headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $site_name . ' <' . $admin_email . '>',
            'Reply-To: ' . $booking_data['email']
        );
        
        wp_mail($admin_email, $admin_subject, $admin_message, $admin_headers);
        
        // Customer email
        $customer_subject = sprintf('[%s] Booking Request Confirmation #%d', $site_name, $booking_id);
        $customer_message = $this->get_customer_booking_email_template($booking_id, $booking_data);
        
        $customer_headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $site_name . ' <' . $admin_email . '>'
        );
        
        wp_mail($booking_data['email'], $customer_subject, $customer_message, $customer_headers);
    }
    
    /**
     * Send inquiry emails
     */
    private function send_inquiry_emails($inquiry_id, $inquiry_data) {
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        
        // Admin email
        $admin_subject = sprintf('[%s] New Inquiry #%d - %s', $site_name, $inquiry_id, $inquiry_data['inquiry_subject']);
        $admin_message = $this->get_admin_inquiry_email_template($inquiry_id, $inquiry_data);
        
        $admin_headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $site_name . ' <' . $admin_email . '>',
            'Reply-To: ' . $inquiry_data['inquiry_email']
        );
        
        wp_mail($admin_email, $admin_subject, $admin_message, $admin_headers);
        
        // Customer email
        $customer_subject = sprintf('[%s] Inquiry Confirmation #%d', $site_name, $inquiry_id);
        $customer_message = $this->get_customer_inquiry_email_template($inquiry_id, $inquiry_data);
        
        $customer_headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $site_name . ' <' . $admin_email . '>'
        );
        
        wp_mail($inquiry_data['inquiry_email'], $customer_subject, $customer_message, $customer_headers);
    }
    
    /**
     * Get admin booking email template
     */
    private function get_admin_booking_email_template($booking_id, $booking_data) {
        ob_start();
        include dirname(__FILE__) . '/email-templates/admin-booking.php';
        return ob_get_clean();
    }
    
    /**
     * Get customer booking email template
     */
    private function get_customer_booking_email_template($booking_id, $booking_data) {
        ob_start();
        include dirname(__FILE__) . '/email-templates/customer-booking.php';
        return ob_get_clean();
    }
    
    /**
     * Get admin inquiry email template
     */
    private function get_admin_inquiry_email_template($inquiry_id, $inquiry_data) {
        ob_start();
        include dirname(__FILE__) . '/email-templates/admin-inquiry.php';
        return ob_get_clean();
    }
    
    /**
     * Get customer inquiry email template
     */
    private function get_customer_inquiry_email_template($inquiry_id, $inquiry_data) {
        ob_start();
        include dirname(__FILE__) . '/email-templates/customer-inquiry.php';
        return ob_get_clean();
    }
    
    /**
     * Booking list columns
     */
    public function booking_columns($columns) {
        $new_columns = array(
            'cb' => $columns['cb'],
            'title' => __('Booking', 'tznew'),
            'customer' => __('Customer', 'tznew'),
            'trip' => __('Trip', 'tznew'),
            'date' => __('Travel Date', 'tznew'),
            'group_size' => __('Group Size', 'tznew'),
            'status' => __('Status', 'tznew'),
            'submitted' => __('Submitted', 'tznew'),
        );
        return $new_columns;
    }
    
    /**
     * Booking column content
     */
    public function booking_column_content($column, $post_id) {
        switch ($column) {
            case 'customer':
                $first_name = get_post_meta($post_id, '_first_name', true);
                $last_name = get_post_meta($post_id, '_last_name', true);
                $email = get_post_meta($post_id, '_email', true);
                echo esc_html($first_name . ' ' . $last_name) . '<br>';
                echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
                break;
                
            case 'trip':
                $trip_title = get_post_meta($post_id, '_trip_title', true);
                $trip_type = get_post_meta($post_id, '_trip_type', true);
                echo esc_html($trip_title) . '<br>';
                echo '<small>' . esc_html(ucfirst($trip_type)) . '</small>';
                break;
                
            case 'date':
                $preferred_date = get_post_meta($post_id, '_preferred_date', true);
                if ($preferred_date) {
                    echo esc_html(date('M j, Y', strtotime($preferred_date)));
                }
                break;
                
            case 'group_size':
                $group_size = get_post_meta($post_id, '_group_size', true);
                echo esc_html($group_size) . ' ' . _n('person', 'people', $group_size, 'tznew');
                break;
                
            case 'status':
                $terms = wp_get_post_terms($post_id, 'booking_status');
                if (!empty($terms)) {
                    $status = $terms[0];
                    $color = $this->get_status_color($status->slug);
                    echo '<span class="booking-status status-' . esc_attr($status->slug) . '" style="background-color: ' . esc_attr($color) . '; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">' . esc_html($status->name) . '</span>';
                }
                break;
                
            case 'submitted':
                $submission_date = get_post_meta($post_id, '_submission_date', true);
                if ($submission_date) {
                    echo esc_html(date('M j, Y', strtotime($submission_date)));
                }
                break;
        }
    }
    
    /**
     * Inquiry list columns
     */
    public function inquiry_columns($columns) {
        $new_columns = array(
            'cb' => $columns['cb'],
            'title' => __('Inquiry', 'tznew'),
            'customer' => __('Customer', 'tznew'),
            'subject' => __('Subject', 'tznew'),
            'urgency' => __('Urgency', 'tznew'),
            'status' => __('Status', 'tznew'),
            'submitted' => __('Submitted', 'tznew'),
        );
        return $new_columns;
    }
    
    /**
     * Inquiry column content
     */
    public function inquiry_column_content($column, $post_id) {
        switch ($column) {
            case 'customer':
                $name = get_post_meta($post_id, '_inquiry_name', true);
                $email = get_post_meta($post_id, '_inquiry_email', true);
                echo esc_html($name) . '<br>';
                echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
                break;
                
            case 'subject':
                $subject = get_post_meta($post_id, '_inquiry_subject', true);
                echo esc_html(ucfirst(str_replace('_', ' ', $subject)));
                break;
                
            case 'urgency':
                $urgency = get_post_meta($post_id, '_response_urgency', true);
                $color = $urgency === 'urgent' ? '#e74c3c' : ($urgency === 'normal' ? '#f39c12' : '#27ae60');
                echo '<span style="color: ' . esc_attr($color) . '; font-weight: bold;">' . esc_html(ucfirst($urgency)) . '</span>';
                break;
                
            case 'status':
                $terms = wp_get_post_terms($post_id, 'inquiry_status');
                if (!empty($terms)) {
                    $status = $terms[0];
                    $color = $this->get_inquiry_status_color($status->slug);
                    echo '<span class="inquiry-status status-' . esc_attr($status->slug) . '" style="background-color: ' . esc_attr($color) . '; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">' . esc_html($status->name) . '</span>';
                }
                break;
                
            case 'submitted':
                $submission_date = get_post_meta($post_id, '_submission_date', true);
                if ($submission_date) {
                    echo esc_html(date('M j, Y', strtotime($submission_date)));
                }
                break;
        }
    }
    
    /**
     * Get status color
     */
    private function get_status_color($status) {
        $colors = array(
            'pending' => '#f39c12',
            'confirmed' => '#27ae60',
            'cancelled' => '#e74c3c',
            'completed' => '#2ecc71',
            'in_progress' => '#3498db',
            'payment_pending' => '#e67e22',
        );
        
        return isset($colors[$status]) ? $colors[$status] : '#95a5a6';
    }
    
    /**
     * Get inquiry status color
     */
    private function get_inquiry_status_color($status) {
        $colors = array(
            'new' => '#e74c3c',
            'replied' => '#27ae60',
            'closed' => '#95a5a6',
            'follow_up' => '#f39c12',
        );
        
        return isset($colors[$status]) ? $colors[$status] : '#95a5a6';
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on booking-related admin pages
        if (strpos($hook, 'booking') !== false || strpos($hook, 'inquiry') !== false || 
            get_current_screen()->post_type === 'booking' || get_current_screen()->post_type === 'inquiry') {
            
            // Enqueue admin styles
            wp_enqueue_style(
                'booking-admin-styles', 
                get_template_directory_uri() . '/inc/admin-assets/admin-styles.css', 
                array(), 
                '1.0.0'
            );
            
            // Enqueue admin scripts
            wp_enqueue_script(
                'booking-admin-scripts', 
                get_template_directory_uri() . '/inc/admin-assets/admin-scripts.js', 
                array('jquery'), 
                '1.0.0', 
                true
            );
            
            // Localize script with data
            wp_localize_script('booking-admin-scripts', 'booking_admin', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('booking_admin_nonce'),
                'admin_url' => admin_url(),
                'strings' => array(
                    'confirm_delete' => __('Are you sure you want to delete this item?', 'tznew'),
                    'confirm_status_change' => __('Are you sure you want to change the status?', 'tznew'),
                    'loading' => __('Loading...', 'tznew'),
                    'error' => __('An error occurred. Please try again.', 'tznew'),
                    'success' => __('Operation completed successfully.', 'tznew')
                )
            ));
        }
    }
    
    /**
     * AJAX handler for updating booking status
     */
    public function update_booking_status() {
        check_ajax_referer('booking_admin_nonce', 'nonce');
        
        $booking_id = intval($_POST['booking_id']);
        $status = sanitize_text_field($_POST['status']);
        
        wp_set_post_terms($booking_id, array($status), 'booking_status');
        
        wp_send_json_success(array('message' => 'Status updated successfully'));
    }
    
    /**
     * AJAX handler for sending booking emails
     */
    public function send_booking_email() {
        check_ajax_referer('booking_admin_nonce', 'nonce');
        
        $booking_id = intval($_POST['booking_id']);
        $email_type = sanitize_text_field($_POST['email_type']);
        
        // Get booking data
        $booking_data = array(
            'first_name' => get_post_meta($booking_id, '_first_name', true),
            'last_name' => get_post_meta($booking_id, '_last_name', true),
            'email' => get_post_meta($booking_id, '_email', true),
            'post_title' => get_post_meta($booking_id, '_trip_title', true),
        );
        
        $site_name = get_bloginfo('name');
        $admin_email = get_option('admin_email');
        
        switch ($email_type) {
            case 'confirmation':
                $subject = sprintf('[%s] Booking Confirmation #%d', $site_name, $booking_id);
                $message = $this->get_confirmation_email_template($booking_id, $booking_data);
                break;
                
            case 'quote':
                $subject = sprintf('[%s] Quote for Booking #%d', $site_name, $booking_id);
                $message = $this->get_quote_email_template($booking_id, $booking_data);
                break;
                
            case 'reminder':
                $subject = sprintf('[%s] Booking Reminder #%d', $site_name, $booking_id);
                $message = $this->get_reminder_email_template($booking_id, $booking_data);
                break;
                
            default:
                wp_send_json_error(array('message' => 'Invalid email type'));
                return;
        }
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $site_name . ' <' . $admin_email . '>'
        );
        
        $sent = wp_mail($booking_data['email'], $subject, $message, $headers);
        
        if ($sent) {
            wp_send_json_success(array('message' => 'Email sent successfully'));
        } else {
            wp_send_json_error(array('message' => 'Failed to send email'));
        }
    }
    
    /**
     * Get confirmation email template
     */
    private function get_confirmation_email_template($booking_id, $booking_data) {
        ob_start();
        include dirname(__FILE__) . '/email-templates/confirmation.php';
        return ob_get_clean();
    }
    
    /**
     * Get quote email template
     */
    private function get_quote_email_template($booking_id, $booking_data) {
        ob_start();
        include dirname(__FILE__) . '/email-templates/quote.php';
        return ob_get_clean();
    }
    
    /**
     * Get reminder email template
     */
    private function get_reminder_email_template($booking_id, $booking_data) {
        ob_start();
        include dirname(__FILE__) . '/email-templates/reminder.php';
        return ob_get_clean();
    }
    
    /**
     * Handle booking review submission
     */
    public function handle_booking_review() {
        // Verify nonce for security
        if (!wp_verify_nonce($_POST['booking_nonce'] ?? '', 'booking_action')) {
            wp_die('Security check failed');
        }
        
        $booking_id = intval($_POST['booking_id'] ?? 0);
        
        if (!$booking_id) {
            wp_die('Invalid booking ID');
        }
        
        // Sanitize review data
        $review_data = array(
            'rating' => intval($_POST['review_rating'] ?? 0),
            'service_quality' => sanitize_text_field($_POST['review_service_quality'] ?? ''),
            'communication' => sanitize_text_field($_POST['review_communication'] ?? ''),
            'timeliness' => sanitize_text_field($_POST['review_timeliness'] ?? ''),
            'professionalism' => sanitize_text_field($_POST['review_professionalism'] ?? ''),
            'comments' => sanitize_textarea_field($_POST['review_comments'] ?? ''),
            'recommendations' => sanitize_textarea_field($_POST['review_recommendations'] ?? ''),
            'follow_up' => sanitize_text_field($_POST['review_follow_up'] ?? 'no'),
            'review_date' => current_time('mysql'),
            'reviewer' => get_current_user_id()
        );
        
        // Save review data as post meta
        update_post_meta($booking_id, '_booking_review', $review_data);
        
        // Add review to admin notes
        $existing_notes = get_post_meta($booking_id, '_admin_notes', true);
        $review_note = sprintf(
            "[%s] ADMIN REVIEW ADDED\n" .
            "Rating: %d/5 stars\n" .
            "Service Quality: %s\n" .
            "Communication: %s\n" .
            "Timeliness: %s\n" .
            "Professionalism: %s\n" .
            "Comments: %s\n" .
            "Recommendations: %s\n" .
            "Follow-up Required: %s\n" .
            "Reviewed by: %s\n\n",
            date('Y-m-d H:i:s'),
            $review_data['rating'],
            ucfirst($review_data['service_quality']),
            ucfirst($review_data['communication']),
            ucfirst($review_data['timeliness']),
            ucfirst($review_data['professionalism']),
            $review_data['comments'] ?: 'No comments provided',
            $review_data['recommendations'] ?: 'No recommendations provided',
            ucfirst($review_data['follow_up']),
            wp_get_current_user()->display_name
        );
        
        $updated_notes = $existing_notes . $review_note;
        update_post_meta($booking_id, '_admin_notes', $updated_notes);
        
        // If follow-up is required, add a flag
        if ($review_data['follow_up'] !== 'no') {
            update_post_meta($booking_id, '_requires_follow_up', $review_data['follow_up']);
        }
        
        // Redirect back with success message
        wp_redirect(add_query_arg(array(
            'page' => 'booking-management',
            'action' => 'view',
            'booking_id' => $booking_id,
            'review_added' => '1'
        ), admin_url('admin.php')));
        exit;
    }
}

// Initialize the booking system
new TZnew_Booking_System();