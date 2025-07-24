/**
 * RevX Chatbot Frontend JavaScript
 */

jQuery(document).ready(function($) {
    // DOM elements
    const chatbotContainer = $('.revx-chatbot-container');
    const chatbotIcon = $('.revx-chatbot-icon');
    const chatbotBox = $('.revx-chatbot-box');
    const chatbotClose = $('.revx-chatbot-close');
    const chatbotMessages = $('.revx-chatbot-messages');
    const chatbotInput = $('.revx-chatbot-input');
    const chatbotSend = $('.revx-chatbot-send');
    
    // Toggle chatbot box
    chatbotIcon.on('click', function() {
        chatbotBox.toggleClass('active');
        
        // Show initial greeting if no messages exist
        if (chatbotMessages.children().length === 0) {
            showInitialGreeting();
        }
    });
    
    // Close chatbot box
    chatbotClose.on('click', function() {
        chatbotBox.removeClass('active');
    });
    
    // Send message on button click
    chatbotSend.on('click', function() {
        sendMessage();
    });
    
    // Send message on Enter key press
    chatbotInput.on('keypress', function(e) {
        if (e.which === 13) {
            sendMessage();
        }
    });
    
    // Function to send message
    function sendMessage() {
        const message = chatbotInput.val().trim();
        
        if (message === '') {
            return;
        }
        
        // Add user message to chat
        addMessage(message, 'user');
        
        // Clear input
        chatbotInput.val('');
        
        // Get user's timezone information
        const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        const currentHour = new Date().getHours();
        
        // Get bot response via AJAX
        $.ajax({
            url: revxChatbot.ajaxurl,
            type: 'POST',
            data: {
                action: 'revx_chatbot_get_response',
                nonce: revxChatbot.nonce,
                message: message,
                user_timezone: userTimezone,
                user_hour: currentHour
            },
            success: function(response) {
                if (response.success) {
                    // Add bot response to chat
                    addMessage(response.data.response, 'bot');
                } else {
                    // Add error message
                    addMessage('Sorry, something went wrong. Please try again.', 'bot');
                }
            },
            error: function() {
                // Add error message
                addMessage('Sorry, there was an error communicating with the server.', 'bot');
            }
        });
    }
    
    // Function to add message to chat
    function addMessage(message, sender) {
        const messageClass = sender === 'user' ? 'revx-chatbot-message-user' : 'revx-chatbot-message-bot';
        let messageHTML = '';
        
        if (sender === 'user') {
            messageHTML = `
                <div class="revx-chatbot-message ${messageClass}" style="background-color: ${revxChatbot.themeColor}">
                    <div class="revx-chatbot-message-content">${message}</div>
                </div>
            `;
        } else {
            messageHTML = `
                <div class="revx-chatbot-message ${messageClass}">
                    <div class="revx-chatbot-message-content">${message}</div>
                </div>
            `;
        }
        
        chatbotMessages.append(messageHTML);
        
        // Scroll to bottom
        chatbotMessages.scrollTop(chatbotMessages[0].scrollHeight);
    }
    
    // Function to show initial time-based greeting
    function showInitialGreeting() {
        // Get user's timezone information
        const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        const currentHour = new Date().getHours();
        
        // Get initial greeting via AJAX
        $.ajax({
            url: revxChatbot.ajaxurl,
            type: 'POST',
            data: {
                action: 'revx_chatbot_get_response',
                nonce: revxChatbot.nonce,
                message: 'hello',
                user_timezone: userTimezone,
                user_hour: currentHour
            },
            success: function(response) {
                if (response.success) {
                    // Add bot greeting to chat
                    addMessage(response.data.response, 'bot');
                } else {
                    // Add default greeting
                    addMessage('Hello! How can I help you today?', 'bot');
                }
            },
            error: function() {
                // Add default greeting
                addMessage('Hello! How can I help you today?', 'bot');
            }
        });
    }
});