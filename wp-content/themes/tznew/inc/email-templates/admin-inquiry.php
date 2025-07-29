<?php
/**
 * Admin Inquiry Notification Email Template
 *
 * @package TZnew
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$site_name = get_bloginfo('name');
$site_url = get_bloginfo('url');
$admin_url = admin_url('admin.php?page=booking-inquiries&inquiry_id=' . $inquiry_id);
$reply_url = admin_url('admin.php?page=booking-inquiries&action=reply&inquiry_id=' . $inquiry_id);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Inquiry - <?php echo esc_html($site_name); ?></title>
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
            background: linear-gradient(135deg, #e67e22, #f39c12);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .urgent-flag {
            background-color: #e74c3c;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            margin: 15px 0;
            text-align: center;
            font-weight: bold;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        .inquiry-details {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 5px solid #e67e22;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #2c3e50;
            min-width: 120px;
        }
        .detail-value {
            color: #34495e;
            flex: 1;
            text-align: right;
        }
        .customer-info {
            background-color: #e8f4fd;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 5px solid #3498db;
        }
        .customer-info h3 {
            margin-top: 0;
            color: #2980b9;
        }
        .message-content {
            background-color: #fff5f5;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 5px solid #e74c3c;
        }
        .message-content h3 {
            margin-top: 0;
            color: #c0392b;
        }
        .message-text {
            background-color: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #fadbd8;
            font-style: italic;
            line-height: 1.8;
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .action-button {
            display: inline-block;
            padding: 12px 24px;
            margin: 5px 10px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .btn-success {
            background-color: #27ae60;
            color: white;
        }
        .btn-success:hover {
            background-color: #229954;
        }
        .btn-warning {
            background-color: #f39c12;
            color: white;
        }
        .btn-warning:hover {
            background-color: #e67e22;
        }
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 25px 0;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 12px;
            opacity: 0.9;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
            color: #7f8c8d;
            font-size: 14px;
        }
        .priority-high {
            border-left-color: #e74c3c !important;
        }
        .priority-medium {
            border-left-color: #f39c12 !important;
        }
        .priority-low {
            border-left-color: #27ae60 !important;
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
                font-weight: bold;
            }
            .action-button {
                display: block;
                margin: 10px 0;
            }
            .quick-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 New Inquiry Received</h1>
            <p>Customer inquiry from <?php echo esc_html($site_name); ?></p>
        </div>
        
        <?php if (isset($is_urgent) && $is_urgent): ?>
        <div class="urgent-flag">
            🚨 URGENT: Customer requesting immediate response!
        </div>
        <?php endif; ?>
        
        <p><strong>Hello Admin,</strong></p>
        
        <p>A new inquiry has been submitted through your website. Please review the details below and respond promptly to provide excellent customer service.</p>
        
        <div class="inquiry-details">
            <h3 style="margin-top: 0; color: #e67e22; display: flex; align-items: center;">
                <span style="margin-right: 10px;">📋</span> Inquiry Details
            </h3>
            <div class="detail-row">
                <span class="detail-label">Inquiry ID:</span>
                <span class="detail-value">#<?php echo esc_html($inquiry_id); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Submitted:</span>
                <span class="detail-value"><?php echo esc_html(date('F j, Y \\a\\t g:i A', strtotime($inquiry_data['date_created']))); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Subject:</span>
                <span class="detail-value"><?php echo esc_html($inquiry_data['subject']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Inquiry Type:</span>
                <span class="detail-value"><?php echo esc_html(ucfirst(str_replace('_', ' ', $inquiry_data['inquiry_type']))); ?></span>
            </div>
            <?php if (!empty($inquiry_data['related_tour'])): ?>
            <div class="detail-row">
                <span class="detail-label">Related Tour:</span>
                <span class="detail-value"><?php echo esc_html($inquiry_data['related_tour']); ?></span>
            </div>
            <?php endif; ?>
            <div class="detail-row">
                <span class="detail-label">Priority:</span>
                <span class="detail-value">
                    <?php 
                    $priority = isset($inquiry_data['priority']) ? $inquiry_data['priority'] : 'medium';
                    $priority_colors = [
                        'high' => '#e74c3c',
                        'medium' => '#f39c12', 
                        'low' => '#27ae60'
                    ];
                    ?>
                    <span style="color: <?php echo $priority_colors[$priority]; ?>; font-weight: bold;">
                        <?php echo esc_html(ucfirst($priority)); ?>
                    </span>
                </span>
            </div>
        </div>
        
        <div class="customer-info">
            <h3>👤 Customer Information</h3>
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span class="detail-value"><?php echo esc_html($inquiry_data['name']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value">
                    <a href="mailto:<?php echo esc_attr($inquiry_data['email']); ?>?subject=Re: <?php echo esc_attr($inquiry_data['subject']); ?>" style="color: #3498db; text-decoration: none;">
                        <?php echo esc_html($inquiry_data['email']); ?>
                    </a>
                </span>
            </div>
            <?php if (!empty($inquiry_data['phone'])): ?>
            <div class="detail-row">
                <span class="detail-label">Phone:</span>
                <span class="detail-value">
                    <a href="tel:<?php echo esc_attr($inquiry_data['phone']); ?>" style="color: #3498db; text-decoration: none;">
                        <?php echo esc_html($inquiry_data['phone']); ?>
                    </a>
                </span>
            </div>
            <?php endif; ?>
            <?php if (!empty($inquiry_data['country'])): ?>
            <div class="detail-row">
                <span class="detail-label">Country:</span>
                <span class="detail-value"><?php echo esc_html($inquiry_data['country']); ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="message-content">
            <h3>💬 Customer Message</h3>
            <div class="message-text">
                <?php echo nl2br(esc_html($inquiry_data['message'])); ?>
            </div>
        </div>
        
        <?php if (!empty($inquiry_data['travel_dates']) || !empty($inquiry_data['group_size']) || !empty($inquiry_data['budget_range'])): ?>
        <div style="background-color: #f0f8ff; padding: 20px; border-radius: 8px; margin: 25px 0; border-left: 5px solid #3498db;">
            <h3 style="margin-top: 0; color: #2980b9;">🎯 Travel Preferences</h3>
            <?php if (!empty($inquiry_data['travel_dates'])): ?>
            <div class="detail-row">
                <span class="detail-label">Travel Dates:</span>
                <span class="detail-value"><?php echo esc_html($inquiry_data['travel_dates']); ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($inquiry_data['group_size'])): ?>
            <div class="detail-row">
                <span class="detail-label">Group Size:</span>
                <span class="detail-value"><?php echo esc_html($inquiry_data['group_size']); ?> people</span>
            </div>
            <?php endif; ?>
            <?php if (!empty($inquiry_data['budget_range'])): ?>
            <div class="detail-row">
                <span class="detail-label">Budget Range:</span>
                <span class="detail-value"><?php echo esc_html($inquiry_data['budget_range']); ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <div class="action-buttons">
            <h3 style="color: #2c3e50; margin-bottom: 20px;">🚀 Quick Actions</h3>
            <a href="<?php echo esc_url($admin_url); ?>" class="action-button btn-primary">
                📋 View in Admin Panel
            </a>
            <a href="<?php echo esc_url($reply_url); ?>" class="action-button btn-success">
                ✉️ Reply to Customer
            </a>
            <a href="mailto:<?php echo esc_attr($inquiry_data['email']); ?>?subject=Re: <?php echo esc_attr($inquiry_data['subject']); ?>" class="action-button btn-warning">
                📧 Direct Email Reply
            </a>
        </div>
        
        <div style="background-color: #fff3cd; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #ffc107;">
            <h4 style="margin-top: 0; color: #856404;">⏰ Response Time Guidelines</h4>
            <ul style="margin: 0; padding-left: 20px; color: #856404;">
                <li><strong>High Priority:</strong> Respond within 2 hours</li>
                <li><strong>Medium Priority:</strong> Respond within 24 hours</li>
                <li><strong>Low Priority:</strong> Respond within 48 hours</li>
                <li><strong>General Inquiries:</strong> Respond within 72 hours</li>
            </ul>
        </div>
        
        <div class="quick-stats">
            <div class="stat-card">
                <div class="stat-number">⏱️</div>
                <div class="stat-label">Response Expected</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo esc_html(ucfirst($priority)); ?></div>
                <div class="stat-label">Priority Level</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">#<?php echo esc_html($inquiry_id); ?></div>
                <div class="stat-label">Inquiry Reference</div>
            </div>
        </div>
        
        <div style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 20px; border-radius: 8px; margin: 25px 0; text-align: center;">
            <h3 style="margin-top: 0;">💡 Customer Service Tips</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px; text-align: left;">
                <div>
                    <div style="font-weight: bold; margin-bottom: 5px;">🎯 Be Personal</div>
                    <div style="font-size: 14px; opacity: 0.9;">Address the customer by name and reference their specific interests</div>
                </div>
                <div>
                    <div style="font-weight: bold; margin-bottom: 5px;">📞 Offer Multiple Contact Options</div>
                    <div style="font-size: 14px; opacity: 0.9;">Provide phone, email, and chat options for follow-up</div>
                </div>
                <div>
                    <div style="font-weight: bold; margin-bottom: 5px;">📋 Include Detailed Information</div>
                    <div style="font-size: 14px; opacity: 0.9;">Provide comprehensive answers and relevant resources</div>
                </div>
                <div>
                    <div style="font-weight: bold; margin-bottom: 5px;">⚡ Follow Up</div>
                    <div style="font-size: 14px; opacity: 0.9;">Set reminders to check if the customer needs additional assistance</div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Inquiry Management System - <?php echo esc_html($site_name); ?></strong></p>
            <p>This notification was automatically generated when a customer submitted an inquiry.</p>
            <p style="margin-top: 15px;">
                <a href="<?php echo esc_url(admin_url('admin.php?page=booking-inquiries')); ?>" style="color: #3498db;">Manage All Inquiries</a> |
                <a href="<?php echo esc_url(admin_url('admin.php?page=booking-settings')); ?>" style="color: #3498db;">System Settings</a> |
                <a href="<?php echo esc_url($site_url); ?>" style="color: #3498db;">Visit Website</a>
            </p>
        </div>
    </div>
</body>
</html>