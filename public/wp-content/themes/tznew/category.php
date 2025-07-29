<?php
/**
 * The template for displaying category archive pages
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
        <section class="category-hero bg-gradient-to-r from-blue-600 to-green-600 text-white py-16">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-3xl lg:text-5xl font-bold mb-4">
                        <?php single_cat_title(); ?>
                    </h1>
                    
                    <?php
                    $category_description = category_description();
                    if ( ! empty( $category_description ) ) :
                    ?>
                        <div class="text-xl text-white/90 mb-6">
                            <?php echo $category_description; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="flex flex-wrap items-center justify-center gap-4 text-sm text-white/80">
                        <span>
                            <i class="fas fa-folder mr-1"></i>
                            <?php esc_html_e( 'Category', 'tznew' ); ?>
                        </span>
                        <span>
                            <i class="fas fa-file-alt mr-1"></i>
                            <?php
                            global $wp_query;
                            $total_posts = $wp_query->found_posts;
                            printf(
                                _n( '%d post', '%d posts', $total_posts, 'tznew' ),
                                $total_posts
                            );
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Posts Grid Section -->
        <section class="category-posts py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <!-- Filter Options -->
                    <div class="mb-8">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold mb-4"><?php esc_html_e( 'Sort Posts', 'tznew' ); ?></h3>
                            <div class="flex flex-wrap gap-2 lg:gap-4 justify-center lg:justify-start">
                                <button class="sort-btn active" data-sort="date">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    <?php esc_html_e( 'Latest', 'tznew' ); ?>
                                </button>
                                <button class="sort-btn" data-sort="title">
                                    <i class="fas fa-sort-alpha-down mr-1"></i>
                                    <?php esc_html_e( 'Alphabetical', 'tznew' ); ?>
                                </button>
                                <button class="sort-btn" data-sort="popular">
                                    <i class="fas fa-fire mr-1"></i>
                                    <?php esc_html_e( 'Popular', 'tznew' ); ?>
                                </button>
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
                        <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">
                            <?php esc_html_e( 'No posts found in this category', 'tznew' ); ?>
                        </h2>
                        <p class="text-gray-600 mb-6">
                            <?php esc_html_e( 'It looks like there are no posts in this category yet. Check back later or explore other categories.', 'tznew' ); ?>
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" 
                           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-300">
                            <i class="fas fa-home"></i>
                            <?php esc_html_e( 'Back to Home', 'tznew' ); ?>
                        </a>
                        
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'blog' ) ); ?>" 
                           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition duration-300">
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
.sort-btn {
    @apply px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition duration-300 cursor-pointer;
}

.sort-btn.active {
    @apply bg-blue-600 text-white border-blue-600 hover:bg-blue-700;
}

.sort-btn:hover {
    @apply shadow-md;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortButtons = document.querySelectorAll('.sort-btn');
    const postsGrid = document.querySelector('.posts-grid .grid');
    
    sortButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            sortButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const sortType = this.dataset.sort;
            
            // Here you would typically make an AJAX call to sort posts
            // For now, we'll just show a loading state
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