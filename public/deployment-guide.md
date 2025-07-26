# WordPress Deployment Guide for Dragon Latest Project

This guide ensures your WordPress site can be deployed to any server without URL or configuration issues.

## 1. Database URL Management

### WordPress Core URLs
WordPress stores site URLs in the database. Before deployment:

1. **Update WordPress URLs in Database:**
   ```sql
   UPDATE wp_options SET option_value = 'https://your-new-domain.com' WHERE option_name = 'home';
   UPDATE wp_options SET option_value = 'https://your-new-domain.com' WHERE option_name = 'siteurl';
   ```

2. **Update Content URLs:**
   ```sql
   UPDATE wp_posts SET post_content = REPLACE(post_content, 'http://dragonlatest.local', 'https://your-new-domain.com');
   UPDATE wp_posts SET post_content = REPLACE(post_content, 'https://dragonlatest.local', 'https://your-new-domain.com');
   ```

3. **Update Meta Values:**
   ```sql
   UPDATE wp_postmeta SET meta_value = REPLACE(meta_value, 'http://dragonlatest.local', 'https://your-new-domain.com');
   UPDATE wp_postmeta SET meta_value = REPLACE(meta_value, 'https://dragonlatest.local', 'https://your-new-domain.com');
   ```

### Alternative: Use WordPress CLI
```bash
wp search-replace 'http://dragonlatest.local' 'https://your-new-domain.com' --dry-run
wp search-replace 'http://dragonlatest.local' 'https://your-new-domain.com'
```

## 2. Configuration Files

### wp-config.php Updates
Update database credentials and add environment-specific constants:

```php
// Database Configuration
define( 'DB_NAME', 'your_production_db_name' );
define( 'DB_USER', 'your_production_db_user' );
define( 'DB_PASSWORD', 'your_production_db_password' );
define( 'DB_HOST', 'your_production_db_host' );

// Force SSL (recommended for production)
define( 'FORCE_SSL_ADMIN', true );

// Disable file editing in admin
define( 'DISALLOW_FILE_EDIT', true );

// Set memory limit
define( 'WP_MEMORY_LIMIT', '256M' );

// Environment-specific settings
if ( defined( 'WP_ENVIRONMENT_TYPE' ) && WP_ENVIRONMENT_TYPE === 'production' ) {
    define( 'WP_DEBUG', false );
    define( 'WP_DEBUG_LOG', false );
    define( 'WP_DEBUG_DISPLAY', false );
} else {
    define( 'WP_DEBUG', true );
    define( 'WP_DEBUG_LOG', true );
    define( 'WP_DEBUG_DISPLAY', false );
}
```

## 3. Theme Compatibility

### Current Theme Status ✅
The TZnew theme is already deployment-ready:

- ✅ Uses WordPress functions for URLs (`home_url()`, `get_template_directory_uri()`)
- ✅ No hardcoded localhost references
- ✅ Proper use of relative paths
- ✅ CDN resources use HTTPS

### External Resources
The theme uses these external CDNs (already HTTPS):
- Tailwind CSS: `https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css`
- Font Awesome: `https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css`
- Chart.js: `https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js`
- Leaflet: `https://unpkg.com/leaflet@1.9.4/dist/leaflet.css` & `.js`

## 4. Server Requirements

### Minimum Requirements
- PHP 7.4 or higher
- MySQL 5.7 or MariaDB 10.3
- Apache or Nginx
- SSL Certificate (recommended)

### Recommended Apache .htaccess
```apache
# Security Headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
Header always set Referrer-Policy "strict-origin-when-cross-origin"

# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Cache Control
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
</IfModule>

# WordPress Rules (keep existing)
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
```

## 5. Deployment Checklist

### Pre-Deployment
- [ ] Backup current database and files
- [ ] Update wp-config.php with production settings
- [ ] Test on staging environment
- [ ] Verify SSL certificate is installed

### During Deployment
- [ ] Upload files to production server
- [ ] Import database
- [ ] Run URL replacement queries
- [ ] Update file permissions (755 for directories, 644 for files)
- [ ] Test all functionality

### Post-Deployment
- [ ] Update WordPress and plugin URLs in admin
- [ ] Clear any caching
- [ ] Test contact forms and booking system
- [ ] Verify all external resources load correctly
- [ ] Check mobile responsiveness
- [ ] Test SSL certificate

## 6. Environment Variables (Optional)

For better security, consider using environment variables:

```php
// In wp-config.php
define( 'DB_NAME', $_ENV['DB_NAME'] ?? 'fallback_db_name' );
define( 'DB_USER', $_ENV['DB_USER'] ?? 'fallback_user' );
define( 'DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? 'fallback_password' );
define( 'DB_HOST', $_ENV['DB_HOST'] ?? 'localhost' );
```

## 7. Troubleshooting

### Common Issues
1. **Mixed Content Warnings**: Ensure all resources use HTTPS
2. **Broken Images**: Run URL replacement queries
3. **Plugin Issues**: Deactivate and reactivate plugins
4. **Permalink Issues**: Go to Settings > Permalinks and save

### Debug Mode
Temporarily enable debug mode if issues occur:
```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

## 8. Performance Optimization

### Recommended Plugins
- **Caching**: WP Rocket or W3 Total Cache
- **Image Optimization**: Smush or ShortPixel
- **Security**: Wordfence or Sucuri
- **Backup**: UpdraftPlus or BackWPup

### CDN Integration
Consider using a CDN like Cloudflare for better performance.

---

**Note**: This project is already well-structured for deployment. The theme uses WordPress best practices with proper URL handling and no hardcoded references to local development URLs.