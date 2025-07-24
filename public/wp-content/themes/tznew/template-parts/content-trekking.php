<?php
/**
 * Template part for displaying trekking posts
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
            $difficulty = tznew_get_field_safe('difficulty');
            $max_altitude = tznew_get_field_safe('max_altitude');
            $best_season = tznew_get_field_safe('best_season');
            $group_size = tznew_get_field_safe('group_size');
            
            if ($duration || $difficulty || $max_altitude || $best_season || $group_size) :
            ?>
                <div class="trek-meta grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                    <?php if ($duration) : ?>
                        <div class="trek-meta-item flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="text-blue-600 text-2xl mb-2">
                                <i class="fas fa-clock" aria-hidden="true"></i>
                            </div>
                            <span class="block text-sm text-gray-600 mb-1 font-medium"><?php esc_html_e('Duration', 'tznew'); ?></span>
                            <span class="font-bold text-lg text-gray-800"><?php echo esc_html($duration); ?> <?php echo esc_html(_n('Day', 'Days', intval($duration), 'tznew')); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($difficulty) : ?>
                        <div class="trek-meta-item flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="text-orange-600 text-2xl mb-2">
                                <i class="fas fa-mountain" aria-hidden="true"></i>
                            </div>
                            <span class="block text-sm text-gray-600 mb-1 font-medium"><?php esc_html_e('Difficulty', 'tznew'); ?></span>
                            <span class="font-bold text-lg text-gray-800"><?php echo esc_html(ucfirst($difficulty)); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($max_altitude) : ?>
                        <div class="trek-meta-item flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="text-green-600 text-2xl mb-2">
                                <i class="fas fa-arrow-up" aria-hidden="true"></i>
                            </div>
                            <span class="block text-sm text-gray-600 mb-1 font-medium"><?php esc_html_e('Max Altitude', 'tznew'); ?></span>
                            <span class="font-bold text-lg text-gray-800"><?php echo esc_html($max_altitude); ?> <?php esc_html_e('m', 'tznew'); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($best_season) : ?>
                        <div class="trek-meta-item flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="text-purple-600 text-2xl mb-2">
                                <i class="fas fa-calendar-days" aria-hidden="true"></i>
                            </div>
                            <span class="block text-sm text-gray-600 mb-1 font-medium"><?php esc_html_e('Best Season', 'tznew'); ?></span>
                            <span class="font-bold text-lg text-gray-800"><?php echo esc_html($best_season); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($group_size) : ?>
                        <div class="trek-meta-item flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="text-red-600 text-2xl mb-2">
                                <i class="fas fa-users" aria-hidden="true"></i>
                            </div>
                            <span class="block text-sm text-gray-600 mb-1 font-medium"><?php esc_html_e('Group Size', 'tznew'); ?></span>
                            <span class="font-bold text-lg text-gray-800"><?php echo esc_html($group_size); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php
            // Overview section
            $overview = tznew_get_field_safe('overview');
            if ($overview) :
            ?>
                <div class="trek-overview mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <h2 class="text-2xl font-bold mb-4 text-blue-700 border-b border-blue-200 pb-3 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-600" aria-hidden="true"></i>
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
                <div class="trek-highlights mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <h2 class="text-2xl font-bold mb-4 text-blue-700 border-b border-blue-200 pb-3 flex items-center">
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
            // Itinerary section
            if (tznew_have_rows_safe('itinerary')) :
            ?>
                <div class="trek-itinerary mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <h2 class="text-2xl font-bold mb-6 text-blue-700 border-b border-blue-200 pb-3 flex items-center">
                        <i class="fas fa-route mr-2 text-blue-600" aria-hidden="true"></i>
                        <?php esc_html_e('Itinerary', 'tznew'); ?>
                    </h2>
                    
                    <?php 
                    // Collect elevation data for graph
                    $elevation_data = array();
                    $day_count_temp = 1;
                    while (tznew_have_rows_safe('itinerary')) : 
                        tznew_the_row_safe();
                        $altitude = tznew_get_sub_field_safe('altitude');
                        if ($altitude) {
                            $elevation_data[] = array(
                                'day' => $day_count_temp,
                                'altitude' => intval($altitude)
                            );
                        }
                        $day_count_temp++;
                    endwhile;
                    
                    // Reset the loop
                     if (function_exists('reset_rows')) {
                         reset_rows();
                     }
                    ?>
                    
                     <?php if (!empty($elevation_data)) : ?>
                        <div class="elevation-graph mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl shadow-sm border border-blue-100">
                            <h3 class="text-2xl font-bold mb-4 text-blue-800 border-b border-blue-200 pb-3 flex items-center">
                                <i class="fas fa-chart-line mr-2 text-blue-600" aria-hidden="true"></i>
                                <?php esc_html_e('Elevation Profile', 'tznew'); ?>
                            </h3>
                            <div class="elevation-chart-container relative" style="height: 400px; width: 100%;">
                                <canvas id="trekkingElevationChart"></canvas>
                                <div id="trekkingChartLoading" class="absolute inset-0 flex items-center justify-center bg-blue-50 rounded-lg">
                                    <div class="text-center">
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
                                        <p class="text-sm text-blue-600"><?php esc_html_e('Loading elevation chart...', 'tznew'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const elevationData = <?php echo json_encode($elevation_data); ?>;
                            
                            function initTrekkingElevationChart() {
                                const ctx = document.getElementById('trekkingElevationChart');
                                const loadingDiv = document.getElementById('trekkingChartLoading');
                                
                                if (!ctx) {
                                    console.error('Trekking elevation chart canvas not found');
                                    return;
                                }
                                
                                if (typeof Chart === 'undefined') {
                                    console.error('Chart.js library not loaded');
                                    if (loadingDiv) {
                                        loadingDiv.innerHTML = '<div class="text-center"><p class="text-sm text-red-600"><?php esc_html_e('Chart library failed to load', 'tznew'); ?></p></div>';
                                    }
                                    return;
                                }
                                
                                try {
                                    new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            labels: elevationData.map(d => 'Day ' + d.day),
                                            datasets: [{
                                                label: '<?php esc_html_e('Altitude (m)', 'tznew'); ?>',
                                                data: elevationData.map(d => d.altitude),
                                                borderColor: 'rgb(59, 130, 246)',
                                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                                borderWidth: 3,
                                                fill: true,
                                                tension: 0.4,
                                                pointBackgroundColor: 'rgb(59, 130, 246)',
                                                pointBorderColor: '#fff',
                                                pointBorderWidth: 2,
                                                pointRadius: 6,
                                                pointHoverRadius: 8
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: {
                                                    display: true,
                                                    position: 'top',
                                                    labels: {
                                                        usePointStyle: true,
                                                        padding: 20
                                                    }
                                                },
                                                tooltip: {
                                                    mode: 'index',
                                                    intersect: false,
                                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                                    titleColor: '#fff',
                                                    bodyColor: '#fff',
                                                    borderColor: 'rgb(59, 130, 246)',
                                                    borderWidth: 1,
                                                    callbacks: {
                                                        label: function(context) {
                                                            return 'Altitude: ' + context.parsed.y.toLocaleString() + 'm';
                                                        }
                                                    }
                                                }
                                            },
                                            scales: {
                                                y: {
                                                    beginAtZero: false,
                                                    title: {
                                                        display: true,
                                                        text: '<?php esc_html_e('Altitude (meters)', 'tznew'); ?>',
                                                        font: {
                                                            size: 14,
                                                            weight: 'bold'
                                                        }
                                                    },
                                                    grid: {
                                                        color: 'rgba(0, 0, 0, 0.1)',
                                                        drawBorder: false
                                                    },
                                                    ticks: {
                                                        callback: function(value) {
                                                            return value.toLocaleString() + 'm';
                                                        }
                                                    }
                                                },
                                                x: {
                                                    title: {
                                                        display: true,
                                                        text: '<?php esc_html_e('Days', 'tznew'); ?>',
                                                        font: {
                                                            size: 14,
                                                            weight: 'bold'
                                                        }
                                                    },
                                                    grid: {
                                                        color: 'rgba(0, 0, 0, 0.1)',
                                                        drawBorder: false
                                                    }
                                                }
                                            },
                                            interaction: {
                                                mode: 'nearest',
                                                axis: 'x',
                                                intersect: false
                                            },
                                            elements: {
                                                point: {
                                                    hoverBackgroundColor: 'rgb(59, 130, 246)',
                                                    hoverBorderColor: '#fff',
                                                    hoverBorderWidth: 3
                                                }
                                            }
                                        }
                                    });
                                    
                                    // Hide loading indicator
                                    if (loadingDiv) {
                                        loadingDiv.style.display = 'none';
                                    }
                                    
                                } catch (error) {
                                    console.error('Error initializing trekking elevation chart:', error);
                                    if (loadingDiv) {
                                        loadingDiv.innerHTML = '<div class="text-center"><p class="text-sm text-red-600"><?php esc_html_e('Failed to load elevation chart', 'tznew'); ?></p></div>';
                                    }
                                }
                            }
                            
                            // Check if Chart.js is already loaded
                            if (typeof Chart !== 'undefined') {
                                initTrekkingElevationChart();
                            } else {
                                // Wait for Chart.js to load
                                let checkCount = 0;
                                const maxChecks = 50; // 5 seconds max wait
                                const checkInterval = setInterval(function() {
                                    checkCount++;
                                    if (typeof Chart !== 'undefined') {
                                        clearInterval(checkInterval);
                                        initTrekkingElevationChart();
                                    } else if (checkCount >= maxChecks) {
                                        clearInterval(checkInterval);
                                        console.error('Chart.js failed to load within timeout');
                                        const loadingDiv = document.getElementById('trekkingChartLoading');
                                        if (loadingDiv) {
                                            loadingDiv.innerHTML = '<div class="text-center"><p class="text-sm text-red-600"><?php esc_html_e('Chart library loading timeout', 'tznew'); ?></p></div>';
                                        }
                                    }
                                }, 100);
                            }
                        });
                        </script>
                    <?php endif; ?>
                    
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
                    
                    <?php if (!empty($route_coordinates)) : ?>
                        <div class="route-map mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                            <h3 class="text-lg font-bold mb-4 text-green-700 flex items-center">
                                <i class="fas fa-map mr-2 text-green-600" aria-hidden="true"></i>
                                <?php esc_html_e('Route Map', 'tznew'); ?>
                            </h3>
                            <div class="route-map-container">
                                <div id="routeMap" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                            </div>
                        </div>
                        
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Initialize the map
                            const routeCoordinates = <?php echo json_encode($route_coordinates); ?>;
                            
                            if (routeCoordinates.length > 0) {
                                // Calculate center point
                                const centerLat = routeCoordinates.reduce((sum, coord) => sum + coord.lat, 0) / routeCoordinates.length;
                                const centerLng = routeCoordinates.reduce((sum, coord) => sum + coord.lng, 0) / routeCoordinates.length;
                                
                                // Initialize map with attribution control disabled
                                const map = L.map('routeMap', {
                                    attributionControl: false
                                }).setView([centerLat, centerLng], 10);
                                
                                // Add OpenStreetMap tiles
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '',
                                    maxZoom: 18
                                }).addTo(map);
                                
                                // Create route line coordinates
                                const routeLineCoords = routeCoordinates.map(coord => [coord.lat, coord.lng]);
                                
                                // Add route line segments with transportation icons
                                const routeSegments = [];
                                for (let i = 0; i < routeLineCoords.length - 1; i++) {
                                    const startCoord = routeLineCoords[i];
                                    const endCoord = routeLineCoords[i + 1];
                                    const segmentCoords = [startCoord, endCoord];
                                    
                                    // Get transportation method for this segment
                                    const nextDayTransport = routeCoordinates[i + 1].transportation;
                                    
                                    // Create segment line
                                    const segment = L.polyline(segmentCoords, {
                                        color: '#059669',
                                        weight: 4,
                                        opacity: 0.8,
                                        smoothFactor: 1
                                    }).addTo(map);
                                    
                                    routeSegments.push(segment);
                                    
                                    // Add transportation icon on the line segment
                                    if (nextDayTransport) {
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
                                
                                // Create a feature group for fitting bounds
                                const allSegments = L.featureGroup(routeSegments);
                                
                                // Add markers for each day
                                routeCoordinates.forEach((coord, index) => {
                                    const isStart = index === 0;
                                    const isEnd = index === routeCoordinates.length - 1;
                                    
                                    let markerColor = '#3B82F6'; // Default blue
                                    let iconClass = 'fa-location-dot';
                                    
                                    if (isStart) {
                                        markerColor = '#10B981'; // Green for start
                                        iconClass = 'fa-play';
                                    } else if (isEnd) {
                                        markerColor = '#EF4444'; // Red for end
                                        iconClass = 'fa-flag-checkered';
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
                                    
                                    const popupContent = `
                                        <div class="map-popup">
                                            <h4 style="margin: 0 0 8px 0; font-weight: bold; color: #1F2937;">Day ${coord.day}: ${coord.title || 'Untitled'}</h4>
                                            ${coord.place_name ? `<p style="margin: 0 0 4px 0; color: #6B7280;"><i class="fas fa-location-dot" style="margin-right: 4px;"></i>${coord.place_name}</p>` : ''}
                                            ${coord.transportation ? `<p style="margin: 0 0 4px 0; color: #6366F1;"><i class="fas ${transportIcon}" style="margin-right: 4px;"></i>Transportation: ${coord.transportation.charAt(0).toUpperCase() + coord.transportation.slice(1)}</p>` : ''}
                                            ${coord.altitude ? `<p style="margin: 0 0 4px 0; color: #6B7280;"><i class="fas fa-mountain" style="margin-right: 4px;"></i>Altitude: ${Number(coord.altitude).toLocaleString()}m</p>` : ''}
                                            <p style="margin: 0; color: #6B7280; font-size: 12px;"><i class="fas fa-crosshairs" style="margin-right: 4px;"></i>${coord.lat.toFixed(6)}, ${coord.lng.toFixed(6)}</p>
                                        </div>
                                    `;
                                    
                                    L.marker([coord.lat, coord.lng], { icon: customIcon })
                                        .addTo(map)
                                        .bindPopup(popupContent);
                                });
                                
                                // Fit map to show all segments and markers
                                if (allSegments.getLayers().length > 0) {
                                    map.fitBounds(allSegments.getBounds(), { padding: [20, 20] });
                                }
                                
                                // Add map controls
                                L.control.scale().addTo(map);
                            }
                        });
                        </script>
                    <?php endif; ?>
                    
                    <div class="itinerary-container space-y-4">
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
                            $images = tznew_get_sub_field_safe('images');
                            $video_urls = tznew_get_sub_field_safe('video_urls');
                            $coordinates = tznew_get_sub_field_safe('coordinates');
                            $recommendation = tznew_get_sub_field_safe('recommendation');
                            
                            // Auto-calculate elevation gain/loss based on altitude difference
                            $calculated_elevation_change = null;
                            if ($previous_altitude !== null && $altitude !== null) {
                                $calculated_elevation_change = $altitude - $previous_altitude;
                            }
                        ?>
                            <div class="itinerary-day bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="itinerary-day-header p-4 cursor-pointer bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-xl border-b border-gray-200 hover:from-blue-100 hover:to-indigo-100 transition-all duration-300" 
                                     onclick="toggleItineraryDay(<?php echo esc_attr($day_count); ?>)">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="bg-blue-600 text-white rounded-full w-10 h-10 flex items-center justify-center text-sm font-bold mr-4"><?php echo esc_html($day_count); ?></span>
                                            <div>
                                                <h3 class="text-lg font-bold text-blue-800">
                                                    <?php esc_html_e('Day', 'tznew'); ?> <?php echo esc_html($day_count); ?>: <?php echo esc_html($day_title); ?>
                                                </h3>
                                                <?php if ($place_name) : ?>
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        <i class="fas fa-location-dot mr-1" aria-hidden="true"></i>
                                                        <?php echo esc_html($place_name); ?>
                                                        <?php if ($altitude) : ?>
                                                            <span class="ml-2 text-blue-600 font-medium"><?php echo esc_html(number_format($altitude)); ?>m</span>
                                                        <?php endif; ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <?php if ($transportation) : 
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
                                                <span class="text-sm text-gray-600">
                                                    <i class="fas <?php echo esc_attr($transport_icon); ?> mr-1" aria-hidden="true"></i>
                                                    <?php echo esc_html(ucfirst($transportation)); ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($walking_time) : ?>
                                                <span class="text-sm text-gray-600">
                                                    <i class="fas fa-clock mr-1" aria-hidden="true"></i>
                                                    <?php echo esc_html($walking_time); ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($distance) : ?>
                                                <span class="text-sm text-gray-600">
                                                    <i class="fas fa-route mr-1" aria-hidden="true"></i>
                                                    <?php echo esc_html($distance); ?>km
                                                </span>
                                            <?php endif; ?>
                                            <i class="fas fa-chevron-down text-blue-600 transform transition-transform duration-300 itinerary-chevron-<?php echo esc_attr($day_count); ?>" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="itinerary-day-content hidden p-6" id="itinerary-day-<?php echo esc_attr($day_count); ?>">
                                    <?php if ($day_description) : ?>
                                        <div class="day-description mb-6">
                                            <h4 class="text-md font-semibold text-gray-800 mb-2 flex items-center">
                                                <i class="fas fa-info-circle mr-2 text-blue-600" aria-hidden="true"></i>
                                                <?php esc_html_e('Description', 'tznew'); ?>
                                            </h4>
                                            <div class="prose max-w-none text-gray-700 leading-relaxed">
                                                <?php echo wp_kses_post($day_description); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="day-details grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                        <?php if ($transportation) : ?>
                                            <div class="detail-item bg-indigo-50 p-3 rounded-lg border border-indigo-200">
                                                <div class="flex items-center">
                                                    <i class="fas <?php echo esc_attr($transport_icon); ?> text-indigo-600 mr-2" aria-hidden="true"></i>
                                                    <span class="text-sm font-semibold text-indigo-700"><?php esc_html_e('Transportation:', 'tznew'); ?></span>
                                                </div>
                                                <div class="text-gray-700 mt-1"><?php echo esc_html(ucfirst($transportation)); ?></div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($calculated_elevation_change !== null) : ?>
                                            <div class="detail-item <?php echo $calculated_elevation_change >= 0 ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200'; ?> p-3 rounded-lg border">
                                                <div class="flex items-center">
                                                    <i class="fas <?php echo $calculated_elevation_change >= 0 ? 'fa-arrow-up text-red-600' : 'fa-arrow-down text-green-600'; ?> mr-2" aria-hidden="true"></i>
                                                    <span class="text-sm font-semibold <?php echo $calculated_elevation_change >= 0 ? 'text-red-700' : 'text-green-700'; ?>"><?php esc_html_e('Elevation Gain/Loss (m) ±:', 'tznew'); ?></span>
                                                </div>
                                                <div class="text-lg font-bold <?php echo $calculated_elevation_change >= 0 ? 'text-red-800' : 'text-green-800'; ?> mt-1">
                                                    <?php echo $calculated_elevation_change >= 0 ? '+' : ''; ?><?php echo esc_html(number_format($calculated_elevation_change)); ?>m
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($accommodation) : ?>
                                            <div class="detail-item bg-blue-50 p-3 rounded-lg border border-blue-200">
                                                <div class="flex items-center">
                                                    <i class="fas fa-bed text-blue-600 mr-2" aria-hidden="true"></i>
                                                    <span class="text-sm font-semibold text-blue-700"><?php esc_html_e('Accommodation:', 'tznew'); ?></span>
                                                </div>
                                                <div class="text-gray-700 mt-1"><?php echo esc_html($accommodation); ?></div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($meals) : ?>
                                            <div class="detail-item bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                                                <div class="flex items-center">
                                                    <i class="fas fa-utensils text-yellow-600 mr-2" aria-hidden="true"></i>
                                                    <span class="text-sm font-semibold text-yellow-700"><?php esc_html_e('Meals:', 'tznew'); ?></span>
                                                </div>
                                                <div class="text-gray-700 mt-1"><?php echo esc_html($meals); ?></div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($coordinates && isset($coordinates['latitude']) && isset($coordinates['longitude'])) : ?>
                                            <div class="detail-item bg-purple-50 p-3 rounded-lg border border-purple-200">
                                                <div class="flex items-center">
                                                    <i class="fas fa-map-marked-alt text-purple-600 mr-2" aria-hidden="true"></i>
                                                    <span class="text-sm font-semibold text-purple-700"><?php esc_html_e('Coordinates:', 'tznew'); ?></span>
                                                </div>
                                                <div class="text-gray-700 mt-1 text-sm">
                                                    <?php echo esc_html($coordinates['latitude']); ?>, <?php echo esc_html($coordinates['longitude']); ?>
                                                    <a href="https://www.google.com/maps?q=<?php echo esc_attr($coordinates['latitude']); ?>,<?php echo esc_attr($coordinates['longitude']); ?>" 
                                                       target="_blank" class="ml-2 text-purple-600 hover:text-purple-800">
                                                        <i class="fas fa-up-right-from-square" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($images && is_array($images) && !empty($images)) : ?>
                                        <div class="day-images mb-6">
                                            <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                                                <i class="fas fa-images mr-2 text-blue-600" aria-hidden="true"></i>
                                                <?php esc_html_e('Images', 'tznew'); ?>
                                            </h4>
                                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                                <?php foreach ($images as $image) : ?>
                                                    <?php if (is_array($image) && !empty($image['url'])) : ?>
                                                        <a href="<?php echo esc_url($image['url']); ?>" 
                                                           class="day-image-item group relative overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-all duration-300"
                                                           data-lightbox="day-<?php echo esc_attr($day_count); ?>-gallery">
                                                            <img src="<?php echo esc_url($image['sizes']['medium'] ?? $image['url']); ?>" 
                                                                 alt="<?php echo esc_attr($image['alt'] ?? ''); ?>" 
                                                                 class="w-full h-24 object-cover transition-transform duration-300 group-hover:scale-110">
                                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                                                <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" aria-hidden="true"></i>
                                                            </div>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($video_urls && is_array($video_urls) && !empty($video_urls)) : ?>
                                        <div class="day-videos mb-6">
                                            <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                                                <i class="fas fa-video mr-2 text-red-600" aria-hidden="true"></i>
                                                <?php esc_html_e('Videos', 'tznew'); ?>
                                            </h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <?php foreach ($video_urls as $video) : ?>
                                                    <?php if (is_array($video) && !empty($video['video_url'])) : ?>
                                                        <div class="video-item">
                                                            <a href="<?php echo esc_url($video['video_url']); ?>" 
                                                               target="_blank" 
                                                               class="block bg-gray-100 p-4 rounded-lg hover:bg-gray-200 transition-colors duration-300">
                                                                <i class="fas fa-play-circle text-red-600 mr-2" aria-hidden="true"></i>
                                                                <?php esc_html_e('Watch Video', 'tznew'); ?>
                                                                <i class="fas fa-up-right-from-square ml-2 text-gray-500" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($recommendation) : ?>
                                        <div class="day-recommendation">
                                            <h4 class="text-md font-semibold text-gray-800 mb-2 flex items-center">
                                                <i class="fas fa-lightbulb mr-2 text-yellow-600" aria-hidden="true"></i>
                                                <?php esc_html_e('Recommendation', 'tznew'); ?>
                                            </h4>
                                            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                                <div class="prose max-w-none text-gray-700 leading-relaxed">
                                                    <?php echo wp_kses_post($recommendation); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php 
                        $day_count++;
                        $previous_altitude = $altitude; // Store current altitude for next iteration
                        endwhile; 
                        ?>
                    </div>
                    
                    <script>
                    function toggleItineraryDay(dayNumber) {
                        const content = document.getElementById('itinerary-day-' + dayNumber);
                        const chevron = document.querySelector('.itinerary-chevron-' + dayNumber);
                        
                        if (content.classList.contains('hidden')) {
                            content.classList.remove('hidden');
                            content.style.maxHeight = content.scrollHeight + 'px';
                            chevron.style.transform = 'rotate(180deg)';
                        } else {
                            content.classList.add('hidden');
                            content.style.maxHeight = '0';
                            chevron.style.transform = 'rotate(0deg)';
                        }
                    }
                    </script>
                </div>
            <?php endif; ?>
            
            <?php
            // Includes/Excludes section
            $includes = tznew_get_field_safe('includes');
            $excludes = tznew_get_field_safe('excludes');
            if ($includes || $excludes) :
            ?>
                <div class="trek-includes-excludes mb-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <?php if ($includes) : ?>
                        <div class="trek-includes bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-xl shadow-sm border border-green-100 hover:shadow-md transition-shadow duration-300">
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
                        <div class="trek-excludes bg-gradient-to-br from-red-50 to-pink-50 p-6 rounded-xl shadow-sm border border-red-100 hover:shadow-md transition-shadow duration-300">
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
            // Gallery section
            $gallery = tznew_get_field_safe('gallery');
            if ($gallery && is_array($gallery) && !empty($gallery)) :
            ?>
                <div class="trek-gallery mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <h2 class="text-2xl font-bold mb-6 text-blue-700 border-b border-blue-200 pb-3 flex items-center">
                        <i class="fas fa-images mr-2 text-blue-600" aria-hidden="true"></i>
                        <?php esc_html_e('Gallery', 'tznew'); ?>
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <?php foreach ($gallery as $image) : ?>
                            <?php if (is_array($image) && !empty($image['url'])) : ?>
                                <a href="<?php echo esc_url($image['url']); ?>" 
                                   class="gallery-item group relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105"
                                   data-lightbox="trek-gallery"
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
            // Cost Information section
            $cost_info = tznew_get_field_safe('cost_info');
            if ($cost_info && (isset($cost_info['price_usd']) || isset($cost_info['pricing_type']))) :
            ?>
                <div class="trek-cost mb-8 bg-gradient-to-br from-yellow-50 to-orange-50 p-6 rounded-xl shadow-sm border border-yellow-100 hover:shadow-md transition-shadow duration-300">
                    <h2 class="text-2xl font-bold mb-4 text-orange-700 border-b border-orange-200 pb-3 flex items-center">
                        <i class="fas fa-dollar-sign mr-2 text-orange-600" aria-hidden="true"></i>
                        <?php esc_html_e('Cost Information', 'tznew'); ?>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php if (isset($cost_info['price_usd']) && $cost_info['price_usd']) : ?>
                            <div class="cost-item bg-white p-4 rounded-lg shadow-sm">
                                <div class="text-sm text-gray-600 mb-1"><?php esc_html_e('Price (USD)', 'tznew'); ?></div>
                                <div class="text-3xl font-bold text-orange-600">$<?php echo esc_html(number_format($cost_info['price_usd'])); ?></div>
                                <?php if (isset($cost_info['pricing_type']) && $cost_info['pricing_type']) : ?>
                                    <div class="text-sm text-gray-500 mt-1"><?php echo esc_html($cost_info['pricing_type']); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        $group_discount = tznew_get_field_safe('group_discount');
                        if ($group_discount) :
                        ?>
                            <div class="cost-item bg-white p-4 rounded-lg shadow-sm">
                                <div class="text-sm text-gray-600 mb-1"><?php esc_html_e('Group Discount', 'tznew'); ?></div>
                                <div class="text-lg font-bold text-green-600"><?php echo esc_html($group_discount); ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php
            // Permits section
            $permits = tznew_get_field_safe('permits');
            if ($permits && (isset($permits['permit_options']) || isset($permits['guide_requirement']) || isset($permits['restricted_area']))) :
            ?>
                <div class="trek-permits mb-8 bg-gradient-to-br from-purple-50 to-indigo-50 p-6 rounded-xl shadow-sm border border-purple-100 hover:shadow-md transition-shadow duration-300">
                    <h2 class="text-2xl font-bold mb-4 text-purple-700 border-b border-purple-200 pb-3 flex items-center">
                        <i class="fas fa-id-card mr-2 text-purple-600" aria-hidden="true"></i>
                        <?php esc_html_e('Permits & Requirements', 'tznew'); ?>
                    </h2>
                    <div class="space-y-4">
                        <?php if (isset($permits['permit_options']) && $permits['permit_options']) : ?>
                            <div class="permit-item bg-white p-4 rounded-lg shadow-sm">
                                <h3 class="font-semibold text-purple-700 mb-2 flex items-center">
                                    <i class="fas fa-file-alt mr-2" aria-hidden="true"></i>
                                    <?php esc_html_e('Permit Options', 'tznew'); ?>
                                </h3>
                                <div class="text-gray-700"><?php echo wp_kses_post(is_string($permits['permit_options']) ? $permits['permit_options'] : ''); ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($permits['guide_requirement']) && $permits['guide_requirement']) : ?>
                            <div class="permit-item bg-white p-4 rounded-lg shadow-sm">
                                <h3 class="font-semibold text-purple-700 mb-2 flex items-center">
                                    <i class="fas fa-user-tie mr-2" aria-hidden="true"></i>
                                    <?php esc_html_e('Guide Requirement', 'tznew'); ?>
                                </h3>
                                <div class="text-gray-700"><?php echo wp_kses_post(is_string($permits['guide_requirement']) ? $permits['guide_requirement'] : ''); ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($permits['restricted_area']) && $permits['restricted_area']) : ?>
                            <div class="permit-item bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-400">
                                <h3 class="font-semibold text-red-700 mb-2 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-2" aria-hidden="true"></i>
                                    <?php esc_html_e('Restricted Area Information', 'tznew'); ?>
                                </h3>
                                <div class="text-gray-700"><?php echo wp_kses_post(is_string($permits['restricted_area']) ? $permits['restricted_area'] : ''); ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php
            // Region and Coordinates section
            $region = tznew_get_field_safe('region');
            $coordinates = tznew_get_field_safe('coordinates');
            if ($region || ($coordinates && isset($coordinates['latitude']) && isset($coordinates['longitude']))) :
            ?>
                <div class="trek-location mb-8 bg-gradient-to-br from-teal-50 to-cyan-50 p-6 rounded-xl shadow-sm border border-teal-100 hover:shadow-md transition-shadow duration-300">
                    <h2 class="text-2xl font-bold mb-4 text-teal-700 border-b border-teal-200 pb-3 flex items-center">
                        <i class="fas fa-map-marked-alt mr-2 text-teal-600" aria-hidden="true"></i>
                        <?php esc_html_e('Location Details', 'tznew'); ?>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php if ($region) : ?>
                            <div class="location-item bg-white p-4 rounded-lg shadow-sm">
                                <h3 class="font-semibold text-teal-700 mb-2 flex items-center">
                                    <i class="fas fa-globe-asia mr-2" aria-hidden="true"></i>
                                    <?php esc_html_e('Region', 'tznew'); ?>
                                </h3>
                                <div class="text-gray-700 text-lg"><?php echo esc_html($region); ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($coordinates && isset($coordinates['latitude']) && isset($coordinates['longitude'])) : ?>
                            <div class="location-item bg-white p-4 rounded-lg shadow-sm">
                                <h3 class="font-semibold text-teal-700 mb-2 flex items-center">
                                    <i class="fas fa-crosshairs mr-2" aria-hidden="true"></i>
                                    <?php esc_html_e('Coordinates', 'tznew'); ?>
                                </h3>
                                <div class="text-gray-700">
                                    <div><?php esc_html_e('Latitude:', 'tznew'); ?> <?php echo esc_html($coordinates['latitude']); ?></div>
                                    <div><?php esc_html_e('Longitude:', 'tznew'); ?> <?php echo esc_html($coordinates['longitude']); ?></div>
                                    <a href="https://www.google.com/maps?q=<?php echo esc_attr($coordinates['latitude']); ?>,<?php echo esc_attr($coordinates['longitude']); ?>" 
                                       target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center mt-2 text-teal-600 hover:text-teal-800 transition-colors duration-300">
                                        <i class="fas fa-up-right-from-square mr-1" aria-hidden="true"></i>
                                        <?php esc_html_e('View on Google Maps', 'tznew'); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php
            // Recommendations section
            $recommendation = tznew_get_field_safe('recommendation');
            if ($recommendation) :
            ?>
                <div class="trek-recommendations mb-8 bg-gradient-to-br from-amber-50 to-yellow-50 p-6 rounded-xl shadow-sm border border-amber-100 hover:shadow-md transition-shadow duration-300">
                    <h2 class="text-2xl font-bold mb-4 text-amber-700 border-b border-amber-200 pb-3 flex items-center">
                        <i class="fas fa-lightbulb mr-2 text-amber-600" aria-hidden="true"></i>
                        <?php esc_html_e('Recommendations', 'tznew'); ?>
                    </h2>
                    <div class="prose max-w-none text-gray-700 leading-relaxed bg-white p-4 rounded-lg shadow-sm">
                        <?php echo wp_kses_post($recommendation); ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php
            // Hashtags section
            $hashtags = tznew_get_field_safe('hashtags');
            if ($hashtags) :
            ?>
                <div class="trek-hashtags mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <h2 class="text-2xl font-bold mb-4 text-gray-700 border-b border-gray-200 pb-3 flex items-center">
                        <i class="fas fa-hashtag mr-2 text-gray-600" aria-hidden="true"></i>
                        <?php esc_html_e('Tags', 'tznew'); ?>
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <?php
                        $hashtag_array = explode(',', $hashtags);
                        foreach ($hashtag_array as $hashtag) :
                            $hashtag = trim($hashtag);
                            if (!empty($hashtag)) :
                        ?>
                            <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium hover:bg-blue-200 transition-colors duration-300">
                                #<?php echo esc_html($hashtag); ?>
                            </span>
                        <?php
                            endif;
                        endforeach;
                        ?>
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
            <?php else : ?>
                <!-- Default booking CTA if function doesn't exist -->
                <div class="booking-section mb-8 bg-gradient-to-r from-blue-600 to-indigo-600 p-8 rounded-xl shadow-lg text-white text-center">
                    <h2 class="text-3xl font-bold mb-4"><?php esc_html_e('Ready to Book This Trek?', 'tznew'); ?></h2>
                    <p class="text-blue-100 mb-6 text-lg"><?php esc_html_e('Contact us today to start planning your adventure!', 'tznew'); ?></p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?php echo esc_url(site_url('/booking?trekking_id=' . get_the_ID())); ?>" class="inline-flex items-center px-8 py-3 bg-white text-blue-600 rounded-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 font-bold shadow-md">
                            <i class="fas fa-phone mr-2" aria-hidden="true"></i>
                            <?php esc_html_e('Book Now', 'tznew'); ?>
                        </a>
                        <a href="<?php echo esc_url(site_url('/inquiry?trekking_id=' . get_the_ID())); ?>" class="inline-flex items-center px-8 py-3 bg-transparent border-2 border-white text-white rounded-lg hover:bg-white hover:text-blue-600 transition-all duration-300 transform hover:scale-105 font-bold">
                            <i class="fas fa-envelope mr-2" aria-hidden="true"></i>
                            <?php esc_html_e('Send Inquiry', 'tznew'); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            
        <?php else : ?>
            <!-- Archive view -->
            <?php the_title('<h2 class="entry-title text-xl font-bold mb-3"><a href="' . esc_url(get_permalink()) . '" rel="bookmark" class="text-blue-800 hover:text-blue-600 transition-colors duration-300">', '</a></h2>'); ?>
            
            <?php
            // Display meta information for archive
            $archive_duration = tznew_get_field_safe('duration');
            $archive_difficulty = tznew_get_field_safe('difficulty');
            $regions = get_the_terms(get_the_ID(), 'region');
            
            if ($archive_duration || $archive_difficulty || ($regions && !is_wp_error($regions) && !empty($regions))) :
            ?>
                <div class="trek-meta flex flex-wrap gap-3 mb-4 text-sm">
                    <?php if ($archive_duration) : ?>
                        <div class="trek-meta-item flex items-center bg-blue-50 px-3 py-2 rounded-full border border-blue-100 hover:bg-blue-100 transition-colors duration-300">
                            <i class="fas fa-clock text-blue-600 mr-2" aria-hidden="true"></i>
                            <span class="text-gray-700 font-medium"><?php echo esc_html($archive_duration); ?> <?php echo esc_html(_n('Day', 'Days', intval($archive_duration), 'tznew')); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($archive_difficulty) : ?>
                        <div class="trek-meta-item flex items-center bg-orange-50 px-3 py-2 rounded-full border border-orange-100 hover:bg-orange-100 transition-colors duration-300">
                            <i class="fas fa-mountain text-orange-600 mr-2" aria-hidden="true"></i>
                            <span class="text-gray-700 font-medium"><?php echo esc_html(ucfirst($archive_difficulty)); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($regions && !is_wp_error($regions) && !empty($regions)) : ?>
                        <div class="trek-meta-item flex items-center bg-green-50 px-3 py-2 rounded-full border border-green-100 hover:bg-green-100 transition-colors duration-300">
                            <i class="fas fa-location-dot text-green-600 mr-2" aria-hidden="true"></i>
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
                <div class="trek-excerpt mb-4 text-gray-700 leading-relaxed">
                    <?php echo wp_trim_words(wp_strip_all_tags($overview), 25, '...'); ?>
                </div>
            <?php endif; ?>
            
            <div class="flex items-center justify-between mt-4">
                <a href="<?php echo esc_url(get_permalink()); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg font-medium">
                    <?php esc_html_e('View Details', 'tznew'); ?>
                    <i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>
                </a>
                
                <?php
                $cost_info = tznew_get_field_safe('cost_info');
                if ($cost_info && isset($cost_info['price_usd']) && $cost_info['price_usd']) :
                ?>
                    <div class="text-right">
                        <span class="text-sm text-gray-600"><?php esc_html_e('From', 'tznew'); ?></span>
                        <div class="text-xl font-bold text-blue-600">$<?php echo esc_html(number_format($cost_info['price_usd'], 0)); ?></div>
                        <?php if (isset($cost_info['pricing_type']) && $cost_info['pricing_type']) : ?>
                            <div class="text-xs text-gray-500"><?php echo esc_html($cost_info['pricing_type']); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
        <?php endif; ?>
    </div>
</article>