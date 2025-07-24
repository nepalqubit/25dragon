<?php
/**
 * Update Response Handler for RevX Chatbot
 * 
 * This file contains the AJAX handler for updating responses in the chatbot.
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Check if WordPress functions are available
if (!function_exists('check_ajax_referer') || !function_exists('current_user_can') || 
    !function_exists('wp_send_json_error') || !function_exists('sanitize_text_field') || 
    !function_exists('wp_send_json_success')) {
    return;
}

/**
 * AJAX handler for updating responses
 */
function revx_chatbot_update_response() {
    // Check nonce for security
    if (function_exists('check_ajax_referer')) {
        check_ajax_referer('revx_chatbot_nonce', 'nonce');
    }
    
    // Check if user has permission to update responses
    if (function_exists('current_user_can') && !current_user_can('manage_options')) {
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'You do not have permission to update responses.'));
        }
        return;
    }
    
    // Get and sanitize form fields
    $category = isset($_POST['category']) ? (function_exists('sanitize_text_field') ? sanitize_text_field($_POST['category']) : strip_tags($_POST['category'])) : '';
    $index = isset($_POST['index']) ? intval($_POST['index']) : -1;
    $pattern_id = isset($_POST['pattern_id']) && $_POST['pattern_id'] !== '' ? intval($_POST['pattern_id']) : null;
    $response_id = isset($_POST['response_id']) && $_POST['response_id'] !== '' ? intval($_POST['response_id']) : null;
    $text = isset($_POST['text']) ? (function_exists('sanitize_text_field') ? sanitize_text_field($_POST['text']) : strip_tags($_POST['text'])) : '';
    
    if (empty($category) || $index < 0 || empty($text)) {
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'Invalid parameters provided.'));
        }
        return;
    }
    
    $json_file = REVX_CHATBOT_PATH . 'data/responses.json';
    
    // Check if file exists and is readable/writable
    if (!file_exists($json_file) || !is_readable($json_file) || !is_writable($json_file)) {
        error_log('RevX Chatbot: Responses file not found or not accessible');
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'Could not access the responses file.'));
        }
        return;
    }
    
    $json_content = file_get_contents($json_file);
    if ($json_content === false) {
        error_log('RevX Chatbot: Failed to read responses file');
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'Could not read the responses file.'));
        }
        return;
    }
    
    $data = json_decode($json_content, true);
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        error_log('RevX Chatbot: Failed to parse responses JSON: ' . json_last_error_msg());
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'Could not parse the responses file.'));
        }
        return;
    }
    
    // Update the appropriate response based on category
    if ($category === 'greetings' || $category === 'farewell' || $category === 'fallback') {
        // Simple categories with direct array access
        if (isset($data[$category][$index])) {
            $data[$category][$index] = $text;
        } else {
            if (function_exists('wp_send_json_error')) {
                wp_send_json_error(array('message' => 'Response not found.'));
            }
            return;
        }
    } elseif ($category === 'responses') {
        // Custom responses with patterns and responses arrays
        if (isset($data[$category][$index])) {
            if ($pattern_id !== null && isset($data[$category][$index]['patterns'][$pattern_id])) {
                $data[$category][$index]['patterns'][$pattern_id] = $text;
            } elseif ($response_id !== null && isset($data[$category][$index]['responses'][$response_id])) {
                $data[$category][$index]['responses'][$response_id] = $text;
            } else {
                if (function_exists('wp_send_json_error')) {
                    wp_send_json_error(array('message' => 'Pattern or response not found.'));
                }
                return;
            }
        } else {
            if (function_exists('wp_send_json_error')) {
                wp_send_json_error(array('message' => 'Response set not found.'));
            }
            return;
        }
    } else {
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'Invalid category.'));
        }
        return;
    }
    
    // Save the updated data
    $json_content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json_content === false) {
        error_log('RevX Chatbot: Failed to encode data to JSON');
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'Failed to encode the responses data.'));
        }
        return;
    }
    
    $result = file_put_contents($json_file, $json_content);
    if ($result === false) {
        error_log('RevX Chatbot: Failed to write to responses file');
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'Failed to save the responses data.'));
        }
        return;
    }
    
    if (function_exists('wp_send_json_success')) {
        wp_send_json_success(array('message' => 'Response updated successfully!'));
    }
}

// Add AJAX action only if WordPress function is available
if (function_exists('add_action')) {
    add_action('wp_ajax_revx_chatbot_update_response', 'revx_chatbot_update_response');
}