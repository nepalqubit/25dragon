<?php
/**
 * Force Elementor Custom Post Type Activation
 * 
 * This script forces Elementor to recognize custom post types immediately.
 * Access it by visiting: yoursite.com/wp-content/themes/tznew/elementor-force-activation.php
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

// Force activation function
function force_elementor_cpt_activation() {
    $custom_post_types = ['trekking', 'tours', 'blog', 'faq'];
    $success_messages = [];
    $error_messages = [];
    
    // 1. Update Elementor CPT support option
    $current_support = get_option('elementor_cpt_support', ['page', 'post']);
    $new_support = array_unique(array_merge($current_support, $custom_post_types));
    
    if (update_option('elementor_cpt_support', $new_support)) {
        $success_messages[] = 'Updated elementor_cpt_support option';
    } else {
        $error_messages[] = 'Failed to update elementor_cpt_support option';
    }
    
    // 2. Force add post type support for each custom post type
    foreach ($custom_post_types as $post_type) {
        if (post_type_exists($post_type)) {
            add_post_type_support($post_type, 'elementor');
            add_post_type_support($post_type, 'editor');
            $success_messages[] = "Added Elementor support to {$post_type}";
        } else {
            $error_messages[] = "Post type {$post_type} does not exist";
        }
    }
    
    // 3. Clear all caches
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
        $success_messages[] = 'Cleared WordPress cache';
    }
    
    // 4. Clear Elementor cache
    if (class_exists('\Elementor\Plugin')) {
        try {
            \Elementor\Plugin::$instance->files_manager->clear_cache();
            $success_messages[] = 'Cleared Elementor cache';
        } catch (Exception $e) {
            $error_messages[] = 'Failed to clear Elementor cache: ' . $e->getMessage();
        }
    }
    
    // 5. Flush rewrite rules
    flush_rewrite_rules();
    $success_messages[] = 'Flushed rewrite rules';
    
    return ['success' => $success_messages, 'errors' => $error_messages];
}

// Execute if requested
$results = null;
if (isset($_GET['action']) && $_GET['action'] === 'force_activate') {
    $results = force_elementor_cpt_activation();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Force Elementor CPT Activation</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f1f1f1; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .status { padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
        .action-button { background: #007cba; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; font-weight: bold; }
        .action-button:hover { background: #005a87; color: white; }
        .danger-button { background: #dc3545; }
        .danger-button:hover { background: #c82333; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
        ul { margin: 0; padding-left: 20px; }
        h1 { color: #333; }
        h2 { color: #555; border-bottom: 2px solid #007cba; padding-bottom: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Force Elementor Custom Post Types Activation</h1>
        
        <?php if ($results): ?>
            <h2>Activation Results</h2>
            
            <?php if (!empty($results['success'])): ?>
                <div class="status success">
                    <h3>✅ Success Messages:</h3>
                    <ul>
                        <?php foreach ($results['success'] as $message): ?>
                            <li><?php echo esc_html($message); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($results['errors'])): ?>
                <div class="status error">
                    <h3>❌ Error Messages:</h3>
                    <ul>
                        <?php foreach ($results['errors'] as $message): ?>
                            <li><?php echo esc_html($message); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="status info">
                <h3>🎯 Next Steps:</h3>
                <ol>
                    <li>Go to <strong>Elementor > Tools > General</strong></li>
                    <li>Scroll to <strong>"Post Types"</strong> section</li>
                    <li>Verify that <strong>Trekking, Tours, Blog, FAQ</strong> are checked</li>
                    <li>If not checked, check them and click <strong>"Save Changes"</strong></li>
                    <li>Try editing a post of these types with Elementor</li>
                </ol>
            </div>
        <?php else: ?>
            <div class="status warning">
                <h3>⚠️ Before You Proceed</h3>
                <p>This script will force Elementor to recognize your custom post types (Trekking, Tours, Blog, FAQ) by:</p>
                <ul>
                    <li>Updating Elementor's CPT support option</li>
                    <li>Adding post type support for Elementor</li>
                    <li>Clearing all caches</li>
                    <li>Flushing rewrite rules</li>
                </ul>
                <p><strong>This is safe to run and will not damage your site.</strong></p>
            </div>
        <?php endif; ?>
        
        <h2>Current Status</h2>
        <?php
        $elementor_cpt_support = get_option('elementor_cpt_support', []);
        $custom_post_types = ['trekking', 'tours', 'blog', 'faq'];
        ?>
        
        <div class="status info">
            <h3>📊 Current Elementor CPT Support:</h3>
            <pre><?php print_r($elementor_cpt_support); ?></pre>
        </div>
        
        <?php foreach ($custom_post_types as $post_type): ?>
            <?php
            $post_type_object = get_post_type_object($post_type);
            $supports_elementor = post_type_supports($post_type, 'elementor');
            $supports_editor = post_type_supports($post_type, 'editor');
            $in_cpt_support = in_array($post_type, $elementor_cpt_support);
            
            $all_good = $post_type_object && $supports_elementor && $supports_editor && $in_cpt_support;
            $status_class = $all_good ? 'success' : 'error';
            ?>
            <div class="status <?php echo $status_class; ?>">
                <strong><?php echo is_string($post_type) ? ucfirst($post_type) : 'Unknown'; ?> Post Type:</strong><br>
                Registered: <?php echo $post_type_object ? '✅ Yes' : '❌ No'; ?><br>
                Elementor Support: <?php echo $supports_elementor ? '✅ Yes' : '❌ No'; ?><br>
                Editor Support: <?php echo $supports_editor ? '✅ Yes' : '❌ No'; ?><br>
                In CPT Support List: <?php echo $in_cpt_support ? '✅ Yes' : '❌ No'; ?>
            </div>
        <?php endforeach; ?>
        
        <h2>Actions</h2>
        <a href="?action=force_activate" class="action-button danger-button" onclick="return confirm('Are you sure you want to force activate Elementor support for custom post types?')">
            🚀 Force Activate Elementor Support
        </a>
        <a href="<?php echo admin_url('admin.php?page=elementor-tools#tab-general'); ?>" class="action-button">
            ⚙️ Go to Elementor Settings
        </a>
        <a href="<?php echo admin_url(); ?>" class="action-button">
            🏠 WordPress Admin
        </a>
        
        <div class="status info" style="margin-top: 30px;">
            <h3>💡 Troubleshooting Tips</h3>
            <ul>
                <li>If activation doesn't work, try deactivating and reactivating Elementor plugin</li>
                <li>Clear any caching plugins you have active</li>
                <li>Check that your custom post types are properly registered</li>
                <li>Ensure you have the latest version of Elementor</li>
            </ul>
        </div>
    </div>
</body>
</html>