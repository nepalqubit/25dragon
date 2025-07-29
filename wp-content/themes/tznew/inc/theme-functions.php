<?php
/**
 * Theme Functions for TZnew Theme
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
 * Pagination function
 */
function tznew_pagination() {
    global $wp_query;
    
    if ($wp_query->max_num_pages <= 1) {
        return;
    }
    
    $big = 999999999;
    $pages = paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'type' => 'array',
        'prev_text' => '<span class="icon">&larr;</span> ' . __('Previous', 'tznew'),
        'next_text' => __('Next', 'tznew') . ' <span class="icon">&rarr;</span>',
    ));
    
    if (is_array($pages)) {
        echo '<nav class="pagination-container" aria-label="' . __('Pagination', 'tznew') . '">';
        echo '<ul class="pagination">';
        
        foreach ($pages as $page) {
            echo '<li class="page-item' . (strpos($page, 'current') !== false ? ' active' : '') . '">';
            echo str_replace('page-numbers', 'page-link', $page);
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</nav>';
    }
}

/**
 * Get related posts
 */
function tznew_get_related_posts($post_id, $post_type, $taxonomy = '', $limit = 3) {
    $args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'post__not_in' => array($post_id),
    );
    
    if (!empty($taxonomy)) {
        $terms = get_the_terms($post_id, $taxonomy);
        
        if ($terms && !is_wp_error($terms)) {
            $term_ids = wp_list_pluck($terms, 'term_id');
            
            $args['tax_query'] = array(
                array(
                    'taxonomy' => $taxonomy,
                    'field' => 'term_id',
                    'terms' => $term_ids,
                ),
            );
        }
    }
    
    return new WP_Query($args);
}

/**
 * Get post thumbnail with fallback
 */
function tznew_get_post_thumbnail($post_id, $size = 'large', $class = '') {
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size, array('class' => $class));
    }
    
    // Check for ACF image field
    if (function_exists('get_field')) {
        $featured_image = tznew_get_field_safe('featured_image', $post_id);
        
        if (!empty($featured_image)) {
            if (is_array($featured_image) && isset($featured_image['ID'])) {
                return wp_get_attachment_image($featured_image['ID'], $size, false, array('class' => $class));
            } elseif (is_numeric($featured_image)) {
                return wp_get_attachment_image($featured_image, $size, false, array('class' => $class));
            }
        }
        
        // Check for gallery field and use the first image
        $gallery = tznew_get_field_safe('gallery', $post_id);
        
        if (!empty($gallery) && is_array($gallery)) {
            $first_image = is_array($gallery[0]) ? $gallery[0] : $gallery;
            if (isset($first_image['ID'])) {
                return wp_get_attachment_image($first_image['ID'], $size, false, array('class' => $class));
            }
        }
    }
    
    // Fallback to default image
    return '<img src="' . TZNEW_THEME_URI . '/assets/images/placeholder.svg" alt="' . get_the_title($post_id) . '" class="' . $class . '" />';
}

/**
 * Display post thumbnail with proper fallback
 */
function tznew_post_thumbnail($size = 'large', $class = '') {
    echo tznew_get_post_thumbnail(get_the_ID(), $size, $class);
}

// ACF helper functions are now defined in acf-database-optimization.php

/**
 * Get post excerpt with custom length
 */
function tznew_get_excerpt($post_id, $length = 20) {
    $post = get_post($post_id);
    
    if (empty($post)) {
        return '';
    }
    
    // Check for ACF excerpt field
    if (function_exists('get_field')) {
        $excerpt = tznew_get_field_safe('excerpt', $post_id);
        
        if (!empty($excerpt)) {
            return wp_trim_words($excerpt, $length, '...');
        }
    }
    
    // Check for post excerpt
    if (!empty($post->post_excerpt)) {
        return wp_trim_words($post->post_excerpt, $length, '...');
    }
    
    // Check for ACF content field
    if (function_exists('get_field')) {
        $content = tznew_get_field_safe('content', $post_id);
        
        if (!empty($content)) {
            return wp_trim_words($content, $length, '...');
        }
        
        $overview = tznew_get_field_safe('overview', $post_id);
        
        if (!empty($overview)) {
            return wp_trim_words($overview, $length, '...');
        }
    }
    
    // Fallback to post content
    return wp_trim_words($post->post_content, $length, '...');
}

