<?php
/**
 * The template for displaying single blog posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package TZnew
 */

get_header();
?>

<?php
// Check if Elementor Theme Builder single template exists for blog posts
if ( function_exists( 'tznew_elementor_location_exists' ) && tznew_elementor_location_exists( 'single' ) ) {
    // Use Elementor Theme Builder single template
    tznew_elementor_do_location( 'single' );
} else {
    // Fallback to default blog template
    ?>
    <main id="primary" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		
		<!-- Hero Section -->
		<section class="relative h-96 md:h-[500px] overflow-hidden">
			<?php if (has_post_thumbnail()) : ?>
				<div class="absolute inset-0">
					<?php the_post_thumbnail('full', array('class' => 'w-full h-full object-cover')); ?>
				</div>
				<div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
			<?php else : ?>
				<div class="absolute inset-0 bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-800"></div>
			<?php endif; ?>
			
			<div class="relative z-10 container mx-auto px-4 h-full flex items-end pb-12">
				<div class="text-white max-w-4xl">
					<div class="mb-4">
						<span class="bg-purple-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
							<?php esc_html_e('Blog Post', 'tznew'); ?>
						</span>
					</div>
					
					<h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 leading-tight">
						<?php the_title(); ?>
					</h1>
					
					<!-- Post Meta -->
					<div class="flex flex-wrap items-center gap-4 text-purple-100">
						<span class="flex items-center">
							<i class="fas fa-calendar-days mr-2"></i>
							<?php echo get_the_date(); ?>
						</span>
						<span class="flex items-center">
							<i class="fas fa-user mr-2"></i>
							<?php echo get_the_author(); ?>
						</span>
						<span class="flex items-center">
							<i class="fas fa-clock mr-2"></i>
							<?php echo tznew_get_reading_time(get_the_content()); ?> <?php esc_html_e('min read', 'tznew'); ?>
						</span>
					</div>
				</div>
			</div>
		</section>
		
		<!-- Content Section -->
		<div class="container mx-auto px-4 py-12">
			<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
				<!-- Main Content -->
				<div class="lg:col-span-3">
					<article class="bg-white rounded-lg shadow-lg overflow-hidden">
						<div class="p-8">
							<?php get_template_part('template-parts/content', 'blog'); ?>
						</div>
						
						<!-- Tags -->
						<?php if (function_exists('tznew_get_field_safe')) : ?>
							<?php $hashtags = tznew_get_field_safe('hashtags', get_the_ID()); ?>
							<?php if (!empty($hashtags)) : ?>
								<div class="px-8 pb-8">
									<div class="border-t border-gray-200 pt-6">
										<h3 class="text-lg font-semibold mb-3 text-gray-800"><?php esc_html_e('Tags', 'tznew'); ?></h3>
										<div class="flex flex-wrap gap-2">
											<?php
											$tags = explode(',', $hashtags);
											foreach ($tags as $tag) :
												$tag = trim($tag);
												if (!empty($tag)) :
													?>
													<span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
														#<?php echo esc_html($tag); ?>
													</span>
													<?php
												endif;
											endforeach;
											?>
										</div>
									</div>
								</div>
							<?php endif; ?>
						<?php endif; ?>
						
						<!-- Social Share -->
						<div class="px-8 pb-8">
							<div class="border-t border-gray-200 pt-6">
								<h3 class="text-lg font-semibold mb-3 text-gray-800"><?php esc_html_e('Share this post', 'tznew'); ?></h3>
								<div class="flex space-x-3">
									<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg transition-colors duration-300">
										<i class="fab fa-facebook-f"></i>
									</a>
									<a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="bg-blue-400 hover:bg-blue-500 text-white p-3 rounded-lg transition-colors duration-300">
										<i class="fab fa-twitter"></i>
									</a>
									<a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="bg-blue-700 hover:bg-blue-800 text-white p-3 rounded-lg transition-colors duration-300">
										<i class="fab fa-linkedin-in"></i>
									</a>
									<a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" target="_blank" class="bg-green-500 hover:bg-green-600 text-white p-3 rounded-lg transition-colors duration-300">
										<i class="fab fa-whatsapp"></i>
									</a>
								</div>
							</div>
						</div>
					</article>
					
					<!-- Author Bio -->
					<div class="bg-white rounded-lg shadow-lg p-6 mt-8">
						<div class="flex items-start space-x-4">
							<div class="flex-shrink-0">
								<?php echo get_avatar(get_the_author_meta('ID'), 80, '', '', array('class' => 'rounded-full')); ?>
							</div>
							<div class="flex-1">
								<h3 class="text-xl font-bold text-gray-800 mb-2"><?php esc_html_e('About', 'tznew'); ?> <?php echo get_the_author(); ?></h3>
								<?php $author_bio = get_the_author_meta('description'); ?>
								<?php if (!empty($author_bio)) : ?>
									<p class="text-gray-600 leading-relaxed"><?php echo esc_html($author_bio); ?></p>
								<?php else : ?>
									<p class="text-gray-600 leading-relaxed"><?php esc_html_e('Travel enthusiast and content creator sharing amazing experiences and travel tips.', 'tznew'); ?></p>
								<?php endif; ?>
							</div>
						</div>
					</div>
					
					<!-- Related Posts -->
					<?php
					$related_posts = get_posts(array(
						'post_type' => 'blog',
						'posts_per_page' => 3,
						'post__not_in' => array(get_the_ID()),
						'orderby' => 'rand',
					));
					
					if (!empty($related_posts)) :
						?>
						<div class="mt-12">
							<h2 class="text-2xl font-bold text-gray-800 mb-6"><?php esc_html_e('Related Posts', 'tznew'); ?></h2>
							<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
								<?php foreach ($related_posts as $related_post) : ?>
									<article class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
										<div class="h-48 overflow-hidden">
											<?php if (has_post_thumbnail($related_post->ID)) : ?>
												<a href="<?php echo get_permalink($related_post->ID); ?>">
													<?php echo get_the_post_thumbnail($related_post->ID, 'medium', array('class' => 'w-full h-full object-cover hover:scale-110 transition-transform duration-300')); ?>
												</a>
											<?php else : ?>
												<div class="w-full h-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
													<i class="fas fa-image text-white text-3xl opacity-50"></i>
												</div>
											<?php endif; ?>
										</div>
										<div class="p-4">
											<h3 class="font-bold text-gray-800 mb-2 hover:text-purple-600 transition-colors duration-300">
												<a href="<?php echo get_permalink($related_post->ID); ?>" class="block">
													<?php echo get_the_title($related_post->ID); ?>
												</a>
											</h3>
											<p class="text-gray-600 text-sm mb-3">
												<?php echo wp_trim_words(get_the_excerpt($related_post->ID), 15); ?>
											</p>
											<div class="text-xs text-gray-500">
												<i class="fas fa-calendar-days mr-1"></i>
												<?php echo get_the_date('', $related_post->ID); ?>
											</div>
										</div>
									</article>
								<?php endforeach; ?>
							</div>
						</div>
						<?php
						wp_reset_postdata();
					endif;
					?>
				</div>
				
				<!-- Sidebar -->
				<div class="lg:col-span-1">
					<!-- Table of Contents -->
					<div class="bg-white rounded-lg shadow-lg p-6 sticky top-8 mb-6">
						<h3 class="text-lg font-bold mb-4 text-gray-800"><?php esc_html_e('Table of Contents', 'tznew'); ?></h3>
						<div id="table-of-contents" class="text-sm">
							<!-- TOC will be generated by JavaScript -->
						</div>
					</div>
					
					<!-- Recent Posts -->
					<?php
					$recent_posts = get_posts(array(
						'post_type' => 'blog',
						'posts_per_page' => 5,
						'post__not_in' => array(get_the_ID()),
					));
					
					if (!empty($recent_posts)) :
						?>
						<div class="bg-white rounded-lg shadow-lg p-6">
							<h3 class="text-lg font-bold mb-4 text-gray-800"><?php esc_html_e('Recent Posts', 'tznew'); ?></h3>
							<div class="space-y-4">
								<?php foreach ($recent_posts as $recent_post) : ?>
									<article class="flex space-x-3">
										<div class="flex-shrink-0 w-16 h-16 overflow-hidden rounded-lg">
											<?php if (has_post_thumbnail($recent_post->ID)) : ?>
												<a href="<?php echo get_permalink($recent_post->ID); ?>">
													<?php echo get_the_post_thumbnail($recent_post->ID, 'thumbnail', array('class' => 'w-full h-full object-cover')); ?>
												</a>
											<?php else : ?>
												<div class="w-full h-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
													<i class="fas fa-image text-white text-sm opacity-50"></i>
												</div>
											<?php endif; ?>
										</div>
										<div class="flex-1 min-w-0">
											<h4 class="text-sm font-semibold text-gray-800 hover:text-purple-600 transition-colors duration-300 line-clamp-2">
												<a href="<?php echo get_permalink($recent_post->ID); ?>" class="block">
													<?php echo get_the_title($recent_post->ID); ?>
												</a>
											</h4>
											<p class="text-xs text-gray-500 mt-1">
												<i class="fas fa-calendar-days mr-1"></i>
												<?php echo get_the_date('M j, Y', $recent_post->ID); ?>
											</p>
										</div>
									</article>
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
		
		<?php
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
		
	endwhile; // End of the loop.
	?>
</main>

<script>
// Generate Table of Contents
document.addEventListener('DOMContentLoaded', function() {
	const content = document.querySelector('.entry-content');
	const toc = document.getElementById('table-of-contents');
	
	if (content && toc) {
		const headings = content.querySelectorAll('h2, h3, h4');
		
		if (headings.length > 0) {
			let tocHTML = '<ul class="space-y-2">';
			
			headings.forEach((heading, index) => {
				const id = 'heading-' + index;
				heading.id = id;
				
				const level = parseInt(heading.tagName.charAt(1));
				const indent = level > 2 ? 'ml-4' : '';
				
				tocHTML += `<li class="${indent}"><a href="#${id}" class="text-purple-600 hover:text-purple-800 transition-colors duration-300 text-sm block py-1">${heading.textContent}</a></li>`;
			});
			
			tocHTML += '</ul>';
			toc.innerHTML = tocHTML;
		} else {
			toc.innerHTML = '<p class="text-gray-500 text-sm">' + '<?php esc_html_e('No headings found', 'tznew'); ?>' + '</p>';
		}
	}
});

// Preloader is now handled universally in header.php
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