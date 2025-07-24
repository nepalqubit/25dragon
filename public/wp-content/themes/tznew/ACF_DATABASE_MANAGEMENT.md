# ACF Database Management System

## Overview

This comprehensive ACF (Advanced Custom Fields) Database Management System ensures that all custom field data is well-organized and managed in the database to prevent data conflicts and losses, making the system operate smoothly without any issues.

## Features

### 1. Database Optimization
- **Orphaned Field Cleanup**: Automatically removes ACF fields that no longer have corresponding posts
- **Duplicate Field Removal**: Identifies and removes duplicate field entries
- **Query Optimization**: Optimizes database queries for better performance
- **Index Management**: Ensures proper database indexing for ACF fields

### 2. Data Integrity
- **Field Validation**: Validates ACF field data according to field type requirements
- **Data Sanitization**: Sanitizes field values to prevent security issues
- **Integrity Checks**: Performs comprehensive database integrity checks
- **Relationship Validation**: Ensures field relationships are maintained

### 3. Backup & Recovery
- **Automated Backups**: Creates regular backups of ACF data
- **Manual Backup Creation**: Allows on-demand backup creation
- **Backup Restoration**: Restores data from previous backups
- **Backup Management**: Manages backup retention and cleanup

### 4. Admin Interface
- **Dashboard Statistics**: Shows database health metrics
- **One-Click Actions**: Provides easy-to-use cleanup and optimization tools
- **Settings Management**: Configurable auto-cleanup and validation settings
- **Progress Tracking**: Real-time progress indicators for long operations

## File Structure

```
theme-root/
├── inc/
│   └── acf-integration.php          # Main ACF integration file
├── acf-database-optimization.php    # Core optimization classes
├── acf-admin-interface.php          # Admin interface
├── assets/
│   ├── js/
│   │   └── acf-admin.js             # Admin interface JavaScript
│   └── css/
│       └── acf-admin.css            # Admin interface styles
└── ACF_DATABASE_MANAGEMENT.md       # This documentation
```

## Core Classes

### TZnew_ACF_Database_Optimizer
Main optimization class that handles:
- Database cleanup operations
- Query optimization
- Performance monitoring
- Index management

### TZnew_ACF_Data_Validator
Validation class that ensures:
- Field type validation
- Data format compliance
- Security sanitization
- Relationship integrity

### TZnew_ACF_Backup_Manager
Backup management class that provides:
- Automated backup creation
- Backup restoration
- Backup file management
- Retention policy enforcement

### TZnew_ACF_Integrity_Checker
Integrity checking class that performs:
- Database consistency checks
- Field relationship validation
- Orphaned data detection
- Corruption identification

### TZnew_ACF_Admin_Interface
Admin interface class that offers:
- WordPress admin integration
- AJAX-powered operations
- Real-time statistics
- User-friendly controls

## Usage Guide

### Accessing the Admin Interface

1. Navigate to **Tools > ACF Database** in your WordPress admin
2. View database statistics and health metrics
3. Use the action buttons to perform maintenance tasks
4. Configure settings for automated maintenance

### Database Actions

#### Clean Database
- Removes orphaned ACF fields
- Cleans up duplicate entries
- Optimizes database tables
- Updates statistics

#### Check Integrity
- Validates all ACF field data
- Identifies potential issues
- Generates detailed reports
- Suggests corrective actions

#### Optimize Database
- Rebuilds database indexes
- Optimizes table structure
- Improves query performance
- Reduces database size

#### Export Backup
- Creates full ACF data backup
- Includes field definitions
- Exports in multiple formats
- Stores in secure location

### Settings Configuration

#### Auto Cleanup
- **Disabled**: No automatic cleanup
- **Daily**: Runs cleanup every day
- **Weekly**: Runs cleanup every week
- **Monthly**: Runs cleanup every month

#### Backup Retention
- Set number of days to keep backups
- Range: 1-365 days
- Default: 30 days
- Automatic cleanup of old backups

