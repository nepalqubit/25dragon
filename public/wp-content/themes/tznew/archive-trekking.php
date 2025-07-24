<?php
/**
 * The template for displaying trekking archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

get_header();
?>

<?php
// Check if Elementor Theme Builder archive template exists for trekking
if ( function_exists( 'tznew_elementor_location_exists' ) && tznew_elementor_location_exists( 'archive' ) ) {
    // Use Elementor Theme Builder archive template
    tznew_elementor_do_location( 'archive' );
} else {
    // Fallback to default trekking archive template
    ?>
    <main id="primary" class="site-main">
	<!-- Hero Section -->
	<?php if (get_theme_mod('tznew_archive_header_show', true)) : ?>
	<section class="relative archive-header bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 py-20">
		<div class="absolute inset-0 overlay bg-black/20"></div>
		<div class="relative z-10 container mx-auto px-4 text-center text-white">
			<h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
				<?php echo esc_html(get_theme_mod('tznew_trekking_archive_title', 'Trekking Adventures')); ?>
			</h1>
			<p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto leading-relaxed">
				<?php echo esc_html(get_theme_mod('tznew_trekking_archive_subtitle', 'Discover breathtaking trails and unforgettable mountain experiences in the heart of the Himalayas')); ?>
			</p>
			
			<!-- Quick Stats -->
			<div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12 max-w-4xl mx-auto">
				<?php
				$total_treks = wp_count_posts('trekking')->publish;
				$regions = get_terms(array('taxonomy' => 'region', 'hide_empty' => true));
				$difficulties = get_terms(array('taxonomy' => 'difficulty', 'hide_empty' => true));
				?>
				<div class="text-center">
					<div class="text-3xl font-bold text-yellow-400"><?php echo esc_html($total_treks); ?>+</div>
					<div class="text-blue-200"><?php esc_html_e('Treks Available', 'tznew'); ?></div>
				</div>
				<div class="text-center">
					<div class="text-3xl font-bold text-yellow-400"><?php echo esc_html(count($regions)); ?>+</div>
					<div class="text-blue-200"><?php esc_html_e('Regions', 'tznew'); ?></div>
				</div>
				<div class="text-center">
					<div class="text-3xl font-bold text-yellow-400"><?php echo esc_html(count($difficulties)); ?>+</div>
					<div class="text-blue-200"><?php esc_html_e('Difficulty Levels', 'tznew'); ?></div>
				</div>
				<div class="text-center">
					<div class="text-3xl font-bold text-yellow-400">15+</div>
					<div class="text-blue-200"><?php esc_html_e('Years Experience', 'tznew'); ?></div>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- Filter Section -->
	<?php if (get_theme_mod('tznew_archive_filters_show', true)) : ?>
	<section class="archive-filters bg-white shadow-lg relative z-20 -mt-8 mx-4 md:mx-8 rounded-2xl overflow-hidden">
		<div class="container mx-auto px-6 py-8">
			<form method="GET" class="trek-filter-form" id="trekking-filter">
				<div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-6">
					<!-- Search -->
					<div class="lg:col-span-2">
						<label for="search" class="block text-sm font-medium text-gray-700 mb-2">
							<i class="fas fa-search mr-1"></i>
							<?php esc_html_e('Search Treks', 'tznew'); ?>
						</label>
						<input type="text" name="search" id="search" 
							   value="<?php echo esc_attr(get_query_var('search', '')); ?>"
							   placeholder="<?php esc_attr_e('Search by trek name...', 'tznew'); ?>"
							   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
					</div>
					
					<!-- Region Filter -->
					<div>
						<label for="region" class="block text-sm font-medium text-gray-700 mb-2">
							<i class="fas fa-location-dot mr-1"></i>
							<?php esc_html_e('Region', 'tznew'); ?>
						</label>
						<select name="region" id="region" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
							<option value=""><?php esc_html_e('All Regions', 'tznew'); ?></option>
							<?php
							if ($regions && !is_wp_error($regions)) :
								foreach ($regions as $region) :
									$selected = (get_query_var('region') == $region->slug) ? 'selected' : '';
							?>
								<option value="<?php echo esc_attr($region->slug); ?>" <?php echo $selected; ?>>
									<?php echo esc_html($region->name); ?> (<?php echo esc_html($region->count); ?>)
								</option>
							<?php
								endforeach;
							endif;
							?>
						</select>
					</div>
					
					<!-- Difficulty Filter -->
					<div>
						<label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
							<i class="fas fa-mountain mr-1"></i>
							<?php esc_html_e('Difficulty', 'tznew'); ?>
						</label>
						<select name="difficulty" id="difficulty" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
							<option value=""><?php esc_html_e('All Levels', 'tznew'); ?></option>
							<?php
							if ($difficulties && !is_wp_error($difficulties)) :
								foreach ($difficulties as $difficulty) :
									$selected = (get_query_var('difficulty') == $difficulty->slug) ? 'selected' : '';
							?>
								<option value="<?php echo esc_attr($difficulty->slug); ?>" <?php echo $selected; ?>>
									<?php echo esc_html($difficulty->name); ?> (<?php echo esc_html($difficulty->count); ?>)
								</option>
							<?php
								endforeach;
							endif;
							?>
						</select>
					</div>
					
					<!-- Duration Filter -->
					<div>
						<label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
							<i class="fas fa-clock mr-1"></i>
							<?php esc_html_e('Duration', 'tznew'); ?>
						</label>
						<select name="duration" id="duration" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
							<option value=""><?php esc_html_e('Any Duration', 'tznew'); ?></option>
							<option value="1-7" <?php selected(get_query_var('duration'), '1-7'); ?>><?php esc_html_e('1-7 Days', 'tznew'); ?></option>
							<option value="8-14" <?php selected(get_query_var('duration'), '8-14'); ?>><?php esc_html_e('8-14 Days', 'tznew'); ?></option>
							<option value="15-21" <?php selected(get_query_var('duration'), '15-21'); ?>><?php esc_html_e('15-21 Days', 'tznew'); ?></option>
							<option value="22+" <?php selected(get_query_var('duration'), '22+'); ?>><?php esc_html_e('22+ Days', 'tznew'); ?></option>
						</select>
					</div>
				</div>
				
				<!-- Featured/Popular Toggle -->
				<div class="mb-6">
					<div class="flex items-center justify-center">
						<div class="bg-gray-100 rounded-xl p-1 flex">
							<button type="button" id="all-treks-btn" class="trek-type-toggle active px-6 py-2 rounded-lg font-medium transition-all duration-300">
								<i class="fas fa-list mr-2"></i>
								<?php esc_html_e('All Treks', 'tznew'); ?>
							</button>
							<button type="button" id="featured-treks-btn" class="trek-type-toggle px-6 py-2 rounded-lg font-medium transition-all duration-300">
								<i class="fas fa-star mr-2"></i>
								<?php esc_html_e('Featured Treks', 'tznew'); ?>
							</button>
							<button type="button" id="popular-treks-btn" class="trek-type-toggle px-6 py-2 rounded-lg font-medium transition-all duration-300">
								<i class="fas fa-fire mr-2"></i>
								<?php esc_html_e('Popular Treks', 'tznew'); ?>
							</button>
						</div>
					</div>
				</div>

				<div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
					<div class="flex gap-3">
						<button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 flex items-center">
							<i class="fas fa-filter mr-2"></i>
							<?php esc_html_e('Apply Filters', 'tznew'); ?>
						</button>
						<a href="<?php echo esc_url(get_post_type_archive_link('trekking')); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-8 rounded-xl transition-all duration-300 flex items-center">
							<i class="fas fa-times mr-2"></i>
							<?php esc_html_e('Clear All', 'tznew'); ?>
						</a>
					</div>
					
					<!-- Sort Options -->
					<div class="flex items-center gap-3">
						<label for="sort" class="text-sm font-medium text-gray-700">
							<i class="fas fa-sort mr-1"></i>
							<?php esc_html_e('Sort by:', 'tznew'); ?>
						</label>
						<select name="sort" id="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
							<option value="date" <?php selected(get_query_var('sort'), 'date'); ?>><?php esc_html_e('Latest', 'tznew'); ?></option>
							<option value="title" <?php selected(get_query_var('sort'), 'title'); ?>><?php esc_html_e('Name A-Z', 'tznew'); ?></option>
							<option value="duration" <?php selected(get_query_var('sort'), 'duration'); ?>><?php esc_html_e('Duration', 'tznew'); ?></option>
							<option value="difficulty" <?php selected(get_query_var('sort'), 'difficulty'); ?>><?php esc_html_e('Difficulty', 'tznew'); ?></option>
						</select>
					</div>
				</div>
			</form>
		</div>
	</section>
	<?php endif; ?>

	<!-- Results Section -->
	<section class="container mx-auto px-4 py-12">
		<?php if ( have_posts() ) : ?>
			<!-- Results Header -->
			<div class="flex flex-col sm:flex-row justify-between items-center mb-8">
				<div class="mb-4 sm:mb-0">
					<h2 class="text-2xl font-bold text-gray-800">
						<?php
						global $wp_query;
						$total_posts = $wp_query->found_posts;
						printf(
							_n('%d Trek Found', '%d Treks Found', $total_posts, 'tznew'),
							number_format_i18n($total_posts)
						);
						?>
					</h2>
					<?php if (is_search() || !empty($_GET['region']) || !empty($_GET['difficulty']) || !empty($_GET['duration']) || !empty($_GET['sort'])) : ?>
						<p class="text-gray-600 mt-1">
							<?php esc_html_e('Showing filtered results', 'tznew'); ?>
						</p>
					<?php endif; ?>
				</div>
				
				<!-- View Toggle -->
				<div class="flex items-center gap-2 bg-gray-100 rounded-lg p-1">
					<button class="view-toggle active" data-view="grid" title="<?php esc_attr_e('Grid View', 'tznew'); ?>">
						<i class="fas fa-th-large"></i>
					</button>
					<button class="view-toggle" data-view="list" title="<?php esc_attr_e('List View', 'tznew'); ?>">
						<i class="fas fa-list"></i>
					</button>
				</div>
			</div>

			<!-- Treks Grid/List -->
			<div id="treks-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 transition-all duration-300">
				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class('trek-card bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2 group'); ?>>
						<!-- Trek Image -->
						<div class="relative overflow-hidden h-64">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('medium_large', array('class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-110')); ?>
							<?php else : ?>
								<div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
									<i class="fas fa-mountain text-white text-4xl"></i>
								</div>
							<?php endif; ?>
							
							<!-- Overlay with Quick Info -->
							<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
								<div class="absolute bottom-4 left-4 right-4">
									<?php
									$difficulty = tznew_get_field_safe('difficulty');
									$duration = tznew_get_field_safe('duration');
									if ($difficulty || $duration) :
									?>
										<div class="flex gap-2 mb-2">
											<?php if ($difficulty) : ?>
												<span class="bg-orange-500/90 backdrop-blur-sm text-white px-2 py-1 rounded-full text-xs font-medium">
													<i class="fas fa-mountain mr-1"></i>
													<?php echo esc_html(ucfirst($difficulty)); ?>
												</span>
											<?php endif; ?>
											<?php if ($duration) : ?>
												<span class="bg-blue-500/90 backdrop-blur-sm text-white px-2 py-1 rounded-full text-xs font-medium">
													<i class="fas fa-clock mr-1"></i>
													<?php echo esc_html($duration); ?> <?php echo esc_html(_n('Day', 'Days', intval($duration), 'tznew')); ?>
												</span>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
							
							<!-- Region Badge -->
							<?php
							$regions = get_the_terms(get_the_ID(), 'region');
							if ($regions && !is_wp_error($regions)) :
							?>
								<div class="absolute top-4 left-4">
									<span class="bg-green-500/90 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-medium">
										<i class="fas fa-location-dot mr-1"></i>
										<?php echo esc_html($regions[0]->name); ?>
									</span>
								</div>
							<?php endif; ?>
						</div>
						
						<!-- Trek Content -->
						<div class="p-6">
							<h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-blue-600 transition-colors duration-300">
								<a href="<?php echo esc_url(get_permalink()); ?>" class="stretched-link">
									<?php the_title(); ?>
								</a>
							</h3>
							
							<?php
							$overview = tznew_get_field_safe('overview');
							if ($overview) :
							?>
								<p class="text-gray-600 mb-4 leading-relaxed">
									<?php echo wp_trim_words(wp_strip_all_tags($overview), 20, '...'); ?>
								</p>
							<?php endif; ?>
							
							<!-- Trek Meta -->
							<div class="flex items-center justify-between text-sm text-gray-500 mb-4">
								<div class="flex items-center gap-4">
									<?php
									$max_altitude = tznew_get_field_safe('max_altitude');
									if ($max_altitude) :
									?>
										<span class="flex items-center">
											<i class="fas fa-arrow-up mr-1 text-green-500"></i>
											<?php echo esc_html(number_format($max_altitude)); ?>m
										</span>
									<?php endif; ?>
									
									<?php
									$group_size = tznew_get_field_safe('group_size');
									if ($group_size) :
									?>
										<span class="flex items-center">
											<i class="fas fa-users mr-1 text-blue-500"></i>
											<?php echo esc_html($group_size); ?>
										</span>
									<?php endif; ?>
								</div>
								
								<?php
								$cost_info = tznew_get_field_safe('cost_info');
								if ($cost_info && isset($cost_info['price_usd']) && $cost_info['price_usd']) :
								?>
									<div class="text-right">
										<span class="text-xs text-gray-500"><?php esc_html_e('From', 'tznew'); ?></span>
										<div class="text-lg font-bold text-blue-600">$<?php echo esc_html(number_format($cost_info['price_usd'])); ?></div>
									</div>
								<?php endif; ?>
							</div>
							
							<!-- Action Button -->
							<div class="flex items-center justify-between">
								<a href="<?php echo esc_url(get_permalink()); ?>" 
								   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:scale-105 font-medium text-sm relative z-10">
									<?php esc_html_e('View Details', 'tznew'); ?>
									<i class="fas fa-arrow-right ml-2"></i>
								</a>
								
								<!-- Quick Actions -->
								<div class="flex gap-2">
									<button class="p-2 text-gray-400 hover:text-red-500 transition-colors duration-300 relative z-10" title="<?php esc_attr_e('Add to Wishlist', 'tznew'); ?>">
										<i class="fas fa-heart"></i>
									</button>
									<button class="p-2 text-gray-400 hover:text-blue-500 transition-colors duration-300 relative z-10" title="<?php esc_attr_e('Share', 'tznew'); ?>">
										<i class="fas fa-share-alt"></i>
									</button>
								</div>
							</div>
						</div>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<!-- Pagination -->
			<div class="mt-12">
				<?php
				the_posts_pagination(array(
					'mid_size' => 2,
					'prev_text' => '<i class="fas fa-chevron-left mr-2"></i>' . __('Previous', 'tznew'),
					'next_text' => __('Next', 'tznew') . '<i class="fas fa-chevron-right ml-2"></i>',
					'class' => 'flex justify-center',
				));
				?>
			</div>

		<?php else : ?>
			<!-- No Results -->
			<div class="text-center py-16">
				<div class="max-w-md mx-auto">
					<i class="fas fa-search text-6xl text-gray-300 mb-6"></i>
					<h2 class="text-2xl font-bold text-gray-800 mb-4">
						<?php esc_html_e('No Treks Found', 'tznew'); ?>
					</h2>
					<p class="text-gray-600 mb-8">
						<?php esc_html_e('Sorry, no treks match your search criteria. Try adjusting your filters or search terms.', 'tznew'); ?>
					</p>
					<a href="<?php echo esc_url(get_post_type_archive_link('trekking')); ?>" 
					   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 font-medium">
						<i class="fas fa-arrow-left mr-2"></i>
						<?php esc_html_e('View All Treks', 'tznew'); ?>
					</a>
				</div>
			</div>
		<?php endif; ?>
	</section>
</main><!-- #main -->

<style>
.view-toggle {
    padding: 0.5rem;
    border-radius: 0.375rem;
    transition-property: all;
    transition-duration: 300ms;
    color: rgb(75 85 99);
}
.view-toggle.active {
    background-color: white;
    color: rgb(37 99 235);
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
}
.view-toggle:hover {
    color: rgb(37 99 235);
}

.trek-type-toggle {
    color: rgb(75 85 99); /* text-gray-600 */
}
.trek-type-toggle.active {
    background-color: rgb(37 99 235);
    color: white;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
}

