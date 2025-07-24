<?php
/**
 * Plugin Name: RevX Chatbot
 * Plugin URI: https://revx.pro/revx-chatbot
 * Description: A standalone, JSON-based chatbot for WordPress that appears in the bottom right corner.
 * Version: 1.0.1
 * Author: RevX
 * Author URI: https://revx.pro
 * Text Domain: revx-chatbot
 * License: GPL-2.0+
 * Requires at least: 5.0
 * Requires PHP: 8.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Ensure WordPress functions are available
if (!function_exists('plugin_dir_path') || !function_exists('plugin_dir_url') || !function_exists('plugin_basename')) {
    return;
}

// Define plugin constants
define('REVX_CHATBOT_VERSION', '1.0.1');
define('REVX_CHATBOT_PATH', plugin_dir_path(__FILE__));
define('REVX_CHATBOT_URL', plugin_dir_url(__FILE__));
define('REVX_CHATBOT_BASENAME', plugin_basename(__FILE__));

// Include OpenRouter API integration
require_once REVX_CHATBOT_PATH . 'includes/openrouter-api.php';

// Include Update Response handler
require_once REVX_CHATBOT_PATH . 'includes/update-response.php';

/**
 * The code that runs during plugin activation.
 */
function activate_revx_chatbot() {
    // Check if WordPress functions are available
    if (!function_exists('wp_mkdir_p')) {
        return;
    }
    
    // Create the responses JSON file if it doesn't exist
    $json_dir = REVX_CHATBOT_PATH . 'data/';
    if (!file_exists($json_dir)) {
        if (!wp_mkdir_p($json_dir)) {
            // Use WordPress function for better compatibility
            error_log('RevX Chatbot: Failed to create data directory');
            return;
        }
    }
    
    $json_file = $json_dir . 'responses.json';
    if (!file_exists($json_file)) {
        $default_data = [
            'greetings' => [
                'Hello!',
                'Hi there!',
                'Welcome! How can I help you today?',
                'Greetings! How may I assist you?'
            ],
            'farewell' => [
                'Goodbye!',
                'See you later!',
                'Have a great day!',
                'Thanks for chatting!'
            ],
            'fallback' => [
                'I\'m not sure I understand. Could you rephrase that?',
                'I don\'t have an answer for that yet. Would you like to train me?',
                'I\'m still learning. Can you help me understand what you\'re asking?'
            ],
            'responses' => [
                [
                    'patterns' => ['help', 'support', 'assistance'],
                    'responses' => ['How can I help you today?', 'I\'m here to assist you. What do you need?']
                ],
                [
                    'patterns' => ['product', 'pricing', 'cost'],
                    'responses' => ['Our products start at $19.99. Would you like more information?', 'We offer various pricing options. What are you looking for specifically?']
                ],
                [
                    'patterns' => ['contact', 'email', 'phone'],
                    'responses' => ['You can reach us at contact@example.com or call us at (555) 123-4567.', 'Our support team is available at contact@example.com.']
                ]
            ]
        ];
        $json_content = json_encode($default_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json_content === false) {
            error_log('RevX Chatbot: Failed to encode default data to JSON');
            return;
        }
        
        $result = file_put_contents($json_file, $json_content);
        if ($result === false) {
            error_log('RevX Chatbot: Failed to write default data to JSON file');
        }
    }
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_revx_chatbot() {
    // Deactivation tasks if needed
}

// Register hooks only if WordPress functions are available
if (function_exists('register_activation_hook')) {
    register_activation_hook(__FILE__, 'activate_revx_chatbot');
}
if (function_exists('register_deactivation_hook')) {
    register_deactivation_hook(__FILE__, 'deactivate_revx_chatbot');
}

/**
 * Enqueue scripts and styles for the chatbot
 */
function revx_chatbot_enqueue_scripts() {
    // Check if WordPress functions are available
    if (!function_exists('wp_enqueue_style') || !function_exists('wp_enqueue_script') || 
        !function_exists('get_option') || !function_exists('wp_localize_script') || 
        !function_exists('admin_url') || !function_exists('wp_create_nonce')) {
        return;
    }
    
    // Enqueue the main CSS file
    if (function_exists('wp_enqueue_style')) {
        wp_enqueue_style('revx-chatbot-style', REVX_CHATBOT_URL . 'assets/css/revx-chatbot.css', array(), REVX_CHATBOT_VERSION);
    }
    
    // Enqueue the main JavaScript file
    if (function_exists('wp_enqueue_script')) {
        wp_enqueue_script('revx-chatbot-script', REVX_CHATBOT_URL . 'assets/js/revx-chatbot.js', array('jquery'), REVX_CHATBOT_VERSION, true);
    }
    
    // Get theme color from options
    $theme_color = function_exists('get_option') ? get_option('revx_chatbot_theme_color', '#4a6cf7') : '#4a6cf7';
    
    // Localize the script with necessary data
    if (function_exists('wp_localize_script') && function_exists('admin_url') && function_exists('wp_create_nonce')) {
        wp_localize_script('revx-chatbot-script', 'revxChatbot', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('revx_chatbot_nonce'),
            'themeColor' => $theme_color,
        ));
    }
}

// Add action only if WordPress function is available
if (function_exists('add_action')) {
    add_action('wp_enqueue_scripts', 'revx_chatbot_enqueue_scripts');
}

/**
 * Add the chatbot HTML to the footer
 */
function revx_chatbot_add_to_footer() {
    // Check if WordPress functions are available
    if (!function_exists('get_option')) {
        return;
    }
    
    // Get custom settings or use defaults
    $title = function_exists('get_option') ? get_option('revx_chatbot_title', 'RevX Chatbot') : 'RevX Chatbot';
    $theme_color = function_exists('get_option') ? get_option('revx_chatbot_theme_color', '#4a6cf7') : '#4a6cf7';
    $initial_message = function_exists('get_option') ? get_option('revx_chatbot_initial_message', 'Hi there! How can I help you today?') : 'Hi there! How can I help you today?';
    
    // Pass settings to template
    $chatbot_settings = array(
        'title' => $title,
        'theme_color' => $theme_color,
        'initial_message' => $initial_message
    );
    
    include_once REVX_CHATBOT_PATH . 'templates/chatbot.php';
}

