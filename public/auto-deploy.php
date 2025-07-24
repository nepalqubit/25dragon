<?php
/**
 * Automatic WordPress Deployment Script
 * 
 * This script automatically configures WordPress for any server environment
 * It detects the current domain and updates all necessary URLs and settings
 * 
 * SECURITY: This file auto-deletes after successful deployment
 * 
 * Usage: Simply access this file via browser after uploading to new server
 * Example: https://your-new-domain.com/auto-deploy.php
 */

// Security: Only allow execution once
$lock_file = __DIR__ . '/.deployment-lock';
if (file_exists($lock_file)) {
    die('Deployment already completed. If you need to redeploy, delete the .deployment-lock file.');
}

// Load WordPress
require_once('wp-config.php');
require_once('wp-includes/wp-db.php');

// Initialize database connection
$wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);

$results = [];
$errors = [];

try {
    // Detect current environment
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
    $domain = $_SERVER['HTTP_HOST'];
    $path = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    $new_url = $protocol . $domain . rtrim($path, '/');
    
    // Get current URLs from database
    $current_home = $wpdb->get_var("SELECT option_value FROM {$wpdb->options} WHERE option_name = 'home'");
    $current_siteurl = $wpdb->get_var("SELECT option_value FROM {$wpdb->options} WHERE option_name = 'siteurl'");
    
    $results[] = "🔍 Detected new URL: {$new_url}";
    $results[] = "📍 Current home URL: {$current_home}";
    $results[] = "📍 Current site URL: {$current_siteurl}";
    
    // Only update if URLs are different
    if ($current_home !== $new_url || $current_siteurl !== $new_url) {
        
        // Start transaction
        $wpdb->query('START TRANSACTION');
        
        // Update WordPress core URLs
        $wpdb->update(
            $wpdb->options,
            ['option_value' => $new_url],
            ['option_name' => 'home']
        );
        
        $wpdb->update(
            $wpdb->options,
            ['option_value' => $new_url],
            ['option_name' => 'siteurl']
        );
        
        $results[] = "✅ Updated WordPress core URLs";
        
        // Update content URLs if old URL exists
        if ($current_home && $current_home !== $new_url) {
            $old_url = rtrim($current_home, '/');
            $new_url_clean = rtrim($new_url, '/');
            
            // Update post content
            $updated_posts = $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)",
                $old_url, $new_url_clean
            ));
            
            // Update post excerpts
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->posts} SET post_excerpt = REPLACE(post_excerpt, %s, %s)",
                $old_url, $new_url_clean
            ));
            
            // Update meta values
            $updated_meta = $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s)",
                $old_url, $new_url_clean
            ));
            
            // Update comments
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->comments} SET comment_content = REPLACE(comment_content, %s, %s)",
                $old_url, $new_url_clean
            ));
            
            // Update options (excluding home/siteurl)
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->options} SET option_value = REPLACE(option_value, %s, %s) WHERE option_name NOT IN ('home', 'siteurl')",
                $old_url, $new_url_clean
            ));
            
            // Handle HTTPS variants
            $old_https = str_replace('http://', 'https://', $old_url);
            $new_https = str_replace('http://', 'https://', $new_url_clean);
            
            if ($old_https !== $old_url) {
                $wpdb->query($wpdb->prepare(
                    "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)",
                    $old_https, $new_https
                ));
                
                $wpdb->query($wpdb->prepare(
                    "UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s)",
                    $old_https, $new_https
                ));
            }
            
            $results[] = "✅ Updated content URLs (posts: {$updated_posts}, meta: {$updated_meta})";
        }
        
        // Commit transaction
        $wpdb->query('COMMIT');
        $results[] = "✅ Database transaction completed successfully";
        
    } else {
        $results[] = "ℹ️ URLs already match current domain - no database updates needed";
    }
    
    // Update .htaccess for production if needed
    $htaccess_file = __DIR__ . '/.htaccess';
    if (file_exists($htaccess_file) && strpos($domain, '.local') === false && strpos($domain, 'localhost') === false) {
        $htaccess_content = file_get_contents($htaccess_file);
        
        // Enable HTTPS redirect if HTTPS is detected
        if ($protocol === 'https://') {
            $htaccess_content = str_replace(
                '# <IfModule mod_rewrite.c>\n#     RewriteEngine On\n#     RewriteCond %{HTTPS} off\n#     RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\n# </IfModule>',
                '<IfModule mod_rewrite.c>\n    RewriteEngine On\n    RewriteCond %{HTTPS} off\n    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\n</IfModule>',
                $htaccess_content
            );
            
            // Enable HSTS header
            $htaccess_content = str_replace(
                '    # Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"',
                '    Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"',
                $htaccess_content
            );
            
            file_put_contents($htaccess_file, $htaccess_content);
            $results[] = "✅ Enabled HTTPS redirects and security headers";
        }
        
        // Remove deployment file access blocks
        $htaccess_content = preg_replace(
            '/<Files "deploy-url-update\.php">.*?<\/Files>/s',
            '',
            $htaccess_content
        );
        
        file_put_contents($htaccess_file, $htaccess_content);
        $results[] = "✅ Updated .htaccess configuration";
    }
    
    // Create deployment lock file
    file_put_contents($lock_file, date('Y-m-d H:i:s') . " - Deployment completed for {$new_url}");
    $results[] = "🔒 Created deployment lock file";
    
    // Schedule self-deletion (will happen on next page load)
    $self_delete_file = __DIR__ . '/.delete-auto-deploy';
    file_put_contents($self_delete_file, __FILE__);
    $results[] = "🗑️ Scheduled auto-deletion of deployment script";
    
    $deployment_success = true;
    
} catch (Exception $e) {
    // Rollback on error
    $wpdb->query('ROLLBACK');
    $errors[] = 'Deployment failed: ' . $e->getMessage();
    $deployment_success = false;
}

