<?php
/**
 * Customer Booking Confirmation Email Template
 *
 * @package TZnew
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$site_name = get_bloginfo('name');
$site_url = get_bloginfo('url');
$contact_email = get_option('admin_email');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - <?php echo esc_html($site_name); ?></title>
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
            background: linear-gradient(135deg, #27ae60, #2ecc71);
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
        .booking-summary {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 5px solid #27ae60;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 16px;
            color: #27ae60;
        }
        .summary-label {
            font-weight: 600;
            color: #2c3e50;
        }
        .summary-value {
            color: #34495e;
        }
        .next-steps {
            background-color: #e8f4fd;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 5px solid #3498db;
        }
        .next-steps h3 {
            margin-top: 0;
            color: #2980b9;
        }
        .step {
            display: flex;
            align-items: flex-start;
            margin: 15px 0;
        }
        .step-number {
            background-color: #3498db;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .step-content {
            flex: 1;
        }
        .contact-info {
            background-color: #fff5f5;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 5px solid #e74c3c;
            text-align: center;
        }
        .contact-info h3 {
            margin-top: 0;
            color: #c0392b;
        }
        .contact-button {
            display: inline-block;
            background-color: #e74c3c;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 10px 5px;
        }
        .important-note {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
            color: #7f8c8d;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #3498db;
            text-decoration: none;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
            .summary-row {
                flex-direction: column;
            }
            .summary-value {
                margin-top: 5px;
                font-weight: bold;
            }
            .contact-button {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Booking Confirmed!</h1>
            <p>Thank you for choosing <?php echo esc_html($site_name); ?></p>
        </div>
        
        <p><strong>Dear <?php echo esc_html($booking_data['first_name']); ?>,</strong></p>
        
        <p>Thank you for your booking request! We're excited to help you plan your adventure. Your booking has been received and is currently being reviewed by our team.</p>
        
        <div class="booking-summary">
            <h3 style="margin-top: 0; color: #27ae60; display: flex; align-items: center;">
                <span style="margin-right: 10px;">📋</span> Your Booking Summary
            </h3>
            <div class="summary-row">
                <span class="summary-label">Booking Reference:</span>
                <span class="summary-value">#<?php echo esc_html($booking_id); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Trip/Tour:</span>
                <span class="summary-value"><?php echo esc_html($booking_data['post_title']); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Trip Type:</span>
                <span class="summary-value"><?php echo esc_html(ucfirst($booking_data['post_type'])); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Preferred Date:</span>
                <span class="summary-value"><?php echo esc_html(date('F j, Y', strtotime($booking_data['preferred_date']))); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Group Size:</span>
                <span class="summary-value"><?php echo esc_html($booking_data['group_size']); ?> <?php echo _n('person', 'people', $booking_data['group_size'], 'tznew'); ?></span>
            </div>
            <?php if (!empty($booking_data['accommodation_preference'])): ?>
            <div class="summary-row">
                <span class="summary-label">Accommodation:</span>
                <span class="summary-value"><?php echo esc_html(ucfirst($booking_data['accommodation_preference'])); ?></span>
            </div>
            <?php endif; ?>
            <div class="summary-row">
                <span class="summary-label">Booking Status:</span>
                <span class="summary-value">Under Review</span>
            </div>
        </div>
        
        <?php if (!empty($booking_data['dietary_requirements']) || !empty($booking_data['special_requests'])): ?>
        <div style="background-color: #f0f8ff; padding: 20px; border-radius: 8px; margin: 25px 0; border-left: 5px solid #3498db;">
            <h3 style="margin-top: 0; color: #2980b9;">📝 Your Special Requirements</h3>
            <?php if (!empty($booking_data['dietary_requirements'])): ?>
            <p><strong>Dietary Requirements:</strong><br><?php echo esc_html($booking_data['dietary_requirements']); ?></p>
            <?php endif; ?>
            <?php if (!empty($booking_data['special_requests'])): ?>
            <p><strong>Special Requests:</strong><br><?php echo esc_html($booking_data['special_requests']); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <div class="next-steps">
            <h3>🚀 What Happens Next?</h3>
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <strong>Review & Confirmation</strong><br>
                    Our team will review your booking details and check availability for your preferred dates.
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <strong>Personalized Quote</strong><br>
                    We'll prepare a detailed quote including all costs, itinerary, and payment options.
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <strong>Direct Contact</strong><br>
                    Our travel specialist will contact you within 24 hours to discuss your trip and answer any questions.
                </div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <strong>Final Confirmation</strong><br>
                    Once you approve the quote and make the deposit, your booking will be confirmed!
                </div>
            </div>
        </div>
        
        <div class="important-note">
            <h4 style="margin-top: 0; color: #856404;">⚠️ Important Information</h4>
            <ul style="margin: 0; padding-left: 20px; color: #856404;">
                <li>Please keep this booking reference number for your records: <strong>#<?php echo esc_html($booking_id); ?></strong></li>
                <li>Our team will contact you within 24 hours during business days</li>
                <li>If you have any urgent questions, please don't hesitate to contact us</li>
                <li>Booking confirmation is subject to availability and final approval</li>
            </ul>
        </div>
        
        <div class="contact-info">
            <h3>📞 Need Immediate Assistance?</h3>
            <p>Our travel experts are here to help you plan the perfect adventure!</p>
            <a href="mailto:<?php echo esc_attr($contact_email); ?>?subject=Booking Inquiry - #<?php echo esc_attr($booking_id); ?>" class="contact-button">📧 Email Us</a>
            <a href="tel:+1234567890" class="contact-button">📱 Call Us</a>
            <p style="margin-top: 15px; font-size: 14px; color: #7f8c8d;">
                Email: <a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a><br>
                Business Hours: Monday - Friday, 9:00 AM - 6:00 PM
            </p>
        </div>
        
        <div style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 20px; border-radius: 8px; margin: 25px 0; text-align: center;">
            <h3 style="margin-top: 0;">🌟 Why Choose <?php echo esc_html($site_name); ?>?</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-top: 15px;">
                <div>
                    <div style="font-size: 24px; margin-bottom: 5px;">🏆</div>
                    <div style="font-size: 14px;">Expert Guides</div>
                </div>
                <div>
                    <div style="font-size: 24px; margin-bottom: 5px;">🛡️</div>
                    <div style="font-size: 14px;">Safety First</div>
                </div>
                <div>
                    <div style="font-size: 24px; margin-bottom: 5px;">💯</div>
                    <div style="font-size: 14px;">100% Satisfaction</div>
                </div>
                <div>
                    <div style="font-size: 24px; margin-bottom: 5px;">🌍</div>
                    <div style="font-size: 14px;">Local Experience</div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Thank you for choosing <?php echo esc_html($site_name); ?>!</strong></p>
            <p>We're committed to making your adventure unforgettable.</p>
            
            <div class="social-links">
                <a href="#">📘 Facebook</a>
                <a href="#">📷 Instagram</a>
                <a href="#">🐦 Twitter</a>
                <a href="#">📺 YouTube</a>
            </div>
            
            <p style="font-size: 12px; margin-top: 20px;">
                This email was sent from <strong><?php echo esc_html($site_name); ?></strong><br>
                <a href="<?php echo esc_url($site_url); ?>"><?php echo esc_html($site_url); ?></a><br>
                <br>
                If you have any questions about this booking, please reply to this email or contact us directly.
            </p>
        </div>
    </div>
</body>
</html>