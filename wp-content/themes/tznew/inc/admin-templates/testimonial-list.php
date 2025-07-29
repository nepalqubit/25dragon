<?php
/**
 * Testimonial List Template
 *
 * @package TZnew
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Handle bulk actions
if (isset($_POST['action']) && $_POST['action'] !== '-1') {
    $action = sanitize_text_field($_POST['action']);
    $testimonial_ids = array_map('intval', $_POST['testimonial_ids'] ?? array());
    
    if (!empty($testimonial_ids)) {
        foreach ($testimonial_ids as $testimonial_id) {
            switch ($action) {
                case 'publish':
                    wp_update_post(array('ID' => $testimonial_id, 'post_status' => 'publish'));
                    break;
                case 'draft':
                    wp_update_post(array('ID' => $testimonial_id, 'post_status' => 'draft'));
                    break;
                case 'feature':
                    update_post_meta($testimonial_id, '_featured', 1);
                    break;
                case 'unfeature':
                    update_post_meta($testimonial_id, '_featured', 0);
                    break;
                case 'delete':
                    wp_delete_post($testimonial_id, true);
                    break;
            }
        }
        echo '<div class="notice notice-success"><p>' . __('Bulk action completed successfully.', 'tznew') . '</p></div>';
    }
}

// Get filter parameters
$status_filter = sanitize_text_field($_GET['status'] ?? 'all');
$source_filter = sanitize_text_field($_GET['source'] ?? 'all');
$rating_filter = sanitize_text_field($_GET['rating'] ?? 'all');
$search = sanitize_text_field($_GET['s'] ?? '');

// Build query arguments
$args = array(
    'post_type' => 'testimonial',
    'posts_per_page' => 20,
    'paged' => get_query_var('paged') ?: 1,
    'meta_query' => array(),
    'tax_query' => array()
);

// Apply filters
if ($status_filter !== 'all') {
    $args['post_status'] = $status_filter;
} else {
    $args['post_status'] = array('publish', 'draft', 'pending');
}

if ($source_filter !== 'all') {
    $args['tax_query'][] = array(
        'taxonomy' => 'testimonial_source',
        'field' => 'slug',
        'terms' => $source_filter
    );
}

if ($rating_filter !== 'all') {
    $args['meta_query'][] = array(
        'key' => '_rating',
        'value' => $rating_filter,
        'compare' => '='
    );
}

if (!empty($search)) {
    $args['s'] = $search;
}

// Get testimonials
$testimonials_query = new WP_Query($args);
$testimonials = $testimonials_query->posts;

// Get filter options
$sources = get_terms(array('taxonomy' => 'testimonial_source', 'hide_empty' => false));
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Manage Testimonials', 'tznew'); ?></h1>
    <a href="<?php echo admin_url('post-new.php?post_type=testimonial'); ?>" class="page-title-action">
        <?php _e('Add New', 'tznew'); ?>
    </a>
    <hr class="wp-header-end">
    
    <!-- Filters -->
    <div class="tablenav top">
        <div class="alignleft actions">
            <form method="get" action="">
                <input type="hidden" name="page" value="testimonial-list">
                
                <select name="status">
                    <option value="all" <?php selected($status_filter, 'all'); ?>><?php _e('All Statuses', 'tznew'); ?></option>
                    <option value="publish" <?php selected($status_filter, 'publish'); ?>><?php _e('Published', 'tznew'); ?></option>
                    <option value="draft" <?php selected($status_filter, 'draft'); ?>><?php _e('Draft', 'tznew'); ?></option>
                    <option value="pending" <?php selected($status_filter, 'pending'); ?>><?php _e('Pending', 'tznew'); ?></option>
                </select>
                
                <select name="source">
                    <option value="all" <?php selected($source_filter, 'all'); ?>><?php _e('All Sources', 'tznew'); ?></option>
                    <?php foreach ($sources as $source): ?>
                        <option value="<?php echo esc_attr($source->slug); ?>" <?php selected($source_filter, $source->slug); ?>>
                            <?php echo esc_html($source->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <select name="rating">
                    <option value="all" <?php selected($rating_filter, 'all'); ?>><?php _e('All Ratings', 'tznew'); ?></option>
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <option value="<?php echo $i; ?>" <?php selected($rating_filter, $i); ?>>
                            <?php echo $i; ?> <?php echo str_repeat('★', $i); ?>
                        </option>
                    <?php endfor; ?>
                </select>
                
                <input type="submit" class="button" value="<?php _e('Filter', 'tznew'); ?>">
            </form>
        </div>
        
        <div class="alignright actions">
            <form method="get" action="">
                <input type="hidden" name="page" value="testimonial-list">
                <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="<?php _e('Search testimonials...', 'tznew'); ?>">
                <input type="submit" class="button" value="<?php _e('Search', 'tznew'); ?>">
            </form>
        </div>
    </div>
    
    <!-- Testimonials Table -->
    <form method="post" action="">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <select name="action">
                    <option value="-1"><?php _e('Bulk Actions', 'tznew'); ?></option>
                    <option value="publish"><?php _e('Publish', 'tznew'); ?></option>
                    <option value="draft"><?php _e('Move to Draft', 'tznew'); ?></option>
                    <option value="feature"><?php _e('Mark as Featured', 'tznew'); ?></option>
                    <option value="unfeature"><?php _e('Remove from Featured', 'tznew'); ?></option>
                    <option value="delete"><?php _e('Delete', 'tznew'); ?></option>
                </select>
                <input type="submit" class="button action" value="<?php _e('Apply', 'tznew'); ?>">
            </div>
        </div>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <td class="manage-column column-cb check-column">
                        <input type="checkbox" id="cb-select-all-1">
                    </td>
                    <th class="manage-column column-title column-primary"><?php _e('Title', 'tznew'); ?></th>
                    <th class="manage-column"><?php _e('Customer', 'tznew'); ?></th>
                    <th class="manage-column"><?php _e('Rating', 'tznew'); ?></th>
                    <th class="manage-column"><?php _e('Source', 'tznew'); ?></th>
                    <th class="manage-column"><?php _e('Status', 'tznew'); ?></th>
                    <th class="manage-column"><?php _e('Featured', 'tznew'); ?></th>
                    <th class="manage-column"><?php _e('Date', 'tznew'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($testimonials): ?>
                    <?php foreach ($testimonials as $testimonial): 
                        $customer_name = get_post_meta($testimonial->ID, '_customer_name', true);
                        $rating = get_post_meta($testimonial->ID, '_rating', true);
                        $featured = get_post_meta($testimonial->ID, '_featured', true);
                        $source_terms = get_the_terms($testimonial->ID, 'testimonial_source');
                        $source = $source_terms && !is_wp_error($source_terms) ? $source_terms[0]->name : 'Manual';
                    ?>
                        <tr>
                            <th class="check-column">
                                <input type="checkbox" name="testimonial_ids[]" value="<?php echo $testimonial->ID; ?>">
                            </th>
                            <td class="column-title column-primary">
                                <strong>
                                    <a href="<?php echo get_edit_post_link($testimonial->ID); ?>">
                                        <?php echo esc_html($testimonial->post_title); ?>
                                    </a>
                                </strong>
                                <div class="row-actions">
                                    <span class="edit">
                                        <a href="<?php echo get_edit_post_link($testimonial->ID); ?>"><?php _e('Edit', 'tznew'); ?></a> |
                                    </span>
                                    <span class="view">
                                        <a href="<?php echo get_permalink($testimonial->ID); ?>" target="_blank"><?php _e('View', 'tznew'); ?></a> |
                                    </span>
                                    <span class="trash">
                                        <a href="<?php echo get_delete_post_link($testimonial->ID); ?>" class="submitdelete"><?php _e('Delete', 'tznew'); ?></a>
                                    </span>
                                </div>
                                <button type="button" class="toggle-row"><span class="screen-reader-text"><?php _e('Show more details', 'tznew'); ?></span></button>
                            </td>
                            <td data-colname="<?php _e('Customer', 'tznew'); ?>">
                                <?php echo esc_html($customer_name); ?>
                            </td>
                            <td data-colname="<?php _e('Rating', 'tznew'); ?>">
                                <span style="color: #f0b849;">
                                    <?php echo str_repeat('★', intval($rating)) . str_repeat('☆', 5 - intval($rating)); ?>
                                </span>
                                (<?php echo esc_html($rating); ?>)
                            </td>
                            <td data-colname="<?php _e('Source', 'tznew'); ?>">
                                <span class="source-badge" style="
                                    padding: 4px 8px; 
                                    border-radius: 4px; 
                                    font-size: 12px; 
                                    background: #e1f5fe;
                                    color: #01579b;
                                ">
                                    <?php echo esc_html($source); ?>
                                </span>
                            </td>
                            <td data-colname="<?php _e('Status', 'tznew'); ?>">
                                <?php
                                $status_colors = array(
                                    'publish' => array('bg' => '#d4edda', 'color' => '#155724'),
                                    'draft' => array('bg' => '#fff3cd', 'color' => '#856404'),
                                    'pending' => array('bg' => '#f8d7da', 'color' => '#721c24')
                                );
                                $status_color = $status_colors[$testimonial->post_status] ?? array('bg' => '#f8f9fa', 'color' => '#495057');
                                ?>
                                <span class="status-badge" style="
                                    padding: 4px 8px; 
                                    border-radius: 4px; 
                                    font-size: 12px; 
                                    background: <?php echo $status_color['bg']; ?>;
                                    color: <?php echo $status_color['color']; ?>;
                                ">
                                    <?php echo ucfirst($testimonial->post_status); ?>
                                </span>
                            </td>
                            <td data-colname="<?php _e('Featured', 'tznew'); ?>">
                                <?php if ($featured): ?>
                                    <span style="color: #f0b849; font-size: 16px;">★</span>
                                <?php else: ?>
                                    <span style="color: #ccc;">☆</span>
                                <?php endif; ?>
                            </td>
                            <td data-colname="<?php _e('Date', 'tznew'); ?>">
                                <?php echo get_the_date('Y/m/d', $testimonial->ID); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px;">
                            <p><?php _e('No testimonials found.', 'tznew'); ?></p>
                            <a href="<?php echo admin_url('post-new.php?post_type=testimonial'); ?>" class="button button-primary">
                                <?php _e('Add Your First Testimonial', 'tznew'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="tablenav bottom">
            <div class="alignleft actions bulkactions">
                <select name="action2">
                    <option value="-1"><?php _e('Bulk Actions', 'tznew'); ?></option>
                    <option value="publish"><?php _e('Publish', 'tznew'); ?></option>
                    <option value="draft"><?php _e('Move to Draft', 'tznew'); ?></option>
                    <option value="feature"><?php _e('Mark as Featured', 'tznew'); ?></option>
                    <option value="unfeature"><?php _e('Remove from Featured', 'tznew'); ?></option>
                    <option value="delete"><?php _e('Delete', 'tznew'); ?></option>
                </select>
                <input type="submit" class="button action" value="<?php _e('Apply', 'tznew'); ?>">
            </div>
            
            <!-- Pagination -->
            <?php if ($testimonials_query->max_num_pages > 1): ?>
                <div class="tablenav-pages">
                    <?php
                    $pagination_args = array(
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => '&laquo;',
                        'next_text' => '&raquo;',
                        'total' => $testimonials_query->max_num_pages,
                        'current' => $args['paged']
                    );
                    echo paginate_links($pagination_args);
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Select all checkbox functionality
    $('#cb-select-all-1').on('change', function() {
        $('input[name="testimonial_ids[]"]').prop('checked', this.checked);
    });
    
    // Update select all when individual checkboxes change
    $('input[name="testimonial_ids[]"]').on('change', function() {
        var total = $('input[name="testimonial_ids[]"]').length;
        var checked = $('input[name="testimonial_ids[]"]:checked').length;
        $('#cb-select-all-1').prop('checked', total === checked);
    });
    
    // Confirm delete action
    $('form').on('submit', function(e) {
        var action = $('select[name="action"]').val() || $('select[name="action2"]').val();
        if (action === 'delete') {
            var checked = $('input[name="testimonial_ids[]"]:checked').length;
            if (checked > 0) {
                if (!confirm('<?php _e('Are you sure you want to delete the selected testimonials? This action cannot be undone.', 'tznew'); ?>')) {
                    e.preventDefault();
                }
            }
        }
    });
});
</script>