// Add action only if WordPress function is available
if (function_exists('add_action')) {
    add_action('wp_footer', 'revx_chatbot_add_to_footer');
}

/**
 * AJAX handler for getting chatbot responses
 */
function revx_chatbot_get_response() {
    // Check if WordPress functions are available
    if (!function_exists('check_ajax_referer') || !function_exists('sanitize_text_field') || 
        !function_exists('wp_send_json_success')) {
        return;
    }
    
    // Check nonce for security
    if (function_exists('check_ajax_referer')) {
        check_ajax_referer('revx_chatbot_nonce', 'nonce');
    }
    
    $message = function_exists('sanitize_text_field') ? sanitize_text_field($_POST['message']) : strip_tags($_POST['message']);
    $user_timezone = isset($_POST['user_timezone']) ? (function_exists('sanitize_text_field') ? sanitize_text_field($_POST['user_timezone']) : strip_tags($_POST['user_timezone'])) : null;
    $user_hour = isset($_POST['user_hour']) ? (int) $_POST['user_hour'] : null;
    
    $response = revx_chatbot_process_message($message, $user_hour);
    
    if (function_exists('wp_send_json_success')) {
        wp_send_json_success(array('response' => $response));
    }
}

// Add actions only if WordPress function is available
if (function_exists('add_action')) {
    add_action('wp_ajax_revx_chatbot_get_response', 'revx_chatbot_get_response');
    add_action('wp_ajax_nopriv_revx_chatbot_get_response', 'revx_chatbot_get_response');
}

/**
 * Get time-based greeting based on user's local time
 */
function revx_chatbot_get_time_based_greeting($data, $user_hour = null) {
    // Use user's local hour if provided, otherwise fall back to server time
    if ($user_hour !== null && is_numeric($user_hour)) {
        $current_hour = (int) $user_hour;
    } else {
        // Check if WordPress function is available
        if (function_exists('current_time')) {
            $current_hour = (int) current_time('H');
        } else {
            $current_hour = (int) date('H');
        }
    }
    
    // Determine time of day
    $time_period = 'morning'; // default
    if ($current_hour >= 5 && $current_hour < 12) {
        $time_period = 'morning';
    } elseif ($current_hour >= 12 && $current_hour < 17) {
        $time_period = 'afternoon';
    } elseif ($current_hour >= 17 && $current_hour < 22) {
        $time_period = 'evening';
    } else {
        $time_period = 'night';
    }
    
    // Check if time-based greetings exist in data
    if (isset($data['time_greetings'][$time_period]) && !empty($data['time_greetings'][$time_period])) {
        return $data['time_greetings'][$time_period][array_rand($data['time_greetings'][$time_period])];
    }
    
    // Fallback to regular greetings if time-based ones don't exist
    if (isset($data['greetings']) && !empty($data['greetings'])) {
        return $data['greetings'][array_rand($data['greetings'])];
    }
    
    // Ultimate fallback
    return 'Hello! How can I help you today?';
}

/**
 * Calculate match score between user message and pattern
 */
