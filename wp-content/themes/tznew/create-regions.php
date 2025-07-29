<?php
/**
 * Create Region Terms for Trekking
 * Run this file once to create the basic region terms
 */

// Include WordPress
require_once('../../../wp-load.php');

// Check if we're in WordPress environment
if (!function_exists('wp_insert_term')) {
    die('WordPress not loaded properly');
}

// Define regions to create
$regions = array(
    array(
        'name' => 'Everest',
        'slug' => 'everest',
        'description' => 'Home to the world\'s highest peak, Mount Everest. Experience breathtaking views and challenging treks in the Khumbu region.'
    ),
    array(
        'name' => 'Annapurna',
        'slug' => 'annapurna',
        'description' => 'Famous for its diverse landscapes and the popular Annapurna Circuit. Offers stunning mountain views and rich cultural experiences.'
    ),
    array(
        'name' => 'Langtang',
        'slug' => 'langtang',
        'description' => 'Known as the "Valley of Glaciers". Closest trekking region to Kathmandu with beautiful rhododendron forests and Tamang culture.'
    ),
    array(
        'name' => 'Manaslu',
        'slug' => 'manaslu',
        'description' => 'Off-the-beaten-path trekking around the eighth highest mountain in the world. Remote and pristine mountain wilderness.'
    ),
    array(
        'name' => 'Mustang',
        'slug' => 'mustang',
        'description' => 'The forbidden kingdom with unique Tibetan culture and dramatic desert landscapes in the rain shadow of the Himalayas.'
    )
);

echo "<h2>Creating Region Terms...</h2>";

foreach ($regions as $region) {
    // Check if term already exists
    $existing_term = get_term_by('slug', $region['slug'], 'region');
    
    if ($existing_term) {
        echo "<p>Region '{$region['name']}' already exists (ID: {$existing_term->term_id})</p>";
    } else {
        // Create the term
        $result = wp_insert_term(
            $region['name'],
            'region',
            array(
                'slug' => $region['slug'],
                'description' => $region['description']
            )
        );
        
        if (is_wp_error($result)) {
            echo "<p style='color: red;'>Error creating region '{$region['name']}': " . $result->get_error_message() . "</p>";
        } else {
            echo "<p style='color: green;'>Successfully created region '{$region['name']}' (ID: {$result['term_id']})</p>";
        }
    }
}

// Create difficulty terms
$difficulties = array(
    array(
        'name' => 'Easy',
        'slug' => 'easy',
        'description' => 'Suitable for beginners with basic fitness level. Well-marked trails with minimal technical challenges.'
    ),
    array(
        'name' => 'Moderate',
        'slug' => 'moderate',
        'description' => 'Requires good physical fitness and some trekking experience. May include steep sections and longer days.'
    ),
    array(
        'name' => 'Challenging',
        'slug' => 'challenging',
        'description' => 'Demanding trek requiring excellent fitness and previous high-altitude experience. Technical sections and extreme conditions.'
    ),
    array(
        'name' => 'Strenuous',
        'slug' => 'strenuous',
        'description' => 'Extremely difficult trek for experienced mountaineers only. Requires technical skills and extreme endurance.'
    )
);

echo "<h2>Creating Difficulty Terms...</h2>";

foreach ($difficulties as $difficulty) {
    // Check if term already exists
    $existing_term = get_term_by('slug', $difficulty['slug'], 'difficulty');
    
    if ($existing_term) {
        echo "<p>Difficulty '{$difficulty['name']}' already exists (ID: {$existing_term->term_id})</p>";
    } else {
        // Create the term
        $result = wp_insert_term(
            $difficulty['name'],
            'difficulty',
            array(
                'slug' => $difficulty['slug'],
                'description' => $difficulty['description']
            )
        );
        
        if (is_wp_error($result)) {
            echo "<p style='color: red;'>Error creating difficulty '{$difficulty['name']}': " . $result->get_error_message() . "</p>";
        } else {
            echo "<p style='color: green;'>Successfully created difficulty '{$difficulty['name']}' (ID: {$result['term_id']})</p>";
        }
    }
}

echo "<h2>Current Regions:</h2>";
$all_regions = get_terms(array(
    'taxonomy' => 'region',
    'hide_empty' => false
));

if (!empty($all_regions)) {
    echo "<ul>";
    foreach ($all_regions as $region) {
        echo "<li><strong>{$region->name}</strong> (slug: {$region->slug}) - {$region->description}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No regions found.</p>";
}

echo "<h2>Current Difficulties:</h2>";
$all_difficulties = get_terms(array(
    'taxonomy' => 'difficulty',
    'hide_empty' => false
));

if (!empty($all_difficulties)) {
    echo "<ul>";
    foreach ($all_difficulties as $difficulty) {
        echo "<li><strong>{$difficulty->name}</strong> (slug: {$difficulty->slug}) - {$difficulty->description}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No difficulties found.</p>";
}

echo "<p><a href='/'>← Back to Homepage</a></p>";
echo "<p><a href='sample-data-creator.php'>→ Create Sample Trekking Posts</a></p>";
?>