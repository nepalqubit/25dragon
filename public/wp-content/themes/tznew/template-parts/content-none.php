<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

?>

<section class="no-results not-found bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-8 md:p-12 text-center">
	<div class="max-w-2xl mx-auto">
		<!-- Icon -->
		<div class="mb-6">
			<div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-4">
				<svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
				</svg>
			</div>
		</div>
		
		<header class="page-header mb-6">
			<?php if ( is_search() ) : ?>
				<h1 class="page-title text-2xl md:text-3xl font-bold text-gray-800 mb-4">
					<?php esc_html_e( 'No Results Found', 'tznew' ); ?>
				</h1>
				<p class="text-lg text-gray-600 mb-6">
					<?php 
					/* translators: %s: search query */
					printf( esc_html__( 'Sorry, we couldn\'t find any results for "%s". Try adjusting your search or explore our suggestions below.', 'tznew' ), '<span class="font-semibold text-blue-600">' . get_search_query() . '</span>' ); 
					?>
				</p>
			<?php else : ?>
				<h1 class="page-title text-2xl md:text-3xl font-bold text-gray-800 mb-4">
					<?php esc_html_e( 'Nothing Found', 'tznew' ); ?>
				</h1>
				<p class="text-lg text-gray-600 mb-6">
					<?php esc_html_e( 'It seems we can\'t find what you\'re looking for. Try searching for something else.', 'tznew' ); ?>
				</p>
			<?php endif; ?>
		</header>

		<div class="page-content">
			<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
				<div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 mb-8">
					<h3 class="text-lg font-semibold text-gray-800 mb-3"><?php esc_html_e( 'Get Started', 'tznew' ); ?></h3>
					<p class="text-gray-600 mb-4">
						<?php esc_html_e( 'Ready to publish your first post?', 'tznew' ); ?>
					</p>
					<a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>" 
					   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-300">
						<?php esc_html_e( 'Create New Post', 'tznew' ); ?>
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
						</svg>
					</a>
				</div>
			<?php endif; ?>
			
			<!-- Search Form -->
			<div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 mb-8">
				<h3 class="text-lg font-semibold text-gray-800 mb-4"><?php esc_html_e( 'Try a New Search', 'tznew' ); ?></h3>
				<?php get_search_form(); ?>
			</div>
			
			<!-- Search Suggestions -->
			<div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
				<h3 class="text-lg font-semibold text-gray-800 mb-4"><?php esc_html_e( 'Popular Searches', 'tznew' ); ?></h3>
				<div class="flex flex-wrap gap-2 mb-4">
					<?php
					$popular_searches = array(
						'Everest Base Camp',
						'Annapurna Circuit',
						'Manaslu Trek',
						'Langtang Valley',
						'Chitwan Safari',
						'Pokhara Tours'
					);
					foreach ( $popular_searches as $search_term ) :
						$search_url = home_url( '/?s=' . urlencode( $search_term ) );
						?>
						<a href="<?php echo esc_url( $search_url ); ?>" 
						   class="inline-flex items-center gap-1 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-medium px-3 py-2 rounded-full border border-blue-200 transition-colors duration-300">
							<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
							</svg>
							<?php echo esc_html( $search_term ); ?>
						</a>
					<?php endforeach; ?>
				</div>
				
				<div class="text-center pt-4">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" 
					   class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium transition-colors duration-300">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
						</svg>
						<?php esc_html_e( 'Back to Homepage', 'tznew' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>