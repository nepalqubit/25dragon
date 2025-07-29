<?php
/**
 * The template for displaying date archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php if ( have_posts() ) : ?>
        <!-- Date Archive Hero Section -->
        <section class="date-hero bg-gradient-to-r from-teal-600 to-cyan-600 text-white py-16">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="mb-4">
                        <span class="inline-block bg-white/20 text-white px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            <?php esc_html_e( 'Archive', 'tznew' ); ?>
                        </span>
                    </div>
                    
                    <h1 class="text-3xl lg:text-5xl font-bold mb-4">
                        <?php
                        if ( is_year() ) {
                            printf( esc_html__( 'Posts from %s', 'tznew' ), get_the_date( 'Y' ) );
                        } elseif ( is_month() ) {
                            printf( esc_html__( 'Posts from %s', 'tznew' ), get_the_date( 'F Y' ) );
                        } elseif ( is_day() ) {
                            printf( esc_html__( 'Posts from %s', 'tznew' ), get_the_date( 'F j, Y' ) );
                        } else {
                            esc_html_e( 'Date Archives', 'tznew' );
                        }
                        ?>
                    </h1>
                    
                    <div class="text-xl text-white/90 mb-6">
                        <?php
                        if ( is_year() ) {
                            esc_html_e( 'Browse all posts published in this year', 'tznew' );
                        } elseif ( is_month() ) {
                            esc_html_e( 'Browse all posts published in this month', 'tznew' );
                        } elseif ( is_day() ) {
                            esc_html_e( 'Browse all posts published on this day', 'tznew' );
                        } else {
                            esc_html_e( 'Browse posts by publication date', 'tznew' );
                        }
                        ?>
                    </div>
                    
                    <div class="flex flex-wrap items-center justify-center gap-4 text-sm text-white/80">
                        <span>
                            <i class="fas fa-file-alt mr-1"></i>
                            <?php
                            global $wp_query;
                            $total_posts = $wp_query->found_posts;
                            printf(
                                _n( '%d post found', '%d posts found', $total_posts, 'tznew' ),
                                $total_posts
                            );
                            ?>
                        </span>
                        
                        <span>
                            <i class="fas fa-clock mr-1"></i>
                            <?php esc_html_e( 'Chronological order', 'tznew' ); ?>
                        </span>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Date Navigation Section -->
        <section class="date-navigation bg-gray-50 py-8">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <h3 class="text-lg font-semibold mb-4 text-center"><?php esc_html_e( 'Browse by Date', 'tznew' ); ?></h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Previous Period -->
                        <div class="text-center">
                            <?php
                            $prev_link = '';
                            $prev_text = '';
                            
                            if ( is_year() ) {
                                $prev_year = get_the_date( 'Y' ) - 1;
                                $prev_link = get_year_link( $prev_year );
                                $prev_text = $prev_year;
                            } elseif ( is_month() ) {
                                $prev_month = date( 'n', strtotime( '-1 month', strtotime( get_the_date( 'Y-m-01' ) ) ) );
                                $prev_year = date( 'Y', strtotime( '-1 month', strtotime( get_the_date( 'Y-m-01' ) ) ) );
                                $prev_link = get_month_link( $prev_year, $prev_month );
                                $prev_text = date( 'F Y', strtotime( $prev_year . '-' . $prev_month . '-01' ) );
                            } elseif ( is_day() ) {
                                $prev_day = date( 'j', strtotime( '-1 day', strtotime( get_the_date( 'Y-m-d' ) ) ) );
                                $prev_month = date( 'n', strtotime( '-1 day', strtotime( get_the_date( 'Y-m-d' ) ) ) );
                                $prev_year = date( 'Y', strtotime( '-1 day', strtotime( get_the_date( 'Y-m-d' ) ) ) );
                                $prev_link = get_day_link( $prev_year, $prev_month, $prev_day );
                                $prev_text = date( 'F j, Y', strtotime( $prev_year . '-' . $prev_month . '-' . $prev_day ) );
                            }
                            
                            if ( $prev_link ) :
                            ?>
                                <a href="<?php echo esc_url( $prev_link ); ?>" 
                                   class="inline-flex items-center gap-2 bg-white hover:bg-teal-50 text-teal-600 hover:text-teal-700 px-4 py-2 rounded-lg border border-teal-200 hover:border-teal-300 transition duration-300">
                                    <i class="fas fa-chevron-left"></i>
                                    <?php echo esc_html( $prev_text ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Current Period -->
                        <div class="text-center">
                            <span class="inline-block bg-teal-600 text-white px-4 py-2 rounded-lg font-medium">
                                <?php
                                if ( is_year() ) {
                                    echo get_the_date( 'Y' );
                                } elseif ( is_month() ) {
                                    echo get_the_date( 'F Y' );
                                } elseif ( is_day() ) {
                                    echo get_the_date( 'F j, Y' );
                                }
                                ?>
                            </span>
                        </div>
                        
                        <!-- Next Period -->
                        <div class="text-center">
                            <?php
                            $next_link = '';
                            $next_text = '';
                            
                            if ( is_year() ) {
                                $next_year = get_the_date( 'Y' ) + 1;
                                if ( $next_year <= date( 'Y' ) ) {
                                    $next_link = get_year_link( $next_year );
                                    $next_text = $next_year;
                                }
                            } elseif ( is_month() ) {
                                $next_month = date( 'n', strtotime( '+1 month', strtotime( get_the_date( 'Y-m-01' ) ) ) );
                                $next_year = date( 'Y', strtotime( '+1 month', strtotime( get_the_date( 'Y-m-01' ) ) ) );
                                if ( strtotime( $next_year . '-' . $next_month . '-01' ) <= time() ) {
                                    $next_link = get_month_link( $next_year, $next_month );
                                    $next_text = date( 'F Y', strtotime( $next_year . '-' . $next_month . '-01' ) );
                                }
                            } elseif ( is_day() ) {
                                $next_day = date( 'j', strtotime( '+1 day', strtotime( get_the_date( 'Y-m-d' ) ) ) );
                                $next_month = date( 'n', strtotime( '+1 day', strtotime( get_the_date( 'Y-m-d' ) ) ) );
                                $next_year = date( 'Y', strtotime( '+1 day', strtotime( get_the_date( 'Y-m-d' ) ) ) );
                                if ( strtotime( $next_year . '-' . $next_month . '-' . $next_day ) <= time() ) {
                                    $next_link = get_day_link( $next_year, $next_month, $next_day );
                                    $next_text = date( 'F j, Y', strtotime( $next_year . '-' . $next_month . '-' . $next_day ) );
                                }
                            }
                            
                            if ( $next_link ) :
                            ?>
                                <a href="<?php echo esc_url( $next_link ); ?>" 
                                   class="inline-flex items-center gap-2 bg-white hover:bg-teal-50 text-teal-600 hover:text-teal-700 px-4 py-2 rounded-lg border border-teal-200 hover:border-teal-300 transition duration-300">
                                    <?php echo esc_html( $next_text ); ?>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Posts Grid Section -->
        <section class="date-posts py-12">
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
                                        <button class="sort-btn active" data-sort="date-desc">
                                            <i class="fas fa-sort-amount-down mr-1"></i>
                                            <?php esc_html_e( 'Newest First', 'tznew' ); ?>
                                        </button>
                                        <button class="sort-btn" data-sort="date-asc">
                                            <i class="fas fa-sort-amount-up mr-1"></i>
                                            <?php esc_html_e( 'Oldest First', 'tznew' ); ?>
                                        </button>
                                        <button class="sort-btn" data-sort="title">
                                            <i class="fas fa-sort-alpha-down mr-1"></i>
                                            <?php esc_html_e( 'A-Z', 'tznew' ); ?>
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
                        <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">
                            <?php esc_html_e( 'No posts found for this date', 'tznew' ); ?>
                        </h2>
                        <p class="text-gray-600 mb-6">
                            <?php esc_html_e( 'It looks like there are no posts published on this date. Try browsing other dates or explore our latest content.', 'tznew' ); ?>
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" 
                           class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg transition duration-300">
                            <i class="fas fa-home"></i>
                            <?php esc_html_e( 'Back to Home', 'tznew' ); ?>
                        </a>
                        
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'blog' ) ); ?>" 
                           class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-3 rounded-lg transition duration-300">
                            <i class="fas fa-blog"></i>
                            <?php esc_html_e( 'View All Blogs', 'tznew' ); ?>
                        </a>
                        
                        <a href="<?php echo esc_url( get_year_link( date( 'Y' ) ) ); ?>" 
                           class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg transition duration-300">
                            <i class="fas fa-calendar-alt"></i>
                            <?php printf( esc_html__( 'Browse %s', 'tznew' ), date( 'Y' ) ); ?>
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
    @apply bg-teal-600 text-white border-teal-600 hover:bg-teal-700;
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