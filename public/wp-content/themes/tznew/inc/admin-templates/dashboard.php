<?php
/**
 * Admin Dashboard Template for Booking Management
 *
 * @package TZnew
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap booking-dashboard-wrap">
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <span class="dashicons dashicons-chart-area"></span>
            <?php _e('Booking Management Dashboard', 'tznew'); ?>
        </h1>
        <div class="dashboard-subtitle">
            <?php _e('Monitor your bookings, inquiries, and system performance', 'tznew'); ?>
        </div>
    </div>
    
    <div class="booking-dashboard">
        <!-- Statistics Cards -->
        <div class="dashboard-stats">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <span class="dashicons dashicons-calendar-alt"></span>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title"><?php _e('Total Bookings', 'tznew'); ?></h3>
                    <div class="stat-number"><?php echo esc_html($total_bookings); ?></div>
                    <p class="stat-description"><?php _e('All time bookings', 'tznew'); ?></p>
                </div>
            </div>
            
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <span class="dashicons dashicons-clock"></span>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title"><?php _e('Pending Bookings', 'tznew'); ?></h3>
                    <div class="stat-number"><?php echo esc_html(count($pending_bookings)); ?></div>
                    <p class="stat-description"><?php _e('Awaiting review', 'tznew'); ?></p>
                </div>
            </div>
            
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <span class="dashicons dashicons-email"></span>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title"><?php _e('Total Inquiries', 'tznew'); ?></h3>
                    <div class="stat-number"><?php echo esc_html($total_inquiries); ?></div>
                    <p class="stat-description"><?php _e('All time inquiries', 'tznew'); ?></p>
                </div>
            </div>
            
            <div class="stat-card stat-card-danger">
                <div class="stat-icon">
                    <span class="dashicons dashicons-bell"></span>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title"><?php _e('New Inquiries', 'tznew'); ?></h3>
                    <div class="stat-number"><?php echo esc_html(count($new_inquiries)); ?></div>
                    <p class="stat-description"><?php _e('Require attention', 'tznew'); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="dashboard-actions">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="dashicons dashicons-admin-tools"></span>
                    <?php _e('Quick Actions', 'tznew'); ?>
                </h2>
                <p class="section-description"><?php _e('Access frequently used features', 'tznew'); ?></p>
            </div>
            <div class="action-buttons">
                <a href="<?php echo admin_url('edit.php?post_type=booking'); ?>" class="action-button action-button-primary">
                    <div class="action-icon">
                        <span class="dashicons dashicons-calendar-alt"></span>
                    </div>
                    <div class="action-content">
                        <span class="action-title"><?php _e('View All Bookings', 'tznew'); ?></span>
                        <span class="action-subtitle"><?php _e('Manage customer bookings', 'tznew'); ?></span>
                    </div>
                </a>
                <a href="<?php echo admin_url('edit.php?post_type=inquiry'); ?>" class="action-button action-button-secondary">
                    <div class="action-icon">
                        <span class="dashicons dashicons-email"></span>
                    </div>
                    <div class="action-content">
                        <span class="action-title"><?php _e('View All Inquiries', 'tznew'); ?></span>
                        <span class="action-subtitle"><?php _e('Review customer inquiries', 'tznew'); ?></span>
                    </div>
                </a>
                <a href="<?php echo admin_url('admin.php?page=booking-settings'); ?>" class="action-button action-button-tertiary">
                    <div class="action-icon">
                        <span class="dashicons dashicons-admin-settings"></span>
                    </div>
                    <div class="action-content">
                        <span class="action-title"><?php _e('Settings', 'tznew'); ?></span>
                        <span class="action-subtitle"><?php _e('Configure system settings', 'tznew'); ?></span>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="dashboard-activity">
            <div class="activity-tabs-container">
                <!-- Tab Navigation -->
                <div class="activity-tabs-nav">
                    <button class="activity-tab-btn active" data-tab="bookings">
                        <span class="dashicons dashicons-calendar-alt"></span>
                        <?php _e('Recent Bookings', 'tznew'); ?>
                        <span class="tab-count"><?php echo count($recent_bookings ?? []); ?></span>
                    </button>
                    <button class="activity-tab-btn" data-tab="inquiries">
                        <span class="dashicons dashicons-email"></span>
                        <?php _e('Recent Inquiries', 'tznew'); ?>
                        <span class="tab-count"><?php echo count($recent_inquiries ?? []); ?></span>
                    </button>
                </div>
                
                <!-- Tab Content -->
                <div class="activity-tabs-content">
                    <!-- Recent Bookings Tab -->
                    <div class="activity-tab-panel active" id="bookings-panel">
                        <?php
                        $recent_bookings = get_posts(array(
                            'post_type' => 'booking',
                            'numberposts' => 5,
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));
                        
                        if ($recent_bookings) {
                            echo '<div class="activity-list">';
                            foreach ($recent_bookings as $booking) {
                                $first_name = get_post_meta($booking->ID, '_first_name', true);
                                $last_name = get_post_meta($booking->ID, '_last_name', true);
                                $trip_title = get_post_meta($booking->ID, '_trip_title', true);
                                $status_terms = wp_get_post_terms($booking->ID, 'booking_status');
                                $status = !empty($status_terms) ? $status_terms[0]->name : 'Pending';
                                $status_slug = !empty($status_terms) ? $status_terms[0]->slug : 'pending';
                                
                                echo '<div class="activity-item">';
                                echo '<div class="activity-content">';
                                echo '<h4 class="activity-title">' . esc_html($first_name . ' ' . $last_name) . '</h4>';
                                echo '<p class="activity-meta">' . esc_html($trip_title) . '</p>';
                                echo '<p class="activity-date">' . esc_html(date('M j, Y', strtotime($booking->post_date))) . '</p>';
                                echo '</div>';
                                echo '<div class="activity-status">';
                                echo '<span class="status-badge status-' . $status_slug . '">' . esc_html($status) . '</span>';
                                echo '</div>';
                                echo '</div>';
                            }
                            echo '</div>';
                            echo '<div class="tab-footer">';
                            echo '<a href="' . admin_url('edit.php?post_type=booking') . '" class="view-all-btn">' . __('View All Bookings', 'tznew') . '</a>';
                            echo '</div>';
                        } else {
                            echo '<div class="empty-state">';
                            echo '<span class="dashicons dashicons-calendar-alt"></span>';
                            echo '<h3>' . __('No bookings yet', 'tznew') . '</h3>';
                            echo '<p>' . __('Bookings will appear here once customers start making reservations.', 'tznew') . '</p>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                    
                    <!-- Recent Inquiries Tab -->
                    <div class="activity-tab-panel" id="inquiries-panel">
                        <?php
                        $recent_inquiries = get_posts(array(
                            'post_type' => 'inquiry',
                            'numberposts' => 5,
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));
                        
                        if ($recent_inquiries) {
                            echo '<div class="activity-list">';
                            foreach ($recent_inquiries as $inquiry) {
                                $name = get_post_meta($inquiry->ID, '_inquiry_name', true);
                                $subject = get_post_meta($inquiry->ID, '_inquiry_subject', true);
                                $urgency = get_post_meta($inquiry->ID, '_response_urgency', true);
                                $status_terms = wp_get_post_terms($inquiry->ID, 'inquiry_status');
                                $status = !empty($status_terms) ? $status_terms[0]->name : 'New';
                                $status_slug = !empty($status_terms) ? $status_terms[0]->slug : 'new';
                                
                                echo '<div class="activity-item">';
                                echo '<div class="activity-content">';
                                echo '<h4 class="activity-title">' . esc_html($name) . '</h4>';
                                echo '<p class="activity-meta">' . esc_html(ucfirst(str_replace('_', ' ', $subject))) . '</p>';
                                echo '<p class="activity-date">' . esc_html(date('M j, Y', strtotime($inquiry->post_date))) . '</p>';
                                echo '</div>';
                                echo '<div class="activity-status">';
                                echo '<span class="status-badge status-' . $status_slug . '">' . esc_html($status) . '</span>';
                                echo '</div>';
                                echo '</div>';
                            }
                            echo '</div>';
                            echo '<div class="tab-footer">';
                            echo '<a href="' . admin_url('edit.php?post_type=inquiry') . '" class="view-all-btn">' . __('View All Inquiries', 'tznew') . '</a>';
                            echo '</div>';
                        } else {
                            echo '<div class="empty-state">';
                            echo '<span class="dashicons dashicons-email"></span>';
                            echo '<h3>' . __('No inquiries yet', 'tznew') . '</h3>';
                            echo '<p>' . __('Customer inquiries will appear here when they contact you.', 'tznew') . '</p>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- System Status -->
        <div class="system-status">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="dashicons dashicons-admin-tools"></span>
                    <?php _e('System Status', 'tznew'); ?>
                </h2>
                <p class="section-description"><?php _e('Monitor system health and performance', 'tznew'); ?></p>
            </div>
            <div class="status-grid">
                <div class="status-item status-operational">
                    <div class="status-icon">
                        <span class="dashicons dashicons-email"></span>
                    </div>
                    <div class="status-content">
                        <h4 class="status-title"><?php _e('Email System', 'tznew'); ?></h4>
                        <span class="status-label status-success"><?php _e('Operational', 'tznew'); ?></span>
                    </div>
                </div>
                <div class="status-item status-connected">
                    <div class="status-icon">
                        <span class="dashicons dashicons-database"></span>
                    </div>
                    <div class="status-content">
                        <h4 class="status-title"><?php _e('Database', 'tznew'); ?></h4>
                        <span class="status-label status-success"><?php _e('Connected', 'tznew'); ?></span>
                    </div>
                </div>
                <div class="status-item status-active">
                    <div class="status-icon">
                        <span class="dashicons dashicons-forms"></span>
                    </div>
                    <div class="status-content">
                        <h4 class="status-title"><?php _e('Form Processing', 'tznew'); ?></h4>
                        <span class="status-label status-success"><?php _e('Active', 'tznew'); ?></span>
                    </div>
                </div>
                <div class="status-item status-enabled">
                    <div class="status-icon">
                        <span class="dashicons dashicons-bell"></span>
                    </div>
                    <div class="status-content">
                        <h4 class="status-title"><?php _e('Notifications', 'tznew'); ?></h4>
                        <span class="status-label status-success"><?php _e('Enabled', 'tznew'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard Layout */
