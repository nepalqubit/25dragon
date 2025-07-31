<?php
// Check if WordPress functions are available
if (!function_exists('esc_attr') || !function_exists('esc_html')) {
    return;
}

// Define fallback functions for safety
if (!function_exists('esc_attr')) {
    function esc_attr($text) { return htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('esc_html')) {
    function esc_html($text) { return htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8'); }
}

// Apply custom settings using null coalescing operator (PHP 7.0+)
$title = $chatbot_settings['title'] ?? 'RevX Chatbot';
$theme_color = $chatbot_settings['theme_color'] ?? '#4a6cf7';
$initial_message = $chatbot_settings['initial_message'] ?? 'Hi there! How can I help you today?';
?>
<div class="revx-chatbot-container">
    <div class="revx-chatbot-icon" style="background-color: <?php echo esc_attr($theme_color); ?>">
        <div class="revx-chatbot-avatar">
            <div class="revx-chatbot-avatar-inner" style="background-color: <?php echo esc_attr($theme_color); ?>"></div>
        </div>
    </div>
    <div class="revx-chatbot-box">
        <div class="revx-chatbot-header" style="background-color: <?php echo esc_attr($theme_color); ?>">
            <h3><?php echo esc_html($title); ?></h3>
            <div class="revx-chatbot-close">&times;</div>
        </div>
        <div class="revx-chatbot-messages">
            <div class="revx-chatbot-message revx-chatbot-message-bot">
                <div class="revx-chatbot-message-content"><?php echo esc_html($initial_message); ?></div>
            </div>
        </div>
        <div class="revx-chatbot-input-container">
            <input type="text" class="revx-chatbot-input" placeholder="Type your message here...">
            <button class="revx-chatbot-send" style="background-color: <?php echo esc_attr($theme_color); ?>">Send</button>
        </div>
        <div class="revx-chatbot-powered-by">Powered by RevX Chatbot</div>
    </div>
</div>