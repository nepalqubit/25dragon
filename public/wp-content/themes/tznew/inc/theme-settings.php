<?php
/**
 * Theme Settings Helper Functions
 *
 * @package TZnew
 * @author Santosh Baral
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get theme setting value with fallback
 *
 * @param string $field_name The ACF field name
 * @param mixed $default Default value if field is empty
 * @return mixed Field value or default
 */
function tznew_get_theme_setting($field_name, $default = '') {
    if (function_exists('get_field')) {
        $value = tznew_get_field_safe($field_name, '', 'option');
        return !empty($value) ? $value : $default;
    }
    return $default;
}

/**
 * Get company information
 */
function tznew_get_company_name() {
    return tznew_get_theme_setting('company_name', get_bloginfo('name'));
}

function tznew_get_company_tagline() {
    return tznew_get_theme_setting('company_tagline', get_bloginfo('description'));
}

function tznew_get_company_phone() {
    return tznew_get_theme_setting('company_phone', '+977-1-4123456');
}

function tznew_get_company_email() {
    return tznew_get_theme_setting('company_email', 'info@dragonholidays.com');
}

function tznew_get_company_whatsapp() {
    return tznew_get_theme_setting('company_whatsapp', '+977-9841234567');
}

function tznew_get_company_address() {
    return tznew_get_theme_setting('company_address', 'Thamel, Kathmandu, Nepal');
}

function tznew_get_company_description() {
    return tznew_get_theme_setting('company_description', 'We are a leading travel agency specializing in adventure tours and trekking in Nepal.');
}

/**
 * Get social media URLs
 */
function tznew_get_social_media_links() {
    return [
        'facebook' => tznew_get_theme_setting('facebook_url'),
        'instagram' => tznew_get_theme_setting('instagram_url'),
        'twitter' => tznew_get_theme_setting('twitter_url'),
        'youtube' => tznew_get_theme_setting('youtube_url'),
        'linkedin' => tznew_get_theme_setting('linkedin_url'),
        'tripadvisor' => tznew_get_theme_setting('tripadvisor_url'),
    ];
}

function tznew_get_facebook_url() {
    return tznew_get_theme_setting('facebook_url');
}

function tznew_get_instagram_url() {
    return tznew_get_theme_setting('instagram_url');
}

function tznew_get_twitter_url() {
    return tznew_get_theme_setting('twitter_url');
}

function tznew_get_youtube_url() {
    return tznew_get_theme_setting('youtube_url');
}

function tznew_get_linkedin_url() {
    return tznew_get_theme_setting('linkedin_url');
}

function tznew_get_tripadvisor_url() {
    return tznew_get_theme_setting('tripadvisor_url');
}

/**
 * Get booking settings
 */
function tznew_get_booking_email() {
    return tznew_get_theme_setting('booking_email', 'booking@dragonholidays.com');
}

function tznew_get_inquiry_email() {
    return tznew_get_theme_setting('inquiry_email', 'info@dragonholidays.com');
}

function tznew_get_booking_success_message() {
    return tznew_get_theme_setting('booking_success_message', 'Thank you for your booking inquiry! We will contact you within 24 hours to confirm your booking details.');
}

/**
 * Get company website URL
 */
function tznew_get_company_website() {
    return tznew_get_theme_setting('company_website', home_url());
}

/**
 * Get social media URL by platform
 */
function tznew_get_social_media_url($platform) {
    $social_links = tznew_get_social_media_links();
    return isset($social_links[$platform]) ? $social_links[$platform] : '';
}

/**
 * Get terms and conditions URL
 */
function tznew_get_terms_url() {
    return tznew_get_theme_setting('terms_url', home_url('/terms-and-conditions/'));
}

/**
 * Get privacy policy URL
 */
function tznew_get_privacy_url() {
    return tznew_get_theme_setting('privacy_url', get_privacy_policy_url());
}

/**
 * Get cancellation policy
 */
function tznew_get_cancellation_policy() {
    return tznew_get_theme_setting('cancellation_policy', 'Cancellation must be made 48 hours before the tour date for a full refund.');
}

/**
 * Get footer settings
 */
function tznew_get_footer_copyright() {
    return tznew_get_theme_setting('footer_copyright', '© ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.');
}

function tznew_get_footer_description() {
    return tznew_get_theme_setting('footer_description', 'Experience the adventure of a lifetime with our expertly crafted tours and trekking packages in Nepal.');
}

/**
 * Display social media links
 *
 * @param array $args Configuration arguments
 */
