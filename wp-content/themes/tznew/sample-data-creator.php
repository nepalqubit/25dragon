<?php
/**
 * Sample Data Creator for Trekking Posts
 * Access this file via: http://dragonlatest.local/wp-content/themes/tznew/sample-data-creator.php
 */

// Include WordPress
require_once('../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('You do not have permission to access this page.');
}

// Sample trekking data with coordinates
$sample_treks = array(
    array(
        'title' => 'Everest Base Camp Trek',
        'region' => 'everest',
        'difficulty' => 'challenging',
        'duration' => '14 days',
        'max_altitude' => '5364',
        'rating' => '4.8',
        'featured' => '1',
        'popular' => '1',
        'overview' => 'Experience the ultimate adventure to the base of the world\'s highest mountain. This iconic trek takes you through Sherpa villages, ancient monasteries, and breathtaking Himalayan landscapes.',
        'itinerary' => array(
            array(
                'title' => 'Arrival in Kathmandu',
                'description' => 'Arrive in Kathmandu, transfer to hotel',
                'place_name' => 'Kathmandu',
                'altitude' => 1400,
                'coordinates' => array('latitude' => '27.7172', 'longitude' => '85.3240')
            ),
            array(
                'title' => 'Fly to Lukla, Trek to Phakding',
                'description' => 'Early morning flight to Lukla, begin trek to Phakding',
                'place_name' => 'Phakding',
                'altitude' => 2610,
                'coordinates' => array('latitude' => '27.7389', 'longitude' => '86.7142')
            ),
            array(
                'title' => 'Trek to Namche Bazaar',
                'description' => 'Cross suspension bridges and climb to the Sherpa capital',
                'place_name' => 'Namche Bazaar',
                'altitude' => 3440,
                'coordinates' => array('latitude' => '27.8059', 'longitude' => '86.7142')
            ),
            array(
                'title' => 'Everest Base Camp',
                'description' => 'Reach the base camp of Mount Everest',
                'place_name' => 'Everest Base Camp',
                'altitude' => 5364,
                'coordinates' => array('latitude' => '28.0018', 'longitude' => '86.8523')
            )
        )
    ),
    array(
        'title' => 'Annapurna Circuit Trek',
        'region' => 'annapurna',
        'difficulty' => 'moderate',
        'duration' => '16 days',
        'max_altitude' => '5416',
        'rating' => '4.7',
        'featured' => '1',
        'popular' => '1',
        'overview' => 'One of the world\'s classic treks, the Annapurna Circuit offers incredible diversity of landscapes, cultures, and climates.',
        'itinerary' => array(
            array(
                'title' => 'Drive to Besisahar',
                'description' => 'Drive from Kathmandu to Besisahar, start of the trek',
                'place_name' => 'Besisahar',
                'altitude' => 760,
                'coordinates' => array('latitude' => '28.2333', 'longitude' => '84.4167')
            ),
            array(
                'title' => 'Trek to Manang',
                'description' => 'Acclimatization day in the beautiful village of Manang',
                'place_name' => 'Manang',
                'altitude' => 3519,
                'coordinates' => array('latitude' => '28.6667', 'longitude' => '84.0167')
            ),
            array(
                'title' => 'Cross Thorong La Pass',
                'description' => 'Cross the highest point of the trek at Thorong La Pass',
                'place_name' => 'Thorong La Pass',
                'altitude' => 5416,
                'coordinates' => array('latitude' => '28.7833', 'longitude' => '83.9333')
            )
        )
    ),
    array(
        'title' => 'Langtang Valley Trek',
        'region' => 'langtang',
        'difficulty' => 'moderate',
        'duration' => '10 days',
        'max_altitude' => '4984',
        'rating' => '4.6',
        'featured' => '1',
        'overview' => 'Known as the "Valley of Glaciers", Langtang offers stunning mountain views and rich Tamang culture.',
        'itinerary' => array(
            array(
                'title' => 'Drive to Syabrubesi',
                'description' => 'Drive from Kathmandu to Syabrubesi',
                'place_name' => 'Syabrubesi',
                'altitude' => 1550,
                'coordinates' => array('latitude' => '28.1667', 'longitude' => '85.3833')
            ),
            array(
                'title' => 'Trek to Langtang Village',
                'description' => 'Trek through rhododendron forests to Langtang Village',
                'place_name' => 'Langtang Village',
                'altitude' => 3430,
                'coordinates' => array('latitude' => '28.2167', 'longitude' => '85.5333')
            ),
            array(
                'title' => 'Kyanjin Gompa',
                'description' => 'Visit the ancient monastery and enjoy mountain views',
                'place_name' => 'Kyanjin Gompa',
                'altitude' => 3870,
                'coordinates' => array('latitude' => '28.2167', 'longitude' => '85.5667')
            )
        )
    )
);

