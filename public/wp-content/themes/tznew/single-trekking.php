<?php
/**
 * The template for displaying single trekking posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package TZnew
 */

get_header();
?>

<?php
// Check if Elementor Theme Builder single template exists for trekking posts
if ( function_exists( 'tznew_elementor_location_exists' ) && tznew_elementor_location_exists( 'single' ) ) {
    // Use Elementor Theme Builder single template
    tznew_elementor_do_location( 'single' );
} else {
    // Fallback to default trekking template
    ?>
    <main id="primary" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		
		<!-- Hero Section -->
		<section class="relative h-screen min-h-[700px] overflow-hidden">
			<?php if (has_post_thumbnail()) : ?>
				<div class="absolute inset-0">
					<?php the_post_thumbnail('full', array('class' => 'w-full h-full object-cover scale-105 hover:scale-100 transition-transform duration-[3s]')); ?>
				</div>
				<div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-black/20"></div>
				<div class="absolute inset-0 bg-gradient-to-r from-blue-900/30 to-transparent"></div>
			<?php else : ?>
				<div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800"></div>
			<?php endif; ?>
			
			<!-- Floating Elements -->
			<div class="absolute top-20 right-10 w-32 h-32 bg-white/10 rounded-full blur-xl animate-pulse"></div>
			<div class="absolute bottom-40 left-20 w-20 h-20 bg-blue-400/20 rounded-full blur-lg animate-bounce"></div>
			
			<div class="relative z-10 container mx-auto px-4 h-full flex items-center">
				<div class="text-white max-w-5xl">
					<div class="flex items-center gap-2 mb-6">
						<?php
						$regions = get_the_terms(get_the_ID(), 'region');
						if ($regions && !is_wp_error($regions)) :
							foreach ($regions as $region) :
						?>
							<div class="inline-flex items-center bg-gradient-to-r from-blue-600/90 to-indigo-600/90 backdrop-blur-md px-6 py-3 rounded-full border border-white/20 shadow-lg">
								<i class="fas fa-location-dot mr-3 text-blue-200"></i>
								<span class="font-semibold text-lg"><?php echo esc_html($region->name); ?></span>
							</div>
						<?php
							endforeach;
						endif;
						?>
					</div>
					<h1 class="text-4xl md:text-6xl lg:text-7xl font-black mb-8 leading-tight bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent drop-shadow-2xl">
						<?php the_title(); ?>
					</h1>
					<?php
					$overview = tznew_get_field_safe('overview');
					if ($overview) :
					?>
						<p class="text-xl md:text-2xl text-blue-50 leading-relaxed max-w-4xl mb-8 font-light">
							<?php echo wp_trim_words(wp_strip_all_tags($overview), 35, '...'); ?>
						</p>
					<?php endif; ?>
					
					<!-- Quick Action Buttons -->
					<div class="flex flex-col sm:flex-row gap-4 mt-8">
						<a href="<?php echo esc_url(site_url('/booking?trekking_id=' . get_the_ID())); ?>" class="inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl group">
							<i class="fas fa-mountain mr-3 group-hover:animate-bounce"></i>
							<span class="text-lg">Book This Trek</span>
						</a>
						<a href="#main-content" class="inline-flex items-center justify-center bg-white/20 backdrop-blur-md hover:bg-white/30 text-white font-semibold py-4 px-8 rounded-2xl border border-white/30 transition-all duration-300 group">
							<i class="fas fa-info-circle mr-3 group-hover:rotate-12 transition-transform"></i>
							<span class="text-lg">View Details</span>
						</a>
					</div>
				</div>
			</div>
			
			<!-- Scroll Indicator -->
			<div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
				<i class="fas fa-chevron-down text-2xl opacity-70"></i>
			</div>
		</section>



		<!-- Main Content -->
		<div id="main-content" class="container mx-auto px-4 py-12">
			<?php
			// Get trek data for sidebar
			$cost_info = tznew_get_field_safe('cost_info');
			$difficulty = tznew_get_field_safe('difficulty');
			?>
			<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
				<!-- Main Content Column -->
				<div class="lg:col-span-2 space-y-12">
					<?php get_template_part( 'template-parts/content', 'trekking' ); ?>
				</div>
				
				<!-- Sidebar -->
				<div class="lg:col-span-1">
					<div class="sticky top-8 space-y-8">
						<!-- Quick Booking Card -->
						<div class="bg-gradient-to-br from-white via-blue-50 to-indigo-100 rounded-3xl p-8 border border-blue-200/50 shadow-2xl backdrop-blur-sm">
							<div class="text-center mb-6">
								<div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg">
									<i class="fas fa-mountain"></i>
								</div>
								<h3 class="text-2xl font-black text-gray-800 mb-2">
									<?php esc_html_e('Book This Trek', 'tznew'); ?>
								</h3>
								<p class="text-gray-600"><?php esc_html_e('Secure your adventure today', 'tznew'); ?></p>
							</div>
							
							<?php if ($cost_info && isset($cost_info['price_usd']) && $cost_info['price_usd']) : ?>
								<div class="bg-white/70 rounded-2xl p-6 mb-6 border border-blue-100">
									<div class="text-center">
										<span class="text-sm font-medium text-gray-600 uppercase tracking-wide"><?php esc_html_e('Starting from', 'tznew'); ?></span>
										<div class="text-4xl font-black text-blue-600 my-2">$<?php echo esc_html(number_format($cost_info['price_usd'])); ?></div>
										<?php if (isset($cost_info['pricing_type']) && $cost_info['pricing_type']) : ?>
											<span class="text-sm text-gray-600 font-medium"><?php echo esc_html($cost_info['pricing_type']); ?></span>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>
							
							<div class="space-y-4">
								<a href="<?php echo esc_url(site_url('/booking?trekking_id=' . get_the_ID())); ?>" class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl text-center group">
									<i class="fas fa-calendar-check mr-3 group-hover:animate-pulse"></i>
									<span class="text-lg"><?php esc_html_e('Book Now', 'tznew'); ?></span>
								</a>
								<a href="<?php echo esc_url(site_url('/inquiry?trekking_id=' . get_the_ID())); ?>" class="block w-full bg-white/80 hover:bg-white text-blue-600 font-bold py-4 px-8 rounded-2xl border-2 border-blue-600 transition-all duration-300 hover:shadow-xl text-center group">
									<i class="fas fa-envelope mr-3 group-hover:animate-bounce"></i>
									<span class="text-lg"><?php esc_html_e('Send Inquiry', 'tznew'); ?></span>
								</a>
								<button type="button" class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 hover:shadow-xl text-center group download-itinerary-btn" data-post-id="<?php echo get_the_ID(); ?>">
									<i class="fas fa-download mr-3 group-hover:animate-bounce"></i>
									<span class="text-lg"><?php esc_html_e('Download Itinerary', 'tznew'); ?></span>
								</button>
								<a href="tel:+977-1-4444444" class="block w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 hover:shadow-xl text-center group">
									<i class="fas fa-phone mr-3 group-hover:animate-pulse"></i>
									<span class="text-lg"><?php esc_html_e('Call Now', 'tznew'); ?></span>
								</a>
							</div>
						</div>
						
						<!-- Trek Highlights -->
						<?php if (tznew_have_rows_safe('highlights')) : ?>
							<div class="bg-gradient-to-br from-white to-yellow-50 rounded-3xl p-8 shadow-2xl border border-yellow-200/50">
								<div class="text-center mb-6">
									<div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg">
										<i class="fas fa-star"></i>
									</div>
									<h3 class="text-2xl font-black text-gray-800">
										<?php esc_html_e('Trek Highlights', 'tznew'); ?>
									</h3>
								</div>
								<ul class="space-y-4">
									<?php while (tznew_have_rows_safe('highlights')) : tznew_the_row_safe(); ?>
										<?php $highlight = tznew_get_sub_field_safe('highlight'); ?>
										<?php if ($highlight) : ?>
											<li class="flex items-start bg-white/60 rounded-xl p-4 hover:bg-white/80 transition-all duration-300 group">
												<div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mr-4 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
													<i class="fas fa-check text-white text-sm"></i>
												</div>
												<span class="text-gray-700 font-medium leading-relaxed"><?php echo wp_kses_post(is_string($highlight) ? $highlight : ''); ?></span>
											</li>
										<?php endif; ?>
									<?php endwhile; ?>
								</ul>
							</div>
						<?php endif; ?>
						
						<!-- Related Treks -->
						<?php
						$related_treks = get_posts(array(
							'post_type' => 'trekking',
							'posts_per_page' => 3,
							'post__not_in' => array(get_the_ID()),
							'meta_query' => array(
								array(
									'key' => 'difficulty',
									'value' => $difficulty,
									'compare' => '='
								)
							)
						));
						
						if ($related_treks) :
						?>
							<div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
								<h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
									<i class="fas fa-hiking mr-2"></i>
									<?php esc_html_e('Similar Treks', 'tznew'); ?>
								</h3>
								<div class="space-y-4">
									<?php foreach ($related_treks as $trek) : ?>
										<a href="<?php echo esc_url(get_permalink($trek->ID)); ?>" class="block group">
											<div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-300">
												<?php if (has_post_thumbnail($trek->ID)) : ?>
													<img src="<?php echo esc_url(get_the_post_thumbnail_url($trek->ID, 'thumbnail')); ?>" alt="<?php echo esc_attr($trek->post_title); ?>" class="w-12 h-12 object-cover rounded-lg">
												<?php else : ?>
													<div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
														<i class="fas fa-mountain text-blue-600"></i>
													</div>
												<?php endif; ?>
												<div class="flex-1">
													<h4 class="font-medium text-gray-800 group-hover:text-blue-600 transition-colors duration-300 text-sm"><?php echo esc_html($trek->post_title); ?></h4>
													<?php
													$trek_duration = tznew_get_field_safe('duration', $trek->ID);
													if ($trek_duration) :
													?>
														<p class="text-xs text-gray-500"><?php echo esc_html($trek_duration); ?> <?php echo esc_html(_n('Day', 'Days', intval($trek_duration), 'tznew')); ?></p>
													<?php endif; ?>
												</div>
											</div>
										</a>
									<?php endforeach; ?>
								</div>
							</div>
						<?php
						wp_reset_postdata();
						endif;
						?>
					</div>
				</div>
			</div>
		</div>

		<?php
		// Display FAQ section
		tznew_display_faqs();
		?>

		<?php
		// Previous/next post navigation.
		the_post_navigation(
			array(
				'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous Trek:', 'tznew' ) . '</span> <span class="nav-title">%title</span>',
				'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next Trek:', 'tznew' ) . '</span> <span class="nav-title">%title</span>',
				'class'     => 'container mx-auto px-4 my-12 p-6 bg-gray-50 rounded-2xl',
			)
		);

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

