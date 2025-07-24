<?php
/**
 * WooCommerce Integration for TZnew Theme
 *
 * @package TZnew
 * @author Santosh Baral
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if WooCommerce is active
if (!class_exists('WooCommerce')) {
    return;
}

/**
 * WooCommerce setup function.
 */
function tznew_woocommerce_setup() {
    // Add theme support for WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Remove default WooCommerce styles
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
}
add_action('after_setup_theme', 'tznew_woocommerce_setup');

/**
 * Enqueue WooCommerce styles
 */
function tznew_woocommerce_scripts() {
    wp_enqueue_style('tznew-woocommerce', TZNEW_THEME_URI . '/assets/css/woocommerce.css', array(), TZNEW_VERSION);
}
add_action('wp_enqueue_scripts', 'tznew_woocommerce_scripts');

/**
 * Disable the default WooCommerce stylesheet
 */
function tznew_dequeue_styles($enqueue_styles) {
    unset($enqueue_styles['woocommerce-general']);
    unset($enqueue_styles['woocommerce-layout']);
    unset($enqueue_styles['woocommerce-smallscreen']);
    return $enqueue_styles;
}
add_filter('woocommerce_enqueue_styles', 'tznew_dequeue_styles');

/**
 * Related Products Args
 */
function tznew_related_products_args($args) {
    $args['posts_per_page'] = 3;
    $args['columns'] = 3;
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'tznew_related_products_args');

/**
 * Product gallery thumbnail columns
 */
function tznew_thumbnail_columns() {
    return 4;
}
add_filter('woocommerce_product_thumbnails_columns', 'tznew_thumbnail_columns');

/**
 * Default loop columns on product archives
 */
function tznew_loop_columns() {
    return 3;
}
add_filter('loop_shop_columns', 'tznew_loop_columns');

/**
 * Products per page
 */
function tznew_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'tznew_products_per_page');

/**
 * Remove WooCommerce breadcrumbs
 */
function tznew_remove_wc_breadcrumbs() {
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
}
add_action('init', 'tznew_remove_wc_breadcrumbs');

/**
 * Add custom breadcrumbs to WooCommerce pages
 */
function tznew_woocommerce_breadcrumbs() {
    echo tznew_breadcrumbs();
}
add_action('woocommerce_before_main_content', 'tznew_woocommerce_breadcrumbs', 20);

/**
 * Rename product tabs
 */
function tznew_rename_tabs($tabs) {
    $tabs['description']['title'] = __('Overview', 'tznew');
    $tabs['additional_information']['title'] = __('Specifications', 'tznew');
    $tabs['reviews']['title'] = __('Reviews', 'tznew');
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'tznew_rename_tabs', 98);

/**
 * Add a custom tab to product pages
 */
function tznew_new_product_tab($tabs) {
    $tabs['booking_info'] = array(
        'title'    => __('Booking Info', 'tznew'),
        'priority' => 50,
        'callback' => 'tznew_booking_info_tab_content'
    );
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'tznew_new_product_tab');

/**
 * Custom tab content
 */
function tznew_booking_info_tab_content() {
    echo '<h2>' . __('Booking Information', 'tznew') . '</h2>';
    echo '<p>' . __('Please read the following information before booking:', 'tznew') . '</p>';
    echo '<ul>';
    echo '<li>' . __('Booking requires a 20% deposit.', 'tznew') . '</li>';
    echo '<li>' . __('Full payment is due 30 days before the start date.', 'tznew') . '</li>';
    echo '<li>' . __('Cancellation policy: 100% refund if cancelled 60 days before, 50% if cancelled 30 days before.', 'tznew') . '</li>';
    echo '<li>' . __('Travel insurance is recommended for all bookings.', 'tznew') . '</li>';
    echo '</ul>';
}

/**
 * Add custom fields to product pages
 */
function tznew_before_add_to_cart_form() {
    global $product;
    
    if ($product->get_type() === 'booking') {
        echo '<div class="booking-highlights">';
        echo '<h3>' . __('Highlights', 'tznew') . '</h3>';
        echo '<ul>';
        echo '<li><i class="fas fa-check"></i> ' . __('Instant confirmation', 'tznew') . '</li>';
        echo '<li><i class="fas fa-check"></i> ' . __('Free cancellation up to 24 hours before', 'tznew') . '</li>';
        echo '<li><i class="fas fa-check"></i> ' . __('Mobile voucher accepted', 'tznew') . '</li>';
        echo '<li><i class="fas fa-check"></i> ' . __('Duration: flexible', 'tznew') . '</li>';
        echo '</ul>';
        echo '</div>';
    }
}
add_action('woocommerce_before_add_to_cart_form', 'tznew_before_add_to_cart_form');

