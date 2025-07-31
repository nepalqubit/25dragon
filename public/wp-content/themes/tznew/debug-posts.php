<?php
/**
 * Debug script to check existing trekking posts and their associations
 */

// Load WordPress
require_once('../../../wp-load.php');

echo "<h1>Debug: Trekking Posts and Regions</h1>";
echo "<style>body { font-family: Arial, sans-serif; margin: 20px; } table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } th { background-color: #f2f2f2; }</style>";

// Check existing trekking posts
$trekking_posts = get_posts(array(
    'post_type' => 'trekking',
    'post_status' => 'publish',
    'numberposts' => -1
));

echo "<h2>Existing Trekking Posts (" . count($trekking_posts) . ")</h2>";

if (!empty($trekking_posts)) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Title</th><th>Region (Taxonomy)</th><th>Region (ACF)</th><th>Difficulty (Taxonomy)</th><th>Difficulty (ACF)</th></tr>";
    
    foreach ($trekking_posts as $post) {
        $region_terms = get_the_terms($post->ID, 'region');
        $region_taxonomy = $region_terms ? implode(', ', wp_list_pluck($region_terms, 'name')) : 'None';
        
        $difficulty_terms = get_the_terms($post->ID, 'difficulty');
        $difficulty_taxonomy = $difficulty_terms ? implode(', ', wp_list_pluck($difficulty_terms, 'name')) : 'None';
        
        $region_acf = get_field('region', $post->ID) ?: 'None';
        $difficulty_acf = get_field('difficulty', $post->ID) ?: 'None';
        
        echo "<tr>";
        echo "<td>{$post->ID}</td>";
        echo "<td>{$post->post_title}</td>";
        echo "<td>{$region_taxonomy}</td>";
        echo "<td>{$region_acf}</td>";
        echo "<td>{$difficulty_taxonomy}</td>";
        echo "<td>{$difficulty_acf}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No trekking posts found.</p>";
}

// Check existing regions
$regions = get_terms(array(
    'taxonomy' => 'region',
    'hide_empty' => false
));

echo "<h2>Existing Region Terms (" . count($regions) . ")</h2>";
if (!empty($regions)) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Slug</th><th>Count</th><th>Description</th></tr>";
    foreach ($regions as $region) {
        echo "<tr>";
        echo "<td>{$region->term_id}</td>";
        echo "<td>{$region->name}</td>";
        echo "<td>{$region->slug}</td>";
        echo "<td>{$region->count}</td>";
        echo "<td>{$region->description}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No region terms found.</p>";
}

// Check existing difficulties
$difficulties = get_terms(array(
    'taxonomy' => 'difficulty',
    'hide_empty' => false
));

echo "<h2>Existing Difficulty Terms (" . count($difficulties) . ")</h2>";
if (!empty($difficulties)) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Slug</th><th>Count</th><th>Description</th></tr>";
    foreach ($difficulties as $difficulty) {
        echo "<tr>";
        echo "<td>{$difficulty->term_id}</td>";
        echo "<td>{$difficulty->name}</td>";
        echo "<td>{$difficulty->slug}</td>";
        echo "<td>{$difficulty->count}</td>";
        echo "<td>{$difficulty->description}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No difficulty terms found.</p>";
}

echo "<p><a href='/'>← Back to Homepage</a></p>";
echo "<p><a href='create-regions.php'>→ Create Regions & Difficulties</a></p>";
echo "<p><a href='sample-data-creator.php'>→ Create Sample Posts</a></p>";
?>