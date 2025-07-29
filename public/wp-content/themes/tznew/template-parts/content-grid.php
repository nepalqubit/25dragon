<?php
/**
 * Template part for displaying posts in grid layout
 *
 * @package TZnew
 */

$post_type = get_post_type();
$post_id = get_the_ID();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('grid-item bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 ' . $post_type); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail relative overflow-hidden">
            <a href="<?php the_permalink(); ?>" class="block">
                <?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-48 object-cover hover:scale-105 transition-transform duration-300' ) ); ?>
            </a>
            
            <!-- Post Type Badge -->
            <div class="absolute top-4 left-4">
                <?php
                $badge_color = 'bg-blue-600';
                $badge_text = ucfirst($post_type);
                
                switch ($post_type) {
                    case 'trekking':
                        $badge_color = 'bg-green-600';
                        $badge_text = __('Trekking', 'tznew');
                        break;
                    case 'tours':
                        $badge_color = 'bg-purple-600';
                        $badge_text = __('Tour', 'tznew');
                        break;
                    case 'blog':
                        $badge_color = 'bg-blue-600';
                        $badge_text = __('Blog', 'tznew');
                        break;
                    case 'faq':
                        $badge_color = 'bg-teal-600';
                        $badge_text = __('FAQ', 'tznew');
                        break;
                }
                ?>
                <span class="<?php echo esc_attr($badge_color); ?> text-white px-2 py-1 rounded-full text-xs font-medium">
                    <?php echo esc_html($badge_text); ?>
                </span>
            </div>
            
            <!-- Price/Duration Badge for Trekking/Tours -->
            <?php if (in_array($post_type, ['trekking', 'tours'])) : ?>
                <div class="absolute top-4 right-4">
                    <?php
                    $price = get_field('price');
                    $duration = get_field('duration');
                    
                    if ($price) :
                    ?>
                        <div class="bg-white/90 backdrop-blur-sm text-gray-900 px-2 py-1 rounded-full text-xs font-semibold">
                            $<?php echo esc_html($price); ?>
                        </div>
                    <?php elseif ($duration) : ?>
                        <div class="bg-white/90 backdrop-blur-sm text-gray-900 px-2 py-1 rounded-full text-xs font-semibold">
                            <?php echo esc_html($duration); ?> <?php esc_html_e('days', 'tznew'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="p-6">
        <!-- Post Meta -->
        <div class="post-meta mb-3 flex items-center justify-between text-sm text-gray-500">
            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                <i class="fas fa-calendar-alt mr-1"></i>
                <?php echo esc_html( get_the_date() ); ?>
            </time>
            
            <?php if (in_array($post_type, ['trekking', 'tours'])) : ?>
                <?php
                $difficulty = get_the_terms($post_id, 'difficulty');
                $region = get_the_terms($post_id, 'region');
                
                if ($difficulty && !is_wp_error($difficulty)) :
                ?>
                    <span class="difficulty-badge bg-orange-100 text-orange-600 px-2 py-1 rounded text-xs">
                        <?php echo esc_html($difficulty[0]->name); ?>
                    </span>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <!-- Post Title -->
        <h2 class="post-title text-xl font-semibold mb-3 line-clamp-2">
            <a href="<?php the_permalink(); ?>" class="text-gray-900 hover:text-blue-600 transition-colors duration-300">
                <?php the_title(); ?>
            </a>
        </h2>
        
        <!-- Post Excerpt -->
        <div class="post-excerpt text-gray-600 mb-4 line-clamp-3">
            <?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?>
        </div>
        
        <!-- Post Details for Trekking/Tours -->
        <?php if (in_array($post_type, ['trekking', 'tours'])) : ?>
            <div class="post-details mb-4 space-y-2">
                <?php
                $max_altitude = get_field('max_altitude');
                $group_size = get_field('group_size');
                $best_season = get_field('best_season');
                
                if ($max_altitude) :
                ?>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-mountain text-gray-400 mr-2 w-4"></i>
                        <span><?php esc_html_e('Max Altitude:', 'tznew'); ?> <?php echo esc_html($max_altitude); ?>m</span>
                    </div>
                <?php endif; ?>
                
                <?php if ($group_size) : ?>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-users text-gray-400 mr-2 w-4"></i>
                        <span><?php esc_html_e('Group Size:', 'tznew'); ?> <?php echo esc_html($group_size); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($best_season) : ?>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar text-gray-400 mr-2 w-4"></i>
                        <span><?php esc_html_e('Best Season:', 'tznew'); ?> <?php echo esc_html($best_season); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- Post Footer -->
        <div class="post-footer flex items-center justify-between pt-4 border-t border-gray-100">
            <a href="<?php the_permalink(); ?>" class="btn-primary text-sm">
                <?php
                switch ($post_type) {
                    case 'trekking':
                        esc_html_e('View Trek', 'tznew');
                        break;
                    case 'tours':
                        esc_html_e('View Tour', 'tznew');
                        break;
                    case 'blog':
                        esc_html_e('Read More', 'tznew');
                        break;
                    case 'faq':
                        esc_html_e('Read FAQ', 'tznew');
                        break;
                    default:
                        esc_html_e('Read More', 'tznew');
                }
                ?>
                <i class="fas fa-arrow-right ml-1"></i>
            </a>
            
            <!-- Rating/Views -->
            <div class="flex items-center text-sm text-gray-500">
                <?php
                $rating = get_field('rating');
                $views = get_post_meta($post_id, 'post_views', true);
                
                if ($rating && in_array($post_type, ['trekking', 'tours'])) :
                ?>
                    <div class="flex items-center mr-3">
                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                        <span><?php echo esc_html($rating); ?></span>
                    </div>
                <?php endif; ?>
                
                <div class="flex items-center">
                    <i class="fas fa-eye mr-1"></i>
                    <span><?php echo esc_html($views ?: '0'); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Taxonomies -->
        <?php
        $taxonomies = [];
        
        switch ($post_type) {
            case 'trekking':
                $taxonomies = ['region', 'difficulty'];
                break;
            case 'tours':
                $taxonomies = ['region', 'tour_type'];
                break;
            case 'blog':
                $taxonomies = ['acf_tag'];
                break;
            case 'faq':
                $taxonomies = ['faq_category'];
                break;
        }
        
        if (!empty($taxonomies)) :
        ?>
            <div class="post-taxonomies mt-4 pt-4 border-t border-gray-100">
                <?php foreach ($taxonomies as $taxonomy) : ?>
                    <?php
                    $terms = get_the_terms($post_id, $taxonomy);
                    if ($terms && !is_wp_error($terms)) :
                    ?>
                        <div class="taxonomy-terms mb-2">
                            <div class="flex flex-wrap gap-1">
                                <?php foreach (array_slice($terms, 0, 3) as $term) : ?>
                                    <a href="<?php echo esc_url(get_term_link($term)); ?>" 
                                       class="inline-block bg-gray-100 hover:bg-blue-100 text-gray-600 hover:text-blue-600 px-2 py-1 rounded text-xs transition duration-300">
                                        <?php echo esc_html($term->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</article>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-300;
}
</style>