/**
 * Add custom fields after add to cart button
 */
function tznew_after_add_to_cart_button() {
    global $product;
    
    echo '<div class="secure-checkout">';
    echo '<p><i class="fas fa-lock"></i> ' . __('Secure checkout', 'tznew') . '</p>';
    echo '</div>';
    
    echo '<div class="payment-methods">';
    echo '<p>' . __('We accept:', 'tznew') . ' <i class="fab fa-cc-visa"></i> <i class="fab fa-cc-mastercard"></i> <i class="fab fa-cc-amex"></i> <i class="fab fa-cc-paypal"></i></p>';
    echo '</div>';
}
add_action('woocommerce_after_add_to_cart_button', 'tznew_after_add_to_cart_button');

/**
 * Modify the checkout fields
 */
function tznew_override_checkout_fields($fields) {
    // Add placeholder to fields
    $fields['billing']['billing_first_name']['placeholder'] = __('First Name', 'tznew');
    $fields['billing']['billing_last_name']['placeholder'] = __('Last Name', 'tznew');
    $fields['billing']['billing_company']['placeholder'] = __('Company Name', 'tznew');
    $fields['billing']['billing_address_1']['placeholder'] = __('Street Address', 'tznew');
    $fields['billing']['billing_address_2']['placeholder'] = __('Apartment, suite, unit, etc.', 'tznew');
    $fields['billing']['billing_city']['placeholder'] = __('City', 'tznew');
    $fields['billing']['billing_postcode']['placeholder'] = __('Postcode / ZIP', 'tznew');
    $fields['billing']['billing_phone']['placeholder'] = __('Phone', 'tznew');
    $fields['billing']['billing_email']['placeholder'] = __('Email Address', 'tznew');
    
    // Add custom field for special requests
    $fields['order']['order_comments']['placeholder'] = __('Special notes or requests for your booking', 'tznew');
    $fields['order']['order_comments']['label'] = __('Special Requests', 'tznew');
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'tznew_override_checkout_fields');

/**
 * Add custom field to checkout for travel date
 */
function tznew_add_checkout_fields($fields) {
    $fields['billing']['travel_date'] = array(
        'type' => 'date',
        'label' => __('Preferred Travel Date', 'tznew'),
        'placeholder' => __('Select a date', 'tznew'),
        'required' => false,
        'class' => array('form-row-wide'),
        'clear' => true,
        'priority' => 25,
    );
    
    $fields['billing']['travelers'] = array(
        'type' => 'number',
        'label' => __('Number of Travelers', 'tznew'),
        'placeholder' => __('Number of people', 'tznew'),
        'required' => false,
        'class' => array('form-row-wide'),
        'clear' => true,
        'priority' => 26,
        'custom_attributes' => array(
            'min' => '1',
            'max' => '20',
        ),
    );
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'tznew_add_checkout_fields');

/**
 * Save custom checkout fields to order meta
 */
function tznew_checkout_field_update_order_meta($order_id) {
    if (!empty($_POST['travel_date'])) {
        update_post_meta($order_id, 'travel_date', sanitize_text_field($_POST['travel_date']));
    }
    
    if (!empty($_POST['travelers'])) {
        update_post_meta($order_id, 'travelers', sanitize_text_field($_POST['travelers']));
    }
}
add_action('woocommerce_checkout_update_order_meta', 'tznew_checkout_field_update_order_meta');

/**
 * Display custom fields on order admin page
 */
function tznew_display_admin_order_meta($order) {
    $travel_date = get_post_meta($order->get_id(), 'travel_date', true);
    $travelers = get_post_meta($order->get_id(), 'travelers', true);
    
    if (!empty($travel_date)) {
        echo '<p><strong>' . __('Preferred Travel Date', 'tznew') . ':</strong> ' . esc_html($travel_date) . '</p>';
    }
    
    if (!empty($travelers)) {
        echo '<p><strong>' . __('Number of Travelers', 'tznew') . ':</strong> ' . esc_html($travelers) . '</p>';
    }
}
add_action('woocommerce_admin_order_data_after_billing_address', 'tznew_display_admin_order_meta', 10, 1);

/**
 * Add custom fields to order emails
 */
function tznew_email_order_meta_fields($fields, $sent_to_admin, $order) {
    $travel_date = get_post_meta($order->get_id(), 'travel_date', true);
    $travelers = get_post_meta($order->get_id(), 'travelers', true);
    
    if (!empty($travel_date)) {
        $fields['travel_date'] = array(
            'label' => __('Preferred Travel Date', 'tznew'),
            'value' => $travel_date,
        );
    }
    
    if (!empty($travelers)) {
        $fields['travelers'] = array(
            'label' => __('Number of Travelers', 'tznew'),
            'value' => $travelers,
        );
    }
    
    return $fields;
}
add_filter('woocommerce_email_order_meta_fields', 'tznew_email_order_meta_fields', 10, 3);

