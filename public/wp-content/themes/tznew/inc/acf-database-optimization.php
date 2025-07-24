<?php
/**
 * ACF Database Optimization and Field Management
 *
 * @package TZnew
 * @author Santosh Baral
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class for managing ACF database optimization and field integrity
 */
class TZnew_ACF_Database_Manager {
    
    private static $instance = null;
    private $field_registry = array();
    private $validation_errors = array();
    
    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
        $this->register_field_mappings();
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        add_action('acf/save_post', array($this, 'validate_field_data'), 10);
        add_action('acf/save_post', array($this, 'sanitize_field_data'), 15);
        add_action('acf/save_post', array($this, 'backup_field_data'), 20);
        add_action('acf/save_post', array($this, 'optimize_database_queries'), 25);
        
        add_action('wp_loaded', array($this, 'check_field_integrity'));
        add_action('admin_init', array($this, 'register_cleanup_tasks'));
        
        // Add admin notices for validation errors
        add_action('admin_notices', array($this, 'display_validation_notices'));
        
        // Database maintenance hooks
        add_action('wp_scheduled_delete', array($this, 'cleanup_orphaned_meta'));
        add_filter('acf/update_value', array($this, 'prevent_data_conflicts'), 10, 3);
    }
    
    /**
     * Register field mappings for validation
     */
    private function register_field_mappings() {
        $this->field_registry = array(
            'trekking' => array(
                'required_fields' => array('trek_name', 'overview', 'duration'),
                'numeric_fields' => array('duration', 'max_altitude', 'price_usd'),
                'text_fields' => array('trek_name', 'trek_number', 'best_season'),
                'repeater_fields' => array('itinerary'),
                'group_fields' => array('permits', 'cost_info'),
                'wysiwyg_fields' => array('overview', 'inclusion', 'exclusion')
            ),
            'tours' => array(
                'required_fields' => array('tour_name', 'overview', 'duration'),
                'numeric_fields' => array('duration', 'price_usd'),
                'text_fields' => array('tour_name', 'tour_number', 'best_season'),
                'repeater_fields' => array('itinerary'),
                'group_fields' => array('permits', 'cost_info'),
                'wysiwyg_fields' => array('overview', 'inclusion', 'exclusion')
            ),
            'faq' => array(
            'required_fields' => array('faq_question', 'faq_answer'),
            'repeater_fields' => array(),
            'wysiwyg_fields' => array('faq_answer')
        ),
            'blog' => array(
                'required_fields' => array('blog_title'),
                'text_fields' => array('blog_title', 'meta_keywords', 'hashtags'),
                'wysiwyg_fields' => array('blog_details'),
                'textarea_fields' => array('meta_description')
            )
        );
    }
    
    /**
     * Validate field data before saving
     */
    public function validate_field_data($post_id) {
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return;
        }
        
        $post_type = get_post_type($post_id);
        
        if (!isset($this->field_registry[$post_type])) {
            return;
        }
        
        $fields = $this->field_registry[$post_type];
        $this->validation_errors = array();
        
        // Validate required fields
        if (isset($fields['required_fields'])) {
            foreach ($fields['required_fields'] as $field_name) {
                $value = tznew_get_field_safe($field_name, $post_id);
                if (empty($value)) {
                    $this->validation_errors[] = sprintf(
                        'Required field "%s" is empty for post ID %d',
                        $field_name,
                        $post_id
                    );
                }
            }
        }
        
        // Validate numeric fields
        if (isset($fields['numeric_fields'])) {
            foreach ($fields['numeric_fields'] as $field_name) {
                $value = tznew_get_field_safe($field_name, $post_id);
                if (!empty($value) && !is_numeric($value)) {
                    $this->validation_errors[] = sprintf(
                        'Field "%s" must be numeric for post ID %d',
                        $field_name,
                        $post_id
                    );
                }
            }
        }
        
        // Validate repeater fields structure
        if (isset($fields['repeater_fields'])) {
            foreach ($fields['repeater_fields'] as $field_name) {
                $this->validate_repeater_field($field_name, $post_id);
            }
        }
        
        // Log validation errors (with rate limiting to prevent spam)
        if (!empty($this->validation_errors)) {
            $log_key = 'validation_errors_' . $post_id;
            $last_logged = get_transient($log_key);
            if (!$last_logged) {
                error_log('ACF Validation Errors: ' . implode('; ', $this->validation_errors));
                set_transient($log_key, time(), 3600); // Log once per hour
            }
        }
    }
    
    /**
     * Validate repeater field structure
     */
    private function validate_repeater_field($field_name, $post_id) {
        if (!have_rows($field_name, $post_id)) {
            return;
        }
        
        $row_count = 0;
        while (have_rows($field_name, $post_id)) {
            the_row();
            $row_count++;
            
            // Validate itinerary specific fields
            if ($field_name === 'itinerary') {
                $title = tznew_get_sub_field_safe('title');
                $description = tznew_get_sub_field_safe('description');
                
                if (empty($title)) {
                    $this->validation_errors[] = sprintf(
                        'Itinerary row %d missing title for post ID %d',
                        $row_count,
                        $post_id
                    );
                }
                
                if (empty($description)) {
                    $this->validation_errors[] = sprintf(
                        'Itinerary row %d missing description for post ID %d',
                        $row_count,
                        $post_id
                    );
                }
                
                // Validate coordinates if provided
                $coordinates = tznew_get_sub_field_safe('coordinates');
                if (!empty($coordinates)) {
                    $lat = $coordinates['latitude'] ?? '';
                    $lng = $coordinates['longitude'] ?? '';
                    
                    if (!empty($lat) && (!is_numeric($lat) || $lat < -90 || $lat > 90)) {
                        $this->validation_errors[] = sprintf(
                            'Invalid latitude in itinerary row %d for post ID %d',
                            $row_count,
                            $post_id
                        );
                    }
                    
                    if (!empty($lng) && (!is_numeric($lng) || $lng < -180 || $lng > 180)) {
                        $this->validation_errors[] = sprintf(
                            'Invalid longitude in itinerary row %d for post ID %d',
                            $row_count,
                            $post_id
                        );
                    }
                }
            }
        }
    }
    
    /**
     * Sanitize field data
     */
    public function sanitize_field_data($post_id) {
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return;
        }
        
        $post_type = get_post_type($post_id);
        
        if (!isset($this->field_registry[$post_type])) {
            return;
        }
        
        $fields = $this->field_registry[$post_type];
        
        // Sanitize text fields
        if (isset($fields['text_fields'])) {
            foreach ($fields['text_fields'] as $field_name) {
                $value = tznew_get_field_safe($field_name, $post_id);
                if (!empty($value)) {
                    $sanitized = sanitize_text_field($value);
                    if ($value !== $sanitized) {
                        update_field($field_name, $sanitized, $post_id);
                    }
                }
            }
        }

        // Sanitize numeric fields
        if (isset($fields['numeric_fields'])) {
            foreach ($fields['numeric_fields'] as $field_name) {
                $value = tznew_get_field_safe($field_name, $post_id);
                if (!empty($value)) {
                    $sanitized = floatval($value);
                    if ($value != $sanitized) {
                        update_field($field_name, $sanitized, $post_id);
                    }
                }
            }
        }
    }
    
    /**
     * Backup field data before major changes
     */
    public function backup_field_data($post_id) {
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return;
        }
        
        $post_type = get_post_type($post_id);
        
        if (!in_array($post_type, array('trekking', 'tours', 'faq', 'blog'))) {
            return;
        }
        
        // Create backup of all ACF fields
        $all_fields = function_exists('get_fields') ? get_fields($post_id) : array();
        
        if (!empty($all_fields)) {
            $backup_data = array(
                'post_id' => $post_id,
                'post_type' => $post_type,
                'timestamp' => current_time('mysql'),
                'fields' => $all_fields
            );
            
            // Store backup in custom table or as post meta
            update_post_meta($post_id, '_acf_backup_' . time(), $backup_data);
            
            // Keep only last 5 backups
            $this->cleanup_old_backups($post_id);
        }
    }
    
    /**
     * Cleanup old backups
     */
    private function cleanup_old_backups($post_id) {
        global $wpdb;
        
        $backup_keys = $wpdb->get_col($wpdb->prepare(
            "SELECT meta_key FROM {$wpdb->postmeta} 
             WHERE post_id = %d AND meta_key LIKE '_acf_backup_%%' 
             ORDER BY meta_key DESC",
            $post_id
        ));
        
        if (count($backup_keys) > 5) {
            $keys_to_delete = array_slice($backup_keys, 5);
            foreach ($keys_to_delete as $key) {
                delete_post_meta($post_id, $key);
            }
        }
    }
    
    /**
     * Optimize database queries
     */
    public function optimize_database_queries($post_id) {
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return;
        }
        
        // Clear any relevant caches
        wp_cache_delete($post_id, 'posts');
        wp_cache_delete($post_id, 'post_meta');
        
        // Update search index if needed
        $this->update_search_index($post_id);
    }
    
    /**
     * Update search index for better performance
     */
    private function update_search_index($post_id) {
        $post_type = get_post_type($post_id);
        
        if (!in_array($post_type, array('trekking', 'tours'))) {
            return;
        }
        
        // Create searchable content from ACF fields
        $searchable_content = array();
        
        // Add basic fields
        $fields_to_index = array('overview', 'region', 'difficulty', 'best_season');
        
        foreach ($fields_to_index as $field_name) {
            $value = tznew_get_field_safe($field_name, $post_id);
            if (!empty($value)) {
                $searchable_content[] = strip_tags($value);
            }
        }
        
        // Add itinerary content
        if (have_rows('itinerary', $post_id)) {
            while (have_rows('itinerary', $post_id)) {
                the_row();
                $title = get_sub_field('title');
                $description = get_sub_field('description');
                $place_name = get_sub_field('place_name');
                
                if (!empty($title)) $searchable_content[] = $title;
                if (!empty($description)) $searchable_content[] = strip_tags($description);
                if (!empty($place_name)) $searchable_content[] = $place_name;
            }
        }
        
        // Store searchable content
        $search_content = implode(' ', $searchable_content);
        update_post_meta($post_id, '_search_content', $search_content);
    }
    
    /**
     * Check field integrity
     */
    public function check_field_integrity() {
        if (!is_admin()) {
            return;
        }
        
        // Check for orphaned ACF meta
        $this->check_orphaned_meta();
        
        // Check for missing required fields
        $this->check_missing_fields();
    }
    
    /**
     * Check for orphaned meta data
     */
    private function check_orphaned_meta() {
        global $wpdb;
        
        $orphaned_meta = $wpdb->get_results(
            "SELECT pm.meta_id, pm.post_id, pm.meta_key 
             FROM {$wpdb->postmeta} pm 
             LEFT JOIN {$wpdb->posts} p ON pm.post_id = p.ID 
             WHERE p.ID IS NULL 
             AND pm.meta_key LIKE 'field_%'"
        );
        
        if (!empty($orphaned_meta)) {
            error_log('Found ' . count($orphaned_meta) . ' orphaned ACF meta entries');
        }
    }
    
    /**
     * Check for missing required fields
     */
    private function check_missing_fields() {
        foreach ($this->field_registry as $post_type => $config) {
            if (!isset($config['required_fields'])) {
                continue;
            }
            
            $posts = get_posts(array(
                'post_type' => $post_type,
                'post_status' => 'publish',
                'numberposts' => -1,
                'fields' => 'ids'
            ));
            
            foreach ($posts as $post_id) {
                foreach ($config['required_fields'] as $field_name) {
                    $value = get_field($field_name, $post_id);
                    if (empty($value)) {
                        error_log(sprintf(
                            'Missing required field "%s" for %s post ID %d',
                            $field_name,
                            $post_type,
                            $post_id
                        ));
                    }
                }
            }
        }
    }
    
    /**
     * Register cleanup tasks
     */
    public function register_cleanup_tasks() {
        if (!wp_next_scheduled('tznew_acf_cleanup')) {
            wp_schedule_event(time(), 'weekly', 'tznew_acf_cleanup');
        }
        
        add_action('tznew_acf_cleanup', array($this, 'run_weekly_cleanup'));
    }
    
    /**
     * Run weekly cleanup
     */
    public function run_weekly_cleanup() {
        $this->cleanup_orphaned_meta();
        $this->optimize_database_tables();
    }
    
    /**
     * Cleanup orphaned meta data
     */
    public function cleanup_orphaned_meta() {
        global $wpdb;
        
        // Remove orphaned ACF meta
        $wpdb->query(
            "DELETE pm FROM {$wpdb->postmeta} pm 
             LEFT JOIN {$wpdb->posts} p ON pm.post_id = p.ID 
             WHERE p.ID IS NULL 
             AND pm.meta_key LIKE 'field_%'"
        );
        
        // Remove orphaned backup data older than 30 days
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->postmeta} 
             WHERE meta_key LIKE '_acf_backup_%%' 
             AND meta_key < %s",
            '_acf_backup_' . (time() - (30 * DAY_IN_SECONDS))
        ));
    }
    
    /**
     * Optimize database tables
     */
    private function optimize_database_tables() {
        global $wpdb;
        
        $wpdb->query("OPTIMIZE TABLE {$wpdb->postmeta}");
        $wpdb->query("OPTIMIZE TABLE {$wpdb->posts}");
    }
    
    /**
     * Prevent data conflicts
     */
    public function prevent_data_conflicts($value, $post_id, $field) {
        // Prevent concurrent updates
        $lock_key = 'acf_update_' . $post_id . '_' . $field['name'];
        
        if (wp_cache_get($lock_key)) {
            // Another update is in progress, skip this one
            return get_field($field['name'], $post_id);
        }
        
        // Set lock for 30 seconds
        wp_cache_set($lock_key, true, '', 30);
        
        return $value;
    }
    
    /**
     * Display validation notices
     */
    public function display_validation_notices() {
        if (!empty($this->validation_errors)) {
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p><strong>ACF Validation Warnings:</strong></p>';
            echo '<ul>';
            foreach ($this->validation_errors as $error) {
                echo '<li>' . esc_html($error) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    }
    
    /**
     * Get field backup history
     */
    public function get_field_backup_history($post_id) {
        global $wpdb;
        
        $backup_keys = $wpdb->get_col($wpdb->prepare(
            "SELECT meta_key FROM {$wpdb->postmeta} 
             WHERE post_id = %d AND meta_key LIKE '_acf_backup_%%' 
             ORDER BY meta_key DESC",
            $post_id
        ));
        
        $backups = array();
        foreach ($backup_keys as $key) {
            $backup_data = get_post_meta($post_id, $key, true);
            if (!empty($backup_data)) {
                $backups[] = $backup_data;
            }
        }
        
        return $backups;
    }
    
    /**
     * Restore field data from backup
     */
    public function restore_from_backup($post_id, $backup_timestamp) {
        $backup_key = '_acf_backup_' . $backup_timestamp;
        $backup_data = get_post_meta($post_id, $backup_key, true);
        
        if (empty($backup_data) || !isset($backup_data['fields'])) {
            return false;
        }
        
        // Create current backup before restore
        $this->backup_field_data($post_id);
        
        // Restore fields
        foreach ($backup_data['fields'] as $field_name => $field_value) {
            update_field($field_name, $field_value, $post_id);
        }
        
        return true;
    }
}

// Initialize the database manager
TZnew_ACF_Database_Manager::get_instance();

/**
 * Helper functions for safe field access
 */
function tznew_get_field_safe($field_name, $default = '', $post_id = false) {
    // Check if ACF is available
    if (!function_exists('get_field')) {
        return $default;
    }
    
    // Check if 'init' action has fired to prevent early translation loading
    if (!did_action('init')) {
        // If init hasn't fired yet, return default to prevent early ACF calls
        return $default;
    }
    
    $value = get_field($field_name, $post_id);
    return !empty($value) ? $value : $default;
}

function tznew_have_rows_safe($field_name, $post_id = false) {
    if (!function_exists('have_rows')) {
        return false;
    }
    
    // Check if 'init' action has fired to prevent early translation loading
    if (!did_action('init')) {
        return false;
    }
    
    return have_rows($field_name, $post_id);
}

function tznew_get_sub_field_safe($field_name, $default = '') {
    if (!function_exists('get_sub_field')) {
        return $default;
    }
    
    // Check if 'init' action has fired to prevent early translation loading
    if (!did_action('init')) {
        return $default;
    }
    
    $value = get_sub_field($field_name);
    return !empty($value) ? $value : $default;
}

function tznew_the_row_safe() {
    if (!function_exists('the_row')) {
        return false;
    }
    
    // Check if 'init' action has fired to prevent early translation loading
    if (!did_action('init')) {
        return false;
    }
    
    return the_row();
}

/**
 * Database integrity check function
 */
function tznew_check_database_integrity() {
    $manager = TZnew_ACF_Database_Manager::get_instance();
    $manager->check_field_integrity();
}

/**
 * Manual cleanup function
 */
function tznew_run_database_cleanup() {
    $manager = TZnew_ACF_Database_Manager::get_instance();
    $manager->run_weekly_cleanup();
}

/**
 * Get backup history for a post
 */
function tznew_get_backup_history($post_id) {
    $manager = TZnew_ACF_Database_Manager::get_instance();
    return $manager->get_field_backup_history($post_id);
}

/**
 * Restore from backup
 */
function tznew_restore_backup($post_id, $backup_timestamp) {
    $manager = TZnew_ACF_Database_Manager::get_instance();
    return $manager->restore_from_backup($post_id, $backup_timestamp);
}