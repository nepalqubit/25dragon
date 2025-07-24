<?php
/**
 * Template part for floating contact buttons
 *
 * @package TZnew
 */

// Security check
if (!defined('ABSPATH')) {
    exit;
}

// Get contact information from theme options using helper functions
$phone_number = tznew_get_contact_phone();
$whatsapp_number = tznew_get_whatsapp_number();
$email = tznew_get_contact_email();
?>

<!-- Floating Contact Buttons -->
<div class="floating-contact-buttons fixed bottom-6 right-6 z-50 hidden md:flex flex-col space-y-3">
    <!-- WhatsApp Button -->
    <?php if ($whatsapp_number) : ?>
        <a href="https://wa.me/<?php echo esc_attr(str_replace(['+', '-', ' '], '', $whatsapp_number)); ?>" 
           target="_blank" 
           rel="noopener noreferrer"
           class="group bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110"
           title="<?php esc_attr_e('Chat on WhatsApp', 'tznew'); ?>">
            <i class="fab fa-whatsapp text-2xl group-hover:animate-pulse"></i>
        </a>
    <?php endif; ?>
    
    <!-- Phone Call Button -->
    <?php if ($phone_number) : ?>
        <a href="tel:<?php echo esc_attr($phone_number); ?>" 
           class="group bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110"
           title="<?php esc_attr_e('Call Now', 'tznew'); ?>">
            <i class="fas fa-phone text-2xl group-hover:animate-bounce"></i>
        </a>
    <?php endif; ?>
    
    <!-- Email Button -->
    <?php if ($email) : ?>
        <a href="mailto:<?php echo esc_attr($email); ?>" 
           class="group bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110"
           title="<?php esc_attr_e('Send Email', 'tznew'); ?>">
            <i class="fas fa-envelope text-2xl group-hover:animate-pulse"></i>
        </a>
    <?php endif; ?>
    
    <!-- Contact Form Button -->
    <a href="<?php echo esc_url(site_url('/inquiry')); ?>" 
       class="group bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110"
       title="<?php esc_attr_e('Contact Form', 'tznew'); ?>">
        <i class="fas fa-comment-dots text-2xl group-hover:animate-bounce"></i>
    </a>
</div>

<!-- Mobile Contact Bar (visible only on mobile) -->
<div class="mobile-contact-bar fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-40 hidden">
    <div class="grid grid-cols-4 gap-1 p-2">
        <!-- Call Button -->
        <?php if ($phone_number) : ?>
            <a href="tel:<?php echo esc_attr($phone_number); ?>" 
               class="flex flex-col items-center justify-center py-3 px-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                <i class="fas fa-phone text-xl mb-1"></i>
                <span class="text-xs font-medium"><?php esc_html_e('Call', 'tznew'); ?></span>
            </a>
        <?php endif; ?>
        
        <!-- WhatsApp Button -->
        <?php if ($whatsapp_number) : ?>
            <a href="https://wa.me/<?php echo esc_attr(str_replace(['+', '-', ' '], '', $whatsapp_number)); ?>" 
               target="_blank" 
               rel="noopener noreferrer"
               class="flex flex-col items-center justify-center py-3 px-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                <i class="fab fa-whatsapp text-xl mb-1"></i>
                <span class="text-xs font-medium"><?php esc_html_e('WhatsApp', 'tznew'); ?></span>
            </a>
        <?php endif; ?>
        
        <!-- Email Button -->
        <?php if ($email) : ?>
            <a href="mailto:<?php echo esc_attr($email); ?>" 
               class="flex flex-col items-center justify-center py-3 px-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                <i class="fas fa-envelope text-xl mb-1"></i>
                <span class="text-xs font-medium"><?php esc_html_e('Email', 'tznew'); ?></span>
            </a>
        <?php endif; ?>
        
        <!-- Contact Form Button -->
        <a href="<?php echo esc_url(site_url('/inquiry')); ?>" 
           class="flex flex-col items-center justify-center py-3 px-2 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors">
            <i class="fas fa-comment-dots text-xl mb-1"></i>
            <span class="text-xs font-medium"><?php esc_html_e('Contact', 'tznew'); ?></span>
        </a>
    </div>
</div>

<style>
/* Hide mobile contact bar completely on all screen sizes */
.mobile-contact-bar {
    display: none !important;
}

/* Ensure floating buttons are properly positioned */
@media (min-width: 768px) {
    .floating-contact-buttons {
        display: flex;
    }
}

@media (max-width: 767px) {
    .floating-contact-buttons {
        display: none;
    }
}
</style>