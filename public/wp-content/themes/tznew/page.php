<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZNEW
 */

get_header();
?>

<?php
// Check if Elementor Theme Builder single template exists for pages
if ( function_exists( 'tznew_elementor_location_exists' ) && tznew_elementor_location_exists( 'single' ) ) {
    // Use Elementor Theme Builder single template
    tznew_elementor_do_location( 'single' );
} else {
    // Fallback to default page template
    ?>
    <main id="primary" class="site-main">
        <?php
        while ( have_posts() ) :
            the_post();

            get_template_part( 'template-parts/content', 'page' );

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>
    </main><!-- #main -->
    <?php
}
?>

<?php
get_sidebar();
get_footer();