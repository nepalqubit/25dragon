/**
 * RevX Chatbot Admin JavaScript
 */

jQuery(document).ready(function($) {
    // Initialize admin interface
    initializeAdminInterface();
    
    function initializeAdminInterface() {
        // Initialize tab switching
        initTabSwitching();
        
        // Initialize search functionality
        initSearchFunctionality();
        
        // Initialize form handlers
        initFormHandlers();
        
        // Initialize UI components
        initUIComponents();
        
        // Load initial data
        loadInitialData();
    }
    
    function initTabSwitching() {
        $('.revx-chatbot-tab-button').on('click', function() {
            const tabId = $(this).data('tab');
            
            // Update active tab button
            $('.revx-chatbot-tab-button').removeClass('active');
            $(this).addClass('active');
            
            // Show selected tab content
            $('.revx-chatbot-tab-content').removeClass('active');
            $(`#${tabId}-tab`).addClass('active');
            
            // Handle tab-specific actions
            handleTabSwitch(tabId);
        });
    }
    
    function handleTabSwitch(tab) {
        switch(tab) {
            case 'responses':
                loadResponses();
                break;
            case 'settings':
                updateChatbotStatus();
                break;
            case 'openrouter':
                updateAIStatus();
                break;
        }
    }
    
    function initSearchFunctionality() {
        // Response search
        $('#revx-response-search').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            filterResponses(searchTerm);
        });
        
        // Category filter
        $('#revx-category-filter').on('change', function() {
            var category = $(this).val();
            filterResponsesByCategory(category);
        });
    }
    
    function filterResponses(searchTerm) {
        $('.revx-response-item').each(function() {
            var pattern = $(this).find('.revx-response-pattern').text().toLowerCase();
            var response = $(this).find('.revx-response-text').text().toLowerCase();
            
            if (pattern.includes(searchTerm) || response.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        updateResponseStats();
    }
    
    function filterResponsesByCategory(category) {
        if (category === 'all') {
            $('.revx-response-item').show();
        } else {
            $('.revx-response-item').each(function() {
                var itemCategory = $(this).data('category') || 'general';
                if (itemCategory === category) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
        
        updateResponseStats();
    }
    
    function updateResponseStats(responses) {
        if (!responses || typeof responses !== 'object') {
            // Reset all stats to 0 if no valid response data
            $('.revx-stat-number').text('0');
            return;
        }
        
        const greetingsCount = responses.greetings ? responses.greetings.length : 0;
        const farewellsCount = responses.farewell ? responses.farewell.length : 0;
        const fallbacksCount = responses.fallback ? responses.fallback.length : 0;
        const customCount = responses.responses ? responses.responses.length : 0;
        
        // Update stats in the response stats section
        const statItems = $('.revx-stat-item');
        if (statItems.length >= 4) {
            statItems.eq(0).find('.revx-stat-number').text(greetingsCount);
            statItems.eq(1).find('.revx-stat-number').text(farewellsCount);
            statItems.eq(2).find('.revx-stat-number').text(fallbacksCount);
            statItems.eq(3).find('.revx-stat-number').text(customCount);
        }
        
        // Also update category counts in headers
        $('.revx-category-count').each(function() {
            const category = $(this).closest('.revx-response-category');
            const categoryTitle = category.find('h3').text().toLowerCase();
            
            if (categoryTitle.includes('greeting')) {
                $(this).text(greetingsCount + ' items');
            } else if (categoryTitle.includes('farewell')) {
                $(this).text(farewellsCount + ' items');
            } else if (categoryTitle.includes('fallback')) {
                $(this).text(fallbacksCount + ' items');
            } else if (categoryTitle.includes('custom')) {
                $(this).text(customCount + ' patterns');
            }
        });
    }
    
    function initFormHandlers() {
        // Add real-time validation
        initFormValidation();
        
        // Training form submission
        $('#revx-training-form').submit(function(e) {
            e.preventDefault();
            
            var pattern = $('#revx-pattern').val().trim();
            var response = $('#revx-response').val().trim();
            var category = $('#revx-category').val() || 'general';
            
            if (!validateTrainingForm(pattern, response)) {
                return;
            }
            
            // Show loading state
            var $submitBtn = $(this).find('button[type="submit"]');
            var originalText = $submitBtn.text();
            $submitBtn.prop('disabled', true).html('<span class="revx-loading-spinner"></span> Adding...');
            
            var data = {
                action: 'revx_add_training_data',
                pattern: pattern,
                response: response,
                category: category,
                nonce: revx_admin_ajax.nonce
            };
            
            $.post(revx_admin_ajax.ajax_url, data, function(response) {
                if (response.success) {
                    showMessage('Training data added successfully!', 'success');
                    $('#revx-training-form')[0].reset();
                    // Refresh responses if on responses tab
                    if ($('#responses-tab').hasClass('active')) {
                        loadResponses();
                    }
                } else {
                    showMessage('Error: ' + response.data, 'error');
                }
            }).fail(function() {
                showMessage('Failed to add training data. Please try again.', 'error');
            }).always(function() {
                $submitBtn.prop('disabled', false).text(originalText);
            });
        });
        
        // Settings form submission
        $('#revx-chatbot-settings-form').submit(function(e) {
            e.preventDefault();
            
            if (!validateSettingsForm()) {
                return;
            }
            
            saveGeneralSettings();
        });
        
        // OpenRouter form submission
        $('#revx-openrouter-form').submit(function(e) {
            e.preventDefault();
            saveOpenRouterSettings();
        });
        
        // Test connection button
        $('#revx-test-connection').click(function(e) {
            e.preventDefault();
            testOpenRouterConnection();
        });
    }
    
    function initUIComponents() {
        // Password toggle functionality
        $('.revx-password-toggle').click(function() {
            var $input = $(this).siblings('input');
            var $icon = $(this).find('i');
            
            if ($input.attr('type') === 'password') {
                $input.attr('type', 'text');
                $icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
            } else {
                $input.attr('type', 'password');
                $icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
            }
        });
        
        // Color picker initialization
        if ($.fn.wpColorPicker) {
            $('.revx-color-picker').wpColorPicker({
                change: function(event, ui) {
                    updatePreview();
                }
            });
        }
        
        // Toggle switch functionality
        $('.revx-toggle-switch input').change(function() {
            var isChecked = $(this).is(':checked');
            var $wrapper = $(this).closest('.revx-toggle-switch');
            
            if (isChecked) {
                $wrapper.addClass('active');
            } else {
                $wrapper.removeClass('active');
            }
            
            // Handle OpenRouter toggle
            if ($(this).attr('id') === 'revx-use-openrouter') {
                toggleOpenRouterFields(isChecked);
            }
        });
        
        // Initialize tooltips
        $('[data-tooltip]').hover(
            function() {
                var tooltip = $(this).data('tooltip');
                $('<div class="revx-tooltip">' + tooltip + '</div>').appendTo('body').fadeIn();
            },
            function() {
                $('.revx-tooltip').remove();
            }
        );
    }
    
    function loadInitialData() {
        // Load responses on page load if responses tab is active
        if ($('#responses-tab').hasClass('active')) {
            loadResponses();
        }
        
        // Update status indicators
        updateChatbotStatus();
        updateAIStatus();
    }
    
    function toggleOpenRouterFields(show) {
        var $fields = $('.revx-openrouter-fields');
        if (show) {
            $fields.slideDown();
        } else {
            $fields.slideUp();
        }
    }
    
    function updateChatbotStatus() {
        var isActive = $('#revx-chatbot-title').val().trim() !== '';
        var $statusBadge = $('.revx-status-badge');
        var $statusDot = $('.revx-status-dot');
        
        if (isActive) {
            $statusBadge.removeClass('revx-status-inactive').addClass('revx-status-active').text('Active');
            $statusDot.removeClass('revx-status-inactive').addClass('revx-status-active');
        } else {
            $statusBadge.removeClass('revx-status-active').addClass('revx-status-inactive').text('Inactive');
            $statusDot.removeClass('revx-status-active').addClass('revx-status-inactive');
        }
    }
    
    function updateAIStatus() {
        var hasApiKey = $('#revx-openrouter-api-key').val().trim() !== '';
        var isEnabled = $('#revx-use-openrouter').is(':checked');
        var $statusText = $('.revx-status-text');
        var $statusDescription = $('.revx-status-description');
        var $statusDot = $('.revx-ai-status-card .revx-status-dot');
        
        if (isEnabled && hasApiKey) {
            $statusText.text('AI Integration Active');
            $statusDescription.text('Enhanced responses powered by OpenRouter AI');
            $statusDot.removeClass('revx-status-inactive').addClass('revx-status-active');
        } else {
            $statusText.text('AI Integration Inactive');
            $statusDescription.text('Using basic pattern matching responses');
            $statusDot.removeClass('revx-status-active').addClass('revx-status-inactive');
        }
    }
    
    function updatePreview() {
        // Update chatbot preview with current settings
        var title = $('#revx-chatbot-title').val();
        var color = $('#revx-theme-color').val();
        
        // This would update a live preview if implemented
        console.log('Preview updated:', { title, color });
    }
    
    function testOpenRouterConnection() {
        var apiKey = $('#revx-openrouter-api-key').val().trim();
        var model = $('#revx-openrouter-model').val();
        
        if (!apiKey) {
            showMessage('Please enter an API key first.', 'error');
            return;
        }
        
        var $btn = $('#revx-test-connection');
        var originalText = $btn.text();
        $btn.prop('disabled', true).html('<span class="revx-loading-spinner"></span> Testing...');
        
        var data = {
            action: 'revx_test_openrouter_connection',
            api_key: apiKey,
            model: model,
            nonce: revx_admin_ajax.nonce
        };
        
        $.post(revx_admin_ajax.ajax_url, data, function(response) {
            if (response.success) {
                showMessage('Connection successful! API key is valid.', 'success');
            } else {
                showMessage('Connection failed: ' + response.data, 'error');
            }
        }).fail(function() {
            showMessage('Failed to test connection. Please try again.', 'error');
        }).always(function() {
            $btn.prop('disabled', false).text(originalText);
        });
    }
    
    // Training form submission
    $('#revx-chatbot-training-form').on('submit', function(e) {
        e.preventDefault();
        
        const pattern = $('#revx-chatbot-pattern').val().trim();
        const response = $('#revx-chatbot-response').val().trim();
        
        if (pattern === '' || response === '') {
            // Create message container if it doesn't exist
            if ($('#training-message-container').length === 0) {
                $('#training-tab').prepend('<div id="training-message-container" class="notice notice-error"><p>Pattern and response cannot be empty.</p></div>');
            } else {
                $('#training-message-container').removeClass('notice-success notice-info').addClass('notice-error').html('<p>Pattern and response cannot be empty.</p>');
            }
            
            // Show message and hide after 3 seconds
            $('#training-message-container').show();
            setTimeout(function() {
                $('#training-message-container').fadeOut();
            }, 3000);
            
            return;
        }
        
        // Create message container if it doesn't exist
        if ($('#training-message-container').length === 0) {
            $('#training-tab').prepend('<div id="training-message-container" class="notice"></div>');
        }
        
        // Show loading message
        $('#training-message-container').removeClass('notice-success notice-error').addClass('notice-info').html('<p>Saving training data...</p>').show();
        
        $.ajax({
            url: revxChatbotAdmin.ajaxurl,
            type: 'POST',
            data: {
                action: 'revx_chatbot_train',
                nonce: revxChatbotAdmin.nonce,
                pattern: pattern,
                response: response
            },
            dataType: 'json',
            cache: false,
            success: function(response) {
                if (response && response.success) {
                    $('#training-message-container').removeClass('notice-info notice-error').addClass('notice-success').html('<p>' + response.data.message + '</p>');
                    $('#revx-chatbot-pattern').val('');
                    $('#revx-chatbot-response').val('');
                } else {
                    const errorMsg = (response && response.data && response.data.message) ? response.data.message : 'Unknown error occurred';
                    $('#training-message-container').removeClass('notice-info notice-success').addClass('notice-error').html('<p>' + errorMsg + '</p>');
                }
                
                // Hide message after 3 seconds
                setTimeout(function() {
                    $('#training-message-container').fadeOut();
                }, 3000);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                $('#training-message-container').removeClass('notice-info notice-success').addClass('notice-error').html('<p>An error occurred while training the chatbot. Please check the console for details.</p>');
                
                // Hide message after 3 seconds
                setTimeout(function() {
                    $('#training-message-container').fadeOut();
                }, 3000);
            }
        });
    });
    
    // Save settings
    $('#revx-chatbot-settings-form').on('submit', function(e) {
        e.preventDefault();
        
        // Get checkbox value manually since unchecked boxes aren't included in serialized data
        const useOpenRouter = $('#revx-chatbot-use-openrouter').is(':checked') ? 1 : 0;
        
        $.ajax({
            url: revxChatbotAdmin.ajaxurl,
            type: 'POST',
            data: {
                action: 'revx_chatbot_save_settings',
                nonce: revxChatbotAdmin.nonce,
                ...Object.fromEntries(new FormData(this)),
                use_openrouter: useOpenRouter
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                } else {
                    alert(response.data.message || 'Error saving settings');
                }
            },
            error: function() {
                alert('Error communicating with the server');
            }
        });
    });
    
    // Save OpenRouter API settings
    $('#revx-chatbot-openrouter-form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: revxChatbotAdmin.ajaxurl,
            type: 'POST',
            data: {
                action: 'revx_chatbot_save_openrouter_settings',
                nonce: revxChatbotAdmin.nonce,
                ...Object.fromEntries(new FormData(this))
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                } else {
                    alert(response.data.message || 'Error saving API settings');
                }
            },
            error: function() {
                alert('Error communicating with the server');
            }
        });
    });
    
    // Function to load responses
    function loadResponses() {
        showLoadingState('#responses-list');
        
        $.ajax({
            url: revxChatbotAdmin.ajaxurl,
            type: 'POST',
            data: {
                action: 'revx_chatbot_get_all_responses',
                nonce: revxChatbotAdmin.nonce
            },
            success: function(response) {
                console.log('AJAX Response received:', response);
                
                if (response.success && response.data) {
                    console.log('Response data:', response.data);
                    
                    // Handle both possible data structures
                    let responseData;
                    if (response.data.responses) {
                        responseData = response.data.responses;
                    } else {
                        responseData = response.data;
                    }
                    
                    console.log('Processing response data:', responseData);
                    displayResponses(responseData);
                    updateResponseStats(responseData);
                    
                    // Initialize response editing after loading
                    initResponseEditing();
                } else {
                    console.error('Invalid response structure:', response);
                    showEmptyState('#responses-list', 'Failed to load responses - invalid data structure.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error loading responses:', error);
                showEmptyState('#responses-list', 'An error occurred while loading responses.');
            }
        });
    }
    
    function showLoadingState(container) {
        $(container).html(`
            <div class="revx-loading-state">
                <div class="revx-loading-spinner"></div>
                <p>Loading responses...</p>
            </div>
        `);
    }
    
    function showEmptyState(container, message) {
        $(container).html(`
            <div class="revx-empty-state">
                <div class="revx-empty-icon">
                    <i class="dashicons dashicons-format-chat"></i>
                </div>
                <h3>No Responses Found</h3>
                <p>${message}</p>
                <button class="revx-btn revx-btn-primary" onclick="$('.revx-chatbot-tabs button[data-tab=\"training\"]').click()">
                    <i class="dashicons dashicons-plus"></i> Add Training Data
                </button>
            </div>
        `);
    }
    
    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Function to display responses
    function displayResponses(responses) {
        if (!responses || typeof responses !== 'object') {
            showEmptyState('#responses-list', 'No response data available.');
            return;
        }
        
        let html = '<div class="revx-responses-wrapper">';
        
        // Response statistics
        html += '<div class="revx-response-stats">';
        html += '<div class="revx-stat-item">';
        html += '<span class="revx-stat-number">' + (responses.greetings ? responses.greetings.length : 0) + '</span>';
        html += '<span class="revx-stat-label">Greetings</span>';
        html += '</div>';
        html += '<div class="revx-stat-item">';
        html += '<span class="revx-stat-number">' + (responses.farewell ? responses.farewell.length : 0) + '</span>';
        html += '<span class="revx-stat-label">Farewells</span>';
        html += '</div>';
        html += '<div class="revx-stat-item">';
        html += '<span class="revx-stat-number">' + (responses.fallback ? responses.fallback.length : 0) + '</span>';
        html += '<span class="revx-stat-label">Fallbacks</span>';
        html += '</div>';
        html += '<div class="revx-stat-item">';
        html += '<span class="revx-stat-number">' + (responses.responses ? responses.responses.length : 0) + '</span>';
        html += '<span class="revx-stat-label">Custom</span>';
        html += '</div>';
        html += '</div>';
        
        html += '<div class="revx-responses-content">';
        
        // Add greetings
        if (responses.greetings && responses.greetings.length > 0) {
            html += '<div class="revx-response-category">';
            html += '<div class="revx-category-header">';
            html += '<h3><span class="revx-category-icon">👋</span> Greetings</h3>';
            html += '<span class="revx-category-count">' + responses.greetings.length + ' items</span>';
            html += '</div>';
            html += '<div class="revx-response-items">';
            responses.greetings.forEach(function(greeting, index) {
                html += '<div class="revx-response-item">' + 
                        '<span class="revx-response-text">' + escapeHtml(greeting) + '</span>' + 
                        '<button type="button" class="revx-btn revx-btn-small revx-edit-response" ' + 
                        'data-category="greetings" data-index="' + index + '">' + 
                        '<i class="dashicons dashicons-edit"></i> Edit</button>' + 
                        '</div>';
            });
            html += '</div>';
            html += '</div>';
        }
        
        // Add farewells
        if (responses.farewell && responses.farewell.length > 0) {
            html += '<div class="revx-response-category">';
            html += '<div class="revx-category-header">';
            html += '<h3><span class="revx-category-icon">👋</span> Farewells</h3>';
            html += '<span class="revx-category-count">' + responses.farewell.length + ' items</span>';
            html += '</div>';
            html += '<div class="revx-response-items">';
            responses.farewell.forEach(function(farewell, index) {
                html += '<div class="revx-response-item">' + 
                        '<span class="revx-response-text">' + escapeHtml(farewell) + '</span>' + 
                        '<button type="button" class="revx-btn revx-btn-small revx-edit-response" ' + 
                        'data-category="farewell" data-index="' + index + '">' + 
                        '<i class="dashicons dashicons-edit"></i> Edit</button>' + 
                        '</div>';
            });
            html += '</div>';
            html += '</div>';
        }
        
        // Add fallbacks
        if (responses.fallback && responses.fallback.length > 0) {
            html += '<div class="revx-response-category">';
            html += '<div class="revx-category-header">';
            html += '<h3><span class="revx-category-icon">🤔</span> Fallbacks</h3>';
            html += '<span class="revx-category-count">' + responses.fallback.length + ' items</span>';
            html += '</div>';
            html += '<div class="revx-response-items">';
            responses.fallback.forEach(function(fallback, index) {
                html += '<div class="revx-response-item">' + 
                        '<span class="revx-response-text">' + escapeHtml(fallback) + '</span>' + 
                        '<button type="button" class="revx-btn revx-btn-small revx-edit-response" ' + 
                        'data-category="fallback" data-index="' + index + '">' + 
                        '<i class="dashicons dashicons-edit"></i> Edit</button>' + 
                        '</div>';
            });
            html += '</div>';
            html += '</div>';
        }
        
        // Add custom responses
        if (responses.responses && responses.responses.length > 0) {
            html += '<div class="revx-response-category">';
            html += '<div class="revx-category-header">';
            html += '<h3><span class="revx-category-icon">💬</span> Custom Responses</h3>';
            html += '<span class="revx-category-count">' + responses.responses.length + ' patterns</span>';
            html += '</div>';
            html += '<div class="revx-custom-responses">';
            
            responses.responses.forEach(function(item, itemIndex) {
                if (item && item.patterns && item.responses) {
                    html += '<div class="revx-custom-response-group">';
                    html += '<div class="revx-patterns-section">';
                    html += '<h4>Patterns:</h4>';
                    html += '<div class="revx-pattern-list">';
                    item.patterns.forEach(function(pattern, patternIndex) {
                        html += '<div class="revx-pattern-item">' + 
                                '<span class="revx-response-text">' + escapeHtml(pattern) + '</span>' + 
                                '<button type="button" class="revx-btn revx-btn-small revx-edit-response" ' + 
                                'data-category="responses" data-index="' + itemIndex + '" ' + 
                                'data-pattern-id="' + patternIndex + '">' + 
                                '<i class="dashicons dashicons-edit"></i> Edit</button>' + 
                                '</div>';
                    });
                    html += '</div>';
                    html += '</div>';
                    
                    html += '<div class="revx-responses-section">';
                    html += '<h4>Responses:</h4>';
                    html += '<div class="revx-response-list">';
                    item.responses.forEach(function(response, responseIndex) {
                        html += '<div class="revx-response-item">' + 
                                '<span class="revx-response-text">' + escapeHtml(response) + '</span>' + 
                                '<button type="button" class="revx-btn revx-btn-small revx-edit-response" ' + 
                                'data-category="responses" data-index="' + itemIndex + '" ' + 
                                'data-response-id="' + responseIndex + '">' + 
                                '<i class="dashicons dashicons-edit"></i> Edit</button>' + 
                                '</div>';
                    });
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                }
            });
            
            html += '</div>';
            html += '</div>';
        }
        
        html += '</div>'; // Close revx-responses-content
        html += '</div>'; // Close revx-responses-wrapper
        
        // Add edit modal
        html += '<div id="revx-edit-response-modal" class="revx-modal">' +
                '<div class="revx-modal-content">' +
                '<span class="revx-modal-close">&times;</span>' +
                '<h3>Edit Response</h3>' +
                '<form id="revx-edit-response-form">' +
                '<input type="hidden" id="revx-edit-category" name="category" value="">' +
                '<input type="hidden" id="revx-edit-index" name="index" value="">' +
                '<input type="hidden" id="revx-edit-pattern-id" name="pattern_id" value="">' +
                '<input type="hidden" id="revx-edit-response-id" name="response_id" value="">' +
                '<div class="revx-form-group">' +
                '<label for="revx-edit-text">Response Text:</label>' +
                '<textarea id="revx-edit-text" name="text" rows="4" class="large-text"></textarea>' +
                '</div>' +
                '<div class="revx-form-actions">' +
                '<button type="submit" class="button button-primary">Save Changes</button>' +
                '<button type="button" class="button revx-cancel-edit">Cancel</button>' +
                '</div>' +
                '</form>' +
                '</div>' +
                '</div>';
        
        $('#responses-list').html(html);
        
        // Initialize edit buttons and modal functionality
        initResponseEditing();
    }
    
    // Function to initialize response editing functionality
    function initResponseEditing() {
        // Edit button click handler
        $('.revx-edit-response').on('click', function() {
            const category = $(this).data('category');
            const index = $(this).data('index');
            const patternId = $(this).data('pattern-id');
            const responseId = $(this).data('response-id');
            const text = $(this).siblings('.revx-response-text').text();
            
            // Populate the form
            $('#revx-edit-category').val(category);
            $('#revx-edit-index').val(index);
            $('#revx-edit-pattern-id').val(patternId !== undefined ? patternId : '');
            $('#revx-edit-response-id').val(responseId !== undefined ? responseId : '');
            $('#revx-edit-text').val(text);
            
            // Show the modal
            $('#revx-edit-response-modal').show();
        });
        
        // Close modal when clicking the X
        $('.revx-modal-close, .revx-cancel-edit').on('click', function() {
            $('#revx-edit-response-modal').hide();
        });
        
        // Close modal when clicking outside of it
        $(window).on('click', function(event) {
            if ($(event.target).is('#revx-edit-response-modal')) {
                $('#revx-edit-response-modal').hide();
            }
        });
        
        // Handle form submission
        $('#revx-edit-response-form').on('submit', function(e) {
            e.preventDefault();
            
            const category = $('#revx-edit-category').val();
            const index = $('#revx-edit-index').val();
            const patternId = $('#revx-edit-pattern-id').val();
            const responseId = $('#revx-edit-response-id').val();
            const text = $('#revx-edit-text').val().trim();
            
            if (text === '') {
                alert('Response text cannot be empty.');
                return;
            }
            
            // Show loading message
            const modalContent = $('.revx-modal-content');
            modalContent.append('<div class="revx-loading">Saving changes...</div>');
            
            // Send AJAX request to update the response
            $.ajax({
                url: revxChatbotAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'revx_chatbot_update_response',
                    nonce: revxChatbotAdmin.nonce,
                    category: category,
                    index: index,
                    pattern_id: patternId,
                    response_id: responseId,
                    text: text
                },
                success: function(response) {
                    $('.revx-loading').remove();
                    
                    if (response.success) {
                        // Close the modal
                        $('#revx-edit-response-modal').hide();
                        
                        // Reload responses to show the updated data
                        loadResponses();
                        
                        // Show success message
                        if ($('#response-message-container').length === 0) {
                            $('#responses-tab').prepend('<div id="response-message-container" class="notice notice-success"><p>' + response.data.message + '</p></div>');
                        } else {
                            $('#response-message-container').removeClass('notice-error').addClass('notice-success').html('<p>' + response.data.message + '</p>');
                        }
                        
                        // Hide message after 3 seconds
                        $('#response-message-container').show();
                        setTimeout(function() {
                            $('#response-message-container').fadeOut();
                        }, 3000);
                    } else {
                        const errorMsg = (response.data && response.data.message) ? response.data.message : 'Unknown error occurred';
                        alert('Error: ' + errorMsg);
                    }
                },
                error: function() {
                    $('.revx-loading').remove();
                    alert('An error occurred while updating the response. Please try again.');
                }
            });
        });
    }
    
    // Save general settings
    function saveGeneralSettings() {
        var $form = $('#revx-chatbot-settings-form');
        var $submitBtn = $form.find('button[type="submit"]');
        var originalText = $submitBtn.find('.revx-btn-text').text();
        
        // Show loading state
        $submitBtn.prop('disabled', true).addClass('loading');
        $submitBtn.find('.revx-btn-text').hide();
        $submitBtn.find('.revx-btn-loading').show();
        
        var formData = {
            action: 'revx_chatbot_save_settings',
            nonce: revx_admin_ajax.nonce,
            chatbot_name: $('#revx-chatbot-name').val(),
            title: $('#revx-chatbot-title').val(),
            theme_color: $('#revx-theme-color').val(),
            initial_message: $('#revx-chatbot-initial-message').val(),
            ai_enhancement: $('#revx-ai-enhancement').is(':checked') ? 1 : 0
        };
        
        $.ajax({
            url: revx_admin_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showMessage('Settings saved successfully!', 'success');
                    updateChatbotStatus();
                } else {
                    showMessage('Failed to save settings: ' + response.data, 'error');
                }
            },
            error: function() {
                showMessage('An error occurred while saving settings.', 'error');
            },
            complete: function() {
                // Reset button state
                $submitBtn.prop('disabled', false).removeClass('loading');
                $submitBtn.find('.revx-btn-text').show().text(originalText);
                $submitBtn.find('.revx-btn-loading').hide();
            }
        });
    }
    
    // Save OpenRouter settings
    function saveOpenRouterSettings() {
        var $form = $('#revx-openrouter-form');
        var $submitBtn = $form.find('button[type="submit"]');
        var originalText = $submitBtn.text();
        
        $submitBtn.prop('disabled', true).html('<span class="revx-loading-spinner"></span> Saving...');
        
        var formData = $form.serialize();
        
        $.ajax({
            url: revx_admin_ajax.ajax_url,
            type: 'POST',
            data: formData + '&action=revx_save_openrouter_settings&nonce=' + revx_admin_ajax.nonce,
            success: function(response) {
                if (response.success) {
                    showMessage('OpenRouter settings saved successfully!', 'success');
                    updateAIStatus();
                } else {
                    showMessage('Failed to save OpenRouter settings: ' + response.data, 'error');
                }
            },
            error: function() {
                showMessage('An error occurred while saving OpenRouter settings.', 'error');
            },
            complete: function() {
                $submitBtn.prop('disabled', false).text(originalText);
            }
        });
    }
    
    // Enhanced show message function
    function showMessage(message, type) {
        var messageClass = type === 'success' ? 'revx-message-success' : 'revx-message-error';
        var icon = type === 'success' ? 'yes-alt' : 'warning';
        
        var messageHtml = `
            <div class="revx-message ${messageClass}">
                <i class="dashicons dashicons-${icon}"></i>
                <span>${message}</span>
                <button class="revx-message-close" onclick="$(this).parent().fadeOut()">
                    <i class="dashicons dashicons-no-alt"></i>
                </button>
            </div>
        `;
        
        // Remove existing messages
        $('.revx-message').remove();
        
        // Add new message
        $('.revx-chatbot-admin-container').prepend(messageHtml);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            $('.revx-message').fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }
    
    // Global functions for response management
    window.editResponse = function(index) {
        console.log('Edit response:', index);
        showMessage('Edit functionality integrated with existing modal system!', 'success');
    };
    
    window.deleteResponse = function(index) {
        if (confirm('Are you sure you want to delete this response?')) {
            console.log('Delete response:', index);
            showMessage('Delete functionality coming soon!', 'info');
        }
    };
    
    // Form validation functions
    function initFormValidation() {
        // Initialize character counters
        initCharacterCounters();
        
        // Real-time validation for settings form
        $('#revx-chatbot-name, #revx-chatbot-title').on('input blur', function() {
            validateField($(this));
            updateCharacterCounter($(this));
        });
        
        $('#revx-chatbot-initial-message').on('input blur', function() {
            validateField($(this));
            updateCharacterCounter($(this));
        });
        
        // Real-time validation for training form
        $('#revx-pattern, #revx-response').on('input blur', function() {
            validateField($(this));
        });
    }
    
    function initCharacterCounters() {
        // Initialize counters for existing content
        $('#revx-chatbot-name, #revx-chatbot-title, #revx-chatbot-initial-message').each(function() {
            updateCharacterCounter($(this));
        });
    }
    
    function updateCharacterCounter($field) {
        var $counter = $field.siblings('.revx-char-counter');
        if ($counter.length) {
            var currentLength = $field.val().length;
            var maxLength = parseInt($field.attr('maxlength')) || 0;
            var $current = $counter.find('.current');
            
            $current.text(currentLength);
            
            // Update counter styling based on usage
            $counter.removeClass('warning danger');
            if (maxLength > 0) {
                var percentage = (currentLength / maxLength) * 100;
                if (percentage >= 90) {
                    $counter.addClass('danger');
                } else if (percentage >= 75) {
                    $counter.addClass('warning');
                }
            }
        }
    }
    
    function validateField($field) {
        var value = $field.val().trim();
        var fieldName = $field.attr('name');
        var isValid = true;
        var errorMessage = '';
        
        // Remove existing validation classes
        $field.removeClass('revx-input-error revx-input-success');
        $field.siblings('.revx-field-error').remove();
        
        // Required field validation
        if ($field.prop('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required.';
        }
        
        // Specific field validations
        switch(fieldName) {
            case 'chatbot_name':
                if (value && (value.length < 2 || value.length > 50)) {
                    isValid = false;
                    errorMessage = 'Chatbot name must be between 2 and 50 characters.';
                }
                break;
            case 'title':
                if (value && (value.length < 3 || value.length > 100)) {
                    isValid = false;
                    errorMessage = 'Title must be between 3 and 100 characters.';
                }
                break;
            case 'initial_message':
                if (value && (value.length < 10 || value.length > 500)) {
                    isValid = false;
                    errorMessage = 'Welcome message must be between 10 and 500 characters.';
                }
                break;
            case 'pattern':
                if (value && value.length < 3) {
                    isValid = false;
                    errorMessage = 'Pattern must be at least 3 characters long.';
                }
                break;
            case 'response':
                if (value && value.length < 5) {
                    isValid = false;
                    errorMessage = 'Response must be at least 5 characters long.';
                }
                break;
        }
        
        // Apply validation styling
        if (!isValid) {
            $field.addClass('revx-input-error');
            $field.after('<div class="revx-field-error">' + errorMessage + '</div>');
        } else if (value) {
            $field.addClass('revx-input-success');
        }
        
        return isValid;
    }
    
    function validateSettingsForm() {
        var isValid = true;
        var $form = $('#revx-chatbot-settings-form');
        
        // Validate all required fields
        $form.find('input[required], textarea[required]').each(function() {
            if (!validateField($(this))) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            showMessage('Please fix the errors in the form before saving.', 'error');
            // Focus on first error field
            $form.find('.revx-input-error').first().focus();
        }
        
        return isValid;
    }
    
    function validateTrainingForm(pattern, response) {
        var isValid = true;
        
        if (!pattern || pattern.length < 3) {
            showMessage('Pattern must be at least 3 characters long.', 'error');
            $('#revx-pattern').focus().addClass('revx-input-error');
            isValid = false;
        }
        
        if (!response || response.length < 5) {
            showMessage('Response must be at least 5 characters long.', 'error');
            if (isValid) $('#revx-response').focus();
            $('#revx-response').addClass('revx-input-error');
            isValid = false;
        }
        
        if (!pattern || !response) {
            showMessage('Please fill in both pattern and response fields.', 'error');
            isValid = false;
        }
        
        return isValid;
    }
});