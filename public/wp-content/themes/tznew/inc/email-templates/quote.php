<?php
/**
 * Booking Quote Email Template
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
    <title>Trip Quote</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3498db; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .quote-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #3498db; }
        .price-section { background: #ecf0f1; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
        .button { display: inline-block; background: #3498db; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .highlight { color: #3498db; font-weight: bold; }
        .price { font-size: 24px; color: #27ae60; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo esc_html($site_name); ?></h1>
            <h2>Your Trip Quote</h2>
        </div>
        
        <div class="content">
            <h3>Dear <?php echo esc_html($customer_name); ?>,</h3>
            
            <p>Thank you for your interest in our services! We're pleased to provide you with a customized quote for your trip.</p>
            
            <div class="quote-details">
                <h4>Quote Details:</h4>
                <p><strong>Quote ID:</strong> <span class="highlight">#<?php echo esc_html($booking_id); ?></span></p>
                <p><strong>Trip:</strong> <?php echo esc_html($trip_title); ?></p>
                <p><strong>Quote Date:</strong> <?php echo date('F j, Y'); ?></p>
                <p><strong>Valid Until:</strong> <?php echo date('F j, Y', strtotime('+30 days')); ?></p>
            </div>
            
            <div class="price-section">
                <h4>Estimated Price</h4>
                <div class="price">Contact us for pricing</div>
                <p><em>Final price may vary based on specific requirements and availability</em></p>
            </div>
            
            <p><strong>What's Included:</strong></p>
            <ul>
                <li>Professional guide services</li>
                <li>All necessary permits and fees</li>
                <li>Safety equipment</li>
                <li>Transportation as specified</li>
                <li>Meals as indicated in itinerary</li>
            </ul>
            
            <p><strong>Next Steps:</strong></p>
            <ol>
                <li>Review the quote details</li>
                <li>Contact us with any questions</li>
                <li>Confirm your booking to secure your dates</li>
                <li>Complete payment as per our terms</li>
            </ol>
            
            <p>This quote is valid for 30 days from the date above. Prices are subject to availability and may change during peak seasons.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="<?php echo esc_url($site_url); ?>/contact" class="button">Contact Us</a>
                <a href="<?php echo esc_url($site_url); ?>" class="button">Visit Website</a>
            </div>
            
            <p>We look forward to making your adventure unforgettable!</p>
            
            <p>Best regards,<br>
            The <?php echo esc_html($site_name); ?> Team</p>
        </div>
        
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html($site_name); ?>. All rights reserved.</p>
            <p>This quote is confidential and intended only for the recipient.</p>
        </div>
    </div>
</body>
</html>