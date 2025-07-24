<?php
/**
 * Booking Confirmation Email Template
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
    <title>Booking Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #27ae60; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .booking-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
        .button { display: inline-block; background: #27ae60; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .highlight { color: #27ae60; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo esc_html($site_name); ?></h1>
            <h2>Booking Confirmation</h2>
        </div>
        
        <div class="content">
            <h3>Dear <?php echo esc_html($customer_name); ?>,</h3>
            
            <p>Thank you for your booking! We're excited to confirm your reservation.</p>
            
            <div class="booking-details">
                <h4>Booking Details:</h4>
                <p><strong>Booking ID:</strong> <span class="highlight">#<?php echo esc_html($booking_id); ?></span></p>
                <p><strong>Trip:</strong> <?php echo esc_html($trip_title); ?></p>
                <p><strong>Status:</strong> <span class="highlight">Confirmed</span></p>
                <p><strong>Confirmation Date:</strong> <?php echo date('F j, Y'); ?></p>
            </div>
            
            <p>We will contact you soon with additional details about your trip, including:</p>
            <ul>
                <li>Detailed itinerary</li>
                <li>Packing recommendations</li>
                <li>Meeting point information</li>
                <li>Payment instructions (if applicable)</li>
            </ul>
            
            <p>If you have any questions or need to make changes to your booking, please don't hesitate to contact us.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="<?php echo esc_url($site_url); ?>" class="button">Visit Our Website</a>
            </div>
            
            <p>Thank you for choosing <?php echo esc_html($site_name); ?>!</p>
            
            <p>Best regards,<br>
            The <?php echo esc_html($site_name); ?> Team</p>
        </div>
        
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html($site_name); ?>. All rights reserved.</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>