function create_sample_treks($sample_treks) {
    $results = array();
    
    foreach ($sample_treks as $trek_data) {
        // Check if trek already exists
        $existing = get_posts(array(
            'post_type' => 'trekking',
            'title' => $trek_data['title'],
            'post_status' => 'any',
            'numberposts' => 1
        ));
        
        if (!empty($existing)) {
            $results[] = "Trek '{$trek_data['title']}' already exists. Skipping.";
            continue;
        }
        
        // Create the post
        $post_data = array(
            'post_title' => $trek_data['title'],
            'post_content' => $trek_data['overview'],
            'post_status' => 'publish',
            'post_type' => 'trekking',
            'post_author' => get_current_user_id()
        );
        
        $post_id = wp_insert_post($post_data);
        
        if ($post_id && !is_wp_error($post_id)) {
            // Add meta fields
            update_field('name_of_the_trek', $trek_data['title'], $post_id);
            update_field('duration', $trek_data['duration'], $post_id);
            update_field('max_altitude', $trek_data['max_altitude'], $post_id);
            update_field('rating', $trek_data['rating'], $post_id);
            update_field('featured', $trek_data['featured'], $post_id);
            update_field('popular', $trek_data['popular'], $post_id);
            update_field('overview', $trek_data['overview'], $post_id);
            
            // Add itinerary with coordinates
            update_field('itinerary', $trek_data['itinerary'], $post_id);
            
            // Assign region taxonomy term
            $region_term = get_term_by('slug', $trek_data['region'], 'region');
            if ($region_term) {
                wp_set_post_terms($post_id, array($region_term->term_id), 'region');
            }
            
            // Assign difficulty taxonomy term
            $difficulty_term = get_term_by('slug', $trek_data['difficulty'], 'difficulty');
            if ($difficulty_term) {
                wp_set_post_terms($post_id, array($difficulty_term->term_id), 'difficulty');
            }
            
            $results[] = "✓ Created trek: {$trek_data['title']} (ID: $post_id)";
        } else {
            $error_msg = is_wp_error($post_id) ? $post_id->get_error_message() : 'Unknown error';
            $results[] = "✗ Failed to create trek: {$trek_data['title']} - $error_msg";
        }
    }
    
    return $results;
}

// Handle form submission
$results = array();
if (isset($_POST['create_sample_data'])) {
    $results = create_sample_treks($sample_treks);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Sample Data Creator</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 800px; }
        .button { background: #0073aa; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .button:hover { background: #005a87; }
        .results { margin-top: 20px; padding: 15px; background: #f9f9f9; border-left: 4px solid #0073aa; }
        .success { color: #46b450; }
        .error { color: #dc3232; }
        .warning { color: #ffb900; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sample Trekking Data Creator</h1>
        <p>This tool will create sample trekking posts with itinerary coordinates for the interactive map functionality.</p>
        
        <form method="post">
            <button type="submit" name="create_sample_data" class="button">Create Sample Trekking Posts</button>
        </form>
        
        <?php if (!empty($results)): ?>
            <div class="results">
                <h3>Results:</h3>
                <?php foreach ($results as $result): ?>
                    <p class="<?php echo strpos($result, '✓') !== false ? 'success' : (strpos($result, '✗') !== false ? 'error' : 'warning'); ?>">
                        <?php echo esc_html($result); ?>
                    </p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <h3>Sample Data Preview:</h3>
        <ul>
            <?php foreach ($sample_treks as $trek): ?>
                <li><strong><?php echo esc_html($trek['title']); ?></strong> - <?php echo esc_html($trek['region']); ?> region, <?php echo esc_html($trek['difficulty']); ?> difficulty</li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>