function tznew_display_social_links($args = []) {
    $defaults = [
        'show_labels' => false,
        'target' => '_blank',
        'class' => 'social-links',
        'item_class' => 'social-link',
        'icon_class' => 'fab',
    ];
    
    $args = wp_parse_args($args, $defaults);
    $social_links = tznew_get_social_media_links();
    
    $social_icons = [
        'facebook' => 'fa-facebook-f',
        'instagram' => 'fa-instagram',
        'twitter' => 'fa-twitter',
        'youtube' => 'fa-youtube',
        'linkedin' => 'fa-linkedin-in',
        'tripadvisor' => 'fa-tripadvisor',
    ];
    
    $output = '<div class="' . esc_attr($args['class']) . '">';
    
    foreach ($social_links as $platform => $url) {
        if (!empty($url)) {
            $icon = isset($social_icons[$platform]) ? $social_icons[$platform] : 'fa-link';
            $label = ucfirst($platform);
            
            $output .= sprintf(
                '<a href="%s" target="%s" class="%s" aria-label="%s" rel="noopener noreferrer">',
                esc_url($url),
                esc_attr($args['target']),
                esc_attr($args['item_class'] . ' ' . $platform),
                esc_attr($label)
            );
            
            $output .= '<i class="' . esc_attr($args['icon_class'] . ' ' . $icon) . '" aria-hidden="true"></i>';
            
            if ($args['show_labels']) {
                $output .= '<span class="social-label">' . esc_html($label) . '</span>';
            }
            
            $output .= '</a>';
        }
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Display company contact information
 *
 * @param array $args Configuration arguments
 */
function tznew_display_contact_info($args = []) {
    $defaults = [
        'show_icons' => true,
        'show_labels' => true,
        'class' => 'contact-info',
        'item_class' => 'contact-item',
        'icon_class' => 'fas',
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    $contact_info = [
        'phone' => [
            'value' => tznew_get_company_phone(),
            'icon' => 'fa-phone',
            'label' => 'Phone',
            'link' => 'tel:'
        ],
        'email' => [
            'value' => tznew_get_company_email(),
            'icon' => 'fa-envelope',
            'label' => 'Email',
            'link' => 'mailto:'
        ],
        'whatsapp' => [
            'value' => tznew_get_company_whatsapp(),
            'icon' => 'fa-whatsapp',
            'label' => 'WhatsApp',
            'link' => 'https://wa.me/'
        ],
        'address' => [
            'value' => tznew_get_company_address(),
            'icon' => 'fa-map-marker-alt',
            'label' => 'Address',
            'link' => false
        ],
    ];
    
    $output = '<div class="' . esc_attr($args['class']) . '">';
    
    foreach ($contact_info as $type => $info) {
        if (!empty($info['value'])) {
            $output .= '<div class="' . esc_attr($args['item_class'] . ' ' . $type) . '">';
            
            if ($args['show_icons']) {
                $output .= '<i class="' . esc_attr($args['icon_class'] . ' ' . $info['icon']) . '" aria-hidden="true"></i>';
            }
            
            if ($args['show_labels']) {
                $output .= '<span class="contact-label">' . esc_html($info['label']) . ':</span> ';
            }
            
            if ($info['link']) {
                $href = $info['link'] . ($type === 'whatsapp' ? str_replace(['+', '-', ' '], '', $info['value']) : $info['value']);
                $output .= '<a href="' . esc_attr($href) . '">' . esc_html($info['value']) . '</a>';
            } else {
                $output .= '<span class="contact-value">' . esc_html($info['value']) . '</span>';
            }
            
            $output .= '</div>';
        }
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Check if theme settings are properly configured
 *
 * @return bool True if settings are configured
 */
function tznew_is_theme_configured() {
    if (!function_exists('get_field')) {
        return false;
    }
    
    // Check if at least basic company info is set
    $company_name = tznew_get_company_name();
    $company_email = tznew_get_company_email();
    
    return !empty($company_name) && !empty($company_email);
}

/**
 * Get theme configuration status for admin notices
 *
 * @return array Configuration status
 */
function tznew_get_theme_config_status() {
    $status = [
        'configured' => false,
        'missing' => [],
        'warnings' => []
    ];
    
    if (!function_exists('get_field')) {
        $status['missing'][] = 'ACF Pro plugin is not active';
        return $status;
    }
    
    // Check required fields
    $required_fields = [
        'company_name' => 'Company Name',
        'company_email' => 'Company Email',
        'company_phone' => 'Company Phone',
    ];
    
    foreach ($required_fields as $field => $label) {
        $value = tznew_get_theme_setting($field);
        if (empty($value)) {
            $status['missing'][] = $label;
        }
    }
    
    // Check optional but recommended fields
    $recommended_fields = [
        'facebook_url' => 'Facebook URL',
        'instagram_url' => 'Instagram URL',
        'booking_email' => 'Booking Email',
    ];
    
    foreach ($recommended_fields as $field => $label) {
        $value = tznew_get_theme_setting($field);
        if (empty($value)) {
            $status['warnings'][] = $label . ' is not set';
        }
    }
    
    $status['configured'] = empty($status['missing']);
    
    return $status;
}