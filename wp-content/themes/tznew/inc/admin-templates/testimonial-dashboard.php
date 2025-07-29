<?php
/**
 * Testimonial Dashboard Template
 *
 * @package TZnew
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get testimonial statistics
$total_testimonials = wp_count_posts('testimonial')->publish;
$pending_testimonials = wp_count_posts('testimonial')->pending;
$featured_testimonials = get_posts(array(
    'post_type' => 'testimonial',
    'meta_query' => array(
        array(
            'key' => '_featured',
            'value' => '1',
            'compare' => '='
        )
    ),
    'numberposts' => -1
));

// Get recent testimonials
$recent_testimonials = get_posts(array(
    'post_type' => 'testimonial',
    'numberposts' => 5,
    'post_status' => 'publish'
));

// Get sync statistics
global $wpdb;
$sync_logs_table = $wpdb->prefix . 'testimonial_sync_logs';
$recent_syncs = $wpdb->get_results(
    "SELECT platform, sync_date, reviews_imported, status 
     FROM $sync_logs_table 
     ORDER BY sync_date DESC 
     LIMIT 5"
);

// Get average rating
$avg_rating = $wpdb->get_var(
    "SELECT AVG(CAST(meta_value AS DECIMAL(3,2))) 
     FROM {$wpdb->postmeta} pm 
     JOIN {$wpdb->posts} p ON pm.post_id = p.ID 
     WHERE pm.meta_key = '_rating' 
     AND p.post_type = 'testimonial' 
     AND p.post_status = 'publish'"
);
?>

<div class="wrap">
    <h1><?php _e('Testimonial Dashboard', 'tznew'); ?></h1>
    
    <!-- Statistics Cards -->
    <div class="dashboard-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0;">
        <div class="stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #0073aa;">
            <h3 style="margin: 0 0 10px 0; color: #0073aa;"><?php _e('Total Testimonials', 'tznew'); ?></h3>
            <p style="font-size: 2em; margin: 0; font-weight: bold;"><?php echo $total_testimonials; ?></p>
        </div>
        
        <div class="stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #d63638;">
            <h3 style="margin: 0 0 10px 0; color: #d63638;"><?php _e('Pending Review', 'tznew'); ?></h3>
            <p style="font-size: 2em; margin: 0; font-weight: bold;"><?php echo $pending_testimonials; ?></p>
        </div>
        
        <div class="stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #00a32a;">
            <h3 style="margin: 0 0 10px 0; color: #00a32a;"><?php _e('Featured', 'tznew'); ?></h3>
            <p style="font-size: 2em; margin: 0; font-weight: bold;"><?php echo count($featured_testimonials); ?></p>
        </div>
        
        <div class="stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #f0b849;">
            <h3 style="margin: 0 0 10px 0; color: #f0b849;"><?php _e('Average Rating', 'tznew'); ?></h3>
            <p style="font-size: 2em; margin: 0; font-weight: bold;">
                <?php echo $avg_rating ? number_format($avg_rating, 1) : '0.0'; ?>
                <span style="font-size: 0.5em; color: #f0b849;">★</span>
            </p>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="quick-actions" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: 20px 0;">
        <h2><?php _e('Quick Actions', 'tznew'); ?></h2>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="<?php echo admin_url('post-new.php?post_type=testimonial'); ?>" class="button button-primary">
                <?php _e('Add New Testimonial', 'tznew'); ?>
            </a>
            <button id="sync-tripadvisor" class="button button-secondary">
                <?php _e('Sync TripAdvisor Reviews', 'tznew'); ?>
            </button>
            <button id="sync-google" class="button button-secondary">
                <?php _e('Sync Google Reviews', 'tznew'); ?>
            </button>
            <a href="<?php echo admin_url('admin.php?page=testimonial-api-settings'); ?>" class="button">
                <?php _e('API Settings', 'tznew'); ?>
            </a>
        </div>
    </div>
    
    <!-- Two Column Layout -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0;">
        
        <!-- Recent Testimonials -->
        <div class="recent-testimonials" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h2><?php _e('Recent Testimonials', 'tznew'); ?></h2>
            <?php if ($recent_testimonials): ?>
                <div class="testimonials-list">
                    <?php foreach ($recent_testimonials as $testimonial): 
                        $rating = get_post_meta($testimonial->ID, '_rating', true);
                        $customer_name = get_post_meta($testimonial->ID, '_customer_name', true);
                        $source_terms = get_the_terms($testimonial->ID, 'testimonial_source');
                        $source = $source_terms && !is_wp_error($source_terms) ? $source_terms[0]->name : 'Manual';
                    ?>
                        <div class="testimonial-item" style="border-bottom: 1px solid #eee; padding: 15px 0;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div style="flex: 1;">
                                    <h4 style="margin: 0 0 5px 0;">
                                        <a href="<?php echo get_edit_post_link($testimonial->ID); ?>">
                                            <?php echo esc_html($testimonial->post_title); ?>
                                        </a>
                                    </h4>
                                    <p style="margin: 0 0 5px 0; color: #666;">
                                        <?php _e('By:', 'tznew'); ?> <?php echo esc_html($customer_name); ?> | 
                                        <?php _e('Source:', 'tznew'); ?> <?php echo esc_html($source); ?>
                                    </p>
                                    <div style="color: #f0b849;">
                                        <?php echo str_repeat('★', intval($rating)) . str_repeat('☆', 5 - intval($rating)); ?>
                                    </div>
                                </div>
                                <div style="font-size: 12px; color: #999;">
                                    <?php echo get_the_date('M j, Y', $testimonial->ID); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p style="text-align: center; margin-top: 15px;">
                    <a href="<?php echo admin_url('admin.php?page=testimonial-list'); ?>" class="button">
                        <?php _e('View All Testimonials', 'tznew'); ?>
                    </a>
                </p>
            <?php else: ?>
                <p><?php _e('No testimonials found.', 'tznew'); ?></p>
            <?php endif; ?>
        </div>
        
        <!-- Recent Sync Activity -->
        <div class="sync-activity" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h2><?php _e('Recent Sync Activity', 'tznew'); ?></h2>
            <?php if ($recent_syncs): ?>
                <div class="sync-list">
                    <?php foreach ($recent_syncs as $sync): ?>
                        <div class="sync-item" style="border-bottom: 1px solid #eee; padding: 15px 0;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="flex: 1;">
                                    <h4 style="margin: 0 0 5px 0; text-transform: capitalize;">
                                        <?php echo esc_html($sync->platform); ?> Sync
                                    </h4>
                                    <p style="margin: 0; color: #666;">
                                        <?php printf(__('Imported %d reviews', 'tznew'), $sync->reviews_imported); ?>
                                    </p>
                                </div>
                                <div style="text-align: right;">
                                    <span class="status-badge" style="
                                        padding: 4px 8px; 
                                        border-radius: 4px; 
                                        font-size: 12px; 
                                        background: <?php echo $sync->status === 'success' ? '#d4edda' : '#f8d7da'; ?>;
                                        color: <?php echo $sync->status === 'success' ? '#155724' : '#721c24'; ?>;
                                    ">
                                        <?php echo esc_html($sync->status); ?>
                                    </span>
                                    <div style="font-size: 12px; color: #999; margin-top: 5px;">
                                        <?php echo date('M j, Y H:i', strtotime($sync->sync_date)); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p style="text-align: center; margin-top: 15px;">
                    <a href="<?php echo admin_url('admin.php?page=testimonial-sync-logs'); ?>" class="button">
                        <?php _e('View All Sync Logs', 'tznew'); ?>
                    </a>
                </p>
            <?php else: ?>
                <p><?php _e('No sync activity found.', 'tznew'); ?></p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Rating Distribution Chart -->
    <div class="rating-distribution" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: 20px 0;">
        <h2><?php _e('Rating Distribution', 'tznew'); ?></h2>
        <?php
        // Get rating distribution
        $rating_counts = array();
        for ($i = 1; $i <= 5; $i++) {
            $count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) 
                 FROM {$wpdb->postmeta} pm 
                 JOIN {$wpdb->posts} p ON pm.post_id = p.ID 
                 WHERE pm.meta_key = '_rating' 
                 AND pm.meta_value = %s 
                 AND p.post_type = 'testimonial' 
                 AND p.post_status = 'publish'",
                $i
            ));
            $rating_counts[$i] = intval($count);
        }
        $max_count = max($rating_counts) ?: 1;
        ?>
        <div class="rating-bars" style="margin-top: 20px;">
            <?php for ($i = 5; $i >= 1; $i--): 
                $count = $rating_counts[$i];
                $percentage = ($count / $max_count) * 100;
            ?>
                <div class="rating-bar" style="display: flex; align-items: center; margin-bottom: 10px;">
                    <div style="width: 60px; text-align: right; margin-right: 10px;">
                        <?php echo $i; ?> ★
                    </div>
                    <div style="flex: 1; background: #f0f0f0; height: 20px; border-radius: 10px; overflow: hidden;">
                        <div style="width: <?php echo $percentage; ?>%; height: 100%; background: linear-gradient(90deg, #f0b849, #f39c12); transition: width 0.3s ease-in-out;"></div>
                    </div>
                    <div style="width: 40px; text-align: left; margin-left: 10px; font-weight: bold;">
                        <?php echo $count; ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Sync TripAdvisor reviews
    $('#sync-tripadvisor').on('click', function(e) {
        e.preventDefault();
        const button = $(this);
        const originalText = button.text();
        
        button.text('<?php _e('Syncing...', 'tznew'); ?>').prop('disabled', true);
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'sync_tripadvisor_reviews',
                nonce: '<?php echo wp_create_nonce('testimonial_ajax_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    showNotice('TripAdvisor reviews synced successfully!', 'success');
                    refreshStats();
                } else {
                    showNotice(response.data || 'Sync failed', 'error');
                }
            },
            error: function() {
                showNotice('Sync failed. Please try again.', 'error');
            },
            complete: function() {
                button.text(originalText).prop('disabled', false);
            }
        });
    });
    
    // Sync Google reviews
    $('#sync-google').on('click', function(e) {
        e.preventDefault();
        const button = $(this);
        const originalText = button.text();
        
        button.text('<?php _e('Syncing...', 'tznew'); ?>').prop('disabled', true);
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'sync_google_reviews',
                nonce: '<?php echo wp_create_nonce('testimonial_ajax_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    showNotice('Google reviews synced successfully!', 'success');
                    refreshStats();
                } else {
                    showNotice(response.data || 'Sync failed', 'error');
                }
            },
            error: function() {
                showNotice('Sync failed. Please try again.', 'error');
            },
            complete: function() {
                button.text(originalText).prop('disabled', false);
            }
        });
    });
    
    // Refresh statistics
    function refreshStats() {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'get_testimonial_stats',
                nonce: '<?php echo wp_create_nonce('testimonial_ajax_nonce'); ?>'
            },
            success: function(response) {
                if (response.success && response.data) {
                    $('.stat-number').each(function() {
                        const statType = $(this).data('stat');
                        if (response.data[statType]) {
                            $(this).text(response.data[statType]);
                        }
                    });
                }
            }
        });
    }
    
    // Show notice function
    function showNotice(message, type) {
        const notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
        $('.wrap h1').after(notice);
        setTimeout(function() {
            notice.fadeOut();
        }, 5000);
    }
});
</script>