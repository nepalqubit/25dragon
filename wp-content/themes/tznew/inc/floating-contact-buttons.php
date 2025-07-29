<?php
/**
 * Floating Contact Buttons
 *
 * @package TZnew
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add floating contact buttons to the site
 */
function tznew_add_floating_contact_buttons() {
    // Get contact information from theme customizer
    $phone = tznew_get_contact_phone();
    $whatsapp = tznew_get_whatsapp_number();
    $email = tznew_get_contact_email();
    
    // Only show buttons if at least one contact method is available
    if (empty($phone) && empty($whatsapp) && empty($email)) {
        return;
    }
    
    // Clean phone numbers for links
    $phone_clean = $phone ? preg_replace('/[^0-9+]/', '', $phone) : '';
    $whatsapp_clean = $whatsapp ? preg_replace('/[^0-9+]/', '', $whatsapp) : '';
    
    // Start output buffer
    ob_start();
    ?>
    <div class="floating-contact-buttons fixed bottom-6 right-6 z-50 flex flex-col space-y-3">
        <?php if (!empty($phone)) : ?>
        <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-lg hover:bg-blue-700 transition-all transform hover:scale-110" aria-label="<?php esc_attr_e('Call us', 'tznew'); ?>">
            <i class="fas fa-phone-alt text-xl"></i>
        </a>
        <?php endif; ?>
        
        <?php if (!empty($whatsapp)) : ?>
        <a href="https://wa.me/<?php echo esc_attr($whatsapp_clean); ?>" target="_blank" rel="noopener noreferrer" class="w-14 h-14 rounded-full bg-green-500 text-white flex items-center justify-center shadow-lg hover:bg-green-600 transition-all transform hover:scale-110" aria-label="<?php esc_attr_e('WhatsApp', 'tznew'); ?>">
            <i class="fab fa-whatsapp text-2xl"></i>
        </a>
        <?php endif; ?>
        
        <?php if (!empty($email)) : ?>
        <a href="mailto:<?php echo esc_attr($email); ?>" class="w-14 h-14 rounded-full bg-red-500 text-white flex items-center justify-center shadow-lg hover:bg-red-600 transition-all transform hover:scale-110" aria-label="<?php esc_attr_e('Email us', 'tznew'); ?>">
            <i class="fas fa-envelope text-xl"></i>
        </a>
        <?php endif; ?>
        
        <button id="contact-toggle" class="w-14 h-14 rounded-full bg-gray-800 text-white flex items-center justify-center shadow-lg hover:bg-gray-900 transition-all transform hover:scale-110" aria-label="<?php esc_attr_e('Toggle contact buttons', 'tznew'); ?>">
            <i class="fas fa-comment-dots text-xl"></i>
        </button>
    </div>
    
    <script>
    (function() {
        document.addEventListener('DOMContentLoaded', function() {
            const contactToggle = document.getElementById('contact-toggle');
            const contactButtons = document.querySelectorAll('.floating-contact-buttons a');
            
            // Initially hide the contact buttons
            contactButtons.forEach(button => {
                button.style.opacity = '0';
                button.style.transform = 'scale(0.5) translateY(20px)';
                button.style.pointerEvents = 'none';
            });
            
            // Toggle contact buttons visibility
            contactToggle.addEventListener('click', function() {
                const isVisible = contactButtons[0].style.opacity === '1';
                
                contactButtons.forEach((button, index) => {
                    button.style.transition = `all 0.3s ease ${index * 0.1}s`;
                    
                    if (isVisible) {
                        button.style.opacity = '0';
                        button.style.transform = 'scale(0.5) translateY(20px)';
                        button.style.pointerEvents = 'none';
                    } else {
                        button.style.opacity = '1';
                        button.style.transform = 'scale(1) translateY(0)';
                        button.style.pointerEvents = 'auto';
                    }
                });
            });
        });
    })();
    </script>
    <?php
    
    // Get buffer contents and clean output
    $output = ob_get_clean();
    echo $output;
}

// Add floating contact buttons to footer
add_action('wp_footer', 'tznew_add_floating_contact_buttons');