/**
 * Add custom information to thank you page
 */
function tznew_thankyou_page($order_id) {
    // Check if WooCommerce is active and functions are available
    if (!class_exists('WooCommerce') || !function_exists('wc_get_order')) {
        return;
    }
    
    // Ensure order ID is valid
    if (empty($order_id)) {
        return;
    }
    
    // Get order object with additional safety check
    if (function_exists('wc_get_order')) {
        /** @var WC_Order|false $order */
        $order = call_user_func('wc_get_order', $order_id);
    } else {
        return;
    }
    
    if (!$order) {
        return;
    }
    
    echo '<div class="woocommerce-thankyou-details">';
    echo '<h2>' . __('Booking Details', 'tznew') . '</h2>';
    
    $travel_date = get_post_meta($order_id, 'travel_date', true);
    $travelers = get_post_meta($order_id, 'travelers', true);
    
    if (!empty($travel_date)) {
        echo '<p><strong>' . __('Preferred Travel Date', 'tznew') . ':</strong> ' . esc_html($travel_date) . '</p>';
    }
    
    if (!empty($travelers)) {
        echo '<p><strong>' . __('Number of Travelers', 'tznew') . ':</strong> ' . esc_html($travelers) . '</p>';
    }
    
    echo '<p>' . __('Thank you for your booking! We will contact you shortly to confirm your reservation details.', 'tznew') . '</p>';
    
    // Get company email from theme settings
    $company_email = tznew_get_company_email();
    if ($company_email) {
        echo '<p>' . __('If you have any questions, please contact us at', 'tznew') . ' <a href="mailto:' . esc_attr($company_email) . '">' . esc_html($company_email) . '</a></p>';
    }
    echo '</div>';
}
add_action('woocommerce_thankyou', 'tznew_thankyou_page', 10);

/**
 * Add custom product data tabs for tour/trekking products
 */
function tznew_tour_product_tabs($tabs) {
    global $post;
    
    // Check if this product is linked to a tour or trekking
    $linked_tour = get_post_meta($post->ID, '_linked_tour', true);
    $linked_trekking = get_post_meta($post->ID, '_linked_trekking', true);
    
    if ($linked_tour || $linked_trekking) {
        $linked_post_id = $linked_tour ? $linked_tour : $linked_trekking;
        $post_type = $linked_tour ? 'tours' : 'trekking';
        
        // Add itinerary tab
        $tabs['itinerary'] = array(
            'title'    => __('Itinerary', 'tznew'),
            'priority' => 20,
            'callback' => 'tznew_itinerary_tab_content',
            'post_id'  => $linked_post_id,
            'post_type' => $post_type,
        );
        
        // Add includes/excludes tab
        $tabs['includes_excludes'] = array(
            'title'    => __('Includes/Excludes', 'tznew'),
            'priority' => 30,
            'callback' => 'tznew_includes_excludes_tab_content',
            'post_id'  => $linked_post_id,
            'post_type' => $post_type,
        );
    }
    
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'tznew_tour_product_tabs');

/**
 * Itinerary tab content
 */
