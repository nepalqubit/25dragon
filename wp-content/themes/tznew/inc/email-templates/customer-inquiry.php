<?php
/**
 * Customer Inquiry Confirmation Email Template
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
    <title>Inquiry Received - <?php echo esc_html($site_name); ?></title>
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
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
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
        .inquiry-summary {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 5px solid #9b59b6;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .summary-row:last-child {
            border-bottom: none;
        }
        .summary-label {
            font-weight: 600;
            color: #2c3e50;
        }
        .summary-value {
            color: #34495e;
        }
        .message-preview {
            background-color: #f0f8ff;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 5px solid #3498db;
        }
        .message-preview h3 {
            margin-top: 0;
            color: #2980b9;
        }
        .message-text {
            background-color: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #d6eaf8;
            font-style: italic;
            line-height: 1.8;
            max-height: 150px;
            overflow-y: auto;
        }
        .response-timeline {
            background-color: #e8f5e8;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 5px solid #27ae60;
        }
        .response-timeline h3 {
            margin-top: 0;
            color: #27ae60;
        }
        .timeline-item {
            display: flex;
            align-items: flex-start;
            margin: 15px 0;
        }
        .timeline-icon {
            background-color: #27ae60;
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
        .timeline-content {
            flex: 1;
        }
        .timeline-time {
            font-weight: bold;
            color: #27ae60;
            margin-bottom: 5px;
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
        .faq-section {
            background-color: #fef9e7;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 5px solid #f1c40f;
        }
        .faq-section h3 {
            margin-top: 0;
            color: #f39c12;
        }
        .faq-item {
            margin: 15px 0;
            padding: 15px;
            background-color: white;
            border-radius: 6px;
            border: 1px solid #fcf3cf;
        }
        .faq-question {
            font-weight: bold;
            color: #d68910;
            margin-bottom: 8px;
        }
        .faq-answer {
            color: #5d4e37;
            font-size: 14px;
            line-height: 1.6;
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
            <h1>📧 Inquiry Received!</h1>
            <p>Thank you for contacting <?php echo esc_html($site_name); ?></p>
        </div>
        
        <p><strong>Dear <?php echo esc_html($inquiry_data['name']); ?>,</strong></p>
        
        <p>Thank you for your inquiry! We've received your message and our travel experts are reviewing it carefully. We're excited to help you plan your perfect adventure.</p>
        
        <div class="inquiry-summary">
            <h3 style="margin-top: 0; color: #9b59b6; display: flex; align-items: center;">
                <span style="margin-right: 10px;">📋</span> Your Inquiry Summary
            </h3>
            <div class="summary-row">
                <span class="summary-label">Inquiry Reference:</span>
                <span class="summary-value">#<?php echo esc_html($inquiry_id); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Subject:</span>
                <span class="summary-value"><?php echo esc_html($inquiry_data['subject']); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Inquiry Type:</span>
                <span class="summary-value"><?php echo esc_html(ucfirst(str_replace('_', ' ', $inquiry_data['inquiry_type']))); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Submitted:</span>
                <span class="summary-value"><?php echo esc_html(date('F j, Y \\a\\t g:i A')); ?></span>
            </div>
            <?php if (!empty($inquiry_data['related_tour'])): ?>
            <div class="summary-row">
                <span class="summary-label">Related Tour:</span>
                <span class="summary-value"><?php echo esc_html($inquiry_data['related_tour']); ?></span>
            </div>
            <?php endif; ?>
            <div class="summary-row">
                <span class="summary-label">Status:</span>
                <span class="summary-value" style="color: #f39c12; font-weight: bold;">Under Review</span>
            </div>
        </div>
        
        <div class="message-preview">
            <h3>💬 Your Message</h3>
            <div class="message-text">
                <?php echo nl2br(esc_html($inquiry_data['message'])); ?>
            </div>
        </div>
        
        <?php if (!empty($inquiry_data['travel_dates']) || !empty($inquiry_data['group_size']) || !empty($inquiry_data['budget_range'])): ?>
        <div style="background-color: #f0f8ff; padding: 20px; border-radius: 8px; margin: 25px 0; border-left: 5px solid #3498db;">
            <h3 style="margin-top: 0; color: #2980b9;">🎯 Your Travel Preferences</h3>
            <?php if (!empty($inquiry_data['travel_dates'])): ?>
            <div class="summary-row">
                <span class="summary-label">Preferred Dates:</span>
                <span class="summary-value"><?php echo esc_html($inquiry_data['travel_dates']); ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($inquiry_data['group_size'])): ?>
            <div class="summary-row">
                <span class="summary-label">Group Size:</span>
                <span class="summary-value"><?php echo esc_html($inquiry_data['group_size']); ?> people</span>
            </div>
            <?php endif; ?>
            <?php if (!empty($inquiry_data['budget_range'])): ?>
            <div class="summary-row">
                <span class="summary-label">Budget Range:</span>
                <span class="summary-value"><?php echo esc_html($inquiry_data['budget_range']); ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <div class="response-timeline">
            <h3>⏰ What Happens Next?</h3>
            <div class="timeline-item">
                <div class="timeline-icon">✓</div>
                <div class="timeline-content">
                    <div class="timeline-time">Completed - Just Now</div>
                    <div>Your inquiry has been received and logged in our system</div>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-icon">2</div>
                <div class="timeline-content">
                    <div class="timeline-time">Within 2-4 Hours</div>
                    <div>Our travel specialist will review your inquiry and research the best options</div>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-icon">3</div>
                <div class="timeline-content">
                    <div class="timeline-time">Within 24 Hours</div>
                    <div>You'll receive a personalized response with detailed information and recommendations</div>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-icon">4</div>
                <div class="timeline-content">
                    <div class="timeline-time">Follow-up</div>
                    <div>We'll schedule a call or video chat to discuss your trip in detail and answer any questions</div>
                </div>
            </div>
        </div>
        
        <div style="background-color: #fff3cd; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #ffc107;">
            <h4 style="margin-top: 0; color: #856404;">📌 Important Information</h4>
            <ul style="margin: 0; padding-left: 20px; color: #856404;">
                <li>Please keep this inquiry reference number for your records: <strong>#<?php echo esc_html($inquiry_id); ?></strong></li>
                <li>Our response time is typically within 24 hours during business days</li>
                <li>For urgent inquiries, please call us directly at the number below</li>
                <li>Check your spam folder if you don't receive our response within 24 hours</li>
            </ul>
        </div>
        
        <div class="faq-section">
            <h3>❓ Frequently Asked Questions</h3>
            <div class="faq-item">
                <div class="faq-question">How quickly will I receive a response?</div>
                <div class="faq-answer">We typically respond within 24 hours during business days. For urgent inquiries, we aim to respond within 2-4 hours.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">What information will be included in your response?</div>
                <div class="faq-answer">Our response will include detailed itinerary options, pricing information, accommodation suggestions, and answers to all your specific questions.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">Can I modify my inquiry or add more information?</div>
                <div class="faq-answer">Absolutely! Simply reply to this email or contact us directly with any additional information or changes to your requirements.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">Is there any cost for the consultation?</div>
                <div class="faq-answer">No, our initial consultation and trip planning advice is completely free. You only pay when you decide to book your adventure with us.</div>
            </div>
        </div>
        
        <div class="contact-info">
            <h3>📞 Need Immediate Assistance?</h3>
            <p>Our travel experts are here to help you plan the perfect adventure!</p>
            <a href="mailto:<?php echo esc_attr($contact_email); ?>?subject=Inquiry Follow-up - #<?php echo esc_attr($inquiry_id); ?>" class="contact-button">📧 Email Us</a>
            <a href="tel:+1234567890" class="contact-button">📱 Call Us</a>
            <p style="margin-top: 15px; font-size: 14px; color: #7f8c8d;">
                Email: <a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a><br>
                Phone: +1 (234) 567-8900<br>
                Business Hours: Monday - Friday, 9:00 AM - 6:00 PM
            </p>
        </div>
        
        <div style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 20px; border-radius: 8px; margin: 25px 0; text-align: center;">
            <h3 style="margin-top: 0;">🌟 Why Choose <?php echo esc_html($site_name); ?>?</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-top: 15px;">
                <div>
                    <div style="font-size: 24px; margin-bottom: 5px;">🏆</div>
                    <div style="font-size: 14px;">Expert Local Guides</div>
                </div>
                <div>
                    <div style="font-size: 24px; margin-bottom: 5px;">🛡️</div>
                    <div style="font-size: 14px;">Safety First Approach</div>
                </div>
                <div>
                    <div style="font-size: 24px; margin-bottom: 5px;">💯</div>
                    <div style="font-size: 14px;">100% Satisfaction</div>
                </div>
                <div>
                    <div style="font-size: 24px; margin-bottom: 5px;">🌍</div>
                    <div style="font-size: 14px;">Authentic Experiences</div>
                </div>
                <div>
                    <div style="font-size: 24px; margin-bottom: 5px;">💰</div>
                    <div style="font-size: 14px;">Best Value Pricing</div>
                </div>
                <div>
                    <div style="font-size: 24px; margin-bottom: 5px;">🤝</div>
                    <div style="font-size: 14px;">Personalized Service</div>
                </div>
            </div>
        </div>
        
        <div style="background-color: #e8f5e8; padding: 20px; border-radius: 8px; margin: 25px 0; text-align: center;">
            <h3 style="margin-top: 0; color: #27ae60;">🎁 Special Offer for New Customers</h3>
            <p style="margin: 10px 0; color: #2c3e50;">Book your first adventure with us and receive:</p>
            <ul style="list-style: none; padding: 0; margin: 15px 0; color: #27ae60; font-weight: bold;">
                <li>✅ 10% discount on your first booking</li>
                <li>✅ Free travel consultation and itinerary planning</li>
                <li>✅ Complimentary travel insurance upgrade</li>
                <li>✅ 24/7 support during your trip</li>
            </ul>
            <p style="font-size: 12px; color: #7f8c8d; margin-top: 15px;">*Offer valid for bookings made within 30 days of inquiry. Terms and conditions apply.</p>
        </div>
        
        <div class="footer">
            <p><strong>Thank you for choosing <?php echo esc_html($site_name); ?>!</strong></p>
            <p>We're committed to making your travel dreams come true.</p>
            
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
                If you have any questions about this inquiry, please reply to this email or contact us directly.<br>
                To unsubscribe from future communications, <a href="#" style="color: #3498db;">click here</a>.
            </p>
        </div>
    </div>
</body>
</html>