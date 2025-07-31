<?php
/**
 * The template for displaying FAQ category taxonomy archive
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-8">
		<header class="page-header mb-8">
			<?php
			$term = get_queried_object();
			?>
			<h1 class="page-title text-4xl font-bold text-center text-gray-900 mb-4">
				<?php echo esc_html($term->name); ?> FAQs
			</h1>
			<?php if ($term->description) : ?>
				<p class="text-lg text-gray-600 text-center max-w-2xl mx-auto">
					<?php echo esc_html($term->description); ?>
				</p>
			<?php else : ?>
				<p class="text-lg text-gray-600 text-center max-w-2xl mx-auto">
					Frequently asked questions about <?php echo esc_html(strtolower($term->name)); ?>.
				</p>
			<?php endif; ?>
		</header><!-- .page-header -->

		<!-- Breadcrumb Navigation -->
		<div class="mb-8">
			<nav class="flex justify-center" aria-label="Breadcrumb">
				<ol class="inline-flex items-center space-x-1 md:space-x-3">
					<li class="inline-flex items-center">
						<a href="<?php echo esc_url(home_url('/')); ?>" 
						   class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
							<i class="fas fa-home mr-2"></i>
							Home
						</a>
					</li>
					<li>
						<div class="flex items-center">
							<i class="fas fa-chevron-right text-gray-400 mx-2"></i>
							<a href="<?php echo esc_url(get_post_type_archive_link('faq')); ?>" 
							   class="text-sm font-medium text-gray-700 hover:text-blue-600">
								FAQs
							</a>
						</div>
					</li>
					<li aria-current="page">
						<div class="flex items-center">
							<i class="fas fa-chevron-right text-gray-400 mx-2"></i>
							<span class="text-sm font-medium text-gray-500">
								<?php echo esc_html($term->name); ?>
							</span>
						</div>
					</li>
				</ol>
			</nav>
		</div>

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
					   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
						All FAQs
					</a>
					<?php foreach ($faq_categories as $category) : ?>
						<?php if ($category->term_id === $term->term_id) : ?>
							<span class="px-4 py-2 bg-blue-600 text-white rounded-lg">
								<?php echo esc_html($category->name); ?>
								<span class="ml-1 text-sm">(<?php echo $category->count; ?>)</span>
							</span>
						<?php else : ?>
							<a href="<?php echo esc_url(get_term_link($category)); ?>" 
							   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
								<?php echo esc_html($category->name); ?>
								<span class="ml-1 text-sm text-gray-500">(<?php echo $category->count; ?>)</span>
							</a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		endif;
		?>

		<?php if ( have_posts() ) : ?>
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
						$applicable_to = tznew_get_field_safe('faq_applicable_to');
						?>
						<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
							<div class="faq-item">
								<button class="faq-question w-full text-left p-6 focus:outline-none focus:ring-2 focus:ring-blue-500 hover:bg-gray-50 transition-colors" 
								        data-faq="<?php echo $faq_count; ?>">
									<div class="flex justify-between items-start">
										<div class="flex-1">
											<?php if ($applicable_to && is_array($applicable_to)) : ?>
												<div class="mb-2">
													<?php foreach ($applicable_to as $type) : ?>
														<span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full mr-2">
															<?php echo esc_html(ucfirst($type)); ?>
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
					<p class="text-gray-500">There are currently no FAQs in this category. Please check back later or browse other categories.</p>
					<div class="mt-6">
						<a href="<?php echo esc_url(get_post_type_archive_link('faq')); ?>" 
						   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
							<i class="fas fa-arrow-left mr-2"></i>
							View All FAQs
						</a>
					</div>
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
get_footer();