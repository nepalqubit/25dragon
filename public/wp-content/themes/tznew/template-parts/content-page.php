<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-md overflow-hidden'); ?>>
	<?php tznew_post_thumbnail('full', 'w-full h-auto'); ?>

	<div class="entry-content p-8">
		<?php
		the_title( '<h1 class="entry-title text-3xl font-bold mb-6">', '</h1>' );
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links my-4 p-4 bg-gray-50 rounded">' . esc_html__( 'Pages:', 'tznew' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer p-6 bg-gray-50 border-t border-gray-200">
			<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post. Only visible to screen readers */
					esc_html__( 'Edit %s', 'tznew' ),
					'<span class="screen-reader-text">' . get_the_title() . '</span>'
				),
				'<span class="edit-link">',
				'</span>',
				null,
				'inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-1 px-3 rounded text-sm transition duration-300'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->