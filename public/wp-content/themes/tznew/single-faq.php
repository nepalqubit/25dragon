<?php
/**
 * The template for displaying single FAQ posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package TZnew
 */

get_header();
?>

<?php
// Check if Elementor Theme Builder single template exists for FAQ posts
if ( function_exists( 'tznew_elementor_location_exists' ) && tznew_elementor_location_exists( 'single' ) ) {
    // Use Elementor Theme Builder single template
    tznew_elementor_do_location( 'single' );
} else {
    // Fallback to default FAQ template
    ?>
    <main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-8">
		<div class="max-w-4xl mx-auto">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-lg overflow-hidden'); ?>>
					<div class="p-8">
						<?php
						// Display FAQ question as title
						$question = tznew_get_field_safe('faq_question');
						if ($question) :
							?>
							<h1 class="text-3xl font-bold text-gray-900 mb-6"><?php echo esc_html($question); ?></h1>
							<?php
						else :
							the_title('<h1 class="text-3xl font-bold text-gray-900 mb-6">', '</h1>');
						endif;
						
						// Display FAQ category
						$category_terms = get_the_terms(get_the_ID(), 'faq_category');
						if ($category_terms && !is_wp_error($category_terms)) :
							?>
							<div class="mb-4">
								<?php foreach ($category_terms as $term) : ?>
									<span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full mr-2">
										<?php echo esc_html($term->name); ?>
									</span>
								<?php endforeach; ?>
							</div>
							<?php
						endif;
						
						// Display FAQ answer
						$answer = tznew_get_field_safe('faq_answer');
						if ($answer) :
							?>
							<div class="prose prose-lg max-w-none">
								<h2 class="text-xl font-semibold text-gray-800 mb-4">Answer:</h2>
								<div class="text-gray-700 leading-relaxed">
									<?php echo wp_kses_post($answer); ?>
								</div>
							</div>
							<?php
						endif;
						
						// Display applicable to information
						$applicable_to = tznew_get_field_safe('faq_applicable_to');
						if ($applicable_to && is_array($applicable_to)) :
							?>
							<div class="mt-8 pt-6 border-t border-gray-200">
								<h3 class="text-lg font-semibold text-gray-800 mb-3">Applicable To:</h3>
								<div class="flex flex-wrap gap-2">
									<?php foreach ($applicable_to as $type) : ?>
										<span class="inline-block bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">
											<?php echo esc_html(ucfirst($type)); ?>
										</span>
									<?php endforeach; ?>
								</div>
							</div>
							<?php
						endif;
						?>
						
						<!-- Navigation -->
						<div class="mt-8 pt-6 border-t border-gray-200">
							<div class="flex justify-between items-center">
								<a href="<?php echo esc_url(get_post_type_archive_link('faq')); ?>" 
								   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
									<i class="fas fa-arrow-left mr-2"></i>
									Back to All FAQs
								</a>
								
								<div class="flex space-x-4">
									<?php
									$prev_post = get_previous_post();
									if ($prev_post) :
										?>
										<a href="<?php echo esc_url(get_permalink($prev_post)); ?>" 
										   class="inline-flex items-center px-3 py-2 text-gray-600 hover:text-blue-600 transition-colors">
											<i class="fas fa-chevron-left mr-1"></i>
											Previous
										</a>
										<?php
									endif;
									
									$next_post = get_next_post();
									if ($next_post) :
										?>
										<a href="<?php echo esc_url(get_permalink($next_post)); ?>" 
										   class="inline-flex items-center px-3 py-2 text-gray-600 hover:text-blue-600 transition-colors">
											Next
											<i class="fas fa-chevron-right ml-1"></i>
										</a>
										<?php
									endif;
									?>
								</div>
							</div>
						</div>
					</div>
				</article>
				<?php
			endwhile; // End of the loop.
			?>
		</div>
	</div>
</main><!-- #main -->
    <?php
}
?>

<?php
get_footer();