.trek-card .stretched-link::after {
	content: '';
	position: absolute;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	z-index: 1;
}

/* List view styles */
.list-view .trek-card {
    display: flex;
    flex-direction: row;
    height: auto;
}
.list-view .trek-card .relative {
width: 16rem; /* w-64 */
height: 12rem; /* h-48 */ 
flex-shrink: 0;
}
.list-view .trek-card .p-6 {
flex: 1 1 auto; display: flex; flex-direction: column; justify-content: space-between;
}
</style>

<script>
// View toggle functionality
document.addEventListener('DOMContentLoaded', function() {
	const viewToggles = document.querySelectorAll('.view-toggle');
	const container = document.getElementById('treks-container');
	
	viewToggles.forEach(toggle => {
		toggle.addEventListener('click', function() {
			const view = this.dataset.view;
			
			// Update active state
			viewToggles.forEach(t => t.classList.remove('active'));
			this.classList.add('active');
			
			// Update container classes
			if (view === 'list') {
				container.className = 'space-y-6 list-view transition-all duration-300';
			} else {
				container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 transition-all duration-300';
			}
		});
	});
	
	// Trek type toggle functionality
	const trekTypeToggles = document.querySelectorAll('.trek-type-toggle');
	trekTypeToggles.forEach(toggle => {
		toggle.addEventListener('click', function() {
			const trekType = this.id.replace('-btn', '').replace('-', '_');
			
			// Update active state
			trekTypeToggles.forEach(t => t.classList.remove('active'));
			this.classList.add('active');
			
			// Redirect with trek type parameter
			const url = new URL(window.location);
			if (trekType === 'all_treks') {
				url.searchParams.delete('trek_type');
			} else {
				url.searchParams.set('trek_type', trekType.replace('_treks', ''));
			}
			window.location.href = url.toString();
		});
	});
	
	// Set active state based on URL parameter
	const urlParams = new URLSearchParams(window.location.search);
	const trekType = urlParams.get('trek_type');
	if (trekType === 'featured') {
		document.getElementById('featured-treks-btn').classList.add('active');
		document.getElementById('all-treks-btn').classList.remove('active');
	} else if (trekType === 'popular') {
		document.getElementById('popular-treks-btn').classList.add('active');
		document.getElementById('all-treks-btn').classList.remove('active');
	}
	
	// Auto-submit form on select change
	const selects = document.querySelectorAll('#trekking-filter select');
	selects.forEach(select => {
		select.addEventListener('change', function() {
			document.getElementById('trekking-filter').submit();
		});
	});
});
</script>
    <?php
}
?>

<?php
get_footer();