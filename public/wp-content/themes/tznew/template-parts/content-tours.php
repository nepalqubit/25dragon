<?php
/**
 * Template part for displaying tours posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 * @version 2.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg'); ?>>
    <?php if (has_post_thumbnail() || tznew_get_field_safe('featured_image')) : ?>
        <div class="post-thumbnail relative overflow-hidden">
            <?php tznew_post_thumbnail('medium_large', 'w-full h-64 object-cover transition-transform duration-300 hover:scale-105'); ?>
            <?php if (is_singular()) : ?>
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="entry-content p-6">
        <?php if (is_singular()) : ?>
            <?php the_title('<h1 class="entry-title text-3xl font-bold mb-6 text-blue-800">', '</h1>'); ?>
            
            <?php
            // Display meta information for singular view
            $duration = tznew_get_field_safe('duration');
            $tour_type = tznew_get_field_safe('tour_type');
            $max_group_size = tznew_get_field_safe('max_group_size');
            $best_season = tznew_get_field_safe('best_season');
            $difficulty = tznew_get_field_safe('difficulty');
            
            if ($duration || $tour_type || $max_group_size || $best_season || $difficulty) :
            ?>
                <div class="tour-meta grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8 p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-100">
                    <?php if ($duration) : ?>
                        <div class="tour-meta-item flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="text-green-600 text-2xl mb-2">
                                <i class="fas fa-clock" aria-hidden="true"></i>
                            </div>
                            <span class="block text-sm text-gray-600 mb-1 font-medium"><?php esc_html_e('Duration', 'tznew'); ?></span>
                            <span class="font-bold text-lg text-gray-800"><?php echo esc_html($duration); ?> <?php echo esc_html(_n('Day', 'Days', intval($duration), 'tznew')); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($tour_type) : ?>
                        <div class="tour-meta-item flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="text-blue-600 text-2xl mb-2">
                                <i class="fas fa-map" aria-hidden="true"></i>
                            </div>
                            <span class="block text-sm text-gray-600 mb-1 font-medium"><?php esc_html_e('Tour Type', 'tznew'); ?></span>
                            <span class="font-bold text-lg text-gray-800"><?php echo esc_html(ucfirst($tour_type)); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($max_group_size) : ?>
                        <div class="tour-meta-item flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="text-purple-600 text-2xl mb-2">
                                <i class="fas fa-users" aria-hidden="true"></i>
                            </div>
                            <span class="block text-sm text-gray-600 mb-1 font-medium"><?php esc_html_e('Group Size', 'tznew'); ?></span>
                            <span class="font-bold text-lg text-gray-800"><?php echo esc_html($max_group_size); ?> <?php esc_html_e('People', 'tznew'); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($best_season) : ?>
                        <div class="tour-meta-item flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="text-orange-600 text-2xl mb-2">
                                <i class="fas fa-calendar-days" aria-hidden="true"></i>
                            </div>
                            <span class="block text-sm text-gray-600 mb-1 font-medium"><?php esc_html_e('Best Season', 'tznew'); ?></span>
                            <span class="font-bold text-lg text-gray-800"><?php echo esc_html($best_season); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($difficulty) : ?>
                        <div class="tour-meta-item flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="text-red-600 text-2xl mb-2">
                                <i class="fas fa-chart-line" aria-hidden="true"></i>
                            </div>
                            <span class="block text-sm text-gray-600 mb-1 font-medium"><?php esc_html_e('Difficulty', 'tznew'); ?></span>
                            <span class="font-bold text-lg text-gray-800"><?php echo esc_html(ucfirst($difficulty)); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php
            // Overview section
            $overview = tznew_get_field_safe('overview');
            if ($overview) :
            ?>
                <div class="tour-overview mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <h2 class="text-2xl font-bold mb-4 text-green-700 border-b border-green-200 pb-3 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-green-600" aria-hidden="true"></i>
                        <?php esc_html_e('Overview', 'tznew'); ?>
                    </h2>
                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        <?php echo wp_kses_post($overview); ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php
            // Highlights section
            if (tznew_have_rows_safe('highlights')) :
            ?>
                <div class="tour-highlights mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <h2 class="text-2xl font-bold mb-4 text-green-700 border-b border-green-200 pb-3 flex items-center">
                        <i class="fas fa-star mr-2 text-yellow-500" aria-hidden="true"></i>
                        <?php esc_html_e('Highlights', 'tznew'); ?>
                    </h2>
                    <ul class="space-y-3">
                        <?php while (tznew_have_rows_safe('highlights')) : tznew_the_row_safe(); ?>
                            <?php $highlight = tznew_get_sub_field_safe('highlight'); ?>
                            <?php if ($highlight) : ?>
                                <li class="flex items-start text-gray-700">
                                    <i class="fas fa-circle-check text-green-500 mr-3 mt-1 flex-shrink-0" aria-hidden="true"></i>
                                    <span class="text-lg leading-relaxed"><?php echo wp_kses_post(is_string($highlight) ? $highlight : ''); ?></span>
                                </li>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php
            // Itinerary section with elevation graph
            if (tznew_have_rows_safe('itinerary')) :
                // Collect elevation data for the chart
                $elevation_data = [];
                $day_labels = [];
                $day_count_temp = 1;
                
                while (tznew_have_rows_safe('itinerary')) :
                    tznew_the_row_safe();
                    $altitude = tznew_get_sub_field_safe('altitude');
                    $day_title = tznew_get_sub_field_safe('title');
                    
                    if ($altitude && is_numeric($altitude)) {
                        $elevation_data[] = intval($altitude);
                        $day_labels[] = 'Day ' . $day_count_temp;
                    }
                    $day_count_temp++;
                endwhile;
                
                // Reset the loop for display
                if (function_exists('reset_rows')) {
                    reset_rows();
                }
            ?>
                <div class="tour-itinerary mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <h2 class="text-2xl font-bold mb-6 text-green-700 border-b border-green-200 pb-3 flex items-center">
                        <i class="fas fa-route mr-2 text-green-600" aria-hidden="true"></i>
                        <?php esc_html_e('Itinerary', 'tznew'); ?>
                    </h2>
                    

                    
                    <?php
                    // Collect route coordinates for map
                    $route_coordinates = array();
                    if (tznew_have_rows_safe('itinerary')) {
                        $map_day_count = 1;
                        while (tznew_have_rows_safe('itinerary')) {
                            tznew_the_row_safe();
                            $coordinates = tznew_get_sub_field_safe('coordinates');
                            $day_title = tznew_get_sub_field_safe('title');
                            $place_name = tznew_get_sub_field_safe('place_name');
                            $altitude = tznew_get_sub_field_safe('altitude');
                            $transportation = tznew_get_sub_field_safe('transportation');
                            
                            if ($coordinates && isset($coordinates['latitude']) && isset($coordinates['longitude'])) {
                                $route_coordinates[] = array(
                                    'day' => $map_day_count,
                                    'lat' => floatval($coordinates['latitude']),
                                    'lng' => floatval($coordinates['longitude']),
                                    'title' => $day_title,
                                    'place_name' => $place_name,
                                    'altitude' => $altitude,
                                    'transportation' => $transportation
                                );
                            }
                            $map_day_count++;
                        }
                        
                        // Reset the loop
                        if (function_exists('reset_rows')) {
                            reset_rows();
                        }
                    }
                    ?>

                    
                    <div class="space-y-4">
                        <?php 
                        $day_count = 1;
                        $previous_altitude = null;
                        while (tznew_have_rows_safe('itinerary')) : 
                            tznew_the_row_safe();
                            $day_title = tznew_get_sub_field_safe('title');
                            $day_description = tznew_get_sub_field_safe('description');
                            $place_name = tznew_get_sub_field_safe('place_name');
                            $altitude = tznew_get_sub_field_safe('altitude');
                            $transportation = tznew_get_sub_field_safe('transportation');
                            $elevation_gain = tznew_get_sub_field_safe('elevation_gain');
                            $elevation_loss = tznew_get_sub_field_safe('elevation_loss');
                            $walking_time = tznew_get_sub_field_safe('walking_time');
                            $distance = tznew_get_sub_field_safe('distance');
                            $accommodation = tznew_get_sub_field_safe('accommodation');
                            $meals = tznew_get_sub_field_safe('meals');
                            $activities = tznew_get_sub_field_safe('activities');
                            $coordinates = tznew_get_sub_field_safe('coordinates');
                            $images = tznew_get_sub_field_safe('images');
                            $video_urls = tznew_get_sub_field_safe('video_urls');
                            $recommendation = tznew_get_sub_field_safe('recommendation');
                            
                            // Calculate elevation change based on altitude difference
                            $elevation_change = null;
                            $elevation_change_class = '';
                            $elevation_change_icon = '';
                            if ($altitude && $previous_altitude !== null) {
                                $elevation_change = intval($altitude) - intval($previous_altitude);
                                if ($elevation_change > 0) {
                                    $elevation_change_class = 'text-red-600';
                                    $elevation_change_icon = 'fas fa-arrow-up';
                                } elseif ($elevation_change < 0) {
                                    $elevation_change_class = 'text-green-600';
                                    $elevation_change_icon = 'fas fa-arrow-down';
                                } else {
                                    $elevation_change_class = 'text-gray-600';
                                    $elevation_change_icon = 'fas fa-minus';
                                }
                            }
                        ?>
                            <div class="itinerary-day border border-gray-200 rounded-lg overflow-hidden">
                                <!-- Day Header - Always Visible -->
                                <div class="day-header bg-gradient-to-r from-green-50 to-emerald-50 p-4 cursor-pointer hover:from-green-100 hover:to-emerald-100 transition-all duration-300" 
                                     onclick="toggleDayContent(<?php echo esc_js($day_count); ?>)">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center text-sm font-bold mr-4"><?php echo esc_html($day_count); ?></span>
                                            <div>
                                                <h3 class="text-xl font-bold text-green-800">
                                                    <?php esc_html_e('Day', 'tznew'); ?> <?php echo esc_html($day_count); ?>: <?php echo esc_html($day_title); ?>
                                                </h3>
                                                <?php if ($place_name) : ?>
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        <i class="fas fa-location-dot mr-1" aria-hidden="true"></i>
                                                        <?php echo esc_html($place_name); ?>
                                                        <?php if ($altitude) : ?>
                                                            (<?php echo esc_html($altitude); ?>m)
                                                        <?php endif; ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <?php if ($walking_time || $distance) : ?>
                                                <div class="text-sm text-gray-600">
                                                    <?php if ($walking_time) : ?>
                                                        <span class="inline-flex items-center">
                                                            <i class="fas fa-clock mr-1" aria-hidden="true"></i>
                                                            <?php echo esc_html($walking_time); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if ($distance) : ?>
                                                        <span class="inline-flex items-center ml-2">
                                                            <i class="fas fa-route mr-1" aria-hidden="true"></i>
                                                            <?php echo esc_html($distance); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            <i class="fas fa-chevron-down transform transition-transform duration-300" id="chevron-<?php echo esc_attr($day_count); ?>" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Day Content - Expandable -->
                                <div class="day-content hidden" id="day-content-<?php echo esc_attr($day_count); ?>">
                                    <div class="p-6 bg-white">
                                        <?php if ($day_description) : ?>
                                            <div class="prose max-w-none text-gray-700 mb-6 leading-relaxed">
                                                <?php echo wp_kses_post($day_description); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Elevation, Transportation and Distance Info -->
                                        <?php if ($elevation_change !== null || $transportation || $walking_time || $distance) : ?>
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                                                <?php if ($elevation_change !== null) : ?>
                                                    <div class="bg-gray-50 p-3 rounded-lg text-center border border-gray-100">
                                                        <i class="<?php echo esc_attr($elevation_change_icon); ?> <?php echo esc_attr($elevation_change_class); ?> mb-2" aria-hidden="true"></i>
                                                        <div class="text-sm font-semibold text-gray-700"><?php esc_html_e('Elevation Gain/Loss (m) ±', 'tznew'); ?></div>
                                                        <div class="text-lg font-bold <?php echo esc_attr($elevation_change_class); ?>">
                                                            <?php echo $elevation_change > 0 ? '+' : ''; ?><?php echo esc_html($elevation_change); ?>m
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($transportation) : ?>
                                                    <?php 
                                                    $transport_icons = array(
                                                'walking' => 'fa-walking',
                                                'bus' => 'fa-bus',
                                                'car' => 'fa-car',
                                                'jeep' => 'fa-truck',
                                                'van' => 'fa-shuttle-van',
                                                'motorbike' => 'fa-motorcycle',
                                                'flight' => 'fa-plane',
                                                'helicopter' => 'fa-helicopter',
                                                'rest_day' => 'fa-bed',
                                                'acclimatization' => 'fa-mountain'
                                            );
                                                    $transport_icon = isset($transport_icons[$transportation]) ? $transport_icons[$transportation] : 'fa-route';
                                                    ?>
                                                    <div class="bg-indigo-50 p-3 rounded-lg text-center border border-indigo-100">
                                                        <i class="fas <?php echo esc_attr($transport_icon); ?> text-indigo-600 mb-2" aria-hidden="true"></i>
                                                        <div class="text-sm font-semibold text-indigo-700"><?php esc_html_e('Transportation', 'tznew'); ?></div>
                                                        <div class="text-lg font-bold text-indigo-800"><?php echo esc_html(ucfirst($transportation)); ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($walking_time) : ?>
                                                    <div class="bg-blue-50 p-3 rounded-lg text-center border border-blue-100">
                                                        <i class="fas fa-clock text-blue-600 mb-2" aria-hidden="true"></i>
                                                        <div class="text-sm font-semibold text-blue-700"><?php esc_html_e('Walking Time', 'tznew'); ?></div>
                                                        <div class="text-lg font-bold text-blue-800"><?php echo esc_html($walking_time); ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($distance) : ?>
                                                    <div class="bg-purple-50 p-3 rounded-lg text-center border border-purple-100">
                                                        <i class="fas fa-route text-purple-600 mb-2" aria-hidden="true"></i>
                                                        <div class="text-sm font-semibold text-purple-700"><?php esc_html_e('Distance', 'tznew'); ?></div>
                                                        <div class="text-lg font-bold text-purple-800"><?php echo esc_html($distance); ?></div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Accommodation, Meals, Activities -->
                                        <?php if ($accommodation || $meals || $activities) : ?>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                                <?php if ($accommodation) : ?>
                                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                                        <div class="flex items-center mb-2">
                                                            <i class="fas fa-bed text-green-600 mr-2" aria-hidden="true"></i>
                                                            <span class="font-semibold text-green-700"><?php esc_html_e('Accommodation', 'tznew'); ?></span>
                                                        </div>
                                                        <p class="text-gray-700"><?php echo esc_html($accommodation); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($meals) : ?>
                                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                                        <div class="flex items-center mb-2">
                                                            <i class="fas fa-utensils text-orange-600 mr-2" aria-hidden="true"></i>
                                                            <span class="font-semibold text-orange-700"><?php esc_html_e('Meals', 'tznew'); ?></span>
                                                        </div>
                                                        <p class="text-gray-700"><?php echo esc_html($meals); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($activities) : ?>
                                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                                        <div class="flex items-center mb-2">
                                                            <i class="fas fa-hiking text-blue-600 mr-2" aria-hidden="true"></i>
                                                            <span class="font-semibold text-blue-700"><?php esc_html_e('Activities', 'tznew'); ?></span>
                                                        </div>
                                                        <p class="text-gray-700"><?php echo esc_html($activities); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Coordinates -->
                                        <?php if ($coordinates && (isset($coordinates['latitude']) || isset($coordinates['longitude']))) : ?>
                                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 mb-6">
                                                <div class="flex items-center mb-2">
                                                    <i class="fas fa-location-dot text-blue-600 mr-2" aria-hidden="true"></i>
                                                    <span class="font-semibold text-blue-700"><?php esc_html_e('Coordinates', 'tznew'); ?></span>
                                                </div>
                                                <p class="text-gray-700">
                                                    <?php if (isset($coordinates['latitude'])) : ?>
                                                        <?php esc_html_e('Latitude:', 'tznew'); ?> <?php echo esc_html($coordinates['latitude']); ?>
                                                    <?php endif; ?>
                                                    <?php if (isset($coordinates['longitude'])) : ?>
                                                        <?php if (isset($coordinates['latitude'])) echo ' | '; ?>
                                                        <?php esc_html_e('Longitude:', 'tznew'); ?> <?php echo esc_html($coordinates['longitude']); ?>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Images -->
                                        <?php if ($images && is_array($images) && !empty($images)) : ?>
                                            <div class="mb-6">
                                                <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                                    <i class="fas fa-images text-green-600 mr-2" aria-hidden="true"></i>
                                                    <?php esc_html_e('Day Images', 'tznew'); ?>
                                                </h4>
                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                                    <?php foreach ($images as $image) : ?>
                                                        <?php if (is_array($image) && !empty($image['url'])) : ?>
                                                            <a href="<?php echo esc_url($image['url']); ?>" 
                                                               class="block rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300"
                                                               data-lightbox="day-<?php echo esc_attr($day_count); ?>-gallery">
                                                                <img src="<?php echo esc_url($image['sizes']['medium'] ?? $image['url']); ?>" 
                                                                     alt="<?php echo esc_attr($image['alt'] ?? ''); ?>" 
                                                                     class="w-full h-24 object-cover hover:scale-105 transition-transform duration-300">
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Videos -->
                                        <?php if ($video_urls && is_array($video_urls) && !empty($video_urls)) : ?>
                                            <div class="mb-6">
                                                <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                                    <i class="fas fa-video text-red-600 mr-2" aria-hidden="true"></i>
                                                    <?php esc_html_e('Day Videos', 'tznew'); ?>
                                                </h4>
                                                <div class="space-y-3">
                                                    <?php foreach ($video_urls as $video) : ?>
                                                        <?php if (is_array($video) && !empty($video['url'])) : ?>
                                                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                                                <a href="<?php echo esc_url($video['url']); ?>" 
                                                                   target="_blank" 
                                                                   class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                                                    <i class="fas fa-up-right-from-square mr-2" aria-hidden="true"></i>
                                                                    <?php echo esc_html($video['title'] ?? __('Watch Video', 'tznew')); ?>
                                                                </a>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Recommendation -->
                                        <?php if ($recommendation) : ?>
                                            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                                                <div class="flex items-center mb-2">
                                                    <i class="fas fa-lightbulb text-yellow-600 mr-2" aria-hidden="true"></i>
                                                    <span class="font-semibold text-yellow-700"><?php esc_html_e('Recommendation', 'tznew'); ?></span>
                                                </div>
                                                <div class="text-gray-700"><?php echo wp_kses_post($recommendation); ?></div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php 
                        // Store current altitude for next iteration
                        if ($altitude) {
                            $previous_altitude = intval($altitude);
                        }
                        $day_count++; 
                        endwhile; 
                        ?>
                    </div>
                </div>
                
                <script>
                function toggleDayContent(dayNumber) {
                    const content = document.getElementById('day-content-' + dayNumber);
                    const chevron = document.getElementById('chevron-' + dayNumber);
                    
                    if (content.classList.contains('hidden')) {
                        content.classList.remove('hidden');
                        chevron.classList.add('rotate-180');
                    } else {
                        content.classList.add('hidden');
                        chevron.classList.remove('rotate-180');
                    }
                }
                </script>
            <?php endif; ?>
            
            <?php
            // Includes/Excludes section
            $includes = tznew_get_field_safe('includes');
            $excludes = tznew_get_field_safe('excludes');
            if ($includes || $excludes) :
            ?>
                <div class="tour-includes-excludes mb-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <?php if ($includes) : ?>
                        <div class="tour-includes bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-xl shadow-sm border border-green-100 hover:shadow-md transition-shadow duration-300">
                            <h2 class="text-2xl font-bold mb-4 text-green-700 border-b border-green-200 pb-3 flex items-center">
                                <i class="fas fa-circle-check mr-2 text-green-600" aria-hidden="true"></i>
                                <?php esc_html_e('Includes', 'tznew'); ?>
                            </h2>
                            <div class="list-content text-gray-700 leading-relaxed">
                                <?php echo wp_kses_post($includes); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($excludes) : ?>
                        <div class="tour-excludes bg-gradient-to-br from-red-50 to-pink-50 p-6 rounded-xl shadow-sm border border-red-100 hover:shadow-md transition-shadow duration-300">
                            <h2 class="text-2xl font-bold mb-4 text-red-700 border-b border-red-200 pb-3 flex items-center">
                                <i class="fas fa-circle-xmark mr-2 text-red-600" aria-hidden="true"></i>
                                <?php esc_html_e('Excludes', 'tznew'); ?>
                            </h2>
                            <div class="list-content text-gray-700 leading-relaxed">
                                <?php echo wp_kses_post($excludes); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php
            // Route Map section with automatic location integration
            // Collect route coordinates for map
            $route_coordinates = array();
            if (tznew_have_rows_safe('itinerary')) {
                $map_day_count = 1;
                while (tznew_have_rows_safe('itinerary')) {
                    tznew_the_row_safe();
                    $coordinates = tznew_get_sub_field_safe('coordinates');
                    $day_title = tznew_get_sub_field_safe('title');
                    $place_name = tznew_get_sub_field_safe('place_name');
                    $altitude = tznew_get_sub_field_safe('altitude');
                    $transportation = tznew_get_sub_field_safe('transportation');
                    
                    // Auto-populate coordinates if place name exists but coordinates are missing
                    if ($place_name && (!$coordinates || empty($coordinates['latitude']) || empty($coordinates['longitude']))) {
                        // Store place name for automatic geocoding on frontend
                        $route_coordinates[] = array(
                            'day' => $map_day_count,
                            'lat' => null,
                            'lng' => null,
                            'title' => $day_title,
                            'place_name' => $place_name,
                            'altitude' => $altitude,
                            'transportation' => $transportation,
                            'needs_geocoding' => true
                        );
                    } elseif ($coordinates && isset($coordinates['latitude']) && isset($coordinates['longitude'])) {
                        $route_coordinates[] = array(
                            'day' => $map_day_count,
                            'lat' => floatval($coordinates['latitude']),
                            'lng' => floatval($coordinates['longitude']),
                            'title' => $day_title,
                            'place_name' => $place_name,
                            'altitude' => $altitude,
                            'transportation' => $transportation,
                            'needs_geocoding' => false
                        );
                    }
                    $map_day_count++;
                }
                
                // Reset the loop
                if (function_exists('reset_rows')) {
                    reset_rows();
                }
            }
            
            // Display route map if coordinates are available
            if (!empty($route_coordinates)) :
            ?>
                <div class="tour-route-map mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <h2 class="text-2xl font-bold mb-6 text-green-700 border-b border-green-200 pb-3 flex items-center">
                        <i class="fas fa-map mr-2 text-green-600" aria-hidden="true"></i>
                        <?php esc_html_e('Route Map', 'tznew'); ?>
                    </h2>
                    <div id="tour-route-map" style="height: 500px; border-radius: 8px; overflow: hidden;"></div>
                </div>
                
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Route coordinates with automatic geocoding support
                    const routeCoordinates = <?php echo wp_json_encode($route_coordinates); ?>;
                    
                    // Function to geocode place names automatically
                    async function geocodeLocation(placeName, country = 'Nepal') {
                        try {
                            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(placeName + ', ' + country)}&limit=1`);
                            const data = await response.json();
                            if (data && data.length > 0) {
                                return {
                                    lat: parseFloat(data[0].lat),
                                    lng: parseFloat(data[0].lon)
                                };
                            }
                        } catch (error) {
                            console.warn('Geocoding failed for:', placeName, error);
                        }
                        return null;
                    }
                    
                    // Function to initialize map with automatic location integration
                    async function initializeInteractiveMap() {
                        // Process coordinates and geocode missing ones
                        const processedCoordinates = [];
                        
                        for (const coord of routeCoordinates) {
                            if (coord.needs_geocoding && coord.place_name) {
                                console.log('Auto-geocoding location:', coord.place_name);
                                const geocoded = await geocodeLocation(coord.place_name);
                                if (geocoded) {
                                    processedCoordinates.push({
                                        ...coord,
                                        lat: geocoded.lat,
                                        lng: geocoded.lng,
                                        auto_geocoded: true
                                    });
                                } else {
                                    console.warn('Could not geocode:', coord.place_name);
                                }
                            } else if (coord.lat && coord.lng) {
                                processedCoordinates.push({
                                    ...coord,
                                    auto_geocoded: false
                                });
                            }
                        }
                        
                        if (processedCoordinates.length === 0) {
                            console.warn('No valid coordinates found for map initialization');
                            return;
                        }
                        
                        // Initialize map with first valid coordinate and attribution control disabled
                        const firstCoord = processedCoordinates[0];
                        const map = L.map('tour-route-map', {
                            attributionControl: false
                        }).setView([firstCoord.lat, firstCoord.lng], 10);
                        
                        // Add tile layer
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '',
                            maxZoom: 18
                        }).addTo(map);
                    
                        // Create route segments with processed coordinates
                        const routeSegments = [];
                        for (let i = 0; i < processedCoordinates.length - 1; i++) {
                            const startCoord = [processedCoordinates[i].lat, processedCoordinates[i].lng];
                            const endCoord = [processedCoordinates[i + 1].lat, processedCoordinates[i + 1].lng];
                            
                            const segment = L.polyline([startCoord, endCoord], {
                                color: '#059669',
                                weight: 4,
                                opacity: 0.8
                            }).addTo(map);
                            
                            routeSegments.push(segment);
                            
                            // Add transportation icons between segments
                            if (i < processedCoordinates.length - 1) {
                                const nextDayTransport = processedCoordinates[i + 1].transportation;
                                if (nextDayTransport) {
                                    // Transportation icon mapping
                                    const transportIcons = {
                                        'walking': 'fa-walking',
                                        'bus': 'fa-bus',
                                        'car': 'fa-car',
                                        'jeep': 'fa-truck',
                                        'van': 'fa-shuttle-van',
                                        'motorbike': 'fa-motorcycle',
                                        'flight': 'fa-plane',
                                        'helicopter': 'fa-helicopter',
                                        'rest_day': 'fa-bed',
                                        'acclimatization': 'fa-mountain'
                                    };
                                    
                                    const transportIcon = transportIcons[nextDayTransport] || 'fa-route';
                                    
                                    // Calculate midpoint of the segment
                                    const midLat = (startCoord[0] + endCoord[0]) / 2;
                                    const midLng = (startCoord[1] + endCoord[1]) / 2;
                                    
                                    // Create transportation icon marker
                                    const transportMarker = L.divIcon({
                                        html: `<div style="background-color: #ffffff; border: 2px solid #059669; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"><i class="fas ${transportIcon}" style="color: #059669; font-size: 12px;"></i></div>`,
                                        className: 'transport-icon-marker',
                                        iconSize: [24, 24],
                                        iconAnchor: [12, 12]
                                    });
                                    
                                    L.marker([midLat, midLng], { icon: transportMarker })
                                        .addTo(map)
                                        .bindTooltip(`Transportation: ${nextDayTransport.charAt(0).toUpperCase() + nextDayTransport.slice(1)}`, {
                                            permanent: false,
                                            direction: 'top',
                                            className: 'transport-tooltip'
                                        });
                                }
                            }
                        }
                        
                        // Create a feature group for fitting bounds
                        const allSegments = L.featureGroup(routeSegments);
                        
                        // Add markers for each day with processed coordinates
                        processedCoordinates.forEach((coord, index) => {
                            const isStart = index === 0;
                            const isEnd = index === processedCoordinates.length - 1;
                            
                            let markerColor = '#3B82F6'; // Default blue
                            
                            if (isStart) {
                                markerColor = '#10B981'; // Green for start
                            } else if (isEnd) {
                                markerColor = '#EF4444'; // Red for end
                            }
                            
                            const customIcon = L.divIcon({
                                html: `<div style="background-color: ${markerColor}; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">${coord.day}</div>`,
                                className: 'custom-marker',
                                iconSize: [30, 30],
                                iconAnchor: [15, 15]
                            });
                            
                            // Transportation icon mapping
                            const transportIcons = {
                                'walking': 'fa-walking',
                                'bus': 'fa-bus',
                                'car': 'fa-car',
                                'jeep': 'fa-truck',
                                'van': 'fa-shuttle-van',
                                'motorbike': 'fa-motorcycle',
                                'flight': 'fa-plane',
                                'helicopter': 'fa-helicopter',
                                'rest_day': 'fa-bed',
                                'acclimatization': 'fa-mountain'
                            };
                            
                            const transportIcon = coord.transportation && transportIcons[coord.transportation] ? transportIcons[coord.transportation] : 'fa-route';
                            
                            let popupContent = `
                                <div class="map-popup">
                                    <h4 style="margin: 0 0 8px 0; font-weight: bold; color: #1F2937;">Day ${coord.day}: ${coord.title || 'Untitled'}</h4>
                                    ${coord.place_name ? `<p style="margin: 0 0 4px 0; color: #6B7280;"><i class="fas fa-location-dot" style="margin-right: 4px;"></i>${coord.place_name}</p>` : ''}
                                    ${coord.transportation ? `<p style="margin: 0 0 4px 0; color: #6366F1;"><i class="fas ${transportIcon}" style="margin-right: 4px;"></i>Transportation: ${coord.transportation.charAt(0).toUpperCase() + coord.transportation.slice(1)}</p>` : ''}
                                    ${coord.altitude ? `<p style="margin: 0 0 4px 0; color: #6B7280;"><i class="fas fa-mountain" style="margin-right: 4px;"></i>Altitude: ${Number(coord.altitude).toLocaleString()}m</p>` : ''}
                            `;
                            
                            if (coord.auto_geocoded) {
                                popupContent += `<p style="margin: 0 0 4px 0; color: #10B981;"><i class="fas fa-map-marker-alt" style="margin-right: 4px;"></i><em>Location auto-detected</em></p>`;
                            }
                            
                            popupContent += `<p style="margin: 0; color: #6B7280; font-size: 12px;"><i class="fas fa-crosshairs" style="margin-right: 4px;"></i>${coord.lat.toFixed(6)}, ${coord.lng.toFixed(6)}</p></div>`;
                            
                            L.marker([coord.lat, coord.lng], { icon: customIcon })
                                .addTo(map)
                                .bindPopup(popupContent);
                        });
                        
                        // Fit map to show all markers and route segments
                        const allMapElements = L.featureGroup([allSegments]);
                        map.fitBounds(allMapElements.getBounds(), { padding: [20, 20] });
                        
                        // Add map controls
                        L.control.scale().addTo(map);
                    }
                    
                    // Initialize the map when DOM is ready
                    initializeInteractiveMap();
                });
                </script>
            <?php endif; ?>
            
            <?php 
            // Gallery section
            $gallery = tznew_get_field_safe('gallery');
            if ($gallery && is_array($gallery) && !empty($gallery)) :
            ?>
                <div class="tour-gallery mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <h2 class="text-2xl font-bold mb-6 text-green-700 border-b border-green-200 pb-3 flex items-center">
                        <i class="fas fa-images mr-2 text-green-600" aria-hidden="true"></i>
                        <?php esc_html_e('Gallery', 'tznew'); ?>
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <?php foreach ($gallery as $image) : ?>
                            <?php if (is_array($image) && !empty($image['url'])) : ?>
                                <a href="<?php echo esc_url($image['url']); ?>" 
                                   class="gallery-item group relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105"
                                   data-lightbox="tour-gallery"
                                   data-title="<?php echo esc_attr($image['alt'] ?? get_the_title()); ?>">
                                    <img src="<?php echo esc_url($image['sizes']['medium'] ?? $image['url']); ?>" 
                                         alt="<?php echo esc_attr($image['alt'] ?? ''); ?>" 
                                         class="w-full h-40 object-cover transition-transform duration-300 group-hover:scale-110"
                                         loading="lazy">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                        <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-xl" aria-hidden="true"></i>
                                    </div>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php
            // Booking CTA section
            $booking_cta = tznew_booking_cta(get_the_ID());
            if ($booking_cta) :
            ?>
                <div class="booking-section mb-8">
                    <?php echo $booking_cta; // Already escaped in the function ?>
                </div>
            <?php endif; ?>
            
        <?php else : ?>
            <!-- Archive view -->
            <?php the_title('<h2 class="entry-title text-xl font-bold mb-3"><a href="' . esc_url(get_permalink()) . '" rel="bookmark" class="text-green-800 hover:text-green-600 transition-colors duration-300">', '</a></h2>'); ?>
            
            <?php
            // Display meta information for archive
            $archive_duration = tznew_get_field_safe('duration');
            $archive_tour_type = tznew_get_field_safe('tour_type');
            $regions = get_the_terms(get_the_ID(), 'region');
            
            if ($archive_duration || $archive_tour_type || ($regions && !is_wp_error($regions) && !empty($regions))) :
            ?>
                <div class="tour-meta flex flex-wrap gap-3 mb-4 text-sm">
                    <?php if ($archive_duration) : ?>
                        <div class="tour-meta-item flex items-center bg-green-50 px-3 py-2 rounded-full border border-green-100 hover:bg-green-100 transition-colors duration-300">
                            <i class="fas fa-clock text-green-600 mr-2" aria-hidden="true"></i>
                            <span class="text-gray-700 font-medium"><?php echo esc_html($archive_duration); ?> <?php echo esc_html(_n('Day', 'Days', intval($archive_duration), 'tznew')); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($archive_tour_type) : ?>
                        <div class="tour-meta-item flex items-center bg-blue-50 px-3 py-2 rounded-full border border-blue-100 hover:bg-blue-100 transition-colors duration-300">
                            <i class="fas fa-map text-blue-600 mr-2" aria-hidden="true"></i>
                            <span class="text-gray-700 font-medium"><?php echo esc_html(ucfirst($archive_tour_type)); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($regions && !is_wp_error($regions) && !empty($regions)) : ?>
                        <div class="tour-meta-item flex items-center bg-purple-50 px-3 py-2 rounded-full border border-purple-100 hover:bg-purple-100 transition-colors duration-300">
                            <i class="fas fa-location-dot text-purple-600 mr-2" aria-hidden="true"></i>
                            <span class="text-gray-700 font-medium"><?php echo esc_html($regions[0]->name); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php
            // Short description for archive
            $overview = tznew_get_field_safe('overview');
            if ($overview) :
            ?>
                <div class="tour-excerpt mb-4 text-gray-700 leading-relaxed">
                    <?php echo wp_trim_words(wp_strip_all_tags($overview), 25, '...'); ?>
                </div>
            <?php endif; ?>
            
            <div class="flex items-center justify-between mt-4">
                <a href="<?php echo esc_url(get_permalink()); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg font-medium">
                    <?php esc_html_e('View Details', 'tznew'); ?>
                    <i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>
                </a>
                
                <?php
                $cost = tznew_get_field_safe('cost');
                if ($cost) :
                ?>
                    <div class="text-right">
                        <span class="text-sm text-gray-600"><?php esc_html_e('From', 'tznew'); ?></span>
                        <div class="text-xl font-bold text-green-600">$<?php echo esc_html(number_format($cost, 0)); ?></div>
                    </div>
                <?php endif; ?>
            </div>
            
        <?php endif; ?>
    </div>
</article>