<?php
/**
 * ACF Database Management Admin Interface
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
 * Class for ACF Database Management Admin Interface
 */
class TZnew_ACF_Admin_Interface {
    
    private static $instance = null;
    
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
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_tznew_run_cleanup', array($this, 'ajax_run_cleanup'));
        add_action('wp_ajax_tznew_check_integrity', array($this, 'ajax_check_integrity'));
        add_action('wp_ajax_tznew_restore_backup', array($this, 'ajax_restore_backup'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=trekking',
            'Database Management',
            'Database Management',
            'manage_options',
            'tznew-acf-database',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('tznew_acf_database', 'tznew_acf_auto_cleanup');
        register_setting('tznew_acf_database', 'tznew_acf_backup_retention');
        register_setting('tznew_acf_database', 'tznew_acf_validation_level');
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'tznew-acf-database') === false) {
            return;
        }
        
        wp_enqueue_script('jquery');
        wp_enqueue_script(
            'tznew-acf-admin',
            get_template_directory_uri() . '/assets/js/acf-admin.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        wp_localize_script('tznew-acf-admin', 'tznewAcfAdmin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('tznew_acf_admin'),
            'messages' => array(
                'cleanup_success' => __('Database cleanup completed successfully.', 'tznew'),
                'cleanup_error' => __('Error during database cleanup.', 'tznew'),
                'integrity_check_complete' => __('Integrity check completed.', 'tznew'),
                'restore_success' => __('Backup restored successfully.', 'tznew'),
                'restore_error' => __('Error restoring backup.', 'tznew'),
            )
        ));
        
        wp_enqueue_style(
            'tznew-acf-admin',
            get_template_directory_uri() . '/assets/css/acf-admin.css',
            array(),
            '1.0.0'
        );
    }
    
    /**
     * Admin page content
     */
    public function admin_page() {
        if (isset($_POST['submit'])) {
            $this->save_settings();
        }
        
        $stats = $this->get_database_stats();
        $recent_backups = $this->get_recent_backups();
        
        ?>
        <div class="wrap">
            <h1><?php _e('ACF Database Management', 'tznew'); ?></h1>
            
            <div class="tznew-acf-admin-container">
                <!-- Database Statistics -->
                <div class="tznew-acf-card">
                    <h2><?php _e('Database Statistics', 'tznew'); ?></h2>
                    <div class="tznew-stats-grid">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo esc_html($stats['total_posts']); ?></span>
                            <span class="stat-label"><?php _e('Total Posts', 'tznew'); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo esc_html($stats['total_meta']); ?></span>
                            <span class="stat-label"><?php _e('ACF Meta Fields', 'tznew'); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo esc_html($stats['orphaned_meta']); ?></span>
                            <span class="stat-label"><?php _e('Orphaned Meta', 'tznew'); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo esc_html($stats['total_backups']); ?></span>
                            <span class="stat-label"><?php _e('Total Backups', 'tznew'); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="tznew-acf-card">
                    <h2><?php _e('Quick Actions', 'tznew'); ?></h2>
                    <div class="tznew-actions-grid">
                        <button type="button" class="button button-primary" id="run-cleanup">
                            <?php _e('Run Database Cleanup', 'tznew'); ?>
                        </button>
                        <button type="button" class="button button-secondary" id="check-integrity">
                            <?php _e('Check Data Integrity', 'tznew'); ?>
                        </button>
                        <button type="button" class="button button-secondary" id="optimize-tables">
                            <?php _e('Optimize Tables', 'tznew'); ?>
                        </button>
                        <button type="button" class="button button-secondary" id="export-data">
                            <?php _e('Export ACF Data', 'tznew'); ?>
                        </button>
                    </div>
                    <div id="action-results" class="tznew-results"></div>
                </div>
                
                <!-- Settings -->
                <div class="tznew-acf-card">
                    <h2><?php _e('Settings', 'tznew'); ?></h2>
                    <form method="post" action="">
                        <?php wp_nonce_field('tznew_acf_settings', 'tznew_acf_nonce'); ?>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Auto Cleanup', 'tznew'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="tznew_acf_auto_cleanup" value="1" 
                                               <?php checked(get_option('tznew_acf_auto_cleanup', 1)); ?> />
                                        <?php _e('Enable automatic weekly cleanup', 'tznew'); ?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Backup Retention', 'tznew'); ?></th>
                                <td>
                                    <select name="tznew_acf_backup_retention">
                                        <option value="5" <?php selected(get_option('tznew_acf_backup_retention', 5), 5); ?>>
                                            <?php _e('5 backups per post', 'tznew'); ?>
                                        </option>
                                        <option value="10" <?php selected(get_option('tznew_acf_backup_retention', 5), 10); ?>>
                                            <?php _e('10 backups per post', 'tznew'); ?>
                                        </option>
                                        <option value="20" <?php selected(get_option('tznew_acf_backup_retention', 5), 20); ?>>
                                            <?php _e('20 backups per post', 'tznew'); ?>
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Validation Level', 'tznew'); ?></th>
                                <td>
                                    <select name="tznew_acf_validation_level">
                                        <option value="basic" <?php selected(get_option('tznew_acf_validation_level', 'basic'), 'basic'); ?>>
                                            <?php _e('Basic validation', 'tznew'); ?>
                                        </option>
                                        <option value="strict" <?php selected(get_option('tznew_acf_validation_level', 'basic'), 'strict'); ?>>
                                            <?php _e('Strict validation', 'tznew'); ?>
                                        </option>
                                        <option value="custom" <?php selected(get_option('tznew_acf_validation_level', 'basic'), 'custom'); ?>>
                                            <?php _e('Custom validation rules', 'tznew'); ?>
                                        </option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        
                        <?php submit_button(); ?>
                    </form>
                </div>
                
                <!-- Recent Backups -->
                <div class="tznew-acf-card">
                    <h2><?php _e('Recent Backups', 'tznew'); ?></h2>
                    <?php if (!empty($recent_backups)) : ?>
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th><?php _e('Post ID', 'tznew'); ?></th>
                                    <th><?php _e('Post Type', 'tznew'); ?></th>
                                    <th><?php _e('Backup Date', 'tznew'); ?></th>
                                    <th><?php _e('Actions', 'tznew'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_backups as $backup) : ?>
                                    <tr>
                                        <td><?php echo esc_html($backup['post_id']); ?></td>
                                        <td><?php echo esc_html($backup['post_type']); ?></td>
                                        <td><?php echo esc_html($backup['timestamp']); ?></td>
                                        <td>
                                            <button type="button" class="button button-small restore-backup" 
                                                    data-post-id="<?php echo esc_attr($backup['post_id']); ?>"
                                                    data-timestamp="<?php echo esc_attr($backup['backup_key']); ?>">
                                                <?php _e('Restore', 'tznew'); ?>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p><?php _e('No recent backups found.', 'tznew'); ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Field Integrity Report -->
                <div class="tznew-acf-card">
                    <h2><?php _e('Field Integrity Report', 'tznew'); ?></h2>
                    <div id="integrity-report">
                        <p><?php _e('Click "Check Data Integrity" to generate a report.', 'tznew'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .tznew-acf-admin-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        
        .tznew-acf-card {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 20px;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        
        .tznew-acf-card h2 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .tznew-stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 15px;
        }
        
        .stat-item {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 4px;
        }
        
        .stat-number {
            display: block;
            font-size: 24px;
            font-weight: bold;
            color: #0073aa;
        }
        
        .stat-label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .tznew-actions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 15px;
        }
        
        .tznew-results {
            margin-top: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
            min-height: 40px;
        }
        
        .tznew-results.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .tznew-results.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .tznew-acf-admin-container {
                grid-template-columns: 1fr;
            }
            
            .tznew-stats-grid,
            .tznew-actions-grid {
                grid-template-columns: 1fr;
            }
        }
        </style>
        <?php
    }
    
    /**
     * Save settings
     */
    private function save_settings() {
        if (!wp_verify_nonce($_POST['tznew_acf_nonce'], 'tznew_acf_settings')) {
            return;
        }
        
        update_option('tznew_acf_auto_cleanup', isset($_POST['tznew_acf_auto_cleanup']) ? 1 : 0);
        update_option('tznew_acf_backup_retention', sanitize_text_field($_POST['tznew_acf_backup_retention']));
        update_option('tznew_acf_validation_level', sanitize_text_field($_POST['tznew_acf_validation_level']));
        
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Settings saved successfully.', 'tznew') . '</p></div>';
    }
    
    /**
     * Get database statistics
     */
    private function get_database_stats() {
        global $wpdb;
        
        $stats = array();
        
        // Total posts
        $stats['total_posts'] = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->posts} 
             WHERE post_type IN ('trekking', 'tours', 'faq', 'blog') 
             AND post_status = 'publish'"
        );
        
        // Total ACF meta
        $stats['total_meta'] = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->postmeta} 
             WHERE meta_key LIKE 'field_%' OR meta_key LIKE '_%'"
        );
        
        // Orphaned meta
        $stats['orphaned_meta'] = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->postmeta} pm 
             LEFT JOIN {$wpdb->posts} p ON pm.post_id = p.ID 
             WHERE p.ID IS NULL AND pm.meta_key LIKE 'field_%'"
        );
        
        // Total backups
        $stats['total_backups'] = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->postmeta} 
             WHERE meta_key LIKE '_acf_backup_%'"
        );
        
        return $stats;
    }
    
    /**
     * Get recent backups
     */
    private function get_recent_backups() {
        global $wpdb;
        
        $backup_meta = $wpdb->get_results(
            "SELECT post_id, meta_key, meta_value FROM {$wpdb->postmeta} 
             WHERE meta_key LIKE '_acf_backup_%' 
             ORDER BY meta_key DESC 
             LIMIT 20"
        );
        
        $backups = array();
        foreach ($backup_meta as $meta) {
            $backup_data = maybe_unserialize($meta->meta_value);
            if (is_array($backup_data) && isset($backup_data['timestamp'])) {
                $backups[] = array(
                    'post_id' => $meta->post_id,
                    'post_type' => $backup_data['post_type'] ?? 'unknown',
                    'timestamp' => $backup_data['timestamp'],
                    'backup_key' => str_replace('_acf_backup_', '', $meta->meta_key)
                );
            }
        }
        
        return $backups;
    }
    
    /**
     * AJAX: Run cleanup
     */
    public function ajax_run_cleanup() {
        check_ajax_referer('tznew_acf_admin', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        try {
            tznew_run_database_cleanup();
            wp_send_json_success(array(
                'message' => __('Database cleanup completed successfully.', 'tznew')
            ));
        } catch (Exception $e) {
            wp_send_json_error(array(
                'message' => __('Error during cleanup: ', 'tznew') . $e->getMessage()
            ));
        }
    }
    
    /**
     * AJAX: Check integrity
     */
    public function ajax_check_integrity() {
        check_ajax_referer('tznew_acf_admin', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        try {
            tznew_check_database_integrity();
            
            // Generate integrity report
            $report = $this->generate_integrity_report();
            
            wp_send_json_success(array(
                'message' => __('Integrity check completed.', 'tznew'),
                'report' => $report
            ));
        } catch (Exception $e) {
            wp_send_json_error(array(
                'message' => __('Error during integrity check: ', 'tznew') . $e->getMessage()
            ));
        }
    }
    
    /**
     * AJAX: Restore backup
     */
    public function ajax_restore_backup() {
        check_ajax_referer('tznew_acf_admin', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $post_id = intval($_POST['post_id']);
        $timestamp = sanitize_text_field($_POST['timestamp']);
        
        try {
            $result = tznew_restore_backup($post_id, $timestamp);
            
            if ($result) {
                wp_send_json_success(array(
                    'message' => __('Backup restored successfully.', 'tznew')
                ));
            } else {
                wp_send_json_error(array(
                    'message' => __('Failed to restore backup.', 'tznew')
                ));
            }
        } catch (Exception $e) {
            wp_send_json_error(array(
                'message' => __('Error restoring backup: ', 'tznew') . $e->getMessage()
            ));
        }
    }
    
    /**
     * Generate integrity report
     */
    private function generate_integrity_report() {
        global $wpdb;
        
        $report = array();
        
        // Check for missing required fields
        $post_types = array('trekking', 'tours', 'faq', 'blog');
        
        foreach ($post_types as $post_type) {
            $posts = get_posts(array(
                'post_type' => $post_type,
                'post_status' => 'publish',
                'numberposts' => -1,
                'fields' => 'ids'
            ));
            
            $missing_fields = array();
            
            foreach ($posts as $post_id) {
                // Check based on post type
                if ($post_type === 'trekking' || $post_type === 'tours') {
                    $required = array('overview', 'duration');
                    foreach ($required as $field) {
                        $field_value = tznew_get_field_safe($field, $post_id);
                        if (empty($field_value)) {
                            $missing_fields[] = array(
                                'post_id' => $post_id,
                                'field' => $field,
                                'post_title' => get_the_title($post_id)
                            );
                            // Log the missing field for debugging (with rate limiting to prevent spam)
                            $log_key = 'missing_field_' . $post_id . '_' . $field;
                            $last_logged = get_transient($log_key);
                            if (!$last_logged) {
                                error_log(sprintf('Missing required field "%s" for %s post ID %d', $field, $post_type, $post_id));
                                set_transient($log_key, time(), 3600); // Log once per hour
                            }
                        }
                    }
                }
            }
            
            if (!empty($missing_fields)) {
                $report[$post_type] = $missing_fields;
            }
        }
        
        return $report;
    }
}

// Initialize the admin interface
TZnew_ACF_Admin_Interface::get_instance();