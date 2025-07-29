/**
 * Admin Scripts for Booking System
 *
 * @package TZnew
 */

(function($) {
    'use strict';

    // Initialize admin functionality when document is ready
    $(document).ready(function() {
        initDashboard();
        initBookingManagement();
        initInquiryManagement();
        initSettings();
        initEmailTemplates();
        initDataExport();
    });

    /**
     * Initialize Dashboard
     */
    function initDashboard() {
        // Auto-refresh dashboard stats every 5 minutes
        if ($('.booking-dashboard').length) {
            setInterval(refreshDashboardStats, 300000);
        }

        // Quick action confirmations
        $('.quick-action[data-confirm]').on('click', function(e) {
            const message = $(this).data('confirm');
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    }

    /**
     * Refresh Dashboard Statistics
     */
    function refreshDashboardStats() {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'refresh_booking_stats',
                nonce: booking_admin.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateDashboardCards(response.data);
                }
            }
        });
    }

    /**
     * Update Dashboard Cards
     */
    function updateDashboardCards(data) {
        $.each(data, function(key, value) {
            const $card = $('[data-stat="' + key + '"]');
            if ($card.length) {
                $card.find('.number').text(value.count);
                if (value.change) {
                    $card.find('.change').text(value.change).removeClass('positive negative').addClass(value.change_type);
                }
            }
        });
    }

    /**
     * Initialize Booking Management
     */
    function initBookingManagement() {
        // Status update handling
        $('.booking-status-update').on('change', function() {
            const $select = $(this);
            const bookingId = $select.data('booking-id');
            const newStatus = $select.val();
            
            updateBookingStatus(bookingId, newStatus, $select);
        });

        // Bulk actions
        $('#bulk-action-selector-top, #bulk-action-selector-bottom').on('change', function() {
            const action = $(this).val();
            toggleBulkActionButton(action);
        });

        // Bulk action execution
        $('#doaction, #doaction2').on('click', function(e) {
            e.preventDefault();
            executeBulkAction();
        });

        // Quick reply functionality
        $('.quick-reply-btn').on('click', function() {
            const bookingId = $(this).data('booking-id');
            showQuickReplyModal(bookingId);
        });

        // Notes functionality
        $('.add-note-btn').on('click', function() {
            const bookingId = $(this).data('booking-id');
            showAddNoteModal(bookingId);
        });

        // Print booking details
        $('.print-booking').on('click', function() {
            const bookingId = $(this).data('booking-id');
            printBookingDetails(bookingId);
        });
    }

    /**
     * Update Booking Status
     */
    function updateBookingStatus(bookingId, newStatus, $element) {
        const $row = $element.closest('tr');
        $row.addClass('loading');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'update_booking_status',
                booking_id: bookingId,
                status: newStatus,
                nonce: booking_admin.nonce
            },
            success: function(response) {
                $row.removeClass('loading');
                if (response.success) {
                    showNotice('Booking status updated successfully!', 'success');
                    // Update status badge
                    const $badge = $row.find('.booking-status');
                    $badge.removeClass().addClass('booking-status status-' + newStatus.toLowerCase()).text(newStatus);
                } else {
                    showNotice(response.data || 'Failed to update booking status.', 'error');
                    // Revert select value
                    $element.val($element.data('original-value'));
                }
            },
            error: function() {
                $row.removeClass('loading');
                showNotice('An error occurred while updating the booking status.', 'error');
                $element.val($element.data('original-value'));
            }
        });
    }

    /**
     * Initialize Inquiry Management
     */
    function initInquiryManagement() {
        // Similar to booking management but for inquiries
        $('.inquiry-status-update').on('change', function() {
            const $select = $(this);
            const inquiryId = $select.data('inquiry-id');
            const newStatus = $select.val();
            
            updateInquiryStatus(inquiryId, newStatus, $select);
        });

        // Quick reply for inquiries
        $('.inquiry-quick-reply').on('click', function() {
            const inquiryId = $(this).data('inquiry-id');
            showInquiryReplyModal(inquiryId);
        });
    }

    /**
     * Update Inquiry Status
     */
    function updateInquiryStatus(inquiryId, newStatus, $element) {
        const $row = $element.closest('tr');
        $row.addClass('loading');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'update_inquiry_status',
                inquiry_id: inquiryId,
                status: newStatus,
                nonce: booking_admin.nonce
            },
            success: function(response) {
                $row.removeClass('loading');
                if (response.success) {
                    showNotice('Inquiry status updated successfully!', 'success');
                    const $badge = $row.find('.inquiry-status');
                    $badge.removeClass().addClass('inquiry-status status-' + newStatus.toLowerCase()).text(newStatus);
                } else {
                    showNotice(response.data || 'Failed to update inquiry status.', 'error');
                    $element.val($element.data('original-value'));
                }
            },
            error: function() {
                $row.removeClass('loading');
                showNotice('An error occurred while updating the inquiry status.', 'error');
                $element.val($element.data('original-value'));
            }
        });
    }

    /**
     * Initialize Settings
     */
    function initSettings() {
        // Settings form validation
        $('#booking-settings-form').on('submit', function(e) {
            if (!validateSettingsForm()) {
                e.preventDefault();
            }
        });

        // Test email functionality
        $('.test-email-btn').on('click', function() {
            const templateType = $(this).data('template');
            sendTestEmail(templateType);
        });

        // Reset settings confirmation
        $('.reset-settings-btn').on('click', function(e) {
            if (!confirm('Are you sure you want to reset all settings to default values? This action cannot be undone.')) {
                e.preventDefault();
            }
        });

        // Currency format preview
        $('#currency, #currency_position').on('change', function() {
            updateCurrencyPreview();
        });
    }

    /**
     * Validate Settings Form
     */
    function validateSettingsForm() {
        let isValid = true;
        const errors = [];

        // Validate email addresses
        const adminEmail = $('#admin_email').val();
        if (adminEmail && !isValidEmail(adminEmail)) {
            errors.push('Please enter a valid admin email address.');
            isValid = false;
        }

        // Validate numeric fields
        const maxGroupSize = parseInt($('#max_group_size').val());
        if (maxGroupSize && (maxGroupSize < 1 || maxGroupSize > 100)) {
            errors.push('Maximum group size must be between 1 and 100.');
            isValid = false;
        }

        const advanceBookingDays = parseInt($('#advance_booking_days').val());
        if (advanceBookingDays && (advanceBookingDays < 0 || advanceBookingDays > 365)) {
            errors.push('Advance booking days must be between 0 and 365.');
            isValid = false;
        }

        const depositPercentage = parseFloat($('#deposit_percentage').val());
        if (depositPercentage && (depositPercentage < 0 || depositPercentage > 100)) {
            errors.push('Deposit percentage must be between 0 and 100.');
            isValid = false;
        }

        if (!isValid) {
            showNotice(errors.join('<br>'), 'error');
        }

        return isValid;
    }

    /**
     * Initialize Email Templates
     */
    function initEmailTemplates() {
        // Template preview
        $('.preview-template-btn').on('click', function() {
            const templateType = $(this).data('template');
            previewEmailTemplate(templateType);
        });

        // Template variables insertion
        $('.insert-variable').on('click', function() {
            const variable = $(this).data('variable');
            const targetEditor = $(this).data('target');
            insertTemplateVariable(variable, targetEditor);
        });

        // Template reset
        $('.reset-template-btn').on('click', function() {
            const templateType = $(this).data('template');
            if (confirm('Are you sure you want to reset this template to default? This will overwrite any custom changes.')) {
                resetEmailTemplate(templateType);
            }
        });
    }

    /**
     * Initialize Data Export
     */
    function initDataExport() {
        // Export data
        $('.export-data-btn').on('click', function() {
            const dataType = $(this).data('type');
            const format = $(this).data('format') || 'csv';
            exportData(dataType, format);
        });

        // Data cleanup
        $('.cleanup-data-btn').on('click', function() {
            const dataType = $(this).data('type');
            if (confirm('Are you sure you want to clean up old ' + dataType + '? This action cannot be undone.')) {
                cleanupData(dataType);
            }
        });
    }

    /**
     * Show Quick Reply Modal
     */
    function showQuickReplyModal(bookingId) {
        // Implementation for quick reply modal
        const modal = createModal('Quick Reply', getQuickReplyForm(bookingId));
        modal.show();
    }

    /**
     * Show Add Note Modal
     */
    function showAddNoteModal(bookingId) {
        const modal = createModal('Add Note', getAddNoteForm(bookingId));
        modal.show();
    }

    /**
     * Create Modal
     */
    function createModal(title, content) {
        const modalHtml = `
            <div class="booking-modal-overlay">
                <div class="booking-modal">
                    <div class="booking-modal-header">
                        <h3>${title}</h3>
                        <button class="booking-modal-close">&times;</button>
                    </div>
                    <div class="booking-modal-content">
                        ${content}
                    </div>
                </div>
            </div>
        `;

        const $modal = $(modalHtml);
        $('body').append($modal);

        // Close modal functionality
        $modal.find('.booking-modal-close, .booking-modal-overlay').on('click', function(e) {
            if (e.target === this) {
                $modal.remove();
            }
        });

        return {
            show: function() {
                $modal.fadeIn();
            },
            hide: function() {
                $modal.fadeOut(function() {
                    $modal.remove();
                });
            }
        };
    }

    /**
     * Show Notice
     */
    function showNotice(message, type = 'info') {
        const $notice = $(`
            <div class="notice notice-${type} is-dismissible">
                <p>${message}</p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">Dismiss this notice.</span>
                </button>
            </div>
        `);

        $('.wrap h1').after($notice);

        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            $notice.fadeOut(function() {
                $notice.remove();
            });
        }, 5000);

        // Manual dismiss
        $notice.find('.notice-dismiss').on('click', function() {
            $notice.fadeOut(function() {
                $notice.remove();
            });
        });
    }

    /**
     * Utility Functions
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function formatCurrency(amount, currency = 'USD', position = 'before') {
        const symbols = {
            'USD': '$',
            'EUR': '€',
            'GBP': '£',
            'NPR': 'Rs.',
            'INR': '₹'
        };

        const symbol = symbols[currency] || currency;
        const formattedAmount = parseFloat(amount).toLocaleString();

        return position === 'before' ? symbol + formattedAmount : formattedAmount + symbol;
    }

    function updateCurrencyPreview() {
        const currency = $('#currency').val();
        const position = $('#currency_position').val();
        const preview = formatCurrency(1234.56, currency, position);
        $('#currency-preview').text(preview);
    }

    /**
     * Export Data
     */
    function exportData(dataType, format) {
        const $btn = $(`.export-data-btn[data-type="${dataType}"]`);
        $btn.prop('disabled', true).text('Exporting...');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'export_booking_data',
                data_type: dataType,
                format: format,
                nonce: booking_admin.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Trigger download
                    const link = document.createElement('a');
                    link.href = response.data.download_url;
                    link.download = response.data.filename;
                    link.click();
                    showNotice('Data exported successfully!', 'success');
                } else {
                    showNotice(response.data || 'Export failed.', 'error');
                }
            },
            error: function() {
                showNotice('An error occurred during export.', 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).text('Export');
            }
        });
    }

    /**
     * Send Test Email
     */
    function sendTestEmail(templateType) {
        const $btn = $(`.test-email-btn[data-template="${templateType}"]`);
        $btn.prop('disabled', true).text('Sending...');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'send_test_email',
                template_type: templateType,
                nonce: booking_admin.nonce
            },
            success: function(response) {
                if (response.success) {
                    showNotice('Test email sent successfully!', 'success');
                } else {
                    showNotice(response.data || 'Failed to send test email.', 'error');
                }
            },
            error: function() {
                showNotice('An error occurred while sending test email.', 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).text('Send Test Email');
            }
        });
    }

    /**
     * Print Booking Details
     */
    function printBookingDetails(bookingId) {
        const printWindow = window.open(
            admin_url + 'admin.php?page=booking-bookings&action=print&booking_id=' + bookingId,
            'print_booking',
            'width=800,height=600,scrollbars=yes'
        );
        
        printWindow.onload = function() {
            printWindow.print();
        };
    }

    // Make functions available globally if needed
    window.BookingAdmin = {
        showNotice: showNotice,
        updateBookingStatus: updateBookingStatus,
        updateInquiryStatus: updateInquiryStatus,
        exportData: exportData,
        sendTestEmail: sendTestEmail
    };

})(jQuery);

/**
 * Additional CSS for modals and dynamic elements
 */
const additionalCSS = `
<style>
.booking-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 100000;
    display: none;
}

.booking-modal {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    border-radius: 4px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    max-width: 600px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
}

.booking-modal-header {
    padding: 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.booking-modal-header h3 {
    margin: 0;
}

.booking-modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.booking-modal-content {
    padding: 20px;
}

.loading {
    position: relative;
    opacity: 0.6;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #0073aa;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    z-index: 1000;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
`;

// Inject additional CSS
if (document.head) {
    document.head.insertAdjacentHTML('beforeend', additionalCSS);
}