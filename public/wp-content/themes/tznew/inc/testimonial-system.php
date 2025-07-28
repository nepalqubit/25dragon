<?php
/**
 * Complete Testimonial System for Trekking and Tours
 *
 * @package TZnew
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class TZnew_Testimonial_System {
    
    public function __construct() {
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_testimonial_meta'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_sync_tripadvisor_reviews', array($this, 'sync_tripadvisor_reviews'));
        add_action('wp_ajax_sync_google_reviews', array($this, 'sync_google_reviews'));
        add_action('wp_ajax_update_testimonial_status', array($this, 'update_testimonial_status'));
        add_filter('manage_testimonial_posts_columns', array($this, 'testimonial_columns'));
        add_action('manage_testimonial_posts_custom_column', array($this, 'testimonial_column_content'), 10, 2);
        
        // Create custom tables on activation
        register_activation_hook(__FILE__, array($this, 'create_testimonial_tables'));
        
        // Schedule automatic review sync
        add_action('wp', array($this, 'schedule_review_sync'));
        add_action('tznew_sync_reviews_hook', array($this, 'auto_sync_reviews'));
    }
    
    /**
     * Register custom post type for testimonials
     */
    public function register_post_types() {
        register_post_type('testimonial', array(
            'labels' => array(
                'name' => __('Testimonials', 'tznew'),
                'singular_name' => __('Testimonial', 'tznew'),
                'menu_name' => __('Testimonials', 'tznew'),
                'add_new' => __('Add New Testimonial', 'tznew'),
                'add_new_item' => __('Add New Testimonial', 'tznew'),
                'edit_item' => __('Edit Testimonial', 'tznew'),
                'new_item' => __('New Testimonial', 'tznew'),
                'view_item' => __('View Testimonial', 'tznew'),
                'search_items' => __('Search Testimonials', 'tznew'),
                'not_found' => __('No testimonials found', 'tznew'),
                'not_found_in_trash' => __('No testimonials found in trash', 'tznew'),
            ),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false, // We'll add custom menu
            'capability_type' => 'post',
            'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
            'has_archive' => true,
            'rewrite' => array('slug' => 'testimonials'),
        ));
    }
    
    /**
     * Register taxonomies for testimonial categories and sources
     */
    public function register_taxonomies() {
        // Testimonial source taxonomy
        register_taxonomy('testimonial_source', 'testimonial', array(
            'labels' => array(
                'name' => __('Testimonial Sources', 'tznew'),
                'singular_name' => __('Source', 'tznew'),
            ),
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'hierarchical' => false,
            'rewrite' => array('slug' => 'testimonial-source'),
        ));
        
        // Testimonial category taxonomy
        register_taxonomy('testimonial_category', 'testimonial', array(
            'labels' => array(
                'name' => __('Testimonial Categories', 'tznew'),
                'singular_name' => __('Category', 'tznew'),
            ),
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'hierarchical' => true,
            'rewrite' => array('slug' => 'testimonial-category'),
        ));
        
        // Create default sources and categories
        $this->create_default_terms();
    }
    
    /**
     * Create default testimonial sources and categories
     */
    private function create_default_terms() {
        // Default sources
        $sources = array(
            'manual' => __('Manual Entry', 'tznew'),
            'tripadvisor' => __('TripAdvisor', 'tznew'),
            'google' => __('Google Reviews', 'tznew'),
            'facebook' => __('Facebook', 'tznew'),
            'booking_com' => __('Booking.com', 'tznew'),
        );
        
        foreach ($sources as $slug => $name) {
            if (!term_exists($slug, 'testimonial_source')) {
                wp_insert_term($name, 'testimonial_source', array('slug' => $slug));
            }
        }
        
        // Default categories
        $categories = array(
            'trekking' => __('Trekking', 'tznew'),
            'tours' => __('Tours', 'tznew'),
            'accommodation' => __('Accommodation', 'tznew'),
            'service' => __('Service Quality', 'tznew'),
            'guide' => __('Guide Service', 'tznew'),
        );
        
        foreach ($categories as $slug => $name) {
            if (!term_exists($slug, 'testimonial_category')) {
                wp_insert_term($name, 'testimonial_category', array('slug' => $slug));
            }
        }
    }
    
    /**
     * Create custom database tables for API settings and sync logs
     */
    public function create_testimonial_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // API Settings table
        $api_settings_table = $wpdb->prefix . 'testimonial_api_settings';
        $sql1 = "CREATE TABLE $api_settings_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            platform varchar(50) NOT NULL,
            api_key text,
            page_url text,
            place_id varchar(255),
            last_sync datetime DEFAULT '0000-00-00 00:00:00',
            sync_frequency varchar(20) DEFAULT 'daily',
            is_active tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY platform (platform)
        ) $charset_collate;";
        
        // Sync logs table
        $sync_logs_table = $wpdb->prefix . 'testimonial_sync_logs';
        $sql2 = "CREATE TABLE $sync_logs_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            platform varchar(50) NOT NULL,
            sync_date datetime DEFAULT CURRENT_TIMESTAMP,
            reviews_fetched int(11) DEFAULT 0,
            reviews_imported int(11) DEFAULT 0,
            status varchar(20) DEFAULT 'success',
            error_message text,
            PRIMARY KEY (id),
            KEY platform (platform),
            KEY sync_date (sync_date)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql1);
        dbDelta($sql2);
    }
    
    /**
     * Add meta boxes for testimonial details
     */
    public function add_meta_boxes() {
        add_meta_box(
            'testimonial_details',
            __('Testimonial Details', 'tznew'),
            array($this, 'testimonial_details_meta_box'),
            'testimonial',
            'normal',
            'high'
        );
        
        add_meta_box(
            'testimonial_rating',
            __('Rating & Review Info', 'tznew'),
            array($this, 'testimonial_rating_meta_box'),
            'testimonial',
            'side',
            'default'
        );
    }
    
    /**
     * Testimonial details meta box
     */
    public function testimonial_details_meta_box($post) {
        wp_nonce_field('testimonial_meta_box', 'testimonial_meta_box_nonce');
        
        $customer_name = get_post_meta($post->ID, '_customer_name', true);
        $customer_email = get_post_meta($post->ID, '_customer_email', true);
        $customer_country = get_post_meta($post->ID, '_customer_country', true);
        $trip_date = get_post_meta($post->ID, '_trip_date', true);
        $trip_type = get_post_meta($post->ID, '_trip_type', true);
        $review_date = get_post_meta($post->ID, '_review_date', true);
        $external_id = get_post_meta($post->ID, '_external_id', true);
        $external_url = get_post_meta($post->ID, '_external_url', true);
        $assigned_trekking = get_post_meta($post->ID, '_assigned_trekking', true);
        $assigned_tours = get_post_meta($post->ID, '_assigned_tours', true);
        $homepage_featured = get_post_meta($post->ID, '_homepage_featured', true);
        
        echo '<table class="form-table">';
        echo '<tr><th><label for="customer_name">' . __('Customer Name', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="customer_name" name="customer_name" value="' . esc_attr($customer_name) . '" class="regular-text" /></td></tr>';
        
        echo '<tr><th><label for="customer_email">' . __('Customer Email', 'tznew') . '</label></th>';
        echo '<td><input type="email" id="customer_email" name="customer_email" value="' . esc_attr($customer_email) . '" class="regular-text" /></td></tr>';
        
        echo '<tr><th><label for="customer_country">' . __('Customer Country', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="customer_country" name="customer_country" value="' . esc_attr($customer_country) . '" class="regular-text" /></td></tr>';
        
        echo '<tr><th><label for="trip_date">' . __('Trip Date', 'tznew') . '</label></th>';
        echo '<td><input type="date" id="trip_date" name="trip_date" value="' . esc_attr($trip_date) . '" class="regular-text" /></td></tr>';
        
        echo '<tr><th><label for="trip_type">' . __('Trip/Tour Type', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="trip_type" name="trip_type" value="' . esc_attr($trip_type) . '" class="regular-text" /></td></tr>';
        
        echo '<tr><th><label for="review_date">' . __('Review Date', 'tznew') . '</label></th>';
        echo '<td><input type="date" id="review_date" name="review_date" value="' . esc_attr($review_date) . '" class="regular-text" /></td></tr>';
        
        // Assigned Trekking
        echo '<tr><th><label for="assigned_trekking">' . __('Assign to Trekking', 'tznew') . '</label></th>';
        echo '<td>';
        $trekking_posts = get_posts(array(
            'post_type' => 'trekking',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
        ));
        if (!empty($trekking_posts)) {
            echo '<select id="assigned_trekking" name="assigned_trekking[]" multiple class="regular-text" style="height: 120px;">';
            echo '<option value="">' . __('Select Trekking (hold Ctrl/Cmd for multiple)', 'tznew') . '</option>';
            $assigned_trekking_array = is_array($assigned_trekking) ? $assigned_trekking : (!empty($assigned_trekking) ? array($assigned_trekking) : array());
            foreach ($trekking_posts as $trekking) {
                $selected = in_array($trekking->ID, $assigned_trekking_array) ? 'selected' : '';
                echo '<option value="' . $trekking->ID . '" ' . $selected . '>' . esc_html($trekking->post_title) . '</option>';
            }
            echo '</select>';
        } else {
            echo '<p>' . __('No trekking packages found.', 'tznew') . '</p>';
        }
        echo '</td></tr>';
        
        // Assigned Tours
        echo '<tr><th><label for="assigned_tours">' . __('Assign to Tours', 'tznew') . '</label></th>';
        echo '<td>';
        $tour_posts = get_posts(array(
            'post_type' => 'tours',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
        ));
        if (!empty($tour_posts)) {
            echo '<select id="assigned_tours" name="assigned_tours[]" multiple class="regular-text" style="height: 120px;">';
            echo '<option value="">' . __('Select Tours (hold Ctrl/Cmd for multiple)', 'tznew') . '</option>';
            $assigned_tours_array = is_array($assigned_tours) ? $assigned_tours : (!empty($assigned_tours) ? array($assigned_tours) : array());
            foreach ($tour_posts as $tour) {
                $selected = in_array($tour->ID, $assigned_tours_array) ? 'selected' : '';
                echo '<option value="' . $tour->ID . '" ' . $selected . '>' . esc_html($tour->post_title) . '</option>';
            }
            echo '</select>';
        } else {
            echo '<p>' . __('No tour packages found.', 'tznew') . '</p>';
        }
        echo '</td></tr>';
        
        // Homepage Featured
        echo '<tr><th><label for="homepage_featured">' . __('Homepage Featured', 'tznew') . '</label></th>';
        echo '<td><label><input type="checkbox" id="homepage_featured" name="homepage_featured" value="1"' . checked($homepage_featured, 1, false) . '> ' . __('Display this testimonial on homepage', 'tznew') . '</label>';
        echo '<p class="description">' . __('Featured testimonials will be displayed prominently on the homepage.', 'tznew') . '</p></td></tr>';
        
        echo '<tr><th><label for="external_id">' . __('External Review ID', 'tznew') . '</label></th>';
        echo '<td><input type="text" id="external_id" name="external_id" value="' . esc_attr($external_id) . '" class="regular-text" readonly /></td></tr>';
        
        echo '<tr><th><label for="external_url">' . __('External Review URL', 'tznew') . '</label></th>';
        echo '<td><input type="url" id="external_url" name="external_url" value="' . esc_attr($external_url) . '" class="regular-text" readonly /></td></tr>';
        
        echo '</table>';
    }
    
    /**
     * Testimonial rating meta box
     */
    public function testimonial_rating_meta_box($post) {
        $rating = get_post_meta($post->ID, '_rating', true);
        $verified = get_post_meta($post->ID, '_verified', true);
        $featured = get_post_meta($post->ID, '_featured', true);
        
        echo '<p><label for="rating"><strong>' . __('Rating (1-5)', 'tznew') . '</strong></label><br>';
        echo '<select id="rating" name="rating">';
        for ($i = 1; $i <= 5; $i++) {
            echo '<option value="' . $i . '"' . selected($rating, $i, false) . '>' . $i . ' ' . str_repeat('★', $i) . '</option>';
        }
        echo '</select></p>';
        
        echo '<p><label><input type="checkbox" name="verified" value="1"' . checked($verified, 1, false) . '> ' . __('Verified Review', 'tznew') . '</label></p>';
        echo '<p><label><input type="checkbox" name="featured" value="1"' . checked($featured, 1, false) . '> ' . __('Featured Testimonial', 'tznew') . '</label></p>';
    }
    
    /**
     * Save testimonial meta data
     */
    public function save_testimonial_meta($post_id) {
        if (!isset($_POST['testimonial_meta_box_nonce']) || !wp_verify_nonce($_POST['testimonial_meta_box_nonce'], 'testimonial_meta_box')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $fields = array(
            'customer_name', 'customer_email', 'customer_country',
            'trip_date', 'trip_type', 'review_date', 'external_id',
            'external_url', 'rating'
        );
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
        
        // Handle assigned trekking (multiple selection)
        if (isset($_POST['assigned_trekking']) && is_array($_POST['assigned_trekking'])) {
            $assigned_trekking = array_filter(array_map('intval', $_POST['assigned_trekking']));
            update_post_meta($post_id, '_assigned_trekking', $assigned_trekking);
        } else {
            delete_post_meta($post_id, '_assigned_trekking');
        }
        
        // Handle assigned tours (multiple selection)
        if (isset($_POST['assigned_tours']) && is_array($_POST['assigned_tours'])) {
            $assigned_tours = array_filter(array_map('intval', $_POST['assigned_tours']));
            update_post_meta($post_id, '_assigned_tours', $assigned_tours);
        } else {
            delete_post_meta($post_id, '_assigned_tours');
        }
        
        // Handle checkboxes
        update_post_meta($post_id, '_verified', isset($_POST['verified']) ? 1 : 0);
        update_post_meta($post_id, '_featured', isset($_POST['featured']) ? 1 : 0);
        update_post_meta($post_id, '_homepage_featured', isset($_POST['homepage_featured']) ? 1 : 0);
    }
    
    /**
     * Add admin menu for testimonial management
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Testimonial Management', 'tznew'),
            __('Testimonials', 'tznew'),
            'manage_options',
            'testimonial-management',
            array($this, 'admin_dashboard'),
            'dashicons-star-filled',
            31
        );
        
        add_submenu_page(
            'testimonial-management',
            __('Dashboard', 'tznew'),
            __('Dashboard', 'tznew'),
            'manage_options',
            'testimonial-management',
            array($this, 'admin_dashboard')
        );
        
        add_submenu_page(
            'testimonial-management',
            __('Manage Testimonials', 'tznew'),
            __('Manage Testimonials', 'tznew'),
            'manage_options',
            'testimonial-list',
            array($this, 'admin_testimonials')
        );
        
        add_submenu_page(
            'testimonial-management',
            __('API Settings', 'tznew'),
            __('API Settings', 'tznew'),
            'manage_options',
            'testimonial-api-settings',
            array($this, 'admin_api_settings')
        );
        
        add_submenu_page(
            'testimonial-management',
            __('Sync Logs', 'tznew'),
            __('Sync Logs', 'tznew'),
            'manage_options',
            'testimonial-sync-logs',
            array($this, 'admin_sync_logs')
        );
        
        add_submenu_page(
            'testimonial-management',
            __('All Testimonials', 'tznew'),
            __('All Testimonials', 'tznew'),
            'manage_options',
            'edit.php?post_type=testimonial'
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'testimonial') !== false) {
            wp_enqueue_script('testimonial-admin', get_template_directory_uri() . '/inc/admin-assets/testimonial-admin.js', array('jquery'), '1.0.0', true);
            wp_enqueue_style('testimonial-admin', get_template_directory_uri() . '/inc/admin-assets/testimonial-admin.css', array(), '1.0.0');
            
            wp_localize_script('testimonial-admin', 'testimonial_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('testimonial_ajax_nonce')
            ));
        }
    }
    
    /**
     * Admin dashboard
     */
    public function admin_dashboard() {
        include dirname(__FILE__) . '/admin-templates/testimonial-dashboard.php';
    }
    
    /**
     * Admin testimonials page
     */
    public function admin_testimonials() {
        include dirname(__FILE__) . '/admin-templates/testimonial-list.php';
    }
    
    /**
     * Admin API settings page
     */
    public function admin_api_settings() {
        include dirname(__FILE__) . '/admin-templates/testimonial-api-settings.php';
    }
    
    /**
     * Admin sync logs page
     */
    public function admin_sync_logs() {
        include dirname(__FILE__) . '/admin-templates/testimonial-sync-logs.php';
    }
    
    /**
     * Sync TripAdvisor reviews
     */
    public function sync_tripadvisor_reviews() {
        check_ajax_referer('testimonial_ajax_nonce', 'nonce');
        
        global $wpdb;
        $api_settings_table = $wpdb->prefix . 'testimonial_api_settings';
        $sync_logs_table = $wpdb->prefix . 'testimonial_sync_logs';
        
        $settings = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $api_settings_table WHERE platform = %s",
            'tripadvisor'
        ));
        
        if (!$settings || !$settings->is_active) {
            wp_send_json_error('TripAdvisor API not configured or inactive');
        }
        
        // Implement TripAdvisor API integration here
        // This is a placeholder for the actual API implementation
        $reviews_fetched = 0;
        $reviews_imported = 0;
        
        // Log the sync attempt
        $wpdb->insert(
            $sync_logs_table,
            array(
                'platform' => 'tripadvisor',
                'reviews_fetched' => $reviews_fetched,
                'reviews_imported' => $reviews_imported,
                'status' => 'success'
            )
        );
        
        wp_send_json_success(array(
            'message' => sprintf(__('Synced %d reviews from TripAdvisor', 'tznew'), $reviews_imported)
        ));
    }
    
    /**
     * Sync Google reviews
     */
    public function sync_google_reviews() {
        check_ajax_referer('testimonial_ajax_nonce', 'nonce');
        
        global $wpdb;
        $api_settings_table = $wpdb->prefix . 'testimonial_api_settings';
        $sync_logs_table = $wpdb->prefix . 'testimonial_sync_logs';
        
        $settings = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $api_settings_table WHERE platform = %s",
            'google'
        ));
        
        if (!$settings || !$settings->is_active) {
            wp_send_json_error('Google Places API not configured or inactive');
        }
        
        // Implement Google Places API integration here
        // This is a placeholder for the actual API implementation
        $reviews_fetched = 0;
        $reviews_imported = 0;
        
        // Log the sync attempt
        $wpdb->insert(
            $sync_logs_table,
            array(
                'platform' => 'google',
                'reviews_fetched' => $reviews_fetched,
                'reviews_imported' => $reviews_imported,
                'status' => 'success'
            )
        );
        
        wp_send_json_success(array(
            'message' => sprintf(__('Synced %d reviews from Google', 'tznew'), $reviews_imported)
        ));
    }
    
    /**
     * Update testimonial status
     */
    public function update_testimonial_status() {
        check_ajax_referer('testimonial_ajax_nonce', 'nonce');
        
        $testimonial_id = intval($_POST['testimonial_id']);
        $status = sanitize_text_field($_POST['status']);
        
        $result = wp_update_post(array(
            'ID' => $testimonial_id,
            'post_status' => $status
        ));
        
        if ($result) {
            wp_send_json_success('Status updated successfully');
        } else {
            wp_send_json_error('Failed to update status');
        }
    }
    
    /**
     * Schedule automatic review sync
     */
    public function schedule_review_sync() {
        if (!wp_next_scheduled('tznew_sync_reviews_hook')) {
            wp_schedule_event(time(), 'daily', 'tznew_sync_reviews_hook');
        }
    }
    
    /**
     * Auto sync reviews (called by cron)
     */
    public function auto_sync_reviews() {
        global $wpdb;
        $api_settings_table = $wpdb->prefix . 'testimonial_api_settings';
        
        $active_apis = $wpdb->get_results(
            "SELECT * FROM $api_settings_table WHERE is_active = 1"
        );
        
        foreach ($active_apis as $api) {
            if ($api->platform === 'tripadvisor') {
                // Auto sync TripAdvisor reviews
            } elseif ($api->platform === 'google') {
                // Auto sync Google reviews
            }
        }
    }
    
    /**
     * Customize testimonial columns
     */
    public function testimonial_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['customer'] = __('Customer', 'tznew');
        $new_columns['rating'] = __('Rating', 'tznew');
        $new_columns['source'] = __('Source', 'tznew');
        $new_columns['featured'] = __('Featured', 'tznew');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }
    
    /**
     * Display testimonial column content
     */
    public function testimonial_column_content($column, $post_id) {
        switch ($column) {
            case 'customer':
                echo esc_html(get_post_meta($post_id, '_customer_name', true));
                break;
            case 'rating':
                $rating = get_post_meta($post_id, '_rating', true);
                echo str_repeat('★', intval($rating)) . str_repeat('☆', 5 - intval($rating));
                break;
            case 'source':
                $terms = get_the_terms($post_id, 'testimonial_source');
                if ($terms && !is_wp_error($terms)) {
                    echo esc_html($terms[0]->name);
                }
                break;
            case 'featured':
                $featured = get_post_meta($post_id, '_featured', true);
                echo $featured ? '★' : '-';
                break;
        }
    }
}

// Initialize the testimonial system
new TZnew_Testimonial_System();