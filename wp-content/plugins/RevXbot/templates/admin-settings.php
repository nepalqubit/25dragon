<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check if WordPress functions are available
if (!function_exists('esc_attr') || !function_exists('get_option') || 
    !function_exists('checked') || !function_exists('selected')) {
    echo '<div class="notice notice-error"><p>WordPress functions not available. This plugin requires WordPress.</p></div>';
    return;
}

// Define fallback functions for WordPress functions if they don't exist
if (!function_exists('esc_attr')) {
    function esc_attr($text) { return htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('get_option')) {
    function get_option($option, $default = false) { return $default; }
}
if (!function_exists('checked')) {
    function checked($checked, $current = true, $echo = true) {
        $result = ($checked == $current) ? ' checked="checked"' : '';
        if ($echo) echo $result;
        return $result;
    }
}
if (!function_exists('selected')) {
    function selected($selected, $current = true, $echo = true) {
        $result = ($selected == $current) ? ' selected="selected"' : '';
        if ($echo) echo $result;
        return $result;
    }
}
?>
<div class="wrap revx-chatbot-admin-container">
    <div class="revx-admin-header">
        <div class="revx-admin-header-content">
            <div class="revx-admin-title">
                <h1><span class="revx-logo">🤖</span> RevX Chatbot for HAPN</h1>
                <p class="revx-subtitle">Manage your Hotel Association Pokhara Nepal chatbot settings and responses</p>
            </div>
            <div class="revx-admin-status">
                <div class="revx-status-indicator">
                    <span class="revx-status-dot active"></span>
                    <span class="revx-status-text">Chatbot Active</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="revx-chatbot-admin-tabs">
        <button class="revx-chatbot-tab-button active" data-tab="settings">
            <span class="revx-tab-icon">⚙️</span>
            <span class="revx-tab-text">General Settings</span>
        </button>
        <button class="revx-chatbot-tab-button" data-tab="responses">
            <span class="revx-tab-icon">💬</span>
            <span class="revx-tab-text">View Responses</span>
        </button>
        <button class="revx-chatbot-tab-button" data-tab="training">
            <span class="revx-tab-icon">🎓</span>
            <span class="revx-tab-text">Train Chatbot</span>
        </button>
        <button class="revx-chatbot-tab-button" data-tab="openrouter">
            <span class="revx-tab-icon">🔗</span>
            <span class="revx-tab-text">AI Integration</span>
        </button>
    </div>
    
    <div class="revx-chatbot-admin-content">
        <!-- Settings Tab -->
        <div class="revx-chatbot-tab-content active" id="settings-tab">
            <div class="revx-tab-header">
                <h2>⚙️ General Settings</h2>
                <p class="revx-tab-description">Configure your chatbot's appearance and basic behavior</p>
            </div>
            
            <div id="settings-message-container" class="revx-message-container"></div>
            
            <form id="revx-chatbot-settings-form" class="revx-modern-form">
                <div class="revx-form-grid">
                    <div class="revx-form-card">
                        <div class="revx-card-header">
                            <h3>🎨 Appearance Settings</h3>
                            <p>Customize how your chatbot looks and feels</p>
                        </div>
                        <div class="revx-form-group">
                            <label for="revx-chatbot-name" class="revx-label">
                                <span class="revx-label-text">Chatbot Name</span>
                                <span class="revx-label-required">*</span>
                            </label>
                            <input type="text" id="revx-chatbot-name" name="chatbot_name" value="<?php echo esc_attr(get_option('revx_chatbot_name', 'RevX Assistant')); ?>" class="revx-input" placeholder="Enter chatbot name" required maxlength="50">
                            <div class="revx-char-counter"><span class="current">0</span>/50</div>
                            <p class="revx-help-text">The name your chatbot will use to introduce itself</p>
                        </div>
                        
                        <div class="revx-form-group">
                            <label for="revx-chatbot-title" class="revx-label">
                                <span class="revx-label-text">Chatbot Title</span>
                                <span class="revx-label-required">*</span>
                            </label>
                            <input type="text" id="revx-chatbot-title" name="title" value="<?php echo esc_attr(get_option('revx_chatbot_title', 'RevX Chatbot')); ?>" class="revx-input" placeholder="Enter chatbot title" required maxlength="100">
                            <div class="revx-char-counter"><span class="current">0</span>/100</div>
                            <p class="revx-help-text">This title appears in the chatbot header</p>
                        </div>
                        
                        <div class="revx-form-group">
                            <label for="revx-chatbot-theme-color" class="revx-label">
                                <span class="revx-label-text">Theme Color</span>
                            </label>
                            <div class="revx-color-picker-wrapper">
                                <input type="color" id="revx-chatbot-theme-color" name="theme_color" value="<?php echo esc_attr(get_option('revx_chatbot_theme_color', '#4a6cf7')); ?>" class="revx-color-input">
                                <span class="revx-color-preview"></span>
                            </div>
                            <p class="revx-help-text">Choose the primary color for your chatbot interface</p>
                        </div>
                    </div>
                    
                    <div class="revx-form-card">
                        <div class="revx-card-header">
                            <h3>💬 Conversation Settings</h3>
                            <p>Configure how your chatbot interacts with users</p>
                        </div>
                        <div class="revx-form-group">
                            <label for="revx-chatbot-initial-message" class="revx-label">
                                <i class="revx-icon-message"></i>
                                Welcome Message <span style="color: #dc3545;">*</span>
                            </label>
                            <textarea id="revx-chatbot-initial-message" name="initial_message" class="revx-textarea" rows="3" placeholder="Enter welcome message" required maxlength="500"><?php echo esc_attr(get_option('revx_chatbot_initial_message', 'Hello! How can I help you today?')); ?></textarea>
                            <div class="revx-char-counter"><span class="current">0</span>/500</div>
                            <p class="revx-help-text">The first message users see when they open the chatbot</p>
                        </div>
                        
                        <div class="revx-form-group">
                            <div class="revx-toggle-wrapper">
                                <label for="revx-chatbot-use-openrouter" class="revx-toggle-label">
                                    <input type="checkbox" id="revx-chatbot-use-openrouter" name="use_openrouter" <?php checked(get_option('revx_chatbot_use_openrouter', false)); ?> class="revx-toggle-input">
                                    <span class="revx-toggle-slider"></span>
                                    <span class="revx-toggle-text">
                                        <strong>Enable AI Enhancement</strong>
                                        <small>Use OpenRouter API for better understanding of complex queries</small>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="revx-form-actions">
                    <button type="submit" class="revx-btn revx-btn-primary">
                        <span class="revx-btn-icon">💾</span>
                        <span class="revx-btn-text">Save Settings</span>
                        <span class="revx-btn-loading">Saving...</span>
                    </button>
                    <button type="button" class="revx-btn revx-btn-secondary" id="revx-preview-chatbot">
                        <span class="revx-btn-icon">👁️</span>
                        <span class="revx-btn-text">Preview Chatbot</span>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Responses Tab -->
        <div class="revx-chatbot-tab-content" id="responses-tab">
            <div class="revx-tab-header">
                <h2>📋 Response Management</h2>
                <p class="revx-tab-description">View and manage all chatbot responses and patterns</p>
            </div>
            
            <div class="revx-responses-controls">
                <div class="revx-search-wrapper">
                    <input type="text" id="revx-response-search" class="revx-search-input" placeholder="🔍 Search patterns or responses...">
                    <button type="button" class="revx-btn revx-btn-outline" id="revx-refresh-responses">
                        <span class="revx-btn-icon">🔄</span>
                        <span class="revx-btn-text">Refresh</span>
                    </button>
                </div>
                
                <div class="revx-filter-wrapper">
                    <select id="revx-response-filter" class="revx-select">
                        <option value="all">All Categories</option>
                        <option value="greetings">Greetings</option>
                        <option value="hotels">Hotels</option>
                        <option value="tourism">Tourism</option>
                        <option value="general">General</option>
                    </select>
                    
                    <div class="revx-stats">
                        <span class="revx-stat-item">
                            <strong class="revx-stat-number">0</strong>
                            <small>Greetings</small>
                        </span>
                        <span class="revx-stat-item">
                            <strong class="revx-stat-number">0</strong>
                            <small>Farewells</small>
                        </span>
                        <span class="revx-stat-item">
                            <strong class="revx-stat-number">0</strong>
                            <small>Fallbacks</small>
                        </span>
                        <span class="revx-stat-item">
                            <strong class="revx-stat-number">0</strong>
                            <small>Custom Patterns</small>
                        </span>
                    </div>
                </div>
            </div>
            
            <div id="responses-loading" class="revx-loading-state">
                <div class="revx-spinner"></div>
                <p>Loading responses...</p>
            </div>
            
            <div id="responses-list" class="revx-responses-container"></div>
            
            <div id="responses-empty" class="revx-empty-state" style="display: none;">
                <div class="revx-empty-icon">📝</div>
                <h3>No responses found</h3>
                <p>Try adjusting your search or filter criteria</p>
            </div>
        </div>
        
        <!-- Training Tab -->
        <div class="revx-chatbot-tab-content" id="training-tab">
            <div class="revx-tab-header">
                <h2>🎓 Train Your Chatbot</h2>
                <p class="revx-tab-description">Add new patterns and responses to improve your chatbot's knowledge</p>
            </div>
            
            <div id="training-message-container" class="revx-message-container"></div>
            
            <div class="revx-training-wrapper">
                <div class="revx-training-form-card">
                    <div class="revx-card-header">
                        <h3>➕ Add New Training Data</h3>
                        <p>Teach your chatbot how to respond to specific user inputs</p>
                    </div>
                    
                    <form id="revx-chatbot-training-form" class="revx-modern-form">
                        <div class="revx-form-group">
                            <label for="revx-chatbot-pattern" class="revx-label">
                                <span class="revx-label-text">User Input Patterns</span>
                                <span class="revx-label-required">*</span>
                            </label>
                            <input type="text" id="revx-chatbot-pattern" name="pattern" class="revx-input" placeholder="hello, hi, good morning, greetings" required>
                            <p class="revx-help-text">Enter keywords or phrases users might type (separate multiple patterns with commas)</p>
                            <div class="revx-pattern-examples">
                                <strong>Examples:</strong>
                                <span class="revx-example-tag">hotel booking</span>
                                <span class="revx-example-tag">room availability</span>
                                <span class="revx-example-tag">contact information</span>
                            </div>
                        </div>
                        
                        <div class="revx-form-group">
                            <label for="revx-chatbot-response" class="revx-label">
                                <span class="revx-label-text">Chatbot Response</span>
                                <span class="revx-label-required">*</span>
                            </label>
                            <textarea id="revx-chatbot-response" name="response" class="revx-textarea" rows="4" placeholder="Enter how the chatbot should respond to these patterns..." required></textarea>
                            <p class="revx-help-text">Write a helpful and informative response for the patterns above</p>
                            <div class="revx-response-tips">
                                <div class="revx-tip">
                                    <strong>💡 Tip:</strong> Keep responses clear, helpful, and on-brand for HAPN
                                </div>
                            </div>
                        </div>
                        
                        <div class="revx-form-group">
                            <label for="revx-chatbot-category" class="revx-label">
                                <span class="revx-label-text">Category</span>
                            </label>
                            <select id="revx-chatbot-category" name="category" class="revx-select">
                                <option value="general">General</option>
                                <option value="greetings">Greetings</option>
                                <option value="hotels">Hotels</option>
                                <option value="tourism">Tourism</option>
                                <option value="booking">Booking</option>
                                <option value="contact">Contact</option>
                            </select>
                            <p class="revx-help-text">Choose a category to help organize your responses</p>
                        </div>
                        
                        <div class="revx-form-actions">
                            <button type="submit" class="revx-btn revx-btn-primary">
                                <span class="revx-btn-icon">🎓</span>
                                <span class="revx-btn-text">Add Training Data</span>
                                <span class="revx-btn-loading">Adding...</span>
                            </button>
                            <button type="button" class="revx-btn revx-btn-secondary" id="revx-clear-form">
                                <span class="revx-btn-icon">🗑️</span>
                                <span class="revx-btn-text">Clear Form</span>
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="revx-training-tips-card">
                    <div class="revx-card-header">
                        <h3>💡 Training Tips</h3>
                    </div>
                    <div class="revx-tips-list">
                        <div class="revx-tip-item">
                            <span class="revx-tip-icon">🎯</span>
                            <div>
                                <strong>Be Specific</strong>
                                <p>Use specific patterns that users are likely to type</p>
                            </div>
                        </div>
                        <div class="revx-tip-item">
                            <span class="revx-tip-icon">🔄</span>
                            <div>
                                <strong>Add Variations</strong>
                                <p>Include different ways users might ask the same question</p>
                            </div>
                        </div>
                        <div class="revx-tip-item">
                            <span class="revx-tip-icon">📝</span>
                            <div>
                                <strong>Clear Responses</strong>
                                <p>Write responses that are helpful and easy to understand</p>
                            </div>
                        </div>
                        <div class="revx-tip-item">
                            <span class="revx-tip-icon">🏨</span>
                            <div>
                                <strong>HAPN Focus</strong>
                                <p>Keep responses relevant to HAPN and Pokhara tourism</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- OpenRouter API Tab -->
        <div class="revx-chatbot-tab-content" id="openrouter-tab">
            <div class="revx-tab-header">
                <h2>🤖 AI Integration</h2>
                <p class="revx-tab-description">Configure advanced AI capabilities for your chatbot</p>
            </div>
            
            <div id="openrouter-message-container" class="revx-message-container"></div>
            
            <div class="revx-ai-integration-wrapper">
                <div class="revx-ai-status-card">
                    <div class="revx-status-indicator">
                        <span class="revx-status-dot revx-status-<?php echo get_option('revx_chatbot_openrouter_api_key', '') ? 'active' : 'inactive'; ?>"></span>
                        <span class="revx-status-text">
                            <?php echo get_option('revx_chatbot_openrouter_api_key', '') ? 'AI Integration Active' : 'AI Integration Inactive'; ?>
                        </span>
                    </div>
                    <p class="revx-status-description">
                        <?php echo get_option('revx_chatbot_openrouter_api_key', '') ? 'Your chatbot is enhanced with AI capabilities' : 'Configure API settings to enable AI enhancement'; ?>
                    </p>
                </div>
                
                <form id="revx-chatbot-openrouter-form" class="revx-modern-form">
                    <div class="revx-form-card">
                        <div class="revx-card-header">
                            <h3>🔑 API Configuration</h3>
                            <p>Set up your OpenRouter API for advanced AI features</p>
                        </div>
                        
                        <div class="revx-form-group">
                            <label for="revx-chatbot-openrouter-api-key" class="revx-label">
                                <span class="revx-label-text">OpenRouter API Key</span>
                                <span class="revx-label-required">*</span>
                            </label>
                            <div class="revx-password-wrapper">
                                <input type="password" id="revx-chatbot-openrouter-api-key" name="openrouter_api_key" value="<?php echo esc_attr(get_option('revx_chatbot_openrouter_api_key', '')); ?>" class="revx-input" placeholder="sk-or-v1-...">
                                <button type="button" class="revx-password-toggle" id="revx-toggle-api-key">
                                    <span class="revx-eye-icon">👁️</span>
                                </button>
                            </div>
                            <p class="revx-help-text">
                                Get your API key from <a href="https://openrouter.ai/" target="_blank" class="revx-link">OpenRouter.ai</a>
                                <span class="revx-security-note">🔒 Your API key is stored securely</span>
                            </p>
                        </div>
                        
                        <div class="revx-form-group">
                            <label for="revx-chatbot-openrouter-model" class="revx-label">
                                <span class="revx-label-text">AI Model</span>
                            </label>
                            <select id="revx-chatbot-openrouter-model" name="openrouter_model" class="revx-select">
                                <option value="openai/gpt-3.5-turbo" <?php selected(get_option('revx_chatbot_openrouter_model', 'openai/gpt-3.5-turbo'), 'openai/gpt-3.5-turbo'); ?>>GPT-3.5 Turbo (Fast & Cost-effective)</option>
                                <option value="openai/gpt-4" <?php selected(get_option('revx_chatbot_openrouter_model', 'openai/gpt-3.5-turbo'), 'openai/gpt-4'); ?>>GPT-4 (Most Capable)</option>
                                <option value="anthropic/claude-3-haiku" <?php selected(get_option('revx_chatbot_openrouter_model', 'openai/gpt-3.5-turbo'), 'anthropic/claude-3-haiku'); ?>>Claude 3 Haiku (Fast)</option>
                                <option value="anthropic/claude-3-sonnet" <?php selected(get_option('revx_chatbot_openrouter_model', 'openai/gpt-3.5-turbo'), 'anthropic/claude-3-sonnet'); ?>>Claude 3 Sonnet (Balanced)</option>
                            </select>
                            <p class="revx-help-text">Choose the AI model that best fits your needs and budget</p>
                        </div>
                        
                        <div class="revx-form-actions">
                            <button type="submit" class="revx-btn revx-btn-primary">
                                <span class="revx-btn-icon">🔗</span>
                                <span class="revx-btn-text">Save API Settings</span>
                                <span class="revx-btn-loading">Saving...</span>
                            </button>
                            <button type="button" class="revx-btn revx-btn-outline" id="revx-test-api">
                                <span class="revx-btn-icon">🧪</span>
                                <span class="revx-btn-text">Test Connection</span>
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="revx-ai-features-card">
                    <div class="revx-card-header">
                        <h3>✨ AI Features</h3>
                        <p>What you get with AI integration</p>
                    </div>
                    <div class="revx-features-grid">
                        <div class="revx-feature-item">
                            <span class="revx-feature-icon">🧠</span>
                            <div>
                                <strong>Smart Understanding</strong>
                                <p>Better comprehension of complex user queries</p>
                            </div>
                        </div>
                        <div class="revx-feature-item">
                            <span class="revx-feature-icon">💬</span>
                            <div>
                                <strong>Natural Responses</strong>
                                <p>More human-like and contextual conversations</p>
                            </div>
                        </div>
                        <div class="revx-feature-item">
                            <span class="revx-feature-icon">🎯</span>
                            <div>
                                <strong>Fallback Handling</strong>
                                <p>AI takes over when no pattern matches</p>
                            </div>
                        </div>
                        <div class="revx-feature-item">
                            <span class="revx-feature-icon">🏨</span>
                            <div>
                                <strong>HAPN Context</strong>
                                <p>AI responses tailored to HAPN and Pokhara</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>