/**
 * Get post meta information
 */
function tznew_post_meta($post_id) {
    $output = '<div class="post-meta">';
    
    // Date
    $output .= '<span class="post-date">';
    $output .= '<i class="fas fa-calendar-days"></i> ';
    $output .= get_the_date('', $post_id);
    $output .= '</span>';
    
    // Author
    $output .= '<span class="post-author">';
    $output .= '<i class="fas fa-user"></i> ';
    $output .= get_the_author_meta('display_name', get_post_field('post_author', $post_id));
    $output .= '</span>';
    
    // Categories/Terms
    $post_type = get_post_type($post_id);
    
    if ($post_type === 'post' || $post_type === 'blog') {
        $taxonomy = $post_type === 'post' ? 'category' : 'acf_tag';
        $terms = get_the_terms($post_id, $taxonomy);
        
        if ($terms && !is_wp_error($terms) && !empty($terms)) {
            $output .= '<span class="post-categories">';
            $output .= '<i class="fas fa-folder"></i> ';
            
            $term_links = array();
            foreach ($terms as $term) {
                if (isset($term->name)) {
                    $term_links[] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                }
            }
            
            if (!empty($term_links)) {
                $output .= implode(', ', $term_links);
            }
            $output .= '</span>';
        }
    } elseif ($post_type === 'trekking') {
        // Difficulty
        $difficulty = tznew_get_field_safe('difficulty', $post_id);
        
        if (!empty($difficulty)) {
            $output .= '<span class="post-difficulty">';
            $output .= '<i class="fas fa-mountain"></i> ';
            $output .= esc_html(ucfirst($difficulty));
            $output .= '</span>';
        }
        
        // Duration
        $duration = tznew_get_field_safe('duration', $post_id);
        
        if (!empty($duration)) {
            $output .= '<span class="post-duration">';
            $output .= '<i class="fas fa-clock"></i> ';
            $output .= esc_html($duration) . ' ' . _n('Day', 'Days', $duration, 'tznew');
            $output .= '</span>';
        }
    } elseif ($post_type === 'tours') {
        // Tour Type
        $tour_type = tznew_get_field_safe('tour_type', $post_id);
        
        if (!empty($tour_type)) {
            $output .= '<span class="post-tour-type">';
            $output .= '<i class="fas fa-tag"></i> ';
            $output .= esc_html(ucfirst($tour_type));
            $output .= '</span>';
        }
        
        // Duration
        $duration = tznew_get_field_safe('duration', $post_id);
        
        if (!empty($duration)) {
            $output .= '<span class="post-duration">';
            $output .= '<i class="fas fa-clock"></i> ';
            $output .= esc_html($duration) . ' ' . _n('Day', 'Days', $duration, 'tznew');
            $output .= '</span>';
        }
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Get breadcrumbs
 */
function tznew_breadcrumbs() {
    if (is_front_page()) {
        return;
    }
    
    $output = '<nav class="breadcrumbs" aria-label="' . __('Breadcrumbs', 'tznew') . '">';
    $output .= '<ol class="breadcrumb">';
    
    // Home
    $output .= '<li class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . __('Home', 'tznew') . '</a></li>';
    
    if (is_archive()) {
        $post_type = get_post_type();
        
        if ($post_type && $post_type !== 'post') {
            $post_type_obj = get_post_type_object($post_type);
            
            if ($post_type_obj) {
                $output .= '<li class="breadcrumb-item active" aria-current="page">' . esc_html($post_type_obj->labels->name) . '</li>';
            }
        } elseif (is_category()) {
            $output .= '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . __('Blog', 'tznew') . '</a></li>';
            $output .= '<li class="breadcrumb-item active" aria-current="page">' . single_cat_title('', false) . '</li>';
        } elseif (is_tag()) {
            $output .= '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . __('Blog', 'tznew') . '</a></li>';
            $output .= '<li class="breadcrumb-item active" aria-current="page">' . single_tag_title('', false) . '</li>';
        } elseif (is_author()) {
            $output .= '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . __('Blog', 'tznew') . '</a></li>';
            $output .= '<li class="breadcrumb-item active" aria-current="page">' . get_the_author() . '</li>';
        } elseif (is_date()) {
            $output .= '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . __('Blog', 'tznew') . '</a></li>';
            
            if (is_day()) {
                $output .= '<li class="breadcrumb-item active" aria-current="page">' . get_the_date() . '</li>';
            } elseif (is_month()) {
                $output .= '<li class="breadcrumb-item active" aria-current="page">' . get_the_date('F Y') . '</li>';
            } elseif (is_year()) {
                $output .= '<li class="breadcrumb-item active" aria-current="page">' . get_the_date('Y') . '</li>';
            }
        }
    } elseif (is_singular()) {
        $post_type = get_post_type();
        
        if ($post_type && $post_type !== 'post') {
            $post_type_obj = get_post_type_object($post_type);
            
            if ($post_type_obj) {
                $output .= '<li class="breadcrumb-item"><a href="' . esc_url(get_post_type_archive_link($post_type)) . '">' . esc_html($post_type_obj->labels->name) . '</a></li>';
            }
        } elseif ($post_type === 'post') {
            $output .= '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . __('Blog', 'tznew') . '</a></li>';
        }
        
        $output .= '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
    } elseif (is_search()) {
        $output .= '<li class="breadcrumb-item active" aria-current="page">' . __('Search Results', 'tznew') . '</li>';
    } elseif (is_404()) {
        $output .= '<li class="breadcrumb-item active" aria-current="page">' . __('Page Not Found', 'tznew') . '</li>';
    }
    
    $output .= '</ol>';
    $output .= '</nav>';
    
    return $output;
}

/**
 * Social sharing links
 */
function tznew_social_sharing($post_id) {
    $post_url = urlencode(get_permalink($post_id));
    $post_title = urlencode(get_the_title($post_id));
    $post_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'large');
    $post_thumbnail_url = $post_thumbnail ? urlencode($post_thumbnail[0]) : '';
    
    $output = '<div class="social-sharing">';
    $output .= '<h4>' . __('Share This', 'tznew') . '</h4>';
    $output .= '<ul class="social-links">';
    
    // Facebook
    $output .= '<li><a href="https://www.facebook.com/sharer/sharer.php?u=' . $post_url . '" target="_blank" rel="noopener noreferrer" class="facebook"><i class="fab fa-facebook-f"></i></a></li>';
    
    // Twitter
    $output .= '<li><a href="https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title . '" target="_blank" rel="noopener noreferrer" class="twitter"><i class="fab fa-twitter"></i></a></li>';
    
    // LinkedIn
    $output .= '<li><a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title . '" target="_blank" rel="noopener noreferrer" class="linkedin"><i class="fab fa-linkedin-in"></i></a></li>';
    
    // Pinterest (only if thumbnail exists)
    if ($post_thumbnail_url) {
        $output .= '<li><a href="https://pinterest.com/pin/create/button/?url=' . $post_url . '&media=' . $post_thumbnail_url . '&description=' . $post_title . '" target="_blank" rel="noopener noreferrer" class="pinterest"><i class="fab fa-pinterest-p"></i></a></li>';
    }
    
    // WhatsApp
    $output .= '<li><a href="https://api.whatsapp.com/send?text=' . $post_title . ' ' . $post_url . '" target="_blank" rel="noopener noreferrer" class="whatsapp"><i class="fab fa-whatsapp"></i></a></li>';
    
    // Email
    $output .= '<li><a href="mailto:?subject=' . $post_title . '&body=' . __('Check out this link:', 'tznew') . ' ' . $post_url . '" class="email"><i class="fas fa-envelope"></i></a></li>';
    
    $output .= '</ul>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Get filter form for trekking and tours
 */
function tznew_get_filter_form($post_type) {
    if (!in_array($post_type, array('trekking', 'tours'))) {
        return '';
    }
    
    $output = '<div class="filter-form grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">';
    $output .= '<form method="get" action="' . esc_url(get_post_type_archive_link($post_type)) . '" class="w-full">';
    
    // Region filter
    $regions = get_terms(array(
        'taxonomy' => 'region',
        'hide_empty' => true,
    ));
    
    if (!is_wp_error($regions) && !empty($regions)) {
        $output .= '<div class="filter-group mb-4">';
        $output .= '<label for="region" class="block text-sm font-medium text-gray-700 mb-1">' . __('Region', 'tznew') . '</label>';
        $output .= '<select name="region" id="region" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">';
        $output .= '<option value="">' . __('All Regions', 'tznew') . '</option>';
        
        foreach ($regions as $region) {
            $selected = isset($_GET['region']) && sanitize_text_field($_GET['region']) === $region->slug ? 'selected' : '';
            $output .= '<option value="' . esc_attr($region->slug) . '" ' . $selected . '>' . esc_html($region->name) . '</option>';
        }
        
        $output .= '</select>';
        $output .= '</div>';
    }
    
    // Post type specific filters
    if ($post_type === 'trekking') {
        // Difficulty filter
        $difficulties = get_terms(array(
            'taxonomy' => 'difficulty',
            'hide_empty' => true,
        ));
        
        if (!is_wp_error($difficulties) && !empty($difficulties)) {
            $output .= '<div class="filter-group mb-4">';
            $output .= '<label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">' . __('Difficulty', 'tznew') . '</label>';
            $output .= '<select name="difficulty" id="difficulty" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">';
            $output .= '<option value="">' . __('All Difficulties', 'tznew') . '</option>';
            
            foreach ($difficulties as $difficulty) {
                $selected = isset($_GET['difficulty']) && sanitize_text_field($_GET['difficulty']) === $difficulty->slug ? 'selected' : '';
                $output .= '<option value="' . esc_attr($difficulty->slug) . '" ' . $selected . '>' . esc_html($difficulty->name) . '</option>';
            }
            
            $output .= '</select>';
            $output .= '</div>';
        }
    } elseif ($post_type === 'tours') {
        // Tour Type filter
        $tour_types = get_terms(array(
            'taxonomy' => 'tour_type',
            'hide_empty' => true,
        ));
        
        if (!is_wp_error($tour_types) && !empty($tour_types)) {
            $output .= '<div class="filter-group mb-4">';
            $output .= '<label for="tour_type" class="block text-sm font-medium text-gray-700 mb-1">' . __('Tour Type', 'tznew') . '</label>';
            $output .= '<select name="tour_type" id="tour_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">';
            $output .= '<option value="">' . __('All Tour Types', 'tznew') . '</option>';
            
            foreach ($tour_types as $tour_type) {
                $selected = isset($_GET['tour_type']) && sanitize_text_field($_GET['tour_type']) === $tour_type->slug ? 'selected' : '';
                $output .= '<option value="' . esc_attr($tour_type->slug) . '" ' . $selected . '>' . esc_html($tour_type->name) . '</option>';
            }
            
            $output .= '</select>';
            $output .= '</div>';
        }
    }
    
    // Duration filter
    $output .= '<div class="filter-group mb-4">';
    $output .= '<label for="duration" class="block text-sm font-medium text-gray-700 mb-1">' . __('Duration', 'tznew') . '</label>';
    $output .= '<select name="duration" id="duration" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">';
    $output .= '<option value="">' . __('Any Duration', 'tznew') . '</option>';
    $output .= '<option value="1-3"' . (isset($_GET['duration']) && sanitize_text_field($_GET['duration']) === '1-3' ? ' selected' : '') . '>' . __('1-3 Days', 'tznew') . '</option>';
	$output .= '<option value="4-7"' . (isset($_GET['duration']) && sanitize_text_field($_GET['duration']) === '4-7' ? ' selected' : '') . '>' . __('4-7 Days', 'tznew') . '</option>';
	$output .= '<option value="8-14"' . (isset($_GET['duration']) && sanitize_text_field($_GET['duration']) === '8-14' ? ' selected' : '') . '>' . __('8-14 Days', 'tznew') . '</option>';
	$output .= '<option value="15+"' . (isset($_GET['duration']) && sanitize_text_field($_GET['duration']) === '15+' ? ' selected' : '') . '>' . __('15+ Days', 'tznew') . '</option>';
    $output .= '</select>';
    $output .= '</div>';
    
    // Price filter
    $output .= '<div class="filter-group mb-4">';
    $output .= '<label for="price" class="block text-sm font-medium text-gray-700 mb-1">' . __('Price Range', 'tznew') . '</label>';
    $output .= '<select name="price" id="price" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">';
    $output .= '<option value="">' . __('Any Price', 'tznew') . '</option>';
    $output .= '<option value="0-500"' . (isset($_GET['price']) && sanitize_text_field($_GET['price']) === '0-500' ? ' selected' : '') . '>' . __('$0 - $500', 'tznew') . '</option>';
	$output .= '<option value="500-1000"' . (isset($_GET['price']) && sanitize_text_field($_GET['price']) === '500-1000' ? ' selected' : '') . '>' . __('$500 - $1,000', 'tznew') . '</option>';
	$output .= '<option value="1000-2000"' . (isset($_GET['price']) && sanitize_text_field($_GET['price']) === '1000-2000' ? ' selected' : '') . '>' . __('$1,000 - $2,000', 'tznew') . '</option>';
	$output .= '<option value="2000+"' . (isset($_GET['price']) && sanitize_text_field($_GET['price']) === '2000+' ? ' selected' : '') . '>' . __('$2,000+', 'tznew') . '</option>';
    $output .= '</select>';
    $output .= '</div>';
    
    // Submit button
    $output .= '<div class="filter-submit flex space-x-2 col-span-1 md:col-span-2 lg:col-span-4 mt-2">';
    $output .= '<button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">' . __('Filter', 'tznew') . '</button>';
    $output .= '<a href="' . esc_url(get_post_type_archive_link($post_type)) . '" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">' . __('Reset', 'tznew') . '</a>';
    $output .= '</div>';
    
    $output .= '</form>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Filter query for trekking and tours
 */
function tznew_filter_query($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    
    $post_type = get_query_var('post_type');
    
    // Handle search form from front page
    if ($query->is_search()) {
        // Check if we have search form parameters
        if (isset($_GET['destination']) || isset($_GET['duration']) || isset($_GET['activity'])) {
            // Set post types based on activity parameter
            if (isset($_GET['activity']) && !empty($_GET['activity'])) {
                $activity = sanitize_text_field($_GET['activity']);
                if ($activity === 'trekking') {
                    $query->set('post_type', 'trekking');
                    $post_type = 'trekking';
                } elseif ($activity === 'tours') {
                    $query->set('post_type', 'tours');
                    $post_type = 'tours';
                } else {
                    // For 'climbing' or other activities, search both post types
                    $query->set('post_type', array('trekking', 'tours'));
                    $post_type = array('trekking', 'tours');
                }
            } else {
                // If no activity specified, search both post types
                $query->set('post_type', array('trekking', 'tours'));
                $post_type = array('trekking', 'tours');
            }
            
            // For search with filters, we want to show all matching posts
            $query->set('posts_per_page', -1);
            
            // Remove the default search query if it's just '*'
            if ($query->get('s') === '*') {
                $query->set('s', '');
            }
        }
    }
    
    if (in_array($post_type, array('trekking', 'tours')) || (is_array($post_type) && (in_array('trekking', $post_type) || in_array('tours', $post_type)))) {
        // Initialize meta_query and tax_query arrays
        $meta_query = array();
        $tax_query = array();
        
        // Featured/Popular filter
        if (isset($_GET['trek_type']) && !empty($_GET['trek_type'])) {
            $trek_type = sanitize_text_field($_GET['trek_type']);
            if ($trek_type === 'featured') {
                $meta_query[] = array(
                    'key' => 'featured',
                    'value' => '1',
                    'compare' => '='
                );
            } elseif ($trek_type === 'popular') {
                $meta_query[] = array(
                    'key' => 'popular',
                    'value' => '1',
                    'compare' => '='
                );
            }
        }
        
        // Region filter (handle both 'region' and 'destination' parameters)
        $region_param = '';
        if (isset($_GET['destination']) && !empty($_GET['destination'])) {
            $region_param = sanitize_text_field($_GET['destination']);
        } elseif (isset($_GET['region']) && !empty($_GET['region'])) {
            $region_param = sanitize_text_field($_GET['region']);
        }
        
        if (!empty($region_param)) {
            $tax_query[] = array(
                'taxonomy' => 'region',
                'field' => 'slug',
                'terms' => $region_param,
            );
        }
        
        // Post type specific filters
        if ($post_type === 'trekking') {
            // Difficulty filter
            if (isset($_GET['difficulty']) && !empty($_GET['difficulty'])) {
                $tax_query[] = array(
                    'taxonomy' => 'difficulty',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['difficulty']),
                );
            }
        } elseif ($post_type === 'tours') {
            // Tour Type filter
            if (isset($_GET['tour_type']) && !empty($_GET['tour_type'])) {
                $tax_query[] = array(
                    'taxonomy' => 'tour_type',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['tour_type']),
                );
            }
        }
        
        // Duration filter
        if (isset($_GET['duration']) && !empty($_GET['duration'])) {
            $duration_range = explode('-', sanitize_text_field($_GET['duration']));
            
            if (count($duration_range) === 2) {
                $min_duration = intval($duration_range[0]);
                $max_duration = intval($duration_range[1]);
                
                $meta_query[] = array(
                    'key' => 'duration',
                    'value' => array($min_duration, $max_duration),
                    'type' => 'NUMERIC',
                    'compare' => 'BETWEEN',
                );
            } elseif (strpos(sanitize_text_field($_GET['duration']), '+') !== false) {
			$min_duration = intval(sanitize_text_field($_GET['duration']));
                
                $meta_query[] = array(
                    'key' => 'duration',
                    'value' => $min_duration,
                    'type' => 'NUMERIC',
                    'compare' => '>=',
                );
            }
        }
        
        // Price filter
        if (isset($_GET['price']) && !empty($_GET['price'])) {
            $price_range = explode('-', sanitize_text_field($_GET['price']));
            
            if (count($price_range) === 2) {
                $min_price = intval($price_range[0]);
                $max_price = intval($price_range[1]);
                
                $meta_query[] = array(
                    'key' => 'cost',
                    'value' => array($min_price, $max_price),
                    'type' => 'NUMERIC',
                    'compare' => 'BETWEEN',
                );
            } elseif (strpos(sanitize_text_field($_GET['price']), '+') !== false) {
			$min_price = intval(sanitize_text_field($_GET['price']));
                
                $meta_query[] = array(
                    'key' => 'cost',
                    'value' => $min_price,
                    'type' => 'NUMERIC',
                    'compare' => '>=',
                );
            }
        }
        
        // Set the queries if they have content
        if (!empty($meta_query)) {
            if (count($meta_query) > 1) {
                $meta_query['relation'] = 'AND';
            }
            $query->set('meta_query', $meta_query);
        }
        
        if (!empty($tax_query)) {
            if (count($tax_query) > 1) {
                $tax_query['relation'] = 'AND';
            }
            $query->set('tax_query', $tax_query);
        }
    }
}
add_action('pre_get_posts', 'tznew_filter_query');

/**
 * Get booking CTA for trekking and tours
 */
function tznew_booking_cta($post_id) {
    if (!class_exists('WooCommerce')) {
        return '';
    }
    
    $post_type = get_post_type($post_id);
    
    if (!in_array($post_type, array('trekking', 'tours'))) {
        return '';
    }
    
    $cost = tznew_get_field_safe('cost', $post_id);
    $title = get_the_title($post_id);
    
    $output = '<div class="booking-cta">';
    $output .= '<h3>' . __('Book This', 'tznew') . ' ' . ($post_type === 'trekking' ? __('Trek', 'tznew') : __('Tour', 'tznew')) . '</h3>';
    
    if ($cost) {
        $output .= '<div class="price"><span>' . __('From', 'tznew') . '</span> $' . number_format($cost, 2) . '</div>';
    }
    
    // Check if WooCommerce Bookings is active
    if (class_exists('WC_Bookings')) {
        // Get product ID associated with this post (this would need to be set up in your system)
        $product_id = get_post_meta($post_id, '_booking_product_id', true);
        
        if ($product_id) {
            $output .= '<a href="' . esc_url(get_permalink($product_id)) . '" class="btn btn-primary btn-block">' . __('Book Now', 'tznew') . '</a>';
        } else {
            $output .= '<a href="' . esc_url(home_url('/contact/')) . '?subject=' . urlencode(__('Booking Inquiry for', 'tznew') . ' ' . $title) . '" class="btn btn-primary btn-block">' . __('Inquire Now', 'tznew') . '</a>';
        }
    } else {
        $output .= '<a href="' . esc_url(home_url('/contact/')) . '?subject=' . urlencode(__('Booking Inquiry for', 'tznew') . ' ' . $title) . '" class="btn btn-primary btn-block">' . __('Inquire Now', 'tznew') . '</a>';
    }
    
    $output .= '<div class="booking-info">';
    $output .= '<p><i class="fas fa-info-circle"></i> ' . __('Need help with your booking?', 'tznew') . '</p>';
    
    $booking_phone = tznew_get_company_phone();
    if ($booking_phone) {
        $phone_clean = str_replace(['+', '-', ' '], '', $booking_phone);
        $output .= '<p><i class="fas fa-phone"></i> <a href="tel:' . esc_attr($phone_clean) . '">' . esc_html($booking_phone) . '</a></p>';
    }
    
    $booking_email = tznew_get_booking_email();
    if ($booking_email) {
        $output .= '<p><i class="fas fa-envelope"></i> <a href="mailto:' . esc_attr($booking_email) . '">' . esc_html($booking_email) . '</a></p>';
    }
    
    $output .= '</div>';
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Get company information
 */
function tznew_company_info() {
    $company_name = tznew_get_company_name();
    $company_address = tznew_get_company_address();
    $company_phone = tznew_get_company_phone();
    $company_email = tznew_get_company_email();
    $site_url = home_url();
    
    $output = '<div class="company-info">';
    $output .= '<h4>' . esc_html($company_name) . '</h4>';
    $output .= '<address>';
    
    if ($company_address) {
        $output .= esc_html($company_address) . '<br>';
    }
    
    if ($company_phone) {
        $phone_clean = str_replace(['+', '-', ' '], '', $company_phone);
        $output .= '<i class="fas fa-phone"></i> <a href="tel:' . esc_attr($phone_clean) . '">' . esc_html($company_phone) . '</a><br>';
    }
    
    if ($company_email) {
        $output .= '<i class="fas fa-envelope"></i> <a href="mailto:' . esc_attr($company_email) . '">' . esc_html($company_email) . '</a><br>';
    }
    
    $output .= '<i class="fas fa-globe"></i> <a href="' . esc_url($site_url) . '" target="_blank">' . esc_html(parse_url($site_url, PHP_URL_HOST)) . '</a>';
    $output .= '</address>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Custom comment callback function
 */
function tznew_comment_callback($comment, $args, $depth) {
    $tag = ('div' === $args['style']) ? 'div' : 'li';
    $commenter = wp_get_current_commenter();
    $comment_author_url = get_comment_author_url($comment);
    $comment_author = get_comment_author($comment);
    $avatar = get_avatar($comment, $args['avatar_size'], '', $comment_author, array('class' => 'rounded-full'));
    
    ?>    
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? 'parent' : 'parent has-children', $comment); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body bg-white p-4 rounded-lg shadow-sm">
            <div class="comment-meta flex items-start mb-4">
                <?php if ($avatar) : ?>
                <div class="comment-author-avatar mr-4">
                    <?php echo $avatar; ?>
                </div>
                <?php endif; ?>
                
                <div class="comment-author-meta">
                    <div class="comment-author vcard">
                        <?php if (!empty($comment_author_url)) : ?>
                            <a href="<?php echo esc_url($comment_author_url); ?>" class="font-bold text-blue-600"><?php echo esc_html($comment_author); ?></a>
                        <?php else : ?>
                            <span class="font-bold"><?php echo esc_html($comment_author); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="comment-metadata text-sm text-gray-600">
                        <time datetime="<?php comment_time('c'); ?>">
                            <?php
                            printf(
                                _x('%1$s at %2$s', '1: date, 2: time', 'tznew'),
                                get_comment_date('', $comment),
                                get_comment_time()
                            );
                            ?>
                        </time>
                        <?php edit_comment_link(__('Edit', 'tznew'), ' <span class="edit-link">', '</span>'); ?>
                    </div>
                </div>
            </div>

            <div class="comment-content prose max-w-none">
                <?php comment_text(); ?>
            </div>

            <?php if ('0' == $comment->comment_approved) : ?>
            <p class="comment-awaiting-moderation bg-yellow-50 text-yellow-800 p-2 rounded mt-2">
                <?php _e('Your comment is awaiting moderation.', 'tznew'); ?>
            </p>
            <?php endif; ?>
            
            <div class="reply mt-2">
                <?php
                comment_reply_link(
                    array_merge(
                        $args,
                        array(
                            'add_below' => 'div-comment',
                            'depth'     => $depth,
                            'max_depth' => $args['max_depth'],
                            'before'    => '<div class="reply-link">',
                            'after'     => '</div>',
                        )
                    )
                );
                ?>
            </div>
        </article>
    <?php
}

/**
 * Prints HTML with meta information for the current post-date/time.
 */
function tznew_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date()),
        esc_attr(get_the_modified_date(DATE_W3C)),
        esc_html(get_the_modified_date())
    );

    $posted_on = sprintf(
        /* translators: %s: post date. */
        esc_html_x('Posted on %s', 'post date', 'tznew'),
        '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
    );

    echo '<span class="posted-on"><i class="fas fa-calendar-days mr-1"></i> ' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the current author.
 */