.booking-dashboard-wrap {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 32px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    min-height: 100vh;
    position: relative;
}

.booking-dashboard-wrap::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.1) 0%, transparent 50%);
    pointer-events: none;
    z-index: 0;
}

/* Header Styles */
.dashboard-header {
    background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
    color: #ffffff;
    padding: 60px 40px;
    margin: -20px -32px 48px -32px;
    border-radius: 0 0 32px 32px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(30, 41, 59, 0.4);
    z-index: 1;
}

.dashboard-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.05) 50%, transparent 70%);
    animation: shimmer 3s ease-in-out infinite;
}

@keyframes shimmer {
    0%, 100% { transform: translateX(-100%); }
    50% { transform: translateX(100%); }
}

.dashboard-title {
    font-size: 2.75rem;
    font-weight: 900;
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 20px;
    color: #ffffff;
    letter-spacing: -0.03em;
    position: relative;
    z-index: 2;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.dashboard-title .dashicons {
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    color: #ffffff;
    font-size: 2rem;
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.dashboard-subtitle {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.85);
    margin: 0;
    font-weight: 500;
    line-height: 1.6;
    position: relative;
    z-index: 2;
    opacity: 0.9;
}

/* Statistics Cards */
.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 32px;
    margin-bottom: 56px;
    position: relative;
    z-index: 1;
}

