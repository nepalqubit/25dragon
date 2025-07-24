<?php
/**
 * The template for displaying FAQ archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

get_header();
?>

<?php
// Check if Elementor Theme Builder archive template exists for FAQ
if ( function_exists( 'tznew_elementor_location_exists' ) && tznew_elementor_location_exists( 'archive' ) ) {
    // Use Elementor Theme Builder archive template
    tznew_elementor_do_location( 'archive' );
} else {
    // Fallback to default FAQ archive template
    ?>
    <main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-8">
		<header class="page-header mb-8">
			<h1 class="page-title text-4xl font-bold text-center text-gray-900 mb-4">
				<?php post_type_archive_title(); ?>
			</h1>
			<p class="text-lg text-gray-600 text-center max-w-2xl mx-auto">
				Find answers to commonly asked questions about our tours and trekking packages.
			</p>
		</header><!-- .page-header -->

		<?php if ( have_posts() ) : ?>
			<!-- FAQ Categories Filter -->
			<?php
			$faq_categories = get_terms(array(
				'taxonomy' => 'faq_category',
				'hide_empty' => true,
			));
			
			if ($faq_categories && !is_wp_error($faq_categories)) :
				?>
				<div class="mb-8">
					<div class="flex flex-wrap justify-center gap-2">
						<a href="<?php echo esc_url(get_post_type_archive_link('faq')); ?>" 
						   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
							All FAQs
						</a>
						<?php foreach ($faq_categories as $category) : ?>
							<a href="<?php echo esc_url(get_term_link($category)); ?>" 
							   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
								<?php echo esc_html($category->name); ?>
								<span class="ml-1 text-sm text-gray-500">(<?php echo $category->count; ?>)</span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
				<?php
			endif;
			?>

			<!-- FAQ List -->
			<div class="max-w-4xl mx-auto">
				<div class="space-y-4">
					<?php
					$faq_count = 0;
					while ( have_posts() ) :
						the_post();
						$faq_count++;
						
						$question = tznew_get_field_safe('faq_question');
						$answer = tznew_get_field_safe('faq_answer');
						$category_terms = get_the_terms(get_the_ID(), 'faq_category');
						?>
						<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
							<div class="faq-item">
								<button class="faq-question w-full text-left p-6 focus:outline-none focus:ring-2 focus:ring-blue-500 hover:bg-gray-50 transition-colors" 
								        data-faq="<?php echo $faq_count; ?>">
									<div class="flex justify-between items-start">
										<div class="flex-1">
											<?php if ($category_terms && !is_wp_error($category_terms)) : ?>
												<div class="mb-2">
													<?php foreach ($category_terms as $term) : ?>
														<span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-2">
															<?php echo esc_html($term->name); ?>
														</span>
													<?php endforeach; ?>
												</div>
											<?php endif; ?>
											<h3 class="text-lg font-semibold text-gray-900">
												<?php echo $question ? esc_html($question) : get_the_title(); ?>
											</h3>
										</div>
										<div class="ml-4">
											<i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200" 
											   id="icon-<?php echo $faq_count; ?>"></i>
										</div>
									</div>
								</button>
								
								<div class="faq-answer hidden p-6 pt-0 border-t border-gray-100" 
								     id="answer-<?php echo $faq_count; ?>">
									<div class="prose prose-gray max-w-none">
										<?php echo $answer ? wp_kses_post($answer) : 'No answer provided.'; ?>
									</div>
									
									<!-- Link to full FAQ page -->
									<div class="mt-4 pt-4 border-t border-gray-100">
										<a href="<?php the_permalink(); ?>" 
										   class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
											View Full Details
											<i class="fas fa-arrow-right ml-2"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
						<?php
					endwhile;
					?>
				</div>
				
				<!-- Pagination -->
				<div class="mt-12">
					<?php
					the_posts_pagination(array(
						'mid_size' => 2,
						'prev_text' => '<i class="fas fa-chevron-left"></i> Previous',
						'next_text' => 'Next <i class="fas fa-chevron-right"></i>',
						'class' => 'flex justify-center space-x-2',
					));
					?>
				</div>
			</div>

		<?php else : ?>
			<div class="text-center py-12">
				<div class="max-w-md mx-auto">
					<i class="fas fa-question-circle text-6xl text-gray-300 mb-4"></i>
					<h2 class="text-2xl font-semibold text-gray-700 mb-2">No FAQs Found</h2>
					<p class="text-gray-500">There are currently no FAQs available. Please check back later.</p>
				</div>
			</div>
		<?php endif; ?>
	</div>
</main><!-- #main -->

<script>
// FAQ Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
	const faqQuestions = document.querySelectorAll('.faq-question');
	
	faqQuestions.forEach(function(question) {
		question.addEventListener('click', function() {
			const faqId = this.getAttribute('data-faq');
			const answer = document.getElementById('answer-' + faqId);
			const icon = document.getElementById('icon-' + faqId);
			
			if (answer.classList.contains('hidden')) {
				answer.classList.remove('hidden');
				icon.classList.add('rotate-180');
			} else {
				answer.classList.add('hidden');
				icon.classList.remove('rotate-180');
			}
		});
	});
});
</script>
    <?php
}
?>

<?php
get_footer();