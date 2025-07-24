<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package TZnew
 */

get_header();
?>

<?php
// Check if Elementor Theme Builder 404 template exists
if ( function_exists( 'tznew_elementor_location_exists' ) && tznew_elementor_location_exists( '404' ) ) {
    // Use Elementor Theme Builder 404 template
    tznew_elementor_do_location( '404' );
} else {
    // Fallback to default 404 template
    ?>
    <main id="primary" class="site-main min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-green-50 relative overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-20 w-32 h-32 bg-blue-500 rounded-full animate-float"></div>
            <div class="absolute bottom-20 right-20 w-24 h-24 bg-green-500 rounded-full animate-float delay-1000"></div>
            <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-yellow-500 rounded-full animate-float delay-500"></div>
        </div>

        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="max-w-2xl mx-auto animate-fade-in-up">
                <!-- 404 Illustration -->
                <div class="mb-8 animate-bounce">
                    <svg class="w-64 h-64 mx-auto text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.291-1.007-5.824-2.562M15 17h5l-1.405-1.405A2.032 2.032 0 0118.158 15.2 2.032 2.032 0 0117.8 14.842L15 17zm-6 0H4l1.405-1.405A2.032 2.032 0 015.842 15.2a2.032 2.032 0 01.358-.358L9 17z"></path>
                    </svg>
                </div>

                <!-- Error Message -->
                <div class="mb-8 animate-fade-in-up delay-200">
                    <h1 class="text-6xl md:text-8xl font-bold text-gray-800 mb-4">
                        <span class="bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">404</span>
                    </h1>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-700 mb-4">
                        <?php esc_html_e('Oops! Adventure Not Found', 'tznew'); ?>
                    </h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        <?php esc_html_e('It looks like the adventure you\'re looking for has wandered off the beaten path. Don\'t worry, even the best explorers sometimes take a wrong turn!', 'tznew'); ?>
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12 animate-fade-in-up delay-400">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-8 rounded-full text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                        <i class="fas fa-home mr-2"></i>
                        <?php esc_html_e('Return Home', 'tznew'); ?>
                    </a>
                    
                    <a href="<?php echo esc_url(get_post_type_archive_link('trekking')); ?>" class="btn btn-outline border-2 border-green-600 text-green-600 hover:bg-green-600 hover:text-white font-bold py-4 px-8 rounded-full text-lg transition-all duration-300">
                        <i class="fas fa-mountain mr-2"></i>
                        <?php esc_html_e('Explore Treks', 'tznew'); ?>
                    </a>
                </div>

                <!-- Search Form -->
                <div class="max-w-md mx-auto animate-fade-in-up delay-600">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">
                        <?php esc_html_e('Search for Your Adventure', 'tznew'); ?>
                    </h3>
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="relative">
                        <input type="search" name="s" placeholder="<?php esc_attr_e('Search adventures...', 'tznew'); ?>" class="search-input w-full py-4 px-6 pr-16 rounded-full bg-white border-2 border-gray-200 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/30 transition-all duration-300 shadow-lg" />
                        <button type="submit" class="search-submit absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full transition-all duration-300 hover:scale-110 shadow-lg">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Popular Content Section -->
            <?php
            $popular_posts = new WP_Query(array(
                'post_type' => array('post', 'trekking'),
                'posts_per_page' => 3,
                'meta_key' => 'post_views_count',
                'orderby' => 'meta_value_num',
                'order' => 'DESC'
            ));
            
            if ($popular_posts->have_posts()) : ?>
                <div class="mt-16 animate-fade-in-up delay-800">
                    <h3 class="text-2xl font-bold text-gray-800 mb-8 text-center">
                        <?php esc_html_e('Popular Adventures', 'tznew'); ?>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                        <?php while ($popular_posts->have_posts()) : $popular_posts->the_post(); ?>
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="relative overflow-hidden">
                                        <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110')); ?>
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    </div>
                                <?php endif; ?>
                                <div class="p-6">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-2 group-hover:text-blue-600 transition-colors duration-300">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                    <p class="text-gray-600 text-sm"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                                    <div class="mt-4">
                                        <a href="<?php the_permalink(); ?>" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors duration-300">
                                            <?php esc_html_e('Read More', 'tznew'); ?> <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endif;
            wp_reset_postdata(); ?>

            <!-- Help Section -->
            <div class="mt-16 bg-white/80 backdrop-blur-sm rounded-2xl p-8 max-w-2xl mx-auto animate-fade-in-up delay-1000">
                <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">
                    <i class="fas fa-question-circle text-blue-600 mr-2"></i>
                    <?php esc_html_e('Need Help?', 'tznew'); ?>
                </h3>
                <p class="text-gray-600 text-center mb-6">
                    <?php esc_html_e('Our adventure experts are here to help you find the perfect trek or tour.', 'tznew'); ?>
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 text-center">
                        <i class="fas fa-envelope mr-2"></i>
                        <?php esc_html_e('Contact Us', 'tznew'); ?>
                    </a>
                    <a href="tel:+977-1-234567" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 text-center">
                        <i class="fas fa-phone mr-2"></i>
                        <?php esc_html_e('Call Now', 'tznew'); ?>
                    </a>
                </div>
            </div>
        </div>
    </main><!-- #main -->
    <?php
}
?>

<?php
get_footer();