.stat-card {
    background: rgba(255, 255, 255, 0.95);
    padding: 40px;
    border-radius: 24px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(20px);
}

.stat-card:hover {
    transform: translateY(-12px) scale(1.03);
    box-shadow: 0 24px 80px rgba(0, 0, 0, 0.15);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    border-radius: 24px 24px 0 0;
}

.stat-card-warning::before {
    background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
}

.stat-card-success::before {
    background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
}

.stat-card-danger::before {
    background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
}

.stat-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    color: white;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 28px;
    box-shadow: 0 12px 32px rgba(59, 130, 246, 0.3);
    position: relative;
    overflow: hidden;
}

.stat-icon::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 20px;
}

.stat-card-warning .stat-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
    box-shadow: 0 12px 32px rgba(245, 158, 11, 0.3);
}

.stat-card-success .stat-icon {
    background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
    box-shadow: 0 12px 32px rgba(16, 185, 129, 0.3);
}

.stat-card-danger .stat-icon {
    background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
    box-shadow: 0 12px 32px rgba(239, 68, 68, 0.3);
}

.stat-icon .dashicons {
    font-size: 2.25rem;
    position: relative;
    z-index: 1;
}

.stat-content {
    position: relative;
}

.stat-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
    margin: 0 0 16px 0;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    opacity: 0.7;
}

