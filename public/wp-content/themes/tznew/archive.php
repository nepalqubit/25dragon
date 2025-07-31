<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

get_header();
?>

<?php
// Check if Elementor Theme Builder archive template exists
if ( function_exists( 'tznew_elementor_location_exists' ) && tznew_elementor_location_exists( 'archive' ) ) {
    // Use Elementor Theme Builder archive template
    tznew_elementor_do_location( 'archive' );
} else {
    // Fallback to default archive template
    ?>
    <main id="primary" class="site-main container mx-auto py-8 px-4">

    	<?php if ( have_posts() ) : ?>

    		<header class="page-header mb-8">
    			<?php
    			the_archive_title( '<h1 class="page-title text-3xl font-bold mb-4">', '</h1>' );
    			the_archive_description( '<div class="archive-description prose">', '</div>' );
    			?>
    		</header><!-- .page-header -->

    		<?php
    		// Check if this is a custom post type archive
    		$post_type = get_post_type();
    		if ($post_type == 'trekking' || $post_type == 'tours') :
    			// Display filter form for trekking and tours
    			if ($post_type == 'trekking') {
    				tznew_trekking_filter_form();
    			} elseif ($post_type == 'tours') {
    				tznew_tours_filter_form();
    			}
    			?>
    			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    				<?php
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
    				?>
    			</div>
    		<?php
    		elseif ($post_type == 'faq') :
    			// Display FAQs in a single column layout
    			?>
    			<div class="space-y-6">
    				<?php
    				/* Start the Loop */
    				while ( have_posts() ) :
    					the_post();
    					get_template_part( 'template-parts/content', get_post_type() );
    				endwhile;
    				?>
    			</div>
    		<?php
    		else :
    			// Default grid layout for other archives
    			?>
    			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    				<?php
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
    				?>
    			</div>
    		<?php endif; ?>

    		<?php
    		// Previous/next page navigation.
    		tznew_pagination();

    	else :

    		get_template_part( 'template-parts/content', 'none' );

    	endif;
    	?>

    </main><!-- #main -->
    <?php
}
?>

<?php
get_sidebar();
get_footer();