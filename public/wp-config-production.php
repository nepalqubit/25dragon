<?php
/**
 * Production WordPress Configuration File
 * 
 * Copy this file to wp-config.php and update the values for your production environment
 * This configuration is optimized for deployment to any server
 *
 * @package WordPress
 */

// ** Environment Detection ** //
// Set this to 'production', 'staging', or 'development'
define( 'WP_ENVIRONMENT_TYPE', 'production' );

// ** Database settings - Update these for your production server ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'your_production_database_name' );

/** Database username */
define( 'DB_USER', 'your_production_database_user' );

/** Database password */
define( 'DB_PASSWORD', 'your_production_database_password' );

/** Database hostname */
define( 'DB_HOST', 'localhost' ); // or your production database host

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 * 
 * IMPORTANT: Generate new keys for production!
 * Visit: https://api.wordpress.org/secret-key/1.1/salt/
 * 
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 */
define( 'AUTH_KEY',          'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',   'put your unique phrase here' );
define( 'LOGGED_IN_KEY',     'put your unique phrase here' );
define( 'NONCE_KEY',         'put your unique phrase here' );
define( 'AUTH_SALT',         'put your unique phrase here' );
define( 'SECURE_AUTH_SALT',  'put your unique phrase here' );
define( 'LOGGED_IN_SALT',    'put your unique phrase here' );
define( 'NONCE_SALT',        'put your unique phrase here' );
define( 'WP_CACHE_KEY_SALT', 'put your unique phrase here' );

/**#@-*/

/**
 * WordPress database table prefix.
 */
$table_prefix = 'wp_';

/**
 * Security Settings for Production
 */
// Force SSL for admin and login pages
define( 'FORCE_SSL_ADMIN', true );

// Disable file editing in WordPress admin
define( 'DISALLOW_FILE_EDIT', true );

// Disable plugin and theme installation/updates from admin
define( 'DISALLOW_FILE_MODS', false ); // Set to true for maximum security

// Limit post revisions
define( 'WP_POST_REVISIONS', 3 );

// Set autosave interval (in seconds)
define( 'AUTOSAVE_INTERVAL', 300 ); // 5 minutes

// Increase memory limit
define( 'WP_MEMORY_LIMIT', '512M' );

// Set maximum execution time
define( 'WP_MAX_EXECUTION_TIME', 300 );

/**
 * Environment-specific Debug Settings
 */
if ( WP_ENVIRONMENT_TYPE === 'production' ) {
    // Production: Disable all debugging
    define( 'WP_DEBUG', false );
    define( 'WP_DEBUG_LOG', false );
    define( 'WP_DEBUG_DISPLAY', false );
    define( 'SCRIPT_DEBUG', false );
    
    // Enable error logging to file only
    ini_set( 'log_errors', 1 );
    ini_set( 'error_log', ABSPATH . 'wp-content/debug.log' );
    
} elseif ( WP_ENVIRONMENT_TYPE === 'staging' ) {
    // Staging: Enable logging but not display
    define( 'WP_DEBUG', true );
    define( 'WP_DEBUG_LOG', true );
    define( 'WP_DEBUG_DISPLAY', false );
    define( 'SCRIPT_DEBUG', false );
    
} else {
    // Development: Enable all debugging
    define( 'WP_DEBUG', true );
    define( 'WP_DEBUG_LOG', true );
    define( 'WP_DEBUG_DISPLAY', true );
    define( 'SCRIPT_DEBUG', true );
}

/**
 * Performance Optimizations
 */
// Enable WordPress caching
define( 'WP_CACHE', true );

// Optimize database queries
// define( 'WP_ALLOW_REPAIR', false ); // Set to true only when needed

// Compress CSS and JS (if not using a plugin)
define( 'COMPRESS_CSS', true );
define( 'COMPRESS_SCRIPTS', true );

// Enable Gzip compression
define( 'ENFORCE_GZIP', true );

/**
 * Multisite Configuration (if needed)
 * Uncomment and configure if using WordPress Multisite
 */
// define( 'WP_ALLOW_MULTISITE', true );
// define( 'MULTISITE', true );
// define( 'SUBDOMAIN_INSTALL', false );
// define( 'DOMAIN_CURRENT_SITE', 'your-domain.com' );
// define( 'PATH_CURRENT_SITE', '/' );
// define( 'SITE_ID_CURRENT_SITE', 1 );
// define( 'BLOG_ID_CURRENT_SITE', 1 );

/**
 * Custom Upload Directory (optional)
 * Uncomment to change the default uploads directory
 */
// define( 'UPLOADS', 'wp-content/uploads' );

/**
 * External URL Configuration
 * Uncomment and set if WordPress files are in a subdirectory
 */
// define( 'WP_SITEURL', 'https://your-domain.com/wordpress' );
// define( 'WP_HOME', 'https://your-domain.com' );

/**
 * CDN Configuration (optional)
 * Uncomment and configure if using a CDN
 */
// define( 'WP_CONTENT_URL', 'https://cdn.your-domain.com/wp-content' );
// define( 'WP_PLUGIN_URL', 'https://cdn.your-domain.com/wp-content/plugins' );

/**
 * Cookie Configuration
 */
// define( 'COOKIE_DOMAIN', '.your-domain.com' ); // For subdomain cookies
// define( 'COOKIEPATH', '/' );
// define( 'SITECOOKIEPATH', '/' );

/**
 * FTP Configuration (if needed for updates)
 */
// define( 'FS_METHOD', 'direct' ); // or 'ftpext', 'ftpsockets'
// define( 'FTP_HOST', 'your-ftp-host' );
// define( 'FTP_USER', 'your-ftp-username' );
// define( 'FTP_PASS', 'your-ftp-password' );
// define( 'FTP_SSL', true ); // Use SSL-FTP if available

/**
 * Email Configuration (optional)
 * Configure SMTP if needed
 */
// define( 'WPMS_ON', true );
// define( 'WPMS_SMTP_HOST', 'your-smtp-host' );
// define( 'WPMS_SMTP_PORT', 587 );
// define( 'WPMS_SMTP_AUTH', true );
// define( 'WPMS_SMTP_USER', 'your-smtp-username' );
// define( 'WPMS_SMTP_PASS', 'your-smtp-password' );

/**
 * Backup and Maintenance
 */
// Automatic database optimization (already defined above)
// define( 'WP_ALLOW_REPAIR', false );

// Disable cron if using server cron
// define( 'DISABLE_WP_CRON', true );

/**
 * Security Headers (handled by .htaccess, but can be set here too)
 */
// Additional security constants (FORCE_SSL_ADMIN already defined above)
// define( 'FORCE_SSL_ADMIN', true );
define( 'WP_HTTP_BLOCK_EXTERNAL', false ); // Set to true to block external HTTP requests

// Allowed external hosts (if WP_HTTP_BLOCK_EXTERNAL is true)
// define( 'WP_ACCESSIBLE_HOSTS', 'api.wordpress.org,*.github.com,cdn.jsdelivr.net,cdnjs.cloudflare.com,unpkg.com' );

/* Add any custom values between this line and the "stop editing" line. */

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';