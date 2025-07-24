<?php
/**
 * Admin Bookings Management Page
 *
 * @package TZnew
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get current action
$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

// Check for review success message
if (isset($_GET['review_added']) && $_GET['review_added'] == '1') {
    echo '<div class="notice notice-success is-dismissible"><p>Booking review added successfully!</p></div>';
}

// Handle actions
if ($_POST && wp_verify_nonce($_POST['booking_nonce'], 'booking_action')) {
    $post_action = sanitize_text_field($_POST['action']);
    $post_booking_id = intval($_POST['booking_id']);
    
    switch ($post_action) {
        case 'update_status':
            $new_status = sanitize_text_field($_POST['booking_status']);
            wp_set_object_terms($post_booking_id, $new_status, 'booking_status');
            
            // Send notification email if confirmed
            if ($new_status === 'confirmed') {
                $booking = get_post($post_booking_id);
                $customer_email = get_post_meta($post_booking_id, '_customer_email', true);
                
                if ($customer_email) {
                    $subject = 'Booking Confirmed - ' . get_bloginfo('name');
                    $message = "Dear " . get_post_meta($post_booking_id, '_customer_name', true) . ",\n\n";
                    $message .= "Your booking has been confirmed!\n\n";
                    $message .= "Booking Reference: #" . $post_booking_id . "\n";
                    $message .= "Trip: " . get_post_meta($post_booking_id, '_trip_title', true) . "\n";
                    $message .= "Date: " . get_post_meta($post_booking_id, '_preferred_date', true) . "\n\n";
                    $message .= "We will contact you soon with further details.\n\n";
                    $message .= "Best regards,\n" . get_bloginfo('name');
                    
                    wp_mail($customer_email, $subject, $message);
                }
            }
            
            echo '<div class="notice notice-success"><p>Booking status updated successfully!</p></div>';
            break;
            
        case 'confirm_booking':
            wp_set_object_terms($post_booking_id, 'confirmed', 'booking_status');
            
            $customer_email = get_post_meta($post_booking_id, '_customer_email', true);
            $customer_name = get_post_meta($post_booking_id, '_customer_name', true);
            $trip_title = get_post_meta($post_booking_id, '_trip_title', true);
            $confirmation_message = sanitize_textarea_field($_POST['confirmation_message']);
            
            if ($customer_email) {
                $subject = '✅ Booking Confirmed - ' . get_bloginfo('name');
                $message = "Dear " . $customer_name . ",\n\n";
                $message .= "Great news! Your booking has been confirmed.\n\n";
                $message .= "📋 Booking Details:\n";
                $message .= "• Booking Reference: #" . $post_booking_id . "\n";
                $message .= "• Trip: " . $trip_title . "\n";
                $message .= "• Date: " . get_post_meta($post_booking_id, '_preferred_date', true) . "\n";
                $message .= "• Group Size: " . get_post_meta($post_booking_id, '_group_size', true) . " people\n\n";
                
                if ($confirmation_message) {
                    $message .= $confirmation_message . "\n\n";
                }
                
                $message .= "We will contact you soon with detailed itinerary and further instructions.\n\n";
                $message .= "Thank you for choosing " . get_bloginfo('name') . "!\n\n";
                $message .= "Best regards,\n" . get_bloginfo('name');
                
                wp_mail($customer_email, $subject, $message);
                
                // Log the action
                $existing_notes = get_post_meta($post_booking_id, '_admin_notes', true);
                $new_note = date('Y-m-d H:i:s') . ' - ' . wp_get_current_user()->display_name . ': Booking confirmed and confirmation email sent';
                $updated_notes = $existing_notes ? $existing_notes . "\n" . $new_note : $new_note;
                update_post_meta($post_booking_id, '_admin_notes', $updated_notes);
            }
            
            echo '<div class="notice notice-success"><p>Booking confirmed and confirmation email sent!</p></div>';
            break;
            
        case 'send_payment_link':
            $payment_amount = floatval($_POST['payment_amount']);
            $payment_description = sanitize_text_field($_POST['payment_description']);
            $payment_due_date = sanitize_text_field($_POST['payment_due_date']);
            $payment_message = sanitize_textarea_field($_POST['payment_message']);
            
            $customer_email = get_post_meta($post_booking_id, '_customer_email', true);
            $customer_name = get_post_meta($post_booking_id, '_customer_name', true);
            
            if ($customer_email) {
                $subject = '💳 Payment Request - Booking #' . $post_booking_id;
                $message = "Dear " . $customer_name . ",\n\n";
                $message .= $payment_message ? $payment_message . "\n\n" : "Please complete your payment for the following booking:\n\n";
                $message .= "📋 Payment Details:\n";
                $message .= "• Amount: $" . number_format($payment_amount, 2) . "\n";
                $message .= "• Description: " . $payment_description . "\n";
                $message .= "• Due Date: " . date('F j, Y', strtotime($payment_due_date)) . "\n";
                $message .= "• Booking Reference: #" . $post_booking_id . "\n\n";
                $message .= "Payment Link: [Payment gateway integration needed]\n\n";
                $message .= "If you have any questions, please don't hesitate to contact us.\n\n";
                $message .= "Best regards,\n" . get_bloginfo('name');
                
                wp_mail($customer_email, $subject, $message);
                
                // Store payment request details
                update_post_meta($post_booking_id, '_payment_amount', $payment_amount);
                update_post_meta($post_booking_id, '_payment_description', $payment_description);
                update_post_meta($post_booking_id, '_payment_due_date', $payment_due_date);
                
                // Log the action
                $existing_notes = get_post_meta($post_booking_id, '_admin_notes', true);
                $new_note = date('Y-m-d H:i:s') . ' - ' . wp_get_current_user()->display_name . ': Payment link sent ($' . number_format($payment_amount, 2) . ')';
                $updated_notes = $existing_notes ? $existing_notes . "\n" . $new_note : $new_note;
                update_post_meta($post_booking_id, '_admin_notes', $updated_notes);
            }
            
            echo '<div class="notice notice-success"><p>Payment link sent successfully!</p></div>';
            break;
            
        case 'reject_booking':
            wp_set_object_terms($post_booking_id, 'rejected', 'booking_status');
            
            $rejection_reason = sanitize_text_field($_POST['rejection_reason']);
            $rejection_message = sanitize_textarea_field($_POST['rejection_message']);
            
            $customer_email = get_post_meta($post_booking_id, '_customer_email', true);
            $customer_name = get_post_meta($post_booking_id, '_customer_name', true);
            
            if ($customer_email) {
                $subject = 'Booking Update - ' . get_bloginfo('name');
                $message = "Dear " . $customer_name . ",\n\n";
                $message .= $rejection_message . "\n\n";
                $message .= "Booking Reference: #" . $post_booking_id . "\n";
                $message .= "Reason: " . ucfirst(str_replace('_', ' ', $rejection_reason)) . "\n\n";
                $message .= "We apologize for any inconvenience and appreciate your understanding.\n\n";
                $message .= "Please feel free to contact us for alternative options or future bookings.\n\n";
                $message .= "Best regards,\n" . get_bloginfo('name');
                
                wp_mail($customer_email, $subject, $message);
                
                // Store rejection details
                update_post_meta($post_booking_id, '_rejection_reason', $rejection_reason);
                update_post_meta($post_booking_id, '_rejection_message', $rejection_message);
                
                // Log the action
                $existing_notes = get_post_meta($post_booking_id, '_admin_notes', true);
                $new_note = date('Y-m-d H:i:s') . ' - ' . wp_get_current_user()->display_name . ': Booking rejected - ' . $rejection_reason;
                $updated_notes = $existing_notes ? $existing_notes . "\n" . $new_note : $new_note;
                update_post_meta($post_booking_id, '_admin_notes', $updated_notes);
            }
            
            echo '<div class="notice notice-error"><p>Booking rejected and notification sent to customer.</p></div>';
            break;
            
        case 'send_quote':
            $quote_amount = floatval($_POST['quote_amount']);
            $quote_validity = sanitize_text_field($_POST['quote_validity']);
            $quote_inclusions = sanitize_textarea_field($_POST['quote_inclusions']);
            $quote_exclusions = sanitize_textarea_field($_POST['quote_exclusions']);
            $quote_message = sanitize_textarea_field($_POST['quote_message']);
            
            $customer_email = get_post_meta($post_booking_id, '_customer_email', true);
            $customer_name = get_post_meta($post_booking_id, '_customer_name', true);
            $trip_title = get_post_meta($post_booking_id, '_trip_title', true);
            
            if ($customer_email) {
                $subject = '💰 Quote for Your Trip - ' . get_bloginfo('name');
                $message = "Dear " . $customer_name . ",\n\n";
                $message .= $quote_message ? $quote_message . "\n\n" : "Thank you for your interest! Please find our detailed quote below:\n\n";
                $message .= "📋 Quote Details:\n";
                $message .= "• Trip: " . $trip_title . "\n";
                $message .= "• Total Amount: $" . number_format($quote_amount, 2) . "\n";
                $message .= "• Valid Until: " . date('F j, Y', strtotime($quote_validity)) . "\n";
                $message .= "• Booking Reference: #" . $post_booking_id . "\n\n";
                
                if ($quote_inclusions) {
                    $message .= "✅ What's Included:\n" . $quote_inclusions . "\n\n";
                }
                
                if ($quote_exclusions) {
                    $message .= "❌ What's Not Included:\n" . $quote_exclusions . "\n\n";
                }
                
                $message .= "To proceed with this booking, please reply to this email or contact us directly.\n\n";
                $message .= "We look forward to making your trip memorable!\n\n";
                $message .= "Best regards,\n" . get_bloginfo('name');
                
                wp_mail($customer_email, $subject, $message);
                
                // Store quote details
                update_post_meta($post_booking_id, '_quote_amount', $quote_amount);
                update_post_meta($post_booking_id, '_quote_validity', $quote_validity);
                update_post_meta($post_booking_id, '_quote_inclusions', $quote_inclusions);
                update_post_meta($post_booking_id, '_quote_exclusions', $quote_exclusions);
                
                // Log the action
                $existing_notes = get_post_meta($post_booking_id, '_admin_notes', true);
                $new_note = date('Y-m-d H:i:s') . ' - ' . wp_get_current_user()->display_name . ': Quote sent ($' . number_format($quote_amount, 2) . ')';
                $updated_notes = $existing_notes ? $existing_notes . "\n" . $new_note : $new_note;
                update_post_meta($post_booking_id, '_admin_notes', $updated_notes);
            }
            
            echo '<div class="notice notice-success"><p>Quote sent successfully!</p></div>';
            break;
            
        case 'add_note':
            $note = sanitize_textarea_field($_POST['booking_note']);
            $existing_notes = get_post_meta($post_booking_id, '_admin_notes', true);
            $new_note = date('Y-m-d H:i:s') . ' - ' . wp_get_current_user()->display_name . ': ' . $note;
            $updated_notes = $existing_notes ? $existing_notes . "\n" . $new_note : $new_note;
            update_post_meta($post_booking_id, '_admin_notes', $updated_notes);
            
            echo '<div class="notice notice-success"><p>Note added successfully!</p></div>';
            break;
    }
}

// Get bookings
$paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
$search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

$args = [
    'post_type' => 'booking',
    'post_status' => 'publish',
    'posts_per_page' => 20,
    'paged' => $paged,
    'meta_query' => []
];

if ($status_filter) {
    $args['tax_query'] = [[
        'taxonomy' => 'booking_status',
        'field' => 'slug',
        'terms' => $status_filter
    ]];
}

if ($search) {
    $args['meta_query'][] = [
        'relation' => 'OR',
        [
            'key' => '_customer_name',
            'value' => $search,
            'compare' => 'LIKE'
        ],
        [
            'key' => '_customer_email',
            'value' => $search,
            'compare' => 'LIKE'
        ],
        [
            'key' => '_trip_title',
            'value' => $search,
            'compare' => 'LIKE'
        ]
    ];
}

$bookings_query = new WP_Query($args);
$bookings = $bookings_query->posts;
$total_pages = $bookings_query->max_num_pages;

// Get status counts
$status_counts = [];
$statuses = get_terms(['taxonomy' => 'booking_status', 'hide_empty' => false]);
foreach ($statuses as $status) {
    $count_args = [
        'post_type' => 'booking',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'tax_query' => [[
            'taxonomy' => 'booking_status',
            'field' => 'slug',
            'terms' => $status->slug
        ]]
    ];
    $count_query = new WP_Query($count_args);
    $status_counts[$status->slug] = $count_query->found_posts;
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Manage Bookings</h1>
    
    <?php if ($action === 'view' && $booking_id): ?>
        <?php
        $booking = get_post($booking_id);
        if ($booking && $booking->post_type === 'booking'):
            $customer_name = get_post_meta($booking_id, '_customer_name', true);
            $customer_email = get_post_meta($booking_id, '_customer_email', true);
            $customer_phone = get_post_meta($booking_id, '_customer_phone', true);
            $customer_country = get_post_meta($booking_id, '_customer_country', true);
            $trip_title = get_post_meta($booking_id, '_trip_title', true);
            $trip_type = get_post_meta($booking_id, '_trip_type', true);
            $preferred_date = get_post_meta($booking_id, '_preferred_date', true);
            $group_size = get_post_meta($booking_id, '_group_size', true);
            $accommodation = get_post_meta($booking_id, '_accommodation_preference', true);
            $dietary = get_post_meta($booking_id, '_dietary_requirements', true);
            $special_requests = get_post_meta($booking_id, '_special_requests', true);
            $admin_notes = get_post_meta($booking_id, '_admin_notes', true);
            $current_status = wp_get_post_terms($booking_id, 'booking_status');
            $status_name = !empty($current_status) ? $current_status[0]->name : 'Pending';
        ?>
        
        <a href="<?php echo admin_url('admin.php?page=booking-management'); ?>" class="page-title-action">← Back to Bookings</a>
        
        <div class="booking-details" style="margin-top: 20px;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <!-- Main Details -->
                <div class="postbox">
                    <div class="postbox-header">
                        <h2>Booking Details - #<?php echo $booking_id; ?></h2>
                    </div>
                    <div class="inside">
                        <table class="form-table">
                            <tr>
                                <th>Customer Name:</th>
                                <td><?php echo esc_html($customer_name); ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><a href="mailto:<?php echo esc_attr($customer_email); ?>"><?php echo esc_html($customer_email); ?></a></td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td><a href="tel:<?php echo esc_attr($customer_phone); ?>"><?php echo esc_html($customer_phone); ?></a></td>
                            </tr>
                            <tr>
                                <th>Country:</th>
                                <td><?php echo esc_html($customer_country); ?></td>
                            </tr>
                            <tr>
                                <th>Trip/Tour:</th>
                                <td><?php echo esc_html($trip_title); ?></td>
                            </tr>
                            <tr>
                                <th>Trip Type:</th>
                                <td><?php echo esc_html(ucfirst($trip_type)); ?></td>
                            </tr>
                            <tr>
                                <th>Preferred Date:</th>
                                <td><?php echo esc_html(date('F j, Y', strtotime($preferred_date))); ?></td>
                            </tr>
                            <tr>
                                <th>Group Size:</th>
                                <td><?php echo esc_html($group_size); ?> people</td>
                            </tr>
                            <?php if ($accommodation): ?>
                            <tr>
                                <th>Accommodation:</th>
                                <td><?php echo esc_html(ucfirst($accommodation)); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($dietary): ?>
                            <tr>
                                <th>Dietary Requirements:</th>
                                <td><?php echo esc_html($dietary); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($special_requests): ?>
                            <tr>
                                <th>Special Requests:</th>
                                <td><?php echo nl2br(esc_html($special_requests)); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Submitted:</th>
                                <td><?php echo get_the_date('F j, Y g:i A', $booking); ?></td>
                            </tr>
                            <tr>
                                <th>Current Status:</th>
                                <td><span class="booking-status status-<?php echo esc_attr(strtolower(str_replace(' ', '-', $status_name))); ?>"><?php echo esc_html($status_name); ?></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <!-- Actions Sidebar -->
                <div>
                    <!-- Status Update -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h3>Update Status</h3>
                        </div>
                        <div class="inside">
                            <form method="post">
                                <?php wp_nonce_field('booking_action', 'booking_nonce'); ?>
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                                
                                <select name="booking_status" class="widefat">
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?php echo esc_attr($status->slug); ?>" <?php selected(!empty($current_status) ? $current_status[0]->slug : '', $status->slug); ?>>
                                            <?php echo esc_html($status->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                
                                <p class="submit">
                                    <input type="submit" class="button-primary" value="Update Status">
                                </p>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h3>Quick Actions</h3>
                        </div>
                        <div class="inside">
                            <div class="action-grid" style="display: grid; gap: 10px;">
                                <a href="mailto:<?php echo esc_attr($customer_email); ?>?subject=Re: Booking #<?php echo $booking_id; ?>" class="button button-primary" style="text-align: center;">📧 Email Customer</a>
                                
                                <?php if ($customer_phone): ?>
                                <a href="tel:<?php echo esc_attr($customer_phone); ?>" class="button" style="text-align: center;">📞 Call Customer</a>
                                <?php endif; ?>
                                
                                <button type="button" class="button button-secondary" onclick="showConfirmationModal(<?php echo $booking_id; ?>)" style="background: #00a32a; color: white; border-color: #00a32a;">✅ Confirm Booking</button>
                                
                                <button type="button" class="button" onclick="showPaymentLinkModal(<?php echo $booking_id; ?>)" style="background: #0073aa; color: white; border-color: #0073aa;">💳 Send Payment Link</button>
                                
                                <button type="button" class="button" onclick="showRejectionModal(<?php echo $booking_id; ?>)" style="background: #d63638; color: white; border-color: #d63638;">❌ Reject Booking</button>
                                
                                <button type="button" class="button" onclick="showQuoteModal(<?php echo $booking_id; ?>)" style="background: #dba617; color: white; border-color: #dba617;">💰 Send Quote</button>
                                
                                <a href="<?php echo admin_url('admin.php?page=booking-management&action=edit&booking_id=' . $booking_id); ?>" class="button">✏️ Edit Booking</a>
                                
                                <button type="button" class="button" onclick="printBooking(<?php echo $booking_id; ?>)">🖨️ Print Details</button>
                                
                                <button type="button" class="button" onclick="showReviewModal(<?php echo $booking_id; ?>)" style="background: #8e44ad; color: white; border-color: #8e44ad;">⭐ Review Booking</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Booking Timeline -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h3>Booking Timeline</h3>
                        </div>
                        <div class="inside">
                            <div class="timeline">
                                <div class="timeline-item completed">
                                    <div class="timeline-marker">✓</div>
                                    <div class="timeline-content">
                                        <strong>Booking Submitted</strong><br>
                                        <small><?php echo get_the_date('M j, Y g:i A', $booking); ?></small>
                                    </div>
                                </div>
                                
                                <?php if ($status_name === 'confirmed' || $status_name === 'completed'): ?>
                                <div class="timeline-item completed">
                                    <div class="timeline-marker">✓</div>
                                    <div class="timeline-content">
                                        <strong>Booking Confirmed</strong><br>
                                        <small>Status: <?php echo esc_html($status_name); ?></small>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($status_name === 'completed'): ?>
                                <div class="timeline-item completed">
                                    <div class="timeline-marker">✓</div>
                                    <div class="timeline-content">
                                        <strong>Trip Completed</strong><br>
                                        <small>Thank you for choosing us!</small>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Admin Notes -->
            <div class="postbox" style="margin-top: 20px;">
                <div class="postbox-header">
                    <h3>Admin Notes</h3>
                </div>
                <div class="inside">
                    <?php if ($admin_notes): ?>
                        <div class="existing-notes" style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 15px; max-height: 200px; overflow-y: auto;">
                            <h4>Previous Notes:</h4>
                            <pre style="white-space: pre-wrap; font-family: inherit;"><?php echo esc_html($admin_notes); ?></pre>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post">
                        <?php wp_nonce_field('booking_action', 'booking_nonce'); ?>
                        <input type="hidden" name="action" value="add_note">
                        <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                        
                        <textarea name="booking_note" rows="4" class="widefat" placeholder="Add a note about this booking..."></textarea>
                        
                        <p class="submit">
                            <input type="submit" class="button-primary" value="Add Note">
                        </p>
                    </form>
                </div>
            </div>
        </div>
        
        <?php else: ?>
            <div class="notice notice-error"><p>Booking not found.</p></div>
        <?php endif; ?>
        
    <?php else: ?>
        <!-- Bookings List -->
        <div class="tablenav top">
            <div class="alignleft actions">
                <form method="get">
                    <input type="hidden" name="page" value="booking-management">
                    
                    <select name="status">
                        <option value="">All Statuses</option>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?php echo esc_attr($status->slug); ?>" <?php selected($status_filter, $status->slug); ?>>
                                <?php echo esc_html($status->name); ?> (<?php echo isset($status_counts[$status->slug]) ? $status_counts[$status->slug] : 0; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <input type="submit" class="button" value="Filter">
                </form>
            </div>
            
            <div class="alignright actions">
                <form method="get">
                    <input type="hidden" name="page" value="booking-management">
                    <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search bookings...">
                    <input type="submit" class="button" value="Search">
                </form>
            </div>
        </div>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Trip/Tour</th>
                    <th>Date</th>
                    <th>Group Size</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($bookings): ?>
                    <?php foreach ($bookings as $booking): ?>
                        <?php
                        $customer_name = get_post_meta($booking->ID, '_customer_name', true);
                        $customer_email = get_post_meta($booking->ID, '_customer_email', true);
                        $trip_title = get_post_meta($booking->ID, '_trip_title', true);
                        $preferred_date = get_post_meta($booking->ID, '_preferred_date', true);
                        $group_size = get_post_meta($booking->ID, '_group_size', true);
                        $booking_status = wp_get_post_terms($booking->ID, 'booking_status');
                        $status_name = !empty($booking_status) ? $booking_status[0]->name : 'Pending';
                        ?>
                        <tr>
                            <td><strong>#<?php echo $booking->ID; ?></strong></td>
                            <td>
                                <strong><?php echo esc_html($customer_name); ?></strong><br>
                                <a href="mailto:<?php echo esc_attr($customer_email); ?>"><?php echo esc_html($customer_email); ?></a>
                            </td>
                            <td><?php echo esc_html($trip_title); ?></td>
                            <td><?php echo esc_html(date('M j, Y', strtotime($preferred_date))); ?></td>
                            <td><?php echo esc_html($group_size); ?> people</td>
                            <td><span class="booking-status status-<?php echo esc_attr(strtolower(str_replace(' ', '-', $status_name))); ?>"><?php echo esc_html($status_name); ?></span></td>
                            <td><?php echo get_the_date('M j, Y', $booking); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=booking-management&action=view&booking_id=' . $booking->ID); ?>" class="button button-small">View</a>
                                <a href="mailto:<?php echo esc_attr($customer_email); ?>?subject=Re: Booking #<?php echo $booking->ID; ?>" class="button button-small">Email</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No bookings found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if ($total_pages > 1): ?>
            <div class="tablenav bottom">
                <div class="tablenav-pages">
                    <?php
                    $pagination_args = [
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => '&laquo;',
                        'next_text' => '&raquo;',
                        'total' => $total_pages,
                        'current' => $paged
                    ];
                    echo paginate_links($pagination_args);
                    ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Modal Dialogs -->
<div id="confirmation-modal" class="booking-modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>✅ Confirm Booking</h3>
            <span class="modal-close" onclick="closeModal('confirmation-modal')">&times;</span>
        </div>
        <div class="modal-body">
            <form id="confirmation-form" method="post">
                <?php wp_nonce_field('booking_action', 'booking_nonce'); ?>
                <input type="hidden" name="action" value="confirm_booking">
                <input type="hidden" name="booking_id" id="confirm-booking-id">
                
                <p><strong>Confirm this booking and send confirmation email to customer?</strong></p>
                
                <label for="confirmation-message">Confirmation Message (optional):</label>
                <textarea name="confirmation_message" id="confirmation-message" rows="4" class="widefat" placeholder="Dear customer, your booking has been confirmed! We will contact you soon with further details..."></textarea>
                
                <div class="modal-actions">
                    <input type="submit" class="button-primary" value="Confirm Booking">
                    <button type="button" class="button" onclick="closeModal('confirmation-modal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="payment-modal" class="booking-modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>💳 Send Payment Link</h3>
            <span class="modal-close" onclick="closeModal('payment-modal')">&times;</span>
        </div>
        <div class="modal-body">
            <form id="payment-form" method="post">
                <?php wp_nonce_field('booking_action', 'booking_nonce'); ?>
                <input type="hidden" name="action" value="send_payment_link">
                <input type="hidden" name="booking_id" id="payment-booking-id">
                
                <label for="payment-amount">Payment Amount (USD):</label>
                <input type="number" name="payment_amount" id="payment-amount" class="widefat" step="0.01" required>
                
                <label for="payment-description">Payment Description:</label>
                <input type="text" name="payment_description" id="payment-description" class="widefat" placeholder="Deposit for booking #..." required>
                
                <label for="payment-due-date">Due Date:</label>
                <input type="date" name="payment_due_date" id="payment-due-date" class="widefat" required>
                
                <label for="payment-message">Message to Customer:</label>
                <textarea name="payment_message" id="payment-message" rows="4" class="widefat" placeholder="Please complete your payment using the secure link below..."></textarea>
                
                <div class="modal-actions">
                    <input type="submit" class="button-primary" value="Send Payment Link">
                    <button type="button" class="button" onclick="closeModal('payment-modal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="rejection-modal" class="booking-modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>❌ Reject Booking</h3>
            <span class="modal-close" onclick="closeModal('rejection-modal')">&times;</span>
        </div>
        <div class="modal-body">
            <form id="rejection-form" method="post">
                <?php wp_nonce_field('booking_action', 'booking_nonce'); ?>
                <input type="hidden" name="action" value="reject_booking">
                <input type="hidden" name="booking_id" id="reject-booking-id">
                
                <label for="rejection-reason">Reason for Rejection:</label>
                <select name="rejection_reason" id="rejection-reason" class="widefat" required>
                    <option value="">Select a reason...</option>
                    <option value="dates_unavailable">Requested dates not available</option>
                    <option value="capacity_full">Trip at full capacity</option>
                    <option value="requirements_not_met">Requirements not met</option>
                    <option value="payment_issues">Payment issues</option>
                    <option value="other">Other (specify below)</option>
                </select>
                
                <label for="rejection-message">Message to Customer:</label>
                <textarea name="rejection_message" id="rejection-message" rows="5" class="widefat" placeholder="We regret to inform you that we cannot accommodate your booking request..." required></textarea>
                
                <div class="modal-actions">
                    <input type="submit" class="button-primary" value="Reject Booking" style="background: #d63638; border-color: #d63638;">
                    <button type="button" class="button" onclick="closeModal('rejection-modal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="quote-modal" class="booking-modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>💰 Send Quote</h3>
            <span class="modal-close" onclick="closeModal('quote-modal')">&times;</span>
        </div>
        <div class="modal-body">
            <form id="quote-form" method="post">
                <?php wp_nonce_field('booking_action', 'booking_nonce'); ?>
                <input type="hidden" name="action" value="send_quote">
                <input type="hidden" name="booking_id" id="quote-booking-id">
                
                <label for="quote-amount">Total Quote Amount (USD):</label>
                <input type="number" name="quote_amount" id="quote-amount" class="widefat" step="0.01" required>
                
                <label for="quote-validity">Quote Valid Until:</label>
                <input type="date" name="quote_validity" id="quote-validity" class="widefat" required>
                
                <label for="quote-inclusions">What's Included:</label>
                <textarea name="quote_inclusions" id="quote-inclusions" rows="3" class="widefat" placeholder="• Accommodation\n• Meals\n• Transportation\n• Guide services"></textarea>
                
                <label for="quote-exclusions">What's Not Included:</label>
                <textarea name="quote_exclusions" id="quote-exclusions" rows="3" class="widefat" placeholder="• International flights\n• Personal expenses\n• Travel insurance"></textarea>
                
                <label for="quote-message">Message to Customer:</label>
                <textarea name="quote_message" id="quote-message" rows="4" class="widefat" placeholder="Thank you for your interest! Please find our detailed quote below..."></textarea>
                
                <div class="modal-actions">
                    <input type="submit" class="button-primary" value="Send Quote">
                    <button type="button" class="button" onclick="closeModal('quote-modal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="review-modal" class="booking-modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>⭐ Review Booking</h3>
            <span class="modal-close" onclick="closeModal('review-modal')">&times;</span>
        </div>
        <div class="modal-body">
            <form id="review-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('booking_action', 'booking_nonce'); ?>
                <input type="hidden" name="action" value="submit_booking_review">
                <input type="hidden" name="booking_id" id="review-booking-id">
                
                <label for="review-rating">Overall Rating:</label>
                <div class="rating-input" style="margin-bottom: 15px;">
                    <input type="radio" name="review_rating" id="star5" value="5">
                    <label for="star5" class="star">★</label>
                    <input type="radio" name="review_rating" id="star4" value="4">
                    <label for="star4" class="star">★</label>
                    <input type="radio" name="review_rating" id="star3" value="3">
                    <label for="star3" class="star">★</label>
                    <input type="radio" name="review_rating" id="star2" value="2">
                    <label for="star2" class="star">★</label>
                    <input type="radio" name="review_rating" id="star1" value="1">
                    <label for="star1" class="star">★</label>
                </div>
                
                <label for="review-service-quality">Service Quality:</label>
                <select name="review_service_quality" id="review-service-quality" class="widefat" required>
                    <option value="">Select rating...</option>
                    <option value="excellent">Excellent</option>
                    <option value="good">Good</option>
                    <option value="average">Average</option>
                    <option value="poor">Poor</option>
                </select>
                
                <label for="review-communication">Communication:</label>
                <select name="review_communication" id="review-communication" class="widefat" required>
                    <option value="">Select rating...</option>
                    <option value="excellent">Excellent</option>
                    <option value="good">Good</option>
                    <option value="average">Average</option>
                    <option value="poor">Poor</option>
                </select>
                
                <label for="review-timeliness">Response Timeliness:</label>
                <select name="review_timeliness" id="review-timeliness" class="widefat" required>
                    <option value="">Select rating...</option>
                    <option value="excellent">Excellent</option>
                    <option value="good">Good</option>
                    <option value="average">Average</option>
                    <option value="poor">Poor</option>
                </select>
                
                <label for="review-professionalism">Professionalism:</label>
                <select name="review_professionalism" id="review-professionalism" class="widefat" required>
                    <option value="">Select rating...</option>
                    <option value="excellent">Excellent</option>
                    <option value="good">Good</option>
                    <option value="average">Average</option>
                    <option value="poor">Poor</option>
                </select>
                
                <label for="review-comments">Review Comments:</label>
                <textarea name="review_comments" id="review-comments" rows="4" class="widefat" placeholder="Share your thoughts about this booking experience..."></textarea>
                
                <label for="review-recommendations">Recommendations for Improvement:</label>
                <textarea name="review_recommendations" id="review-recommendations" rows="3" class="widefat" placeholder="Any suggestions for improving our service?"></textarea>
                
                <label for="review-follow-up">Follow-up Required:</label>
                <select name="review_follow_up" id="review-follow-up" class="widefat">
                    <option value="no">No follow-up needed</option>
                    <option value="yes">Yes, follow-up required</option>
                    <option value="urgent">Urgent follow-up needed</option>
                </select>
                
                <div class="modal-actions">
                    <input type="submit" class="button-primary" value="Save Review">
                    <button type="button" class="button" onclick="closeModal('review-modal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.booking-status {
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}
.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }
.status-completed { background: #d1ecf1; color: #0c5460; }
.status-rejected { background: #f8d7da; color: #721c24; }

/* Timeline Styles */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-left: 2px solid #e0e0e0;
}