#### Validation Level
- **Basic**: Standard validation checks
- **Strict**: Enhanced validation with warnings
- **Paranoid**: Maximum validation with detailed logging

## API Functions

### Safe Field Access
```php
// Safe way to get ACF field values
$value = tznew_get_field_safe('field_name', $post_id);

// Safe way to check for repeater rows
if (tznew_have_rows_safe('repeater_field', $post_id)) {
    // Process rows
}
```

### Manual Operations
```php
// Initialize optimizer
$optimizer = new TZnew_ACF_Database_Optimizer();

// Clean orphaned fields
$result = $optimizer->cleanup_orphaned_fields();

// Check data integrity
$integrity = $optimizer->check_data_integrity();

// Create backup
$backup_manager = new TZnew_ACF_Backup_Manager();
$backup_result = $backup_manager->create_backup();
```

## Automated Maintenance

### WordPress Cron Integration
The system integrates with WordPress cron to provide:
- Scheduled cleanup operations
- Automatic backup creation
- Regular integrity checks
- Performance monitoring

### Hooks and Filters
```php
// Hook into auto cleanup
add_action('tznew_acf_auto_cleanup', 'custom_cleanup_function');

// Filter backup settings
add_filter('tznew_acf_backup_settings', 'custom_backup_settings');

// Hook into integrity checks
add_action('tznew_acf_integrity_check', 'custom_integrity_handler');
```

## Security Considerations

### Access Control
- Admin interface requires `manage_options` capability
- AJAX requests use WordPress nonces
- File operations are restricted to authorized users
- Backup files are stored in secure locations

### Data Protection
- All user inputs are sanitized
- SQL queries use prepared statements
- File uploads are validated
- Backup files are encrypted (optional)

## Performance Optimization

### Database Indexes
The system automatically creates and maintains indexes on:
- ACF field keys
- Post relationships
- Meta value lookups
- Timestamp fields

### Query Optimization
- Uses efficient SQL queries
- Implements proper JOIN operations
- Utilizes WordPress object caching
- Minimizes database calls

### Memory Management
- Processes large datasets in chunks
- Implements garbage collection
- Uses streaming for file operations
- Monitors memory usage

## Troubleshooting

### Common Issues

#### High Memory Usage
- Reduce batch size in settings
- Enable chunked processing
- Increase PHP memory limit
- Use command-line operations for large datasets

#### Slow Operations
- Check database indexes
- Optimize MySQL configuration
- Use background processing
- Enable query caching

#### Backup Failures
- Check file permissions
- Verify disk space
- Review PHP execution limits
- Check backup directory accessibility

### Debug Mode
Enable debug mode by adding to wp-config.php:
```php
define('TZNEW_ACF_DEBUG', true);
```

This enables:
- Detailed error logging
- Performance metrics
- SQL query logging
- Memory usage tracking

## Best Practices

### Regular Maintenance
1. Run integrity checks weekly
2. Create backups before major changes
3. Monitor database statistics
4. Review cleanup logs regularly

### Field Management
1. Use consistent field naming
2. Avoid unnecessary field groups
3. Clean up unused fields promptly
4. Document field relationships

### Performance
1. Enable object caching
2. Use appropriate validation levels
3. Schedule maintenance during low traffic
4. Monitor database size growth

## Support and Maintenance

### Logging
All operations are logged to:
- WordPress debug log
- Custom ACF log files
- Database activity logs
- Error reporting system

### Monitoring
The system provides monitoring for:
- Database health metrics
- Performance indicators
- Error rates
- Backup status

### Updates
Regular updates include:
- Security patches
- Performance improvements
- New features
- Bug fixes

## Conclusion

This ACF Database Management System provides a comprehensive solution for maintaining data integrity, preventing conflicts, and ensuring smooth operation of your WordPress site. By implementing automated maintenance, providing powerful admin tools, and following best practices, it helps maintain a healthy and efficient database environment.

For additional support or questions, please refer to the code comments or contact the development team.