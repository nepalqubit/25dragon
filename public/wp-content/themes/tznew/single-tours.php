<?php
/**
 * The template for displaying single tours posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package TZnew
 */

get_header();
?>

<?php
// Check if Elementor Theme Builder single template exists for tours posts
if ( function_exists( 'tznew_elementor_location_exists' ) && tznew_elementor_location_exists( 'single' ) ) {
    // Use Elementor Theme Builder single template
    tznew_elementor_do_location( 'single' );
} else {
    // Fallback to default tours template
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
				<div class="absolute inset-0 bg-gradient-to-r from-green-900/30 to-transparent"></div>
			<?php else : ?>
				<div class="absolute inset-0 bg-gradient-to-br from-green-600 via-teal-700 to-cyan-800"></div>
			<?php endif; ?>
			
			<!-- Floating Elements -->
			<div class="absolute top-20 right-10 w-32 h-32 bg-white/10 rounded-full blur-xl animate-pulse"></div>
			<div class="absolute bottom-40 left-20 w-20 h-20 bg-green-400/20 rounded-full blur-lg animate-bounce"></div>
			
			<div class="relative z-10 container mx-auto px-4 h-full flex items-center">
				<div class="text-white max-w-5xl">
					<?php if (function_exists('tznew_get_field_safe')) : ?>
						<?php $region = tznew_get_field_safe('region', get_the_ID()); ?>
						<?php if (!empty($region)) : ?>
							<div class="inline-flex items-center bg-gradient-to-r from-green-600/90 to-teal-600/90 backdrop-blur-md px-6 py-3 rounded-full mb-6 border border-white/20 shadow-lg">
								<i class="fas fa-location-dot mr-3 text-green-200"></i>
								<span class="font-semibold text-lg"><?php echo esc_html($region); ?></span>
							</div>
						<?php endif; ?>
					<?php endif; ?>
					
					<h1 class="text-4xl md:text-6xl lg:text-7xl font-black mb-8 leading-tight bg-gradient-to-r from-white to-green-100 bg-clip-text text-transparent drop-shadow-2xl">
						<?php the_title(); ?>
					</h1>
					
					<?php if (function_exists('tznew_get_field_safe')) : ?>
						<?php $overview = tznew_get_field_safe('overview', get_the_ID()); ?>
						<?php if (!empty($overview)) : ?>
							<p class="text-xl md:text-2xl text-green-50 leading-relaxed max-w-4xl mb-8 font-light">
								<?php echo esc_html(wp_trim_words($overview, 35)); ?>
							</p>
						<?php endif; ?>
					<?php endif; ?>
					
					<!-- Quick Action Buttons -->
					<div class="flex flex-col sm:flex-row gap-4 mt-8">
						<a href="<?php echo esc_url(site_url('/booking?tour_id=' . get_the_ID() . '&tour_title=' . urlencode(get_the_title()))); ?>" class="inline-flex items-center justify-center bg-gradient-to-r from-green-500 to-teal-600 hover:from-green-600 hover:to-teal-700 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl group">
							<i class="fas fa-map-marked-alt mr-3 group-hover:animate-bounce"></i>
							<span class="text-lg">Book This Tour</span>
						</a>
						<a href="#tour-details" class="inline-flex items-center justify-center bg-white/20 backdrop-blur-md hover:bg-white/30 text-white font-semibold py-4 px-8 rounded-2xl border border-white/30 transition-all duration-300 group">
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
		
		<!-- Tour Meta Information -->
		<section id="tour-details" class="bg-gradient-to-br from-slate-50 via-green-50 to-teal-50 py-20 relative overflow-hidden">
			<!-- Background Pattern -->
			<div class="absolute inset-0 opacity-5">
				<div class="absolute top-10 left-10 w-64 h-64 bg-green-600 rounded-full blur-3xl"></div>
				<div class="absolute bottom-10 right-10 w-48 h-48 bg-teal-600 rounded-full blur-3xl"></div>
			</div>
			
			<div class="container mx-auto px-4 relative z-10">
				<div class="text-center mb-16">
					<h2 class="text-4xl md:text-5xl font-black text-gray-800 mb-4">
						<span class="bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent">Tour</span> Overview
					</h2>
					<p class="text-xl text-gray-600 max-w-2xl mx-auto">Essential information about your upcoming journey</p>
				</div>
				
				<?php if (function_exists('tznew_get_field_safe')) : ?>
					<?php 
					$duration = tznew_get_field_safe('duration', get_the_ID());
					$tour_type = tznew_get_field_safe('tour_type', get_the_ID());
					$region = tznew_get_field_safe('region', get_the_ID());
					$price = tznew_get_field_safe('price', get_the_ID());
					?>
					
					<?php if ($duration || $tour_type || $region || $price) : ?>
						<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto mb-16">
							<?php if ($duration) : ?>
								<div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-xl border border-white/50 hover:shadow-2xl transition-all duration-500 group hover:-translate-y-2">
									<div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-green-500 to-teal-600 rounded-2xl flex items-center justify-center text-white text-3xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
										<i class="fas fa-clock"></i>
									</div>
									<h3 class="text-xl font-bold text-gray-800 mb-3 text-center"><?php esc_html_e('Duration', 'tznew'); ?></h3>
									<p class="text-3xl font-black text-green-600 text-center mb-2"><?php echo esc_html($duration); ?></p>
									<p class="text-lg text-gray-600 text-center font-medium"><?php echo esc_html(_n('Day', 'Days', intval($duration), 'tznew')); ?></p>
								</div>
							<?php endif; ?>
							
							<?php if ($tour_type) : ?>
								<div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-xl border border-white/50 hover:shadow-2xl transition-all duration-500 group hover:-translate-y-2">
									<div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-green-500 to-teal-600 rounded-2xl flex items-center justify-center text-white text-3xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
										<i class="fas fa-tag"></i>
									</div>
									<h3 class="text-xl font-bold text-gray-800 mb-3 text-center"><?php esc_html_e('Tour Type', 'tznew'); ?></h3>
									<p class="text-3xl font-black text-teal-600 text-center"><?php echo esc_html(ucfirst($tour_type)); ?></p>
								</div>
							<?php endif; ?>
							
							<?php if ($region) : ?>
								<div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-xl border border-white/50 hover:shadow-2xl transition-all duration-500 group hover:-translate-y-2">
									<div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center text-white text-3xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
										<i class="fas fa-location-dot"></i>
									</div>
									<h3 class="text-xl font-bold text-gray-800 mb-3 text-center"><?php esc_html_e('Region', 'tznew'); ?></h3>
									<p class="text-3xl font-black text-purple-600 text-center"><?php echo esc_html($region); ?></p>
								</div>
							<?php endif; ?>
							
							<?php if ($price) : ?>
								<div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-xl border border-white/50 hover:shadow-2xl transition-all duration-500 group hover:-translate-y-2">
									<div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center text-white text-3xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
										<i class="fas fa-dollar-sign"></i>
									</div>
									<h3 class="text-xl font-bold text-gray-800 mb-3 text-center"><?php esc_html_e('Starting Price', 'tznew'); ?></h3>
									<p class="text-3xl font-black text-yellow-600 text-center">$<?php echo esc_html(number_format($price)); ?></p>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</section>
		
		<!-- Content Section -->
		<div class="container mx-auto px-4 py-12">
			<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
				<!-- Main Content -->
				<div class="lg:col-span-2 space-y-12">
					<?php get_template_part('template-parts/content', 'tours'); ?>
				</div>
				
				<!-- Sidebar -->
				<div class="lg:col-span-1">
					<div class="sticky top-8 space-y-8">
						<!-- Quick Booking Card -->
						<div class="bg-gradient-to-br from-white via-green-50 to-teal-100 rounded-3xl p-8 border border-green-200/50 shadow-2xl backdrop-blur-sm">
							<div class="text-center mb-6">
								<div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-green-600 to-teal-700 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg">
									<i class="fas fa-map-marked-alt"></i>
								</div>
								<h3 class="text-2xl font-black text-gray-800 mb-2">
									<?php esc_html_e('Book This Tour', 'tznew'); ?>
								</h3>
								<p class="text-gray-600"><?php esc_html_e('Start your journey today', 'tznew'); ?></p>
							</div>
						
						<?php if (function_exists('tznew_get_field_safe')) : ?>
							<?php 
							$price = tznew_get_field_safe('price', get_the_ID());
							?>
							
							<?php if ($price) : ?>
								<div class="text-center mb-8">
									<p class="text-lg text-gray-600 mb-2"><?php esc_html_e('Starting from', 'tznew'); ?></p>
									<p class="text-5xl font-black text-green-600 mb-1">$<?php echo esc_html(number_format($price)); ?></p>
									<p class="text-gray-500"><?php esc_html_e('per person', 'tznew'); ?></p>
								</div>
							<?php endif; ?>
						<?php endif; ?>
						
						<div class="space-y-4">
							<a href="<?php echo esc_url(site_url('/booking?tour_id=' . get_the_ID() . '&tour_title=' . urlencode(get_the_title()))); ?>" class="group relative block w-full bg-gradient-to-r from-green-600 to-teal-600 text-white text-center py-4 px-8 rounded-2xl font-bold text-lg hover:from-green-700 hover:to-teal-700 transition-all duration-300 shadow-xl hover:shadow-2xl hover:-translate-y-1 overflow-hidden">
								<div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
								<span class="relative flex items-center justify-center gap-3">
									<i class="fas fa-calendar-check text-xl"></i>
									<?php esc_html_e('Book Now', 'tznew'); ?>
								</span>
							</a>
							
							<a href="<?php echo esc_url(site_url('/inquiry?tour_id=' . get_the_ID() . '&tour_title=' . urlencode(get_the_title()))); ?>" class="group relative block w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-center py-4 px-8 rounded-2xl font-bold text-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-xl hover:shadow-2xl hover:-translate-y-1 overflow-hidden">
								<div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
								<span class="relative flex items-center justify-center gap-3">
									<i class="fas fa-envelope text-xl"></i>
									<?php esc_html_e('Send Inquiry', 'tznew'); ?>
								</span>
							</a>
							
							<button type="button" class="group relative block w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white text-center py-4 px-8 rounded-2xl font-bold text-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-300 shadow-xl hover:shadow-2xl hover:-translate-y-1 overflow-hidden download-itinerary-btn" data-post-id="<?php echo get_the_ID(); ?>">
								<div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
								<span class="relative flex items-center justify-center gap-3">
									<i class="fas fa-download text-xl"></i>
									<?php esc_html_e('Download Itinerary', 'tznew'); ?>
								</span>
							</button>
							
							<a href="tel:+977-1-4444444" class="group relative block w-full bg-gradient-to-r from-orange-500 to-red-500 text-white text-center py-4 px-8 rounded-2xl font-bold text-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-xl hover:shadow-2xl hover:-translate-y-1 overflow-hidden">
								<div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
								<span class="relative flex items-center justify-center gap-3">
									<i class="fas fa-phone text-xl"></i>
									<?php esc_html_e('Call Now', 'tznew'); ?>
								</span>
							</a>
						</div>
						
						<div class="mt-8 p-6 bg-white/50 rounded-2xl border border-green-200">
							<h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
								<i class="fas fa-shield-alt text-green-600"></i>
								<?php esc_html_e('Why Choose Us?', 'tznew'); ?>
							</h4>
							<ul class="space-y-3 text-sm text-gray-600">
								<li class="flex items-center gap-3">
									<div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
										<i class="fas fa-check text-green-600 text-xs"></i>
									</div>
									<?php esc_html_e('Expert Local Guides', 'tznew'); ?>
								</li>
								<li class="flex items-center gap-3">
									<div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
										<i class="fas fa-check text-green-600 text-xs"></i>
									</div>
									<?php esc_html_e('24/7 Support', 'tznew'); ?>
								</li>
								<li class="flex items-center gap-3">
									<div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
										<i class="fas fa-check text-green-600 text-xs"></i>
									</div>
									<?php esc_html_e('Best Price Guarantee', 'tznew'); ?>
								</li>
								<li class="flex items-center gap-3">
									<div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
										<i class="fas fa-check text-green-600 text-xs"></i>
									</div>
									<?php esc_html_e('Flexible Cancellation', 'tznew'); ?>
								</li>
							</ul>
						</div>
					</div>
				</div>
					</div>
				</div>
			</div>
		</div>
		
		<?php
		// Display FAQ section
		tznew_display_faqs();
		?>
		
		<?php
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
		
	endwhile; // End of the loop.
	?>
</main>
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
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'generate_pdf_itinerary',
                post_id: postId,
                post_type: 'tours',
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
get_sidebar();
get_footer();