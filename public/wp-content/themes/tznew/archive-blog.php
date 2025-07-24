<?php
/**
 * The template for displaying blog archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

get_header();
?>

<?php
// Check if Elementor Theme Builder archive template exists for blog
if ( function_exists( 'tznew_elementor_location_exists' ) && tznew_elementor_location_exists( 'archive' ) ) {
    // Use Elementor Theme Builder archive template
    tznew_elementor_do_location( 'archive' );
} else {
    // Fallback to default blog archive template
    ?>
    <main id="primary" class="site-main">
	<!-- Hero Section -->
	<section class="relative h-64 md:h-80 overflow-hidden">
		<div class="absolute inset-0 bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-800"></div>
		<div class="absolute inset-0 bg-black/20"></div>
		
		<div class="relative z-10 container mx-auto px-4 h-full flex items-center">
			<div class="text-white max-w-4xl">
				<h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">
					<?php esc_html_e('Travel Blog', 'tznew'); ?>
				</h1>
				<p class="text-lg md:text-xl text-purple-100 leading-relaxed">
					<?php esc_html_e('Discover amazing travel stories, tips, and insights from our adventures around the world.', 'tznew'); ?>
				</p>
			</div>
		</div>
	</section>
	
	<!-- Filter and Search Section -->
	<section class="bg-white shadow-sm border-b">
		<div class="container mx-auto px-4 py-6">
			<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
				<!-- Search -->
				<div class="flex-1 max-w-md">
					<div class="relative">
						<input type="text" id="blog-search" placeholder="<?php esc_attr_e('Search blog posts...', 'tznew'); ?>" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
						<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
							<i class="fas fa-search text-gray-400"></i>
						</div>
					</div>
				</div>
				
				<!-- Sort Options -->
				<div class="flex items-center space-x-4">
					<label for="sort-by" class="text-sm font-medium text-gray-700"><?php esc_html_e('Sort by:', 'tznew'); ?></label>
					<select id="sort-by" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
						<option value="date-desc"><?php esc_html_e('Latest First', 'tznew'); ?></option>
						<option value="date-asc"><?php esc_html_e('Oldest First', 'tznew'); ?></option>
						<option value="title-asc"><?php esc_html_e('Title A-Z', 'tznew'); ?></option>
						<option value="title-desc"><?php esc_html_e('Title Z-A', 'tznew'); ?></option>
					</select>
				</div>
			</div>
		</div>
	</section>
	
	<!-- Blog Posts Grid -->
	<section class="py-12 bg-gray-50">
		<div class="container mx-auto px-4">
			<?php if (have_posts()) : ?>
				<div id="blog-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
					<?php
					while (have_posts()) :
						the_post();
						?>
						<article class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>" data-date="<?php echo get_the_date('Y-m-d'); ?>">
							<!-- Featured Image -->
							<div class="h-48 overflow-hidden">
								<?php if (has_post_thumbnail()) : ?>
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail('medium_large', array('class' => 'w-full h-full object-cover hover:scale-110 transition-transform duration-300')); ?>
									</a>
								<?php else : ?>
									<div class="w-full h-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
										<i class="fas fa-image text-white text-4xl opacity-50"></i>
									</div>
								<?php endif; ?>
							</div>
							
							<!-- Content -->
							<div class="p-6">
								<!-- Meta Info -->
								<div class="flex items-center justify-between mb-3">
									<span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-semibold">
										<?php esc_html_e('Blog', 'tznew'); ?>
									</span>
									<span class="text-gray-500 text-xs">
										<i class="fas fa-calendar-days mr-1"></i>
										<?php echo get_the_date(); ?>
									</span>
								</div>
								
								<!-- Title -->
								<h2 class="text-xl font-bold text-gray-800 mb-3 hover:text-purple-600 transition-colors duration-300">
									<a href="<?php the_permalink(); ?>" class="block">
										<?php the_title(); ?>
									</a>
								</h2>
								
								<!-- Excerpt -->
								<p class="text-gray-600 mb-4 leading-relaxed">
									<?php echo wp_trim_words(get_the_excerpt(), 20); ?>
								</p>
								
								<!-- Author and Reading Time -->
								<div class="flex items-center justify-between text-sm text-gray-500">
									<div class="flex items-center">
										<i class="fas fa-user mr-1"></i>
										<?php echo get_the_author(); ?>
									</div>
									<div class="flex items-center">
										<i class="fas fa-clock mr-1"></i>
										<?php echo tznew_get_reading_time(get_the_content()); ?> <?php esc_html_e('min read', 'tznew'); ?>
									</div>
								</div>
								
								<!-- Read More Button -->
								<div class="mt-4">
									<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-purple-600 hover:text-purple-800 font-semibold transition-colors duration-300">
										<?php esc_html_e('Read More', 'tznew'); ?>
										<i class="fas fa-arrow-right ml-2"></i>
									</a>
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
						'prev_text' => '<i class="fas fa-chevron-left mr-2"></i>' . esc_html__('Previous', 'tznew'),
						'next_text' => esc_html__('Next', 'tznew') . '<i class="fas fa-chevron-right ml-2"></i>',
						'class' => 'flex justify-center',
					));
					?>
				</div>
				
			<?php else : ?>
				<!-- No Posts Found -->
				<div class="text-center py-16">
					<div class="max-w-md mx-auto">
						<i class="fas fa-search text-6xl text-gray-300 mb-6"></i>
						<h2 class="text-2xl font-bold text-gray-800 mb-4"><?php esc_html_e('No Blog Posts Found', 'tznew'); ?></h2>
						<p class="text-gray-600 mb-6"><?php esc_html_e('We couldn\'t find any blog posts. Please check back later for new content.', 'tznew'); ?></p>
						<a href="<?php echo home_url(); ?>" class="inline-flex items-center bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-300">
							<i class="fas fa-home mr-2"></i>
							<?php esc_html_e('Back to Home', 'tznew'); ?>
						</a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const searchInput = document.getElementById('blog-search');
	const sortSelect = document.getElementById('sort-by');
	const blogGrid = document.getElementById('blog-grid');
	const articles = Array.from(blogGrid.querySelectorAll('article'));
	
	// Search functionality
	searchInput.addEventListener('input', function() {
		const searchTerm = this.value.toLowerCase();
		filterAndSort();
	});
	
	// Sort functionality
	sortSelect.addEventListener('change', function() {
		filterAndSort();
	});
	
	function filterAndSort() {
		const searchTerm = searchInput.value.toLowerCase();
		const sortBy = sortSelect.value;
		
		// Filter articles
		let filteredArticles = articles.filter(article => {
			const title = article.dataset.title;
			return title.includes(searchTerm);
		});
		
		// Sort articles
		filteredArticles.sort((a, b) => {
			switch(sortBy) {
				case 'date-desc':
					return new Date(b.dataset.date) - new Date(a.dataset.date);
				case 'date-asc':
					return new Date(a.dataset.date) - new Date(b.dataset.date);
				case 'title-asc':
					return a.dataset.title.localeCompare(b.dataset.title);
				case 'title-desc':
					return b.dataset.title.localeCompare(a.dataset.title);
				default:
					return 0;
			}
		});
		
		// Hide all articles
		articles.forEach(article => {
			article.style.display = 'none';
		});
		
		// Show filtered and sorted articles
		blogGrid.innerHTML = '';
		filteredArticles.forEach(article => {
			article.style.display = 'block';
			blogGrid.appendChild(article);
		});
		
		// Show no results message if needed
		if (filteredArticles.length === 0) {
			blogGrid.innerHTML = `
				<div class="col-span-full text-center py-16">
					<div class="max-w-md mx-auto">
						<i class="fas fa-search text-6xl text-gray-300 mb-6"></i>
						<h3 class="text-xl font-bold text-gray-800 mb-4"><?php esc_html_e('No Results Found', 'tznew'); ?></h3>
						<p class="text-gray-600"><?php esc_html_e('Try adjusting your search terms or filters.', 'tznew'); ?></p>
					</div>
				</div>
			`;
		}
	}
});
</script>

<?php
// Add reading time function if not exists
if (!function_exists('tznew_get_reading_time')) {
	function tznew_get_reading_time($content) {
		$word_count = str_word_count(strip_tags($content));
		$reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
		return max(1, $reading_time);
	}
}
}
?>

<?php get_footer();