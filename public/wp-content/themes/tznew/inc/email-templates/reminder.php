<?php
/**
 * Booking Reminder Email Template
 * 
 * Available variables:
 * $booking_id - The booking ID
 * $booking_data - Array containing booking information
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$site_name = get_bloginfo('name');
$site_url = home_url();
$customer_name = $booking_data['first_name'] . ' ' . $booking_data['last_name'];
$trip_title = $booking_data['post_title'] ?? 'Your Trip';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f39c12; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .reminder-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #f39c12; }
        .checklist { background: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
        .button { display: inline-block; background: #f39c12; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .highlight { color: #f39c12; font-weight: bold; }
        .urgent { color: #e74c3c; font-weight: bold; }
        .checkmark { color: #27ae60; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo esc_html($site_name); ?></h1>
            <h2>🔔 Trip Reminder</h2>
        </div>
        
        <div class="content">
            <h3>Dear <?php echo esc_html($customer_name); ?>,</h3>
            
            <p>This is a friendly reminder about your upcoming trip with us!</p>
            
            <div class="reminder-details">
                <h4>Trip Details:</h4>
                <p><strong>Booking ID:</strong> <span class="highlight">#<?php echo esc_html($booking_id); ?></span></p>
                <p><strong>Trip:</strong> <?php echo esc_html($trip_title); ?></p>
                <p><strong>Reminder Date:</strong> <?php echo date('F j, Y'); ?></p>
            </div>
            
            <div class="checklist">
                <h4>📋 Pre-Trip Checklist</h4>
                <p>Please ensure you have completed the following:</p>
                <ul>
                    <li>☐ Reviewed your itinerary</li>
                    <li>☐ Packed according to our recommendations</li>
                    <li>☐ Confirmed transportation arrangements</li>
                    <li>☐ Checked weather conditions</li>
                    <li>☐ Prepared necessary documents</li>
                    <li>☐ Completed any required payments</li>
                </ul>
            </div>
            
            <p><strong class="urgent">Important Reminders:</strong></p>
            <ul>
                <li>Arrive at the meeting point 15 minutes early</li>
                <li>Bring a valid ID and any required permits</li>
                <li>Wear appropriate clothing and footwear</li>
                <li>Bring sufficient water and snacks</li>
                <li>Inform us of any last-minute changes</li>
            </ul>
            
            <p><strong>Emergency Contact:</strong><br>
            If you need to reach us urgently, please call our emergency line or send us a message.</p>
            
            <p><strong>Weather Update:</strong><br>
            Please check the latest weather forecast and dress accordingly. We'll contact you if there are any weather-related changes to the itinerary.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="<?php echo esc_url($site_url); ?>/contact" class="button">Contact Us</a>
                <a href="<?php echo esc_url($site_url); ?>" class="button">Trip Information</a>
            </div>
            
            <p>We're excited to have you join us and look forward to providing you with an amazing experience!</p>
            
            <p>Safe travels,<br>
            The <?php echo esc_html($site_name); ?> Team</p>
        </div>
        
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html($site_name); ?>. All rights reserved.</p>
            <p>This is an automated reminder. Please contact us if you have any questions.</p>
        </div>
    </div>
</body>
</html>