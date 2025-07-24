# 🚀 WordPress Deployment Checklist - Dragon Latest Project

## ⚡ Quick Auto-Deployment (Recommended)
- [ ] Upload all files to production server
- [ ] Import database to production database server
- [ ] Update database credentials in `wp-config.php` (if different)
- [ ] Visit `https://your-domain.com/auto-deploy.php`
- [ ] Follow the auto-deployment instructions
- [ ] Test website functionality
- [ ] The deployment script will auto-delete for security

## 🔧 Manual Deployment Process (Alternative)

## ✅ Pre-Deployment Preparation

### 1. Backup Everything
- [ ] Export current database
- [ ] Create full file backup
- [ ] Document current URLs and settings
- [ ] Test backup restoration locally

### 2. Environment Setup
- [ ] Verify server requirements (PHP 7.4+, MySQL 5.7+)
- [ ] Ensure SSL certificate is installed
- [ ] Configure domain DNS settings
- [ ] Set up hosting environment

### 3. File Preparation
- [ ] Copy `wp-config-production.php` to `wp-config.php`
- [ ] Update database credentials in `wp-config.php`
- [ ] Generate new security keys at https://api.wordpress.org/secret-key/1.1/salt/
- [ ] Set `WP_ENVIRONMENT_TYPE` to 'production'
- [ ] Review and update `.htaccess` file

## 🔧 Deployment Process

### 1. File Upload
- [ ] Upload all WordPress files to server
- [ ] Set correct file permissions:
  - Directories: 755 (`find . -type d -exec chmod 755 {} \;`)
  - Files: 644 (`find . -type f -exec chmod 644 {} \;`)
  - wp-config.php: 600 (`chmod 600 wp-config.php`)

### 2. Database Migration
- [ ] Import database to production server
- [ ] Update database connection settings
- [ ] Test database connectivity

### 3. URL Updates
- [ ] Use `deploy-url-update.php` script OR WordPress CLI:
  ```bash
  # Using WP-CLI (recommended)
  wp search-replace 'http://dragonlatest.local' 'https://your-domain.com' --dry-run
  wp search-replace 'http://dragonlatest.local' 'https://your-domain.com'
  
  # Or use the provided PHP script
  # Access: https://your-domain.com/deploy-url-update.php
  ```
- [ ] **IMPORTANT**: Delete `deploy-url-update.php` after use!

### 4. WordPress Configuration
- [ ] Log into WordPress admin
- [ ] Go to Settings → General and verify URLs
- [ ] Go to Settings → Permalinks and save (refresh rewrite rules)
- [ ] Update any hardcoded URLs in widgets/menus

## 🔒 Security Configuration

### 1. SSL/HTTPS Setup
- [ ] Verify SSL certificate is working
- [ ] Uncomment HTTPS redirect in `.htaccess`:
  ```apache
  # Uncomment these lines:
  RewriteEngine On
  RewriteCond %{HTTPS} off
  RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
  ```
- [ ] Uncomment HSTS header in `.htaccess`:
  ```apache
  # Uncomment this line:
  Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
  ```

### 2. File Security
- [ ] Remove deployment files:
  - [ ] `deploy-url-update.php`
  - [ ] `wp-config-production.php`
  - [ ] `deployment-guide.md`
  - [ ] `DEPLOYMENT-CHECKLIST.md`
- [ ] Verify sensitive files are blocked by `.htaccess`
- [ ] Test that `wp-config.php` is not accessible via browser

### 3. WordPress Security
- [ ] Update all plugins and themes
- [ ] Install security plugin (Wordfence, Sucuri, etc.)
- [ ] Change default admin username if it's 'admin'
- [ ] Use strong passwords for all accounts
- [ ] Limit login attempts

## ⚡ Performance Optimization

### 1. Caching
- [ ] Install caching plugin (WP Rocket, W3 Total Cache, etc.)
- [ ] Configure browser caching (already in `.htaccess`)
- [ ] Enable Gzip compression (already in `.htaccess`)
- [ ] Test page load speeds

