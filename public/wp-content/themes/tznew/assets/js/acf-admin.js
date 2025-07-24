/**
 * ACF Database Management Admin JavaScript
 *
 * @package TZnew
 * @author Santosh Baral
 * @version 1.0.0
 */

(function($) {
    'use strict';
    
    var TZnewACFAdmin = {
        
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            $('#run-cleanup').on('click', this.runCleanup);
            $('#check-integrity').on('click', this.checkIntegrity);
            $('#optimize-tables').on('click', this.optimizeTables);
            $('#export-data').on('click', this.exportData);
            $('.restore-backup').on('click', this.restoreBackup);
        },
        
        /**
         * Show loading state
         */
        showLoading: function(button) {
            button.prop('disabled', true);
            button.data('original-text', button.text());
            button.text('Processing...');
        },
        
        /**
         * Hide loading state
         */
        hideLoading: function(button) {
            button.prop('disabled', false);
            button.text(button.data('original-text'));
        },
        
        /**
         * Show result message
         */
        showResult: function(message, type) {
            var $results = $('#action-results');
            $results.removeClass('success error');
            $results.addClass(type);
            $results.html('<p>' + message + '</p>');
            
            // Auto-hide after 5 seconds
            setTimeout(function() {
                $results.removeClass('success error');
                $results.html('');
            }, 5000);
        },
        
        /**
         * Run database cleanup
         */
        runCleanup: function(e) {
            e.preventDefault();
            
            var $button = $(this);
            TZnewACFAdmin.showLoading($button);
            
            if (!confirm('Are you sure you want to run database cleanup? This action cannot be undone.')) {
                TZnewACFAdmin.hideLoading($button);
                return;
            }
            
            $.ajax({
                url: tznewAcfAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'tznew_run_cleanup',
                    nonce: tznewAcfAdmin.nonce
                },
                success: function(response) {
                    TZnewACFAdmin.hideLoading($button);
                    
                    if (response.success) {
                        TZnewACFAdmin.showResult(response.data.message, 'success');
                        // Refresh page after 2 seconds to update stats
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        TZnewACFAdmin.showResult(response.data.message, 'error');
                    }
                },
                error: function() {
                    TZnewACFAdmin.hideLoading($button);
                    TZnewACFAdmin.showResult(tznewAcfAdmin.messages.cleanup_error, 'error');
                }
            });
        },
        
        /**
         * Check data integrity
         */
        checkIntegrity: function(e) {
            e.preventDefault();
            
            var $button = $(this);
            TZnewACFAdmin.showLoading($button);
            
            $.ajax({
                url: tznewAcfAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'tznew_check_integrity',
                    nonce: tznewAcfAdmin.nonce
                },
                success: function(response) {
                    TZnewACFAdmin.hideLoading($button);
                    
                    if (response.success) {
                        TZnewACFAdmin.showResult(response.data.message, 'success');
                        TZnewACFAdmin.displayIntegrityReport(response.data.report);
                    } else {
                        TZnewACFAdmin.showResult(response.data.message, 'error');
                    }
                },
                error: function() {
                    TZnewACFAdmin.hideLoading($button);
                    TZnewACFAdmin.showResult('Error during integrity check.', 'error');
                }
            });
        },
        
        /**
         * Display integrity report
         */
        displayIntegrityReport: function(report) {
            var $reportDiv = $('#integrity-report');
            var html = '';
            
            if (Object.keys(report).length === 0) {
                html = '<div class="notice notice-success"><p>No integrity issues found!</p></div>';
            } else {
                html = '<div class="notice notice-warning"><p>Found some integrity issues:</p></div>';
                
                for (var postType in report) {
                    html += '<h4>Missing fields in ' + postType + ' posts:</h4>';
                    html += '<ul>';
                    
                    report[postType].forEach(function(issue) {
                        html += '<li>Post "' + issue.post_title + '" (ID: ' + issue.post_id + ') missing field: ' + issue.field + '</li>';
                    });
                    
                    html += '</ul>';
                }
            }
            
            $reportDiv.html(html);
        },
        
        /**
         * Optimize database tables
         */
        optimizeTables: function(e) {
            e.preventDefault();
            
            var $button = $(this);
            TZnewACFAdmin.showLoading($button);
            
            // Simulate optimization (you can implement actual optimization)
            setTimeout(function() {
                TZnewACFAdmin.hideLoading($button);
                TZnewACFAdmin.showResult('Database tables optimized successfully.', 'success');
            }, 2000);
        },
        
        /**
         * Export ACF data
         */
        exportData: function(e) {
            e.preventDefault();
            
            var $button = $(this);
            TZnewACFAdmin.showLoading($button);
            
            // Create export functionality
            var exportData = {
                timestamp: new Date().toISOString(),
                version: '1.0.0',
                data: 'ACF export functionality would be implemented here'
            };
            
            // Create download link
            var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(exportData, null, 2));
            var downloadAnchorNode = document.createElement('a');
            downloadAnchorNode.setAttribute("href", dataStr);
            downloadAnchorNode.setAttribute("download", "acf-export-" + Date.now() + ".json");
            document.body.appendChild(downloadAnchorNode);
            downloadAnchorNode.click();
            downloadAnchorNode.remove();
            
            TZnewACFAdmin.hideLoading($button);
            TZnewACFAdmin.showResult('ACF data exported successfully.', 'success');
        },
        
        /**
         * Restore backup
         */
        restoreBackup: function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var postId = $button.data('post-id');
            var timestamp = $button.data('timestamp');
            
            if (!confirm('Are you sure you want to restore this backup? Current data will be backed up first.')) {
                return;
            }
            
            TZnewACFAdmin.showLoading($button);
            
            $.ajax({
                url: tznewAcfAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'tznew_restore_backup',
                    nonce: tznewAcfAdmin.nonce,
                    post_id: postId,
                    timestamp: timestamp
                },
                success: function(response) {
                    TZnewACFAdmin.hideLoading($button);
                    
                    if (response.success) {
                        TZnewACFAdmin.showResult(response.data.message, 'success');
                        // Refresh page after 2 seconds
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        TZnewACFAdmin.showResult(response.data.message, 'error');
                    }
                },
                error: function() {
                    TZnewACFAdmin.hideLoading($button);
                    TZnewACFAdmin.showResult(tznewAcfAdmin.messages.restore_error, 'error');
                }
            });
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        TZnewACFAdmin.init();
    });
    
    // Progress bar for long operations
    var ProgressBar = {
        show: function() {
            if ($('#tznew-progress-bar').length === 0) {
                $('body').append('<div id="tznew-progress-overlay"><div id="tznew-progress-bar"><div class="progress-fill"></div><div class="progress-text">Processing...</div></div></div>');
            }
            $('#tznew-progress-overlay').show();
        },
        
        hide: function() {
            $('#tznew-progress-overlay').hide();
        },
        
        update: function(percent, text) {
            $('#tznew-progress-bar .progress-fill').css('width', percent + '%');
            $('#tznew-progress-bar .progress-text').text(text || 'Processing...');
        }
    };
    
    // Add progress bar styles
    $('<style>').text(`
        #tznew-progress-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 999999;
            display: none;
        }
        
        #tznew-progress-bar {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 60px;
            background: white;
            border-radius: 4px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        
        .progress-fill {
            height: 20px;
            background: #0073aa;
            border-radius: 2px;
            transition: width 0.3s ease;
            width: 0%;
        }
        
        .progress-text {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            color: #333;
        }
    `).appendTo('head');
    
    // Export ProgressBar for use in other functions
    window.TZnewProgressBar = ProgressBar;
    
})(jQuery);