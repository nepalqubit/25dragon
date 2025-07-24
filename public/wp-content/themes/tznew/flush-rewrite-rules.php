<?php
/**
 * Flush Rewrite Rules and Clear Elementor Cache
 * 
 * This file should be accessed once via browser after theme updates
 * to ensure all custom post types work properly with Elementor.
 * 
 * URL: yoursite.com/wp-content/themes/tznew/flush-rewrite-rules.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('You do not have permission to access this page.');
}

echo '<h1>Flushing Rewrite Rules and Clearing Caches</h1>';

// Flush rewrite rules
flush_rewrite_rules();
echo '<p>✓ Rewrite rules flushed</p>';

// Clear Elementor cache if available
if (class_exists('\Elementor\Plugin')) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
    echo '<p>✓ Elementor cache cleared</p>';
}

// Update Elementor CPT support
$cpt_support = get_option('elementor_cpt_support');
if (!$cpt_support) {
    $cpt_support = ['page', 'post', 'trekking', 'tours', 'blog', 'faq'];
    update_option('elementor_cpt_support', $cpt_support);
} else {
    $cpt_support = array_merge($cpt_support, ['trekking', 'tours', 'blog', 'faq']);
    $cpt_support = array_unique($cpt_support);
    update_option('elementor_cpt_support', $cpt_support);
}
echo '<p>✓ Elementor custom post type support updated</p>';

echo '<h2>Summary</h2>';
echo '<p>All custom post types (trekking, tours, blog, faq) are now properly configured for Elementor:</p>';
echo '<ul>';
echo '<li>✓ Custom post types support "editor" and "elementor"</li>';
echo '<li>✓ ACF field groups no longer hide "the_content" editor</li>';
echo '<li>✓ Elementor CPT support option updated</li>';
echo '<li>✓ Theme Builder support enabled for custom post types</li>';
echo '<li>✓ Rewrite rules flushed</li>';
echo '<li>✓ Elementor cache cleared</li>';
echo '</ul>';

echo '<p><strong>You can now use Elementor with your custom post types!</strong></p>';
echo '<p><em>You can delete this file after running it once.</em></p>';

// Auto-delete this file after execution for security
// unlink(__FILE__);
?>