<?php
/**
 * The template for displaying Tour Type taxonomy archives
 *
 * @package TZnew
 */

get_header();

// Get current term
$current_term = get_queried_object();
?>

<main id="primary" class="site-main">
    <!-- Tour Type Hero Section -->
    <section class="tour-type-hero bg-gradient-to-br from-purple-600 via-pink-600 to-red-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="mb-6">
                    <?php
                    // Display tour type icon based on name
                    $tour_type_name = strtolower($current_term->name);
                    $icon = 'fas fa-map-marked-alt'; // default icon
                    
                    if (strpos($tour_type_name, 'cultural') !== false) {
                        $icon = 'fas fa-landmark';
                    } elseif (strpos($tour_type_name, 'adventure') !== false) {
                        $icon = 'fas fa-mountain';
                    } elseif (strpos($tour_type_name, 'wildlife') !== false || strpos($tour_type_name, 'safari') !== false) {
                        $icon = 'fas fa-paw';
                    } elseif (strpos($tour_type_name, 'city') !== false || strpos($tour_type_name, 'urban') !== false) {
                        $icon = 'fas fa-city';
                    } elseif (strpos($tour_type_name, 'spiritual') !== false || strpos($tour_type_name, 'pilgrimage') !== false) {
                        $icon = 'fas fa-pray';
                    } elseif (strpos($tour_type_name, 'luxury') !== false) {
                        $icon = 'fas fa-gem';
                    } elseif (strpos($tour_type_name, 'budget') !== false) {
                        $icon = 'fas fa-coins';
                    }
                    ?>
                    <i class="<?php echo esc_attr($icon); ?> text-6xl text-yellow-300"></i>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    <?php echo esc_html( $current_term->name ); ?> <?php esc_html_e( 'Tours', 'tznew' ); ?>
                </h1>
                
                <?php if ( $current_term->description ) : ?>
                    <div class="text-xl text-white/90 mb-8 max-w-3xl mx-auto">
                        <?php echo wp_kses_post( $current_term->description ); ?>
                    </div>
                <?php endif; ?>
                
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                        <i class="fas fa-route mr-2"></i>
                        <?php esc_html_e( 'Tour Type', 'tznew' ); ?>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                        <i class="fas fa-list mr-2"></i>
                        <?php
                        global $wp_query;
                        echo esc_html( $wp_query->found_posts );
                        ?> <?php esc_html_e( 'tours available', 'tznew' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-12">
        <?php if ( have_posts() ) : ?>
            <!-- Tour Type Features -->
            <div class="tour-features mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold mb-4 text-center"><?php esc_html_e( 'What to Expect', 'tznew' ); ?></h3>
                    <div class="grid md:grid-cols-4 gap-4">
                        <?php
                        // Define features based on tour type
                        $features = [];
                        if (strpos($tour_type_name, 'cultural') !== false) {
                            $features = [
                                ['icon' => 'fas fa-users', 'title' => 'Local Communities', 'desc' => 'Meet local people'],
                                ['icon' => 'fas fa-camera', 'title' => 'Photo Opportunities', 'desc' => 'Capture traditions'],
                                ['icon' => 'fas fa-utensils', 'title' => 'Local Cuisine', 'desc' => 'Taste authentic food'],
                                ['icon' => 'fas fa-book', 'title' => 'Rich History', 'desc' => 'Learn local stories']
                            ];
                        } elseif (strpos($tour_type_name, 'adventure') !== false) {
                            $features = [
                                ['icon' => 'fas fa-hiking', 'title' => 'Active Experience', 'desc' => 'Physical activities'],
                                ['icon' => 'fas fa-heart', 'title' => 'Adrenaline Rush', 'desc' => 'Exciting moments'],
                                ['icon' => 'fas fa-mountain', 'title' => 'Natural Beauty', 'desc' => 'Stunning landscapes'],
                                ['icon' => 'fas fa-medal', 'title' => 'Achievement', 'desc' => 'Personal challenges']
                            ];
                        } elseif (strpos($tour_type_name, 'wildlife') !== false) {
                            $features = [
                                ['icon' => 'fas fa-binoculars', 'title' => 'Wildlife Viewing', 'desc' => 'Spot rare animals'],
                                ['icon' => 'fas fa-leaf', 'title' => 'Nature Conservation', 'desc' => 'Support wildlife'],
                                ['icon' => 'fas fa-camera-retro', 'title' => 'Photography', 'desc' => 'Capture wildlife'],
                                ['icon' => 'fas fa-tree', 'title' => 'Natural Habitat', 'desc' => 'Pristine environments']
                            ];
                        } else {
                            $features = [
                                ['icon' => 'fas fa-star', 'title' => 'Quality Experience', 'desc' => 'Premium service'],
                                ['icon' => 'fas fa-map', 'title' => 'Expert Guides', 'desc' => 'Knowledgeable locals'],
                                ['icon' => 'fas fa-shield-alt', 'title' => 'Safe Travel', 'desc' => 'Secure journeys'],
                                ['icon' => 'fas fa-clock', 'title' => 'Flexible Timing', 'desc' => 'Customizable tours']
                            ];
                        }
                        
                        foreach ($features as $feature) :
                        ?>
                            <div class="text-center">
                                <div class="text-2xl text-purple-600 mb-2">
                                    <i class="<?php echo esc_attr($feature['icon']); ?>"></i>
                                </div>
                                <h4 class="font-semibold mb-1"><?php echo esc_html($feature['title']); ?></h4>
                                <p class="text-gray-600 text-sm"><?php echo esc_html($feature['desc']); ?></p>
                            </div>
                        <?php endforeach; ?>
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
                    <i class="fas fa-route text-6xl text-gray-300 mb-6"></i>
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4">
                        <?php esc_html_e( 'No tours found', 'tznew' ); ?>
                    </h2>
                    <p class="text-gray-600 mb-6">
                        <?php esc_html_e( 'There are currently no tours available for this type.', 'tznew' ); ?>
                    </p>
                    <a href="<?php echo esc_url( home_url( '/tours' ) ); ?>" class="btn btn-primary">
                        <?php esc_html_e( 'View All Tours', 'tznew' ); ?>
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