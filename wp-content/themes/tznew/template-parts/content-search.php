<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('search-result-item bg-white rounded-xl shadow-lg hover:shadow-xl overflow-hidden transition-all duration-300 transform hover:-translate-y-1 border border-gray-100'); ?> data-post-type="<?php echo esc_attr( get_post_type() ); ?>">
	<div class="relative">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="relative overflow-hidden">
				<?php tznew_post_thumbnail('medium', 'w-full h-48 object-cover transition-transform duration-300 hover:scale-105'); ?>
				<div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
				
				<!-- Post Type Badge -->
				<div class="absolute top-3 left-3">
					<?php
					$main_post_type = get_post_type();
					// Ensure post type is a valid string
					if (is_array($main_post_type) || empty($main_post_type) || !is_string($main_post_type)) {
						$main_post_type = 'post'; // fallback
					}
					$main_post_type_obj = get_post_type_object($main_post_type);
					$badge_colors = [
						'trekking' => 'bg-green-500',
						'tours' => 'bg-blue-500',
						'post' => 'bg-purple-500',
						'blog' => 'bg-orange-500'
					];
					$badge_color = isset($badge_colors[$main_post_type]) ? $badge_colors[$main_post_type] : 'bg-gray-500';
					$badge_display_name = 'Post'; // default fallback
					if ($main_post_type_obj && isset($main_post_type_obj->labels->singular_name)) {
						$badge_display_name = $main_post_type_obj->labels->singular_name;
					} elseif (is_string($main_post_type)) {
						$badge_display_name = ucfirst($main_post_type);
					}
					?>
					<span class="<?php echo esc_attr( $badge_color ); ?> text-white text-xs font-semibold px-3 py-1 rounded-full backdrop-blur-sm">
						<?php echo esc_html($badge_display_name); ?>
					</span>
				</div>
				
				<!-- Difficulty/Rating Badge for Trekking -->
				<?php if ( $main_post_type === 'trekking' ) :
					$difficulties = get_the_terms(get_the_ID(), 'difficulty');
					if ( $difficulties && !is_wp_error($difficulties) ) :
				?>
					<div class="absolute top-3 right-3">
						<span class="bg-yellow-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
							<?php echo esc_html( $difficulties[0]->name ); ?>
						</span>
					</div>
				<?php endif; endif; ?>
			</div>
		<?php else : ?>
			<!-- Fallback for posts without thumbnails -->
			<div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
				<div class="text-center">
					<div class="w-16 h-16 mx-auto mb-2 bg-gray-300 rounded-full flex items-center justify-center">
						<svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
						</svg>
					</div>
					<?php
					$current_post_type = get_post_type();
					// Ensure post type is a valid string
					if (is_array($current_post_type) || empty($current_post_type) || !is_string($current_post_type)) {
						$current_post_type = 'post'; // fallback
					}
					$current_post_type_obj = get_post_type_object($current_post_type);
					$display_name = 'Post'; // default fallback
					if ($current_post_type_obj && isset($current_post_type_obj->labels->singular_name)) {
						$display_name = $current_post_type_obj->labels->singular_name;
					} elseif (is_string($current_post_type)) {
						$display_name = ucfirst($current_post_type);
					}
					?>
					<span class="text-sm text-gray-500"><?php echo esc_html($display_name); ?></span>
				</div>
			</div>
		<?php endif; ?>
	</div>
	
	<div class="entry-content p-6">
		<?php the_title( sprintf( '<h2 class="entry-title text-xl font-bold mb-3 line-clamp-2"><a href="%s" rel="bookmark" class="text-gray-900 hover:text-blue-600 transition-colors duration-200">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta text-sm text-gray-500 mb-3 flex items-center gap-4">
			<span class="flex items-center gap-1">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
				</svg>
				<?php echo get_the_date(); ?>
			</span>
			<span class="flex items-center gap-1">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
				</svg>
				<?php echo get_the_author(); ?>
			</span>
		</div>
		<?php endif; ?>

		<div class="entry-summary mb-4 text-gray-600 line-clamp-3">
			<?php echo wp_trim_words( get_the_excerpt(), 25, '...' ); ?>
		</div>

		<!-- Taxonomies and Meta Info -->
		<div class="entry-footer mb-4">
			<div class="flex flex-wrap gap-2 mb-3">
				<?php
				// Get and validate post type for taxonomies
				$post_type = get_post_type();
				if (is_array($post_type) || empty($post_type)) {
					$post_type = 'post'; // fallback
				}
				
				// For custom post types, show relevant taxonomies
				if ($post_type == 'trekking') :
					$regions = get_the_terms(get_the_ID(), 'region');
					$duration = get_field('duration');
					
					if ($regions && !is_wp_error($regions) && !empty($regions)) :
						?>
						<span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-medium px-3 py-1 rounded-full border border-blue-200">
							<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
							</svg>
							<?php echo esc_html($regions[0]->name); ?>
						</span>
						<?php
					endif;
					
					if ($duration) : ?>
						<span class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-xs font-medium px-3 py-1 rounded-full border border-green-200">
							<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
							<?php echo esc_html($duration); ?>
						</span>
					<?php endif;
					
				elseif ($post_type == 'tours') :
					$regions = get_the_terms(get_the_ID(), 'region');
					$tour_types = get_the_terms(get_the_ID(), 'tour_type');
					$duration = get_field('duration');
					
					if ($regions && !is_wp_error($regions) && !empty($regions)) : ?>
						<span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-medium px-3 py-1 rounded-full border border-blue-200">
							<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
							</svg>
							<?php echo esc_html($regions[0]->name); ?>
						</span>
					<?php endif;
					
					if ($tour_types && !is_wp_error($tour_types) && !empty($tour_types)) : ?>
						<span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 text-xs font-medium px-3 py-1 rounded-full border border-purple-200">
							<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
							</svg>
							<?php echo esc_html($tour_types[0]->name); ?>
						</span>
					<?php endif;
					
					if ($duration) : ?>
						<span class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-xs font-medium px-3 py-1 rounded-full border border-green-200">
							<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
							<?php echo esc_html($duration); ?>
						</span>
					<?php endif;
					
				elseif ($post_type == 'blog') :
					$tags = get_the_terms(get_the_ID(), 'acf_tag');
					if ($tags && !is_wp_error($tags) && !empty($tags)) :
						foreach (array_slice($tags, 0, 3) as $tag) :
							if (isset($tag->name)) : ?>
								<span class="inline-flex items-center gap-1 bg-orange-50 text-orange-700 text-xs font-medium px-3 py-1 rounded-full border border-orange-200">
									<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
									</svg>
									<?php echo esc_html($tag->name); ?>
								</span>
							<?php endif;
						endforeach;
					endif;
					
				elseif ($post_type == 'post') :
					$categories = get_the_category();
					if ($categories && !empty($categories)) :
						foreach (array_slice($categories, 0, 2) as $category) :
							if (isset($category->name)) : ?>
								<span class="inline-flex items-center gap-1 bg-red-50 text-red-700 text-xs font-medium px-3 py-1 rounded-full border border-red-200">
									<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
									</svg>
									<?php echo esc_html($category->name); ?>
								</span>
							<?php endif;
						endforeach;
					endif;
				endif;
				?>
			</div>
		</div>
		
		<!-- Action Buttons -->
		<div class="flex items-center justify-between pt-4 border-t border-gray-100">
			<div class="flex items-center gap-4 text-sm text-gray-500">
				<span class="flex items-center gap-1">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
					</svg>
					<?php echo esc_html__( 'View Details', 'tznew' ); ?>
				</span>
				<?php if ( in_array( $post_type, ['trekking', 'tours'] ) ) : ?>
					<span class="flex items-center gap-1">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
						</svg>
						<?php echo esc_html__( 'Book Now', 'tznew' ); ?>
					</span>
				<?php endif; ?>
			</div>
			
			<a href="<?php echo esc_url(get_permalink()); ?>" 
			   class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
				<?php echo esc_html__( 'Learn More', 'tznew' ); ?>
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
				</svg>
			</a>
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->