<?php
/**
 * Testimonial System Usage Examples
 *
 * This file contains examples of how to use the testimonial system
 * shortcodes and functions in your WordPress theme.
 *
 * @package TZnew
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/*
===========================================
TESTIMONIAL SHORTCODE USAGE EXAMPLES
===========================================

1. BASIC GRID LAYOUT (Default)
   [testimonials]
   
   This displays testimonials in a responsive grid layout with default settings:
   - Shows 6 testimonials
   - 3 columns on desktop
   - Includes featured testimonials
   - Shows all sources (manual, TripAdvisor, Google)

2. GRID WITH CUSTOM SETTINGS
   [testimonials layout="grid" limit="9" columns="3" show_featured="true" sources="manual,tripadvisor"]
   
   Parameters:
   - layout: "grid" (default), "slider", "list"
   - limit: Number of testimonials to show (default: 6)
   - columns: Number of columns for grid (1-4, default: 3)
   - show_featured: "true" or "false" (default: true)
   - sources: Comma-separated list: "manual", "tripadvisor", "google", "all" (default: all)
   - min_rating: Minimum star rating to display (1-5, default: 1)
   - order: "date", "rating", "random" (default: date)
   - order_direction: "ASC" or "DESC" (default: DESC)

3. SLIDER LAYOUT
   [testimonials layout="slider" limit="5" autoplay="true" autoplay_delay="5000"]
   
   Additional slider parameters:
   - autoplay: "true" or "false" (default: true)
   - autoplay_delay: Delay in milliseconds (default: 5000)
   - show_dots: "true" or "false" (default: true)
   - show_arrows: "true" or "false" (default: true)

4. LIST LAYOUT
   [testimonials layout="list" limit="10" show_excerpt="true" excerpt_length="150"]
   
   Additional list parameters:
   - show_excerpt: "true" or "false" (default: false for list)
   - excerpt_length: Number of characters for excerpt (default: 150)

5. FEATURED TESTIMONIALS ONLY
   [testimonials show_featured="only" limit="3" columns="3"]
   
   This shows only featured testimonials.

6. HIGH-RATED TESTIMONIALS
   [testimonials min_rating="4" order="rating" order_direction="DESC"]
   
   Shows only testimonials with 4+ stars, ordered by rating.

7. TRIPADVISOR REVIEWS ONLY
   [testimonials sources="tripadvisor" limit="6" layout="grid" columns="2"]
   
   Shows only TripAdvisor reviews in a 2-column grid.

8. RANDOM TESTIMONIALS
   [testimonials order="random" limit="4" layout="slider"]
   
   Shows 4 random testimonials in a slider.

===========================================
PHP FUNCTION USAGE EXAMPLES
===========================================

// Get testimonials programmatically
$testimonials = get_testimonials(array(
    'limit' => 5,
    'sources' => array('manual', 'tripadvisor'),
    'min_rating' => 4,
    'featured_only' => false,
    'order' => 'date',
    'order_direction' => 'DESC'
));

// Display testimonials in custom template
if (!empty($testimonials)) {
    echo '<div class="custom-testimonials">';
    foreach ($testimonials as $testimonial) {
        echo '<div class="custom-testimonial">';
        echo '<h3>' . esc_html($testimonial->post_title) . '</h3>';
        echo '<div class="rating">' . display_star_rating($testimonial->rating) . '</div>';
        echo '<p>' . esc_html($testimonial->post_excerpt) . '</p>';
        echo '<cite>' . esc_html($testimonial->guest_name) . '</cite>';
        echo '</div>';
    }
    echo '</div>';
}

// Get testimonial statistics
$stats = get_testimonial_stats();
echo 'Total Testimonials: ' . $stats['total'];
echo 'Average Rating: ' . $stats['average_rating'];
echo 'Featured Count: ' . $stats['featured_count'];

===========================================
CUSTOM CSS CLASSES
===========================================

You can customize the appearance using these CSS classes:

.testimonials-grid          - Grid container
.testimonials-slider        - Slider container
.testimonials-list          - List container
.testimonial-card           - Individual testimonial card
.testimonial-card.featured  - Featured testimonial card
.testimonial-content        - Testimonial content area
.testimonial-title          - Testimonial title
.testimonial-excerpt        - Testimonial excerpt
.testimonial-rating         - Rating container
.star-rating                - Star rating display
.testimonial-meta           - Meta information area
.testimonial-author         - Author information
.author-name                - Author name
.author-location            - Author location
.visit-date                 - Visit date
.testimonial-source         - Source badge
.featured-badge             - Featured badge

===========================================
WORDPRESS WIDGET USAGE
===========================================

The testimonial system can also be used in WordPress widgets:

1. Go to Appearance > Widgets
2. Add a "Custom HTML" widget
3. Insert any of the shortcodes above
4. Save the widget

Example widget content:
<h3>What Our Guests Say</h3>
[testimonials layout="list" limit="3" show_featured="only"]

===========================================
GUTENBERG BLOCK USAGE
===========================================

To use in Gutenberg editor:

1. Add a "Shortcode" block
2. Insert any of the shortcodes above
3. Preview or publish the page

Example:
[testimonials layout="slider" limit="5" sources="tripadvisor,google"]

===========================================
THEME INTEGRATION EXAMPLES
===========================================

// In your theme template files (e.g., front-page.php, page.php)

// Homepage testimonials section
echo '<section class="testimonials-section">';
echo '<div class="container">';
echo '<h2>What Our Guests Say</h2>';
echo do_shortcode('[testimonials layout="grid" limit="6" columns="3" show_featured="true"]');
echo '</div>';
echo '</section>';

// Sidebar testimonials
echo '<div class="sidebar-testimonials">';
echo '<h3>Recent Reviews</h3>';
echo do_shortcode('[testimonials layout="list" limit="3" show_excerpt="true" excerpt_length="100"]');
echo '</div>';

// Footer testimonials
echo '<div class="footer-testimonials">';
echo do_shortcode('[testimonials layout="slider" limit="5" autoplay="true" show_featured="only"]');
echo '</div>';

===========================================
ADMIN PANEL FEATURES
===========================================

Admin Panel Location: WordPress Admin > Testimonials

Features available:
1. Dashboard - Overview of testimonials and statistics
2. Manage Testimonials - List, edit, delete testimonials
3. Add New - Manually add guest testimonials
4. API Settings - Configure TripAdvisor and Google API settings

API Configuration:
- TripAdvisor: Requires API key and page URL
- Google Reviews: Requires API key and Place ID
- Automatic sync can be scheduled (hourly, daily, weekly)

Bulk Actions:
- Publish/Draft testimonials
- Feature/Unfeature testimonials
- Delete multiple testimonials
- Export testimonials (future feature)

===========================================
TROUBLESHOoting
===========================================

Common Issues:

1. Shortcode not displaying:
   - Check if testimonials exist in the database
   - Verify shortcode syntax
   - Check if theme supports shortcodes

2. Styling issues:
   - Ensure CSS files are loaded
   - Check for theme CSS conflicts
   - Verify responsive design on different devices

3. API sync not working:
   - Verify API keys are correct
   - Check API rate limits
   - Ensure URLs/Place IDs are valid
   - Check WordPress cron jobs

4. Performance issues:
   - Limit number of testimonials displayed
   - Enable caching if available
   - Optimize images if using custom avatars

For support, check the WordPress admin Testimonials > Settings page.

*/

// Example function to display testimonials in theme
function display_homepage_testimonials() {
    return do_shortcode('[testimonials layout="grid" limit="6" columns="3" show_featured="true" sources="all" min_rating="4"]');
}

// Example function to get testimonial count
function get_testimonial_count($source = 'all') {
    $args = array(
        'post_type' => 'testimonial',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids'
    );
    
    if ($source !== 'all') {
        $args['meta_query'] = array(
            array(
                'key' => '_testimonial_source',
                'value' => $source,
                'compare' => '='
            )
        );
    }
    
    $testimonials = get_posts($args);
    return count($testimonials);
}

// Example function to get average rating
function get_average_testimonial_rating() {
    global $wpdb;
    
    $average = $wpdb->get_var(
        "SELECT AVG(CAST(meta_value AS DECIMAL(3,2))) 
         FROM {$wpdb->postmeta} pm 
         INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID 
         WHERE pm.meta_key = '_testimonial_rating' 
         AND p.post_type = 'testimonial' 
         AND p.post_status = 'publish'"
    );
    
    return round($average, 1);
}

?>