function revx_chatbot_calculate_match_score($user_message, $pattern) {
    // Enhanced text normalization with better preprocessing
    $user_text = strtolower(trim($user_message));
    $pattern_text = strtolower(trim($pattern));
    
    // Remove extra spaces and normalize
    $user_text = preg_replace('/\s+/', ' ', $user_text);
    $pattern_text = preg_replace('/\s+/', ' ', $pattern_text);
    
    // Handle common contractions and abbreviations
    $contractions = array(
        "won't" => "will not", "can't" => "cannot", "n't" => " not",
        "'re" => " are", "'ve" => " have", "'ll" => " will",
        "'d" => " would", "'m" => " am", "'s" => " is",
        "gonna" => "going to", "wanna" => "want to", "gotta" => "got to",
        "lemme" => "let me", "gimme" => "give me", "dunno" => "do not know",
        "kinda" => "kind of", "sorta" => "sort of", "lotta" => "lot of",
        "outta" => "out of", "goin'" => "going", "doin'" => "doing",
        "comin'" => "coming", "lookin'" => "looking", "thinkin'" => "thinking"
    );
    
    foreach ($contractions as $contraction => $expansion) {
        $user_text = str_replace($contraction, $expansion, $user_text);
        $pattern_text = str_replace($contraction, $expansion, $pattern_text);
    }
    
    $user_words = explode(' ', $user_text);
    $pattern_words = explode(' ', $pattern_text);
    
    // Clean and normalize text
    $user_clean = preg_replace('/[^a-z0-9\s]/', '', $user_text);
    $pattern_clean = preg_replace('/[^a-z0-9\s]/', '', $pattern_text);
    
    // Direct phrase matching
    if (strpos($user_clean, $pattern_clean) !== false) {
        return 100;
    }
    
    // Reverse phrase matching
    if (strpos($pattern_clean, $user_clean) !== false) {
        return 95;
    }
    
    // Fuzzy matching using similar_text
    $similarity = 0;
    similar_text($user_clean, $pattern_clean, $similarity);
    if ($similarity >= 70) {
        return $similarity;
    }
    
    // Advanced word matching with synonyms and variations
    $matches = 0;
    $total_words = max(count($user_words), count($pattern_words));
    
    // Enhanced synonym mapping for better word matching
    $synonyms = array(
        'hi' => array('hello', 'hey', 'greetings', 'howdy', 'sup', 'yo', 'hiya', 'heya', 'wassup', 'whats up'),
        'bye' => array('goodbye', 'farewell', 'see you', 'later', 'ciao', 'ttyl', 'talk to you later', 'catch you later', 'peace'),
        'good' => array('great', 'excellent', 'awesome', 'wonderful', 'amazing', 'fantastic', 'brilliant', 'superb', 'outstanding', 'marvelous', 'terrific', 'fabulous', 'cool', 'nice', 'sweet', 'dope', 'lit', 'fire'),
        'bad' => array('terrible', 'awful', 'horrible', 'poor', 'disappointing', 'sucks', 'lame', 'trash', 'garbage', 'worst', 'crappy'),
        'help' => array('assist', 'support', 'aid', 'guidance', 'advice', 'hand', 'backup', 'assistance'),
        'thanks' => array('thank you', 'appreciate', 'grateful', 'cheers', 'thx', 'ty', 'much appreciated', 'thanks a lot'),
        'yes' => array('yeah', 'yep', 'sure', 'absolutely', 'definitely', 'ok', 'okay', 'yup', 'uh huh', 'for sure', 'bet', 'facts', 'true'),
        'no' => array('nope', 'nah', 'never', 'not really', 'negative', 'nuh uh', 'no way', 'not at all'),
        'how' => array('wat', 'wot', 'howz', 'hows'),
        'you' => array('u', 'ya', 'yall', 'you all'),
        'are' => array('r', 'ur'),
        'your' => array('ur', 'yer'),
        'what' => array('wat', 'wot', 'whats', 'whatcha'),
        'going' => array('goin', 'gonna'),
        'want' => array('wanna', 'wanting'),
        'because' => array('cuz', 'cos', 'bc', 'bcuz'),
        'about' => array('bout', 'abt'),
        'something' => array('somethin', 'sumthin', 'sth'),
        'nothing' => array('nothin', 'nuthin', 'nada'),
        'probably' => array('prob', 'prolly'),
        'definitely' => array('def', 'defo'),
        'awesome' => array('awsome', 'awsum'),
        'cool' => array('kool', 'kewl'),
        'okay' => array('ok', 'k', 'kk', 'alright', 'aight'),
        'love' => array('luv', 'lurve', 'adore'),
        'like' => array('lyk', 'lik'),
        'time' => array('tym', 'tyme'),
        'people' => array('ppl', 'peeps', 'folks'),
        'money' => array('cash', 'dough', 'bucks', 'bread'),
        'food' => array('grub', 'eats', 'chow'),
        'work' => array('job', 'gig', 'hustle'),
        'house' => array('home', 'place', 'crib', 'pad'),
        'car' => array('ride', 'whip', 'vehicle'),
        'phone' => array('cell', 'mobile', 'smartphone'),
        'computer' => array('pc', 'laptop', 'desktop', 'machine'),
        'internet' => array('web', 'net', 'online', 'wifi'),
        'friend' => array('buddy', 'pal', 'mate', 'bro', 'dude', 'homie'),
        'girl' => array('gal', 'chick', 'lady', 'woman'),
        'boy' => array('guy', 'dude', 'man', 'bro'),
        'happy' => array('glad', 'joyful', 'cheerful', 'pleased', 'content'),
        'sad' => array('upset', 'down', 'blue', 'depressed', 'bummed'),
        'angry' => array('mad', 'pissed', 'furious', 'annoyed', 'irritated'),
        'tired' => array('exhausted', 'beat', 'worn out', 'sleepy', 'drained'),
        'hungry' => array('starving', 'famished', 'peckish'),
        'thirsty' => array('parched', 'dehydrated'),
        'sick' => array('ill', 'unwell', 'under the weather', 'not feeling good'),
        'busy' => array('swamped', 'tied up', 'occupied', 'loaded'),
        'free' => array('available', 'open', 'not busy'),
        'funny' => array('hilarious', 'amusing', 'comical', 'witty'),
        'boring' => array('dull', 'tedious', 'uninteresting', 'bland'),
        'crazy' => array('insane', 'wild', 'nuts', 'mad', 'bonkers'),
        'smart' => array('intelligent', 'clever', 'bright', 'brilliant'),
        'stupid' => array('dumb', 'foolish', 'idiotic', 'moronic'),
        'big' => array('large', 'huge', 'enormous', 'massive', 'giant'),
        'small' => array('little', 'tiny', 'mini', 'petite'),
        'fast' => array('quick', 'speedy', 'rapid', 'swift'),
        'slow' => array('sluggish', 'gradual', 'leisurely'),
        'easy' => array('simple', 'effortless', 'straightforward'),
        'hard' => array('difficult', 'challenging', 'tough', 'complex'),
        'new' => array('fresh', 'recent', 'latest', 'modern'),
        'old' => array('ancient', 'vintage', 'aged', 'classic'),
        'nice' => array('pleasant', 'lovely', 'beautiful', 'attractive'),
        'place' => array('location', 'spot', 'area', 'venue'),
        'person' => array('individual', 'people', 'human', 'someone'),
        'thing' => array('item', 'object', 'stuff', 'matter'),
        'way' => array('method', 'approach', 'manner', 'technique'),
        'day' => array('date', 'today', 'daily', 'everyday'),
        'year' => array('annual', 'yearly', 'period', 'season'),
        'world' => array('global', 'earth', 'planet', 'universe'),
        'life' => array('living', 'existence', 'lifestyle', 'experience'),
        'home' => array('house', 'residence', 'dwelling', 'place'),
        'family' => array('relatives', 'household', 'kin', 'clan'),
        'hate' => array('dislike', 'despise', 'loathe', 'detest'),
        'need' => array('require', 'desire', 'wish'),
        'enjoy' => array('prefer', 'fancy', 'appreciate'),
        'know' => array('understand', 'realize', 'recognize', 'aware'),
        'think' => array('believe', 'consider', 'suppose', 'imagine'),
        'feel' => array('sense', 'experience', 'emotion', 'perceive'),
        'look' => array('see', 'view', 'observe', 'watch'),
        'come' => array('arrive', 'approach', 'reach', 'visit'),
        'go' => array('leave', 'depart', 'travel', 'move'),
        'get' => array('obtain', 'acquire', 'receive', 'gain'),
        'give' => array('provide', 'offer', 'supply', 'deliver'),
        'take' => array('grab', 'pick', 'choose', 'select'),
        'make' => array('create', 'build', 'produce', 'generate'),
        'use' => array('utilize', 'employ', 'apply', 'operate'),
        'find' => array('discover', 'locate', 'search', 'identify'),
        'tell' => array('inform', 'explain', 'describe', 'communicate'),
        'ask' => array('question', 'inquire', 'request', 'query'),
        'try' => array('attempt', 'effort', 'endeavor', 'test'),
        'call' => array('phone', 'contact', 'reach', 'communicate'),
        'turn' => array('rotate', 'change', 'switch', 'convert'),
        'move' => array('shift', 'relocate', 'transfer', 'transport'),
        'play' => array('game', 'sport', 'entertainment', 'fun'),
        'run' => array('jog', 'sprint', 'operate', 'manage'),
        'walk' => array('stroll', 'hike', 'step', 'pace'),
        'talk' => array('speak', 'chat', 'converse', 'discuss'),
        'read' => array('study', 'review', 'examine', 'browse'),
        'write' => array('compose', 'author', 'draft', 'document'),
        'buy' => array('purchase', 'acquire', 'shop', 'invest'),
        'sell' => array('market', 'trade', 'offer', 'distribute'),
        'eat' => array('consume', 'dine', 'meal', 'food'),
        'drink' => array('beverage', 'liquid', 'consume', 'sip'),
        'sleep' => array('rest', 'nap', 'slumber', 'doze'),
        'wake' => array('awake', 'rise', 'alert', 'conscious'),
        'open' => array('unlock', 'access', 'available', 'start'),
        'close' => array('shut', 'end', 'finish', 'complete'),
        'start' => array('begin', 'initiate', 'launch', 'commence'),
        'stop' => array('halt', 'cease', 'end', 'terminate'),
        'win' => array('victory', 'succeed', 'triumph', 'achieve'),
        'lose' => array('defeat', 'fail', 'miss', 'forfeit'),
        'learn' => array('study', 'educate', 'train', 'develop'),
        'teach' => array('instruct', 'educate', 'guide', 'mentor'),
        'build' => array('construct', 'create', 'develop', 'establish'),
        'break' => array('damage', 'destroy', 'crack', 'split'),
        'fix' => array('repair', 'solve', 'correct', 'mend'),
        'change' => array('modify', 'alter', 'transform', 'adjust'),
        'keep' => array('maintain', 'preserve', 'retain', 'hold'),
        'let' => array('allow', 'permit', 'enable', 'authorize'),
        'put' => array('place', 'position', 'set', 'locate'),
        'end' => array('finish', 'complete', 'conclude', 'terminate'),
        'follow' => array('pursue', 'track', 'accompany', 'observe'),
        'act' => array('perform', 'behave', 'function', 'operate'),
        'show' => array('display', 'demonstrate', 'present', 'reveal'),
        'hear' => array('listen', 'sound', 'audio', 'perceive'),
        'leave' => array('depart', 'exit', 'abandon', 'quit'),
        'meet' => array('encounter', 'gather', 'assembly', 'conference'),
        'include' => array('contain', 'comprise', 'involve', 'encompass'),
        'continue' => array('proceed', 'persist', 'maintain', 'carry on'),
        'set' => array('establish', 'arrange', 'configure', 'determine'),
        'add' => array('include', 'append', 'insert', 'supplement'),
        'expect' => array('anticipate', 'predict', 'forecast', 'await'),
        'remember' => array('recall', 'recollect', 'memorize', 'retain'),
        'consider' => array('think', 'evaluate', 'contemplate', 'ponder'),
        'appear' => array('seem', 'look', 'emerge', 'surface'),
        'suggest' => array('recommend', 'propose', 'advise', 'hint'),
        'require' => array('need', 'demand', 'necessitate', 'mandate'),
        'allow' => array('permit', 'enable', 'authorize', 'approve'),
        'remain' => array('stay', 'continue', 'persist', 'endure'),
        'result' => array('outcome', 'consequence', 'effect', 'conclusion'),
        'become' => array('transform', 'evolve', 'develop', 'turn into'),
        'offer' => array('provide', 'present', 'propose', 'suggest'),
        'produce' => array('create', 'generate', 'manufacture', 'yield'),
        'decide' => array('choose', 'determine', 'resolve', 'conclude'),
        'reach' => array('achieve', 'attain', 'arrive', 'contact'),
        'explain' => array('clarify', 'describe', 'illustrate', 'detail'),
        'develop' => array('create', 'build', 'grow', 'evolve'),
        'carry' => array('transport', 'bear', 'convey', 'support'),
        'lead' => array('guide', 'direct', 'manage', 'head'),
        'understand' => array('comprehend', 'grasp', 'realize', 'appreciate'),
        'watch' => array('observe', 'monitor', 'view', 'supervise'),
        'provide' => array('supply', 'offer', 'deliver', 'furnish'),
        'serve' => array('assist', 'help', 'support', 'cater'),
        'send' => array('transmit', 'deliver', 'dispatch', 'forward'),
        'choose' => array('select', 'pick', 'decide', 'opt'),
        'support' => array('help', 'assist', 'back', 'encourage'),
        'report' => array('inform', 'notify', 'announce', 'communicate'),
        'hold' => array('grasp', 'contain', 'maintain', 'possess'),
        'return' => array('comeback', 'restore', 'give back', 'reply'),
        'receive' => array('get', 'obtain', 'accept', 'acquire'),
        'cut' => array('slice', 'reduce', 'trim', 'divide'),
        'kill' => array('eliminate', 'destroy', 'terminate', 'end'),
        'raise' => array('lift', 'increase', 'elevate', 'boost'),
        'pass' => array('succeed', 'approve', 'transfer', 'exceed'),
        'pull' => array('drag', 'draw', 'tug', 'attract'),
        'push' => array('shove', 'press', 'force', 'promote'),
        'drop' => array('fall', 'decrease', 'abandon', 'release'),
        'plan' => array('strategy', 'scheme', 'design', 'organize'),
        'attack' => array('assault', 'strike', 'offensive', 'criticize'),
        'defend' => array('protect', 'guard', 'shield', 'justify'),
        'grow' => array('expand', 'increase', 'develop', 'flourish'),
        'draw' => array('sketch', 'attract', 'pull', 'illustrate'),
        'die' => array('perish', 'expire', 'pass away', 'end'),
        'pick' => array('choose', 'select', 'gather', 'collect'),
        'save' => array('rescue', 'preserve', 'store', 'economize'),
        'fall' => array('drop', 'decline', 'tumble', 'decrease'),
        'catch' => array('capture', 'grab', 'seize', 'understand'),
        'throw' => array('toss', 'hurl', 'cast', 'pitch'),
        'seek' => array('search', 'look for', 'pursue', 'quest'),
        'join' => array('connect', 'unite', 'participate', 'combine'),
        'speak' => array('talk', 'communicate', 'express', 'articulate'),
        'spend' => array('use', 'invest', 'consume', 'allocate'),
        'pay' => array('compensate', 'remunerate', 'settle', 'reward'),
        'apply' => array('use', 'implement', 'request', 'utilize'),
        'wait' => array('pause', 'delay', 'hold', 'expect'),
        'stay' => array('remain', 'continue', 'reside', 'persist'),
        
        // HAPN and Organization-related terms
        'hapn' => array('hotel association pokhara', 'hotel association pokhara nepal', 'pokhara hotel association', 'association'),
        'hotel' => array('accommodation', 'lodging', 'resort', 'guesthouse', 'inn', 'motel', 'hospitality'),
        'association' => array('organization', 'society', 'union', 'federation', 'group', 'body', 'alliance'),
        'pokhara' => array('pokhra', 'pokara', 'lakeside'),
        'nepal' => array('nepali', 'nepalese'),
        'member' => array('members', 'membership', 'affiliate', 'participant', 'subscriber'),
        'contact' => array('reach', 'communicate', 'phone', 'email', 'address', 'location'),
        'mission' => array('vision', 'goal', 'objective', 'purpose', 'aim'),
        'service' => array('services', 'offering', 'benefit', 'support', 'assistance'),
        'industry' => array('sector', 'business', 'trade', 'hospitality sector'),
        'advocacy' => array('lobbying', 'representation', 'support', 'promotion'),
        'directory' => array('listing', 'list', 'catalog', 'database', 'registry'),
        'training' => array('education', 'seminar', 'workshop', 'course', 'development'),
        'benefit' => array('benefits', 'advantage', 'privilege', 'perk', 'feature'),
        'star' => array('rating', 'category', 'class', 'standard', 'grade'),
        'tourist' => array('tourism', 'travel', 'visitor', 'guest', 'traveler'),
        'lakeside' => array('lake side', 'phewa lake', 'fewa lake', 'waterfront'),
        'attraction' => array('attractions', 'place', 'destination', 'site', 'location'),
        'peace' => array('world peace', 'shanti'),
        'pagoda' => array('stupa', 'temple', 'shrine'),
        'sarangkot' => array('sunrise point', 'viewpoint', 'hill station'),
        'begnas' => array('begnas lake', 'lake'),
        'han' => array('hotel association nepal', 'hotel association of nepal'),
        'affiliation' => array('affiliated', 'connected', 'linked', 'associated'),
        'lobbying' => array('advocacy', 'representation', 'negotiation', 'influence'),
        'governmental' => array('government', 'official', 'public', 'state'),
        'collective' => array('joint', 'combined', 'unified', 'group'),
        'discount' => array('reduction', 'offer', 'deal', 'savings', 'concession'),
        'accommodation' => array('lodging', 'stay', 'room', 'boarding'),
        'beverage' => array('drink', 'refreshment', 'f&b', 'food and beverage'),
        
        // Specific HAPN Member Hotels
        'pokhara_grande' => array('pokhara grande', 'hotel pokhara grande', 'grande hotel', 'five star hotel', '5 star hotel', 'luxury hotel pokhara', 'kiriti tripathi'),
        'hotel_barahi' => array('hotel barahi', 'barahi hotel', 'barahi temple hotel', 'chanda kc', 'chanda k.c.', 'ms chanda', 'fewa lake hotel'),
        'landmark_pokhara' => array('hotel landmark', 'landmark pokhara', 'heritage hotel', 'cultural heritage hotel', 'environmental heritage'),
        'silver_oaks' => array('silver oaks inn', 'silver oaks', 'fewa lake inn', 'lakeside inn', 'peaceful retreat'),
        'fish_tail_lodge' => array('fish tail lodge', 'fishtail lodge', 'luxury fewa lake', 'private boat hotel', 'ropeway hotel'),
        'temple_tree' => array('temple tree resort', 'temple tree spa', 'lakeside resort', 'spa resort pokhara', 'mountain view resort'),
        'middle_path' => array('hotel middle path', 'middle path spa', 'budget hotel', 'cheap hotel lakeside', '8 dollar hotel', '$8 hotel'),
        'three_jewels' => array('three jewels boutique', 'three jewels hotel', 'pet friendly hotel', 'boutique hotel pokhara', 'pet hotel'),
        'pavilions_himalayas' => array('pavilions himalayas', 'pavilions farm', 'luxury lodge pokhara', 'mountain view lodge', 'jacuzzi hotel'),
        'trekkers_inn' => array('hotel trekkers inn', 'trekkers inn', 'budget mountain view', '6 dollar hotel', '$6 hotel', 'solo traveler hotel'),
        'crown_himalayas' => array('hotel crown himalayas', 'crown himalayas', 'airport hotel', 'near pokhara airport', 'layover hotel'),
        
        // Hotel Amenities and Features
        'hotel_amenities' => array('amenities', 'facilities', 'services', 'features', 'room service', 'wifi', 'breakfast', 'parking'),
        'hotel_booking' => array('booking', 'reservation', 'contact', 'phone number', 'email', 'availability', 'rates', 'prices'),
        'hotel_reviews' => array('reviews', 'ratings', 'guest feedback', 'tripadvisor', 'testimonials', 'recommendations'),
        'hotel_location' => array('location', 'address', 'nearby', 'distance', 'accessibility', 'transport', 'airport'),
        
        // Tourism Statistics and Events
        'tourism_occupancy' => array('occupancy', '90% occupancy', 'hotel occupancy', 'tourism surge', 'booking rates'),
        'fewa_festival' => array('fewa new year festival', 'cultural event', 'annual festival', 'tourism appeal'),
        'pokhara_attractions' => array('fewa lake', 'barahi temple', 'devi falls', 'world peace pagoda', 'annapurna trekking'),
        'hapn_contact' => array('join hapn', 'hapn membership', 'pokharahotels gmail', 'hotelspokhara.org', 'hapn phone')
    );
    
    foreach ($pattern_words as $pattern_word) {
        $best_match = 0;
        foreach ($user_words as $user_word) {
            // Exact match
            if ($pattern_word === $user_word) {
                $matches++;
                break;
            }
            
            // Synonym matching
            if (isset($synonyms[$pattern_word]) && in_array($user_word, $synonyms[$pattern_word])) {
                $matches += 0.9;
                break;
            }
            if (isset($synonyms[$user_word]) && in_array($pattern_word, $synonyms[$user_word])) {
                $matches += 0.9;
                break;
            }
            
            // Partial word matching for longer words
            if (strlen($pattern_word) > 3 && strlen($user_word) > 3) {
                $word_similarity = 0;
                similar_text($pattern_word, $user_word, $word_similarity);
                if ($word_similarity >= 75) {
                    $current_match = ($word_similarity / 100) * 0.8;
                    if ($current_match > $best_match) {
                        $best_match = $current_match;
                    }
                }
            }
            
            // Substring matching
            if (strlen($pattern_word) > 4 && strpos($user_word, $pattern_word) !== false) {
                $current_match = 0.7;
                if ($current_match > $best_match) {
                    $best_match = $current_match;
                }
            }
            if (strlen($user_word) > 4 && strpos($pattern_word, $user_word) !== false) {
                $current_match = 0.7;
                if ($current_match > $best_match) {
                    $best_match = $current_match;
                }
            }
        }
        $matches += $best_match;
    }
    
    // Bonus for length similarity
    $length_ratio = min(strlen($user_clean), strlen($pattern_clean)) / max(strlen($user_clean), strlen($pattern_clean));
    $length_bonus = $length_ratio * 10;
    
    $score = (($matches / $total_words) * 100) + $length_bonus;
    return min($score, 100);
}

