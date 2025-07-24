<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZNEW
 */

get_header();
?>

<?php
// Check if Elementor Theme Builder template exists for archive/home
if ( function_exists( 'tznew_elementor_location_exists' ) && tznew_elementor_location_exists( 'archive' ) ) {
    // Use Elementor Theme Builder archive template
    tznew_elementor_do_location( 'archive' );
} else {
    // Fallback to default template
    ?>
    <main id="primary" class="site-main">
        <div class="container mx-auto px-4 py-8">
            <!-- Page Header -->
            <div class="page-header mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">
                    <?php
                    if ( is_home() && ! is_front_page() ) :
                        echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) );
                    elseif ( is_archive() ) :
                        the_archive_title();
                    elseif ( is_search() ) :
                        printf( esc_html__( 'Search Results for: %s', 'tznew' ), '<span>' . get_search_query() . '</span>' );
                    else :
                        esc_html_e( 'Latest Posts', 'tznew' );
                    endif;
                    ?>
                </h1>
                <?php if ( is_archive() && get_the_archive_description() ) : ?>
                    <div class="archive-description text-gray-600">
                        <?php the_archive_description(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <?php
                    if ( have_posts() ) :
                        /* Start the Loop */
                        while ( have_posts() ) :
                            the_post();

                            /*
                             * Include the Post-Type-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                             */
                            get_template_part( 'template-parts/content', get_post_type() );

                        endwhile;

                        the_posts_navigation();

                    else :

                        get_template_part( 'template-parts/content', 'none' );

                    endif;
                    ?>
                </div>

                <div class="lg:col-span-1">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>
    </main><!-- #main -->
    <?php
}
?>

<?php
get_footer();