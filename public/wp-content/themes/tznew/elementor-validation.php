<?php
/**
 * Elementor Integration Validation Script
 * 
 * This file helps validate that Elementor integration is working correctly.
 * Access this file via: /wp-content/themes/tznew/elementor-validation.php
 * 
 * @package TZNEW
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    // Load WordPress if accessed directly
    require_once('../../../wp-load.php');
}

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('Access denied. Admin privileges required.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>TZNEW Elementor Integration Validation</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f1f1f1; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .status { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        h1 { color: #333; border-bottom: 2px solid #0073aa; padding-bottom: 10px; }
        h2 { color: #0073aa; margin-top: 30px; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
        .test-item { margin: 15px 0; padding: 15px; border-left: 4px solid #0073aa; background: #f9f9f9; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎨 TZNEW Elementor Integration Validation</h1>
        <p>This validation script checks if Elementor integration is working correctly in the TZNEW theme.</p>
        
        <h2>📋 Basic Checks</h2>
        
        <?php
        // Check if Elementor is active
        echo '<div class="test-item">';
        echo '<strong>Elementor Plugin Status:</strong><br>';
        if (class_exists('\Elementor\Plugin')) {
            echo '<div class="status success">✅ Elementor is active and loaded</div>';
        } else {
            echo '<div class="status error">❌ Elementor is not active or not installed</div>';
        }
        echo '</div>';
        
        // Check if Elementor Pro is active
        echo '<div class="test-item">';
        echo '<strong>Elementor Pro Status:</strong><br>';
        if (class_exists('\ElementorPro\Plugin')) {
            echo '<div class="status success">✅ Elementor Pro is active</div>';
        } else {
            echo '<div class="status warning">⚠️ Elementor Pro is not active (Theme Builder features may be limited)</div>';
        }
        echo '</div>';
        
        // Check theme support
        echo '<div class="test-item">';
        echo '<strong>Theme Support:</strong><br>';
        $supports = [];
        if (current_theme_supports('elementor')) {
            $supports[] = '✅ Elementor support';
        } else {
            $supports[] = '❌ Elementor support missing';
        }
        
        if (current_theme_supports('elementor-header-footer')) {
            $supports[] = '✅ Elementor header/footer support';
        } else {
            $supports[] = '❌ Elementor header/footer support missing';
        }
        
        if (current_theme_supports('elementor-pro-header-footer')) {
            $supports[] = '✅ Elementor Pro header/footer support';
        } else {
            $supports[] = '⚠️ Elementor Pro header/footer support missing';
        }
        
        if (current_theme_supports('elementor-theme-builder')) {
            $supports[] = '✅ Elementor Theme Builder support';
        } else {
            $supports[] = '⚠️ Elementor Theme Builder support missing';
        }
        
        foreach ($supports as $support) {
            if (strpos($support, '✅') !== false) {
                echo '<div class="status success">' . $support . '</div>';
            } elseif (strpos($support, '⚠️') !== false) {
                echo '<div class="status warning">' . $support . '</div>';
            } else {
                echo '<div class="status error">' . $support . '</div>';
            }
        }
        echo '</div>';
        
        // Check custom functions
        echo '<div class="test-item">';
        echo '<strong>Custom Functions:</strong><br>';
        $functions = [
            'tznew_elementor_location_exists' => 'Location existence checker',
            'tznew_elementor_do_location' => 'Location renderer',
            'tznew_get_company_name' => 'Company name getter',
            'tznew_get_company_phone' => 'Company phone getter',
            'tznew_get_booking_email' => 'Booking email getter'
        ];
        
        foreach ($functions as $function => $description) {
            if (function_exists($function)) {
                echo '<div class="status success">✅ ' . $function . ' (' . $description . ')</div>';
            } else {
                echo '<div class="status error">❌ ' . $function . ' (' . $description . ') - Missing</div>';
            }
        }
        echo '</div>';
        
        // Check dynamic tags files
        echo '<div class="test-item">';
        echo '<strong>Dynamic Tags Files:</strong><br>';
        $tag_files = [
            'acf-text.php' => 'ACF Text Tag',
            'acf-image.php' => 'ACF Image Tag', 
            'acf-url.php' => 'ACF URL Tag',
            'company-info.php' => 'Company Info Tag',
            'booking-info.php' => 'Booking Info Tag'
        ];
        
        foreach ($tag_files as $file => $description) {
            $file_path = get_template_directory() . '/inc/elementor/dynamic-tags/' . $file;
            if (file_exists($file_path)) {
                echo '<div class="status success">✅ ' . $file . ' (' . $description . ')</div>';
            } else {
                echo '<div class="status error">❌ ' . $file . ' (' . $description . ') - Missing</div>';
            }
        }
        echo '</div>';
        
        // Check if ACF is active
        echo '<div class="test-item">';
        echo '<strong>ACF Plugin Status:</strong><br>';
        if (class_exists('ACF')) {
            echo '<div class="status success">✅ Advanced Custom Fields is active</div>';
        } else {
            echo '<div class="status warning">⚠️ Advanced Custom Fields is not active (ACF dynamic tags will not work)</div>';
        }
        echo '</div>';
        
        ?>
        
        <h2>🔧 Template Integration Status</h2>
        
        <?php
        // Check template files
        $templates = [
            'header.php' => 'Header template',
            'footer.php' => 'Footer template', 
            'index.php' => 'Index template',
            'single.php' => 'Single post template',
            'archive.php' => 'Archive template',
            'page.php' => 'Page template',
            'search.php' => 'Search template',
            '404.php' => '404 template'
        ];
        
        foreach ($templates as $template => $description) {
            echo '<div class="test-item">';
            echo '<strong>' . $description . ' (' . $template . '):</strong><br>';
            
            $template_path = get_template_directory() . '/' . $template;
            if (file_exists($template_path)) {
                $content = file_get_contents($template_path);
                if (strpos($content, 'tznew_elementor_location_exists') !== false && 
                    strpos($content, 'tznew_elementor_do_location') !== false) {
                    echo '<div class="status success">✅ Elementor integration implemented</div>';
                } else {
                    echo '<div class="status warning">⚠️ Template exists but Elementor integration not detected</div>';
                }
            } else {
                echo '<div class="status error">❌ Template file missing</div>';
            }
            echo '</div>';
        }
        ?>
        
        <h2>📝 Next Steps</h2>
        <div class="status info">
            <strong>To test the integration:</strong><br>
            1. Go to <strong>Elementor → Theme Builder</strong><br>
            2. Create a new Header, Footer, or Single Post template<br>
            3. Add some content and publish the template<br>
            4. Set display conditions for the template<br>
            5. Visit your site to see if the Elementor template is being used<br>
            6. Check if custom dynamic tags appear in <strong>Dynamic Content</strong> options
        </div>
        
        <div class="status info">
            <strong>Dynamic Tags Testing:</strong><br>
            1. Edit any page/post with Elementor<br>
            2. Add a Text widget<br>
            3. Click the dynamic content icon (database icon)<br>
            4. Look for "TZNEW ACF Fields" and "TZNEW Theme Data" groups<br>
            5. Test selecting different dynamic tags
        </div>
        
        <p><em>Generated on: <?php echo date('Y-m-d H:i:s'); ?></em></p>
    </div>
</body>
</html>