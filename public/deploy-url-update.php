<?php
/**
 * WordPress URL Update Script for Deployment
 * 
 * This script helps update all URLs when moving WordPress to a new domain
 * Run this script after uploading files to the new server
 * 
 * IMPORTANT: Remove this file after deployment for security!
 * 
 * Usage: 
 * 1. Upload this file to your WordPress root directory
 * 2. Access it via browser: https://your-new-domain.com/deploy-url-update.php
 * 3. Fill in the form and submit
 * 4. Delete this file after successful update
 */

// Security check - only allow access from specific IPs (optional)
// $allowed_ips = ['your.ip.address.here'];
// if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
//     die('Access denied');
// }

// Load WordPress
require_once('wp-config.php');
require_once('wp-includes/wp-db.php');

// Initialize database connection
$wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);

$message = '';
$error = '';

if ($_POST && isset($_POST['update_urls'])) {
    $old_url = sanitize_text_field($_POST['old_url']);
    $new_url = sanitize_text_field($_POST['new_url']);
    
    // Validate URLs
    if (!filter_var($old_url, FILTER_VALIDATE_URL) || !filter_var($new_url, FILTER_VALIDATE_URL)) {
        $error = 'Please enter valid URLs';
    } else {
        // Remove trailing slashes
        $old_url = rtrim($old_url, '/');
        $new_url = rtrim($new_url, '/');
        
        try {
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
            
            // Update post content
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)",
                $old_url, $new_url
            ));
            
            // Update post excerpts
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->posts} SET post_excerpt = REPLACE(post_excerpt, %s, %s)",
                $old_url, $new_url
            ));
            
            // Update meta values
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s)",
                $old_url, $new_url
            ));
            
            // Update comments
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->comments} SET comment_content = REPLACE(comment_content, %s, %s)",
                $old_url, $new_url
            ));
            
            // Update comment meta
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->commentmeta} SET meta_value = REPLACE(meta_value, %s, %s)",
                $old_url, $new_url
            ));
            
            // Update options (for serialized data)
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->options} SET option_value = REPLACE(option_value, %s, %s) WHERE option_name NOT IN ('home', 'siteurl')",
                $old_url, $new_url
            ));
            
            // Update user meta
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->usermeta} SET meta_value = REPLACE(meta_value, %s, %s)",
                $old_url, $new_url
            ));
            
            // Update term meta
            if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->termmeta}'")) {
                $wpdb->query($wpdb->prepare(
                    "UPDATE {$wpdb->termmeta} SET meta_value = REPLACE(meta_value, %s, %s)",
                    $old_url, $new_url
                ));
            }
            
            // Handle HTTPS variants
            $old_https = str_replace('http://', 'https://', $old_url);
            $new_https = str_replace('http://', 'https://', $new_url);
            
            if ($old_https !== $old_url) {
                // Update HTTPS variants
                $wpdb->query($wpdb->prepare(
                    "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)",
                    $old_https, $new_https
                ));
                
                $wpdb->query($wpdb->prepare(
                    "UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s)",
                    $old_https, $new_https
                ));
                
                $wpdb->query($wpdb->prepare(
                    "UPDATE {$wpdb->options} SET option_value = REPLACE(option_value, %s, %s) WHERE option_name NOT IN ('home', 'siteurl')",
                    $old_https, $new_https
                ));
            }
            
            // Commit transaction
            $wpdb->query('COMMIT');
            
            $message = 'URLs updated successfully! Please test your site and delete this file.';
            
        } catch (Exception $e) {
            // Rollback on error
            $wpdb->query('ROLLBACK');
            $error = 'Error updating URLs: ' . $e->getMessage();
        }
    }
}

// Get current URLs from database
$current_home = $wpdb->get_var("SELECT option_value FROM {$wpdb->options} WHERE option_name = 'home'");
$current_siteurl = $wpdb->get_var("SELECT option_value FROM {$wpdb->options} WHERE option_name = 'siteurl'");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WordPress URL Update Tool</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f1f1f1;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #23282d;
            border-bottom: 2px solid #0073aa;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #23282d;
        }
        input[type="url"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn {
            background: #0073aa;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #005a87;
        }
        .message {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
            margin-bottom: 20px;
        }
        .current-urls {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .security-notice {
            background: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #f44336;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 WordPress URL Update Tool</h1>
        
        <div class="security-notice">
            <strong>⚠️ Security Notice:</strong> This file should be deleted immediately after use! It provides direct database access and should not remain on your server.
        </div>
        
        <?php if ($message): ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="current-urls">
            <h3>📍 Current URLs in Database:</h3>
            <p><strong>Home URL:</strong> <?php echo htmlspecialchars($current_home); ?></p>
            <p><strong>Site URL:</strong> <?php echo htmlspecialchars($current_siteurl); ?></p>
        </div>
        
        <div class="warning">
            <strong>⚠️ Important:</strong> This will update ALL URLs in your database. Make sure you have a backup before proceeding!
        </div>
        
        <form method="post">
            <div class="form-group">
                <label for="old_url">Old URL (from development/staging):</label>
                <input type="url" id="old_url" name="old_url" 
                       placeholder="https://dragonlatest.local" 
                       value="<?php echo isset($_POST['old_url']) ? htmlspecialchars($_POST['old_url']) : 'http://dragonlatest.local'; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="new_url">New URL (production domain):</label>
                <input type="url" id="new_url" name="new_url" 
                       placeholder="https://your-new-domain.com" 
                       value="<?php echo isset($_POST['new_url']) ? htmlspecialchars($_POST['new_url']) : ''; ?>" 
                       required>
            </div>
            
            <button type="submit" name="update_urls" class="btn" 
                    onclick="return confirm('Are you sure you want to update all URLs? This cannot be undone without a database restore.')">
                🔄 Update URLs
            </button>
        </form>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666;">
            <h3>📋 What this script does:</h3>
            <ul>
                <li>Updates WordPress home and site URLs</li>
                <li>Replaces URLs in post content and excerpts</li>
                <li>Updates URLs in post meta, comments, and user meta</li>
                <li>Handles both HTTP and HTTPS variants</li>
                <li>Uses database transactions for safety</li>
            </ul>
            
            <h3>🔧 After running this script:</h3>
            <ul>
                <li>Test your website thoroughly</li>
                <li>Check that images and links work correctly</li>
                <li>Go to Settings → Permalinks and save (to refresh rewrite rules)</li>
                <li>Clear any caching plugins</li>
                <li><strong>Delete this file immediately!</strong></li>
            </ul>
        </div>
    </div>
</body>
</html>