<script>
jQuery(document).ready(function($) {
    // Handle PDF download button click
    $('.download-itinerary-btn').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var postId = button.data('post-id');
        var originalText = button.find('span').text();
        
        // Show loading state
        button.prop('disabled', true);
        button.find('i').removeClass('fa-download').addClass('fa-spinner fa-spin');
        button.find('span').text('<?php esc_html_e('Generating PDF...', 'tznew'); ?>');
        
        // Make AJAX request
        $.ajax({
            url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
            type: 'POST',
            data: {
                action: 'generate_pdf_itinerary',
                post_id: postId,
                post_type: 'trekking',
                nonce: '<?php echo wp_create_nonce('pdf_itinerary_nonce'); ?>'
            },
            success: function(response) {
                console.log('PDF AJAX Response:', response);
                if (response.success && response.data && response.data.html) {
                    // Open HTML content in new window for printing/saving as PDF
                    var newWindow = window.open('', '_blank');
                    newWindow.document.write(response.data.html);
                    newWindow.document.close();
                } else {
                    console.error('PDF Generation Failed:', response);
                    alert('Error generating PDF. Please try again. Check console for details.');
                }
                
                // Reset button
                button.prop('disabled', false);
                button.find('i').removeClass('fa-spinner fa-spin').addClass('fa-download');
                button.find('span').text(originalText);
            },
            error: function(xhr, status, error) {
                console.error('PDF AJAX Error:', xhr, status, error);
                alert('Error generating PDF. Please try again. Check console for details.');
                button.prop('disabled', false);
                button.find('i').removeClass('fa-spinner fa-spin').addClass('fa-download');
                button.find('span').text(originalText);
            }
        });
    });
});
</script>

<?php
get_footer();