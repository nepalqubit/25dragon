<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-md overflow-hidden'); ?>>
	<?php tznew_post_thumbnail('medium_large', 'w-full h-48 object-cover'); ?>
	
	<div class="entry-content p-6">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title text-2xl font-bold mb-4">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title text-xl font-bold mb-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta text-sm text-gray-600 mb-4">
				<?php
				tznew_posted_on();
				tznew_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>

		<?php
		if ( is_singular() ) :
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'tznew' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'tznew' ),
					'after'  => '</div>',
				)
			);
		else :
			the_excerpt();
			?>
			<div class="mt-4">
				<a href="<?php echo esc_url(get_permalink()); ?>" class="inline-block bg-gradient-to-r from-green-500 to-teal-600 hover:from-green-600 hover:to-teal-700 text-white font-medium py-2 px-4 rounded transition duration-300">
					<?php esc_html_e('Read More', 'tznew'); ?>
				</a>
			</div>
		<?php
		endif;
		?>
	</div><!-- .entry-content -->

	<?php if ( is_singular() ) : ?>
	<footer class="entry-footer p-6 bg-gray-50 border-t border-gray-200">
		<?php tznew_entry_footer(); ?>
	</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->