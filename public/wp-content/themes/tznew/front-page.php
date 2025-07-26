<?php
/**
 * The template for displaying the front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php
	// Hero Section with Search
	$hero_title = get_theme_mod('tznew_hero_title', 'Explore Nepal');
	$hero_subtitle = get_theme_mod('tznew_hero_subtitle', 'Essential information about your upcoming adventure');
	$hero_image_url = get_theme_mod('tznew_hero_bg_image', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
	
	if (get_theme_mod('tznew_hero_show', true)) :
		?>
		<!-- Hero Section -->
		<section class="hero-section relative min-h-screen flex items-center justify-center" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('<?php echo esc_url($hero_image_url); ?>'); background-size: cover; background-position: center;">
			<div class="container mx-auto px-4 text-center text-white relative z-10">
				<div class="max-w-4xl mx-auto">
					<!-- Main Heading -->
					<h1 class="text-5xl lg:text-7xl font-bold mb-6 leading-tight">
						<?php echo esc_html($hero_title); ?>
					</h1>
					
					<!-- Subtitle -->
					<p class="text-xl lg:text-2xl mb-12 opacity-90 max-w-3xl mx-auto"><?php echo esc_html($hero_subtitle); ?></p>
					
					<!-- Search Form -->
					<div class="max-w-2xl mx-auto">
						<form class="bg-white rounded-2xl p-6 shadow-2xl" action="<?php echo esc_url(home_url('/')); ?>" method="get">
							<input type="hidden" name="s" value="*">
							<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
								<div class="relative">
									<label class="block text-sm font-medium text-gray-700 mb-2">Destination</label>
									<select name="destination" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900">
										<option value="">Select Destination</option>
										<option value="nepal">Nepal</option>
										<option value="india">India</option>
										<option value="bhutan">Bhutan</option>
										<option value="tibet">Tibet</option>
									</select>
								</div>
								<div class="relative">
									<label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
									<select name="duration" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900">
										<option value="">Any Duration</option>
										<option value="1-5">1-5 Days</option>
										<option value="6-10">6-10 Days</option>
										<option value="11-15">11-15 Days</option>
										<option value="16+">16+ Days</option>
									</select>
								</div>
								<div class="relative">
									<label class="block text-sm font-medium text-gray-700 mb-2">Activity</label>
									<select name="activity" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900">
										<option value="">All Activities</option>
										<option value="trekking">Trekking</option>
										<option value="tours">Tours</option>
										<option value="climbing">Climbing</option>
									</select>
								</div>
							</div>
							<button type="submit" class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-8 rounded-lg transition-all duration-300 transform hover:scale-105">
								<i class="fas fa-search mr-2"></i>
								Search Adventures
							</button>
						</form>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// Popular Trek Packages Section
	$featured_treks_title = get_theme_mod('tznew_featured_treks_title', 'Popular Trek Packages');
	$featured_treks_subtitle = get_theme_mod('tznew_featured_treks_subtitle', 'Choose from our carefully curated selection of the most sought-after trekking adventures in Nepal.');
	$featured_treks_count = get_theme_mod('tznew_featured_treks_count', 6);
	
	if (get_theme_mod('tznew_featured_treks_show', true)) :
		?>
		<section id="popular-treks" class="popular-treks py-20 bg-gray-50 relative overflow-hidden">
			<!-- Background decoration -->
			<div class="absolute top-0 left-0 w-full h-full opacity-5">
				<div class="absolute top-20 left-10 w-32 h-32 bg-green-500 rounded-full animate-float"></div>
				<div class="absolute bottom-20 right-10 w-24 h-24 bg-teal-500 rounded-full animate-float delay-1000"></div>
			</div>
			
			<div class="container mx-auto px-4 relative z-10">
				<div class="text-center mb-16 scroll-reveal-up">
				<h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-4">
					<?php echo esc_html($featured_treks_title); ?>
				</h2>
				<p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed"><?php echo esc_html($featured_treks_subtitle); ?></p>
			</div>
				
				<?php
				$featured_args = array(
					'post_type'      => 'trekking',
					'posts_per_page' => intval($featured_treks_count),
					'meta_key'       => 'featured',
					'meta_value'     => '1',
				);
				
				$featured_query = new WP_Query($featured_args);
				
				if ($featured_query->have_posts()) :
					?>
					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 stagger-animation">
						<?php
						$card_index = 0;
						while ($featured_query->have_posts()) :
							$featured_query->the_post();
							$card_index++;
							$rating = tznew_get_field_safe('rating') ?: '4.8';
							?>
							<article class="trek-card bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-3 group relative">
								<!-- Trek Image -->
								<div class="relative overflow-hidden h-72">
									<?php if (has_post_thumbnail()) : ?>
										<a href="<?php the_permalink(); ?>">
											<?php the_post_thumbnail('medium_large', array('class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-110')); ?>
										</a>
									<?php else : ?>
										<div class="w-full h-full bg-gradient-to-br from-green-400 to-teal-600 flex items-center justify-center">
											<i class="fas fa-mountain text-white text-4xl"></i>
										</div>
									<?php endif; ?>
									
									<!-- Hover Overlay with Quick Info -->
									<div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500">
										<div class="absolute bottom-6 left-6 right-6">
											<?php
											$difficulty = tznew_get_field_safe('difficulty');
											$duration = tznew_get_field_safe('duration');
											if ($difficulty || $duration) :
											?>
												<div class="flex gap-2 mb-3">
													<?php if ($difficulty) : ?>
														<span class="bg-orange-500/90 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-medium">
															<i class="fas fa-mountain mr-1"></i>
															<?php echo esc_html(ucfirst($difficulty)); ?>
														</span>
													<?php endif; ?>
													<?php if ($duration) : ?>
														<span class="bg-teal-500/90 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-medium">
															<i class="fas fa-clock mr-1"></i>
															<?php echo esc_html($duration); ?> Days
														</span>
													<?php endif; ?>
												</div>
											<?php endif; ?>
											
											<!-- Quick Action Buttons -->
											<div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-all duration-500 delay-200">
												<a href="<?php the_permalink(); ?>" class="flex-1 bg-white/90 backdrop-blur-sm text-gray-900 text-center py-2 px-4 rounded-lg font-semibold hover:bg-white transition-all duration-300 text-sm">
													<i class="fas fa-eye mr-1"></i>
													View Details
												</a>
												<button class="bg-green-600/90 backdrop-blur-sm text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-all duration-300 text-sm">
													<i class="fas fa-heart"></i>
												</button>
											</div>
										</div>
									</div>
									
									<!-- Region Badge -->
									<?php
									$regions = get_the_terms(get_the_ID(), 'region');
									if ($regions && !is_wp_error($regions)) :
									?>
										<div class="absolute top-4 left-4">
											<span class="bg-green-500/95 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-medium shadow-lg">
												<i class="fas fa-location-dot mr-1"></i>
												<?php echo esc_html($regions[0]->name); ?>
											</span>
										</div>
									<?php endif; ?>
									
									<!-- Price Badge -->
									<?php
									$cost_info = tznew_get_field_safe('cost_info');
									if ($cost_info && isset($cost_info['price_usd']) && $cost_info['price_usd']) :
									?>
										<div class="absolute top-4 right-4">
											<div class="bg-white/95 backdrop-blur-sm text-gray-900 px-3 py-2 rounded-full shadow-lg">
												<span class="text-xs text-gray-600 block leading-none">From</span>
												<span class="text-lg font-bold text-green-600 leading-none">$<?php echo esc_html(number_format($cost_info['price_usd'])); ?></span>
											</div>
										</div>
									<?php endif; ?>
									
									<!-- Rating Badge -->
									<?php
									$reviews_count = tznew_get_field_safe('reviews_count') ?: 0;
									?>
									<div class="absolute bottom-4 right-4">
										<div class="bg-white/95 backdrop-blur-sm px-3 py-1 rounded-full flex items-center shadow-lg">
											<svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
												<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
											</svg>
											<span class="text-sm font-bold text-gray-900"><?php echo esc_html($rating); ?></span>
											<?php if ($reviews_count > 0) : ?>
												<span class="text-xs text-gray-600 ml-1">(<?php echo esc_html(number_format($reviews_count)); ?>)</span>
											<?php endif; ?>
										</div>
									</div>
									
									<!-- Featured/Popular Badge -->
									<?php
									$is_featured = tznew_get_field_safe('featured');
									$is_popular = tznew_get_field_safe('popular');
									if ($is_featured || $is_popular) :
									?>
										<div class="absolute top-4 left-1/2 transform -translate-x-1/2">
											<?php if ($is_featured) : ?>
												<span class="bg-yellow-500/95 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
													<i class="fas fa-star mr-1"></i>
													FEATURED
												</span>
											<?php elseif ($is_popular) : ?>
												<span class="bg-red-500/95 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
													<i class="fas fa-fire mr-1"></i>
													POPULAR
												</span>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
								
								<!-- Trek Content -->
								<div class="p-6">
									<h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-green-600 transition-colors duration-300 line-clamp-2">
										<a href="<?php the_permalink(); ?>" class="stretched-link">
											<?php the_title(); ?>
										</a>
									</h3>
									
									<?php
									$overview = tznew_get_field_safe('overview');
									if ($overview) :
									?>
										<p class="text-gray-600 mb-4 leading-relaxed line-clamp-2">
											<?php echo wp_trim_words(wp_strip_all_tags($overview), 18, '...'); ?>
										</p>
									<?php endif; ?>
									
									<!-- Trek Meta Information -->
										<div class="grid grid-cols-2 gap-3 mb-6 text-sm">
											<?php
											$max_altitude = tznew_get_field_safe('max_altitude');
											if ($max_altitude) :
											?>
												<div class="flex items-center text-gray-600">
													<i class="fas fa-mountain mr-2 text-green-500"></i>
													<span class="font-medium"><?php echo esc_html(number_format($max_altitude)); ?>m</span>
												</div>
											<?php endif; ?>
											
											<?php
											$group_size = tznew_get_field_safe('group_size');
											if ($group_size) :
											?>
												<div class="flex items-center text-gray-600">
													<i class="fas fa-users mr-2 text-teal-500"></i>
													<span class="font-medium"><?php echo esc_html($group_size); ?></span>
												</div>
											<?php endif; ?>
											
											<?php
											$duration = tznew_get_field_safe('duration');
											if ($duration) :
											?>
												<div class="flex items-center text-gray-600">
													<i class="fas fa-clock mr-2 text-purple-500"></i>
													<span class="font-medium"><?php echo esc_html($duration); ?> days</span>
												</div>
											<?php endif; ?>
											
											<?php
											$season = tznew_get_field_safe('best_season') ?: tznew_get_field_safe('season');
											if ($season) :
											?>
												<div class="flex items-center text-gray-600">
													<i class="fas fa-calendar mr-2 text-orange-500"></i>
													<span class="font-medium"><?php echo esc_html($season); ?></span>
												</div>
											<?php endif; ?>
										</div>
										
										<!-- Additional Trek Details -->
										<div class="flex flex-wrap gap-2 mb-4">
											<?php
											$difficulty = tznew_get_field_safe('difficulty');
											if ($difficulty) :
												$difficulty_colors = array(
													'Easy' => 'bg-green-100 text-green-800',
													'Moderate' => 'bg-yellow-100 text-yellow-800',
													'Challenging' => 'bg-orange-100 text-orange-800',
													'Strenuous' => 'bg-red-100 text-red-800'
												);
												$color_class = $difficulty_colors[$difficulty] ?? 'bg-gray-100 text-gray-800';
											?>
												<span class="<?php echo esc_attr($color_class); ?> px-3 py-1 rounded-full text-xs font-medium">
													<?php echo esc_html($difficulty); ?>
												</span>
											<?php endif; ?>
											
											<?php
											$permits = tznew_get_field_safe('permits');
											if ($permits && is_array($permits) && isset($permits['permit_options']) && !empty($permits['permit_options'])) :
												foreach ($permits['permit_options'] as $permit) :
											?>
													<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
														<?php echo esc_html($permit); ?>
													</span>
												<?php
												endforeach;
											endif;
											?>
										</div>
									
									<!-- Action Buttons -->
									<div class="flex gap-3">
										<a href="<?php the_permalink(); ?>" class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-center py-3 px-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg relative z-10">
											<i class="fas fa-calendar-check mr-2"></i>
											Book Now
										</a>
										<a href="<?php the_permalink(); ?>" class="flex-1 border-2 border-green-600 text-green-600 hover:bg-green-600 hover:text-white text-center py-3 px-4 rounded-xl font-semibold transition-all duration-300 relative z-10">
											<i class="fas fa-info-circle mr-2"></i>
											Details
										</a>
									</div>
								</div>
							</article>
						<?php endwhile; ?>
					</div>
					
					<div class="text-center mt-12">
						<a href="<?php echo esc_url(get_post_type_archive_link('trekking')); ?>" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
							<i class="fas fa-mountain mr-2"></i>
							View All Trek Packages
							<i class="fas fa-arrow-right ml-2"></i>
						</a>
					</div>
					
					<!-- Enhanced CSS for Popular Trek Packages -->
					<style>
					.popular-treks .trek-card {
						position: relative;
						overflow: hidden;
					}
					
					.popular-treks .trek-card::before {
						content: '';
						position: absolute;
						top: 0;
						left: -100%;
						width: 100%;
						height: 100%;
						background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
						transition: left 0.5s;
						z-index: 1;
						pointer-events: none;
					}
					
					.popular-treks .trek-card:hover::before {
						left: 100%;
					}
					
					.popular-treks .line-clamp-2 {
						display: -webkit-box;
						line-clamp: 2;
						-webkit-line-clamp: 2;
						-webkit-box-orient: vertical;
						overflow: hidden;
					}
					
					.popular-treks .stretched-link::after {
						position: absolute;
						top: 0;
						right: 0;
						bottom: 0;
						left: 0;
						z-index: 1;
						content: "";
					}
					
					.popular-treks .stagger-animation > * {
						animation: fadeInUp 0.6s ease-out forwards;
						opacity: 0;
						transform: translateY(30px);
					}
					
					.popular-treks .stagger-animation > *:nth-child(1) { animation-delay: 0.1s; }
					.popular-treks .stagger-animation > *:nth-child(2) { animation-delay: 0.2s; }
					.popular-treks .stagger-animation > *:nth-child(3) { animation-delay: 0.3s; }
					.popular-treks .stagger-animation > *:nth-child(4) { animation-delay: 0.4s; }
					.popular-treks .stagger-animation > *:nth-child(5) { animation-delay: 0.5s; }
					.popular-treks .stagger-animation > *:nth-child(6) { animation-delay: 0.6s; }
					
					@keyframes fadeInUp {
						to {
							opacity: 1;
							transform: translateY(0);
						}
					}
					
					.popular-treks .animate-float {
						animation: float 6s ease-in-out infinite;
					}
					
					.popular-treks .delay-1000 {
						animation-delay: 1s;
					}
					
					@keyframes float {
						0%, 100% { transform: translateY(0px); }
						50% { transform: translateY(-20px); }
					}
					
					/* Responsive adjustments */
					@media (max-width: 768px) {
						.popular-treks .trek-card {
							transform: none !important;
						}
						
						.popular-treks .trek-card:hover {
							transform: translateY(-5px) !important;
						}
					}
					</style>
					<?php
					wp_reset_postdata();
				else :
					?>
					<p class="text-center"><?php esc_html_e('No featured treks found.', 'tznew'); ?></p>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<!-- Informational Trek Blocks Section -->
	<section class="py-20 bg-white">
		<div class="container mx-auto px-4">
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
				<!-- Trek Block 1 -->
				<div class="bg-gradient-to-br from-green-50 to-teal-100 rounded-2xl p-8 text-center hover:shadow-lg transition-all duration-300">
					<div class="w-16 h-16 bg-teal-600 rounded-full flex items-center justify-center mx-auto mb-6">
						<i class="fas fa-mountain text-white text-2xl"></i>
					</div>
					<h3 class="text-xl font-bold text-gray-900 mb-4">High Altitude Treks</h3>
					<p class="text-gray-600 mb-6">Experience the thrill of high-altitude trekking with our expert guides and safety protocols.</p>
					<a href="#" class="inline-flex items-center text-teal-600 font-semibold hover:text-teal-700">
						Learn More
						<i class="fas fa-arrow-right ml-2"></i>
					</a>
				</div>
				
				<!-- Trek Block 2 -->
				<div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 text-center hover:shadow-lg transition-all duration-300">
					<div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
						<i class="fas fa-leaf text-white text-2xl"></i>
					</div>
					<h3 class="text-xl font-bold text-gray-900 mb-4">Eco-Friendly Treks</h3>
					<p class="text-gray-600 mb-6">Sustainable trekking practices that preserve Nepal's natural beauty for future generations.</p>
					<a href="#" class="inline-flex items-center text-green-600 font-semibold hover:text-green-700">
						Learn More
						<i class="fas fa-arrow-right ml-2"></i>
					</a>
				</div>
				
				<!-- Trek Block 3 -->
				<div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-8 text-center hover:shadow-lg transition-all duration-300">
					<div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
						<i class="fas fa-users text-white text-2xl"></i>
					</div>
					<h3 class="text-xl font-bold text-gray-900 mb-4">Group Adventures</h3>
					<p class="text-gray-600 mb-6">Join like-minded adventurers on group treks with shared experiences and memories.</p>
					<a href="#" class="inline-flex items-center text-purple-600 font-semibold hover:text-purple-700">
						Learn More
						<i class="fas fa-arrow-right ml-2"></i>
					</a>
				</div>
				
				<!-- Trek Block 4 -->
				<div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-8 text-center hover:shadow-lg transition-all duration-300">
					<div class="w-16 h-16 bg-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
						<i class="fas fa-shield-alt text-white text-2xl"></i>
					</div>
					<h3 class="text-xl font-bold text-gray-900 mb-4">Safety First</h3>
					<p class="text-gray-600 mb-6">Comprehensive safety measures and emergency protocols for all our trekking adventures.</p>
					<a href="#" class="inline-flex items-center text-orange-600 font-semibold hover:text-orange-700">
						Learn More
						<i class="fas fa-arrow-right ml-2"></i>
					</a>
				</div>
			</div>
		</div>
		
		<!-- Interactive Map JavaScript -->
		<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Check if Leaflet is loaded
			if (typeof L === 'undefined') {
				console.error('Leaflet library not loaded');
				document.getElementById('trek-count').textContent = 'Map library not loaded';
				return;
			}
			
			// Check if map container exists
			const mapContainer = document.getElementById('trek-map');
			if (!mapContainer) {
				console.error('Map container not found');
				return;
			}
			
			try {
				// Initialize the map with attribution control disabled
				const map = L.map('trek-map', {
					attributionControl: false
				}).setView([28.3949, 84.1240], 7); // Center on Nepal
				
				// Add tile layer
				L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
					attribution: '',
					maxZoom: 18
				}).addTo(map);
			
			// Custom marker icon for regional destinations
			const regionIcon = L.divIcon({
				html: '<i class="fas fa-map-marker-alt text-blue-600 text-2xl"></i>',
				iconSize: [40, 40],
				className: 'custom-div-icon region-icon'
			});
			
			// Define regional boundaries (approximate polygons for each region)
			const regionBoundaries = {
				'everest': [
					[27.7, 86.5], [28.2, 86.5], [28.2, 87.3], [27.7, 87.3]
				],
				'annapurna': [
					[28.2, 83.4], [28.9, 83.4], [28.9, 84.2], [28.2, 84.2]
				],
				'langtang': [
					[27.9, 85.2], [28.5, 85.2], [28.5, 85.9], [27.9, 85.9]
				],
				'manaslu': [
					[28.2, 84.2], [28.8, 84.2], [28.8, 84.9], [28.2, 84.9]
				],
				'mustang': [
					[28.4, 83.4], [29.1, 83.4], [29.1, 84.2], [28.4, 84.2]
				],
				'dolpo': [
					[28.7, 82.4], [29.5, 82.4], [29.5, 83.4], [28.7, 83.4]
				],
				'rara': [
					[29.2, 81.7], [29.8, 81.7], [29.8, 82.4], [29.2, 82.4]
				],
				'makalu': [
					[27.5, 86.8], [28.2, 86.8], [28.2, 87.5], [27.5, 87.5]
				],
				'kanchenjunga': [
					[27.4, 87.8], [28.0, 87.8], [28.0, 88.5], [27.4, 88.5]
				]
			};
			
				// Fetch regional destinations
				fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=get_all_locations')
					.then(response => {
						if (!response.ok) {
							throw new Error(`HTTP error! status: ${response.status}`);
						}
						return response.json();
					})
					.then(data => {
					if (data.success && data.data.length > 0) {
						const bounds = L.latLngBounds();
						let totalTreks = 0;
						let totalTours = 0;
						
						data.data.forEach(region => {
							if (region.latitude && region.longitude) {
								totalTreks += region.trekking_count;
								totalTours += region.tours_count;
								
								// Get region boundary coordinates with flexible matching
								const regionSlug = region.region_slug.toLowerCase();
								let boundary = null;
								
								// First try exact match
								boundary = regionBoundaries[regionSlug];
								
								// If no exact match, try partial matching
								if (!boundary) {
									for (const [key, coords] of Object.entries(regionBoundaries)) {
										if (regionSlug.includes(key) || key.includes(regionSlug)) {
											boundary = coords;
											break;
										}
									}
								}
					
						
						// Build regional popup content with routes
						let popupContent = `
							<div class="p-4 min-w-80 max-w-96">
								<div class="mb-4">
									<h3 class="text-xl font-bold text-gray-900 mb-2">
										<i class="fas fa-mountain text-blue-600 mr-2"></i>
										${region.region_name}
									</h3>
									<div class="flex gap-4 text-sm text-gray-600 mb-3">
										<span><i class="fas fa-hiking text-green-600 mr-1"></i>${region.trekking_count} Treks</span>
										<span><i class="fas fa-car text-blue-600 mr-1"></i>${region.tours_count} Tours</span>
										<span><i class="fas fa-route text-purple-600 mr-1"></i>${region.routes_count} Total Routes</span>
									</div>
								</div>
								
								<div class="max-h-64 overflow-y-auto space-y-3">
						`;
								
								// Add routes to popup
								region.routes.forEach(route => {
									const typeColor = route.type === 'trekking' ? 'green' : 'blue';
									const typeLabel = route.type === 'trekking' ? 'Trek' : 'Tour';
									const typeIcon = route.type === 'trekking' ? 'fa-mountain' : 'fa-map-marked-alt';
									
									popupContent += `
										<div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors">
											<div class="flex items-start gap-3">
												${route.thumbnail ? `<img src="${route.thumbnail}" alt="${route.title}" class="w-12 h-12 object-cover rounded-lg flex-shrink-0">` : ''}
												<div class="flex-1 min-w-0">
													<div class="flex items-center gap-2 mb-1">
														<span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-${typeColor}-600 rounded-full">
															<i class="fas ${typeIcon} mr-1"></i>${typeLabel}
														</span>
													</div>
													<h4 class="font-semibold text-sm leading-tight mb-1 truncate">${route.title}</h4>
													${route.overview ? `<p class="text-xs text-gray-600 mb-2 line-clamp-2">${route.overview}</p>` : ''}
													
													<div class="flex flex-wrap gap-2 text-xs text-gray-500 mb-2">
														${route.duration ? `<span><i class="fas fa-clock mr-1"></i>${route.duration}</span>` : ''}
														${route.difficulty ? `<span><i class="fas fa-chart-line mr-1"></i>${route.difficulty}</span>` : ''}
														${route.tour_type ? `<span><i class="fas fa-tag mr-1"></i>${route.tour_type}</span>` : ''}
														${route.rating ? `<span><i class="fas fa-star text-yellow-500 mr-1"></i>${route.rating}/5</span>` : ''}
													</div>
													
													<div class="flex gap-1">
														<a href="${route.url}" class="flex-1 text-center bg-${typeColor}-600 text-white px-2 py-1 rounded text-xs hover:bg-${typeColor}-700 transition-colors">
															View Details
														</a>
														<a href="<?php echo site_url('/booking'); ?>?${route.type === 'trekking' ? 'trekking' : 'tour'}_id=${route.id}" class="flex-1 text-center bg-orange-600 text-white px-2 py-1 rounded text-xs hover:bg-orange-700 transition-colors">
															Book Now
														</a>
													</div>
												</div>
											</div>
										</div>
									`;
								});
								
								popupContent += `
										</div>
										
										<div class="mt-4 pt-3 border-t border-gray-200">
											<div class="flex gap-2">
												<a href="<?php echo site_url('/trekking'); ?>?region=${region.region_slug}" class="flex-1 text-center bg-gradient-to-r from-green-600 to-teal-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-teal-700 transition-all text-sm font-medium">
													<i class="fas fa-hiking mr-2"></i>View All Treks
												</a>
												<a href="<?php echo site_url('/tours'); ?>?region=${region.region_slug}" class="flex-1 text-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all text-sm font-medium">
													<i class="fas fa-car mr-2"></i>View All Tours
												</a>
											</div>
										</div>
									</div>
								`;
								
								// Create region polygon if boundary exists
								if (boundary) {
									const polygon = L.polygon(boundary, {
										color: '#3b82f6',
										fillColor: '#3b82f6',
										fillOpacity: 0.3,
										weight: 3,
										opacity: 0.8,
										className: 'region-polygon'
									}).addTo(map);
									
									// Add hover effects
									polygon.on('mouseover', function(e) {
										this.setStyle({
											fillOpacity: 0.6,
											weight: 4,
											opacity: 1.0
										});
										
										// Show region name tooltip
										const tooltip = L.tooltip({
											permanent: false,
											direction: 'top',
											className: 'region-tooltip'
										})
										.setContent(`<strong>${region.region_name}</strong><br/>${region.trekking_count} Treks, ${region.tours_count} Tours`)
										.setLatLng(e.latlng)
										.addTo(map);
										
										polygon._tooltip = tooltip;
									});
									
									polygon.on('mouseout', function(e) {
										this.setStyle({
											fillOpacity: 0.3,
											weight: 3,
											opacity: 0.8
										});
										
										// Remove tooltip
										if (this._tooltip) {
											map.removeLayer(this._tooltip);
										}
									});
									
									// Add click event for detailed popup
									polygon.on('click', function(e) {
										L.popup({
											maxWidth: 400,
											className: 'custom-popup regional-popup'
										})
										.setContent(popupContent)
										.setLatLng(e.latlng)
										.openOn(map);
									});
									
									bounds.extend(boundary);
								} else {
									// Fallback to marker if no boundary defined
									const marker = L.marker([region.latitude, region.longitude], {icon: regionIcon})
										.addTo(map)
										.bindPopup(popupContent, {
											maxWidth: 400,
											className: 'custom-popup regional-popup'
										});
									
									bounds.extend([region.latitude, region.longitude]);
								}
							}
						});
						
						// Fit map to show all markers
						if (bounds.isValid()) {
							map.fitBounds(bounds, {padding: [30, 30]});
						}
						
						// Update counter
						const totalRegions = data.data.length;
						document.getElementById('trek-count').textContent = `${totalRegions} regions (${totalTreks} treks, ${totalTours} tours)`;
					} else {
						document.getElementById('trek-count').textContent = 'No regions found';
					}
				})
					.catch(error => {
						console.error('Error loading regions:', error);
						document.getElementById('trek-count').textContent = 'Error loading regions: ' + error.message;
					});
				
				// Map toggle functionality
				const mapToggleBtn = document.getElementById('map-toggle-btn');
				const mapToggleIcon = document.getElementById('map-toggle-icon');
				const mapToggleText = document.getElementById('map-toggle-text');
				const mapContainer = document.getElementById('trek-map');
				let mapVisible = true;
				
				mapToggleBtn.addEventListener('click', function() {
					if (mapVisible) {
						// Hide map
						mapContainer.style.display = 'none';
						mapToggleIcon.className = 'fas fa-eye-slash';
						mapToggleText.textContent = 'Show Map';
						mapVisible = false;
					} else {
						// Show map
						mapContainer.style.display = 'block';
						mapToggleIcon.className = 'fas fa-eye';
						mapToggleText.textContent = 'Hide Map';
						mapVisible = true;
						
						// Invalidate map size to ensure proper rendering
						setTimeout(() => {
							map.invalidateSize();
						}, 100);
					}
				});
			} catch (error) {
				console.error('Error initializing map:', error);
				document.getElementById('trek-count').textContent = 'Error initializing map';
			}
		});
		</script>
		
		<!-- Custom CSS for map icons -->
		<style>
		.custom-div-icon {
			background: white;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 4px 12px rgba(0,0,0,0.15);
			transition: all 0.3s ease;
			cursor: pointer;
		}
		.custom-div-icon:hover {
			transform: scale(1.15);
			box-shadow: 0 6px 20px rgba(0,0,0,0.25);
		}
		.region-icon {
			border: 3px solid #2563eb;
			background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
		}
		.region-icon:hover {
			border-color: #1d4ed8;
			background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
		}
		.custom-popup .leaflet-popup-content {
			margin: 0;
			padding: 0;
		}
		.custom-popup .leaflet-popup-content-wrapper {
			border-radius: 16px;
			overflow: hidden;
			box-shadow: 0 10px 25px rgba(0,0,0,0.15);
		}
		.regional-popup .leaflet-popup-content-wrapper {
			max-height: 500px;
			overflow-y: auto;
		}
		.regional-popup .leaflet-popup-tip {
			background: white;
			border: none;
			box-shadow: 0 2px 6px rgba(0,0,0,0.1);
		}
		/* Custom scrollbar for popup content */
		.regional-popup .max-h-64::-webkit-scrollbar {
			width: 4px;
		}
		.regional-popup .max-h-64::-webkit-scrollbar-track {
			background: #f1f5f9;
			border-radius: 2px;
		}
		.regional-popup .max-h-64::-webkit-scrollbar-thumb {
			background: #cbd5e1;
			border-radius: 2px;
		}
		.regional-popup .max-h-64::-webkit-scrollbar-thumb:hover {
			background: #94a3b8;
		}
		/* Line clamp utility */
		.line-clamp-2 {
			display: -webkit-box;
            line-clamp: 2;
            -webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			overflow: hidden;
		}
		
		/* Region polygon styles */
		.region-polygon {
			cursor: pointer;
			transition: all 0.3s ease;
		}
		
		/* Region tooltip styles */
		.region-tooltip {
			background: rgba(0, 0, 0, 0.8) !important;
			border: none !important;
			border-radius: 8px !important;
			color: white !important;
			font-size: 14px !important;
			padding: 8px 12px !important;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
			pointer-events: none;
		}
		
		.region-tooltip::before {
			border-top-color: rgba(0, 0, 0, 0.8) !important;
		}
		</style>
	</section>

	<!-- Interactive Trek Blocks Section -->
	<section class="py-20 bg-gray-50">
		<div class="container mx-auto px-4">
			<div class="text-center mb-16">
				<h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
					Interesting <span class="text-green-600">Trek Blocks</span>
				</h2>
				<p class="text-lg text-gray-600 max-w-2xl mx-auto">
					Explore different aspects of trekking in Nepal with our comprehensive guide blocks.
				</p>
			</div>
			
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
				<!-- Interactive Map Section -->
				<div class="relative">
					<div class="bg-white rounded-2xl shadow-lg overflow-hidden">
						<div class="p-6 bg-gradient-to-r from-blue-600 via-purple-600 to-teal-600 text-white">
							<div class="flex items-center justify-between">
								<div>
									<h3 class="text-2xl font-bold mb-2">
										<i class="fas fa-globe-asia mr-2"></i>
										Regional Adventure Map
									</h3>
									<p class="opacity-90">Discover Nepal's trekking regions and explore routes by destination</p>
								</div>
								<div class="flex items-center">
									<button id="map-toggle-btn" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center space-x-2">
										<i id="map-toggle-icon" class="fas fa-eye"></i>
										<span id="map-toggle-text">Hide Map</span>
									</button>
								</div>
							</div>
						</div>
						<div id="trek-map" class="h-96 w-full"></div>
						<div class="p-4 bg-gray-50 border-t">
							<div class="flex items-center justify-between text-sm text-gray-600">
								<span class="flex items-center">
									<i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
									<span id="trek-count">Loading regions...</span>
								</span>
								<span class="flex items-center">
									<i class="fas fa-mouse-pointer text-purple-600 mr-2"></i>
									Click regions to view routes
								</span>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Trek Information Cards -->
				<div class="space-y-6">
					<?php
					// Get popular trekking regions from database
					$popular_regions = get_terms(array(
						'taxonomy' => 'region',
						'hide_empty' => true,
						'number' => 3,
						'orderby' => 'count',
						'order' => 'DESC'
					));
					
					if (empty($popular_regions)) {
						// Fallback to all regions if none found
						$popular_regions = get_terms(array(
							'taxonomy' => 'region',
							'hide_empty' => false,
							'number' => 3
						));
					}
					?>
					
					<!-- Best Trekking Routes -->
					<div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
						<div class="flex items-start space-x-4">
							<div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
								<i class="fas fa-route text-teal-600"></i>
							</div>
							<div class="flex-1">
								<h4 class="text-lg font-bold text-gray-900 mb-3">Best Trekking Regions</h4>
								<?php if (!empty($popular_regions)) : ?>
									<div class="space-y-2">
										<?php foreach ($popular_regions as $region) : 
											$region_description = get_field('region_description', 'region_' . $region->term_id);
											$trek_count = $region->count;
											$region_link = get_term_link($region);
										?>
											<div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
												<div>
													<a href="<?php echo esc_url($region_link); ?>" class="text-sm font-medium text-gray-900 hover:text-teal-600 transition-colors">
														<?php echo esc_html($region->name); ?>
													</a>
													<span class="ml-2 text-xs px-2 py-1 bg-green-100 text-green-600 rounded-full"><?php echo esc_html($trek_count); ?> treks</span>
												</div>
												<div class="text-right">
													<i class="fas fa-map-marker-alt text-teal-500 text-xs"></i>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								<?php else : ?>
									<p class="text-gray-600 text-sm">Discover the most scenic and rewarding trekking regions in the Himalayas.</p>
								<?php endif; ?>
							</div>
						</div>
					</div>
					
					<!-- Best Seasons -->
					<div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
						<div class="flex items-start space-x-4">
							<div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
								<i class="fas fa-calendar-alt text-green-600"></i>
							</div>
							<div class="flex-1">
								<h4 class="text-lg font-bold text-gray-900 mb-3">Best Seasons</h4>
								<div class="space-y-3">
									<div class="flex items-center justify-between">
										<div class="flex items-center">
											<i class="fas fa-sun text-yellow-500 mr-2"></i>
											<span class="text-sm font-medium text-gray-900">Spring (Mar-May)</span>
										</div>
										<span class="text-xs text-gray-500">Clear views, blooming rhododendrons</span>
									</div>
									<div class="flex items-center justify-between">
										<div class="flex items-center">
											<i class="fas fa-cloud-sun text-blue-500 mr-2"></i>
											<span class="text-sm font-medium text-gray-900">Autumn (Sep-Nov)</span>
										</div>
										<span class="text-xs text-gray-500">Perfect weather, crystal clear skies</span>
									</div>
									<div class="flex items-center justify-between">
										<div class="flex items-center">
											<i class="fas fa-snowflake text-cyan-500 mr-2"></i>
											<span class="text-sm font-medium text-gray-900">Winter (Dec-Feb)</span>
										</div>
										<span class="text-xs text-gray-500">Lower altitudes, fewer crowds</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<!-- Packing Guide -->
					<div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
						<div class="flex items-start space-x-4">
							<div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
								<i class="fas fa-backpack text-purple-600"></i>
							</div>
							<div class="flex-1">
								<h4 class="text-lg font-bold text-gray-900 mb-3">Essential Gear</h4>
								<div class="space-y-2">
									<div class="flex items-center text-sm text-gray-700">
										<i class="fas fa-check text-green-500 mr-2"></i>
										<span>Layered clothing system</span>
									</div>
									<div class="flex items-center text-sm text-gray-700">
										<i class="fas fa-check text-green-500 mr-2"></i>
										<span>Quality trekking boots</span>
									</div>
									<div class="flex items-center text-sm text-gray-700">
										<i class="fas fa-check text-green-500 mr-2"></i>
										<span>Sleeping bag & trekking poles</span>
									</div>
									<div class="flex items-center text-sm text-gray-700">
										<i class="fas fa-check text-green-500 mr-2"></i>
										<span>First aid & water purification</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Why Choose Nepal Trekking Section -->
	<section class="py-20 bg-white">
		<div class="container mx-auto px-4">
			<div class="text-center mb-16">
				<h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
					Why Choose <span class="text-green-600">Nepal Trekking</span>
				</h2>
				<p class="text-lg text-gray-600 max-w-3xl mx-auto">
					Discover what makes Nepal the ultimate trekking destination with unparalleled mountain views and rich cultural experiences.
				</p>
			</div>
			
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
				<!-- Feature 1 -->
				<div class="text-center group">
					<div class="w-20 h-20 bg-gradient-to-br from-green-500 to-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
						<i class="fas fa-mountain text-white text-2xl"></i>
					</div>
					<h3 class="text-xl font-bold text-gray-900 mb-4">World's Highest Peaks</h3>
					<p class="text-gray-600">Home to 8 of the world's 14 highest peaks including Mount Everest.</p>
				</div>
				
				<!-- Feature 2 -->
				<div class="text-center group">
					<div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
						<i class="fas fa-users text-white text-2xl"></i>
					</div>
					<h3 class="text-xl font-bold text-gray-900 mb-4">Rich Culture</h3>
					<p class="text-gray-600">Experience diverse ethnic communities and ancient traditions along the trails.</p>
				</div>
				
				<!-- Feature 3 -->
				<div class="text-center group">
					<div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
						<i class="fas fa-leaf text-white text-2xl"></i>
					</div>
					<h3 class="text-xl font-bold text-gray-900 mb-4">Diverse Landscapes</h3>
					<p class="text-gray-600">From subtropical forests to alpine meadows and glacial valleys.</p>
				</div>
				
				<!-- Feature 4 -->
				<div class="text-center group">
					<div class="w-20 h-20 bg-gradient-to-br from-green-600 to-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
						<i class="fas fa-heart text-white text-2xl"></i>
					</div>
					<h3 class="text-xl font-bold text-gray-900 mb-4">Warm Hospitality</h3>
					<p class="text-gray-600">Experience the legendary warmth and friendliness of Nepalese people.</p>
				</div>
			</div>
			
			<!-- Statistics Section -->
			<div class="mt-20 bg-gradient-to-r from-green-600 to-teal-600 rounded-3xl p-12 text-white">
				<div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
					<div>
						<div class="text-4xl font-bold mb-2">15+</div>
						<div class="text-lg opacity-90">Years Experience</div>
					</div>
					<div>
						<div class="text-4xl font-bold mb-2">10K+</div>
						<div class="text-lg opacity-90">Happy Trekkers</div>
					</div>
					<div>
						<div class="text-4xl font-bold mb-2">50+</div>
						<div class="text-lg opacity-90">Trek Routes</div>
					</div>
					<div>
						<div class="text-4xl font-bold mb-2">100%</div>
						<div class="text-lg opacity-90">Safety Record</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- What Our Trekkers Say Section -->
	<section class="py-20 bg-gray-50">
		<div class="container mx-auto px-4">
			<div class="text-center mb-16">
				<h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
					What Our <span class="text-green-600">Trekkers Say</span>
				</h2>
				<p class="text-lg text-gray-600 max-w-2xl mx-auto">
					Read authentic reviews from adventurers who have experienced the magic of Nepal with us.
				</p>
			</div>
			
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
				<!-- Testimonial 1 -->
				<div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
					<div class="flex items-center mb-6">
						<div class="flex text-yellow-400 mr-4">
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
						</div>
						<span class="text-gray-600 font-semibold">5.0</span>
					</div>
					<p class="text-gray-700 mb-6 italic">"An absolutely incredible experience! The guides were knowledgeable and the views were breathtaking. I can't wait to come back for another trek."</p>
					<div class="flex items-center">
						<div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
							<span class="text-green-600 font-bold text-lg">JS</span>
						</div>
						<div>
							<div class="font-semibold text-gray-900">John Smith</div>
							<div class="text-sm text-gray-600">Everest Base Camp Trek</div>
						</div>
					</div>
				</div>
				
				<!-- Testimonial 2 -->
				<div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
					<div class="flex items-center mb-6">
						<div class="flex text-yellow-400 mr-4">
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
						</div>
						<span class="text-gray-600 font-semibold">5.0</span>
					</div>
					<p class="text-gray-700 mb-6 italic">"Professional service from start to finish. The team took care of everything and made sure we had a safe and memorable journey."</p>
					<div class="flex items-center">
						<div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
							<span class="text-teal-600 font-bold text-lg">MJ</span>
						</div>
						<div>
							<div class="font-semibold text-gray-900">Maria Johnson</div>
							<div class="text-sm text-gray-600">Annapurna Circuit Trek</div>
						</div>
					</div>
				</div>
				
				<!-- Testimonial 3 -->
				<div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
					<div class="flex items-center mb-6">
						<div class="flex text-yellow-400 mr-4">
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
						</div>
						<span class="text-gray-600 font-semibold">5.0</span>
					</div>
					<p class="text-gray-700 mb-6 italic">"The cultural immersion was as amazing as the mountain views. Our guide shared so much knowledge about local traditions and customs."</p>
					<div class="flex items-center">
						<div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
							<span class="text-purple-600 font-bold text-lg">DL</span>
						</div>
						<div>
							<div class="font-semibold text-gray-900">David Lee</div>
							<div class="text-sm text-gray-600">Langtang Valley Trek</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Popular Trips Section -->
	<section class="py-20 bg-white">
		<div class="container mx-auto px-4">
			<!-- Section Header -->
			<div class="text-center mb-16">
				<?php
				$popular_trips_title = tznew_get_field_safe('popular_trips_title');
				if ($popular_trips_title) : ?>
					<h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6"><?php echo esc_html($popular_trips_title); ?></h2>
				<?php else : ?>
					<h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">Popular Trips</h2>
				<?php endif; ?>
				
				<?php
				$popular_trips_subtitle = tznew_get_field_safe('popular_trips_subtitle');
				if ($popular_trips_subtitle) : ?>
					<p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed"><?php echo esc_html($popular_trips_subtitle); ?></p>
				<?php else : ?>
					<p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">Discover our most sought-after adventures combining the best of trekking and touring experiences.</p>
				<?php endif; ?>
				<div class="w-24 h-1 bg-gradient-to-r from-green-600 to-teal-600 mx-auto mt-6 rounded-full"></div>
			</div>

			<!-- Popular Trips Grid -->
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
				<?php
				// Query for popular trekking posts only
				$popular_trekking_args = array(
					'post_type'      => 'trekking',
					'posts_per_page' => 3,
					'meta_key'       => 'popular',
					'meta_value'     => '1',
					'orderby'        => 'date',
					'order'          => 'DESC'
				);
				
				$popular_trekking = new WP_Query($popular_trekking_args);
				
				// Display popular trekking
				if ($popular_trekking->have_posts()) :
					while ($popular_trekking->have_posts()) : $popular_trekking->the_post();
						?>
						<article class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3">
							<!-- Trip Image -->
							<div class="relative h-64 overflow-hidden">
								<?php if (has_post_thumbnail()) : ?>
									<?php the_post_thumbnail('medium_large', array('class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-110')); ?>
								<?php else : ?>
									<div class="w-full h-full bg-gradient-to-br from-green-400 to-teal-600 flex items-center justify-center">
										<i class="fas fa-mountain text-white text-4xl"></i>
									</div>
								<?php endif; ?>
								
								<!-- Trip Type Badge -->
								<div class="absolute top-4 left-4">
									<span class="bg-teal-600/90 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-semibold">
										<i class="fas fa-mountain mr-1"></i>
										Trekking
									</span>
								</div>
								
								<!-- Popular Badge -->
								<div class="absolute top-4 right-4">
									<span class="bg-orange-500/90 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-semibold">
										<i class="fas fa-fire mr-1"></i>
										Popular
									</span>
								</div>
							</div>
							
							<!-- Trip Content -->
							<div class="p-6">
								<h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-teal-600 transition-colors duration-300">
									<a href="<?php echo esc_url(get_permalink()); ?>" class="block">
										<?php the_title(); ?>
									</a>
								</h3>
								
								<?php
								$overview = tznew_get_field_safe('overview');
								if ($overview) :
								?>
									<p class="text-gray-600 mb-4 leading-relaxed">
										<?php echo wp_trim_words(wp_strip_all_tags($overview), 15, '...'); ?>
									</p>
								<?php endif; ?>
								
								<!-- Trip Meta -->
								<div class="flex items-center justify-between text-sm text-gray-500 mb-4">
									<div class="flex items-center gap-4">
										<?php
										$duration = tznew_get_field_safe('duration');
										if ($duration) :
										?>
											<span class="flex items-center">
												<i class="fas fa-clock mr-1 text-teal-500"></i>
												<?php echo esc_html($duration); ?> <?php echo esc_html(_n('Day', 'Days', intval($duration), 'tznew')); ?>
											</span>
										<?php endif; ?>
										
										<?php
										$difficulty = tznew_get_field_safe('difficulty');
										if ($difficulty) :
										?>
											<span class="flex items-center">
												<i class="fas fa-mountain mr-1 text-orange-500"></i>
												<?php echo esc_html(ucfirst($difficulty)); ?>
											</span>
										<?php endif; ?>
									</div>
									
									<?php
									$cost_info = tznew_get_field_safe('cost_info');
									if ($cost_info && isset($cost_info['price_usd']) && $cost_info['price_usd']) :
									?>
										<div class="text-right">
											<span class="text-xs text-gray-500"><?php esc_html_e('From', 'tznew'); ?></span>
											<div class="text-lg font-bold text-teal-600">$<?php echo esc_html(number_format($cost_info['price_usd'])); ?></div>
										</div>
									<?php endif; ?>
								</div>
								
								<!-- Action Buttons -->
								<div class="flex gap-3">
									<a href="<?php echo esc_url(get_permalink()); ?>" class="flex-1 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white text-center py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 font-semibold text-sm">
										<?php esc_html_e('View Details', 'tznew'); ?>
									</a>
									<a href="<?php echo esc_url(site_url('/booking')); ?>?trekking_id=<?php echo get_the_ID(); ?>" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 font-semibold text-sm">
										<?php esc_html_e('Book Now', 'tznew'); ?>
									</a>
								</div>
							</div>
						</article>
						<?php
					endwhile;
					wp_reset_postdata();
				endif;
				

				?>
			</div>
			
			<!-- View All Button -->
			<div class="text-center mt-12">
				<a href="<?php echo esc_url(get_post_type_archive_link('trekking')); ?>?trek_type=popular" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white rounded-lg transition-all duration-300 transform hover:scale-105 font-semibold">
					<i class="fas fa-mountain mr-2"></i>
					<?php esc_html_e('View All Trekking Routes', 'tznew'); ?>
				</a>
			</div>
		</div>
	</section>

	<!-- Plan Your Adventure Section -->
	<section class="py-20 bg-gradient-to-br from-green-600 to-blue-600 text-white">
		<div class="container mx-auto px-4">
			<div class="max-w-4xl mx-auto text-center">
				<h2 class="text-4xl lg:text-5xl font-bold mb-6">
					Plan Your <span class="text-yellow-300">Adventure</span>
				</h2>
				<p class="text-xl mb-12 opacity-90">
					Ready to embark on the journey of a lifetime? Let us help you plan the perfect trekking adventure in Nepal.
				</p>
				
				<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
					<!-- Contact Form -->
					<div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-8">
						<h3 class="text-2xl font-bold mb-6">Get a Custom Quote</h3>
						<form class="space-y-4">
							<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
								<input type="text" placeholder="Your Name" class="w-full px-4 py-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-white placeholder-opacity-70 focus:outline-none focus:ring-2 focus:ring-yellow-300">
								<input type="email" placeholder="Your Email" class="w-full px-4 py-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-white placeholder-opacity-70 focus:outline-none focus:ring-2 focus:ring-yellow-300">
							</div>
							<select class="w-full px-4 py-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white focus:outline-none focus:ring-2 focus:ring-yellow-300">
								<option value="">Select Trek Type</option>
								<option value="everest">Everest Region</option>
								<option value="annapurna">Annapurna Region</option>
								<option value="langtang">Langtang Region</option>
								<option value="manaslu">Manaslu Region</option>
							</select>
							<textarea placeholder="Tell us about your dream trek..." rows="4" class="w-full px-4 py-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-white placeholder-opacity-70 focus:outline-none focus:ring-2 focus:ring-yellow-300 resize-none"></textarea>
							<button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-3 px-6 rounded-lg transition-colors duration-300">
								Get My Quote
							</button>
						</form>
					</div>
					
					<!-- Quick Actions -->
					<div class="space-y-6">
						<div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6">
							<div class="flex items-center mb-4">
								<div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mr-4">
									<i class="fas fa-phone text-gray-900"></i>
								</div>
								<div>
									<h4 class="text-lg font-bold">Call Us Now</h4>
									<p class="text-sm opacity-80">Speak with our trek experts</p>
								</div>
							</div>
							<a href="tel:+977-1-4444444" class="text-yellow-300 font-semibold hover:text-yellow-200 transition-colors">+977-1-4444444</a>
						</div>
						
						<div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6">
							<div class="flex items-center mb-4">
								<div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mr-4">
									<i class="fas fa-envelope text-gray-900"></i>
								</div>
								<div>
									<h4 class="text-lg font-bold">Email Us</h4>
									<p class="text-sm opacity-80">Get detailed information</p>
								</div>
							</div>
							<a href="mailto:info@dragonholidays.com" class="text-yellow-300 font-semibold hover:text-yellow-200 transition-colors">info@dragonholidays.com</a>
						</div>
						
						<div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6">
							<div class="flex items-center mb-4">
								<div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mr-4">
									<i class="fab fa-whatsapp text-gray-900"></i>
								</div>
								<div>
									<h4 class="text-lg font-bold">WhatsApp</h4>
									<p class="text-sm opacity-80">Quick chat support</p>
								</div>
							</div>
							<a href="https://wa.me/9779841234567" class="text-yellow-300 font-semibold hover:text-yellow-200 transition-colors">+977-984-123-4567</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	// Modern Popular Tours Section
	if (function_exists('get_field')) :
		$popular_tours_title = tznew_get_field_safe('popular_tours_title', 'option');
		$popular_tours_subtitle = tznew_get_field_safe('popular_tours_subtitle', 'option');
		$popular_tours_count = tznew_get_field_safe('popular_tours_count', 'option') ?: 3;
		?>
		<section class="popular-tours py-24 bg-gradient-to-br from-white via-blue-50 to-indigo-50 relative overflow-hidden">
			<!-- Background Elements -->
			<div class="absolute top-0 right-0 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
			<div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
			
			<div class="container mx-auto px-4 relative z-10">
				<div class="text-center mb-16">
					<div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium mb-6">
						<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
							<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
						</svg>
						Most Popular
					</div>
					<h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
						<?php echo $popular_tours_title ? esc_html($popular_tours_title) : 'Popular <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 bg-clip-text text-transparent">Tours</span>'; ?>
					</h2>
					<?php if ($popular_tours_subtitle) : ?>
						<p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed"><?php echo esc_html($popular_tours_subtitle); ?></p>
					<?php else : ?>
						<p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">Explore our handpicked selection of the most sought-after tours, designed to create lasting memories and extraordinary experiences.</p>
					<?php endif; ?>
					<div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-purple-600 mx-auto mt-6 rounded-full"></div>
				</div>
				
				<?php
				$popular_args = array(
					'post_type'      => 'tours',
					'posts_per_page' => intval($popular_tours_count),
					'meta_key'       => 'featured',
					'meta_value'     => '1',
				);
				
				$popular_query = new WP_Query($popular_args);
				
				if ($popular_query->have_posts()) :
					?>
					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
						<?php
						while ($popular_query->have_posts()) :
							$popular_query->the_post();
							?>
							<div class="tour-card bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 group">
								<?php if (has_post_thumbnail()) : ?>
									<div class="tour-image relative overflow-hidden">
										<a href="<?php the_permalink(); ?>">
											<?php the_post_thumbnail('medium_large', array('class' => 'w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110')); ?>
										</a>
										<div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
										
										<?php
										$price = tznew_get_field_safe('price');
										if ($price) : ?>
											<div class="absolute top-4 right-4 bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
												<svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
												</svg>
												<?php echo esc_html($price); ?>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								
								<div class="tour-content p-6">
									<h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300">
										<a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition duration-300">
											<?php the_title(); ?>
										</a>
									</h3>
									
									<div class="tour-meta grid grid-cols-2 gap-3 text-sm text-gray-600 mb-4">
										<?php
										$duration = tznew_get_field_safe('duration');
										$tour_type = tznew_get_field_safe('tour_type');
										$regions = get_the_terms(get_the_ID(), 'region');
										
										if ($duration) : ?>
											<div class="flex items-center bg-gray-50 rounded-lg p-2">
												<svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
												</svg>
												<span class="font-medium"><?php echo esc_html($duration); ?> days</span>
											</div>
										<?php endif; ?>
										
										<?php if ($regions && !is_wp_error($regions)) : ?>
											<div class="flex items-center bg-gray-50 rounded-lg p-2">
												<svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
												</svg>
												<span class="font-medium"><?php echo esc_html($regions[0]->name); ?></span>
											</div>
										<?php endif; ?>
									</div>
									
									<div class="mb-6 text-gray-700 leading-relaxed">
										<?php
										$overview = tznew_get_field_safe('overview');
										if ($overview) {
											echo wp_trim_words(wp_strip_all_tags($overview), 20, '...');
										} else {
											the_excerpt();
										}
										?>
									</div>
									
									<div class="flex gap-3">
										<a href="<?php the_permalink(); ?>" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 text-center">
											<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
											</svg>
											<?php esc_html_e('View Details', 'tznew'); ?>
										</a>
										<button class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-3 rounded-xl transition-colors duration-300" title="Add to Wishlist">
											<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
											</svg>
										</button>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
					
					<div class="text-center mt-16">
						<a href="<?php echo esc_url(get_post_type_archive_link('tours')); ?>" class="inline-flex items-center bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-900 hover:to-black text-white font-bold py-4 px-8 rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
							<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
							</svg>
							<?php esc_html_e('View All Tours', 'tznew'); ?>
							<svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
							</svg>
						</a>
					</div>
					<?php
					wp_reset_postdata();
				else :
					?>
					<p class="text-center"><?php esc_html_e('No popular tours found.', 'tznew'); ?></p>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// Trekking Regions Section
	$regions_title = get_theme_mod('tznew_regions_title', 'Popular Trekking Regions');
	$regions_subtitle = get_theme_mod('tznew_regions_subtitle', 'Explore Nepal\'s most spectacular trekking regions, each offering unique landscapes, cultural experiences, and unforgettable adventures.');
	
	if (get_theme_mod('tznew_regions_show', true)) :
	?>
	<section class="trekking-regions py-24 bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 relative overflow-hidden">
		<!-- Background Elements -->
		<div class="absolute top-0 right-0 w-96 h-96 bg-green-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
		<div class="absolute bottom-0 left-0 w-96 h-96 bg-teal-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
		
		<div class="container mx-auto px-4 relative z-10">
			<div class="text-center mb-16">
				<div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium mb-6">
					<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
						<path d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"></path>
					</svg>
					Trekking Destinations
				</div>
				<h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
				<?php echo esc_html($regions_title); ?>
			</h2>
			<p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed"><?php echo esc_html($regions_subtitle); ?></p>
				<div class="w-24 h-1 bg-gradient-to-r from-green-600 to-teal-600 mx-auto mt-6 rounded-full"></div>
			</div>
			
			<div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
				<?php
				// Get actual trekking regions from database
				$regions = get_terms(array(
					'taxonomy' => 'region',
					'hide_empty' => true,
					'number' => 3,
					'orderby' => 'count',
					'order' => 'DESC'
				));
				
				// Fallback to all regions if none found
				if (empty($regions) || is_wp_error($regions)) {
					$regions = get_terms(array(
						'taxonomy' => 'region',
						'hide_empty' => false,
						'number' => 3
					));
				}
				
				// Define default styling for regions
				$region_styles = array(
					'from-blue-600 to-indigo-700',
					'from-green-600 to-emerald-700', 
					'from-teal-600 to-cyan-700',
					'from-purple-600 to-pink-700',
					'from-orange-600 to-red-700'
				);
				
				$region_icons = array(
					'fas fa-mountain',
					'fas fa-leaf',
					'fas fa-water',
					'fas fa-tree',
					'fas fa-snowflake'
				);
				
				if (!empty($regions) && !is_wp_error($regions)) :
					foreach ($regions as $index => $region_term) :
						// Count trekking packages in this region
						$count_args = array(
							'post_type' => 'trekking',
							'post_status' => 'publish',
							'posts_per_page' => -1,
							'tax_query' => array(
								array(
									'taxonomy' => 'region',
									'field' => 'term_id',
									'terms' => $region_term->term_id,
								),
							),
						);
						$count_query = new WP_Query($count_args);
						$trekking_count = $count_query->found_posts;
						wp_reset_postdata();
						
						// Get archive link
						$archive_link = get_term_link($region_term);
						if (is_wp_error($archive_link)) {
							$archive_link = add_query_arg('region', $region_term->slug, get_post_type_archive_link('trekking'));
						}
						
						// Get region description from ACF or use default
						$region_description = tznew_get_field_safe('description', 'region_' . $region_term->term_id);
						if (empty($region_description)) {
							$region_description = $region_term->description;
						}
						if (empty($region_description)) {
							$region_description = 'Discover amazing trekking adventures in ' . $region_term->name . ' with breathtaking landscapes and unforgettable experiences.';
						}
						
						// Assign styles cyclically
						$color_class = $region_styles[$index % count($region_styles)];
						$icon_class = $region_icons[$index % count($region_icons)];
				?>
					<div class="trekking-region-card group">
						<div class="bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3">
							<!-- Header with Icon -->
							<div class="bg-gradient-to-r <?php echo esc_attr($color_class); ?> p-8 text-white text-center">
								<div class="w-20 h-20 mx-auto mb-4 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
									<i class="<?php echo esc_attr($icon_class); ?> text-3xl"></i>
								</div>
								<h3 class="text-2xl font-bold mb-2"><?php echo esc_html($region_term->name); ?></h3>
								<div class="inline-flex items-center bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium">
									<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
									</svg>
									<?php echo number_format_i18n($trekking_count); ?> <?php echo _n('Trek', 'Treks', $trekking_count, 'tznew'); ?>
								</div>
							</div>
							
							<!-- Content -->
							<div class="p-8">
								<p class="text-gray-600 text-lg leading-relaxed mb-6"><?php echo esc_html($region_description); ?></p>
								
								<!-- Stats -->
								<div class="grid grid-cols-2 gap-4 mb-6">
									<div class="text-center p-4 bg-gray-50 rounded-xl">
										<div class="text-2xl font-bold text-gray-900 mb-1"><?php echo number_format_i18n($trekking_count); ?></div>
										<div class="text-sm text-gray-600"><?php echo _n('Trek Package', 'Trek Packages', $trekking_count, 'tznew'); ?></div>
									</div>
									<div class="text-center p-4 bg-gray-50 rounded-xl">
										<div class="text-2xl font-bold text-gray-900 mb-1">4.8</div>
										<div class="text-sm text-gray-600">Average Rating</div>
									</div>
								</div>
								
								<!-- CTA Button -->
								<a href="<?php echo esc_url($archive_link); ?>" class="block w-full bg-gradient-to-r <?php echo esc_attr($color_class); ?> hover:shadow-lg text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 text-center group">
									<span class="flex items-center justify-center">
										<span>Explore <?php echo esc_html($region_term->name); ?></span>
										<svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
										</svg>
									</span>
								</a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				else :
					?>
					<div class="col-span-full text-center py-12">
						<p class="text-gray-600 text-lg"><?php esc_html_e('No trekking regions found.', 'tznew'); ?></p>
					</div>
				<?php endif; ?>
			</div>
			
			<!-- View All Regions Link -->
			<div class="text-center mt-16">
				<a href="<?php echo esc_url(get_post_type_archive_link('trekking')); ?>" class="inline-flex items-center bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-900 hover:to-black text-white font-bold py-4 px-8 rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
					<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
					</svg>
					<?php esc_html_e('View All Trekking Packages', 'tznew'); ?>
					<svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
					</svg>
				</a>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php
	// Modern Destinations Section
	$destinations_title = get_theme_mod('tznew_destinations_title', 'Explore Amazing Destinations');
	$destinations_subtitle = get_theme_mod('tznew_destinations_subtitle', 'Discover breathtaking landscapes and immerse yourself in diverse cultures across our carefully curated destinations.');
	$destinations_count = get_theme_mod('tznew_destinations_count', 6);
	
	if (get_theme_mod('tznew_destinations_show', true)) :
		?>
		<section class="destinations py-24 bg-gradient-to-br from-gray-50 via-slate-50 to-gray-100 relative overflow-hidden">
			<!-- Background Pattern -->
			<div class="absolute inset-0 opacity-5">
				<div class="absolute top-20 right-20 w-80 h-80 bg-green-500 rounded-full mix-blend-multiply filter blur-xl animate-blob"></div>
				<div class="absolute bottom-20 left-20 w-80 h-80 bg-teal-500 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-4000"></div>
			</div>
			
			<div class="container mx-auto px-4 relative z-10">
				<div class="text-center mb-16">
					<div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium mb-6">
						<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
						</svg>
						Top Destinations
					</div>
					<h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
					<?php echo esc_html($destinations_title); ?>
				</h2>
				<p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed"><?php echo esc_html($destinations_subtitle); ?></p>
					<div class="w-24 h-1 bg-gradient-to-r from-green-600 to-teal-600 mx-auto mt-6 rounded-full"></div>
				</div>
				
				<?php
				// Get regions with image and count
				$regions = get_terms(array(
					'taxonomy' => 'region',
					'hide_empty' => true,
				));
				
				if (!empty($regions) && !is_wp_error($regions)) :
					?>
					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
						<?php foreach ($regions as $region) : 
							$region_image = tznew_get_field_safe('region_image', 'region_' . $region->term_id);
							$region_image_url = isset($region_image['url']) ? $region_image['url'] : '';
							
							if (empty($region_image_url)) {
								$region_image_url = get_template_directory_uri() . '/assets/images/default-region.jpg';
							}
							
							// Count posts in this region
							$count_args = array(
								'post_type' => array('trekking', 'tours'),
								'tax_query' => array(
									array(
										'taxonomy' => 'region',
										'field' => 'term_id',
										'terms' => $region->term_id,
									),
								),
								'posts_per_page' => -1,
							);
							
							$count_query = new WP_Query($count_args);
							$count = $count_query->found_posts;
							?>
							<div class="destination-card relative rounded-3xl overflow-hidden shadow-xl group transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl">
								<a href="<?php echo esc_url(get_term_link($region)); ?>" class="block">
									<div class="relative h-80">
										<img src="<?php echo esc_url($region_image_url); ?>" alt="<?php echo esc_attr($region->name); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
										
										<!-- Gradient Overlays -->
											<div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-black/10"></div>
											<div class="absolute inset-0 bg-gradient-to-br from-green-600/30 to-teal-600/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
										
										<!-- Adventure Count Badge -->
										<div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm text-gray-900 px-3 py-2 rounded-full text-sm font-semibold shadow-lg">
											<svg class="w-4 h-4 inline mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
											</svg>
											<?php echo number_format_i18n($count); ?>
										</div>
										
										<!-- Content -->
											<div class="absolute bottom-0 left-0 right-0 p-6 text-white">
												<h3 class="text-2xl lg:text-3xl font-bold mb-2 group-hover:text-green-200 transition-colors duration-300 text-shadow-lg drop-shadow-lg">
													<?php echo esc_html($region->name); ?>
												</h3>
												<p class="text-white text-base mb-4 font-medium drop-shadow-md">
													<?php printf(_n('%s Adventure Available', '%s Adventures Available', $count, 'tznew'), number_format_i18n($count)); ?>
												</p>
											
											<!-- CTA Button -->
															<div class="inline-flex items-center bg-green-600/80 backdrop-blur-md hover:bg-green-500/90 text-white px-5 py-3 rounded-full text-sm font-semibold transition-all duration-300 group-hover:scale-105 border border-green-500/30 shadow-lg">
														<span class="drop-shadow-sm">Explore Now</span>
														<svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
														</svg>
													</div>
										</div>
									</div>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
					<?php
				else :
					?>
					<p class="text-center"><?php esc_html_e('No destinations found.', 'tznew'); ?></p>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// Modern Testimonials Section
	if (function_exists('get_field')) :
		$testimonials_title = tznew_get_field_safe('testimonials_title', 'option');
		$testimonials_subtitle = tznew_get_field_safe('testimonials_subtitle', 'option');
		$testimonials = tznew_get_field_safe('testimonials', 'option');
		?>
		<section class="testimonials py-24 bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800 text-white relative overflow-hidden">
			<!-- Background Elements -->
			<div class="absolute inset-0 opacity-10">
				<div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-xl animate-blob"></div>
				<div class="absolute bottom-0 right-0 w-96 h-96 bg-pink-300 rounded-full mix-blend-overlay filter blur-xl animate-blob animation-delay-2000"></div>
			</div>
			
			<div class="container mx-auto px-4 relative z-10">
				<div class="text-center mb-16">
					<div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-medium mb-6">
						<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
							<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
						</svg>
						Client Reviews
					</div>
					<h2 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
						<?php echo $testimonials_title ? esc_html($testimonials_title) : 'What Our <span class="text-yellow-300">Adventurers</span> Say'; ?>
					</h2>
					<?php if ($testimonials_subtitle) : ?>
						<p class="text-xl text-white/90 max-w-3xl mx-auto leading-relaxed"><?php echo esc_html($testimonials_subtitle); ?></p>
					<?php else : ?>
						<p class="text-xl text-white/90 max-w-3xl mx-auto leading-relaxed">Hear from our amazing travelers who have experienced unforgettable adventures with us.</p>
					<?php endif; ?>
					<div class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-orange-400 mx-auto mt-6 rounded-full"></div>
				</div>
				
				<?php if ($testimonials) : ?>
					<div class="testimonial-slider max-w-6xl mx-auto">
						<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
							<?php foreach ($testimonials as $testimonial) : ?>
								<div class="testimonial-card bg-white/10 backdrop-blur-md p-8 rounded-3xl border border-white/20 hover:bg-white/15 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl group">
									<!-- Quote Icon -->
									<div class="mb-6">
										<svg class="w-8 h-8 text-yellow-300 opacity-80" fill="currentColor" viewBox="0 0 24 24">
											<path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"></path>
										</svg>
									</div>
									
									<!-- Rating Stars -->
									<div class="mb-6 flex items-center">
										<?php
										$rating = isset($testimonial['rating']) ? intval($testimonial['rating']) : 5;
										for ($i = 1; $i <= 5; $i++) {
											if ($i <= $rating) {
												echo '<svg class="w-5 h-5 text-yellow-300 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>';
											} else {
												echo '<svg class="w-5 h-5 text-white/30 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>';
											}
										}
										?>
									</div>
									
									<!-- Testimonial Content -->
									<div class="mb-6 text-white/95 text-lg leading-relaxed italic group-hover:text-white transition-colors duration-300">
										"<?php echo esc_html($testimonial['content']); ?>"
									</div>
									
									<!-- Author Info -->
									<div class="flex items-center pt-4 border-t border-white/20">
										<?php if (isset($testimonial['photo']) && !empty($testimonial['photo']['url'])) : ?>
											<img src="<?php echo esc_url($testimonial['photo']['url']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>" class="w-14 h-14 rounded-full mr-4 object-cover border-2 border-white/30 group-hover:border-white/50 transition-colors duration-300" />
										<?php else : ?>
											<div class="w-14 h-14 rounded-full mr-4 bg-white/20 flex items-center justify-center border-2 border-white/30">
												<svg class="w-6 h-6 text-white/60" fill="currentColor" viewBox="0 0 20 20">
													<path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
												</svg>
											</div>
										<?php endif; ?>
										<div>
											<div class="font-bold text-white text-lg group-hover:text-yellow-300 transition-colors duration-300"><?php echo esc_html($testimonial['name']); ?></div>
											<?php if (isset($testimonial['trip'])) : ?>
												<div class="text-sm text-white/70 group-hover:text-white/90 transition-colors duration-300"><?php echo esc_html($testimonial['trip']); ?></div>
											<?php endif; ?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php else : ?>
					<p class="text-center"><?php esc_html_e('No testimonials found.', 'tznew'); ?></p>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// Blog Section
	if (function_exists('get_field')) :
		$blog_title = tznew_get_field_safe('blog_title', 'option');
		$blog_subtitle = tznew_get_field_safe('blog_subtitle', 'option');
		$blog_count = tznew_get_field_safe('blog_count', 'option') ?: 3;
		?>
		<section class="blog py-16 bg-white">
			<div class="container mx-auto px-4">
				<div class="text-center mb-12">
					<h2 class="text-3xl md:text-4xl font-bold mb-4">
						<?php echo $blog_title ? esc_html($blog_title) : esc_html__('Latest from Our Blog', 'tznew'); ?>
					</h2>
					<?php if ($blog_subtitle) : ?>
						<p class="text-xl text-gray-600"><?php echo esc_html($blog_subtitle); ?></p>
					<?php endif; ?>
				</div>
				
				<?php
				$blog_args = array(
					'post_type'      => 'blog',
					'posts_per_page' => intval($blog_count),
				);
				
				$blog_query = new WP_Query($blog_args);
				
				if ($blog_query->have_posts()) :
					?>
					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
						<?php
						while ($blog_query->have_posts()) :
							$blog_query->the_post();
							?>
							<div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:-translate-y-2">
								<?php if (has_post_thumbnail()) : ?>
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail('medium_large', array('class' => 'w-full h-48 object-cover')); ?>
									</a>
								<?php endif; ?>
								<div class="p-6">
									<div class="flex items-center text-sm text-gray-500 mb-3">
										<span class="mr-3">
											<i class="dashicons dashicons-calendar-alt"></i> 
											<?php echo get_the_date(); ?>
										</span>
										<?php
										$tags = get_the_terms(get_the_ID(), 'acf_tag');
										if ($tags && !is_wp_error($tags)) :
											?>
											<span>
												<i class="dashicons dashicons-tag"></i>
												<?php echo esc_html($tags[0]->name); ?>
											</span>
											<?php
										endif;
										?>
									</div>
									
									<h3 class="text-xl font-bold mb-3">
										<a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition duration-300">
											<?php the_title(); ?>
										</a>
									</h3>
									
									<div class="mb-4 text-gray-700">
										<?php
										$content = tznew_get_field_safe('content');
										if ($content) {
											echo wp_trim_words(wp_strip_all_tags($content), 20, '...');
										} else {
											the_excerpt();
										}
										?>
									</div>
									
									<a href="<?php the_permalink(); ?>" class="inline-block text-blue-600 hover:text-blue-800 font-medium transition duration-300">
										<?php esc_html_e('Read More', 'tznew'); ?> &rarr;
									</a>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
					
					<div class="text-center mt-10">
						<a href="<?php echo esc_url(get_post_type_archive_link('blog')); ?>" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-300">
							<?php esc_html_e('View All Posts', 'tznew'); ?>
						</a>
					</div>
					<?php
					wp_reset_postdata();
				else :
					?>
					<p class="text-center"><?php esc_html_e('No blog posts found.', 'tznew'); ?></p>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// CTA Section
	if (function_exists('get_field')) :
		$cta_title = tznew_get_field_safe('cta_title', 'option');
		$cta_content = tznew_get_field_safe('cta_content', 'option');
		$cta_button_text = tznew_get_field_safe('cta_button_text', 'option');
		$cta_button_link = tznew_get_field_safe('cta_button_link', 'option');
		$cta_background = tznew_get_field_safe('cta_background', 'option');
		
		$cta_bg_url = isset($cta_background['url']) ? $cta_background['url'] : '';
		if (empty($cta_bg_url)) {
			$cta_bg_url = get_template_directory_uri() . '/assets/images/default-cta-bg.jpg';
		}
		?>
		<section class="cta relative py-20" style="background-image: url('<?php echo esc_url($cta_bg_url); ?>'); background-size: cover; background-position: center;">
			<div class="absolute inset-0 bg-blue-900 bg-opacity-80"></div>
			<div class="container mx-auto px-4 relative z-10 text-center text-white">
				<div class="max-w-3xl mx-auto">
					<?php if ($cta_title) : ?>
						<h2 class="text-3xl md:text-4xl font-bold mb-6"><?php echo esc_html($cta_title); ?></h2>
					<?php endif; ?>
					
					<?php if ($cta_content) : ?>
						<div class="text-xl mb-8"><?php echo wp_kses_post($cta_content); ?></div>
					<?php endif; ?>
					
					<?php if ($cta_button_text && $cta_button_link) : ?>
						<a href="<?php echo esc_url($cta_button_link); ?>" class="inline-block bg-white text-blue-900 hover:bg-gray-100 font-bold py-3 px-8 rounded-lg text-lg transition duration-300">
							<?php echo esc_html($cta_button_text); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

</main><!-- #main -->

<?php
get_footer();