<?php
/**
 * Testimonial Frontend Display
 *
 * @package TZnew
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Testimonial Frontend Class
 */
class TZnew_Testimonial_Frontend {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
    }
    
    public function init() {
        // Register shortcodes
        add_shortcode('testimonials', array($this, 'testimonials_shortcode'));
        add_shortcode('testimonial_slider', array($this, 'testimonial_slider_shortcode'));
        add_shortcode('testimonial_grid', array($this, 'testimonial_grid_shortcode'));
        
        // Enqueue frontend styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        
        // Add testimonial schema markup
        add_action('wp_head', array($this, 'add_testimonial_schema'));
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        wp_enqueue_style(
            'testimonial-frontend',
            get_template_directory_uri() . '/inc/testimonial-frontend.css',
            array(),
            TZNEW_VERSION
        );
        
        wp_enqueue_script(
            'testimonial-frontend',
            get_template_directory_uri() . '/inc/testimonial-frontend.js',
            array('jquery'),
            TZNEW_VERSION,
            true
        );
    }
    
    /**
     * Main testimonials shortcode
     */
    public function testimonials_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 6,
            'featured' => 'false',
            'homepage_featured' => 'false',
            'trekking_id' => '',
            'tour_id' => '',
            'source' => 'all', // all, manual, tripadvisor, google
            'rating' => 'all', // all, 5, 4, 3, 2, 1
            'layout' => 'grid', // grid, slider, list
            'columns' => 3,
            'show_rating' => 'true',
            'show_source' => 'false',
            'show_date' => 'true',
            'autoplay' => 'true',
            'autoplay_speed' => 5000
        ), $atts);
        
        switch ($atts['layout']) {
            case 'slider':
                return $this->testimonial_slider_shortcode($atts);
            case 'list':
                return $this->testimonial_list_shortcode($atts);
            default:
                return $this->testimonial_grid_shortcode($atts);
        }
    }
    
    /**
     * Testimonial grid shortcode
     */
    public function testimonial_grid_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 6,
            'featured' => 'false',
            'homepage_featured' => 'false',
            'trekking_id' => '',
            'tour_id' => '',
            'source' => 'all',
            'rating' => 'all',
            'columns' => 3,
            'show_rating' => 'true',
            'show_source' => 'false',
            'show_date' => 'true'
        ), $atts);
        
        $testimonials = $this->get_testimonials($atts);
        
        if (empty($testimonials)) {
            return '<p class="no-testimonials">' . __('No testimonials found.', 'tznew') . '</p>';
        }
        
        $columns = intval($atts['columns']);
        $columns = max(1, min(4, $columns)); // Limit between 1-4 columns
        
        ob_start();
        ?>
        <div class="testimonials-grid testimonials-grid-<?php echo $columns; ?>">
            <?php foreach ($testimonials as $testimonial): ?>
                <?php echo $this->render_testimonial_card($testimonial, $atts); ?>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Testimonial slider shortcode
     */
    public function testimonial_slider_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 10,
            'featured' => 'false',
            'homepage_featured' => 'false',
            'trekking_id' => '',
            'tour_id' => '',
            'source' => 'all',
            'rating' => 'all',
            'show_rating' => 'true',
            'show_source' => 'false',
            'show_date' => 'true',
            'autoplay' => 'true',
            'autoplay_speed' => 5000
        ), $atts);
        
        $testimonials = $this->get_testimonials($atts);
        
        if (empty($testimonials)) {
            return '<p class="no-testimonials">' . __('No testimonials found.', 'tznew') . '</p>';
        }
        
        $slider_id = 'testimonial-slider-' . uniqid();
        
        ob_start();
        ?>
        <div class="testimonials-slider" id="<?php echo $slider_id; ?>" 
             data-autoplay="<?php echo $atts['autoplay']; ?>" 
             data-autoplay-speed="<?php echo $atts['autoplay_speed']; ?>">
            <div class="testimonials-slider-wrapper">
                <?php foreach ($testimonials as $testimonial): ?>
                    <div class="testimonial-slide">
                        <?php echo $this->render_testimonial_card($testimonial, $atts); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($testimonials) > 1): ?>
            <div class="slider-controls">
                <button class="slider-prev" aria-label="<?php _e('Previous testimonial', 'tznew'); ?>">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="slider-next" aria-label="<?php _e('Next testimonial', 'tznew'); ?>">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <div class="slider-dots">
                <?php for ($i = 0; $i < count($testimonials); $i++): ?>
                    <button class="slider-dot <?php echo $i === 0 ? 'active' : ''; ?>" 
                            data-slide="<?php echo $i; ?>" 
                            aria-label="<?php printf(__('Go to testimonial %d', 'tznew'), $i + 1); ?>"></button>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Testimonial list shortcode
     */
    public function testimonial_list_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 10,
            'featured' => 'false',
            'homepage_featured' => 'false',
            'trekking_id' => '',
            'tour_id' => '',
            'source' => 'all',
            'rating' => 'all',
            'show_rating' => 'true',
            'show_source' => 'true',
            'show_date' => 'true'
        ), $atts);
        
        $testimonials = $this->get_testimonials($atts);
        
        if (empty($testimonials)) {
            return '<p class="no-testimonials">' . __('No testimonials found.', 'tznew') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="testimonials-list">
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="testimonial-list-item">
                    <?php echo $this->render_testimonial_card($testimonial, $atts, 'list'); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get testimonials based on criteria
     */
    private function get_testimonials($atts) {
        $args = array(
            'post_type' => 'testimonial',
            'post_status' => 'publish',
            'posts_per_page' => intval($atts['limit']),
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        $meta_query = array();
        
        // Filter by featured
        if ($atts['featured'] === 'true') {
            $meta_query[] = array(
                'key' => '_featured',
                'value' => '1',
                'compare' => '='
            );
        }
        
        // Filter by homepage featured
        if ($atts['homepage_featured'] === 'true') {
            $meta_query[] = array(
                'key' => '_homepage_featured',
                'value' => '1',
                'compare' => '='
            );
        }
        
        // Filter by assigned trekking
        if (!empty($atts['trekking_id'])) {
            $trekking_id = intval($atts['trekking_id']);
            $meta_query[] = array(
                'key' => '_assigned_trekking',
                'value' => serialize(strval($trekking_id)),
                'compare' => 'LIKE'
            );
        }
        
        // Filter by assigned tours
        if (!empty($atts['tour_id'])) {
            $tour_id = intval($atts['tour_id']);
            $meta_query[] = array(
                'key' => '_assigned_tours',
                'value' => serialize(strval($tour_id)),
                'compare' => 'LIKE'
            );
        }
        
        // Filter by rating
        if ($atts['rating'] !== 'all') {
            $rating = intval($atts['rating']);
            if ($rating >= 1 && $rating <= 5) {
                $meta_query[] = array(
                    'key' => '_rating',
                    'value' => $rating,
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                );
            }
        }
        
        if (!empty($meta_query)) {
            $args['meta_query'] = $meta_query;
        }
        
        // Filter by source
        if ($atts['source'] !== 'all') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'testimonial_source',
                    'field' => 'slug',
                    'terms' => $atts['source']
                )
            );
        }
        
        $query = new WP_Query($args);
        return $query->posts;
    }
    
    /**
     * Render a single testimonial card
     */
    private function render_testimonial_card($testimonial, $atts, $layout = 'card') {
        $guest_name = get_post_meta($testimonial->ID, '_customer_name', true);
        $guest_location = get_post_meta($testimonial->ID, '_customer_country', true);
        $rating = get_post_meta($testimonial->ID, '_rating', true);
        $visit_date = get_post_meta($testimonial->ID, '_trip_date', true);
        $source = get_post_meta($testimonial->ID, '_external_url', true);
        $is_featured = get_post_meta($testimonial->ID, '_featured', true);
        
        $source_terms = wp_get_post_terms($testimonial->ID, 'testimonial_source');
        $source_name = !empty($source_terms) ? $source_terms[0]->name : ucfirst($source);
        
        ob_start();
        ?>
        <div class="testimonial-card <?php echo $layout === 'list' ? 'testimonial-list-layout' : ''; ?> <?php echo $is_featured ? 'featured' : ''; ?>">
            <?php if ($is_featured): ?>
                <div class="featured-badge">
                    <i class="fas fa-star"></i>
                    <span><?php _e('Featured', 'tznew'); ?></span>
                </div>
            <?php endif; ?>
            
            <div class="testimonial-content">
                <div class="testimonial-text">
                    <?php if ($testimonial->post_title): ?>
                        <h3 class="testimonial-title"><?php echo esc_html($testimonial->post_title); ?></h3>
                    <?php endif; ?>
                    
                    <div class="testimonial-excerpt">
                        <?php 
                        $content = $testimonial->post_content;
                        if (strlen($content) > 200) {
                            $content = substr($content, 0, 200) . '...';
                        }
                        echo wp_kses_post($content);
                        ?>
                    </div>
                </div>
                
                <?php if ($atts['show_rating'] === 'true' && $rating): ?>
                    <div class="testimonial-rating">
                        <?php echo $this->render_star_rating($rating); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="testimonial-meta">
                <div class="testimonial-author">
                    <div class="author-info">
                        <div class="author-name"><?php echo esc_html($guest_name); ?></div>
                        <?php if ($guest_location): ?>
                            <div class="author-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo esc_html($guest_location); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="testimonial-footer">
                    <?php if ($atts['show_date'] === 'true' && $visit_date): ?>
                        <div class="visit-date">
                            <i class="fas fa-calendar-alt"></i>
                            <?php echo date('M Y', strtotime($visit_date)); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($atts['show_source'] === 'true' && $source): ?>
                        <div class="testimonial-source source-<?php echo esc_attr($source); ?>">
                            <?php 
                            switch ($source) {
                                case 'tripadvisor':
                                    echo '<i class="fab fa-tripadvisor"></i>';
                                    break;
                                case 'google':
                                    echo '<i class="fab fa-google"></i>';
                                    break;
                                default:
                                    echo '<i class="fas fa-user-edit"></i>';
                            }
                            ?>
                            <span><?php echo esc_html($source_name); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render star rating
     */
    private function render_star_rating($rating) {
        $rating = intval($rating);
        $output = '<div class="star-rating">';
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $output .= '<i class="fas fa-star filled"></i>';
            } else {
                $output .= '<i class="far fa-star empty"></i>';
            }
        }
        
        $output .= '</div>';
        return $output;
    }
    
    /**
     * Add testimonial schema markup
     */
    public function add_testimonial_schema() {
        if (!is_singular() && !is_front_page()) {
            return;
        }
        
        // Get featured testimonials for schema
        $testimonials = $this->get_testimonials(array(
            'limit' => 5,
            'featured' => 'true',
            'rating' => '4' // Only 4+ star reviews
        ));
        
        if (empty($testimonials)) {
            return;
        }
        
        $schema_reviews = array();
        
        foreach ($testimonials as $testimonial) {
            $guest_name = get_post_meta($testimonial->ID, '_customer_name', true);
            $rating = get_post_meta($testimonial->ID, '_rating', true);
            $visit_date = get_post_meta($testimonial->ID, '_trip_date', true);
            
            if ($guest_name && $rating) {
                $schema_reviews[] = array(
                    '@type' => 'Review',
                    'author' => array(
                        '@type' => 'Person',
                        'name' => $guest_name
                    ),
                    'reviewRating' => array(
                        '@type' => 'Rating',
                        'ratingValue' => $rating,
                        'bestRating' => '5'
                    ),
                    'reviewBody' => wp_strip_all_tags($testimonial->post_content),
                    'datePublished' => $visit_date ? date('c', strtotime($visit_date)) : date('c', strtotime($testimonial->post_date))
                );
            }
        }
        
        if (!empty($schema_reviews)) {
            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url(),
                'review' => $schema_reviews
            );
            
            echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
        }
    }
}

// Initialize the frontend class
new TZnew_Testimonial_Frontend();