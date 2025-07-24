<?php
/**
 * Admin Inquiries Management Page
 *
 * @package TZnew
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get current action
$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
$inquiry_id = isset($_GET['inquiry_id']) ? intval($_GET['inquiry_id']) : 0;

// Handle actions
if ($_POST && wp_verify_nonce($_POST['inquiry_nonce'], 'inquiry_action')) {
    $post_action = sanitize_text_field($_POST['action']);
    $post_inquiry_id = intval($_POST['inquiry_id']);
    
    switch ($post_action) {
        case 'update_status':
            $new_status = sanitize_text_field($_POST['inquiry_status']);
            wp_set_object_terms($post_inquiry_id, $new_status, 'inquiry_status');
            echo '<div class="notice notice-success"><p>Inquiry status updated successfully!</p></div>';
            break;
            
        case 'confirm_inquiry':
            wp_set_object_terms($post_inquiry_id, 'confirmed', 'inquiry_status');
            
            $customer_email = get_post_meta($post_inquiry_id, '_customer_email', true);
            $customer_name = get_post_meta($post_inquiry_id, '_customer_name', true);
            $related_tour = get_post_meta($post_inquiry_id, '_related_tour', true);
            $confirmation_message = sanitize_textarea_field($_POST['confirmation_message']);
            
            if ($customer_email) {
                $subject = '✅ Inquiry Confirmed - ' . get_bloginfo('name');
                $message = "Dear " . $customer_name . ",\n\n";
                $message .= "Thank you for your inquiry! We're excited to help you plan your trip.\n\n";
                $message .= "📋 Inquiry Details:\n";
                $message .= "• Inquiry Reference: #" . $post_inquiry_id . "\n";
                $message .= "• Tour: " . get_the_title($related_tour) . "\n";
                $message .= "• Travel Dates: " . get_post_meta($post_inquiry_id, '_travel_dates', true) . "\n";
                $message .= "• Group Size: " . get_post_meta($post_inquiry_id, '_group_size', true) . " people\n\n";
                
                if ($confirmation_message) {
                    $message .= $confirmation_message . "\n\n";
                }
                
                $message .= "We will prepare a detailed itinerary and quote for you shortly.\n\n";
                $message .= "Thank you for choosing " . get_bloginfo('name') . "!\n\n";
                $message .= "Best regards,\n" . get_bloginfo('name');
                
                wp_mail($customer_email, $subject, $message);
                
                // Log the action
                $existing_notes = get_post_meta($post_inquiry_id, '_admin_notes', true);
                $new_note = date('Y-m-d H:i:s') . ' - ' . wp_get_current_user()->display_name . ': Inquiry confirmed and confirmation email sent';
                $updated_notes = $existing_notes ? $existing_notes . "\n" . $new_note : $new_note;
                update_post_meta($post_inquiry_id, '_admin_notes', $updated_notes);
            }
            
            echo '<div class="notice notice-success"><p>Inquiry confirmed and confirmation email sent!</p></div>';
            break;
            
        case 'reject_inquiry':
            wp_set_object_terms($post_inquiry_id, 'rejected', 'inquiry_status');
            
            $rejection_reason = sanitize_text_field($_POST['rejection_reason']);
            $rejection_message = sanitize_textarea_field($_POST['rejection_message']);
            
            $customer_email = get_post_meta($post_inquiry_id, '_customer_email', true);
            $customer_name = get_post_meta($post_inquiry_id, '_customer_name', true);
            
            if ($customer_email) {
                $subject = 'Inquiry Update - ' . get_bloginfo('name');
                $message = "Dear " . $customer_name . ",\n\n";
                $message .= $rejection_message . "\n\n";
                $message .= "Inquiry Reference: #" . $post_inquiry_id . "\n";
                $message .= "Reason: " . ucfirst(str_replace('_', ' ', $rejection_reason)) . "\n\n";
                $message .= "We apologize for any inconvenience and appreciate your understanding.\n\n";
                $message .= "Please feel free to contact us for alternative options or future inquiries.\n\n";
                $message .= "Best regards,\n" . get_bloginfo('name');
                
                wp_mail($customer_email, $subject, $message);
                
                // Store rejection details
                update_post_meta($post_inquiry_id, '_rejection_reason', $rejection_reason);
                update_post_meta($post_inquiry_id, '_rejection_message', $rejection_message);
                
                // Log the action
                $existing_notes = get_post_meta($post_inquiry_id, '_admin_notes', true);
                $new_note = date('Y-m-d H:i:s') . ' - ' . wp_get_current_user()->display_name . ': Inquiry rejected - ' . $rejection_reason;
                $updated_notes = $existing_notes ? $existing_notes . "\n" . $new_note : $new_note;
                update_post_meta($post_inquiry_id, '_admin_notes', $updated_notes);
            }
            
            echo '<div class="notice notice-error"><p>Inquiry rejected and notification sent to customer.</p></div>';
            break;
            
        case 'send_quote':
            $quote_amount = floatval($_POST['quote_amount']);
            $quote_validity = sanitize_text_field($_POST['quote_validity']);
            $quote_inclusions = sanitize_textarea_field($_POST['quote_inclusions']);
            $quote_exclusions = sanitize_textarea_field($_POST['quote_exclusions']);
            $quote_message = sanitize_textarea_field($_POST['quote_message']);
            
            $customer_email = get_post_meta($post_inquiry_id, '_customer_email', true);
            $customer_name = get_post_meta($post_inquiry_id, '_customer_name', true);
            $related_tour = get_post_meta($post_inquiry_id, '_related_tour', true);
            $tour_title = get_the_title($related_tour);
            
            if ($customer_email) {
                $subject = '💰 Quote for Your Trip - ' . get_bloginfo('name');
                $message = "Dear " . $customer_name . ",\n\n";
                $message .= $quote_message ? $quote_message . "\n\n" : "Thank you for your inquiry! Please find our detailed quote below:\n\n";
                $message .= "📋 Quote Details:\n";
                $message .= "• Tour: " . $tour_title . "\n";
                $message .= "• Total Amount: $" . number_format($quote_amount, 2) . "\n";
                $message .= "• Valid Until: " . date('F j, Y', strtotime($quote_validity)) . "\n";
                $message .= "• Inquiry Reference: #" . $post_inquiry_id . "\n\n";
                
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
                update_post_meta($post_inquiry_id, '_quote_amount', $quote_amount);
                update_post_meta($post_inquiry_id, '_quote_validity', $quote_validity);
                update_post_meta($post_inquiry_id, '_quote_inclusions', $quote_inclusions);
                update_post_meta($post_inquiry_id, '_quote_exclusions', $quote_exclusions);
                
                // Log the action
                $existing_notes = get_post_meta($post_inquiry_id, '_admin_notes', true);
                $new_note = date('Y-m-d H:i:s') . ' - ' . wp_get_current_user()->display_name . ': Quote sent ($' . number_format($quote_amount, 2) . ')';
                $updated_notes = $existing_notes ? $existing_notes . "\n" . $new_note : $new_note;
                update_post_meta($post_inquiry_id, '_admin_notes', $updated_notes);
            }
            
            echo '<div class="notice notice-success"><p>Quote sent successfully!</p></div>';
            break;
            
        case 'add_note':
            $note = sanitize_textarea_field($_POST['inquiry_note']);
            $existing_notes = get_post_meta($post_inquiry_id, '_admin_notes', true);
            $new_note = date('Y-m-d H:i:s') . ' - ' . wp_get_current_user()->display_name . ': ' . $note;
            $updated_notes = $existing_notes ? $existing_notes . "\n" . $new_note : $new_note;
            update_post_meta($post_inquiry_id, '_admin_notes', $updated_notes);
            
            echo '<div class="notice notice-success"><p>Note added successfully!</p></div>';
            break;
            
        case 'send_reply':
            $reply_subject = sanitize_text_field($_POST['reply_subject']);
            $reply_message = sanitize_textarea_field($_POST['reply_message']);
            $customer_email = get_post_meta($post_inquiry_id, '_customer_email', true);
            
            if ($customer_email && $reply_message) {
                $headers = ['Content-Type: text/html; charset=UTF-8'];
                $sent = wp_mail($customer_email, $reply_subject, nl2br($reply_message), $headers);
                
                if ($sent) {
                    // Log the reply
                    $existing_notes = get_post_meta($post_inquiry_id, '_admin_notes', true);
                    $reply_note = date('Y-m-d H:i:s') . ' - ' . wp_get_current_user()->display_name . ': REPLY SENT - ' . $reply_subject;
                    $updated_notes = $existing_notes ? $existing_notes . "\n" . $reply_note : $reply_note;
                    update_post_meta($post_inquiry_id, '_admin_notes', $updated_notes);
                    
                    // Update status to replied if it was new
                    $current_status = wp_get_post_terms($post_inquiry_id, 'inquiry_status');
                    if (empty($current_status) || $current_status[0]->slug === 'new') {
                        wp_set_object_terms($post_inquiry_id, 'replied', 'inquiry_status');
                    }
                    
                    echo '<div class="notice notice-success"><p>Reply sent successfully!</p></div>';
                } else {
                    echo '<div class="notice notice-error"><p>Failed to send reply. Please try again.</p></div>';
                }
            }
            break;
    }
}

// Get inquiries
$paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
$search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

$args = [
    'post_type' => 'inquiry',
    'post_status' => 'publish',
    'posts_per_page' => 20,
    'paged' => $paged,
    'meta_query' => []
];

if ($status_filter) {
    $args['tax_query'] = [[
        'taxonomy' => 'inquiry_status',
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
            'key' => '_subject',
            'value' => $search,
            'compare' => 'LIKE'
        ]
    ];
}

$inquiries_query = new WP_Query($args);
$inquiries = $inquiries_query->posts;
$total_pages = $inquiries_query->max_num_pages;

// Get status counts
$status_counts = [];
$statuses = get_terms(['taxonomy' => 'inquiry_status', 'hide_empty' => false]);
foreach ($statuses as $status) {
    $count_args = [
        'post_type' => 'inquiry',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'tax_query' => [[
            'taxonomy' => 'inquiry_status',
            'field' => 'slug',
            'terms' => $status->slug
        ]]
    ];
    $count_query = new WP_Query($count_args);
    $status_counts[$status->slug] = $count_query->found_posts;
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Manage Inquiries</h1>
    
    <?php if ($action === 'view' && $inquiry_id): ?>
        <?php
        $inquiry = get_post($inquiry_id);
        if ($inquiry && $inquiry->post_type === 'inquiry'):
            $customer_name = get_post_meta($inquiry_id, '_customer_name', true);
            $customer_email = get_post_meta($inquiry_id, '_customer_email', true);
            $customer_phone = get_post_meta($inquiry_id, '_customer_phone', true);
            $customer_country = get_post_meta($inquiry_id, '_customer_country', true);
            $subject = get_post_meta($inquiry_id, '_subject', true);
            $message = get_post_meta($inquiry_id, '_message', true);
            $inquiry_type = get_post_meta($inquiry_id, '_inquiry_type', true);
            $related_tour = get_post_meta($inquiry_id, '_related_tour', true);
            $travel_dates = get_post_meta($inquiry_id, '_travel_dates', true);
            $group_size = get_post_meta($inquiry_id, '_group_size', true);
            $budget_range = get_post_meta($inquiry_id, '_budget_range', true);
            $admin_notes = get_post_meta($inquiry_id, '_admin_notes', true);
            $current_status = wp_get_post_terms($inquiry_id, 'inquiry_status');
            $status_name = !empty($current_status) ? $current_status[0]->name : 'New';
        ?>
        
        <a href="<?php echo admin_url('admin.php?page=booking-inquiries'); ?>" class="page-title-action">← Back to Inquiries</a>
        
        <div class="inquiry-details" style="margin-top: 20px;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <!-- Main Details -->
                <div class="postbox">
                    <div class="postbox-header">
                        <h2>Inquiry Details - #<?php echo $inquiry_id; ?></h2>
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
                            <?php if ($customer_phone): ?>
                            <tr>
                                <th>Phone:</th>
                                <td><a href="tel:<?php echo esc_attr($customer_phone); ?>"><?php echo esc_html($customer_phone); ?></a></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($customer_country): ?>
                            <tr>
                                <th>Country:</th>
                                <td><?php echo esc_html($customer_country); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Subject:</th>
                                <td><strong><?php echo esc_html($subject); ?></strong></td>
                            </tr>
                            <tr>
                                <th>Inquiry Type:</th>
                                <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $inquiry_type))); ?></td>
                            </tr>
                            <?php if ($related_tour): ?>
                            <tr>
                                <th>Related Tour:</th>
                                <td><?php echo esc_html($related_tour); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($travel_dates): ?>
                            <tr>
                                <th>Travel Dates:</th>
                                <td><?php echo esc_html($travel_dates); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($group_size): ?>
                            <tr>
                                <th>Group Size:</th>
                                <td><?php echo esc_html($group_size); ?> people</td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($budget_range): ?>
                            <tr>
                                <th>Budget Range:</th>
                                <td><?php echo esc_html($budget_range); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Submitted:</th>
                                <td><?php echo get_the_date('F j, Y g:i A', $inquiry); ?></td>
                            </tr>
                            <tr>
                                <th>Current Status:</th>
                                <td><span class="inquiry-status status-<?php echo esc_attr(strtolower(str_replace(' ', '-', $status_name))); ?>"><?php echo esc_html($status_name); ?></span></td>
                            </tr>
                        </table>
                        
                        <h3>Customer Message:</h3>
                        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; border-left: 4px solid #0073aa;">
                            <?php echo nl2br(esc_html($message)); ?>
                        </div>
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
                                <?php wp_nonce_field('inquiry_action', 'inquiry_nonce'); ?>
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="inquiry_id" value="<?php echo $inquiry_id; ?>">
                                
                                <select name="inquiry_status" class="widefat">
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
                    
                    <!-- Enhanced Quick Actions -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h3>Quick Actions</h3>
                        </div>
                        <div class="inside">
                            <div class="admin-actions-grid">
                                <a href="mailto:<?php echo esc_attr($customer_email); ?>" class="action-btn email-btn">
                                    <span class="dashicons dashicons-email"></span>
                                    <span>Email</span>
                                </a>
                                <a href="tel:<?php echo esc_attr($customer_phone); ?>" class="action-btn call-btn">
                                    <span class="dashicons dashicons-phone"></span>
                                    <span>Call</span>
                                </a>
                                <button type="button" class="action-btn confirm-btn" onclick="openConfirmModal()">
                                    <span class="dashicons dashicons-yes-alt"></span>
                                    <span>Confirm</span>
                                </button>
                                <button type="button" class="action-btn quote-btn" onclick="openQuoteModal()">
                                    <span class="dashicons dashicons-money-alt"></span>
                                    <span>Send Quote</span>
                                </button>
                                <button type="button" class="action-btn reject-btn" onclick="openRejectModal()">
                                    <span class="dashicons dashicons-dismiss"></span>
                                    <span>Reject</span>
                                </button>
                                <button type="button" class="action-btn reply-btn" onclick="toggleReplyForm()">
                                    <span class="dashicons dashicons-admin-comments"></span>
                                    <span>Reply</span>
                                </button>
                                <button type="button" class="action-btn print-btn" onclick="printInquiry()">
                                    <span class="dashicons dashicons-printer"></span>
                                    <span>Print</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Inquiry Timeline -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h3>Inquiry Timeline</h3>
                        </div>
                        <div class="inside">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker submitted"></div>
                                    <div class="timeline-content">
                                        <strong>Inquiry Submitted</strong>
                                        <span class="timeline-date"><?php echo get_the_date('M j, Y g:i A', $inquiry_id); ?></span>
                                    </div>
                                </div>
                                <?php
                                $status_terms = wp_get_object_terms($inquiry_id, 'inquiry_status');
                                if (!empty($status_terms)) {
                                    $current_status = $status_terms[0]->name;
                                    if ($current_status !== 'pending') {
                                        echo '<div class="timeline-item">';
                                        echo '<div class="timeline-marker ' . esc_attr($current_status) . '"></div>';
                                        echo '<div class="timeline-content">';
                                        echo '<strong>Status: ' . ucfirst($current_status) . '</strong>';
                                        echo '<span class="timeline-date">' . get_the_modified_date('M j, Y g:i A', $inquiry_id) . '</span>';
                                        echo '</div></div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Reply Form -->
            <div id="reply-form" class="postbox" style="margin-top: 20px; display: none;">
                <div class="postbox-header">
                    <h3>Send Reply to Customer</h3>
                </div>
                <div class="inside">
                    <form method="post">
                        <?php wp_nonce_field('inquiry_action', 'inquiry_nonce'); ?>
                        <input type="hidden" name="action" value="send_reply">
                        <input type="hidden" name="inquiry_id" value="<?php echo $inquiry_id; ?>">
                        
                        <table class="form-table">
                            <tr>
                                <th><label for="reply_subject">Subject:</label></th>
                                <td><input type="text" id="reply_subject" name="reply_subject" value="Re: <?php echo esc_attr($subject); ?>" class="widefat" required></td>
                            </tr>
                            <tr>
                                <th><label for="reply_message">Message:</label></th>
                                <td>
                                    <textarea id="reply_message" name="reply_message" rows="10" class="widefat" required placeholder="Dear <?php echo esc_attr($customer_name); ?>,\n\nThank you for your inquiry...\n\nBest regards,\n<?php echo get_bloginfo('name'); ?>"></textarea>
                                </td>
                            </tr>
                        </table>
                        
                        <p class="submit">
                            <input type="submit" class="button-primary" value="Send Reply">
                            <button type="button" class="button" onclick="document.getElementById('reply-form').style.display='none';">Cancel</button>
                        </p>
                    </form>
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
                        <?php wp_nonce_field('inquiry_action', 'inquiry_nonce'); ?>
                        <input type="hidden" name="action" value="add_note">
                        <input type="hidden" name="inquiry_id" value="<?php echo $inquiry_id; ?>">
                        
                        <textarea name="inquiry_note" rows="4" class="widefat" placeholder="Add a note about this inquiry..."></textarea>
                        
                        <p class="submit">
                            <input type="submit" class="button-primary" value="Add Note">
                        </p>
                    </form>
                </div>
            </div>
        </div>
        
        <?php else: ?>
            <div class="notice notice-error"><p>Inquiry not found.</p></div>
        <?php endif; ?>
        
    <?php else: ?>
        <!-- Inquiries List -->
        <div class="tablenav top">
            <div class="alignleft actions">
                <form method="get">
                    <input type="hidden" name="page" value="booking-inquiries">
                    
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
                    <input type="hidden" name="page" value="booking-inquiries">
                    <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search inquiries...">
                    <input type="submit" class="button" value="Search">
                </form>
            </div>
        </div>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Subject</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($inquiries): ?>
                    <?php foreach ($inquiries as $inquiry): ?>
                        <?php
                        $customer_name = get_post_meta($inquiry->ID, '_customer_name', true);
                        $customer_email = get_post_meta($inquiry->ID, '_customer_email', true);
                        $subject = get_post_meta($inquiry->ID, '_subject', true);
                        $inquiry_type = get_post_meta($inquiry->ID, '_inquiry_type', true);
                        $inquiry_status = wp_get_post_terms($inquiry->ID, 'inquiry_status');
                        $status_name = !empty($inquiry_status) ? $inquiry_status[0]->name : 'New';
                        ?>
                        <tr>
                            <td><strong>#<?php echo $inquiry->ID; ?></strong></td>
                            <td>
                                <strong><?php echo esc_html($customer_name); ?></strong><br>
                                <a href="mailto:<?php echo esc_attr($customer_email); ?>"><?php echo esc_html($customer_email); ?></a>
                            </td>
                            <td>
                                <strong><?php echo esc_html($subject); ?></strong><br>
                                <span style="color: #666; font-size: 12px;"><?php echo esc_html(wp_trim_words(get_post_meta($inquiry->ID, '_message', true), 15)); ?></span>
                            </td>
                            <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $inquiry_type))); ?></td>
                            <td><span class="inquiry-status status-<?php echo esc_attr(strtolower(str_replace(' ', '-', $status_name))); ?>"><?php echo esc_html($status_name); ?></span></td>
                            <td><?php echo get_the_date('M j, Y', $inquiry); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=booking-inquiries&action=view&inquiry_id=' . $inquiry->ID); ?>" class="button button-small">View</a>
                                <a href="mailto:<?php echo esc_attr($customer_email); ?>?subject=Re: <?php echo esc_attr($subject); ?>" class="button button-small">Reply</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No inquiries found.</td>
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
<!-- Confirm Inquiry Modal -->
<div id="confirmModal" class="admin-modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>✅ Confirm Inquiry</h3>
            <span class="close" onclick="closeModal('confirmModal')">&times;</span>
        </div>
        <form method="post" action="">
            <input type="hidden" name="action" value="confirm_inquiry">
            <input type="hidden" name="inquiry_id" value="<?php echo $inquiry_id; ?>">
            <?php wp_nonce_field('inquiry_action', 'inquiry_nonce'); ?>
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="confirmation_message">Confirmation Message (Optional):</label>
                    <textarea name="confirmation_message" id="confirmation_message" rows="4" placeholder="Add a personal message to include in the confirmation email..."></textarea>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="button" onclick="closeModal('confirmModal')">Cancel</button>
                <button type="submit" class="button button-primary">✅ Confirm Inquiry</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Inquiry Modal -->
<div id="rejectModal" class="admin-modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>❌ Reject Inquiry</h3>
            <span class="close" onclick="closeModal('rejectModal')">&times;</span>
        </div>
        <form method="post" action="">
            <input type="hidden" name="action" value="reject_inquiry">
            <input type="hidden" name="inquiry_id" value="<?php echo $inquiry_id; ?>">
            <?php wp_nonce_field('inquiry_action', 'inquiry_nonce'); ?>
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="rejection_reason">Rejection Reason:</label>
                    <select name="rejection_reason" id="rejection_reason" required>
                        <option value="">Select a reason...</option>
                        <option value="dates_unavailable">Dates Not Available</option>
                        <option value="tour_unavailable">Tour Not Available</option>
                        <option value="group_size_issue">Group Size Issue</option>
                        <option value="budget_mismatch">Budget Mismatch</option>
                        <option value="requirements_not_met">Requirements Not Met</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="rejection_message">Message to Customer:</label>
                    <textarea name="rejection_message" id="rejection_message" rows="4" required placeholder="Explain the reason for rejection and suggest alternatives if possible..."></textarea>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="button" onclick="closeModal('rejectModal')">Cancel</button>
                <button type="submit" class="button button-primary">❌ Reject Inquiry</button>
            </div>
        </form>
    </div>
</div>

<!-- Send Quote Modal -->
<div id="quoteModal" class="admin-modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>💰 Send Quote</h3>
            <span class="close" onclick="closeModal('quoteModal')">&times;</span>
        </div>
        <form method="post" action="">
            <input type="hidden" name="action" value="send_quote">
            <input type="hidden" name="inquiry_id" value="<?php echo $inquiry_id; ?>">
            <?php wp_nonce_field('inquiry_action', 'inquiry_nonce'); ?>
            
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="quote_amount">Quote Amount ($):</label>
                        <input type="number" name="quote_amount" id="quote_amount" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="quote_validity">Valid Until:</label>
                        <input type="date" name="quote_validity" id="quote_validity" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="quote_message">Personal Message (Optional):</label>
                    <textarea name="quote_message" id="quote_message" rows="3" placeholder="Add a personal message to include with the quote..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="quote_inclusions">What's Included:</label>
                    <textarea name="quote_inclusions" id="quote_inclusions" rows="4" placeholder="List what's included in this quote...\n• Accommodation\n• Meals\n• Transportation\n• Guide services"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="quote_exclusions">What's Not Included:</label>
                    <textarea name="quote_exclusions" id="quote_exclusions" rows="3" placeholder="List what's not included...\n• International flights\n• Personal expenses\n• Travel insurance"></textarea>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="button" onclick="closeModal('quoteModal')">Cancel</button>
                <button type="submit" class="button button-primary">💰 Send Quote</button>
            </div>
        </form>
    </div>
</div>

<style>
.inquiry-status {
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}
.status-new { background: #fff3cd; color: #856404; }
.status-replied { background: #d4edda; color: #155724; }
.status-resolved { background: #d1ecf1; color: #0c5460; }
.status-closed { background: #e2e3e5; color: #383d41; }

/* Enhanced Admin Styles for Inquiries */
.admin-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 10px;
    margin: 15px 0;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 12px 8px;
    border: 2px solid #ddd;
    border-radius: 8px;
    background: #fff;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    font-size: 12px;
    min-height: 60px;
    justify-content: center;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    text-decoration: none;
    color: #333;
}

