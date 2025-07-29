<?php
/**
 * Admin Settings Template for Booking Management
 *
 * @package TZnew
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add methods for default templates
if (!function_exists('get_default_booking_template')) {
    function get_default_booking_template() {
        return '<h2>Booking Confirmation</h2>
<p>Dear {customer_name},</p>
<p>Thank you for your booking request for <strong>{trip_title}</strong>.</p>
<p><strong>Booking Details:</strong></p>
<ul>
<li>Booking ID: {booking_id}</li>
<li>Trip: {trip_title}</li>
<li>Preferred Date: {preferred_date}</li>
<li>Group Size: {group_size}</li>
</ul>
<p>We will review your booking and get back to you within 24 hours with confirmation and further details.</p>
<p>Best regards,<br>{site_name} Team</p>';
    }
}

if (!function_exists('get_default_inquiry_template')) {
    function get_default_inquiry_template() {
        return '<h2>Inquiry Confirmation</h2>
<p>Dear {customer_name},</p>
<p>Thank you for your inquiry regarding <strong>{inquiry_subject}</strong>.</p>
<p><strong>Inquiry Details:</strong></p>
<ul>
<li>Inquiry ID: {inquiry_id}</li>
<li>Subject: {inquiry_subject}</li>
</ul>
<p>We have received your inquiry and will respond to you as soon as possible.</p>
<p>Best regards,<br>{site_name} Team</p>';
    }
}
?>

<div class="wrap">
    <h1><?php _e('Booking System Settings', 'tznew'); ?></h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('booking_settings_nonce', 'booking_settings_nonce'); ?>
        
        <div class="booking-settings" style="max-width: 800px;">
            <!-- General Settings -->
            <div class="settings-section" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h2 style="margin: 0 0 20px 0; color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;"><?php _e('General Settings', 'tznew'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="admin_email"><?php _e('Admin Email', 'tznew'); ?></label>
                        </th>
                        <td>
                            <input type="email" id="admin_email" name="admin_email" value="<?php echo esc_attr($settings['admin_email'] ?? get_option('admin_email')); ?>" class="regular-text" />
                            <p class="description"><?php _e('Email address to receive booking and inquiry notifications.', 'tznew'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <?php _e('Auto-confirm Bookings', 'tznew'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="auto_confirm_bookings" value="1" <?php checked($settings['auto_confirm_bookings'] ?? false, true); ?> />
                                <?php _e('Automatically confirm bookings upon submission', 'tznew'); ?>
                            </label>
                            <p class="description"><?php _e('When enabled, bookings will be automatically set to "Confirmed" status instead of "Pending Review".', 'tznew'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <?php _e('Email Notifications', 'tznew'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="email_notifications" value="1" <?php checked($settings['email_notifications'] ?? true, true); ?> />
                                <?php _e('Send email notifications for new bookings and inquiries', 'tznew'); ?>
                            </label>
                            <p class="description"><?php _e('Disable this if you want to handle notifications manually.', 'tznew'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Email Templates -->
            <div class="settings-section" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h2 style="margin: 0 0 20px 0; color: #2c3e50; border-bottom: 2px solid #27ae60; padding-bottom: 10px;"><?php _e('Email Templates', 'tznew'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="booking_email_template"><?php _e('Booking Confirmation Email', 'tznew'); ?></label>
                        </th>
                        <td>
                            <?php
                            $booking_template = $settings['booking_email_template'] ?? get_default_booking_template();
                            wp_editor($booking_template, 'booking_email_template', array(
                                'textarea_name' => 'booking_email_template',
                                'textarea_rows' => 10,
                                'media_buttons' => false,
                                'teeny' => true,
                                'quicktags' => true
                            ));
                            ?>
                            <p class="description">
                                <?php _e('Available placeholders:', 'tznew'); ?>
                                <code>{customer_name}</code>, <code>{trip_title}</code>, <code>{booking_id}</code>, <code>{preferred_date}</code>, <code>{group_size}</code>, <code>{site_name}</code>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="inquiry_email_template"><?php _e('Inquiry Confirmation Email', 'tznew'); ?></label>
                        </th>
                        <td>
                            <?php
                            $inquiry_template = $settings['inquiry_email_template'] ?? get_default_inquiry_template();
                            wp_editor($inquiry_template, 'inquiry_email_template', array(
                                'textarea_name' => 'inquiry_email_template',
                                'textarea_rows' => 10,
                                'media_buttons' => false,
                                'teeny' => true,
                                'quicktags' => true
                            ));
                            ?>
                            <p class="description">
                                <?php _e('Available placeholders:', 'tznew'); ?>
                                <code>{customer_name}</code>, <code>{inquiry_subject}</code>, <code>{inquiry_id}</code>, <code>{site_name}</code>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Form Settings -->
            <div class="settings-section" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h2 style="margin: 0 0 20px 0; color: #2c3e50; border-bottom: 2px solid #f39c12; padding-bottom: 10px;"><?php _e('Form Settings', 'tznew'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="max_group_size"><?php _e('Maximum Group Size', 'tznew'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="max_group_size" name="max_group_size" value="<?php echo esc_attr($settings['max_group_size'] ?? 50); ?>" class="small-text" min="1" max="100" />
                            <p class="description"><?php _e('Maximum number of people allowed per booking.', 'tznew'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="booking_advance_days"><?php _e('Minimum Advance Booking', 'tznew'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="booking_advance_days" name="booking_advance_days" value="<?php echo esc_attr($settings['booking_advance_days'] ?? 7); ?>" class="small-text" min="0" max="365" />
                            <span><?php _e('days', 'tznew'); ?></span>
                            <p class="description"><?php _e('Minimum number of days in advance that bookings must be made.', 'tznew'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <?php _e('Required Fields', 'tznew'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><?php _e('Required Fields', 'tznew'); ?></legend>
                                <?php
                                $required_fields = $settings['required_fields'] ?? array('first_name', 'last_name', 'email', 'phone', 'preferred_date', 'group_size');
                                $available_fields = array(
                                    'first_name' => __('First Name', 'tznew'),
                                    'last_name' => __('Last Name', 'tznew'),
                                    'email' => __('Email', 'tznew'),
                                    'phone' => __('Phone', 'tznew'),
                                    'country' => __('Country', 'tznew'),
                                    'preferred_date' => __('Preferred Date', 'tznew'),
                                    'group_size' => __('Group Size', 'tznew'),
                                    'accommodation_preference' => __('Accommodation Preference', 'tznew'),
                                    'dietary_requirements' => __('Dietary Requirements', 'tznew'),
                                    'special_requests' => __('Special Requests', 'tznew')
                                );
                                
                                foreach ($available_fields as $field => $label) {
                                    $checked = in_array($field, $required_fields);
                                    echo '<label style="display: block; margin-bottom: 5px;">';
                                    echo '<input type="checkbox" name="required_fields[]" value="' . esc_attr($field) . '"' . checked($checked, true, false) . ' /> ';
                                    echo esc_html($label);
                                    echo '</label>';
                                }
                                ?>
                                <p class="description"><?php _e('Select which fields are required for booking submissions.', 'tznew'); ?></p>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Payment Settings -->
            <div class="settings-section" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h2 style="margin: 0 0 20px 0; color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 10px;"><?php _e('Payment Settings', 'tznew'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="default_currency"><?php _e('Default Currency', 'tznew'); ?></label>
                        </th>
                        <td>
                            <select id="default_currency" name="default_currency" class="regular-text">
                                <?php
                                $currencies = array(
                                    'USD' => 'US Dollar ($)',
                                    'EUR' => 'Euro (€)',
                                    'GBP' => 'British Pound (£)',
                                    'NPR' => 'Nepalese Rupee (₨)',
                                    'INR' => 'Indian Rupee (₹)',
                                    'AUD' => 'Australian Dollar (A$)',
                                    'CAD' => 'Canadian Dollar (C$)'
                                );
                                $selected_currency = $settings['default_currency'] ?? 'USD';
                                
                                foreach ($currencies as $code => $name) {
                                    echo '<option value="' . esc_attr($code) . '"' . selected($selected_currency, $code, false) . '>' . esc_html($name) . '</option>';
                                }
                                ?>
                            </select>
                            <p class="description"><?php _e('Default currency for pricing and quotes.', 'tznew'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="deposit_percentage"><?php _e('Default Deposit Percentage', 'tznew'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="deposit_percentage" name="deposit_percentage" value="<?php echo esc_attr($settings['deposit_percentage'] ?? 25); ?>" class="small-text" min="0" max="100" step="5" />
                            <span>%</span>
                            <p class="description"><?php _e('Default percentage of total cost required as deposit.', 'tznew'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="payment_instructions"><?php _e('Payment Instructions', 'tznew'); ?></label>
                        </th>
                        <td>
                            <textarea id="payment_instructions" name="payment_instructions" rows="5" class="large-text"><?php echo esc_textarea($settings['payment_instructions'] ?? ''); ?></textarea>
                            <p class="description"><?php _e('Instructions for customers on how to make payments.', 'tznew'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Notification Settings -->
            <div class="settings-section" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h2 style="margin: 0 0 20px 0; color: #2c3e50; border-bottom: 2px solid #9b59b6; padding-bottom: 10px;"><?php _e('Notification Settings', 'tznew'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <?php _e('Admin Notifications', 'tznew'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><?php _e('Admin Notifications', 'tznew'); ?></legend>
                                <?php
                                $admin_notifications = $settings['admin_notifications'] ?? array('new_booking', 'new_inquiry');
                                $notification_types = array(
                                    'new_booking' => __('New Booking Submissions', 'tznew'),
                                    'new_inquiry' => __('New Inquiry Submissions', 'tznew'),
                                    'booking_confirmed' => __('Booking Confirmations', 'tznew'),
                                    'booking_cancelled' => __('Booking Cancellations', 'tznew'),
                                    'payment_received' => __('Payment Notifications', 'tznew')
                                );
                                
                                foreach ($notification_types as $type => $label) {
                                    $checked = in_array($type, $admin_notifications);
                                    echo '<label style="display: block; margin-bottom: 5px;">';
                                    echo '<input type="checkbox" name="admin_notifications[]" value="' . esc_attr($type) . '"' . checked($checked, true, false) . ' /> ';
                                    echo esc_html($label);
                                    echo '</label>';
                                }
                                ?>
                                <p class="description"><?php _e('Select which events should trigger admin email notifications.', 'tznew'); ?></p>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="notification_frequency"><?php _e('Notification Frequency', 'tznew'); ?></label>
                        </th>
                        <td>
                            <select id="notification_frequency" name="notification_frequency" class="regular-text">
                                <?php
                                $frequencies = array(
                                    'immediate' => __('Immediate', 'tznew'),
                                    'hourly' => __('Hourly Digest', 'tznew'),
                                    'daily' => __('Daily Digest', 'tznew'),
                                    'weekly' => __('Weekly Digest', 'tznew')
                                );
                                $selected_frequency = $settings['notification_frequency'] ?? 'immediate';
                                
                                foreach ($frequencies as $value => $label) {
                                    echo '<option value="' . esc_attr($value) . '"' . selected($selected_frequency, $value, false) . '>' . esc_html($label) . '</option>';
                                }
                                ?>
                            </select>
                            <p class="description"><?php _e('How often to send notification emails.', 'tznew'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Data Management -->
            <div class="settings-section" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h2 style="margin: 0 0 20px 0; color: #2c3e50; border-bottom: 2px solid #34495e; padding-bottom: 10px;"><?php _e('Data Management', 'tznew'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="data_retention_days"><?php _e('Data Retention Period', 'tznew'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="data_retention_days" name="data_retention_days" value="<?php echo esc_attr($settings['data_retention_days'] ?? 365); ?>" class="small-text" min="30" max="3650" />
                            <span><?php _e('days', 'tznew'); ?></span>
                            <p class="description"><?php _e('How long to keep booking and inquiry data. Set to 0 for indefinite retention.', 'tznew'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <?php _e('Auto-cleanup', 'tznew'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="auto_cleanup" value="1" <?php checked($settings['auto_cleanup'] ?? false, true); ?> />
                                <?php _e('Automatically delete old data based on retention period', 'tznew'); ?>
                            </label>
                            <p class="description"><?php _e('When enabled, old bookings and inquiries will be automatically deleted.', 'tznew'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <?php _e('Export Data', 'tznew'); ?>
                        </th>
                        <td>
                            <button type="button" class="button" id="export-bookings"><?php _e('Export Bookings (CSV)', 'tznew'); ?></button>
                            <button type="button" class="button" id="export-inquiries"><?php _e('Export Inquiries (CSV)', 'tznew'); ?></button>
                            <p class="description"><?php _e('Export all booking and inquiry data to CSV files.', 'tznew'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <p class="submit">
                <input type="submit" name="save_settings" class="button-primary" value="<?php _e('Save Settings', 'tznew'); ?>" />
                <button type="button" class="button" id="reset-settings"><?php _e('Reset to Defaults', 'tznew'); ?></button>
            </p>
        </div>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Export functionality
    $('#export-bookings').on('click', function() {
        window.location.href = ajaxurl + '?action=export_bookings&nonce=' + '<?php echo wp_create_nonce("export_bookings"); ?>';
    });
    
    $('#export-inquiries').on('click', function() {
        window.location.href = ajaxurl + '?action=export_inquiries&nonce=' + '<?php echo wp_create_nonce("export_inquiries"); ?>';
    });
    
    // Reset settings confirmation
    $('#reset-settings').on('click', function() {
        if (confirm('<?php _e("Are you sure you want to reset all settings to defaults? This action cannot be undone.", "tznew"); ?>')) {
            // Add reset functionality here
            alert('<?php _e("Reset functionality will be implemented in the next update.", "tznew"); ?>');
        }
    });
});
</script>