/**
 * Process the user message and return a response
 */
function revx_chatbot_process_message($message, $user_hour = null) {
    $json_file = REVX_CHATBOT_PATH . 'data/responses.json';
    
    // Check if file exists and is readable
    if (!file_exists($json_file) || !is_readable($json_file)) {
        error_log('RevX Chatbot: Responses file not found or not readable');
        return 'Sorry, I\'m having trouble accessing my knowledge base.';
    }
    
    $json_content = file_get_contents($json_file);
    if ($json_content === false) {
        error_log('RevX Chatbot: Failed to read responses file');
        return 'Sorry, I\'m having trouble accessing my knowledge base.';
    }
    
    $data = json_decode($json_content, true);
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        error_log('RevX Chatbot: Failed to parse responses JSON: ' . json_last_error_msg());
        return 'Sorry, I\'m having trouble understanding my knowledge base.';
    }
    
    // Check if OpenRouter API is enabled
    $use_openrouter = function_exists('get_option') ? get_option('revx_chatbot_use_openrouter', false) : false;
    
    // Process message with OpenRouter API if enabled
    $processed_data = array(
        'processed_message' => $message,
        'entities' => array(),
        'success' => false
    );
    
    if ($use_openrouter && function_exists('revx_chatbot_process_with_openrouter')) {
        $processed_data = revx_chatbot_process_with_openrouter($message);
    }
    
    // Convert original message to lowercase for easier matching
    $message_lower = strtolower($message);
    $message_words = preg_split('/\s+/', $message_lower);
    
    // If OpenRouter processing was successful, also use the processed message and entities
    $additional_keywords = array();
    if ($processed_data['success']) {
        // Add extracted entities to our keywords for matching
        $additional_keywords = $processed_data['entities'];
    }
    
    // Check for greetings with time-based responses
    $greeting_patterns = ['hello', 'hi', 'hey', 'greetings', 'howdy', 'good morning', 'good afternoon', 'good evening', 'morning', 'afternoon', 'evening'];
    foreach ($greeting_patterns as $pattern) {
        if (strpos($message_lower, $pattern) !== false) {
            return revx_chatbot_get_time_based_greeting($data, $user_hour);
        }
    }
    
    // Check for specific time-based greetings
    if (preg_match('/\b(morning|afternoon|evening|night)\b/', $message_lower)) {
        return revx_chatbot_get_time_based_greeting($data, $user_hour);
    }
    
    // Check for farewells
    $farewell_patterns = ['bye', 'goodbye', 'see you', 'farewell'];
    foreach ($farewell_patterns as $pattern) {
        if (strpos($message_lower, $pattern) !== false) {
            return $data['farewell'][array_rand($data['farewell'])];
        }
    }
    
    // Enhanced pattern matching for specific responses using new scoring system
    $best_match = null;
    $best_match_score = 0;
    $best_response = null;
    
    foreach ($data['responses'] as $response_set) {
        foreach ($response_set['patterns'] as $pattern) {
            // Use the new advanced matching function
            $score = revx_chatbot_calculate_match_score($message, $pattern);
            
            // If we have a very high score (90+), return immediately
            if ($score >= 90) {
                return $response_set['responses'][array_rand($response_set['responses'])];
            }
            
            // Store the best match if it's above threshold
            if ($score > $best_match_score && $score >= 50) { // At least 50% match
                $best_match_score = $score;
                $best_match = $pattern;
                $best_response = $response_set['responses'][array_rand($response_set['responses'])];
            }
        }
    }
    
    // If we found a good partial match, use it
    if ($best_response !== null) {
        return $best_response;
    }
    
    // If no match found, return a fallback response
    return $data['fallback'][array_rand($data['fallback'])];
}

