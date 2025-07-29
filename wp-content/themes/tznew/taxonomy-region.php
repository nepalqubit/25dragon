<?php
/**
 * The template for displaying Region taxonomy archives
 *
 * @package TZnew
 */

get_header();

// Get current term
$current_term = get_queried_object();
?>

<main id="primary" class="site-main">
    <!-- Region Hero Section -->
    <section class="region-hero bg-gradient-to-br from-green-600 via-teal-600 to-blue-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    <?php echo esc_html( $current_term->name ); ?>
                </h1>
                
                <?php if ( $current_term->description ) : ?>
                    <div class="text-xl text-white/90 mb-8 max-w-3xl mx-auto">
                        <?php echo wp_kses_post( $current_term->description ); ?>
                    </div>
                <?php endif; ?>
                
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <?php esc_html_e( 'Region', 'tznew' ); ?>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                        <i class="fas fa-list mr-2"></i>
                        <?php
                        global $wp_query;
                        echo esc_html( $wp_query->found_posts );
                        ?> <?php esc_html_e( 'items available', 'tznew' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-12">
        <?php if ( have_posts() ) : ?>
            <!-- Filter Tabs -->
            <div class="filter-tabs mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex flex-wrap gap-4 justify-center">
                        <button class="filter-btn active" data-filter="all">
                            <?php esc_html_e( 'All', 'tznew' ); ?>
                            <span class="count"><?php echo esc_html( $wp_query->found_posts ); ?></span>
                        </button>
                        <?php
                        // Count posts by type
                        $post_types = [];
                        while ( have_posts() ) {
                            the_post();
                            $type = get_post_type();
                            $post_types[$type] = isset($post_types[$type]) ? $post_types[$type] + 1 : 1;
                        }
                        rewind_posts();
                        
                        foreach ( $post_types as $type => $count ) {
                            $type_obj = get_post_type_object( $type );
                            if ( $type_obj ) :
                        ?>
                            <button class="filter-btn" data-filter="<?php echo esc_attr( $type ); ?>">
                                <?php echo esc_html( $type_obj->labels->name ); ?>
                                <span class="count"><?php echo esc_html( $count ); ?></span>
                            </button>
                        <?php
                            endif;
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Results Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content', 'grid' );
                endwhile;
                ?>
            </div>

            <?php
            // Pagination
            tznew_pagination();
            ?>

        <?php else : ?>
            <div class="no-results text-center py-12">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-search text-6xl text-gray-300 mb-6"></i>
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4">
                        <?php esc_html_e( 'No items found', 'tznew' ); ?>
                    </h2>
                    <p class="text-gray-600 mb-6">
                        <?php esc_html_e( 'There are currently no items available in this region.', 'tznew' ); ?>
                    </p>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                        <?php esc_html_e( 'Back to Home', 'tznew' ); ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
.filter-btn {
    @apply inline-flex items-center gap-2 px-4 py-2 rounded-full border border-gray-300 bg-white text-gray-700 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 transition-all duration-300;
}

.filter-btn.active {
    @apply bg-blue-600 text-white border-blue-600 hover:bg-blue-700;
}

.filter-btn .count {
    @apply bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full;
}

.filter-btn.active .count {
    @apply bg-white/20 text-white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const gridItems = document.querySelectorAll('.grid > *');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Filter items
            gridItems.forEach(item => {
                if (filter === 'all' || item.classList.contains(filter)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php
get_sidebar();
get_footer();
?>