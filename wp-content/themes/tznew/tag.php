<?php
/**
 * The template for displaying tag archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php if ( have_posts() ) : ?>
        <!-- Hero Section -->
        <section class="tag-hero bg-gradient-to-r from-purple-600 to-pink-600 text-white py-16">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="mb-4">
                        <span class="inline-block bg-white/20 text-white px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-tag mr-1"></i>
                            <?php esc_html_e( 'Tag', 'tznew' ); ?>
                        </span>
                    </div>
                    
                    <h1 class="text-3xl lg:text-5xl font-bold mb-4">
                        #<?php single_tag_title(); ?>
                    </h1>
                    
                    <?php
                    $tag_description = tag_description();
                    if ( ! empty( $tag_description ) ) :
                    ?>
                        <div class="text-xl text-white/90 mb-6">
                            <?php echo $tag_description; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="flex flex-wrap items-center justify-center gap-4 text-sm text-white/80">
                        <span>
                            <i class="fas fa-file-alt mr-1"></i>
                            <?php
                            global $wp_query;
                            $total_posts = $wp_query->found_posts;
                            printf(
                                _n( '%d post tagged', '%d posts tagged', $total_posts, 'tznew' ),
                                $total_posts
                            );
                            ?>
                        </span>
                        <span>
                            <i class="fas fa-calendar-alt mr-1"></i>
                            <?php esc_html_e( 'Latest posts', 'tznew' ); ?>
                        </span>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Related Tags Section -->
        <section class="related-tags bg-gray-50 py-8">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <h3 class="text-lg font-semibold mb-4 text-center"><?php esc_html_e( 'Related Tags', 'tznew' ); ?></h3>
                    
                    <?php
                    // Get related tags
                    $related_tags = get_tags(array(
                        'exclude' => get_queried_object_id(),
                        'number' => 10,
                        'orderby' => 'count',
                        'order' => 'DESC'
                    ));
                    
                    if ( $related_tags ) :
                    ?>
                        <div class="flex flex-wrap gap-2 justify-center">
                            <?php foreach ( $related_tags as $tag ) : ?>
                                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" 
                                   class="inline-block bg-white hover:bg-purple-50 text-purple-600 hover:text-purple-700 px-3 py-1 rounded-full text-sm border border-purple-200 hover:border-purple-300 transition duration-300">
                                    #<?php echo esc_html( $tag->name ); ?>
                                    <span class="text-xs text-purple-400 ml-1">(<?php echo $tag->count; ?>)</span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        
        <!-- Posts Grid Section -->
        <section class="tag-posts py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <!-- Filter Options -->
                    <div class="mb-8">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold mb-4"><?php esc_html_e( 'Filter & Sort', 'tznew' ); ?></h3>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <!-- Post Type Filter -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <?php esc_html_e( 'Post Type', 'tznew' ); ?>
                                    </label>
                                    <div class="flex flex-wrap gap-2">
                                        <button class="filter-btn active" data-filter="all">
                                            <?php esc_html_e( 'All', 'tznew' ); ?>
                                        </button>
                                        <button class="filter-btn" data-filter="blog">
                                            <?php esc_html_e( 'Blogs', 'tznew' ); ?>
                                        </button>
                                        <button class="filter-btn" data-filter="trekking">
                                            <?php esc_html_e( 'Trekking', 'tznew' ); ?>
                                        </button>
                                        <button class="filter-btn" data-filter="tours">
                                            <?php esc_html_e( 'Tours', 'tznew' ); ?>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Sort Options -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <?php esc_html_e( 'Sort By', 'tznew' ); ?>
                                    </label>
                                    <div class="flex flex-wrap gap-2">
                                        <button class="sort-btn active" data-sort="date">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            <?php esc_html_e( 'Latest', 'tznew' ); ?>
                                        </button>
                                        <button class="sort-btn" data-sort="title">
                                            <i class="fas fa-sort-alpha-down mr-1"></i>
                                            <?php esc_html_e( 'A-Z', 'tznew' ); ?>
                                        </button>
                                        <button class="sort-btn" data-sort="popular">
                                            <i class="fas fa-fire mr-1"></i>
                                            <?php esc_html_e( 'Popular', 'tznew' ); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Posts Grid -->
                    <div class="posts-grid">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            <?php
                            while ( have_posts() ) :
                                the_post();
                                get_template_part( 'template-parts/content', 'grid' );
                            endwhile;
                            ?>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-wrapper mt-12">
                        <?php
                        if ( function_exists( 'tznew_pagination' ) ) {
                            tznew_pagination();
                        } else {
                            the_posts_navigation();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
        
    <?php else : ?>
        <!-- No Posts Found -->
        <section class="no-posts py-16">
            <div class="container mx-auto px-4">
                <div class="max-w-2xl mx-auto text-center">
                    <div class="mb-8">
                        <i class="fas fa-tag text-6xl text-gray-300 mb-4"></i>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">
                            <?php esc_html_e( 'No posts found with this tag', 'tznew' ); ?>
                        </h2>
                        <p class="text-gray-600 mb-6">
                            <?php esc_html_e( 'It looks like there are no posts tagged with this term yet. Try exploring other tags or browse our latest content.', 'tznew' ); ?>
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" 
                           class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition duration-300">
                            <i class="fas fa-home"></i>
                            <?php esc_html_e( 'Back to Home', 'tznew' ); ?>
                        </a>
                        
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'blog' ) ); ?>" 
                           class="inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-lg transition duration-300">
                            <i class="fas fa-blog"></i>
                            <?php esc_html_e( 'View All Blogs', 'tznew' ); ?>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<style>
.filter-btn, .sort-btn {
    @apply px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition duration-300 cursor-pointer text-sm;
}

.filter-btn.active, .sort-btn.active {
    @apply bg-purple-600 text-white border-purple-600 hover:bg-purple-700;
}

.filter-btn:hover, .sort-btn:hover {
    @apply shadow-md;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const sortButtons = document.querySelectorAll('.sort-btn');
    const postsGrid = document.querySelector('.posts-grid .grid');
    
    // Filter functionality
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const filterType = this.dataset.filter;
            
            // Add loading state
            postsGrid.style.opacity = '0.5';
            
            setTimeout(() => {
                postsGrid.style.opacity = '1';
            }, 500);
        });
    });
    
    // Sort functionality
    sortButtons.forEach(button => {
        button.addEventListener('click', function() {
            sortButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const sortType = this.dataset.sort;
            
            // Add loading state
            postsGrid.style.opacity = '0.5';
            
            setTimeout(() => {
                postsGrid.style.opacity = '1';
            }, 500);
        });
    });
});
</script>

<?php
get_sidebar();
get_footer();
?>