/**
 * AJAX handler for training the chatbot
 */
function revx_chatbot_train() {
    // Check if WordPress functions are available
    if (!function_exists('check_ajax_referer') || !function_exists('current_user_can') || 
        !function_exists('wp_send_json_error') || !function_exists('sanitize_text_field')) {
        return;
    }
    
    // Check nonce for security
    if (function_exists('check_ajax_referer')) {
        check_ajax_referer('revx_chatbot_nonce', 'nonce');
    }
    
    // Check if user has permission to train the chatbot
    if (function_exists('current_user_can') && !current_user_can('manage_options')) {
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'You do not have permission to train the chatbot.'));
        }
        return;
    }
    
    // Log the incoming request for debugging
    error_log('RevX Chatbot: Training request received');
    
    $pattern = isset($_POST['pattern']) ? (function_exists('sanitize_text_field') ? sanitize_text_field($_POST['pattern']) : strip_tags($_POST['pattern'])) : '';
    $response = isset($_POST['response']) ? (function_exists('sanitize_text_field') ? sanitize_text_field($_POST['response']) : strip_tags($_POST['response'])) : '';
    
    if (empty($pattern) || empty($response)) {
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'Pattern and response cannot be empty.'));
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
    
    // Check if pattern already exists
    $pattern_exists = false;
    foreach ($data['responses'] as &$response_set) {
        if (in_array($pattern, $response_set['patterns'])) {
            // Add the new response to existing pattern
            if (!in_array($response, $response_set['responses'])) {
                $response_set['responses'][] = $response;
                $pattern_exists = true;
            }
        }
    }
    unset($response_set); // Break the reference with the last element
    
    // If pattern doesn't exist, create a new entry
    if (!$pattern_exists) {
        $data['responses'][] = [
            'patterns' => [$pattern],
            'responses' => [$response]
        ];
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
    
    // Make sure we're sending a properly formatted JSON response
    header('Content-Type: application/json');
    if (function_exists('wp_send_json_success')) {
        wp_send_json_success(array('message' => 'Chatbot trained successfully!'));
    }
    exit; // Ensure no additional output
}

