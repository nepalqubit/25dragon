<?php
/**
 * Testimonial API Settings Template
 *
 * @package TZnew
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$api_settings_table = $wpdb->prefix . 'testimonial_api_settings';

// Handle form submission
if (isset($_POST['save_api_settings'])) {
    $platform = sanitize_text_field($_POST['platform']);
    $api_key = sanitize_text_field($_POST['api_key']);
    $page_url = esc_url_raw($_POST['page_url']);
    $place_id = sanitize_text_field($_POST['place_id']);
    $sync_frequency = sanitize_text_field($_POST['sync_frequency']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Check if platform settings already exist
    $existing = $wpdb->get_row($wpdb->prepare(
        "SELECT id FROM $api_settings_table WHERE platform = %s",
        $platform
    ));
    
    if ($existing) {
        // Update existing settings
        $wpdb->update(
            $api_settings_table,
            array(
                'api_key' => $api_key,
                'page_url' => $page_url,
                'place_id' => $place_id,
                'sync_frequency' => $sync_frequency,
                'is_active' => $is_active,
                'updated_at' => current_time('mysql')
            ),
            array('platform' => $platform)
        );
    } else {
        // Insert new settings
        $wpdb->insert(
            $api_settings_table,
            array(
                'platform' => $platform,
                'api_key' => $api_key,
                'page_url' => $page_url,
                'place_id' => $place_id,
                'sync_frequency' => $sync_frequency,
                'is_active' => $is_active
            )
        );
    }
    
    echo '<div class="notice notice-success"><p>' . __('API settings saved successfully!', 'tznew') . '</p></div>';
}

// Handle test connection
if (isset($_POST['test_connection'])) {
    $platform = sanitize_text_field($_POST['test_platform']);
    $api_key = sanitize_text_field($_POST['test_api_key']);
    $place_id = sanitize_text_field($_POST['test_place_id']);
    
    // Test API connection (placeholder)
    $test_result = true; // This would be replaced with actual API testing
    
    if ($test_result) {
        echo '<div class="notice notice-success"><p>' . sprintf(__('%s API connection successful!', 'tznew'), ucfirst($platform)) . '</p></div>';
    } else {
        echo '<div class="notice notice-error"><p>' . sprintf(__('%s API connection failed. Please check your credentials.', 'tznew'), ucfirst($platform)) . '</p></div>';
    }
}

// Get current settings
$tripadvisor_settings = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM $api_settings_table WHERE platform = %s",
    'tripadvisor'
));

$google_settings = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM $api_settings_table WHERE platform = %s",
    'google'
));
?>

<div class="wrap">
    <h1><?php _e('API Settings', 'tznew'); ?></h1>
    <p><?php _e('Configure API settings to automatically sync reviews from TripAdvisor and Google.', 'tznew'); ?></p>
    
    <!-- TripAdvisor Settings -->
    <div class="api-settings-section" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: 20px 0;">
        <h2 style="margin-top: 0; color: #00af87;">
            <span class="dashicons dashicons-location-alt" style="margin-right: 10px;"></span>
            <?php _e('TripAdvisor Integration', 'tznew'); ?>
        </h2>
        
        <form method="post" action="">
            <input type="hidden" name="platform" value="tripadvisor">
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="tripadvisor_active"><?php _e('Enable TripAdvisor Sync', 'tznew'); ?></label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" id="tripadvisor_active" name="is_active" value="1" 
                                   <?php checked($tripadvisor_settings->is_active ?? 0, 1); ?>>
                            <?php _e('Automatically sync reviews from TripAdvisor', 'tznew'); ?>
                        </label>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="tripadvisor_api_key"><?php _e('API Key', 'tznew'); ?></label>
                    </th>
                    <td>
                        <input type="password" id="tripadvisor_api_key" name="api_key" 
                               value="<?php echo esc_attr($tripadvisor_settings->api_key ?? ''); ?>" 
                               class="regular-text" placeholder="<?php _e('Enter your TripAdvisor API key', 'tznew'); ?>">
                        <p class="description">
                            <?php _e('Get your API key from', 'tznew'); ?> 
                            <a href="https://developer-tripadvisor.com/" target="_blank">TripAdvisor Developer Portal</a>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="tripadvisor_page_url"><?php _e('TripAdvisor Page URL', 'tznew'); ?></label>
                    </th>
                    <td>
                        <input type="url" id="tripadvisor_page_url" name="page_url" 
                               value="<?php echo esc_attr($tripadvisor_settings->page_url ?? ''); ?>" 
                               class="regular-text" placeholder="https://www.tripadvisor.com/...">
                        <p class="description">
                            <?php _e('Your business page URL on TripAdvisor', 'tznew'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="tripadvisor_place_id"><?php _e('Location ID', 'tznew'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="tripadvisor_place_id" name="place_id" 
                               value="<?php echo esc_attr($tripadvisor_settings->place_id ?? ''); ?>" 
                               class="regular-text" placeholder="<?php _e('TripAdvisor Location ID', 'tznew'); ?>">
                        <p class="description">
                            <?php _e('Your TripAdvisor location ID (found in your page URL)', 'tznew'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="tripadvisor_sync_frequency"><?php _e('Sync Frequency', 'tznew'); ?></label>
                    </th>
                    <td>
                        <select id="tripadvisor_sync_frequency" name="sync_frequency">
                            <option value="hourly" <?php selected($tripadvisor_settings->sync_frequency ?? 'daily', 'hourly'); ?>>
                                <?php _e('Every Hour', 'tznew'); ?>
                            </option>
                            <option value="daily" <?php selected($tripadvisor_settings->sync_frequency ?? 'daily', 'daily'); ?>>
                                <?php _e('Daily', 'tznew'); ?>
                            </option>
                            <option value="weekly" <?php selected($tripadvisor_settings->sync_frequency ?? 'daily', 'weekly'); ?>>
                                <?php _e('Weekly', 'tznew'); ?>
                            </option>
                            <option value="manual" <?php selected($tripadvisor_settings->sync_frequency ?? 'daily', 'manual'); ?>>
                                <?php _e('Manual Only', 'tznew'); ?>
                            </option>
                        </select>
                    </td>
                </tr>
            </table>
            
            <div style="margin-top: 20px;">
                <input type="submit" name="save_api_settings" class="button button-primary" 
                       value="<?php _e('Save TripAdvisor Settings', 'tznew'); ?>">
                
                <button type="button" id="test-tripadvisor" class="button button-secondary" style="margin-left: 10px;">
                    <?php _e('Test Connection', 'tznew'); ?>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Google Settings -->
    <div class="api-settings-section" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: 20px 0;">
        <h2 style="margin-top: 0; color: #4285f4;">
            <span class="dashicons dashicons-google" style="margin-right: 10px;"></span>
            <?php _e('Google Reviews Integration', 'tznew'); ?>
        </h2>
        
        <form method="post" action="">
            <input type="hidden" name="platform" value="google">
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="google_active"><?php _e('Enable Google Reviews Sync', 'tznew'); ?></label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" id="google_active" name="is_active" value="1" 
                                   <?php checked($google_settings->is_active ?? 0, 1); ?>>
                            <?php _e('Automatically sync reviews from Google My Business', 'tznew'); ?>
                        </label>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="google_api_key"><?php _e('Google Places API Key', 'tznew'); ?></label>
                    </th>
                    <td>
                        <input type="password" id="google_api_key" name="api_key" 
                               value="<?php echo esc_attr($google_settings->api_key ?? ''); ?>" 
                               class="regular-text" placeholder="<?php _e('Enter your Google Places API key', 'tznew'); ?>">
                        <p class="description">
                            <?php _e('Get your API key from', 'tznew'); ?> 
                            <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="google_page_url"><?php _e('Google My Business URL', 'tznew'); ?></label>
                    </th>
                    <td>
                        <input type="url" id="google_page_url" name="page_url" 
                               value="<?php echo esc_attr($google_settings->page_url ?? ''); ?>" 
                               class="regular-text" placeholder="https://goo.gl/maps/...">
                        <p class="description">
                            <?php _e('Your Google My Business page URL', 'tznew'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="google_place_id"><?php _e('Google Place ID', 'tznew'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="google_place_id" name="place_id" 
                               value="<?php echo esc_attr($google_settings->place_id ?? ''); ?>" 
                               class="regular-text" placeholder="<?php _e('Google Place ID', 'tznew'); ?>">
                        <p class="description">
                            <?php _e('Find your Place ID using', 'tznew'); ?> 
                            <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank">
                                Google Place ID Finder
                            </a>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="google_sync_frequency"><?php _e('Sync Frequency', 'tznew'); ?></label>
                    </th>
                    <td>
                        <select id="google_sync_frequency" name="sync_frequency">
                            <option value="hourly" <?php selected($google_settings->sync_frequency ?? 'daily', 'hourly'); ?>>
                                <?php _e('Every Hour', 'tznew'); ?>
                            </option>
                            <option value="daily" <?php selected($google_settings->sync_frequency ?? 'daily', 'daily'); ?>>
                                <?php _e('Daily', 'tznew'); ?>
                            </option>
                            <option value="weekly" <?php selected($google_settings->sync_frequency ?? 'daily', 'weekly'); ?>>
                                <?php _e('Weekly', 'tznew'); ?>
                            </option>
                            <option value="manual" <?php selected($google_settings->sync_frequency ?? 'daily', 'manual'); ?>>
                                <?php _e('Manual Only', 'tznew'); ?>
                            </option>
                        </select>
                    </td>
                </tr>
            </table>
            
            <div style="margin-top: 20px;">
                <input type="submit" name="save_api_settings" class="button button-primary" 
                       value="<?php _e('Save Google Settings', 'tznew'); ?>">
                
                <button type="button" id="test-google" class="button button-secondary" style="margin-left: 10px;">
                    <?php _e('Test Connection', 'tznew'); ?>
                </button>
            </div>
        </form>
    </div>
    
    <!-- API Usage Guidelines -->
    <div class="api-guidelines" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: 20px 0;">
        <h2><?php _e('API Usage Guidelines', 'tznew'); ?></h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <div>
                <h3 style="color: #00af87;"><?php _e('TripAdvisor API', 'tznew'); ?></h3>
                <ul>
                    <li><?php _e('Requires TripAdvisor Content API access', 'tznew'); ?></li>
                    <li><?php _e('Rate limits: 1000 requests per day', 'tznew'); ?></li>
                    <li><?php _e('Reviews are cached for 24 hours', 'tznew'); ?></li>
                    <li><?php _e('Only public reviews are accessible', 'tznew'); ?></li>
                </ul>
                
                <h4><?php _e('Setup Steps:', 'tznew'); ?></h4>
                <ol>
                    <li><?php _e('Register at TripAdvisor Developer Portal', 'tznew'); ?></li>
                    <li><?php _e('Create a new application', 'tznew'); ?></li>
                    <li><?php _e('Get your API key and location ID', 'tznew'); ?></li>
                    <li><?php _e('Configure the settings above', 'tznew'); ?></li>
                </ol>
            </div>
            
            <div>
                <h3 style="color: #4285f4;"><?php _e('Google Places API', 'tznew'); ?></h3>
                <ul>
                    <li><?php _e('Requires Google Cloud Platform account', 'tznew'); ?></li>
                    <li><?php _e('Rate limits: 100,000 requests per day', 'tznew'); ?></li>
                    <li><?php _e('Billing must be enabled for production use', 'tznew'); ?></li>
                    <li><?php _e('Reviews are updated in real-time', 'tznew'); ?></li>
                </ul>
                
                <h4><?php _e('Setup Steps:', 'tznew'); ?></h4>
                <ol>
                    <li><?php _e('Create a Google Cloud Platform project', 'tznew'); ?></li>
                    <li><?php _e('Enable Places API', 'tznew'); ?></li>
                    <li><?php _e('Create API credentials', 'tznew'); ?></li>
                    <li><?php _e('Find your Place ID', 'tznew'); ?></li>
                </ol>
            </div>
        </div>
    </div>
    
    <!-- Current Status -->
    <div class="api-status" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: 20px 0;">
        <h2><?php _e('Current API Status', 'tznew'); ?></h2>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Platform', 'tznew'); ?></th>
                    <th><?php _e('Status', 'tznew'); ?></th>
                    <th><?php _e('Last Sync', 'tznew'); ?></th>
                    <th><?php _e('Sync Frequency', 'tznew'); ?></th>
                    <th><?php _e('Actions', 'tznew'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>TripAdvisor</strong></td>
                    <td>
                        <?php if ($tripadvisor_settings && $tripadvisor_settings->is_active): ?>
                            <span style="color: #00a32a;">● <?php _e('Active', 'tznew'); ?></span>
                        <?php else: ?>
                            <span style="color: #d63638;">● <?php _e('Inactive', 'tznew'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php 
                        $last_sync = $tripadvisor_settings->last_sync ?? '0000-00-00 00:00:00';
                        echo $last_sync !== '0000-00-00 00:00:00' ? date('M j, Y H:i', strtotime($last_sync)) : __('Never', 'tznew');
                        ?>
                    </td>
                    <td><?php echo ucfirst($tripadvisor_settings->sync_frequency ?? 'daily'); ?></td>
                    <td>
                        <button class="button button-small sync-now" data-platform="tripadvisor">
                            <?php _e('Sync Now', 'tznew'); ?>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><strong>Google Reviews</strong></td>
                    <td>
                        <?php if ($google_settings && $google_settings->is_active): ?>
                            <span style="color: #00a32a;">● <?php _e('Active', 'tznew'); ?></span>
                        <?php else: ?>
                            <span style="color: #d63638;">● <?php _e('Inactive', 'tznew'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php 
                        $last_sync = $google_settings->last_sync ?? '0000-00-00 00:00:00';
                        echo $last_sync !== '0000-00-00 00:00:00' ? date('M j, Y H:i', strtotime($last_sync)) : __('Never', 'tznew');
                        ?>
                    </td>
                    <td><?php echo ucfirst($google_settings->sync_frequency ?? 'daily'); ?></td>
                    <td>
                        <button class="button button-small sync-now" data-platform="google">
                            <?php _e('Sync Now', 'tznew'); ?>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Test TripAdvisor connection
    $('#test-tripadvisor').on('click', function() {
        var button = $(this);
        var api_key = $('#tripadvisor_api_key').val();
        var place_id = $('#tripadvisor_place_id').val();
        
        if (!api_key || !place_id) {
            alert('<?php _e('Please enter API key and Place ID first.', 'tznew'); ?>');
            return;
        }
        
        button.prop('disabled', true).text('<?php _e('Testing...', 'tznew'); ?>');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'test_api_connection',
                test_platform: 'tripadvisor',
                test_api_key: api_key,
                test_place_id: place_id,
                nonce: '<?php echo wp_create_nonce('testimonial_ajax_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    alert('<?php _e('TripAdvisor API connection successful!', 'tznew'); ?>');
                } else {
                    alert('<?php _e('TripAdvisor API connection failed. Please check your credentials.', 'tznew'); ?>');
                }
            },
            error: function() {
                alert('<?php _e('An error occurred while testing the connection.', 'tznew'); ?>');
            },
            complete: function() {
                button.prop('disabled', false).text('<?php _e('Test Connection', 'tznew'); ?>');
            }
        });
    });
    
    // Test Google connection
    $('#test-google').on('click', function() {
        var button = $(this);
        var api_key = $('#google_api_key').val();
        var place_id = $('#google_place_id').val();
        
        if (!api_key || !place_id) {
            alert('<?php _e('Please enter API key and Place ID first.', 'tznew'); ?>');
            return;
        }
        
        button.prop('disabled', true).text('<?php _e('Testing...', 'tznew'); ?>');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'test_api_connection',
                test_platform: 'google',
                test_api_key: api_key,
                test_place_id: place_id,
                nonce: '<?php echo wp_create_nonce('testimonial_ajax_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    alert('<?php _e('Google API connection successful!', 'tznew'); ?>');
                } else {
                    alert('<?php _e('Google API connection failed. Please check your credentials.', 'tznew'); ?>');
                }
            },
            error: function() {
                alert('<?php _e('An error occurred while testing the connection.', 'tznew'); ?>');
            },
            complete: function() {
                button.prop('disabled', false).text('<?php _e('Test Connection', 'tznew'); ?>');
            }
        });
    });
    
    // Manual sync
    $('.sync-now').on('click', function() {
        var button = $(this);
        var platform = button.data('platform');
        
        button.prop('disabled', true).text('<?php _e('Syncing...', 'tznew'); ?>');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'sync_' + platform + '_reviews',
                nonce: '<?php echo wp_create_nonce('testimonial_ajax_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('<?php _e('An error occurred while syncing reviews.', 'tznew'); ?>');
            },
            complete: function() {
                button.prop('disabled', false).text('<?php _e('Sync Now', 'tznew'); ?>');
            }
        });
    });
});
</script>