.stat-number {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 12px;
    display: block;
    line-height: 1;
    color: #1e293b;
    letter-spacing: -0.02em;
}

.stat-description {
    font-size: 0.95rem;
    color: #64748b;
    margin: 0;
    font-weight: 500;
    opacity: 0.8;
    line-height: 1.4;
}

/* Section Headers */
.section-header {
    margin-bottom: 40px;
    position: relative;
}

.section-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 20px;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -12px;
    left: 0;
    width: 80px;
    height: 4px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    border-radius: 2px;
}

.section-title .dashicons {
    font-size: 1.75rem;
    color: #3b82f6;
}

.section-description {
    color: #64748b;
    margin: 0;
    font-size: 1.125rem;
    font-weight: 500;
    opacity: 0.8;
    line-height: 1.6;
}

/* Card Styles */
.dashboard-actions,
.activity-card,
.system-status {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 28px;
    padding: 48px;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.6);
    margin-bottom: 40px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(20px);
    position: relative;
    overflow: hidden;
}

.dashboard-actions::before,
.activity-card::before,
.system-status::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    border-radius: 28px 28px 0 0;
}

.dashboard-actions:hover,
.activity-card:hover,
.system-status:hover {
    box-shadow: 0 24px 80px rgba(0, 0, 0, 0.15);
    transform: translateY(-8px) scale(1.01);
}

/* Action Buttons */
.action-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 32px;
}

.action-button {
    display: flex;
    align-items: center;
    gap: 24px;
    padding: 40px;
    border-radius: 24px;
    text-decoration: none;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.6);
    color: #1e293b;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    backdrop-filter: blur(20px);
}

.action-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    border-radius: 24px 24px 0 0;
}

.action-button:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 24px 80px rgba(0, 0, 0, 0.15);
    border-color: rgba(59, 130, 246, 0.3);
    background: rgba(255, 255, 255, 1);
}

.action-icon {
    width: 72px;
    height: 72px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    color: white;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 12px 32px rgba(59, 130, 246, 0.3);
    position: relative;
    overflow: hidden;
}

.action-icon::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 20px;
}

.action-icon .dashicons {
    font-size: 2rem;
    position: relative;
    z-index: 1;
}

.action-content {
    flex: 1;
}

.action-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0 0 12px 0;
    display: block;
    color: #1e293b;
    line-height: 1.3;
}

.action-subtitle {
    font-size: 1rem;
    color: #64748b;
    margin: 0;
    display: block;
    font-weight: 500;
    opacity: 0.8;
    line-height: 1.5;
}

/* Activity Layout */
.dashboard-activity {
    margin-bottom: 30px;
}

.activity-tabs-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.activity-tabs-nav {
    display: flex;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.activity-tab-btn {
    flex: 1;
    padding: 20px 30px;
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.7);
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.activity-tab-btn:hover {
    color: rgba(255, 255, 255, 0.9);
    background: rgba(255, 255, 255, 0.1);
}

.activity-tab-btn.active {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.15);
}

.activity-tab-btn.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #ff6b6b, #feca57);
    border-radius: 3px 3px 0 0;
}

.activity-tab-btn .dashicons {
    font-size: 18px;
}

.tab-count {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    min-width: 20px;
    text-align: center;
}

.activity-tabs-content {
    position: relative;
}

.activity-tab-panel {
    display: none;
    padding: 30px;
    animation: fadeInUp 0.4s ease;
}

.activity-tab-panel.active {
    display: block;
}

.tab-footer {
    text-align: center;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.view-all-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.view-all-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: #ffffff;
    text-decoration: none;
}