// Add action only if WordPress function is available
if (function_exists('add_action')) {
    add_action('wp_ajax_revx_chatbot_train', 'revx_chatbot_train');
}

/**
 * AJAX handler for getting all responses
 */
function revx_chatbot_get_all_responses() {
    // Check if WordPress functions are available
    if (!function_exists('check_ajax_referer') || !function_exists('current_user_can') || 
        !function_exists('wp_send_json_error') || !function_exists('sanitize_text_field') || 
        !function_exists('update_option') || !function_exists('wp_send_json_success')) {
        return;
    }
    
    // Check nonce for security
    if (function_exists('check_ajax_referer')) {
        check_ajax_referer('revx_chatbot_nonce', 'nonce');
    }
    
    // Check if user has permission to manage options
    if (function_exists('current_user_can') && !current_user_can('manage_options')) {
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'You do not have permission to access responses.'));
        }
        return;
    }
    
    $json_file = REVX_CHATBOT_PATH . 'data/responses.json';
    
    // Check if file exists and is readable
    if (!file_exists($json_file) || !is_readable($json_file)) {
        error_log('RevX Chatbot: Responses file not found or not readable');
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
    
    if (function_exists('wp_send_json_success')) {
        wp_send_json_success(array('responses' => $data));
    }
}

// Add action only if WordPress function is available
if (function_exists('add_action')) {
    add_action('wp_ajax_revx_chatbot_get_all_responses', 'revx_chatbot_get_all_responses');
}

