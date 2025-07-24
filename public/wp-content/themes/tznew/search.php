<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package TZnew
 */

get_header();
?>

<?php
// Check if Elementor Theme Builder search results template exists
if ( function_exists( 'tznew_elementor_location_exists' ) && tznew_elementor_location_exists( 'search-results' ) ) {
    // Use Elementor Theme Builder search results template
    tznew_elementor_do_location( 'search-results' );
} else {
    // Fallback to default search template
    ?>
    <main id="primary" class="site-main">
        <!-- Search Hero Section -->
        <section class="search-hero bg-gradient-to-br from-blue-600 via-teal-600 to-green-600 text-white py-16">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">
                        <?php
                        if ( have_posts() ) {
                            global $wp_query;
                            /* translators: %1$s: search query, %2$d: number of results */
                            printf( esc_html__( 'Found %2$d results for "%1$s"', 'tznew' ), 
                                '<span class="text-yellow-300">' . get_search_query() . '</span>',
                                $wp_query->found_posts
                            );
                        } else {
                            /* translators: %s: search query */
                            printf( esc_html__( 'No results found for "%s"', 'tznew' ), 
                                '<span class="text-yellow-300">' . get_search_query() . '</span>'
                            );
                        }
                        ?>
                    </h1>
                    
                    <div class="max-w-2xl mx-auto">
                        <?php get_search_form(); ?>
                    </div>
                    
                    <?php if ( have_posts() ) : ?>
                        <div class="mt-8 flex flex-wrap justify-center gap-4 text-sm">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                                <i class="fas fa-search mr-2"></i>
                                <?php echo esc_html( $wp_query->found_posts ); ?> <?php esc_html_e( 'total results', 'tznew' ); ?>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                                <i class="fas fa-filter mr-2"></i>
                                <?php esc_html_e( 'All content types', 'tznew' ); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <div class="container mx-auto px-4 py-12">
            <?php if ( have_posts() ) : ?>
                <!-- Search Filters -->
                <div class="search-filters mb-8">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4"><?php esc_html_e( 'Filter Results', 'tznew' ); ?></h3>
                        <div class="flex flex-wrap gap-4">
                            <button class="filter-btn active" data-filter="all">
                                <?php esc_html_e( 'All Results', 'tznew' ); ?>
                                <span class="count"><?php echo esc_html( $wp_query->found_posts ); ?></span>
                            </button>
                            <?php
                            // Count posts by type
                            $post_types = [];
                            while ( have_posts() ) {
                                the_post();
                                $type = get_post_type();
                                $post_types[$type] = isset($post_types[$type]) ? $post_types[$type] + 1 : 1;
                            }
                            rewind_posts();
                            
                            foreach ( $post_types as $type => $count ) {
                                $type_obj = get_post_type_object( $type );
                                if ( $type_obj ) :
                            ?>
                                <button class="filter-btn" data-filter="<?php echo esc_attr( $type ); ?>">
                                    <?php echo esc_html( $type_obj->labels->name ); ?>
                                    <span class="count"><?php echo esc_html( $count ); ?></span>
                                </button>
                            <?php
                                endif;
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Search Results -->
                <div class="search-results-grid">
                    <?php
                    /* Start the Loop */
                    while ( have_posts() ) :
                        the_post();
                        get_template_part( 'template-parts/content', 'search' );
                    endwhile;
                    ?>
                </div>

                <?php
                // Previous/next page navigation.
                tznew_pagination();
                ?>

            <?php else : ?>
                <div class="no-results-section">
                    <?php get_template_part( 'template-parts/content', 'none' ); ?>
                    
                    <!-- Search Suggestions -->
                    <div class="mt-12">
                        <div class="bg-gray-50 rounded-lg p-8">
                            <h3 class="text-xl font-semibold mb-4"><?php esc_html_e( 'Popular Searches', 'tznew' ); ?></h3>
                            <div class="flex flex-wrap gap-3">
                                <?php
                                $popular_searches = ['Everest Base Camp', 'Annapurna Circuit', 'Langtang Valley', 'Manaslu Trek', 'Chitwan Safari'];
                                foreach ( $popular_searches as $search ) :
                                ?>
                                    <a href="<?php echo esc_url( home_url( '/?s=' . urlencode( $search ) ) ); ?>" 
                                       class="inline-block bg-white hover:bg-blue-50 text-gray-700 hover:text-blue-600 px-4 py-2 rounded-full border border-gray-200 hover:border-blue-300 transition duration-300">
                                        <?php echo esc_html( $search ); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </main><!-- #main -->
    <?php
}
?>

<?php
get_sidebar();
get_footer();