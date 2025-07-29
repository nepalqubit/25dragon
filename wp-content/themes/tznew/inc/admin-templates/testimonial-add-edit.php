<?php
/**
 * Add/Edit Testimonial Template
 *
 * @package TZnew
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$testimonial_id = isset($_GET['testimonial_id']) ? intval($_GET['testimonial_id']) : 0;
$is_edit = $testimonial_id > 0;
$testimonial = null;

if ($is_edit) {
    $testimonial = get_post($testimonial_id);
    if (!$testimonial || $testimonial->post_type !== 'testimonial') {
        wp_die(__('Testimonial not found.', 'tznew'));
    }
}

// Handle form submission
if (isset($_POST['save_testimonial'])) {
    $guest_name = sanitize_text_field($_POST['guest_name']);
    $guest_email = sanitize_email($_POST['guest_email']);
    $guest_location = sanitize_text_field($_POST['guest_location']);
    $rating = intval($_POST['rating']);
    $review_title = sanitize_text_field($_POST['review_title']);
    $review_content = wp_kses_post($_POST['review_content']);
    $visit_date = sanitize_text_field($_POST['visit_date']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $status = sanitize_text_field($_POST['status']);
    
    $post_data = array(
        'post_title' => $review_title,
        'post_content' => $review_content,
        'post_status' => $status,
        'post_type' => 'testimonial',
        'meta_input' => array(
            '_testimonial_guest_name' => $guest_name,
            '_testimonial_guest_email' => $guest_email,
            '_testimonial_guest_location' => $guest_location,
            '_testimonial_rating' => $rating,
            '_testimonial_visit_date' => $visit_date,
            '_testimonial_is_featured' => $is_featured,
            '_testimonial_source' => 'manual',
            '_testimonial_created_by' => get_current_user_id()
        )
    );
    
    if ($is_edit) {
        $post_data['ID'] = $testimonial_id;
        $result = wp_update_post($post_data);
        $message = __('Testimonial updated successfully!', 'tznew');
    } else {
        $result = wp_insert_post($post_data);
        $message = __('Testimonial created successfully!', 'tznew');
        $testimonial_id = $result;
    }
    
    if ($result && !is_wp_error($result)) {
        // Set testimonial source taxonomy
        wp_set_object_terms($testimonial_id, 'manual', 'testimonial_source');
        
        echo '<div class="notice notice-success"><p>' . $message . '</p></div>';
        
        if (!$is_edit) {
            // Redirect to edit page after creation
            echo '<script>window.location.href = "' . admin_url('admin.php?page=testimonial-add-edit&testimonial_id=' . $testimonial_id) . '";</script>';
        }
    } else {
        echo '<div class="notice notice-error"><p>' . __('Error saving testimonial. Please try again.', 'tznew') . '</p></div>';
    }
}

// Get testimonial data for editing
if ($is_edit) {
    $guest_name = get_post_meta($testimonial_id, '_testimonial_guest_name', true);
    $guest_email = get_post_meta($testimonial_id, '_testimonial_guest_email', true);
    $guest_location = get_post_meta($testimonial_id, '_testimonial_guest_location', true);
    $rating = get_post_meta($testimonial_id, '_testimonial_rating', true);
    $visit_date = get_post_meta($testimonial_id, '_testimonial_visit_date', true);
    $is_featured = get_post_meta($testimonial_id, '_testimonial_is_featured', true);
    $review_title = $testimonial->post_title;
    $review_content = $testimonial->post_content;
    $status = $testimonial->post_status;
} else {
    // Default values for new testimonial
    $guest_name = '';
    $guest_email = '';
    $guest_location = '';
    $rating = 5;
    $visit_date = date('Y-m-d');
    $is_featured = 0;
    $review_title = '';
    $review_content = '';
    $status = 'publish';
}
?>

<div class="wrap">
    <h1>
        <?php if ($is_edit): ?>
            <?php _e('Edit Testimonial', 'tznew'); ?>
            <a href="<?php echo admin_url('admin.php?page=testimonial-add-edit'); ?>" class="page-title-action">
                <?php _e('Add New', 'tznew'); ?>
            </a>
        <?php else: ?>
            <?php _e('Add New Testimonial', 'tznew'); ?>
        <?php endif; ?>
    </h1>
    
    <form method="post" action="" class="testimonial-form">
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                
                <!-- Main Content -->
                <div id="post-body-content">
                    
                    <!-- Review Title -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2 class="hndle"><?php _e('Review Title', 'tznew'); ?></h2>
                        </div>
                        <div class="inside">
                            <input type="text" name="review_title" id="review_title" 
                                   value="<?php echo esc_attr($review_title); ?>" 
                                   placeholder="<?php _e('Enter review title...', 'tznew'); ?>"
                                   class="large-text" required>
                            <p class="description">
                                <?php _e('A brief title that summarizes the review (e.g., "Amazing experience!", "Perfect vacation")', 'tznew'); ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Review Content -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2 class="hndle"><?php _e('Review Content', 'tznew'); ?></h2>
                        </div>
                        <div class="inside">
                            <?php
                            wp_editor($review_content, 'review_content', array(
                                'textarea_name' => 'review_content',
                                'textarea_rows' => 8,
                                'media_buttons' => false,
                                'teeny' => true,
                                'quicktags' => false
                            ));
                            ?>
                            <p class="description">
                                <?php _e('The full testimonial content from the guest.', 'tznew'); ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Guest Information -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2 class="hndle"><?php _e('Guest Information', 'tznew'); ?></h2>
                        </div>
                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="guest_name"><?php _e('Guest Name', 'tznew'); ?> *</label>
                                    </th>
                                    <td>
                                        <input type="text" name="guest_name" id="guest_name" 
                                               value="<?php echo esc_attr($guest_name); ?>" 
                                               class="regular-text" required>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="guest_email"><?php _e('Guest Email', 'tznew'); ?></label>
                                    </th>
                                    <td>
                                        <input type="email" name="guest_email" id="guest_email" 
                                               value="<?php echo esc_attr($guest_email); ?>" 
                                               class="regular-text">
                                        <p class="description">
                                            <?php _e('Optional - for internal reference only', 'tznew'); ?>
                                        </p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="guest_location"><?php _e('Guest Location', 'tznew'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="guest_location" id="guest_location" 
                                               value="<?php echo esc_attr($guest_location); ?>" 
                                               class="regular-text" 
                                               placeholder="<?php _e('e.g., New York, USA', 'tznew'); ?>">
                                        <p class="description">
                                            <?php _e('Where the guest is from (city, country)', 'tznew'); ?>
                                        </p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="visit_date"><?php _e('Visit Date', 'tznew'); ?></label>
                                    </th>
                                    <td>
                                        <input type="date" name="visit_date" id="visit_date" 
                                               value="<?php echo esc_attr($visit_date); ?>" 
                                               class="regular-text">
                                        <p class="description">
                                            <?php _e('When did the guest visit?', 'tznew'); ?>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Rating -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2 class="hndle"><?php _e('Rating', 'tznew'); ?></h2>
                        </div>
                        <div class="inside">
                            <div class="rating-input" style="margin: 20px 0;">
                                <label style="display: block; margin-bottom: 10px; font-weight: 600;">
                                    <?php _e('Guest Rating:', 'tznew'); ?>
                                </label>
                                
                                <div class="star-rating" style="font-size: 24px; margin-bottom: 10px;">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <label style="cursor: pointer; color: #ddd; margin-right: 5px;">
                                            <input type="radio" name="rating" value="<?php echo $i; ?>" 
                                                   <?php checked($rating, $i); ?> 
                                                   style="display: none;" class="rating-input">
                                            <span class="star" data-rating="<?php echo $i; ?>">★</span>
                                        </label>
                                    <?php endfor; ?>
                                </div>
                                
                                <p class="description">
                                    <?php _e('Click on the stars to set the rating (1-5 stars)', 'tznew'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <!-- Sidebar -->
                <div id="postbox-container-1" class="postbox-container">
                    
                    <!-- Publish Box -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2 class="hndle"><?php _e('Publish', 'tznew'); ?></h2>
                        </div>
                        <div class="inside">
                            <div class="submitbox">
                                
                                <!-- Status -->
                                <div class="misc-pub-section">
                                    <label for="status"><?php _e('Status:', 'tznew'); ?></label>
                                    <select name="status" id="status">
                                        <option value="publish" <?php selected($status, 'publish'); ?>>
                                            <?php _e('Published', 'tznew'); ?>
                                        </option>
                                        <option value="draft" <?php selected($status, 'draft'); ?>>
                                            <?php _e('Draft', 'tznew'); ?>
                                        </option>
                                        <option value="pending" <?php selected($status, 'pending'); ?>>
                                            <?php _e('Pending Review', 'tznew'); ?>
                                        </option>
                                    </select>
                                </div>
                                
                                <!-- Featured -->
                                <div class="misc-pub-section">
                                    <label>
                                        <input type="checkbox" name="is_featured" value="1" 
                                               <?php checked($is_featured, 1); ?>>
                                        <?php _e('Featured Testimonial', 'tznew'); ?>
                                    </label>
                                    <p class="description">
                                        <?php _e('Featured testimonials appear prominently on the website', 'tznew'); ?>
                                    </p>
                                </div>
                                
                                <!-- Save Button -->
                                <div class="major-publishing-actions">
                                    <div class="publishing-action">
                                        <input type="submit" name="save_testimonial" 
                                               class="button button-primary button-large" 
                                               value="<?php echo $is_edit ? __('Update Testimonial', 'tznew') : __('Save Testimonial', 'tznew'); ?>">
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    <!-- Testimonial Info -->
                    <?php if ($is_edit): ?>
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2 class="hndle"><?php _e('Testimonial Info', 'tznew'); ?></h2>
                        </div>
                        <div class="inside">
                            <p><strong><?php _e('ID:', 'tznew'); ?></strong> <?php echo $testimonial_id; ?></p>
                            <p><strong><?php _e('Created:', 'tznew'); ?></strong> <?php echo date('M j, Y H:i', strtotime($testimonial->post_date)); ?></p>
                            <p><strong><?php _e('Source:', 'tznew'); ?></strong> <?php _e('Manual Entry', 'tznew'); ?></p>
                            
                            <?php if ($testimonial->post_modified !== $testimonial->post_date): ?>
                            <p><strong><?php _e('Last Modified:', 'tznew'); ?></strong> <?php echo date('M j, Y H:i', strtotime($testimonial->post_modified)); ?></p>
                            <?php endif; ?>
                            
                            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                                <a href="<?php echo admin_url('admin.php?page=testimonial-list'); ?>" class="button">
                                    <?php _e('← Back to Testimonials', 'tznew'); ?>
                                </a>
                                
                                <?php if ($status === 'publish'): ?>
                                <a href="<?php echo get_permalink($testimonial_id); ?>" class="button" target="_blank">
                                    <?php _e('View Testimonial', 'tznew'); ?>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Help -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2 class="hndle"><?php _e('Tips', 'tznew'); ?></h2>
                        </div>
                        <div class="inside">
                            <ul style="margin-left: 20px;">
                                <li><?php _e('Use descriptive titles that capture the essence of the review', 'tznew'); ?></li>
                                <li><?php _e('Include specific details about the guest\'s experience', 'tznew'); ?></li>
                                <li><?php _e('Featured testimonials appear in special sections', 'tznew'); ?></li>
                                <li><?php _e('Pending reviews require approval before going live', 'tznew'); ?></li>
                                <li><?php _e('Guest location helps add credibility and context', 'tznew'); ?></li>
                            </ul>
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </form>
</div>

<style>
.testimonial-form .postbox {
    margin-bottom: 20px;
}

.testimonial-form .form-table th {
    width: 150px;
    padding-left: 0;
}

.star-rating .star {
    transition: color 0.2s ease;
}

.star-rating .star:hover,
.star-rating .star.active {
    color: #ffb900 !important;
}

.star-rating .star.filled {
    color: #ffb900;
}

.submitbox .misc-pub-section {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.submitbox .misc-pub-section:last-of-type {
    border-bottom: none;
}

.major-publishing-actions {
    padding: 10px 0 0;
    border-top: 1px solid #ddd;
    margin-top: 10px;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Star rating functionality
    $('.star-rating .star').on('click', function() {
        var rating = $(this).data('rating');
        var $container = $(this).closest('.star-rating');
        
        // Update radio button
        $container.find('input[value="' + rating + '"]').prop('checked', true);
        
        // Update star display
        updateStarDisplay($container, rating);
    });
    
    $('.star-rating .star').on('mouseenter', function() {
        var rating = $(this).data('rating');
        var $container = $(this).closest('.star-rating');
        updateStarDisplay($container, rating);
    });
    
    $('.star-rating').on('mouseleave', function() {
        var $container = $(this);
        var currentRating = $container.find('input:checked').val() || 0;
        updateStarDisplay($container, currentRating);
    });
    
    function updateStarDisplay($container, rating) {
        $container.find('.star').each(function() {
            var starRating = $(this).data('rating');
            if (starRating <= rating) {
                $(this).addClass('filled');
            } else {
                $(this).removeClass('filled');
            }
        });
    }
    
    // Initialize star display
    var initialRating = $('.star-rating input:checked').val() || 0;
    updateStarDisplay($('.star-rating'), initialRating);
    
    // Auto-generate title from content if title is empty
    $('#review_content_ifr').on('load', function() {
        var iframe = this;
        $(iframe.contentDocument).on('keyup', function() {
            var content = $(iframe.contentDocument.body).text();
            var title = $('#review_title').val();
            
            if (!title && content.length > 10) {
                var autoTitle = content.substring(0, 50);
                if (content.length > 50) {
                    autoTitle += '...';
                }
                $('#review_title').attr('placeholder', 'Auto: ' + autoTitle);
            }
        });
    });
    
    // Form validation
    $('.testimonial-form').on('submit', function(e) {
        var guestName = $('#guest_name').val().trim();
        var reviewTitle = $('#review_title').val().trim();
        var rating = $('.star-rating input:checked').val();
        
        if (!guestName) {
            alert('<?php _e('Please enter the guest name.', 'tznew'); ?>');
            $('#guest_name').focus();
            e.preventDefault();
            return false;
        }
        
        if (!reviewTitle) {
            alert('<?php _e('Please enter a review title.', 'tznew'); ?>');
            $('#review_title').focus();
            e.preventDefault();
            return false;
        }
        
        if (!rating) {
            alert('<?php _e('Please select a rating.', 'tznew'); ?>');
            $('.star-rating').focus();
            e.preventDefault();
            return false;
        }
    });
});
</script>