/**
 * AJAX handler for saving chatbot settings
 */
function revx_chatbot_save_settings() {
    // Check if WordPress functions are available
    if (!function_exists('check_ajax_referer') || !function_exists('current_user_can') || 
        !function_exists('wp_send_json_error') || !function_exists('sanitize_text_field') || 
        !function_exists('update_option') || !function_exists('wp_send_json_success')) {
        return;
    }
    
    // Check nonce for security
    if (function_exists('check_ajax_referer')) {
        check_ajax_referer('revx_chatbot_nonce', 'nonce');
    }
    
    // Check if user has permission to save settings
    if (function_exists('current_user_can') && !current_user_can('manage_options')) {
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'You do not have permission to save settings.'));
        }
        return;
    }
    
    // Get and sanitize form fields
    $chatbot_name = isset($_POST['chatbot_name']) ? (function_exists('sanitize_text_field') ? sanitize_text_field($_POST['chatbot_name']) : strip_tags($_POST['chatbot_name'])) : 'RevX Assistant';
    $title = isset($_POST['title']) ? (function_exists('sanitize_text_field') ? sanitize_text_field($_POST['title']) : strip_tags($_POST['title'])) : '';
    $theme_color = isset($_POST['theme_color']) ? (function_exists('sanitize_text_field') ? sanitize_text_field($_POST['theme_color']) : strip_tags($_POST['theme_color'])) : '#4a6cf7';
    $initial_message = isset($_POST['initial_message']) ? (function_exists('sanitize_text_field') ? sanitize_text_field($_POST['initial_message']) : strip_tags($_POST['initial_message'])) : '';
    $ai_enhancement = isset($_POST['ai_enhancement']) ? (bool)$_POST['ai_enhancement'] : false;
    
    // Validate required fields
    if (empty($chatbot_name) || empty($title) || empty($initial_message)) {
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'Please fill in all required fields.'));
        }
        return;
    }
    
    // Validate field lengths
    if (strlen($chatbot_name) < 2 || strlen($chatbot_name) > 50) {
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'Chatbot name must be between 2 and 50 characters.'));
        }
        return;
    }
    
    if (strlen($title) < 3 || strlen($title) > 100) {
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'Title must be between 3 and 100 characters.'));
        }
        return;
    }
    
    if (strlen($initial_message) < 10 || strlen($initial_message) > 500) {
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'Welcome message must be between 10 and 500 characters.'));
        }
        return;
    }
    
    // Validate theme color format
    if (!preg_match('/^#[a-f0-9]{6}$/i', $theme_color)) {
        $theme_color = '#4a6cf7'; // Default if invalid
    }
    
    // Save settings to options table
    if (function_exists('update_option')) {
        update_option('revx_chatbot_name', $chatbot_name);
        update_option('revx_chatbot_title', $title);
        update_option('revx_chatbot_theme_color', $theme_color);
        update_option('revx_chatbot_initial_message', $initial_message);
        update_option('revx_chatbot_ai_enhancement', $ai_enhancement);
    }
    
    if (function_exists('wp_send_json_success')) {
        wp_send_json_success(array('message' => 'Settings saved successfully!'));
    }
}