.timeline-item.completed {
    border-left-color: #00a32a;
}

.timeline-marker {
    position: absolute;
    left: -8px;
    top: 0;
    width: 16px;
    height: 16px;
    background: #00a32a;
    border-radius: 50%;
    color: white;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.timeline-content {
    padding-left: 20px;
}

/* Modal Styles */
.booking-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 100000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
}

.modal-header h3 {
    margin: 0;
    color: #2c3e50;
}

.modal-close {
    font-size: 24px;
    cursor: pointer;
    color: #666;
    line-height: 1;
}

.modal-close:hover {
    color: #000;
}

.modal-body {
    padding: 20px;
}

.modal-body label {
    display: block;
    margin: 15px 0 5px 0;
    font-weight: bold;
    color: #2c3e50;
}

.modal-body input,
.modal-body select,
.modal-body textarea {
    margin-bottom: 10px;
}

.modal-actions {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #e0e0e0;
    text-align: right;
}

.modal-actions .button {
    margin-left: 10px;
}

.action-grid .button {
    margin-bottom: 5px;
    font-weight: 500;
}

/* Star Rating Styles */
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input .star {
    font-size: 24px;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

.rating-input .star:hover,
.rating-input .star:hover ~ .star {
    color: #ffc107;
}

.rating-input input[type="radio"]:checked ~ .star {
    color: #ffc107;
}

.rating-input input[type="radio"]:checked + .star {
    color: #ffc107;
}
</style>

<script>
function showConfirmationModal(bookingId) {
    document.getElementById('confirm-booking-id').value = bookingId;
    document.getElementById('confirmation-modal').style.display = 'flex';
}

function showPaymentLinkModal(bookingId) {
    document.getElementById('payment-booking-id').value = bookingId;
    document.getElementById('payment-modal').style.display = 'flex';
}

function showRejectionModal(bookingId) {
    document.getElementById('reject-booking-id').value = bookingId;
    document.getElementById('rejection-modal').style.display = 'flex';
}

function showQuoteModal(bookingId) {
    document.getElementById('quote-booking-id').value = bookingId;
    document.getElementById('quote-modal').style.display = 'flex';
}

function showReviewModal(bookingId) {
    document.getElementById('review-booking-id').value = bookingId;
    document.getElementById('review-modal').style.display = 'flex';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function printBooking(bookingId) {
    window.open('<?php echo admin_url('admin.php?page=booking-management&action=print&booking_id='); ?>' + bookingId, '_blank');
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('booking-modal')) {
        e.target.style.display = 'none';
    }
});
</script>