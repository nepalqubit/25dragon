<?php
/**
 * Template part for displaying FAQ posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-md overflow-hidden'); ?>>
	<div class="entry-content p-6">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title text-3xl font-bold mb-6">', '</h1>' );
			
			// Display FAQ content
			if (function_exists('get_field')) :
				$question = tznew_get_field_safe('faq_question');
				$answer = tznew_get_field_safe('faq_answer');
				$category = tznew_get_field_safe('faq_category');
				
				if ($question && $answer) :
					?>
					<div class="faq-single">
						<?php if ($category) : ?>
							<div class="faq-category mb-4">
								<span class="inline-block bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
									<?php echo esc_html(ucfirst(str_replace('_', ' ', $category))); ?>
								</span>
							</div>
						<?php endif; ?>
						
						<div class="faq-item bg-gray-50 rounded-lg overflow-hidden mb-6">
							<div class="faq-question p-6 font-medium cursor-pointer flex justify-between items-center border-b border-gray-200">
								<h2 class="text-xl font-semibold text-gray-800 m-0"><?php echo esc_html($question); ?></h2>
								<span class="dashicons dashicons-arrow-down-alt2 text-teal-600"></span>
							</div>
							<div class="faq-answer p-6 hidden">
								<div class="prose max-w-none">
									<?php echo wp_kses_post($answer); ?>
								</div>
							</div>
						</div>
					</div>
					
					<script>
					// FAQ Toggle Script
					document.addEventListener('DOMContentLoaded', function() {
						const faqQuestion = document.querySelector('.faq-question');
						if (faqQuestion) {
							faqQuestion.addEventListener('click', function() {
								const answer = this.nextElementSibling;
								const icon = this.querySelector('.dashicons');
								
								// Toggle answer visibility
								answer.classList.toggle('hidden');
								
								// Toggle icon
								if (answer.classList.contains('hidden')) {
									icon.classList.remove('dashicons-arrow-up-alt2');
									icon.classList.add('dashicons-arrow-down-alt2');
								} else {
									icon.classList.remove('dashicons-arrow-down-alt2');
									icon.classList.add('dashicons-arrow-up-alt2');
								}
							});
						}
					});
					</script>
					<?php
				else :
					?>
					<div class="faq-empty p-6 bg-yellow-50 rounded-lg">
						<p class="text-yellow-800"><?php esc_html_e('No FAQ content available.', 'tznew'); ?></p>
					</div>
					<?php
				endif;
				
			endif; // End if function_exists('get_field')
			
		else :
			// Archive view
			the_title( '<h2 class="entry-title text-xl font-bold mb-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			
			// Display FAQ excerpt
			if (function_exists('get_field')) :
				$question = tznew_get_field_safe('faq_question');
				$answer = tznew_get_field_safe('faq_answer');
				$category = tznew_get_field_safe('faq_category');
				
				if ($category) :
					?>
					<div class="faq-category-preview text-sm text-gray-600 mb-2">
						<span class="inline-block bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
							<?php echo esc_html(ucfirst(str_replace('_', ' ', $category))); ?>
						</span>
					</div>
					<?php
				endif;
				
				if ($question && $answer) :
					?>
					<div class="faq-preview mb-4">
						<div class="faq-question-preview font-medium mb-2 text-gray-800">
							<?php echo esc_html($question); ?>
						</div>
						<div class="faq-answer-preview text-gray-600">
							<?php echo wp_trim_words(wp_strip_all_tags($answer), 20, '...'); ?>
						</div>
					</div>
					<?php
				else :
					the_excerpt();
				endif;
			else :
				the_excerpt();
			endif;
			?>
			
			<div class="mt-4">
				<a href="<?php echo esc_url(get_permalink()); ?>" class="inline-block bg-gradient-to-r from-green-500 to-teal-600 hover:from-green-600 hover:to-teal-700 text-white font-medium py-2 px-4 rounded transition duration-300">
					<?php esc_html_e('Read FAQ', 'tznew'); ?>
				</a>
			</div>
			
		<?php endif; // End is_singular() check ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
