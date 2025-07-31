<?php
/**
 * The template for displaying Blog Tag taxonomy archives
 *
 * @package TZnew
 */

get_header();

// Get current term
$current_term = get_queried_object();
?>

<main id="primary" class="site-main">
    <!-- Tag Hero Section -->
    <section class="tag-hero bg-gradient-to-br from-indigo-600 via-blue-600 to-cyan-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="mb-6">
                    <i class="fas fa-tag text-6xl text-yellow-300"></i>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    <?php esc_html_e( 'Tagged:', 'tznew' ); ?> <?php echo esc_html( $current_term->name ); ?>
                </h1>
                
                <?php if ( $current_term->description ) : ?>
                    <div class="text-xl text-white/90 mb-8 max-w-3xl mx-auto">
                        <?php echo wp_kses_post( $current_term->description ); ?>
                    </div>
                <?php endif; ?>
                
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                        <i class="fas fa-hashtag mr-2"></i>
                        <?php esc_html_e( 'Blog Tag', 'tznew' ); ?>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                        <i class="fas fa-newspaper mr-2"></i>
                        <?php
                        global $wp_query;
                        echo esc_html( $wp_query->found_posts );
                        ?> <?php esc_html_e( 'articles', 'tznew' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-12">
        <?php if ( have_posts() ) : ?>
            <!-- Related Tags -->
            <div class="related-tags mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4"><?php esc_html_e( 'Related Topics', 'tznew' ); ?></h3>
                    <div class="flex flex-wrap gap-2">
                        <?php
                        // Get related tags
                        $related_tags = get_terms(array(
                            'taxonomy' => 'acf_tag',
                            'hide_empty' => true,
                            'exclude' => array($current_term->term_id),
                            'number' => 10
                        ));
                        
                        if (!is_wp_error($related_tags) && !empty($related_tags)) :
                            foreach ($related_tags as $tag) :
                        ?>
                            <a href="<?php echo esc_url(get_term_link($tag)); ?>" 
                               class="inline-block bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-600 px-3 py-1 rounded-full text-sm transition duration-300">
                                #<?php echo esc_html($tag->name); ?>
                            </a>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            </div>

            <!-- Blog Posts Grid -->
            <div class="blog-grid">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                    ?>
                        <article class="blog-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="blog-thumbnail relative overflow-hidden">
                                    <a href="<?php the_permalink(); ?>" class="block">
                                        <?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-48 object-cover hover:scale-105 transition-transform duration-300' ) ); ?>
                                    </a>
                                    <div class="absolute top-4 left-4">
                                        <span class="bg-blue-600 text-white px-2 py-1 rounded-full text-xs font-medium">
                                            <?php esc_html_e( 'Blog', 'tznew' ); ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-6">
                                <div class="mb-3">
                                    <time class="text-sm text-gray-500" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        <?php echo esc_html( get_the_date() ); ?>
                                    </time>
                                </div>
                                
                                <h2 class="text-xl font-semibold mb-3 line-clamp-2">
                                    <a href="<?php the_permalink(); ?>" class="text-gray-900 hover:text-blue-600 transition-colors duration-300">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
                                <div class="text-gray-600 mb-4 line-clamp-3">
                                    <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <a href="<?php the_permalink(); ?>" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                        <?php esc_html_e( 'Read More', 'tznew' ); ?>
                                        <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                    
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-eye mr-1"></i>
                                        <span><?php echo esc_html( get_post_meta( get_the_ID(), 'post_views', true ) ?: '0' ); ?></span>
                                    </div>
                                </div>
                                
                                <!-- Post Tags -->
                                <?php
                                $post_tags = get_the_terms( get_the_ID(), 'acf_tag' );
                                if ( $post_tags && !is_wp_error( $post_tags ) ) :
                                ?>
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <div class="flex flex-wrap gap-1">
                                            <?php foreach ( array_slice( $post_tags, 0, 3 ) as $tag ) : ?>
                                                <span class="inline-block bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">
                                                    #<?php echo esc_html( $tag->name ); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php
                    endwhile;
                    ?>
                </div>
            </div>

            <?php
            // Pagination
            tznew_pagination();
            ?>

        <?php else : ?>
            <div class="no-results text-center py-12">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-newspaper text-6xl text-gray-300 mb-6"></i>
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4">
                        <?php esc_html_e( 'No articles found', 'tznew' ); ?>
                    </h2>
                    <p class="text-gray-600 mb-6">
                        <?php esc_html_e( 'There are currently no blog articles with this tag.', 'tznew' ); ?>
                    </p>
                    <a href="<?php echo esc_url( home_url( '/blogs' ) ); ?>" class="btn btn-primary">
                        <?php esc_html_e( 'View All Blogs', 'tznew' ); ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

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
</style>

<?php
get_sidebar();
get_footer();
?>