/* Activity Lists */
.activity-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 24px;
    padding: 32px 0;
    border-bottom: 1px solid rgba(241, 245, 249, 0.4);
    position: relative;
    transition: all 0.3s ease;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(10px);
}

.activity-item::before {
    content: '';
    position: absolute;
    left: -40px;
    top: 0;
    bottom: 0;
    width: 0;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    border-radius: 3px;
    transition: width 0.3s ease;
}

.activity-item:hover::before {
    width: 5px;
}

.activity-item:hover {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 16px;
    margin: 0 -24px;
    padding: 32px 24px;
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 12px 0;
    line-height: 1.4;
}

.activity-meta {
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 1rem;
    color: #64748b;
    font-weight: 500;
    line-height: 1.5;
}

.activity-date {
    color: #94a3b8;
    font-weight: 600;
    font-size: 0.9rem;
}

.activity-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
    flex-shrink: 0;
}

.activity-status::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
    box-shadow: 0 0 8px currentColor;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 12px;
    font-size: 0.8125rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.status-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.status-pending {
    background: rgba(245, 158, 11, 0.15);
    color: #d97706;
    border-color: rgba(245, 158, 11, 0.3);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
}

.status-confirmed {
    background: rgba(16, 185, 129, 0.15);
    color: #059669;
    border-color: rgba(16, 185, 129, 0.3);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}

.status-cancelled {
    background: rgba(239, 68, 68, 0.15);
    color: #dc2626;
    border-color: rgba(239, 68, 68, 0.3);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
}

.status-completed {
    background: rgba(139, 92, 246, 0.15);
    color: #7c3aed;
    border-color: rgba(139, 92, 246, 0.3);
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
}

.status-new {
    background: rgba(59, 130, 246, 0.15);
    color: #2563eb;
    border-color: rgba(59, 130, 246, 0.3);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.status-replied {
    background: rgba(6, 182, 212, 0.15);
    color: #0891b2;
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
}

.status-closed {
    background: rgba(107, 114, 128, 0.15);
    color: #4b5563;
    border-color: rgba(107, 114, 128, 0.3);
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.2);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 100px 48px;
    color: #64748b;
    background: rgba(255, 255, 255, 0.9);
    border: 2px dashed rgba(226, 232, 240, 0.6);
    border-radius: 24px;
    backdrop-filter: blur(15px);
    margin: 48px 0;
    transition: all 0.3s ease;
}

.empty-state:hover {
    background: rgba(255, 255, 255, 1);
    border-color: rgba(59, 130, 246, 0.3);
    transform: translateY(-4px);
    box-shadow: 0 16px 48px rgba(0, 0, 0, 0.1);
}

.empty-state .dashicons {
    font-size: 2.5rem;
    background: linear-gradient(135deg, #cbd5e1 0%, #94a3b8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 32px;
    display: block;
    width: 96px;
    height: 96px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 32px;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
    color: #94a3b8;
}

.empty-state h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 16px 0;
    line-height: 1.3;
}

.empty-state p {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 500;
    opacity: 0.8;
    line-height: 1.5;
}

/* System Status */
.status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 24px;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px;
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.status-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
}

.status-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.status-icon .dashicons {
    font-size: 1.25rem;
}

.status-icon.status-good {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #16a34a;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.status-icon.status-warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #d97706;
    box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
}

.status-icon.status-error {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #dc2626;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.status-content {
    flex: 1;
}

.status-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 4px 0;
}

.status-label {
    font-size: 0.8rem;
    font-weight: 500;
}

.status-success {
    color: #059669;
}

.status-warning {
    color: #d97706;
}

.status-error {
    color: #dc2626;
}