function tznew_posted_by() {
    $byline = sprintf(
        /* translators: %s: post author. */
        esc_html_x('by %s', 'post author', 'tznew'),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );

    echo '<span class="byline ml-3"><i class="fas fa-user mr-1"></i> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display filter form for trekking archive
 */
function tznew_trekking_filter_form() {
    echo '<div class="filter-container bg-white rounded-lg shadow-md p-6 mb-8">';
    echo '<h3 class="text-xl font-bold mb-4">' . __('Filter Treks', 'tznew') . '</h3>';
    echo tznew_get_filter_form('trekking');
    echo '</div>';
}

/**
 * Display filter form for tours archive
 */
function tznew_tours_filter_form() {
    echo '<div class="filter-container bg-white rounded-lg shadow-md p-6 mb-8">';
    echo '<h3 class="text-xl font-bold mb-4">' . __('Filter Tours', 'tznew') . '</h3>';
    echo tznew_get_filter_form('tours');
    echo '</div>';
}



/**
 * Safe wrapper for the_row() function
 */
if (!function_exists('tznew_the_row_safe')) {
	function tznew_the_row_safe() {
		if (function_exists('the_row')) {
			return the_row();
		}
		return false;
	}
}

/**
 * Safe wrapper for get_sub_field() function
 */
if (!function_exists('tznew_get_sub_field_safe')) {
	function tznew_get_sub_field_safe($field_name, $format_value = true) {
		if (function_exists('get_sub_field')) {
			return get_sub_field($field_name, $format_value);
		}
		return false;
	}
}

/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function tznew_entry_footer() {
    // Hide category and tag text for pages.
    if ('post' === get_post_type()) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list(esc_html__(', ', 'tznew'));
        if ($categories_list) {
            /* translators: 1: list of categories. */
            printf('<span class="cat-links mb-2 block"><span class="font-medium">%1$s</span> %2$s</span>',
                esc_html__('Categories:', 'tznew'),
                $categories_list
            );
        }

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list('', esc_html__(', ', 'tznew'));
        if ($tags_list) {
            /* translators: 1: list of tags. */
            printf('<span class="tags-links block"><span class="font-medium">%1$s</span> %2$s</span>',
                esc_html__('Tags:', 'tznew'),
                $tags_list
            );
        }
    }

    if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
        echo '<span class="comments-link mt-2 block">';
        comments_popup_link(
            sprintf(
                wp_kses(
                    /* translators: %s: post title */
                    __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'tznew'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            )
        );
        echo '</span>';
    }

    edit_post_link(
        sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __('Edit <span class="screen-reader-text">%s</span>', 'tznew'),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            get_the_title()
        ),
        '<span class="edit-link mt-2 block">',
        '</span>',
        null,
        'inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-1 px-3 rounded text-sm transition duration-300'
    );
}