<?php
/**
 * The template for displaying Difficulty taxonomy archives
 *
 * @package TZnew
 */

get_header();

// Get current term
$current_term = get_queried_object();
?>

<main id="primary" class="site-main">
    <!-- Difficulty Hero Section -->
    <section class="difficulty-hero bg-gradient-to-br from-red-600 via-orange-600 to-yellow-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="mb-6">
                    <?php
                    // Display difficulty level icons
                    $difficulty_name = strtolower($current_term->name);
                    $difficulty_icons = '';
                    if (strpos($difficulty_name, 'easy') !== false) {
                        $difficulty_icons = str_repeat('<i class="fas fa-star text-yellow-300"></i>', 1) . str_repeat('<i class="far fa-star text-white/50"></i>', 4);
                    } elseif (strpos($difficulty_name, 'moderate') !== false) {
                        $difficulty_icons = str_repeat('<i class="fas fa-star text-yellow-300"></i>', 3) . str_repeat('<i class="far fa-star text-white/50"></i>', 2);
                    } elseif (strpos($difficulty_name, 'challenging') !== false || strpos($difficulty_name, 'hard') !== false) {
                        $difficulty_icons = str_repeat('<i class="fas fa-star text-yellow-300"></i>', 4) . str_repeat('<i class="far fa-star text-white/50"></i>', 1);
                    } elseif (strpos($difficulty_name, 'extreme') !== false) {
                        $difficulty_icons = str_repeat('<i class="fas fa-star text-yellow-300"></i>', 5);
                    } else {
                        $difficulty_icons = str_repeat('<i class="fas fa-star text-yellow-300"></i>', 3) . str_repeat('<i class="far fa-star text-white/50"></i>', 2);
                    }
                    echo $difficulty_icons;
                    ?>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    <?php echo esc_html( $current_term->name ); ?> <?php esc_html_e( 'Difficulty', 'tznew' ); ?>
                </h1>
                
                <?php if ( $current_term->description ) : ?>
                    <div class="text-xl text-white/90 mb-8 max-w-3xl mx-auto">
                        <?php echo wp_kses_post( $current_term->description ); ?>
                    </div>
                <?php endif; ?>
                
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                        <i class="fas fa-mountain mr-2"></i>
                        <?php esc_html_e( 'Difficulty Level', 'tznew' ); ?>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                        <i class="fas fa-hiking mr-2"></i>
                        <?php
                        global $wp_query;
                        echo esc_html( $wp_query->found_posts );
                        ?> <?php esc_html_e( 'treks available', 'tznew' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-12">
        <?php if ( have_posts() ) : ?>
            <!-- Difficulty Info -->
            <div class="difficulty-info mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl text-orange-500 mb-2">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3 class="font-semibold mb-2"><?php esc_html_e( 'Duration', 'tznew' ); ?></h3>
                            <p class="text-gray-600 text-sm">
                                <?php
                                if (strpos($difficulty_name, 'easy') !== false) {
                                    esc_html_e( '3-7 days typical', 'tznew' );
                                } elseif (strpos($difficulty_name, 'moderate') !== false) {
                                    esc_html_e( '7-14 days typical', 'tznew' );
                                } else {
                                    esc_html_e( '14+ days typical', 'tznew' );
                                }
                                ?>
                            </p>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl text-orange-500 mb-2">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <h3 class="font-semibold mb-2"><?php esc_html_e( 'Fitness Level', 'tznew' ); ?></h3>
                            <p class="text-gray-600 text-sm">
                                <?php
                                if (strpos($difficulty_name, 'easy') !== false) {
                                    esc_html_e( 'Basic fitness required', 'tznew' );
                                } elseif (strpos($difficulty_name, 'moderate') !== false) {
                                    esc_html_e( 'Good fitness required', 'tznew' );
                                } else {
                                    esc_html_e( 'Excellent fitness required', 'tznew' );
                                }
                                ?>
                            </p>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl text-orange-500 mb-2">
                                <i class="fas fa-mountain"></i>
                            </div>
                            <h3 class="font-semibold mb-2"><?php esc_html_e( 'Experience', 'tznew' ); ?></h3>
                            <p class="text-gray-600 text-sm">
                                <?php
                                if (strpos($difficulty_name, 'easy') !== false) {
                                    esc_html_e( 'Beginner friendly', 'tznew' );
                                } elseif (strpos($difficulty_name, 'moderate') !== false) {
                                    esc_html_e( 'Some experience helpful', 'tznew' );
                                } else {
                                    esc_html_e( 'Experienced trekkers', 'tznew' );
                                }
                                ?>
                            </p>
                        </div>
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
                    <i class="fas fa-mountain text-6xl text-gray-300 mb-6"></i>
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4">
                        <?php esc_html_e( 'No treks found', 'tznew' ); ?>
                    </h2>
                    <p class="text-gray-600 mb-6">
                        <?php esc_html_e( 'There are currently no treks available for this difficulty level.', 'tznew' ); ?>
                    </p>
                    <a href="<?php echo esc_url( home_url( '/trekking' ) ); ?>" class="btn btn-primary">
                        <?php esc_html_e( 'View All Treks', 'tznew' ); ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();
?>