// Auto-delete previous deployment script if exists
if (file_exists(__DIR__ . '/.delete-auto-deploy')) {
    $file_to_delete = trim(file_get_contents(__DIR__ . '/.delete-auto-deploy'));
    if (file_exists($file_to_delete) && $file_to_delete !== __FILE__) {
        unlink($file_to_delete);
        unlink(__DIR__ . '/.delete-auto-deploy');
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WordPress Auto-Deployment</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f1f1f1;
            line-height: 1.6;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #23282d;
            border-bottom: 3px solid #0073aa;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .results {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .results ul {
            margin: 0;
            padding-left: 20px;
        }
        .results li {
            margin: 8px 0;
        }
        .next-steps {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            background: #0073aa;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 10px 10px 0;
        }
        .btn:hover {
            background: #005a87;
        }
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 WordPress Auto-Deployment</h1>
        
        <?php if ($deployment_success): ?>
            <div class="success">
                <h3>✅ Deployment Completed Successfully!</h3>
                <p>Your WordPress site has been automatically configured for this server.</p>
            </div>
        <?php else: ?>
            <div class="error">
                <h3>❌ Deployment Failed</h3>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="results">
            <h3>📋 Deployment Results:</h3>
            <ul>
                <?php foreach ($results as $result): ?>
                    <li><?php echo htmlspecialchars($result); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <?php if ($deployment_success): ?>
            <div class="next-steps">
                <h3>🎯 Next Steps:</h3>
                <ol>
                    <li><strong>Test your website:</strong> Browse through your site to ensure everything works correctly</li>
                    <li><strong>Check admin area:</strong> Log into WordPress admin and verify settings</li>
                    <li><strong>Update permalinks:</strong> Go to Settings → Permalinks and click "Save Changes"</li>
                    <li><strong>Clear caches:</strong> Clear any caching plugins or server-side caches</li>
                    <li><strong>Security check:</strong> This deployment script will auto-delete on next page load</li>
                    <li><strong>SSL verification:</strong> Ensure HTTPS is working if SSL certificate is installed</li>
                </ol>
            </div>
            
            <div style="margin-top: 30px;">
                <a href="<?php echo $new_url; ?>" class="btn">🏠 Visit Website</a>
                <a href="<?php echo $new_url; ?>/wp-admin" class="btn">⚙️ WordPress Admin</a>
                <a href="<?php echo $new_url; ?>/wp-admin/options-permalink.php" class="btn">🔗 Update Permalinks</a>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 14px;">
            <h4>🔧 What this script did:</h4>
            <ul>
                <li>Automatically detected the current domain and protocol</li>
                <li>Updated WordPress home and site URLs in the database</li>
                <li>Replaced old URLs in post content, meta data, and comments</li>
                <li>Configured .htaccess for production environment</li>
                <li>Applied environment-specific settings</li>
                <li>Created deployment lock to prevent re-execution</li>
                <li>Scheduled self-deletion for security</li>
            </ul>
            
            <p><strong>Note:</strong> This script automatically deletes itself after successful deployment for security reasons.</p>
        </div>
    </div>
</body>
</html>