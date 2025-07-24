<?php
/**
 * Template part for displaying blog posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-md overflow-hidden'); ?>>
	<?php tznew_post_thumbnail('medium_large', 'w-full h-56 object-cover'); ?>
	
	<div class="entry-content p-6">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title text-3xl font-bold mb-4">', '</h1>' );
			?>
			<div class="entry-meta text-sm text-gray-600 mb-6">
				<?php
				tznew_posted_on();
				tznew_posted_by();
				
				// Display tags if available
				$tags = get_the_terms(get_the_ID(), 'acf_tag');
				if ($tags && !is_wp_error($tags) && !empty($tags)) :
					?>
					<span class="tags-links ml-4">
						<span class="sr-only"><?php esc_html_e('Tags:', 'tznew'); ?></span>
						<?php
						$tag_links = array();
						foreach ($tags as $tag) {
							if (isset($tag->name) && isset($tag->term_id)) {
								$tag_links[] = '<a href="' . esc_url(get_term_link($tag)) . '" class="text-teal-600 hover:underline">' . esc_html($tag->name) . '</a>';
							}
						}
						if (!empty($tag_links)) {
							echo implode(', ', $tag_links);
						}
						?>
					</span>
					<?php
				endif;
				?>
			</div><!-- .entry-meta -->
			
			<?php
			// Featured Image for single post with larger size
			if (has_post_thumbnail()) :
				?>
				<div class="featured-image mb-6">
					<?php the_post_thumbnail('large', array('class' => 'w-full h-auto rounded-lg')); ?>
				</div>
				<?php
			endif;
			
			// Display blog content
			if (function_exists('get_field')) :
				?>
				<?php
				// Main Content
				$content = tznew_get_field_safe('content');
				if ($content) : ?>
					<div class="blog-content mb-8">
						<div class="prose max-w-none">
							<?php echo wp_kses_post($content); ?>
						</div>
					</div>
				<?php endif; ?>
				
				<?php
				// Gallery
				$gallery = tznew_get_field_safe('gallery');
				if ($gallery && is_array($gallery) && !empty($gallery)) : ?>
					<div class="blog-gallery mb-8">
						<h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Gallery', 'tznew'); ?></h2>
						<div class="grid grid-cols-2 md:grid-cols-3 gap-4">
							<?php foreach ($gallery as $image) : 
								if (!is_array($image) || empty($image['url'])) continue; ?>
								<a href="<?php echo esc_url($image['url']); ?>" class="gallery-item">
									<img src="<?php echo esc_url($image['sizes']['medium'] ?? $image['url']); ?>" alt="<?php echo esc_attr($image['alt'] ?? ''); ?>" class="w-full h-48 object-cover rounded">
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
				
				<?php
				// Author Bio
				$show_author_bio = tznew_get_field_safe('show_author_bio');
				$author_bio = tznew_get_field_safe('author_bio');
				if ($show_author_bio && $author_bio) : ?>
					<div class="blog-author-bio mt-8 p-6 bg-gray-50 rounded-lg flex flex-wrap items-center">
						<?php
						// Author Image
						$author_image = tznew_get_field_safe('author_image');
						if ($author_image && is_array($author_image) && !empty($author_image['url'])) :
							?>
							<div class="author-image w-20 h-20 mr-6">
								<?php $author_name = tznew_get_field_safe('author_name'); ?>
								<img src="<?php echo esc_url($author_image['sizes']['thumbnail'] ?? $author_image['url']); ?>" alt="<?php echo esc_attr($author_name ?? ''); ?>" class="w-full h-full rounded-full object-cover">
							</div>
						<?php endif; ?>
						
						<div class="author-content flex-1">
							<?php $author_name = tznew_get_field_safe('author_name'); if ($author_name) : ?>
								<h3 class="text-lg font-bold mb-2"><?php echo esc_html($author_name); ?></h3>
							<?php endif; ?>
							
							<div class="prose max-w-none">
								<?php echo wp_kses_post($author_bio); ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
				
				<?php
				// Related Posts
				$related_posts = tznew_get_related_posts(get_the_ID(), 'blog', 3);
				if ($related_posts->have_posts()) : ?>
					<div class="blog-related-posts mt-8">
						<h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Related Posts', 'tznew'); ?></h2>
						<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
							<?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
								<div class="related-post bg-gray-50 rounded-lg overflow-hidden">
									<?php if (has_post_thumbnail()) : ?>
										<a href="<?php the_permalink(); ?>" class="block">
											<?php the_post_thumbnail('medium', array('class' => 'w-full h-40 object-cover')); ?>
										</a>
									<?php endif; ?>
									<div class="p-4">
										<h3 class="text-lg font-bold mb-2">
											<a href="<?php the_permalink(); ?>" class="hover:text-teal-600"><?php the_title(); ?></a>
										</h3>
										<div class="text-sm text-gray-600 mb-2"><?php echo get_the_date(); ?></div>
										<div class="text-sm"><?php echo wp_trim_words(get_the_excerpt(), 10, '...'); ?></div>
									</div>
								</div>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</div>
				<?php endif; ?>
				
				<?php
				// Social Sharing
				tznew_social_sharing(get_the_ID());
				?>
				
			<?php else : // If ACF is not available, display regular content ?>
				<div class="blog-content mb-8">
					<?php the_content(); ?>
				</div>
			<?php endif; // End if function_exists('get_field') ?>
			
		else :
			// Archive view
			the_title( '<h2 class="entry-title text-xl font-bold mb-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			?>
			<div class="entry-meta text-sm text-gray-600 mb-4">
				<?php
				tznew_posted_on();
				tznew_posted_by();
				?>
			</div><!-- .entry-meta -->
			
			<?php
			// Excerpt
			if (function_exists('get_field')) :
				$content = tznew_get_field_safe('content');
				if ($content) :
					echo '<div class="blog-excerpt mb-4">' . wp_trim_words(wp_strip_all_tags($content), 30, '...') . '</div>';
				else :
					the_excerpt();
				endif;
			else :
				the_excerpt();
			endif;
			?>
			
			<div class="mt-4">
				<a href="<?php echo esc_url(get_permalink()); ?>" class="inline-block bg-gradient-to-r from-green-500 to-teal-600 hover:from-green-600 hover:to-teal-700 text-white font-medium py-2 px-4 rounded transition duration-300">
					<?php esc_html_e('Read More', 'tznew'); ?>
				</a>
			</div>
		<?php
		endif; // End is_singular() check
		?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->