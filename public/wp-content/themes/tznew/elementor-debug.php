<?php
/**
 * Elementor Debug Script
 * 
 * This script helps debug Elementor custom post type support.
 * Access it by visiting: yoursite.com/wp-content/themes/tznew/elementor-debug.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Security check - only allow administrators
if (!current_user_can('manage_options')) {
    wp_die('Access denied. You must be an administrator to view this page.');
}

// Check if Elementor is active
if (!class_exists('\Elementor\Plugin')) {
    wp_die('Elementor is not active on this site.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Elementor Custom Post Types Debug</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .action-button { background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px; }
        .action-button:hover { background: #005a87; color: white; }
    </style>
</head>
<body>
    <h1>Elementor Custom Post Types Debug</h1>
    
    <?php
    // Get current Elementor CPT support
    $elementor_cpt_support = get_option('elementor_cpt_support', []);
    $custom_post_types = ['trekking', 'tours', 'blog', 'faq'];
    
    echo '<h2>Current Status</h2>';
    
    // Check if custom post types are registered
    echo '<h3>Custom Post Types Registration</h3>';
    foreach ($custom_post_types as $post_type) {
        $post_type_object = get_post_type_object($post_type);
        if ($post_type_object) {
            $supports = get_all_post_type_supports($post_type);
            $has_editor = isset($supports['editor']);
            $has_elementor = isset($supports['elementor']);
            
            $status_class = ($has_editor && $has_elementor) ? 'success' : 'warning';
            echo "<div class='status {$status_class}'>";
            echo "<strong>{$post_type}</strong>: Registered";
            echo "<br>Editor support: " . ($has_editor ? '✓ Yes' : '✗ No');
            echo "<br>Elementor support: " . ($has_elementor ? '✓ Yes' : '✗ No');
            echo "</div>";
        } else {
            echo "<div class='status error'><strong>{$post_type}</strong>: Not registered</div>";
        }
    }
    
    // Check Elementor CPT support option
    echo '<h3>Elementor CPT Support Option</h3>';
    echo '<pre>';
    print_r($elementor_cpt_support);
    echo '</pre>';
    
    // Check which custom post types are missing
    $missing_types = array_diff($custom_post_types, $elementor_cpt_support);
    if (empty($missing_types)) {
        echo "<div class='status success'>All custom post types are in Elementor CPT support list!</div>";
    } else {
        echo "<div class='status warning'>Missing from Elementor CPT support: " . implode(', ', $missing_types) . "</div>";
    }
    
    // Action to fix Elementor support
    if (isset($_GET['action']) && $_GET['action'] === 'fix_elementor_support') {
        $new_cpt_support = array_unique(array_merge($elementor_cpt_support, $custom_post_types));
        update_option('elementor_cpt_support', $new_cpt_support);
        
        // Clear caches
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }
        
        // Clear Elementor cache if possible
        if (class_exists('\Elementor\Plugin')) {
            \Elementor\Plugin::$instance->files_manager->clear_cache();
        }
        
        echo "<div class='status success'>Elementor CPT support has been updated! Please refresh this page to see the changes.</div>";
        echo "<script>setTimeout(function(){ window.location.reload(); }, 2000);</script>";
    }
    
    // Show action buttons
    echo '<h3>Actions</h3>';
    echo '<a href="?action=fix_elementor_support" class="action-button">Fix Elementor Support</a>';
    echo '<a href="' . admin_url('admin.php?page=elementor-tools#tab-general') . '" class="action-button">Go to Elementor Settings</a>';
    echo '<a href="' . admin_url() . '" class="action-button">Go to WordPress Admin</a>';
    
    // Show instructions
    echo '<h3>Manual Steps (if automatic fix doesn\'t work)</h3>';
    echo '<div class="info">';
    echo '<ol>';
    echo '<li>Go to <strong>Elementor > Tools > General</strong></li>';
    echo '<li>Scroll down to the <strong>"Post Types"</strong> section</li>';
    echo '<li>Check the boxes for: <strong>Trekking, Tours, Blog, FAQ</strong></li>';
    echo '<li>Click <strong>"Save Changes"</strong></li>';
    echo '<li>Clear any caching plugins you might have</li>';
    echo '<li>Try editing a post of these types with Elementor</li>';
    echo '</ol>';
    echo '</div>';
    
    // Show theme information
    echo '<h3>Theme Information</h3>';
    echo '<div class="info">';
    echo 'Theme: ' . get_template() . '<br>';
    echo 'Elementor Version: ' . (defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : 'Not detected') . '<br>';
    echo 'WordPress Version: ' . get_bloginfo('version') . '<br>';
    echo 'PHP Version: ' . PHP_VERSION . '<br>';
    echo '</div>';
    ?>
    
</body>
</html>