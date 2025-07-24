<?php
/**
 * ACF Database Management Admin Interface
 *
 * Provides an admin interface for managing ACF database optimization features.
 *
 * @package TZnew
 * @author Santosh Baral
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class TZnew_ACF_Admin_Interface {
    
    /**
     * @var object|null Database optimizer instance
     */
    private $db_optimizer;
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_acf_cleanup_database', array($this, 'ajax_cleanup_database'));
        add_action('wp_ajax_acf_check_integrity', array($this, 'ajax_check_integrity'));
        add_action('wp_ajax_acf_optimize_database', array($this, 'ajax_optimize_database'));
        add_action('wp_ajax_acf_export_backup', array($this, 'ajax_export_backup'));
        add_action('wp_ajax_acf_restore_backup', array($this, 'ajax_restore_backup'));
        
        // Initialize database optimizer
        $this->db_optimizer = null;
        if (class_exists('TZnew_ACF_Database_Optimizer')) {
            $class_name = 'TZnew_ACF_Database_Optimizer';
            $this->db_optimizer = new $class_name();
        }
    }
    
    public function add_admin_menu() {
        add_management_page(
            'ACF Database Management',
            'ACF Database',
            'manage_options',
            'acf-database-management',
            array($this, 'admin_page')
        );
    }
    
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'tools_page_acf-database-management') {
            return;
        }
        
        // Enqueue CSS
        wp_enqueue_style(
            'tznew-acf-admin-css',
            get_template_directory_uri() . '/assets/css/acf-admin.css',
            array(),
            '1.0.0'
        );
        
        // Enqueue JavaScript
        wp_enqueue_script(
            'tznew-acf-admin',
            get_template_directory_uri() . '/assets/js/acf-admin.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        wp_localize_script('tznew-acf-admin', 'acfAdmin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('acf_admin_nonce')
        ));
    }
    
    public function admin_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        // Handle form submissions
        if (isset($_POST['submit']) && wp_verify_nonce($_POST['acf_admin_nonce'], 'acf_admin_action')) {
            $this->handle_form_submission();
        }
        
        $stats = $this->get_database_stats();
        $settings = get_option('tznew_acf_settings', array());
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="tznew-acf-admin-container">
                <!-- Database Statistics -->
                <div class="tznew-acf-card">
                    <h2>Database Statistics</h2>
                    <div class="tznew-stats-grid">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo esc_html($stats['total_fields']); ?></span>
                            <span class="stat-label">Total ACF Fields</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo esc_html($stats['orphaned_fields']); ?></span>
                            <span class="stat-label">Orphaned Fields</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo esc_html($stats['duplicate_fields']); ?></span>
                            <span class="stat-label">Duplicate Fields</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo esc_html(size_format($stats['database_size'])); ?></span>
                            <span class="stat-label">Database Size</span>
                        </div>
                    </div>
                </div>
                
                <!-- Database Actions -->
                <div class="tznew-acf-card">
                    <h2>Database Actions</h2>
                    <div class="tznew-actions-grid">
                        <button type="button" class="button button-primary" id="cleanup-btn">
                            Clean Database
                        </button>
                        <button type="button" class="button button-secondary" id="integrity-btn">
                            Check Integrity
                        </button>
                        <button type="button" class="button button-secondary" id="optimize-btn">
                            Optimize Database
                        </button>
                        <button type="button" class="button button-secondary" id="export-btn">
                            Export Backup
                        </button>
                    </div>
                    <div id="action-results" class="tznew-results"></div>
                </div>
                
                <!-- Settings -->
                <div class="tznew-acf-card">
                    <h2>Settings</h2>
                    <form method="post" action="">
                        <?php wp_nonce_field('acf_admin_action', 'acf_admin_nonce'); ?>
                        <table class="form-table">
                            <tr>
                                <th scope="row">Auto Cleanup</th>
                                <td>
                                    <select name="auto_cleanup">
                                        <option value="disabled" <?php selected($settings['auto_cleanup'] ?? 'disabled', 'disabled'); ?>>Disabled</option>
                                        <option value="daily" <?php selected($settings['auto_cleanup'] ?? 'disabled', 'daily'); ?>>Daily</option>
                                        <option value="weekly" <?php selected($settings['auto_cleanup'] ?? 'disabled', 'weekly'); ?>>Weekly</option>
                                        <option value="monthly" <?php selected($settings['auto_cleanup'] ?? 'disabled', 'monthly'); ?>>Monthly</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Backup Retention (days)</th>
                                <td>
                                    <input type="number" name="backup_retention" value="<?php echo esc_attr($settings['backup_retention'] ?? 30); ?>" min="1" max="365" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Validation Level</th>
                                <td>
                                    <select name="validation_level">
                                        <option value="basic" <?php selected($settings['validation_level'] ?? 'basic', 'basic'); ?>>Basic</option>
                                        <option value="strict" <?php selected($settings['validation_level'] ?? 'basic', 'strict'); ?>>Strict</option>
                                        <option value="paranoid" <?php selected($settings['validation_level'] ?? 'basic', 'paranoid'); ?>>Paranoid</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <?php submit_button('Save Settings'); ?>
                    </form>
                </div>
                
                <!-- Integrity Report -->
                <div class="tznew-acf-card">
                    <h2>Integrity Report</h2>
                    <button type="button" class="button button-secondary" id="generate-report-btn">
                        Generate Report
                    </button>
                    <div id="integrity-report"></div>
                </div>
                
                <!-- Backup Management -->
                <div class="tznew-acf-card">
                    <h2>Backup Management</h2>
                    <?php $this->render_backup_table(); ?>
                    <div style="margin-top: 15px;">
                        <input type="file" id="backup-file" accept=".sql,.json" style="display: none;" />
                        <button type="button" class="button button-secondary" onclick="document.getElementById('backup-file').click();">
                            Restore from File
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    private function get_database_stats() {
        global $wpdb;
        
        $stats = array(
            'total_fields' => 0,
            'orphaned_fields' => 0,
            'duplicate_fields' => 0,
            'database_size' => 0
        );
        
        // Get total ACF fields
        $total_fields = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key LIKE 'field_%'"
        );
        $stats['total_fields'] = (int) $total_fields;
        
        // Get orphaned fields (fields without corresponding posts)
        $orphaned_fields = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->postmeta} pm 
             LEFT JOIN {$wpdb->posts} p ON pm.post_id = p.ID 
             WHERE pm.meta_key LIKE 'field_%' AND p.ID IS NULL"
        );
        $stats['orphaned_fields'] = (int) $orphaned_fields;
        
        // Get duplicate fields
        $duplicate_fields = $wpdb->get_var(
            "SELECT COUNT(*) - COUNT(DISTINCT meta_key) FROM {$wpdb->postmeta} 
             WHERE meta_key LIKE 'field_%'"
        );
        $stats['duplicate_fields'] = (int) $duplicate_fields;
        
        // Get database size (approximate)
        $database_size = $wpdb->get_var(
            "SELECT SUM(LENGTH(meta_key) + LENGTH(meta_value)) 
             FROM {$wpdb->postmeta} WHERE meta_key LIKE 'field_%'"
        );
        $stats['database_size'] = (int) $database_size;
        
        return $stats;
    }
    
    private function render_backup_table() {
        $backups = $this->get_available_backups();
        
        if (empty($backups)) {
            echo '<p>No backups available.</p>';
            return;
        }
        
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>Date</th><th>Size</th><th>Type</th><th>Actions</th></tr></thead>';
        echo '<tbody>';
        
        foreach ($backups as $backup) {
            echo '<tr>';
            echo '<td>' . esc_html($backup['date']) . '</td>';
            echo '<td>' . esc_html(size_format($backup['size'])) . '</td>';
            echo '<td>' . esc_html($backup['type']) . '</td>';
            echo '<td>';
            echo '<button class="button button-small restore-backup" data-file="' . esc_attr($backup['file']) . '">Restore</button> ';
            echo '<button class="button button-small delete-backup" data-file="' . esc_attr($backup['file']) . '">Delete</button>';
            echo '</td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
    }
    
    private function get_available_backups() {
        $backup_dir = wp_upload_dir()['basedir'] . '/acf-backups/';
        
        if (!is_dir($backup_dir)) {
            return array();
        }
        
        $backups = array();
        $files = glob($backup_dir . '*.{sql,json}', GLOB_BRACE);
        
        foreach ($files as $file) {
            $backups[] = array(
                'file' => basename($file),
                'date' => date('Y-m-d H:i:s', filemtime($file)),
                'size' => filesize($file),
                'type' => pathinfo($file, PATHINFO_EXTENSION)
            );
        }
        
        // Sort by date (newest first)
        usort($backups, function($a, $b) {
            return strcmp($b['date'], $a['date']);
        });
        
        return $backups;
    }
    
    private function handle_form_submission() {
        if (isset($_POST['auto_cleanup'])) {
            $settings = array(
                'auto_cleanup' => sanitize_text_field($_POST['auto_cleanup']),
                'backup_retention' => (int) $_POST['backup_retention'],
                'validation_level' => sanitize_text_field($_POST['validation_level'])
            );
            
            update_option('tznew_acf_settings', $settings);
            
            // Schedule or unschedule auto cleanup
            if ($settings['auto_cleanup'] !== 'disabled') {
                if (!wp_next_scheduled('tznew_acf_auto_cleanup')) {
                    wp_schedule_event(time(), $settings['auto_cleanup'], 'tznew_acf_auto_cleanup');
                }
            } else {
                wp_clear_scheduled_hook('tznew_acf_auto_cleanup');
            }
            
            echo '<div class="notice notice-success"><p>Settings saved successfully!</p></div>';
        }
    }
    
    // AJAX Handlers
    public function ajax_cleanup_database() {
        check_ajax_referer('acf_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        if ($this->db_optimizer) {
            $result = $this->db_optimizer->cleanup_orphaned_fields();
            wp_send_json_success($result);
        } else {
            wp_send_json_error('Database optimizer not available');
        }
    }
    
    public function ajax_check_integrity() {
        check_ajax_referer('acf_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        if ($this->db_optimizer) {
            $result = $this->db_optimizer->check_data_integrity();
            wp_send_json_success($result);
        } else {
            wp_send_json_error('Database optimizer not available');
        }
    }
    
    public function ajax_optimize_database() {
        check_ajax_referer('acf_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        if ($this->db_optimizer) {
            $result = $this->db_optimizer->optimize_queries();
            wp_send_json_success($result);
        } else {
            wp_send_json_error('Database optimizer not available');
        }
    }
    
    public function ajax_export_backup() {
        check_ajax_referer('acf_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        if (class_exists('TZnew_ACF_Backup_Manager')) {
            $class_name = 'TZnew_ACF_Backup_Manager';
            $backup_manager = new $class_name();
            $result = $backup_manager->create_backup();
            wp_send_json_success($result);
        } else {
            wp_send_json_error('Backup manager not available');
        }
    }
    
    public function ajax_restore_backup() {
        check_ajax_referer('acf_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $backup_file = sanitize_text_field($_POST['backup_file']);
        
        if (class_exists('TZnew_ACF_Backup_Manager')) {
            $class_name = 'TZnew_ACF_Backup_Manager';
            $backup_manager = new $class_name();
            $result = $backup_manager->restore_backup($backup_file);
            wp_send_json_success($result);
        } else {
            wp_send_json_error('Backup manager not available');
        }
    }
}

// Initialize the admin interface
if (is_admin()) {
    new TZnew_ACF_Admin_Interface();
}

// Add auto cleanup hook
add_action('tznew_acf_auto_cleanup', function() {
    if (class_exists('TZnew_ACF_Database_Optimizer')) {
        $class_name = 'TZnew_ACF_Database_Optimizer';
        $optimizer = new $class_name();
        if (method_exists($optimizer, 'cleanup_orphaned_fields')) {
            $optimizer->cleanup_orphaned_fields();
        }
        if (method_exists($optimizer, 'remove_duplicate_fields')) {
            $optimizer->remove_duplicate_fields();
        }
    }
});

?>