/* Button Styles */
.booking-dashboard .button {
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    padding: 10px 20px;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.booking-dashboard .button:hover {
    background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
}

.booking-dashboard .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes shimmer {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

.booking-dashboard > * {
    animation: fadeInUp 0.6s ease-out;
}

.booking-dashboard > *:nth-child(2) { animation-delay: 0.1s; }
.booking-dashboard > *:nth-child(3) { animation-delay: 0.2s; }
.booking-dashboard > *:nth-child(4) { animation-delay: 0.3s; }
.booking-dashboard > *:nth-child(5) { animation-delay: 0.4s; }

/* Responsive Design */
/* Large screens optimization */
@media (min-width: 1400px) {
    .booking-dashboard-wrap {
        max-width: 1400px;
        padding: 0 40px;
    }
    
    .dashboard-stats {
        grid-template-columns: repeat(4, 1fr);
        gap: 28px;
    }
    
    .action-buttons {
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }
    
    .dashboard-activity {
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }
    
    .status-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }
}

@media (min-width: 1600px) {
    .booking-dashboard-wrap {
        max-width: 1600px;
        padding: 0 60px;
    }
    
    .dashboard-stats {
        grid-template-columns: repeat(4, 1fr);
        gap: 32px;
    }
    
    .dashboard-activity {
        grid-template-columns: 2fr 1fr;
    }
    
    .activity-card {
        min-height: 400px;
    }
}

@media (min-width: 1920px) {
    .booking-dashboard-wrap {
        max-width: 1800px;
        padding: 0 80px;
    }
    
    .dashboard-header {
        padding: 60px 40px;
    }
    
    .dashboard-title {
        font-size: 3rem;
    }
    
    .dashboard-subtitle {
        font-size: 1.375rem;
    }
    
    .stat-card {
        padding: 48px;
    }
    
    .dashboard-actions,
    .activity-card,
    .system-status {
        padding: 56px;
    }
}

@media (max-width: 1024px) {
    .activity-tab-btn {
        padding: 16px 20px;
        font-size: 14px;
    }
    
    .activity-tab-btn .dashicons {
        font-size: 16px;
    }
    
    .activity-tab-panel {
        padding: 24px;
    }
    
    .action-buttons {
        grid-template-columns: 1fr;
    }
    
    .dashboard-stats {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
    }
}

@media (max-width: 768px) {
    .booking-dashboard-wrap {
        padding: 0 20px;
    }
    
    .activity-tabs-nav {
        flex-direction: column;
    }
    
    .activity-tab-btn {
        padding: 16px 24px;
        font-size: 14px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .activity-tab-btn:last-child {
        border-bottom: none;
    }
    
    .activity-tab-btn.active::after {
        height: 3px;
        left: 0;
        right: auto;
        width: 4px;
        top: 0;
        bottom: 0;
        border-radius: 0 3px 3px 0;
    }
    
    .activity-tab-panel {
        padding: 20px;
    }
    
    .tab-count {
        font-size: 11px;
        padding: 3px 6px;
    }
    
    .booking-dashboard .dashboard-stats {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .status-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .dashboard-actions,
    .activity-card,
    .system-status {
        padding: 32px;
    }
    
    .dashboard-header {
        padding: 40px 20px;
        text-align: center;
    }
    
    .dashboard-title {
        font-size: 2.25rem;
    }
    
    .dashboard-subtitle {
        font-size: 1.125rem;
    }
    
    .action-button {
        padding: 32px;
    }
    
    .action-icon {
        width: 64px;
        height: 64px;
    }
    
    .action-icon .dashicons {
        font-size: 1.75rem;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
    
    .empty-state {
        padding: 60px 32px;
    }
}

@media (max-width: 480px) {
    .activity-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
        padding: 24px 0;
    }
    
    .action-button {
        flex-direction: column;
        text-align: center;
        gap: 16px;
        padding: 24px;
    }
    
    .dashboard-header {
        padding: 32px 16px;
    }
    
    .dashboard-title {
        font-size: 1.875rem;
        flex-direction: column;
        gap: 12px;
    }
    
    .dashboard-subtitle {
        font-size: 1rem;
    }
    
    .stat-card {
        padding: 24px;
    }
    
    .dashboard-actions,
    .activity-card,
    .system-status {
        padding: 24px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.activity-tab-btn');
    const tabPanels = document.querySelectorAll('.activity-tab-panel');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panels
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Show corresponding panel
            const targetPanel = document.getElementById(targetTab + '-panel');
            if (targetPanel) {
                targetPanel.classList.add('active');
            }
        });
    });
    
    // Add smooth scroll animation for better UX
    const activityItems = document.querySelectorAll('.activity-item');
    activityItems.forEach((item, index) => {
        item.style.animationDelay = (index * 0.1) + 's';
    });
});
</script>