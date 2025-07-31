<?php
/**
 * Homepage Sections Customizer Settings
 *
 * @package TZnew
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Homepage Sections to Customizer
 */
function tznew_homepage_sections_customizer($wp_customize) {
    
    // Homepage Sections Panel
    $wp_customize->add_panel('tznew_homepage_sections', array(
        'title'       => __('Homepage Sections', 'tznew'),
        'description' => __('Customize homepage sections content and settings', 'tznew'),
        'priority'    => 30,
    ));

    // === OFFERS SECTION SETTINGS ===
    
    // Show/Hide Offers Section
    $wp_customize->add_setting('tznew_offers_show', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('tznew_offers_show', array(
        'label'   => __('Show Offers Section', 'tznew'),
        'section' => 'tznew_offers_section',
        'type'    => 'checkbox',
    ));

    // Offers Section Title
    $wp_customize->add_setting('tznew_offers_title', array(
        'default'           => 'Our Best Offer',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('tznew_offers_title', array(
        'label'   => __('Section Title', 'tznew'),
        'section' => 'tznew_offers_section',
        'type'    => 'text',
    ));

    // Offers Section Subtitle
    $wp_customize->add_setting('tznew_offers_subtitle', array(
        'default'           => 'Offers To Inspire You',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('tznew_offers_subtitle', array(
        'label'   => __('Section Subtitle', 'tznew'),
        'section' => 'tznew_offers_section',
        'type'    => 'text',
    ));

    // Offers Section Description
    $wp_customize->add_setting('tznew_offers_description', array(
        'default'           => 'Discover exclusive deals and special offers that will spark your wanderlust. From discounted rates to limited-time promotion.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('tznew_offers_description', array(
        'label'   => __('Section Description', 'tznew'),
        'section' => 'tznew_offers_section',
        'type'    => 'textarea',
    ));

    // Animation Speed
    $wp_customize->add_setting('tznew_offers_animation_speed', array(
        'default'           => 5000,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('tznew_offers_animation_speed', array(
        'label'       => __('Animation Speed (milliseconds)', 'tznew'),
        'description' => __('How long each offer is displayed before fading to the next', 'tznew'),
        'section'     => 'tznew_offers_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 2000,
            'max'  => 10000,
            'step' => 500,
        ),
    ));

    // Add multiple offers (up to 6 offers)
    for ($i = 1; $i <= 6; $i++) {
        // Offer Image
        $default_image = '';
        if ($i == 1) {
            $default_image = get_template_directory_uri() . '/assets/images/placeholder.svg';
        } elseif ($i == 2) {
            $default_image = get_template_directory_uri() . '/assets/images/placeholder.svg';
        }
        
        $wp_customize->add_setting("tznew_offer_{$i}_image", array(
            'default'           => $default_image,
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "tznew_offer_{$i}_image", array(
            'label'   => sprintf(__('Offer %d - Image', 'tznew'), $i),
            'section' => 'tznew_offers_section',
        )));

        // Offer Title
        $default_title = '';
        if ($i == 1) {
            $default_title = 'Best deals Staycation';
        } elseif ($i == 2) {
            $default_title = 'Tours and Trip Packages, Globally';
        }
        
        $wp_customize->add_setting("tznew_offer_{$i}_title", array(
            'default'           => $default_title,
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("tznew_offer_{$i}_title", array(
            'label'   => sprintf(__('Offer %d - Title', 'tznew'), $i),
            'section' => 'tznew_offers_section',
            'type'    => 'text',
        ));

        // Offer Subtitle
        $wp_customize->add_setting("tznew_offer_{$i}_subtitle", array(
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("tznew_offer_{$i}_subtitle", array(
            'label'   => sprintf(__('Offer %d - Subtitle', 'tznew'), $i),
            'section' => 'tznew_offers_section',
            'type'    => 'text',
        ));

        // Offer Discount Text
        $default_discount = '';
        if ($i == 1) {
            $default_discount = 'Discount 30% off';
        } elseif ($i == 2) {
            $default_discount = 'Discount 30% off';
        }
        
        $wp_customize->add_setting("tznew_offer_{$i}_discount", array(
            'default'           => $default_discount,
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("tznew_offer_{$i}_discount", array(
            'label'   => sprintf(__('Offer %d - Discount Text', 'tznew'), $i),
            'section' => 'tznew_offers_section',
            'type'    => 'text',
        ));

        // Offer Button Text
        $wp_customize->add_setting("tznew_offer_{$i}_button_text", array(
            'default'           => 'Book Now',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("tznew_offer_{$i}_button_text", array(
            'label'   => sprintf(__('Offer %d - Button Text', 'tznew'), $i),
            'section' => 'tznew_offers_section',
            'type'    => 'text',
        ));

        // Offer Button Link
        $wp_customize->add_setting("tznew_offer_{$i}_button_link", array(
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control("tznew_offer_{$i}_button_link", array(
            'label'   => sprintf(__('Offer %d - Button Link', 'tznew'), $i),
            'section' => 'tznew_offers_section',
            'type'    => 'url',
        ));
    }
    
    // Statistics Section
    $wp_customize->add_section('tznew_statistics_section', array(
        'title'    => __('Statistics Section', 'tznew'),
        'panel'    => 'tznew_homepage_sections',
        'priority' => 10,
    ));
    
    // Show Statistics Section
    $wp_customize->add_setting('tznew_statistics_show', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('tznew_statistics_show', array(
        'label'   => __('Show Statistics Section', 'tznew'),
        'section' => 'tznew_statistics_section',
        'type'    => 'checkbox',
    ));
    
    // Statistics Data
    $statistics = array(
        'years_experience' => array(
            'label' => __('Years Experience', 'tznew'),
            'default_value' => '15+',
            'default_label' => 'Years Experience'
        ),
        'happy_trekkers' => array(
            'label' => __('Happy Trekkers', 'tznew'),
            'default_value' => '10K+',
            'default_label' => 'Happy Trekkers'
        ),
        'trek_routes' => array(
            'label' => __('Trek Routes', 'tznew'),
            'default_value' => '50+',
            'default_label' => 'Trek Routes'
        ),
        'safety_record' => array(
            'label' => __('Safety Record', 'tznew'),
            'default_value' => '100%',
            'default_label' => 'Safety Record'
        )
    );
    
    foreach ($statistics as $key => $stat) {
        // Value
        $wp_customize->add_setting('tznew_stat_' . $key . '_value', array(
            'default'           => $stat['default_value'],
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('tznew_stat_' . $key . '_value', array(
            'label'   => $stat['label'] . ' - ' . __('Value', 'tznew'),
            'section' => 'tznew_statistics_section',
            'type'    => 'text',
        ));
        
        // Label
        $wp_customize->add_setting('tznew_stat_' . $key . '_label', array(
            'default'           => $stat['default_label'],
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('tznew_stat_' . $key . '_label', array(
            'label'   => $stat['label'] . ' - ' . __('Label', 'tznew'),
            'section' => 'tznew_statistics_section',
            'type'    => 'text',
        ));
    }
    
    // Offers Section
    $wp_customize->add_section('tznew_offers_section', array(
        'title'    => __('Offers Section', 'tznew'),
        'panel'    => 'tznew_homepage_sections',
        'priority' => 20,
    ));

    // Contact Form Section
    $wp_customize->add_section('tznew_contact_form_section', array(
        'title'    => __('Contact Form Section', 'tznew'),
        'panel'    => 'tznew_homepage_sections',
        'priority' => 30,
    ));
    
    // Show Contact Form Section
    $wp_customize->add_setting('tznew_contact_form_show', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('tznew_contact_form_show', array(
        'label'   => __('Show Contact Form Section', 'tznew'),
        'section' => 'tznew_contact_form_section',
        'type'    => 'checkbox',
    ));
    
    // Section Title
    $wp_customize->add_setting('tznew_contact_form_title', array(
        'default'           => 'Plan Your Adventure',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('tznew_contact_form_title', array(
        'label'   => __('Section Title', 'tznew'),
        'section' => 'tznew_contact_form_section',
        'type'    => 'text',
    ));
    
    // Section Subtitle
    $wp_customize->add_setting('tznew_contact_form_subtitle', array(
        'default'           => 'Ready to embark on the journey of a lifetime? Let us help you plan the perfect trekking adventure in Nepal.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('tznew_contact_form_subtitle', array(
        'label'   => __('Section Subtitle', 'tznew'),
        'section' => 'tznew_contact_form_section',
        'type'    => 'textarea',
    ));
    
    // Form Title
    $wp_customize->add_setting('tznew_contact_form_form_title', array(
        'default'           => 'Get a Custom Quote',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('tznew_contact_form_form_title', array(
        'label'   => __('Form Title', 'tznew'),
        'section' => 'tznew_contact_form_section',
        'type'    => 'text',
    ));
    
    // Button Text
    $wp_customize->add_setting('tznew_contact_form_button_text', array(
        'default'           => 'Send Email',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('tznew_contact_form_button_text', array(
        'label'   => __('Button Text', 'tznew'),
        'section' => 'tznew_contact_form_section',
        'type'    => 'text',
    ));
    
    // Email Settings
    $wp_customize->add_setting('tznew_contact_form_email', array(
        'default'           => get_option('admin_email'),
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('tznew_contact_form_email', array(
        'label'       => __('Recipient Email Address', 'tznew'),
        'description' => __('Email address where quote requests will be sent', 'tznew'),
        'section'     => 'tznew_contact_form_section',
        'type'        => 'email',
    ));
    
    // Contact Information
    $wp_customize->add_setting('tznew_contact_phone', array(
        'default'           => '+977-1-4444444',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('tznew_contact_phone', array(
        'label'   => __('Phone Number', 'tznew'),
        'section' => 'tznew_contact_form_section',
        'type'    => 'text',
    ));
    
    $wp_customize->add_setting('tznew_contact_email_display', array(
        'default'           => 'info@dragonholidays.com',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('tznew_contact_email_display', array(
        'label'   => __('Display Email', 'tznew'),
        'section' => 'tznew_contact_form_section',
        'type'    => 'email',
    ));
    
    $wp_customize->add_setting('tznew_contact_whatsapp', array(
        'default'           => '9779841234567',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('tznew_contact_whatsapp', array(
        'label'       => __('WhatsApp Number', 'tznew'),
        'description' => __('WhatsApp number without + or country code formatting', 'tznew'),
        'section'     => 'tznew_contact_form_section',
        'type'        => 'text',
    ));
}
add_action('customize_register', 'tznew_homepage_sections_customizer');

/**
 * Handle Contact Form Submission
 */
function tznew_handle_contact_form_submission() {
    // Verify nonce
    if (!isset($_POST['tznew_contact_nonce']) || !wp_verify_nonce($_POST['tznew_contact_nonce'], 'tznew_contact_form')) {
        wp_die(__('Security check failed', 'tznew'));
    }
    
    // Sanitize form data
    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_email($_POST['contact_email']);
    $trek_type = sanitize_text_field($_POST['trek_type']);
    $message = sanitize_textarea_field($_POST['contact_message']);
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        wp_redirect(add_query_arg('contact_error', 'missing_fields', wp_get_referer()));
        exit;
    }
    
    // Get recipient email from customizer
    $recipient_email = get_theme_mod('tznew_contact_form_email', get_option('admin_email'));
    
    // Prepare email
    $subject = sprintf(__('New Quote Request from %s', 'tznew'), get_bloginfo('name'));
    
    $email_message = sprintf(
        __('New quote request received:\n\nName: %s\nEmail: %s\nTrek Type: %s\nMessage:\n%s\n\nSent from: %s', 'tznew'),
        $name,
        $email,
        $trek_type,
        $message,
        home_url()
    );
    
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
        'Reply-To: ' . $name . ' <' . $email . '>'
    );
    
    // Send email
    $sent = wp_mail($recipient_email, $subject, $email_message, $headers);
    
    if ($sent) {
        wp_redirect(add_query_arg('contact_success', '1', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('contact_error', 'send_failed', wp_get_referer()));
    }
    exit;
}
add_action('wp_ajax_tznew_contact_form', 'tznew_handle_contact_form_submission');
add_action('wp_ajax_nopriv_tznew_contact_form', 'tznew_handle_contact_form_submission');

/**
 * Add contact form scripts
 */
function tznew_contact_form_scripts() {
    if (is_front_page()) {
        wp_enqueue_script('tznew-contact-form', get_template_directory_uri() . '/assets/js/contact-form.js', array('jquery'), '1.0.0', true);
        wp_localize_script('tznew-contact-form', 'tznew_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('tznew_contact_form')
        ));
    }
}
add_action('wp_enqueue_scripts', 'tznew_contact_form_scripts');

/**
 * Enqueue offers animation scripts
 */
function tznew_offers_animation_scripts() {
    if (is_front_page() && get_theme_mod('tznew_offers_show', true)) {
        wp_enqueue_script('tznew-offers-animation', get_template_directory_uri() . '/assets/js/offers-animation.js', array('jquery'), '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'tznew_offers_animation_scripts');