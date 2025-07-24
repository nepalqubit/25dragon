<?php
/**
 * OpenRouter API Integration for RevX Chatbot
 * 
 * This file contains functions to process user queries through OpenRouter API
 * to better understand complex phrases before matching them with local data.
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Check if WordPress functions are available
if (!function_exists('get_option') || !function_exists('wp_remote_post') || 
    !function_exists('site_url') || !function_exists('is_wp_error') || 
    !function_exists('wp_remote_retrieve_response_code') || !function_exists('wp_remote_retrieve_body')) {
    return;
}

/**
 * Process a user message through OpenRouter API to better understand the intent
 * 
 * @param string $message The user's message
 * @return array An array containing the processed message and any extracted entities
 */
function revx_chatbot_process_with_openrouter($message) {
    // OpenRouter API endpoint
    $api_url = 'https://openrouter.ai/api/v1/chat/completions';
    
    // Get API key from settings
    $api_key = function_exists('get_option') ? get_option('revx_chatbot_openrouter_api_key', '') : '';
    
    // If no API key is set, return the original message
    if (empty($api_key)) {
        error_log('RevX Chatbot: OpenRouter API key not configured');
        return array(
            'processed_message' => $message,
            'entities' => array(),
            'success' => false
        );
    }
    
    // Prepare the request body
    $request_body = array(
        'model' => function_exists('get_option') ? get_option('revx_chatbot_openrouter_model', 'meta-llama/llama-3.1-8b-instruct:free') : 'meta-llama/llama-3.1-8b-instruct:free', // Default to a free model
        'messages' => array(
            array(
                'role' => 'system',
                'content' => 'You are an assistant that helps understand user queries. Extract the main intent and any entities from the following message. DO NOT generate a response, only analyze the query.'
            ),
            array(
                'role' => 'user',
                'content' => $message
            )
        ),
        'max_tokens' => 150
    );
    
    // Make the API request
    if (!function_exists('wp_remote_post') || !function_exists('site_url')) {
        return array(
            'processed_message' => $message,
            'entities' => array(),
            'success' => false
        );
    }
    
    $response = wp_remote_post($api_url, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
            'HTTP-Referer' => site_url(), // Required by OpenRouter
            'X-Title' => 'RevX Chatbot' // Identify your application
        ),
        'body' => json_encode($request_body),
        'timeout' => 30
    ));
    
    // Check for errors
    if (!function_exists('is_wp_error') || is_wp_error($response)) {
        if (function_exists('is_wp_error') && is_wp_error($response)) {
            error_log('RevX Chatbot: OpenRouter API request failed: ' . $response->get_error_message());
        }
        return array(
            'processed_message' => $message,
            'entities' => array(),
            'success' => false
        );
    }
    
    // Parse the response
    if (!function_exists('wp_remote_retrieve_response_code') || !function_exists('wp_remote_retrieve_body')) {
        return array(
            'processed_message' => $message,
            'entities' => array(),
            'success' => false
        );
    }
    
    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);
    
    if ($response_code !== 200) {
        error_log('RevX Chatbot: OpenRouter API returned error code: ' . $response_code);
        return array(
            'processed_message' => $message,
            'entities' => array(),
            'success' => false
        );
    }
    
    $data = json_decode($response_body, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('RevX Chatbot: Failed to parse OpenRouter API response: ' . json_last_error_msg());
        return array(
            'processed_message' => $message,
            'entities' => array(),
            'success' => false
        );
    }
    
    // Extract the processed message from the API response
    $processed_message = $message; // Default to original message
    $entities = array();
    
    if (isset($data['choices'][0]['message']['content'])) {
        $ai_analysis = $data['choices'][0]['message']['content'];
        
        // Parse the AI analysis to extract intent and entities
        // This is a simple implementation - you may want to enhance this based on your needs
        $processed_message = $ai_analysis;
        
        // Extract potential keywords from the analysis
        $keywords = extract_keywords_from_analysis($ai_analysis);
        if (!empty($keywords)) {
            $entities = $keywords;
        }
    }
    
    return array(
        'processed_message' => $processed_message,
        'entities' => $entities,
        'success' => true
    );
}

/**
 * Extract keywords from the AI analysis
 * 
 * @param string $analysis The AI analysis text
 * @return array An array of extracted keywords
 */
function extract_keywords_from_analysis($analysis) {
    $keywords = array();
    
    // Look for common patterns in the AI's analysis
    if (preg_match('/intent[s]?:?\s*([^\n\.]+)/i', $analysis, $matches)) {
        $intent = trim($matches[1]);
        $keywords[] = $intent;
        
        // Further split the intent into individual words
        $intent_words = preg_split('/\s+/', $intent);
        foreach ($intent_words as $word) {
            if (strlen($word) > 3 && !in_array($word, $keywords)) { // Only add words longer than 3 chars
                $keywords[] = $word;
            }
        }
    }
    
    // Look for entities or keywords sections
    if (preg_match('/(?:entities|keywords|key terms|topics)\s*:?\s*([^\n\.]+)/i', $analysis, $matches)) {
        $entities_text = trim($matches[1]);
        
        // Split by common separators
        $entity_list = preg_split('/[,;|]/', $entities_text);
        foreach ($entity_list as $entity) {
            $entity = trim($entity);
            if (!empty($entity) && !in_array($entity, $keywords)) {
                $keywords[] = $entity;
            }
        }
    }
    
    return $keywords;
}