.action-btn .dashicons {
    font-size: 20px;
    margin-bottom: 4px;
}

.email-btn:hover { border-color: #0073aa; background: #f0f8ff; }
.call-btn:hover { border-color: #00a32a; background: #f0fff0; }
.confirm-btn:hover { border-color: #00a32a; background: #f0fff0; }
.quote-btn:hover { border-color: #996600; background: #fffbf0; }
.reject-btn:hover { border-color: #d63638; background: #fff0f0; }
.reply-btn:hover { border-color: #8c8f94; background: #f8f9fa; }
.print-btn:hover { border-color: #8c8f94; background: #f8f9fa; }

/* Timeline Styles */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #ddd;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    background: #ddd;
}

.timeline-marker.submitted { background: #0073aa; }
.timeline-marker.confirmed { background: #00a32a; }
.timeline-marker.rejected { background: #d63638; }
.timeline-marker.quoted { background: #996600; }

.timeline-content {
    background: #f9f9f9;
    padding: 10px 15px;
    border-radius: 6px;
    border-left: 3px solid #0073aa;
}

.timeline-date {
    display: block;
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}

/* Modal Styles */
.admin-modal {
    position: fixed;
    z-index: 100000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fff;
    margin: 5% auto;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #333;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #000;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #ddd;
    text-align: right;
}

.modal-footer .button {
    margin-left: 10px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

@media (max-width: 768px) {
    .admin-actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
    }
}
</style>

<script>
function openConfirmModal() {
    document.getElementById('confirmModal').style.display = 'block';
}

function openRejectModal() {
    document.getElementById('rejectModal').style.display = 'block';
}

function openQuoteModal() {
    // Set default validity date to 30 days from now
    const validityDate = new Date();
    validityDate.setDate(validityDate.getDate() + 30);
    document.getElementById('quote_validity').value = validityDate.toISOString().split('T')[0];
    
    document.getElementById('quoteModal').style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function toggleReplyForm() {
    const form = document.getElementById('reply-form');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function printInquiry() {
    window.print();
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modals = ['confirmModal', 'rejectModal', 'quoteModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}
</script>