// Add action only if WordPress function is available
if (function_exists('add_action')) {
    add_action('wp_ajax_revx_chatbot_save_settings', 'revx_chatbot_save_settings');
}

/**
 * AJAX handler for saving OpenRouter API settings
 */
function revx_chatbot_save_openrouter_settings() {
    // Check if WordPress functions are available
    if (!function_exists('check_ajax_referer') || !function_exists('current_user_can') || 
        !function_exists('wp_send_json_error') || !function_exists('sanitize_text_field') || 
        !function_exists('update_option') || !function_exists('wp_send_json_success')) {
        return;
    }
    
    // Check nonce for security
    if (function_exists('check_ajax_referer')) {
        check_ajax_referer('revx_chatbot_nonce', 'nonce');
    }
    
    // Check if user has permission to save settings
    if (function_exists('current_user_can') && !current_user_can('manage_options')) {
        if (function_exists('wp_send_json_error')) {
            wp_send_json_error(array('message' => 'You do not have permission to save settings.'));
        }
        return;
    }
    
    // Get and sanitize form fields
    $api_key = isset($_POST['openrouter_api_key']) ? (function_exists('sanitize_text_field') ? sanitize_text_field($_POST['openrouter_api_key']) : strip_tags($_POST['openrouter_api_key'])) : '';
    $model = isset($_POST['openrouter_model']) ? (function_exists('sanitize_text_field') ? sanitize_text_field($_POST['openrouter_model']) : strip_tags($_POST['openrouter_model'])) : 'openai/gpt-3.5-turbo';
    
    // Save settings to options table
    if (function_exists('update_option')) {
        update_option('revx_chatbot_openrouter_api_key', $api_key);
        update_option('revx_chatbot_openrouter_model', $model);
    }
    
    if (function_exists('wp_send_json_success')) {
        wp_send_json_success(array('message' => 'OpenRouter API settings saved successfully!'));
    }
}

// Add action only if WordPress function is available
if (function_exists('add_action')) {
    add_action('wp_ajax_revx_chatbot_save_openrouter_settings', 'revx_chatbot_save_openrouter_settings');
}

/**
 * Add admin menu for chatbot settings
 */
function revx_chatbot_admin_menu() {
    // Check if WordPress function is available
    if (!function_exists('add_menu_page')) {
        return;
    }
    
    add_menu_page(
        'RevX Chatbot Settings',
        'RevX Chatbot',
        'manage_options',
        'revx-chatbot-settings',
        'revx_chatbot_settings_page',
        'dashicons-format-chat',
        100
    );
}

// Add action only if WordPress function is available
if (function_exists('add_action')) {
    add_action('admin_menu', 'revx_chatbot_admin_menu');
}

/**
 * Render the settings page
 */
function revx_chatbot_settings_page() {
    include_once REVX_CHATBOT_PATH . 'templates/admin-settings.php';
}

/**
 * Enqueue admin scripts and styles
 */
function revx_chatbot_admin_enqueue_scripts($hook) {
    // Check if WordPress functions are available
    if (!function_exists('wp_enqueue_style') || !function_exists('wp_enqueue_script') || 
        !function_exists('wp_localize_script') || !function_exists('admin_url') || 
        !function_exists('wp_create_nonce')) {
        return;
    }
    
    if ($hook != 'toplevel_page_revx-chatbot-settings') {
        return;
    }
    
    wp_enqueue_style('revx-chatbot-admin-style', REVX_CHATBOT_URL . 'assets/css/revx-chatbot-admin.css', array(), REVX_CHATBOT_VERSION);
    wp_enqueue_script('revx-chatbot-admin-script', REVX_CHATBOT_URL . 'assets/js/revx-chatbot-admin.js', array('jquery'), REVX_CHATBOT_VERSION, true);
    
    wp_localize_script('revx-chatbot-admin-script', 'revxChatbotAdmin', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('revx_chatbot_nonce'),
    ));
}

// Add action only if WordPress function is available
if (function_exists('add_action')) {
    add_action('admin_enqueue_scripts', 'revx_chatbot_admin_enqueue_scripts');
}