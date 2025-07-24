<?php
/**
 * Admin Booking Email Template
 *
 * @package TZnew
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$site_name = get_bloginfo('name');
$site_url = get_bloginfo('url');
$admin_url = admin_url('post.php?post=' . $booking_id . '&action=edit');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking Request - <?php echo esc_html($site_name); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #3498db;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .booking-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #2c3e50;
            flex: 1;
        }
        .detail-value {
            flex: 2;
            text-align: right;
        }
        .customer-info {
            background-color: #e8f5e8;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #27ae60;
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
        }
        .button-primary {
            background-color: #3498db;
            color: white;
        }
        .button-secondary {
            background-color: #95a5a6;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #7f8c8d;
            font-size: 14px;
        }
        .urgent {
            background-color: #e74c3c;
            color: white;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
            .detail-row {
                flex-direction: column;
            }
            .detail-value {
                text-align: left;
                margin-top: 5px;
            }
            .button {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 New Booking Request</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Booking ID: #<?php echo esc_html($booking_id); ?></p>
        </div>
        
        <?php if (isset($booking_data['preferred_date']) && strtotime($booking_data['preferred_date']) <= strtotime('+7 days')): ?>
        <div class="urgent">
            ⚠️ URGENT: This booking is for a date within the next 7 days!
        </div>
        <?php endif; ?>
        
        <p><strong>Hello Admin,</strong></p>
        <p>You have received a new booking request that requires your attention. Please review the details below and take appropriate action.</p>
        
        <div class="booking-details">
            <h3 style="margin-top: 0; color: #2c3e50;">📋 Booking Details</h3>
            <div class="detail-row">
                <span class="detail-label">Trip/Tour:</span>
                <span class="detail-value"><strong><?php echo esc_html($booking_data['post_title']); ?></strong></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Trip Type:</span>
                <span class="detail-value"><?php echo esc_html(ucfirst($booking_data['post_type'])); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Preferred Date:</span>
                <span class="detail-value"><?php echo esc_html(date('F j, Y', strtotime($booking_data['preferred_date']))); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Group Size:</span>
                <span class="detail-value"><?php echo esc_html($booking_data['group_size']); ?> <?php echo _n('person', 'people', $booking_data['group_size'], 'tznew'); ?></span>
            </div>
            <?php if (!empty($booking_data['accommodation_preference'])): ?>
            <div class="detail-row">
                <span class="detail-label">Accommodation:</span>
                <span class="detail-value"><?php echo esc_html(ucfirst($booking_data['accommodation_preference'])); ?></span>
            </div>
            <?php endif; ?>
            <div class="detail-row">
                <span class="detail-label">Submission Date:</span>
                <span class="detail-value"><?php echo esc_html(date('F j, Y g:i A')); ?></span>
            </div>
        </div>
        
        <div class="customer-info">
            <h3 style="margin-top: 0; color: #27ae60;">👤 Customer Information</h3>
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span class="detail-value"><?php echo esc_html($booking_data['first_name'] . ' ' . $booking_data['last_name']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value"><a href="mailto:<?php echo esc_attr($booking_data['email']); ?>"><?php echo esc_html($booking_data['email']); ?></a></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone:</span>
                <span class="detail-value"><a href="tel:<?php echo esc_attr($booking_data['phone']); ?>"><?php echo esc_html($booking_data['phone']); ?></a></span>
            </div>
            <?php if (!empty($booking_data['country'])): ?>
            <div class="detail-row">
                <span class="detail-label">Country:</span>
                <span class="detail-value"><?php echo esc_html($booking_data['country']); ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($booking_data['dietary_requirements']) || !empty($booking_data['special_requests'])): ?>
        <div style="background-color: #fff3cd; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #ffc107;">
            <h3 style="margin-top: 0; color: #856404;">📝 Additional Information</h3>
            <?php if (!empty($booking_data['dietary_requirements'])): ?>
            <p><strong>Dietary Requirements:</strong><br><?php echo esc_html($booking_data['dietary_requirements']); ?></p>
            <?php endif; ?>
            <?php if (!empty($booking_data['special_requests'])): ?>
            <p><strong>Special Requests:</strong><br><?php echo esc_html($booking_data['special_requests']); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <div class="action-buttons">
            <a href="<?php echo esc_url($admin_url); ?>" class="button button-primary">📝 Review Booking</a>
            <a href="mailto:<?php echo esc_attr($booking_data['email']); ?>?subject=Re: Your booking request for <?php echo esc_attr($booking_data['post_title']); ?>&body=Dear <?php echo esc_attr($booking_data['first_name']); ?>," class="button button-secondary">📧 Reply to Customer</a>
        </div>
        
        <div style="background-color: #d1ecf1; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #17a2b8;">
            <h4 style="margin-top: 0; color: #0c5460;">💡 Next Steps:</h4>
            <ol style="margin: 0; padding-left: 20px; color: #0c5460;">
                <li>Review the booking details and customer requirements</li>
                <li>Check availability for the requested date</li>
                <li>Calculate pricing and prepare a quote</li>
                <li>Contact the customer within 24 hours</li>
                <li>Update the booking status in the admin panel</li>
            </ol>
        </div>
        
        <div class="footer">
            <p>This email was sent from <strong><?php echo esc_html($site_name); ?></strong><br>
            <a href="<?php echo esc_url($site_url); ?>"><?php echo esc_html($site_url); ?></a></p>
            <p>Manage all bookings: <a href="<?php echo esc_url(admin_url('admin.php?page=booking-management')); ?>">Booking Dashboard</a></p>
        </div>
    </div>
</body>
</html>