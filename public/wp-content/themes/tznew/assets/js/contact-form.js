/**
 * Contact Form Handler
 * 
 * @package TZnew
 * @version 1.0.0
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Handle contact form submission
    $('#tznew-contact-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $submitBtn = $form.find('button[type="submit"]');
        var originalText = $submitBtn.text();
        
        // Show loading state
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Sending...');
        
        // Clear previous messages
        $('.contact-message').remove();
        
        // Prepare form data
        var formData = {
            action: 'tznew_contact_form',
            contact_name: $form.find('input[name="contact_name"]').val(),
            contact_email: $form.find('input[name="contact_email"]').val(),
            trek_type: $form.find('select[name="trek_type"]').val(),
            contact_message: $form.find('textarea[name="contact_message"]').val(),
            tznew_contact_nonce: tznew_ajax.nonce
        };
        
        // Validate required fields
        var isValid = true;
        var requiredFields = ['contact_name', 'contact_email', 'contact_message'];
        
        requiredFields.forEach(function(field) {
            var $field = $form.find('[name="' + field + '"]');
            if (!$field.val().trim()) {
                $field.addClass('border-red-500');
                isValid = false;
            } else {
                $field.removeClass('border-red-500');
            }
        });
        
        // Validate email format
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var $emailField = $form.find('input[name="contact_email"]');
        if ($emailField.val() && !emailRegex.test($emailField.val())) {
            $emailField.addClass('border-red-500');
            isValid = false;
        }
        
        if (!isValid) {
            showMessage('Please fill in all required fields correctly.', 'error');
            $submitBtn.prop('disabled', false).text(originalText);
            return;
        }
        
        // Submit form via AJAX
        $.ajax({
            url: tznew_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                showMessage('Thank you! Your quote request has been sent successfully. We will get back to you soon.', 'success');
                $form[0].reset();
            },
            error: function(xhr, status, error) {
                showMessage('Sorry, there was an error sending your message. Please try again or contact us directly.', 'error');
            },
            complete: function() {
                $submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });
    
    // Show message function
    function showMessage(message, type) {
        var messageClass = type === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700';
        var iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
        
        var messageHtml = '<div class="contact-message border-l-4 p-4 mb-4 rounded ' + messageClass + '">' +
                         '<div class="flex items-center">' +
                         '<i class="fas ' + iconClass + ' mr-2"></i>' +
                         '<span>' + message + '</span>' +
                         '</div>' +
                         '</div>';
        
        $('#tznew-contact-form').before(messageHtml);
        
        // Auto-hide success messages after 5 seconds
        if (type === 'success') {
            setTimeout(function() {
                $('.contact-message').fadeOut(500, function() {
                    $(this).remove();
                });
            }, 5000);
        }
        
        // Scroll to message
        $('html, body').animate({
            scrollTop: $('.contact-message').offset().top - 100
        }, 500);
    }
    
    // Handle URL parameters for form feedback
    var urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('contact_success')) {
        showMessage('Thank you! Your quote request has been sent successfully.', 'success');
        // Clean URL
        if (history.replaceState) {
            history.replaceState(null, null, window.location.pathname);
        }
    }
    
    if (urlParams.get('contact_error')) {
        var errorType = urlParams.get('contact_error');
        var errorMessage = 'Sorry, there was an error sending your message.';
        
        switch (errorType) {
            case 'missing_fields':
                errorMessage = 'Please fill in all required fields.';
                break;
            case 'send_failed':
                errorMessage = 'Failed to send email. Please try again or contact us directly.';
                break;
        }
        
        showMessage(errorMessage, 'error');
        
        // Clean URL
        if (history.replaceState) {
            history.replaceState(null, null, window.location.pathname);
        }
    }
    
    // Real-time validation
    $('#tznew-contact-form input, #tznew-contact-form textarea').on('blur', function() {
        var $field = $(this);
        var value = $field.val().trim();
        
        if ($field.prop('required') && !value) {
            $field.addClass('border-red-500');
        } else if ($field.attr('type') === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            $field.addClass('border-red-500');
        } else {
            $field.removeClass('border-red-500');
        }
    });
    
    // Remove error styling on focus
    $('#tznew-contact-form input, #tznew-contact-form textarea').on('focus', function() {
        $(this).removeClass('border-red-500');
    });
});