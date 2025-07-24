<?php
/**
 * The template for displaying search forms
 *
 * @package TZnew
 */

// Unique ID for search form
$tznew_unique_id = wp_unique_id( 'search-form-' );
?>

<form role="search" method="get" class="search-form relative w-full max-w-2xl mx-auto" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $tznew_unique_id ); ?>" class="sr-only">
		<?php echo esc_html_x( 'Search for:', 'label', 'tznew' ); ?>
	</label>
	
	<div class="relative">
		<!-- Search Icon -->
		<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
			<svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
			</svg>
		</div>
		
		<!-- Search Input -->
		<input type="search" 
			id="<?php echo esc_attr( $tznew_unique_id ); ?>" 
			class="search-field search-input w-full pl-12 pr-32 py-4 text-lg border-2 border-white/30 rounded-full bg-white/90 backdrop-blur-sm placeholder-gray-500 focus:outline-none focus:ring-4 focus:ring-white/50 focus:border-white focus:bg-white transition-all duration-300 shadow-lg" 
			placeholder="<?php echo esc_attr_x( 'Search treks, tours, destinations...', 'placeholder', 'tznew' ); ?>" 
			value="<?php echo get_search_query(); ?>" 
			name="s" 
			autocomplete="off" />
		
		<!-- Clear Button -->
		<button type="button" class="search-clear absolute inset-y-0 right-20 flex items-center pr-2 opacity-0 transition-opacity duration-200" aria-label="<?php esc_attr_e( 'Clear search', 'tznew' ); ?>">
			<svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
			</svg>
		</button>
		
		<!-- Submit Button -->
		<button type="submit" class="search-submit absolute inset-y-0 right-0 flex items-center justify-center w-16 bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600 text-white font-medium rounded-full mr-1 my-1 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
			<span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', 'tznew' ); ?></span>
			<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
			</svg>
		</button>
	</div>
	
	<!-- Live Search Results Container -->
	<div class="search-results absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 z-50 hidden max-h-96 overflow-y-auto">
		<!-- Results will be populated by JavaScript -->
	</div>
	
	<!-- Search Suggestions -->
	<div class="search-suggestions mt-4 text-center">
		<div class="text-sm text-white/80 mb-2"><?php esc_html_e( 'Popular searches:', 'tznew' ); ?></div>
		<div class="flex flex-wrap justify-center gap-2">
			<?php
			$quick_searches = ['Everest Base Camp', 'Annapurna Circuit', 'Chitwan Safari', 'Kathmandu Tour'];
			foreach ( $quick_searches as $search ) :
			?>
				<a href="<?php echo esc_url( home_url( '/?s=' . urlencode( $search ) ) ); ?>" 
				   class="inline-block bg-white/20 hover:bg-white/30 text-white text-xs px-3 py-1 rounded-full transition duration-300 backdrop-blur-sm">
					<?php echo esc_html( $search ); ?>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</form>