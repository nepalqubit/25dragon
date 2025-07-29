/**
 * Testimonial Admin JavaScript
 *
 * @package TZnew
 */

(function($) {
    'use strict';
    
    // Initialize when document is ready
    $(document).ready(function() {
        initTestimonialAdmin();
    });
    
    function initTestimonialAdmin() {
        // Dashboard functionality
        initDashboard();
        
        // List table functionality
        initListTable();
        
        // Form functionality
        initForms();
        
        // API functionality
        initAPISettings();
    }
    
    // Dashboard Functions
    function initDashboard() {
        // Sync buttons
        $('.sync-reviews').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var platform = button.data('platform');
            
            if (!platform) {
                alert('Invalid platform specified.');
                return;
            }
            
            syncReviews(platform, button);
        });
        
        // Refresh stats
        $('.refresh-stats').on('click', function(e) {
            e.preventDefault();
            refreshDashboardStats();
        });
    }
    
    // List Table Functions
    function initListTable() {
        // Bulk actions
        $('#doaction, #doaction2').on('click', function(e) {
            var action = $(this).siblings('select').val();
            var checkedItems = $('input[name="testimonial[]"]:checked');
            
            if (action === '-1') {
                e.preventDefault();
                alert('Please select an action.');
                return;
            }
            
            if (checkedItems.length === 0) {
                e.preventDefault();
                alert('Please select at least one testimonial.');
                return;
            }
            
            if (action === 'delete') {
                if (!confirm('Are you sure you want to delete the selected testimonials? This action cannot be undone.')) {
                    e.preventDefault();
                    return;
                }
            }
        });
        
        // Quick edit functionality
        $('.quick-edit').on('click', function(e) {
            e.preventDefault();
            
            var testimonialId = $(this).data('id');
            var row = $(this).closest('tr');
            
            showQuickEdit(testimonialId, row);
        });
        
        // Status toggle
        $('.status-toggle').on('click', function(e) {
            e.preventDefault();
            
            var testimonialId = $(this).data('id');
            var currentStatus = $(this).data('status');
            var newStatus = currentStatus === 'publish' ? 'draft' : 'publish';
            
            updateTestimonialStatus(testimonialId, newStatus, $(this));
        });
        
        // Feature toggle
        $('.feature-toggle').on('click', function(e) {
            e.preventDefault();
            
            var testimonialId = $(this).data('id');
            var isFeatured = $(this).data('featured') === '1';
            
            toggleTestimonialFeature(testimonialId, !isFeatured, $(this));
        });
        
        // Delete single testimonial
        $('.delete-testimonial').on('click', function(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to delete this testimonial? This action cannot be undone.')) {
                return;
            }
            
            var testimonialId = $(this).data('id');
            deleteTestimonial(testimonialId, $(this).closest('tr'));
        });
        
        // Filter functionality
        $('.testimonial-filters select, .testimonial-filters input').on('change', function() {
            $(this).closest('form').submit();
        });
    }
    
    // Form Functions
    function initForms() {
        // Star rating
        initStarRating();
        
        // Form validation
        $('.testimonial-form').on('submit', function(e) {
            if (!validateTestimonialForm()) {
                e.preventDefault();
            }
        });
        
        // Auto-save draft
        var autoSaveTimer;
        $('.testimonial-form input, .testimonial-form textarea, .testimonial-form select').on('change', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                autoSaveDraft();
            }, 30000); // Auto-save after 30 seconds of inactivity
        });
    }
    
    // API Settings Functions
    function initAPISettings() {
        // Test API connections
        $('.test-api-connection').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var platform = button.data('platform');
            var apiKey = $('#' + platform + '_api_key').val();
            var placeId = $('#' + platform + '_place_id').val();
            
            if (!apiKey || !placeId) {
                alert('Please enter API key and Place ID first.');
                return;
            }
            
            testAPIConnection(platform, apiKey, placeId, button);
        });
        
        // Manual sync
        $('.sync-now').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var platform = button.data('platform');
            
            syncReviews(platform, button);
        });
    }
    
    // Star Rating Functions
    function initStarRating() {
        $('.star-rating .star').on('click', function() {
            var rating = $(this).data('rating');
            var container = $(this).closest('.star-rating');
            
            // Update radio button
            container.find('input[value="' + rating + '"]').prop('checked', true);
            
            // Update star display
            updateStarDisplay(container, rating);
        });
        
        $('.star-rating .star').on('mouseenter', function() {
            var rating = $(this).data('rating');
            var container = $(this).closest('.star-rating');
            updateStarDisplay(container, rating);
        });
        
        $('.star-rating').on('mouseleave', function() {
            var container = $(this);
            var currentRating = container.find('input:checked').val() || 0;
            updateStarDisplay(container, currentRating);
        });
        
        // Initialize star display
        $('.star-rating').each(function() {
            var container = $(this);
            var currentRating = container.find('input:checked').val() || 0;
            updateStarDisplay(container, currentRating);
        });
    }
    
    function updateStarDisplay(container, rating) {
        container.find('.star').each(function() {
            var starRating = $(this).data('rating');
            if (starRating <= rating) {
                $(this).addClass('filled');
            } else {
                $(this).removeClass('filled');
            }
        });
    }
    
    // AJAX Functions
    function syncReviews(platform, button) {
        var originalText = button.text();
        
        button.prop('disabled', true).text('Syncing...');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'sync_' + platform + '_reviews',
                nonce: testimonialAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    showMessage('success', response.data.message);
                    
                    // Update dashboard stats if on dashboard page
                    if ($('.testimonial-dashboard').length) {
                        refreshDashboardStats();
                    }
                } else {
                    showMessage('error', response.data || 'Sync failed. Please try again.');
                }
            },
            error: function() {
                showMessage('error', 'An error occurred while syncing reviews.');
            },
            complete: function() {
                button.prop('disabled', false).text(originalText);
            }
        });
    }
    
    function testAPIConnection(platform, apiKey, placeId, button) {
        var originalText = button.text();
        
        button.prop('disabled', true).text('Testing...');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'test_api_connection',
                platform: platform,
                api_key: apiKey,
                place_id: placeId,
                nonce: testimonialAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    showMessage('success', platform.charAt(0).toUpperCase() + platform.slice(1) + ' API connection successful!');
                } else {
                    showMessage('error', platform.charAt(0).toUpperCase() + platform.slice(1) + ' API connection failed. Please check your credentials.');
                }
            },
            error: function() {
                showMessage('error', 'An error occurred while testing the connection.');
            },
            complete: function() {
                button.prop('disabled', false).text(originalText);
            }
        });
    }
    
    function updateTestimonialStatus(testimonialId, newStatus, element) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'update_testimonial_status',
                testimonial_id: testimonialId,
                status: newStatus,
                nonce: testimonialAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Update UI
                    var row = element.closest('tr');
                    var statusCell = row.find('.status-badge');
                    
                    statusCell.removeClass('status-published status-draft status-pending')
                             .addClass('status-' + newStatus)
                             .text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                    
                    element.data('status', newStatus)
                           .text(newStatus === 'publish' ? 'Unpublish' : 'Publish');
                    
                    showMessage('success', 'Status updated successfully.');
                } else {
                    showMessage('error', response.data || 'Failed to update status.');
                }
            },
            error: function() {
                showMessage('error', 'An error occurred while updating status.');
            }
        });
    }
    
    function toggleTestimonialFeature(testimonialId, isFeatured, element) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'toggle_testimonial_feature',
                testimonial_id: testimonialId,
                is_featured: isFeatured ? 1 : 0,
                nonce: testimonialAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Update UI
                    var row = element.closest('tr');
                    var featuredCell = row.find('.featured-indicator');
                    
                    if (isFeatured) {
                        featuredCell.html('★');
                        element.text('Unfeature');
                    } else {
                        featuredCell.html('');
                        element.text('Feature');
                    }
                    
                    element.data('featured', isFeatured ? '1' : '0');
                    
                    showMessage('success', isFeatured ? 'Testimonial featured.' : 'Testimonial unfeatured.');
                } else {
                    showMessage('error', response.data || 'Failed to update feature status.');
                }
            },
            error: function() {
                showMessage('error', 'An error occurred while updating feature status.');
            }
        });
    }
    
    function deleteTestimonial(testimonialId, row) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'delete_testimonial',
                testimonial_id: testimonialId,
                nonce: testimonialAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    row.fadeOut(300, function() {
                        $(this).remove();
                    });
                    showMessage('success', 'Testimonial deleted successfully.');
                } else {
                    showMessage('error', response.data || 'Failed to delete testimonial.');
                }
            },
            error: function() {
                showMessage('error', 'An error occurred while deleting testimonial.');
            }
        });
    }
    
    function refreshDashboardStats() {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'refresh_testimonial_stats',
                nonce: testimonialAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Update stats
                    var stats = response.data;
                    
                    $('.stat-card[data-stat="total"] .stat-number').text(stats.total);
                    $('.stat-card[data-stat="pending"] .stat-number').text(stats.pending);
                    $('.stat-card[data-stat="featured"] .stat-number').text(stats.featured);
                    $('.stat-card[data-stat="rating"] .stat-number').text(stats.average_rating);
                    
                    showMessage('success', 'Statistics refreshed.');
                }
            },
            error: function() {
                showMessage('error', 'Failed to refresh statistics.');
            }
        });
    }
    
    function autoSaveDraft() {
        var form = $('.testimonial-form');
        if (!form.length) return;
        
        var formData = form.serialize() + '&action=auto_save_testimonial&nonce=' + testimonialAdmin.nonce;
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showMessage('success', 'Draft saved automatically.', 2000);
                }
            }
        });
    }
    
    // Validation Functions
    function validateTestimonialForm() {
        var isValid = true;
        var errors = [];
        
        // Check required fields
        var guestName = $('#guest_name').val().trim();
        var reviewTitle = $('#review_title').val().trim();
        var rating = $('.star-rating input:checked').val();
        
        if (!guestName) {
            errors.push('Guest name is required.');
            $('#guest_name').focus();
            isValid = false;
        }
        
        if (!reviewTitle) {
            errors.push('Review title is required.');
            if (isValid) $('#review_title').focus();
            isValid = false;
        }
        
        if (!rating) {
            errors.push('Rating is required.');
            if (isValid) $('.star-rating').focus();
            isValid = false;
        }
        
        // Check email format if provided
        var email = $('#guest_email').val().trim();
        if (email && !isValidEmail(email)) {
            errors.push('Please enter a valid email address.');
            if (isValid) $('#guest_email').focus();
            isValid = false;
        }
        
        if (!isValid) {
            showMessage('error', errors.join('<br>'));
        }
        
        return isValid;
    }
    
    function isValidEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Utility Functions
    function showMessage(type, message, duration) {
        duration = duration || 5000;
        
        var messageHtml = '<div class="testimonial-message ' + type + '">' + message + '</div>';
        var messageElement = $(messageHtml);
        
        // Remove existing messages
        $('.testimonial-message').remove();
        
        // Add new message
        if ($('.wrap h1').length) {
            $('.wrap h1').after(messageElement);
        } else {
            $('.wrap').prepend(messageElement);
        }
        
        // Auto-hide message
        setTimeout(function() {
            messageElement.fadeOut(300, function() {
                $(this).remove();
            });
        }, duration);
    }
    
    function showQuickEdit(testimonialId, row) {
        // This would implement inline editing functionality
        // For now, redirect to edit page
        window.location.href = testimonialAdmin.editUrl + '&testimonial_id=' + testimonialId;
    }
    
    // Export functions for external use
    window.testimonialAdmin = {
        syncReviews: syncReviews,
        testAPIConnection: testAPIConnection,
        updateTestimonialStatus: updateTestimonialStatus,
        toggleTestimonialFeature: toggleTestimonialFeature,
        deleteTestimonial: deleteTestimonial,
        showMessage: showMessage
    };
    
})(jQuery);