### 2. CDN Setup (Optional)
- [ ] Configure CDN (Cloudflare, MaxCDN, etc.)
- [ ] Update WordPress to use CDN URLs
- [ ] Test CDN functionality

### 3. Image Optimization
- [ ] Install image optimization plugin
- [ ] Optimize existing images
- [ ] Configure automatic optimization

## 🧪 Testing Phase

### 1. Functionality Testing
- [ ] Test homepage loads correctly
- [ ] Verify all menu links work
- [ ] Test contact forms
- [ ] Check booking system functionality
- [ ] Test search functionality
- [ ] Verify blog/news sections
- [ ] Test FAQ section

### 2. Content Verification
- [ ] Check all images display correctly
- [ ] Verify all internal links work
- [ ] Test external links
- [ ] Check that videos/media play correctly
- [ ] Verify social media links

### 3. Mobile & Browser Testing
- [ ] Test on mobile devices
- [ ] Test on different browsers (Chrome, Firefox, Safari, Edge)
- [ ] Verify responsive design works
- [ ] Test touch interactions

### 4. SEO & Analytics
- [ ] Verify Google Analytics is working
- [ ] Check Google Search Console
- [ ] Test meta tags and descriptions
- [ ] Verify sitemap is accessible
- [ ] Check robots.txt file

## 📊 Monitoring Setup

### 1. Uptime Monitoring
- [ ] Set up uptime monitoring service
- [ ] Configure alert notifications
- [ ] Test monitoring alerts

### 2. Backup Schedule
- [ ] Install backup plugin (UpdraftPlus, BackWPup, etc.)
- [ ] Configure automatic daily backups
- [ ] Test backup restoration
- [ ] Set up offsite backup storage

### 3. Update Management
- [ ] Configure automatic security updates
- [ ] Set up staging environment for testing updates
- [ ] Create update schedule and process

## 🎯 Post-Deployment Tasks

### 1. DNS & Domain
- [ ] Update DNS records if needed
- [ ] Set up www redirect (www to non-www or vice versa)
- [ ] Configure email forwarding if needed

### 2. Search Engine Optimization
- [ ] Submit sitemap to Google Search Console
- [ ] Submit sitemap to Bing Webmaster Tools
- [ ] Update Google My Business listing
- [ ] Update social media profiles with new URL

### 3. Documentation
- [ ] Document server credentials
- [ ] Create admin user guide
- [ ] Document backup procedures
- [ ] Create emergency contact list

## 🚨 Emergency Procedures

### If Something Goes Wrong:
1. **Don't Panic** - Most issues can be resolved
2. **Check Error Logs** - Look in cPanel/server error logs
3. **Restore from Backup** - If needed, restore previous version
4. **Contact Support** - Reach out to hosting provider if needed

### Common Issues & Solutions:

**Site shows "Database Connection Error"**
- Check database credentials in `wp-config.php`
- Verify database server is running
- Check database user permissions

**Images/CSS not loading**
- Check file permissions
- Verify URLs are updated correctly
- Clear any caching

**SSL Certificate Issues**
- Verify certificate is properly installed
- Check for mixed content warnings
- Update any hardcoded HTTP URLs

**404 Errors on Pages**
- Go to Settings → Permalinks and save
- Check `.htaccess` file permissions
- Verify rewrite rules are correct

## 📋 Final Verification

- [ ] All checklist items completed
- [ ] Site loads correctly on HTTPS
- [ ] All functionality tested and working
- [ ] Security measures in place
- [ ] Monitoring and backups configured
- [ ] Documentation completed
- [ ] Deployment files removed
- [ ] Team notified of successful deployment

---

**🎉 Congratulations!** Your WordPress site is now successfully deployed and ready for production use.

**Remember**: Keep this checklist for future deployments and updates. Regular maintenance and monitoring will ensure your site continues to perform optimally.

**Support**: If you encounter any issues, refer to the `deployment-guide.md` file for detailed technical information.