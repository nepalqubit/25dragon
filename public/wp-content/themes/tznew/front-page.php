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
?>
<?php else : ?>
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
		
		<!-- Region Manager Map JavaScript -->
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
					attributionControl: false,
					zoomControl: true,
					scrollWheelZoom: true,
					doubleClickZoom: true,
					boxZoom: false,
					keyboard: true,
					dragging: true,
					touchZoom: true
				}).setView([28.3949, 84.1240], 6); // Center on Nepal
				
				// Add tile layer
				L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
					attribution: '',
					maxZoom: 18
				}).addTo(map);
			
			// Define missing variables and functions
			const regionColors = {
				'everest': '#e74c3c',
				'annapurna': '#3498db', 
				'langtang': '#2ecc71',
				'manaslu': '#f39c12',
				'dolpo': '#9b59b6',
				'mustang': '#e67e22',
				'kanchenjunga': '#1abc9c',
				'makalu': '#34495e'
			};
			
			// Define map icons
			const trekIcon = L.divIcon({
				html: '<i class="fas fa-mountain" style="color: #e67e22; font-size: 16px;"></i>',
				iconSize: [20, 20],
				className: 'custom-div-icon'
			});
			
			
			const tourIcon = L.divIcon({
				html: '<i class="fas fa-car" style="color: #3498db; font-size: 16px;"></i>',
				iconSize: [20, 20],
				className: 'custom-div-icon'
			});
			
			const regionIcon = L.divIcon({
				html: '<i class="fas fa-map-marker-alt" style="color: #e74c3c; font-size: 20px;"></i>',
				iconSize: [25, 25],
				className: 'custom-div-icon'
			});
			
			// Define helper functions
			function getRegionColor(regionSlug) {
				return regionColors[regionSlug] || '#3b82f6';
			}
			
			const trekRoutes = {}; // Initialize empty trek routes
			
			// Store for region polygons
			const regionPolygons = [];
			let regionCount = 0;
			let assignedTripsCount = 0;
			
			// Fetch region manager data from admin
			console.log('Loading region manager data...');
			document.getElementById('trek-count').textContent = 'Loading regions...';
			
				// Fetch region manager data
			fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=tznew_get_regions_for_map')
				.then(response => {
					if (!response.ok) {
						throw new Error(`HTTP error! status: ${response.status}`);
					}
					return response.json();
				})
				.then(data => {
				console.log('Region manager data loaded:', data);
				if (data.success && data.data && data.data.length > 0) {
						const bounds = L.latLngBounds();
						regionCount = data.data.length;
					assignedTripsCount = 0;
					
					data.data.forEach(region => {

					
					// Count assigned trips
					if (region.assigned_trekking) {
						assignedTripsCount += region.assigned_trekking.length;
					}
					if (region.assigned_tours) {
						assignedTripsCount += region.assigned_tours.length;
					}
					
					// Convert polygon data to Leaflet format
					let boundary = null;
					if (region.polygon_coordinates && region.polygon_coordinates.length > 0) {
						
						try {
							const polygonCoords = region.polygon_coordinates.map(coord => {
								// Handle different coordinate formats
								if (coord && typeof coord === 'object') {
									// Try different property names
									const lat = coord.lat || coord.latitude || coord[0];
									const lng = coord.lng || coord.longitude || coord[1];
									if (lat !== undefined && lng !== undefined) {
										return [parseFloat(lat), parseFloat(lng)];
									}
								}
								return null;
							}).filter(coord => coord !== null);
							
							if (polygonCoords.length > 0) {
								boundary = polygonCoords;
							}
						} catch (error) {
							console.error('Error processing polygon coordinates for region', region.name, ':', error);
						}
					}
					
					// Create popup content for region
						const trekCount = region.assigned_trekking ? region.assigned_trekking.length : 0;
						const tourCount = region.assigned_tours ? region.assigned_tours.length : 0;
						
						let popupContent = `
							<div class="p-4 min-w-80 max-w-lg">
								<div class="mb-4 border-b pb-3">
									<h3 class="text-xl font-bold text-gray-900 mb-2">
										<i class="fas fa-map-marked-alt text-blue-600 mr-2"></i>
										${region.name}
									</h3>
									<div class="flex gap-3 text-sm">
										<span class="flex items-center bg-green-100 text-green-800 px-2 py-1 rounded-full">
											<i class="fas fa-hiking mr-1"></i>${trekCount} Treks
										</span>
										<span class="flex items-center bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
											<i class="fas fa-car mr-1"></i>${tourCount} Tours
										</span>
									</div>
								</div>
								
								<div class="max-h-60 overflow-y-auto space-y-3">
							`;
							
							// Add assigned trekking routes
			if (region.assigned_trekking && region.assigned_trekking.length > 0) {
				popupContent += `<div class="mb-3"><h4 class="font-semibold text-green-700 mb-2">Assigned Treks:</h4>`;
				region.assigned_trekking.forEach(trek => {
					popupContent += `
						<div class="border border-gray-200 rounded-lg p-2 mb-2">
							<div class="flex items-center gap-2">
								<span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-green-600 rounded-full">
									<i class="fas fa-mountain mr-1"></i>Trek
								</span>
								<h5 class="font-medium text-sm">${trek.title || 'Untitled Trek'}</h5>
							</div>
							<a href="${trek.permalink || '#'}" class="text-xs text-blue-600 hover:text-blue-800">View Details</a>
						</div>
					`;
				});
				popupContent += `</div>`;
			}
			
			// Add assigned tours
			if (region.assigned_tours && region.assigned_tours.length > 0) {
				popupContent += `<div class="mb-3"><h4 class="font-semibold text-blue-700 mb-2">Assigned Tours:</h4>`;
				region.assigned_tours.forEach(tour => {
					popupContent += `
						<div class="border border-gray-200 rounded-lg p-2 mb-2">
							<div class="flex items-center gap-2">
								<span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-blue-600 rounded-full">
									<i class="fas fa-car mr-1"></i>Tour
								</span>
								<h5 class="font-medium text-sm">${tour.title || 'Untitled Tour'}</h5>
							</div>
							<a href="${tour.permalink || '#'}" class="text-xs text-blue-600 hover:text-blue-800">View Details</a>
						</div>
					`;
				});
				popupContent += `</div>`;
			}
			
			
			popupContent += `
					</div>
					
					<div class="mt-4 pt-3 border-t border-gray-200">
						<div class="flex gap-2">
							<a href="<?php echo site_url('/trekking'); ?>?region=${region.slug}" class="flex-1 text-center bg-gradient-to-r from-green-600 to-teal-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-teal-700 transition-all text-sm font-medium">
						<i class="fas fa-hiking mr-2"></i>View All Treks
					</a>
					<a href="<?php echo site_url('/tours'); ?>?region=${region.slug}" class="flex-1 text-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all text-sm font-medium">
						<i class="fas fa-car mr-2"></i>View All Tours
					</a>
						</div>
					</div>
				</div>
			`;
				
				// Create region polygon if boundary exists
				if (boundary) {
					const regionColor = regionColors[region.slug] || '#3b82f6';
					
					const polygon = L.polygon(boundary, {
						color: regionColor,
						fillColor: regionColor,
						fillOpacity: 0.2,
						weight: 2,
						opacity: 0.8,
						className: 'region-polygon'
					}).addTo(map);
					
					// Store trek data with polygon for easy access
					polygon.regionData = region;
					
					// Add trek and tour markers within the region
					const trekMarkers = [];
					const tourMarkers = [];
								
								// Create trek markers
								const trekCount = Math.min(region.trekking_count, 2);
								for (let i = 0; i < trekCount; i++) {
									const offsetLat = (Math.random() - 0.5) * 0.04;
									const offsetLng = (Math.random() - 0.5) * 0.04;
									
									const bounds = L.latLngBounds(boundary);
									const center = bounds.getCenter();
									const markerLat = center.lat + offsetLat;
									const markerLng = center.lng + offsetLng;
									
									const trekMarker = L.marker([markerLat, markerLng], {icon: trekIcon})
										.addTo(map)
										.bindTooltip(`Trek in ${region.name}`, {
											permanent: false,
											direction: 'top',
											className: 'custom-tooltip'
										});
									
									trekMarkers.push(trekMarker);
								}
								
								// Create tour markers
								const tourCount = Math.min(region.tours_count, 2);
								for (let i = 0; i < tourCount; i++) {
									const offsetLat = (Math.random() - 0.5) * 0.04;
									const offsetLng = (Math.random() - 0.5) * 0.04;
									
									const bounds = L.latLngBounds(boundary);
									const center = bounds.getCenter();
									const markerLat = center.lat + offsetLat + 0.02;
									const markerLng = center.lng + offsetLng + 0.02;
									
									const tourMarker = L.marker([markerLat, markerLng], {icon: tourIcon})
										.addTo(map)
										.bindTooltip(`Tour in ${region.name}`, {
											permanent: false,
											direction: 'top',
											className: 'custom-tooltip'
										});
									
									tourMarkers.push(tourMarker);
								}
							
							// Add hover effects with immediate popup
								polygon.on('mouseover', function(e) {
									this.setStyle({
										fillOpacity: 0.4,
										weight: 3,
										opacity: 1.0
									});
									
									// Create enhanced hover popup content
									const assignedTreks = region.assigned_trekking ? region.assigned_trekking.length : 0;
									const assignedTours = region.assigned_tours ? region.assigned_tours.length : 0;
									const hoverContent = `
										<div class="bg-white rounded-lg p-3 shadow-lg" style="min-width: 200px;">
											<div class="flex items-center mb-2">
												<div class="w-3 h-3 rounded-full mr-2" style="background-color: ${regionColor}"></div>
												<h4 class="font-bold text-gray-900">${region.name}</h4>
											</div>
											<div class="text-sm text-gray-600 mb-2">
												${region.description || 'Explore amazing treks and tours in this region.'}
											</div>
											<div class="flex items-center gap-3 text-xs">
												<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full">
													<i class="fas fa-mountain mr-1"></i>${assignedTreks} Treks
												</span>
												<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
													<i class="fas fa-map-marked-alt mr-1"></i>${assignedTours} Tours
												</span>
											</div>
											<div class="mt-2 text-xs text-gray-500">
												<em>Click to see assigned routes</em>
											</div>
										</div>
									`;
									
									// Show immediate popup on hover
									const hoverPopup = L.popup({
										closeButton: false,
										offset: [0, -10],
										className: 'hover-popup'
									})
									.setContent(hoverContent)
									.setLatLng(e.latlng)
									.openOn(map);
									
									polygon._hoverPopup = hoverPopup;
									
									// Show trek markers
									trekMarkers.forEach(marker => marker.setOpacity(1));
								});
								
								polygon.on('mouseout', function(e) {
									this.setStyle({
										fillOpacity: 0.2,
										weight: 2,
										opacity: 0.8
									});
									
									// Remove hover popup
									if (this._hoverPopup) {
										map.removeLayer(this._hoverPopup);
									}
									
									// Hide trek markers
									trekMarkers.forEach(marker => marker.setOpacity(0));
								});
							
							// Add click event for detailed popup with trek routes
							polygon.on('click', function(e) {
								// Add trek route polyline if available
			if (trekRoutes && trekRoutes[region.slug]) {
								const routeLine = L.polyline(trekRoutes[region.slug], {
										color: regionColor,
										weight: 4,
										opacity: 0.8,
										dashArray: '5, 10'
									}).addTo(map);
									
									// Auto-remove route after 5 seconds
									setTimeout(() => {
										if (map.hasLayer(routeLine)) {
											map.removeLayer(routeLine);
										}
									}, 5000);
								}
								
								L.popup({
									maxWidth: 450,
									className: 'custom-popup regional-popup'
								})
								.setContent(popupContent)
								.setLatLng(e.latlng)
								.openOn(map);
							});
							
							if (boundary) {
					bounds.extend(boundary);
				}
						} else if (region.coordinates && region.coordinates.latitude && region.coordinates.longitude) {
				// Fallback to marker if no boundary defined
				const marker = L.marker([region.coordinates.latitude, region.coordinates.longitude], {icon: regionIcon})
					.addTo(map)
					.bindPopup(popupContent, {
						maxWidth: 450,
						className: 'custom-popup regional-popup'
					});
				
				bounds.extend([region.coordinates.latitude, region.coordinates.longitude]);
			}
		}); // Close the forEach loop
		
		// Fit map to show all markers
				if (bounds.isValid()) {
					map.fitBounds(bounds, {padding: [30, 30]});
				}
				
				// Add map legend
				const legend = L.control({position: 'bottomright'});
				
				legend.onAdd = function(map) {
					const div = L.DomUtil.create('div', 'map-legend');
					div.innerHTML = `
						<div style="background: white; padding: 12px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); font-size: 12px; min-width: 180px; border: 1px solid #e5e7eb;">
							<h4 style="margin: 0 0 8px 0; font-size: 14px; font-weight: bold; color: #111827;">Map Legend</h4>
							<div style="margin-bottom: 6px;">
								<span style="display: inline-block; width: 20px; height: 3px; background: #3b82f6; margin-right: 8px; border-radius: 2px;"></span>
								Trekking Region
							</div>
							<div style="margin-bottom: 6px;">
								<span style="display: inline-block; width: 20px; height: 3px; background: #10b981; margin-right: 8px; border-radius: 2px; border-style: dashed;"></span>
								Trek Route
							</div>
							<div style="margin-bottom: 6px;">
								<span style="display: inline-block; width: 12px; height: 12px; background: #f59e0b; border-radius: 50%; margin-right: 8px; border: 1px solid #d97706;"></span>
								Trekking Point
							</div>
							<div style="margin-bottom: 6px;">
								<span style="display: inline-block; width: 12px; height: 12px; background: #3b82f6; border-radius: 50%; margin-right: 8px; border: 1px solid #2563eb;"></span>
								Tour Point
							</div>
							<div style="font-size: 10px; color: #6b7280; margin-top: 8px;">
								<small><i class="fas fa-info-circle mr-1"></i>Click regions to explore<br/>Hover for details</small>
							</div>
						</div>
					`;
					return div;
				};
				
				legend.addTo(map);
				
				// Update counter
				document.getElementById('trek-count').textContent = `${regionCount} regions (${assignedTripsCount} trips)`;
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
			
			if (mapToggleBtn) {
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
			}
		} catch (error) {
			console.error('Error initializing map:', error);
			document.getElementById('trek-count').textContent = 'Error initializing map';
		}
	});
		
		// Function to add test regions when AJAX fails
		function addTestRegions() {
			console.log('Adding test regions...');
			
			const testRegions = [
				{
					region_name: "Everest Region",
					region_slug: "everest",
					description: "Home to the world's highest peak and legendary trekking routes.",
					latitude: 27.9881,
					longitude: 86.9250,
					boundary: [
						[27.5, 86.5],
						[28.2, 86.5],
						[28.2, 87.2],
						[27.5, 87.2]
					],
					trekking_count: 12,
					tours_count: 5,
					image: "<?php echo get_template_directory_uri(); ?>/images/everest.jpg",
					trekking_routes: [
						{
							title: "Everest Base Camp Trek",
							thumbnail: "<?php echo get_template_directory_uri(); ?>/images/everest-base-camp.jpg",
							duration: "14 days",
							difficulty: "Challenging",
							rating: 4.8,
							link: "<?php echo home_url(); ?>/trek/everest-base-camp"
						}
					],
					tours: [
						{
							title: "Everest Helicopter Tour",
							thumbnail: "<?php echo get_template_directory_uri(); ?>/images/helicopter-tour.jpg",
							duration: "1 day",
							rating: 4.9,
							link: "<?php echo home_url(); ?>/tour/everest-helicopter"
						}
					]
				},
				{
					region_name: "Annapurna Region",
					region_slug: "annapurna",
					description: "Diverse landscapes from subtropical valleys to high mountain passes.",
					latitude: 28.3333,
					longitude: 83.8333,
					boundary: [
						[28.0, 83.5],
						[28.6, 83.5],
						[28.6, 84.2],
						[28.0, 84.2]
					],
					trekking_count: 15,
					tours_count: 8,
					image: "<?php echo get_template_directory_uri(); ?>/images/annapurna.jpg",
					trekking_routes: [
						{
							title: "Annapurna Circuit Trek",
							thumbnail: "<?php echo get_template_directory_uri(); ?>/images/annapurna-circuit.jpg",
							duration: "18 days",
							difficulty: "Moderate",
							rating: 4.7,
							link: "<?php echo home_url(); ?>/trek/annapurna-circuit"
						}
					],
					tours: [
						{
							title: "Pokhara Valley Tour",
							thumbnail: "<?php echo get_template_directory_uri(); ?>/images/pokhara.jpg",
							duration: "3 days",
							rating: 4.6,
							link: "<?php echo home_url(); ?>/tour/pokhara-valley"
						}
					]
				},
				{
					region_name: "Langtang Region",
					region_slug: "langtang",
					description: "Closest trekking region to Kathmandu with beautiful valleys and peaks.",
					latitude: 28.1333,
					longitude: 85.5333,
					boundary: [
						[27.8, 85.2],
						[28.4, 85.2],
						[28.4, 85.8],
						[27.8, 85.8]
					],
					trekking_count: 8,
					tours_count: 3,
					image: "<?php echo get_template_directory_uri(); ?>/images/langtang.jpg",
					trekking_routes: [
						{
							title: "Langtang Valley Trek",
							thumbnail: "<?php echo get_template_directory_uri(); ?>/images/langtang-valley.jpg",
							duration: "10 days",
							difficulty: "Moderate",
							rating: 4.5,
							link: "<?php echo home_url(); ?>/trek/langtang-valley"
						}
					],
					tours: [
						{
							title: "Langtang Helicopter Tour",
							thumbnail: "<?php echo get_template_directory_uri(); ?>/images/langtang-helicopter.jpg",
							duration: "1 day",
							rating: 4.8,
							link: "<?php echo home_url(); ?>/tour/langtang-helicopter"
						}
					]
				}
			];
			
			const bounds = L.latLngBounds();
			
			testRegions.forEach(region => {
				const regionColor = getRegionColor(region.region_slug);
				
				// Build popup content
				let popupContent = `
					<div class="bg-white rounded-lg p-4 max-w-md">
						<div class="flex items-center mb-3">
							<div class="w-4 h-4 rounded-full mr-3" style="background-color: ${regionColor}"></div>
							<h3 class="text-lg font-bold text-gray-900">${region.region_name}</h3>
						</div>
						<div class="mb-3">
							<p class="text-sm text-gray-600">${region.description}</p>
						</div>
						<div class="flex items-center gap-3 mb-4">
							<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
								<i class="fas fa-mountain mr-1"></i>${region.trekking_count} Treks
							</span>
							<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
								<i class="fas fa-map-marked-alt mr-1"></i>${region.tours_count} Tours
							</span>
						</div>
				`;
				
				// Add trek routes
				if (region.trekking_routes && region.trekking_routes.length > 0) {
					popupContent += `
						<div class="mb-3">
							<h4 class="font-semibold text-sm text-gray-900 mb-2">Popular Treks</h4>
							${region.trekking_routes.map(trek => `
								<div class="flex items-center mb-2 p-2 bg-gray-50 rounded">
									<img src="${trek.thumbnail}" alt="${trek.title}" class="w-12 h-12 rounded object-cover mr-2">
									<div>
										<div class="font-medium text-sm">${trek.title}</div>
										<div class="text-xs text-gray-500">${trek.duration} • ${trek.difficulty}</div>
									</div>
								</div>
							`).join('')}
						</div>
					`;
				}
				
				// Add tours
				if (region.tours && region.tours.length > 0) {
					popupContent += `
						<div class="mb-3">
							<h4 class="font-semibold text-sm text-gray-900 mb-2">Popular Tours</h4>
							${region.tours.map(tour => `
								<div class="flex items-center mb-2 p-2 bg-blue-50 rounded">
									<img src="${tour.thumbnail}" alt="${tour.title}" class="w-12 h-12 rounded object-cover mr-2">
									<div>
										<div class="font-medium text-sm">${tour.title}</div>
										<div class="text-xs text-gray-500">${tour.duration}</div>
									</div>
								</div>
							`).join('')}
						</div>
					`;
				}
				
				popupContent += `
						<div class="flex gap-2">
							<a href="<?php echo home_url(); ?>/regions/${region.region_slug}" class="flex-1 bg-blue-600 text-white text-center py-2 px-3 rounded text-sm hover:bg-blue-700 transition-colors">
								View All Routes
							</a>
						</div>
					</div>
				`;
				
				// Create polygon
				if (region.boundary && region.boundary.length > 0) {
					const polygon = L.polygon(region.boundary, {
						color: regionColor,
						fillColor: regionColor,
						fillOpacity: 0.2,
						weight: 2,
						opacity: 0.8
					}).addTo(map);
					
					polygon.bindPopup(popupContent);
					
					// Add hover effects
					polygon.on('mouseover', function(e) {
						this.setStyle({
							fillOpacity: 0.4,
							weight: 3,
							opacity: 1.0
						});
					});
					
					polygon.on('mouseout', function(e) {
						this.setStyle({
							fillOpacity: 0.2,
							weight: 2,
							opacity: 0.8
						});
					});
					
					bounds.extend(region.boundary);
				} else {
					// Fallback marker
					const marker = L.marker([region.latitude, region.longitude], {icon: regionIcon})
						.addTo(map)
						.bindPopup(popupContent);
					
					bounds.extend([region.latitude, region.longitude]);
				}
			});
			
			// Fit map to show all test regions
			if (bounds.isValid()) {
				map.fitBounds(bounds, {padding: [30, 30]});
			}
			
			// Update counter
			document.getElementById('trek-count').textContent = `Test: ${testRegions.length} regions`;
		}
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
			
			/* Custom tooltip styles */
			.custom-tooltip {
				background: rgba(0, 0, 0, 0.9) !important;
				border: none !important;
				border-radius: 6px !important;
				color: white !important;
				font-size: 12px !important;
				padding: 6px 10px !important;
				box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
			}
			
			.custom-tooltip::before {
				border-top-color: rgba(0, 0, 0, 0.9) !important;
			}
			
			/* Enhanced map legend */
			.map-legend {
				margin: 10px !important;
			}
			
			.map-legend .leaflet-control {
				background: none !important;
				box-shadow: none !important;
			}
			
			/* Enhanced popup styles */
			.regional-popup .leaflet-popup-content-wrapper {
				border-radius: 12px !important;
				overflow: hidden;
				box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15) !important;
			}
			
			/* Trek marker animation */
			.trek-marker {
				animation: pulse 2s infinite;
			}
			
			@keyframes pulse {
				0% {
					transform: scale(1);
					opacity: 1;
				}
				50% {
					transform: scale(1.1);
					opacity: 0.8;
				}
				100% {
					transform: scale(1);
					opacity: 1;
				}
			}
			
			/* Route line styling */
			.route-line {
				animation: dash 3s linear infinite;
			}
			
			@keyframes dash {
				to {
					stroke-dashoffset: -20;
				}
			}
			
			/* Hover popup styles */
			.hover-popup .leaflet-popup-content-wrapper {
				background: white;
				border-radius: 8px;
				box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
				padding: 0;
			}
			
			.hover-popup .leaflet-popup-content {
				margin: 0;
				padding: 0;
			}
			
			.hover-popup .leaflet-popup-tip {
				background: white;
				box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			}
			
			/* Enhanced region polygon hover */
			.region-polygon:hover {
				filter: brightness(1.1);
			}
			</style>
	</section>

	<?php if (get_theme_mod('tznew_show_trek_blocks', true)) : ?>
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
	<?php endif; ?>

	<?php if (get_theme_mod('tznew_show_why_choose', true)) : ?>
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
			
			<?php if (get_theme_mod('tznew_statistics_show', true)) : ?>
			<!-- Statistics Section -->
			<div class="mt-20 statistics-section bg-gradient-to-r from-green-600 to-teal-600 rounded-3xl p-12 text-white">
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
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

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

	<?php if (get_theme_mod('tznew_show_popular_trips', true)) : ?>
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
				$popular_trips_count = get_theme_mod('tznew_popular_trips_count', 3);
				$popular_trekking_args = array(
					'post_type'      => 'trekking',
					'posts_per_page' => intval($popular_trips_count),
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
	<?php if (get_theme_mod('tznew_adventure_show', true)) : ?>
	<section class="adventure-section py-20 bg-gradient-to-br from-green-600 to-blue-600 text-white">
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
	<?php endif; ?>

	<?php
	// Modern Popular Tours Section
	if (function_exists('get_field') && get_theme_mod('tznew_popular_tours_show', true)) :
		$popular_tours_title = tznew_get_field_safe('popular_tours_title', 'option');
		$popular_tours_subtitle = tznew_get_field_safe('popular_tours_subtitle', 'option');
		$popular_tours_count = get_theme_mod('tznew_popular_tours_count', 6);
		?>
		<section class="popular-tours-section py-24 bg-gradient-to-br from-white via-blue-50 to-indigo-50 relative overflow-hidden">
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
					?>
				<?php else : ?>
						<p class="text-center"><?php esc_html_e('No popular tours found.', 'tznew'); ?></p>
					<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>
	<?php endif; ?>

	<?php
	// Modern Testimonials Section
	if (function_exists('get_field') && get_theme_mod('tznew_testimonials_show', true)) :
		$testimonials_title = tznew_get_field_safe('testimonials_title', 'option');
		$testimonials_subtitle = tznew_get_field_safe('testimonials_subtitle', 'option');
		$testimonials = tznew_get_field_safe('testimonials', 'option');
		?>
		<section class="testimonials-section py-24 bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800 text-white relative overflow-hidden">
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
				
				<?php if ($testimonials) : 
					$testimonials_count = get_theme_mod('tznew_testimonials_count', 6);
					$limited_testimonials = array_slice($testimonials, 0, $testimonials_count);
				?>
					<div class="testimonial-slider max-w-6xl mx-auto">
						<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
							<?php foreach ($limited_testimonials as $testimonial) : ?>
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
				?>
				<?php else : ?>
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