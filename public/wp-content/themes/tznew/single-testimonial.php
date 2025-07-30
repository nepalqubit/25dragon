<?php
/**
 * The template for displaying single testimonial posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package TZnew
 */

get_header();
?>

<main class="single-testimonial-page">
	<?php
	while ( have_posts() ) :
		the_post();
		
		// Get testimonial meta data
		$guest_name = get_post_meta(get_the_ID(), '_customer_name', true);
		$guest_location = get_post_meta(get_the_ID(), '_customer_country', true);
		$rating = get_post_meta(get_the_ID(), '_rating', true);
		$visit_date = get_post_meta(get_the_ID(), '_trip_date', true);
		$source = get_post_meta(get_the_ID(), '_external_url', true);
		$is_featured = get_post_meta(get_the_ID(), '_featured', true);
		$trekking_id = get_post_meta(get_the_ID(), '_trekking_id', true);
		$tour_id = get_post_meta(get_the_ID(), '_tour_id', true);
		
		// Get source terms
		$source_terms = wp_get_post_terms(get_the_ID(), 'testimonial_source');
		$source_name = !empty($source_terms) ? $source_terms[0]->name : 'Website';
		
		// Get related trip
		$related_trip = null;
		$trip_type = '';
		if ($trekking_id) {
			$related_trip = get_post($trekking_id);
			$trip_type = 'trekking';
		} elseif ($tour_id) {
			$related_trip = get_post($tour_id);
			$trip_type = 'tours';
		}
	?>

	<!-- Hero Section -->
	<section class="relative bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 py-20 lg:py-32">
		<div class="absolute inset-0 bg-black/20"></div>
		<div class="relative container mx-auto px-4 text-center text-white">
			<?php if ($is_featured): ?>
				<div class="inline-flex items-center bg-yellow-400 text-yellow-900 px-4 py-2 rounded-full text-sm font-semibold mb-6">
					<i class="fas fa-star mr-2"></i>
					Featured Review
				</div>
			<?php endif; ?>
			
			<h1 class="text-3xl lg:text-5xl font-bold mb-6">
				<?php echo esc_html($guest_name ?: 'Customer Review'); ?>
			</h1>
			
			<?php if ($rating): ?>
				<div class="flex items-center justify-center gap-2 mb-6">
					<div class="flex text-yellow-400 text-2xl">
						<?php for ($i = 1; $i <= 5; $i++): ?>
							<i class="fas fa-star<?php echo $i <= $rating ? '' : ' opacity-30'; ?>"></i>
						<?php endfor; ?>
					</div>
					<span class="text-xl font-semibold"><?php echo esc_html($rating); ?>/5</span>
				</div>
			<?php endif; ?>
			
			<?php if ($guest_location): ?>
				<p class="text-lg opacity-90 mb-4">
					<i class="fas fa-map-marker-alt mr-2"></i>
					<?php echo esc_html($guest_location); ?>
				</p>
			<?php endif; ?>
			
			<?php if ($visit_date): ?>
				<p class="text-lg opacity-90">
					<i class="fas fa-calendar mr-2"></i>
					Visited: <?php echo esc_html(date('F Y', strtotime($visit_date))); ?>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<!-- Testimonial Content -->
	<section class="py-16 bg-white">
		<div class="container mx-auto px-4">
			<div class="max-w-4xl mx-auto">
				<!-- Main Review -->
				<div class="bg-gray-50 rounded-3xl p-8 lg:p-12 mb-12 relative">
					<div class="absolute top-6 left-6 text-6xl text-blue-200 opacity-50">
						<i class="fas fa-quote-left"></i>
					</div>
					
					<div class="relative z-10">
						<?php if (get_the_title()): ?>
							<h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-6">
								<?php the_title(); ?>
							</h2>
						<?php endif; ?>
						
						<div class="text-lg lg:text-xl text-gray-700 leading-relaxed mb-8">
							<?php 
							if (get_the_content()) {
								the_content();
							} elseif (get_the_excerpt()) {
								echo '<p>' . get_the_excerpt() . '</p>';
							} else {
								echo '<p>This customer had an amazing experience with us!</p>';
							}
							?>
						</div>
						
						<!-- Author Info -->
						<div class="flex items-center justify-between flex-wrap gap-4">
							<div class="flex items-center gap-4">
								<div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
									<?php echo esc_html(substr($guest_name ?: 'C', 0, 1)); ?>
								</div>
								<div>
									<h3 class="text-xl font-bold text-gray-900"><?php echo esc_html($guest_name ?: 'Valued Customer'); ?></h3>
									<?php if ($guest_location): ?>
										<p class="text-gray-600 flex items-center gap-1">
											<i class="fas fa-map-marker-alt text-sm"></i>
											<?php echo esc_html($guest_location); ?>
										</p>
									<?php endif; ?>
								</div>
							</div>
							
							<div class="flex items-center gap-4 text-sm text-gray-500">
								<?php if ($source_name): ?>
									<span class="flex items-center gap-1">
										<i class="fas fa-external-link-alt"></i>
										<?php echo esc_html($source_name); ?>
									</span>
								<?php endif; ?>
								
								<?php if ($visit_date): ?>
									<span class="flex items-center gap-1">
										<i class="fas fa-calendar"></i>
										<?php echo esc_html(date('M Y', strtotime($visit_date))); ?>
									</span>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Related Trip -->
				<?php if ($related_trip): ?>
					<div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-8 mb-12">
						<h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">
							<i class="fas fa-<?php echo $trip_type === 'trekking' ? 'mountain' : 'route'; ?> text-green-600 mr-3"></i>
							Experienced Trip
						</h3>
						
						<div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
							<div class="flex items-center gap-6">
								<?php if (has_post_thumbnail($related_trip->ID)): ?>
									<img src="<?php echo esc_url(get_the_post_thumbnail_url($related_trip->ID, 'medium')); ?>" 
										 alt="<?php echo esc_attr($related_trip->post_title); ?>" 
										 class="w-24 h-24 object-cover rounded-lg">
								<?php else: ?>
									<div class="w-24 h-24 bg-gradient-to-br from-green-500 to-blue-600 rounded-lg flex items-center justify-center text-white text-2xl">
										<i class="fas fa-<?php echo $trip_type === 'trekking' ? 'mountain' : 'route'; ?>"></i>
									</div>
								<?php endif; ?>
								
								<div class="flex-1">
									<h4 class="text-xl font-bold text-gray-900 mb-2">
										<?php echo esc_html($related_trip->post_title); ?>
									</h4>
									<p class="text-gray-600 mb-4">
										<?php echo esc_html(wp_trim_words($related_trip->post_excerpt ?: $related_trip->post_content, 20)); ?>
									</p>
									<a href="<?php echo esc_url(get_permalink($related_trip->ID)); ?>" 
									   class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors duration-300">
										<i class="fas fa-eye mr-2"></i>
										View Trip Details
									</a>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
				
				<!-- Navigation -->
				<div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
					<a href="<?php echo esc_url(home_url('/testimonials')); ?>" 
					   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-300">
						<i class="fas fa-arrow-left mr-2"></i>
						Back to All Reviews
					</a>
					
					<?php if ($related_trip): ?>
						<a href="<?php echo esc_url(site_url('/booking?' . $trip_type . '_id=' . $related_trip->ID)); ?>" 
						   class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-300">
							<i class="fas fa-calendar-check mr-2"></i>
							Book This Trip
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- Related Testimonials -->
	<section class="py-16 bg-gray-50">
		<div class="container mx-auto px-4">
			<div class="text-center mb-12">
				<h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
					More Customer Reviews
				</h2>
				<p class="text-lg text-gray-600 max-w-2xl mx-auto">
					Discover what other travelers are saying about their experiences.
				</p>
			</div>
			
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
				<?php 
				// Get related testimonials (exclude current one)
				$related_args = array(
					'limit' => '6',
					'layout' => 'grid',
					'columns' => '3',
					'show_rating' => 'true',
					'show_date' => 'true',
					'exclude' => get_the_ID()
				);
				
				// If related to specific trip, show testimonials from same trip
				if ($trekking_id) {
					$related_args['trekking_id'] = $trekking_id;
				} elseif ($tour_id) {
					$related_args['tour_id'] = $tour_id;
				}
				
				echo do_shortcode('[testimonials ' . http_build_query($related_args, '', ' ') . ']');
				?>
			</div>
			
			<div class="text-center mt-12">
				<a href="<?php echo esc_url(home_url('/testimonials')); ?>" 
				   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-300">
					<i class="fas fa-comments mr-2"></i>
					View All Testimonials
				</a>
			</div>
		</div>
	</section>

	<?php
	endwhile; // End of the loop.
	?>
</main>

<style>
.single-testimonial-page .testimonial-card {
	@apply bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100;
}

.single-testimonial-page .testimonial-card .testimonial-rating {
	@apply mb-4;
}

.single-testimonial-page .testimonial-card .star-rating {
	@apply flex gap-1;
}

.single-testimonial-page .testimonial-card .star-rating .filled {
	@apply text-yellow-400;
}

.single-testimonial-page .testimonial-card .star-rating .empty {
	@apply text-gray-300;
}

.single-testimonial-page .testimonial-card .testimonial-excerpt {
	@apply text-gray-700 mb-4 italic leading-relaxed;
}

.single-testimonial-page .testimonial-card .author-name {
	@apply font-semibold text-gray-900 mb-1;
}

.single-testimonial-page .testimonial-card .author-location {
	@apply text-sm text-gray-600 flex items-center gap-1;
}

.single-testimonial-page .testimonial-card .visit-date {
	@apply text-sm text-gray-500 flex items-center gap-1;
}

.single-testimonial-page .testimonial-card.featured {
	@apply ring-2 ring-yellow-400 relative;
}

.single-testimonial-page .testimonial-card .featured-badge {
	@apply absolute -top-3 -right-3 bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-1;
}
</style>

<?php
get_footer();