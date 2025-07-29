<?php
/**
 * The template for displaying author archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

get_header();

$author = get_queried_object();
?>

<main id="primary" class="site-main">
    <!-- Author Hero Section -->
    <section class="author-hero bg-gradient-to-r from-indigo-600 to-blue-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="flex flex-col lg:flex-row items-center gap-8">
                    <!-- Author Avatar -->
                    <div class="author-avatar flex-shrink-0">
                        <div class="w-32 h-32 lg:w-40 lg:h-40 rounded-full overflow-hidden border-4 border-white/20 shadow-xl">
                            <?php echo get_avatar( $author->ID, 160, '', '', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                        </div>
                    </div>
                    
                    <!-- Author Info -->
                    <div class="author-info flex-1 text-center lg:text-left">
                        <h1 class="text-3xl lg:text-5xl font-bold mb-4">
                            <?php echo esc_html( $author->display_name ); ?>
                        </h1>
                        
                        <?php if ( $author->description ) : ?>
                            <div class="text-xl text-white/90 mb-6">
                                <?php echo wp_kses_post( $author->description ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="author-meta flex flex-wrap items-center justify-center lg:justify-start gap-4 text-sm text-white/80 mb-6">
                            <span>
                                <i class="fas fa-user mr-1"></i>
                                <?php esc_html_e( 'Author', 'tznew' ); ?>
                            </span>
                            
                            <span>
                                <i class="fas fa-file-alt mr-1"></i>
                                <?php
                                $post_count = count_user_posts( $author->ID );
                                printf(
                                    _n( '%d post', '%d posts', $post_count, 'tznew' ),
                                    $post_count
                                );
                                ?>
                            </span>
                            
                            <span>
                                <i class="fas fa-calendar-alt mr-1"></i>
                                <?php
                                printf(
                                    esc_html__( 'Member since %s', 'tznew' ),
                                    date( 'F Y', strtotime( $author->user_registered ) )
                                );
                                ?>
                            </span>
                        </div>
                        
                        <!-- Author Social Links -->
                        <div class="author-social flex flex-wrap gap-3 justify-center lg:justify-start">
                            <?php
                            $website = $author->user_url;
                            $twitter = get_user_meta( $author->ID, 'twitter', true );
                            $facebook = get_user_meta( $author->ID, 'facebook', true );
                            $linkedin = get_user_meta( $author->ID, 'linkedin', true );
                            $instagram = get_user_meta( $author->ID, 'instagram', true );
                            
                            if ( $website ) :
                            ?>
                                <a href="<?php echo esc_url( $website ); ?>" 
                                   target="_blank" 
                                   class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition duration-300">
                                    <i class="fas fa-globe"></i>
                                    <?php esc_html_e( 'Website', 'tznew' ); ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ( $twitter ) : ?>
                                <a href="<?php echo esc_url( $twitter ); ?>" 
                                   target="_blank" 
                                   class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition duration-300">
                                    <i class="fab fa-twitter"></i>
                                    <?php esc_html_e( 'Twitter', 'tznew' ); ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ( $facebook ) : ?>
                                <a href="<?php echo esc_url( $facebook ); ?>" 
                                   target="_blank" 
                                   class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition duration-300">
                                    <i class="fab fa-facebook-f"></i>
                                    <?php esc_html_e( 'Facebook', 'tznew' ); ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ( $linkedin ) : ?>
                                <a href="<?php echo esc_url( $linkedin ); ?>" 
                                   target="_blank" 
                                   class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition duration-300">
                                    <i class="fab fa-linkedin-in"></i>
                                    <?php esc_html_e( 'LinkedIn', 'tznew' ); ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ( $instagram ) : ?>
                                <a href="<?php echo esc_url( $instagram ); ?>" 
                                   target="_blank" 
                                   class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition duration-300">
                                    <i class="fab fa-instagram"></i>
                                    <?php esc_html_e( 'Instagram', 'tznew' ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php if ( have_posts() ) : ?>
        <!-- Author Stats Section -->
        <section class="author-stats bg-gray-50 py-8">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <?php
                        // Get post counts by type
                        $blog_count = count_user_posts( $author->ID, 'blog' );
                        $trekking_count = count_user_posts( $author->ID, 'trekking' );
                        $tours_count = count_user_posts( $author->ID, 'tours' );
                        $total_count = $blog_count + $trekking_count + $tours_count;
                        ?>
                        
                        <div class="stat-card bg-white rounded-lg p-4 text-center shadow-sm">
                            <div class="text-2xl font-bold text-indigo-600 mb-1"><?php echo $total_count; ?></div>
                            <div class="text-sm text-gray-600"><?php esc_html_e( 'Total Posts', 'tznew' ); ?></div>
                        </div>
                        
                        <div class="stat-card bg-white rounded-lg p-4 text-center shadow-sm">
                            <div class="text-2xl font-bold text-blue-600 mb-1"><?php echo $blog_count; ?></div>
                            <div class="text-sm text-gray-600"><?php esc_html_e( 'Blog Posts', 'tznew' ); ?></div>
                        </div>
                        
                        <div class="stat-card bg-white rounded-lg p-4 text-center shadow-sm">
                            <div class="text-2xl font-bold text-green-600 mb-1"><?php echo $trekking_count; ?></div>
                            <div class="text-sm text-gray-600"><?php esc_html_e( 'Trekking', 'tznew' ); ?></div>
                        </div>
                        
                        <div class="stat-card bg-white rounded-lg p-4 text-center shadow-sm">
                            <div class="text-2xl font-bold text-orange-600 mb-1"><?php echo $tours_count; ?></div>
                            <div class="text-sm text-gray-600"><?php esc_html_e( 'Tours', 'tznew' ); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Author Posts Section -->
        <section class="author-posts py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <h2 class="text-2xl font-bold mb-8 text-center">
                        <?php printf( esc_html__( 'Posts by %s', 'tznew' ), $author->display_name ); ?>
                    </h2>
                    
                    <!-- Filter Options -->
                    <div class="mb-8">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold mb-4"><?php esc_html_e( 'Filter Posts', 'tznew' ); ?></h3>
                            <div class="flex flex-wrap gap-2 lg:gap-4 justify-center lg:justify-start">
                                <button class="filter-btn active" data-filter="all">
                                    <?php esc_html_e( 'All Posts', 'tznew' ); ?>
                                    <span class="ml-1 text-xs">(<?php echo $total_count; ?>)</span>
                                </button>
                                <?php if ( $blog_count > 0 ) : ?>
                                    <button class="filter-btn" data-filter="blog">
                                        <?php esc_html_e( 'Blogs', 'tznew' ); ?>
                                        <span class="ml-1 text-xs">(<?php echo $blog_count; ?>)</span>
                                    </button>
                                <?php endif; ?>
                                <?php if ( $trekking_count > 0 ) : ?>
                                    <button class="filter-btn" data-filter="trekking">
                                        <?php esc_html_e( 'Trekking', 'tznew' ); ?>
                                        <span class="ml-1 text-xs">(<?php echo $trekking_count; ?>)</span>
                                    </button>
                                <?php endif; ?>
                                <?php if ( $tours_count > 0 ) : ?>
                                    <button class="filter-btn" data-filter="tours">
                                        <?php esc_html_e( 'Tours', 'tznew' ); ?>
                                        <span class="ml-1 text-xs">(<?php echo $tours_count; ?>)</span>
                                    </button>
                                <?php endif; ?>
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
                        <i class="fas fa-user-edit text-6xl text-gray-300 mb-4"></i>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">
                            <?php printf( esc_html__( '%s hasn\'t published any posts yet', 'tznew' ), $author->display_name ); ?>
                        </h2>
                        <p class="text-gray-600 mb-6">
                            <?php esc_html_e( 'This author hasn\'t published any content yet. Check back later or explore other authors.', 'tznew' ); ?>
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" 
                           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg transition duration-300">
                            <i class="fas fa-home"></i>
                            <?php esc_html_e( 'Back to Home', 'tznew' ); ?>
                        </a>
                        
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'blog' ) ); ?>" 
                           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-300">
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
.filter-btn {
    @apply px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition duration-300 cursor-pointer;
}

.filter-btn.active {
    @apply bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700;
}

.filter-btn:hover {
    @apply shadow-md;
}

.stat-card {
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const postsGrid = document.querySelector('.posts-grid .grid');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const filterType = this.dataset.filter;
            
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