<?php
/**
 * Elementor Integration Test File
 *
 * This file helps test and verify Elementor integration
 * Access via: yoursite.com/wp-content/themes/tznew/elementor-test.php
 *
 * @package TZNEW
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    // Load WordPress
    require_once( '../../../wp-load.php' );
}

// Check if user is admin
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied. Admin privileges required.' );
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Elementor Integration Test - <?php bloginfo( 'name' ); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .success { background: #d4edda; border-color: #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .warning { background: #fff3cd; border-color: #ffeaa7; color: #856404; }
        .info { background: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
    </style>
</head>
<body>
    <h1>Elementor Integration Test Results</h1>
    
    <?php
    // Test 1: Check if Elementor is active
    echo '<div class="test-section ';
    if ( defined( 'ELEMENTOR_VERSION' ) ) {
        echo 'success"><h3>✓ Elementor Plugin</h3><p>Elementor is active (Version: ' . ELEMENTOR_VERSION . ')</p>';
    } else {
        echo 'error"><h3>✗ Elementor Plugin</h3><p>Elementor is not active or installed</p>';
    }
    echo '</div>';
    
    // Test 2: Check if Elementor Pro is active
    echo '<div class="test-section ';
    if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
        echo 'success"><h3>✓ Elementor Pro</h3><p>Elementor Pro is active (Version: ' . ELEMENTOR_PRO_VERSION . ')</p>';
    } else {
        echo 'warning"><h3>⚠ Elementor Pro</h3><p>Elementor Pro is not active (Theme Builder features may be limited)</p>';
    }
    echo '</div>';
    
    // Test 3: Check ACF integration
    echo '<div class="test-section ';
    if ( class_exists( 'ACF' ) ) {
        echo 'success"><h3>✓ ACF Plugin</h3><p>Advanced Custom Fields is active</p>';
        
        // List ACF field groups
        if ( function_exists( 'acf_get_field_groups' ) ) {
            $field_groups = acf_get_field_groups();
            if ( ! empty( $field_groups ) ) {
                echo '<p><strong>Available Field Groups:</strong></p><ul>';
                foreach ( $field_groups as $group ) {
                    echo '<li>' . esc_html( $group['title'] ) . ' (Key: ' . esc_html( $group['key'] ) . ')</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No ACF field groups found.</p>';
            }
        }
    } else {
        echo 'error"><h3>✗ ACF Plugin</h3><p>Advanced Custom Fields is not active</p>';
    }
    echo '</div>';
    
    // Test 4: Check custom post types
    echo '<div class="test-section info"><h3>Custom Post Types</h3>';
    $custom_post_types = [ 'trekking', 'tours', 'blog', 'faq' ];
    foreach ( $custom_post_types as $post_type ) {
        if ( post_type_exists( $post_type ) ) {
            $elementor_support = post_type_supports( $post_type, 'elementor' ) ? '✓' : '✗';
            echo '<p>' . $elementor_support . ' ' . ucfirst( $post_type ) . ' post type';
            if ( post_type_supports( $post_type, 'elementor' ) ) {
                echo ' (Elementor supported)';
            } else {
                echo ' (Elementor NOT supported)';
            }
            echo '</p>';
        } else {
            echo '<p>✗ ' . ucfirst( $post_type ) . ' post type (not registered)</p>';
        }
    }
    echo '</div>';
    
    // Test 5: Check Elementor CPT support option
    echo '<div class="test-section info"><h3>Elementor CPT Support Setting</h3>';
    $cpt_support = get_option( 'elementor_cpt_support', [] );
    if ( ! empty( $cpt_support ) ) {
        echo '<p><strong>Supported Post Types:</strong> ' . implode( ', ', $cpt_support ) . '</p>';
    } else {
        echo '<p>No post types configured for Elementor support.</p>';
    }
    echo '</div>';
    
    // Test 6: Check theme support
    echo '<div class="test-section ';
    if ( current_theme_supports( 'elementor' ) ) {
        echo 'success"><h3>✓ Theme Support</h3><p>Theme supports Elementor</p>';
    } else {
        echo 'warning"><h3>⚠ Theme Support</h3><p>Theme does not declare Elementor support</p>';
    }
    echo '</div>';
    
    // Test 7: Check page templates
    echo '<div class="test-section info"><h3>Page Templates</h3>';
    $page_templates = wp_get_theme()->get_page_templates();
    if ( isset( $page_templates['page-canvas.php'] ) ) {
        echo '<p>✓ Elementor Canvas template available</p>';
    } else {
        echo '<p>✗ Elementor Canvas template missing</p>';
    }
    
    if ( isset( $page_templates['page-elementor_header_footer.php'] ) ) {
        echo '<p>✓ Elementor Full Width template available</p>';
    } else {
        echo '<p>✗ Elementor Full Width template missing</p>';
    }
    echo '</div>';
    
    // Test 8: Check dynamic tags
    echo '<div class="test-section info"><h3>Dynamic Tags</h3>';
    if ( class_exists( 'TZNEW_ACF_Text_Tag' ) ) {
        echo '<p>✓ ACF Text dynamic tag class loaded</p>';
    } else {
        echo '<p>✗ ACF Text dynamic tag class not loaded</p>';
    }
    
    if ( class_exists( 'TZNEW_ACF_Image_Tag' ) ) {
        echo '<p>✓ ACF Image dynamic tag class loaded</p>';
    } else {
        echo '<p>✗ ACF Image dynamic tag class not loaded</p>';
    }
    echo '</div>';
    
    // Test 9: Check if content area functions exist
    echo '<div class="test-section ';
    if ( function_exists( 'tznew_elementor_location_exists' ) && function_exists( 'tznew_elementor_do_location' ) ) {
        echo 'success"><h3>✓ Theme Builder Functions</h3><p>Theme Builder helper functions are available</p>';
    } else {
        echo 'error"><h3>✗ Theme Builder Functions</h3><p>Theme Builder helper functions are missing</p>';
    }
    echo '</div>';
    ?>
    
    <div class="test-section info">
        <h3>Next Steps</h3>
        <ol>
            <li>If any tests show errors, check the theme's functions.php and inc/elementor.php files</li>
            <li>Clear Elementor cache: Elementor → Tools → Regenerate CSS & Data</li>
            <li>Go to Elementor → Settings → Advanced and ensure your custom post types are selected</li>
            <li>Try creating a new page with Elementor to test the content area</li>
            <li>Test ACF field integration by adding dynamic content in Elementor</li>
        </ol>
    </div>
    
    <p><a href="<?php echo admin_url(); ?>">← Back to WordPress Admin</a></p>
</body>
</html>