function tznew_itinerary_tab_content($tab) {
    $post_id = $tab['post_id'];
    $post_type = $tab['post_type'];
    
    echo '<h2>' . __('Itinerary', 'tznew') . '</h2>';
    
    if (function_exists('tznew_have_rows_safe') && tznew_have_rows_safe('itinerary', $post_id)) {
        echo '<div class="itinerary-wrapper">';
        
        while (tznew_have_rows_safe('itinerary', $post_id)) {
            tznew_the_row_safe();
            
            $day = tznew_get_sub_field_safe('day');
            $title = tznew_get_sub_field_safe('title');
            $description = tznew_get_sub_field_safe('description');
            
            echo '<div class="itinerary-day">';
            echo '<div class="itinerary-day-header">';
            echo '<h3>Day ' . esc_html($day) . ': ' . esc_html($title) . '</h3>';
            echo '</div>';
            echo '<div class="itinerary-day-content">';
            echo wpautop($description);
            
            // Additional fields based on post type
            if ($post_type === 'trekking') {
                $distance = tznew_get_sub_field_safe('distance');
                $altitude = tznew_get_sub_field_safe('altitude');
                $duration = tznew_get_sub_field_safe('duration');
                
                if ($distance || $altitude || $duration) {
                    echo '<div class="itinerary-details">';
                    
                    if ($distance) {
                        echo '<span class="itinerary-distance"><i class="fas fa-route"></i> ' . esc_html($distance) . '</span>';
                    }
                    
                    if ($altitude) {
                        echo '<span class="itinerary-altitude"><i class="fas fa-mountain"></i> ' . esc_html($altitude) . '</span>';
                    }
                    
                    if ($duration) {
                        echo '<span class="itinerary-duration"><i class="fas fa-clock"></i> ' . esc_html($duration) . '</span>';
                    }
                    
                    echo '</div>';
                }
            } elseif ($post_type === 'tours') {
                $meals = tznew_get_sub_field_safe('meals');
                $accommodation = tznew_get_sub_field_safe('accommodation');
                
                if ($meals || $accommodation) {
                    echo '<div class="itinerary-details">';
                    
                    if ($meals) {
                        echo '<span class="itinerary-meals"><i class="fas fa-utensils"></i> ' . esc_html($meals) . '</span>';
                    }
                    
                    if ($accommodation) {
                        echo '<span class="itinerary-accommodation"><i class="fas fa-bed"></i> ' . esc_html($accommodation) . '</span>';
                    }
                    
                    echo '</div>';
                }
            }
            
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
    } else {
        echo '<p>' . __('No itinerary available for this tour.', 'tznew') . '</p>';
    }
}

/**
 * Includes/Excludes tab content
 */
function tznew_includes_excludes_tab_content($tab) {
    $post_id = $tab['post_id'];
    
    $includes = tznew_get_field_safe('includes', $post_id);
		$excludes = tznew_get_field_safe('excludes', $post_id);
    
    echo '<div class="includes-excludes-wrapper">';
    
    if ($includes) {
        echo '<div class="includes">';
        echo '<h3>' . __('What\'s Included', 'tznew') . '</h3>';
        echo wpautop($includes);
        echo '</div>';
    }
    
    if ($excludes) {
        echo '<div class="excludes">';
        echo '<h3>' . __('What\'s Not Included', 'tznew') . '</h3>';
        echo wpautop($excludes);
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Add custom meta box to products to link them to tours/trekking
 */
function tznew_add_product_meta_box() {
    add_meta_box(
        'tznew_product_tour_link',
        __('Link to Tour/Trekking', 'tznew'),
        'tznew_product_tour_link_callback',
        'product',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'tznew_add_product_meta_box');

/**
 * Meta box callback
 */
function tznew_product_tour_link_callback($post) {
    wp_nonce_field('tznew_product_tour_link', 'tznew_product_tour_link_nonce');
    
    $linked_tour = get_post_meta($post->ID, '_linked_tour', true);
    $linked_trekking = get_post_meta($post->ID, '_linked_trekking', true);
    
    // Get tours
    $tours = get_posts(array(
        'post_type' => 'tours',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ));
    
    // Get trekking
    $trekkings = get_posts(array(
        'post_type' => 'trekking',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ));
    
    echo '<p>' . __('Link this product to a tour or trekking to display its details on the product page.', 'tznew') . '</p>';
    
    echo '<p><strong>' . __('Link to Tour', 'tznew') . '</strong></p>';
    echo '<select name="linked_tour" id="linked_tour">';
    echo '<option value="">' . __('-- Select Tour --', 'tznew') . '</option>';
    
    foreach ($tours as $tour) {
        echo '<option value="' . esc_attr($tour->ID) . '"' . selected($linked_tour, $tour->ID, false) . '>' . esc_html($tour->post_title) . '</option>';
    }
    
    echo '</select>';
    
    echo '<p><strong>' . __('Link to Trekking', 'tznew') . '</strong></p>';
    echo '<select name="linked_trekking" id="linked_trekking">';
    echo '<option value="">' . __('-- Select Trekking --', 'tznew') . '</option>';
    
    foreach ($trekkings as $trekking) {
        echo '<option value="' . esc_attr($trekking->ID) . '"' . selected($linked_trekking, $trekking->ID, false) . '>' . esc_html($trekking->post_title) . '</option>';
    }
    
    echo '</select>';
    
    echo '<p class="description">' . __('Note: Select only one option (either Tour or Trekking).', 'tznew') . '</p>';
}

/**
 * Save meta box data
 */
function tznew_save_product_tour_link($post_id) {
    if (!isset($_POST['tznew_product_tour_link_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['tznew_product_tour_link_nonce'], 'tznew_product_tour_link')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save linked tour
    if (isset($_POST['linked_tour'])) {
        update_post_meta($post_id, '_linked_tour', sanitize_text_field($_POST['linked_tour']));
    }
    
    // Save linked trekking
    if (isset($_POST['linked_trekking'])) {
        update_post_meta($post_id, '_linked_trekking', sanitize_text_field($_POST['linked_trekking']));
    }
}
add_action('save_post_product', 'tznew_save_product_tour_link');