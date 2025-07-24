<?php
/**
 * ACF Integration for TZnew Theme
 *
 * @package TZnew
 * @author Santosh Baral
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include database optimization module
require_once get_template_directory() . '/inc/acf-database-optimization.php';

/**
 * Check if ACF Pro is active
 */
function tznew_is_acf_active() {
    return class_exists('ACF');
}

/**
 * Create ACF JSON directory
 */
function tznew_create_acf_json_dir() {
    $dir = TZNEW_THEME_DIR . '/acf-json';
    if (!file_exists($dir)) {
        wp_mkdir_p($dir);
    }
}
add_action('after_setup_theme', 'tznew_create_acf_json_dir');

/**
 * Register ACF fields programmatically if they don't exist
 * This is a fallback in case the JSON import doesn't work
 */
function tznew_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    // Ensure ACF is fully loaded
    if (!class_exists('ACF')) {
        return;
    }
    
    // Trekking Fields
    acf_add_local_field_group(array(
        'key' => 'group_trekking_fields',
        'title' => 'Trekking Fields',
        'fields' => array(
            array(
                'key' => 'field_trek_name',
                'label' => 'Name of the Trek',
                'name' => 'trek_name',
                'type' => 'text',
            ),
            array(
                'key' => 'field_trek_number',
                'label' => 'Trek Number',
                'name' => 'trek_number',
                'type' => 'text',
                'readonly' => true,
            ),
            array(
                'key' => 'field_overview',
                'label' => 'Overview',
                'name' => 'overview',
                'type' => 'wysiwyg',
            ),
            array(
                'key' => 'field_region',
                'label' => 'Region',
                'name' => 'region',
                'type' => 'select',
                'choices' => array(
                    'Annapurna' => 'Annapurna',
                    'Everest' => 'Everest',
                    'Langtang' => 'Langtang',
                    'Manaslu' => 'Manaslu',
                    'Dolpo' => 'Dolpo',
                    'Kanchenjunga' => 'Kanchenjunga',
                ),
                'ui' => true,
            ),
            array(
                'key' => 'field_difficulty',
                'label' => 'Difficulty',
                'name' => 'difficulty',
                'type' => 'select',
                'choices' => array(
                    'Easy' => 'Easy',
                    'Moderate' => 'Moderate',
                    'Challenging' => 'Challenging',
                    'Strenuous' => 'Strenuous',
                ),
                'ui' => true,
            ),
            array(
                'key' => 'field_season',
                'label' => 'Best Season',
                'name' => 'best_season',
                'type' => 'text',
            ),
            array(
                'key' => 'field_trek_duration',
                'label' => 'Duration',
                'name' => 'duration',
                'type' => 'number',
                'instructions' => 'Enter the duration in days',
                'min' => 1,
                'step' => 1,
            ),
            array(
                'key' => 'field_max_altitude',
                'label' => 'Max Altitude',
                'name' => 'max_altitude',
                'type' => 'number',
                'instructions' => 'Enter the maximum altitude in meters',
                'min' => 0,
                'step' => 1,
            ),
            array(
                'key' => 'field_permits',
                'label' => 'Permits & Regulations',
                'name' => 'permits',
                'type' => 'group',
                'sub_fields' => array(
                    array(
                        'key' => 'field_permit_options',
                        'label' => 'Permits',
                        'name' => 'permit_options',
                        'type' => 'checkbox',
                        'choices' => array(
                            'TIMS' => 'TIMS',
                            'ACAP' => 'ACAP',
                            'RAP' => 'RAP',
                        ),
                        'ui' => true,
                    ),
                    array(
                        'key' => 'field_guide',
                        'label' => 'Guide Requirement',
                        'name' => 'guide_requirement',
                        'type' => 'radio',
                        'choices' => array(
                            'Mandatory' => 'Mandatory',
                            'Optional' => 'Optional',
                        ),
                        'ui' => true,
                    ),
                    array(
                        'key' => 'field_restricted',
                        'label' => 'Restricted Area',
                        'name' => 'restricted_area',
                        'type' => 'true_false',
                        'ui' => true,
                    ),
                ),
            ),
            array(
                'key' => 'field_tour_cost_info',
                'label' => 'Cost Information',
                'name' => 'cost_info',
                'type' => 'group',
                'sub_fields' => array(
                    array(
                        'key' => 'field_tour_price_usd',
                        'label' => 'Price USD',
                        'name' => 'price_usd',
                        'type' => 'number',
                    ),
                    array(
                        'key' => 'field_tour_price_type',
                        'label' => 'Pricing Type',
                        'name' => 'pricing_type',
                        'type' => 'select',
                        'choices' => array(
                            'Single' => 'Single',
                            'Double' => 'Double',
                            'Group' => 'Group',
                        ),
                        'ui' => true,
                    ),
                ),
            ),
            array(
                'key' => 'field_tour_inclusion',
                'label' => 'Inclusion',
                'name' => 'inclusion',
                'type' => 'wysiwyg',
            ),
            array(
                'key' => 'field_tour_exclusion',
                'label' => 'Exclusion',
                'name' => 'exclusion',
                'type' => 'wysiwyg',
            ),
            array(
                'key' => 'field_hashtags',
                'label' => 'Hashtags',
                'name' => 'hashtags',
                'type' => 'text',
                'instructions' => 'Use format like #trek #hiking. Separate with spaces.',
            ),
            array(
                'key' => 'field_discount',
                'label' => 'Group Discount',
                'name' => 'group_discount',
                'type' => 'text',
            ),
            array(
                'key' => 'field_itinerary',
                'label' => 'Itinerary Details',
                'name' => 'itinerary',
                'type' => 'repeater',
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_day_title',
                        'label' => 'Day Title',
                        'name' => 'title',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_day_description',
                        'label' => 'Day Description',
                        'name' => 'description',
                        'type' => 'wysiwyg',
                    ),
                    array(
                        'key' => 'field_place_name',
                        'label' => 'Place Name',
                        'name' => 'place_name',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_altitude',
                        'label' => 'Altitude (m)',
                        'name' => 'altitude',
                        'type' => 'number',
                    ),
                    array(
                        'key' => 'field_transportation',
                        'label' => 'Transportation',
                        'name' => 'transportation',
                        'type' => 'select',
                        'choices' => array(
                            'walking' => 'Walking',
                            'bus' => 'Bus',
                            'car' => 'Car',
                            'jeep' => 'Jeep',
                            'van' => 'Van',
                            'motorbike' => 'Motorbike',
                            'flight' => 'Flight',
                            'helicopter' => 'Helicopter',
                            'rest_day' => 'Rest Day',
                            'acclimatization' => 'Acclimatization',
                        ),
                        'default_value' => 'walking',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 1,
                        'return_format' => 'value',
                        'instructions' => 'Select the primary mode of transportation for this day',
                    ),
                    array(
                        'key' => 'field_walking_time',
                        'label' => 'Walking Time',
                        'name' => 'walking_time',
                        'type' => 'text',
                        'instructions' => 'e.g., 5-6 hours',
                    ),
                    array(
                        'key' => 'field_distance',
                        'label' => 'Distance (km)',
                        'name' => 'distance',
                        'type' => 'number',
                        'step' => 0.1,
                    ),
                    array(
                        'key' => 'field_accommodation',
                        'label' => 'Accommodation',
                        'name' => 'accommodation',
                        'type' => 'text',
                        'instructions' => 'e.g., Tea House, Lodge, Hotel',
                    ),
                    array(
                        'key' => 'field_meals',
                        'label' => 'Meals',
                        'name' => 'meals',
                        'type' => 'text',
                        'instructions' => 'e.g., B+L+D, B+L, etc.',
                    ),
                    array(
                        'key' => 'field_day_images',
                        'label' => 'Images',
                        'name' => 'images',
                        'type' => 'gallery',
                        'max' => 5,
                    ),
                    array(
                        'key' => 'field_day_videos',
                        'label' => 'Video URLs',
                        'name' => 'video_urls',
                        'type' => 'repeater',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_video_url',
                                'label' => 'Video URL',
                                'name' => 'video_url',
                                'type' => 'url',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_coordinates',
                        'label' => 'Coordinates',
                        'name' => 'coordinates',
                        'type' => 'group',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_latitude',
                                'label' => 'Latitude',
                                'name' => 'latitude',
                                'type' => 'text',
                            ),
                            array(
                                'key' => 'field_longitude',
                                'label' => 'Longitude',
                                'name' => 'longitude',
                                'type' => 'text',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_recommendation',
                        'label' => 'Recommendation',
                        'name' => 'recommendation',
                        'type' => 'wysiwyg',
                    ),
                ),
            ),
            array(
                'key' => 'field_trekking_includes',
                'label' => 'Includes',
                'name' => 'includes',
                'type' => 'wysiwyg',
                'instructions' => 'Enter what is included in the package',
                'required' => 0,
            ),
            array(
                'key' => 'field_trekking_excludes',
                'label' => 'Excludes',
                'name' => 'excludes',
                'type' => 'wysiwyg',
                'instructions' => 'Enter what is excluded from the package',
                'required' => 0,
            ),
            array(
                'key' => 'field_trekking_meta_title',
                'label' => 'Meta Title',
                'name' => 'meta_title',
                'type' => 'text',
                'instructions' => 'Enter the meta title for SEO',
                'required' => 0,
            ),
            array(
                'key' => 'field_trekking_meta_description',
                'label' => 'Meta Description',
                'name' => 'meta_description',
                'type' => 'textarea',
                'instructions' => 'Enter the meta description for SEO',
                'required' => 0,
            ),
            array(
                'key' => 'field_trekking_featured',
                'label' => 'Featured Trek',
                'name' => 'featured',
                'type' => 'true_false',
                'instructions' => 'Mark this trek as featured to highlight it on the website',
                'required' => 0,
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Yes',
                'ui_off_text' => 'No',
            ),
            array(
                'key' => 'field_trekking_popular',
                'label' => 'Popular Trek',
                'name' => 'popular',
                'type' => 'true_false',
                'instructions' => 'Mark this trek as popular to show it in popular sections',
                'required' => 0,
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Yes',
                'ui_off_text' => 'No',
            ),
            array(
                'key' => 'field_trekking_rating',
                'label' => 'Rating',
                'name' => 'rating',
                'type' => 'number',
                'instructions' => 'Enter the rating for this trek (e.g., 4.8)',
                'required' => 0,
                'default_value' => 4.8,
                'min' => 1,
                'max' => 5,
                'step' => 0.1,
            ),
            array(
                'key' => 'field_trekking_reviews_count',
                'label' => 'Reviews Count',
                'name' => 'reviews_count',
                'type' => 'number',
                'instructions' => 'Enter the number of reviews for this trek',
                'required' => 0,
                'default_value' => 0,
                'min' => 0,
                'step' => 1,
                'ui_off_text' => 'No',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'trekking',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(
            'excerpt',
            'discussion',
            'comments',
            'revisions',
            'slug',
            'author',
            'format',
            'page_attributes',
            'categories',
            'tags',
            'send-trackbacks',
        ),
    ));
    
    // Tours Fields
    acf_add_local_field_group(array(
        'key' => 'group_tour_fields',
        'title' => 'Tour Fields',
        'fields' => array(
            array(
                'key' => 'field_tour_name',
                'label' => 'Name of the Tour',
                'name' => 'tour_name',
                'type' => 'text',
            ),
            array(
                'key' => 'field_tour_number',
                'label' => 'Tour Number',
                'name' => 'tour_number',
                'type' => 'text',
                'readonly' => true,
            ),
            array(
                'key' => 'field_tour_overview',
                'label' => 'Overview',
                'name' => 'overview',
                'type' => 'wysiwyg',
            ),
            array(
                'key' => 'field_places_covered',
                'label' => 'Places Covered',
                'name' => 'places_covered',
                'type' => 'checkbox',
                'choices' => array(
                    'Kathmandu' => 'Kathmandu',
                    'Pokhara' => 'Pokhara',
                    'Chitwan' => 'Chitwan',
                    'Lumbini' => 'Lumbini',
                    'Mustang' => 'Mustang',
                    'Everest' => 'Everest',
                    'Annapurna' => 'Annapurna',
                    'Nepal' => 'Nepal',
                    'India' => 'India',
                    'Tibet' => 'Tibet',
                    'Bhutan' => 'Bhutan',
                ),
                'ui' => true,
            ),
            array(
                'key' => 'field_tour_type',
                'label' => 'Type',
                'name' => 'tour_type',
                'type' => 'select',
                'choices' => array(
                    'Luxury' => 'Luxury',
                    'Premium' => 'Premium',
                    'Deluxe' => 'Deluxe',
                    'Economy' => 'Economy',
                ),
                'ui' => true,
            ),
            array(
                'key' => 'field_tour_season',
                'label' => 'Best Season',
                'name' => 'best_season',
                'type' => 'text',
            ),
            array(
                'key' => 'field_tour_duration',
                'label' => 'Duration',
                'name' => 'duration',
                'type' => 'number',
                'instructions' => 'Enter the duration in days',
                'min' => 1,
                'step' => 1,
            ),
            array(
                'key' => 'field_group_size',
                'label' => 'Group Size',
                'name' => 'group_size',
                'type' => 'text',
                'instructions' => 'Enter the recommended group size (e.g., "2-10 people")',
            ),
            array(
                'key' => 'field_languages',
                'label' => 'Languages',
                'name' => 'languages',
                'type' => 'text',
                'instructions' => 'Enter the languages available (e.g., "English, Nepali")',
            ),
            array(
                'key' => 'field_tour_permits',
                'label' => 'Permits & Regulations',
                'name' => 'permits',
                'type' => 'group',
                'sub_fields' => array(
                    array(
                        'key' => 'field_tour_permit_options',
                        'label' => 'Permits',
                        'name' => 'permit_options',
                        'type' => 'checkbox',
                        'choices' => array(
                            'TIMS' => 'TIMS',
                            'ACAP' => 'ACAP',
                            'RAP' => 'RAP',
                        ),
                        'ui' => true,
                    ),
                    array(
                        'key' => 'field_tour_guide',
                        'label' => 'Guide Requirement',
                        'name' => 'guide_requirement',
                        'type' => 'radio',
                        'choices' => array(
                            'Mandatory' => 'Mandatory',
                            'Optional' => 'Optional',
                        ),
                        'ui' => true,
                    ),
                    array(
                        'key' => 'field_tour_restricted',
                        'label' => 'Restricted Area',
                        'name' => 'restricted_area',
                        'type' => 'true_false',
                        'ui' => true,
                    ),
                ),
            ),
            array(
                'key' => 'field_cost_info',
                'label' => 'Cost Information',
                'name' => 'cost_info',
                'type' => 'group',
                'sub_fields' => array(
                    array(
                        'key' => 'field_price_usd',
                        'label' => 'Price USD',
                        'name' => 'price_usd',
                        'type' => 'number',
                    ),
                    array(
                        'key' => 'field_price_type',
                        'label' => 'Pricing Type',
                        'name' => 'pricing_type',
                        'type' => 'select',
                        'choices' => array(
                            'Single' => 'Single',
                            'Double' => 'Double',
                            'Group' => 'Group',
                        ),
                        'ui' => true,
                    ),
                ),
            ),
            array(
                'key' => 'field_inclusion',
                'label' => 'Inclusion',
                'name' => 'inclusion',
                'type' => 'wysiwyg',
            ),
            array(
                'key' => 'field_exclusion',
                'label' => 'Exclusion',
                'name' => 'exclusion',
                'type' => 'wysiwyg',
            ),
            array(
                'key' => 'field_group_discount',
                'label' => 'Group Discount',
                'name' => 'group_discount',
                'type' => 'text',
            ),
            array(
                'key' => 'field_tour_itinerary',
                'label' => 'Itinerary Details',
                'name' => 'itinerary',
                'type' => 'repeater',
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_tour_day_title',
                        'label' => 'Day Title',
                        'name' => 'title',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_tour_day_description',
                        'label' => 'Day Description',
                        'name' => 'description',
                        'type' => 'wysiwyg',
                    ),
                    array(
                        'key' => 'field_tour_place_name',
                        'label' => 'Place Name',
                        'name' => 'place_name',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_tour_altitude',
                        'label' => 'Altitude (m)',
                        'name' => 'altitude',
                        'type' => 'number',
                    ),
                    array(
                        'key' => 'field_tour_transportation',
                        'label' => 'Transportation',
                        'name' => 'transportation',
                        'type' => 'select',
                        'choices' => array(
                            'walking' => 'Walking',
                            'bus' => 'Bus',
                            'car' => 'Car',
                            'jeep' => 'Jeep',
                            'van' => 'Van',
                            'motorbike' => 'Motorbike',
                            'flight' => 'Flight',
                            'helicopter' => 'Helicopter',
                            'rest_day' => 'Rest Day',
                            'acclimatization' => 'Acclimatization',
                        ),
                        'default_value' => 'walking',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 1,
                        'return_format' => 'value',
                        'instructions' => 'Select the primary mode of transportation for this day',
                    ),
                    array(
                        'key' => 'field_tour_walking_time',
                        'label' => 'Walking Time',
                        'name' => 'walking_time',
                        'type' => 'text',
                        'instructions' => 'e.g., 5-6 hours',
                    ),
                    array(
                        'key' => 'field_tour_distance',
                        'label' => 'Distance (km)',
                        'name' => 'distance',
                        'type' => 'number',
                        'step' => 0.1,
                    ),
                    array(
                        'key' => 'field_tour_accommodation',
                        'label' => 'Accommodation',
                        'name' => 'accommodation',
                        'type' => 'text',
                        'instructions' => 'e.g., Hotel, Resort, Lodge',
                    ),
                    array(
                        'key' => 'field_tour_meals',
                        'label' => 'Meals',
                        'name' => 'meals',
                        'type' => 'text',
                        'instructions' => 'e.g., B+L+D, B+L, etc.',
                    ),
                    array(
                        'key' => 'field_tour_day_images',
                        'label' => 'Images',
                        'name' => 'images',
                        'type' => 'gallery',
                        'max' => 5,
                    ),
                    array(
                        'key' => 'field_tour_day_videos',
                        'label' => 'Video URLs',
                        'name' => 'video_urls',
                        'type' => 'repeater',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_tour_video_url_item',
                                'label' => 'Video URL',
                                'name' => 'video_url',
                                'type' => 'url',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_tour_coordinates_group',
                        'label' => 'Coordinates',
                        'name' => 'coordinates',
                        'type' => 'group',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_tour_latitude_coord',
                                'label' => 'Latitude',
                                'name' => 'latitude',
                                'type' => 'text',
                            ),
                            array(
                                'key' => 'field_tour_longitude_coord',
                                'label' => 'Longitude',
                                'name' => 'longitude',
                                'type' => 'text',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_tour_recommendation',
                        'label' => 'Recommendation',
                        'name' => 'recommendation',
                        'type' => 'wysiwyg',
                    ),
                ),
            ),
            array(
                'key' => 'field_tour_featured',
                'label' => 'Featured Tour',
                'name' => 'featured',
                'type' => 'true_false',
                'instructions' => 'Mark this tour as featured to highlight it on the website',
                'required' => 0,
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Yes',
                'ui_off_text' => 'No',
            ),
            array(
                'key' => 'field_tour_popular',
                'label' => 'Popular Tour',
                'name' => 'popular',
                'type' => 'true_false',
                'instructions' => 'Mark this tour as popular to show it in popular sections',
                'required' => 0,
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Yes',
                'ui_off_text' => 'No',
            ),
            array(
                'key' => 'field_tour_rating',
                'label' => 'Rating',
                'name' => 'rating',
                'type' => 'number',
                'instructions' => 'Enter the rating for this tour (e.g., 4.8)',
                'required' => 0,
                'default_value' => 4.8,
                'min' => 1,
                'max' => 5,
                'step' => 0.1,
            ),
            array(
                'key' => 'field_tour_reviews_count',
                'label' => 'Reviews Count',
                'name' => 'reviews_count',
                'type' => 'number',
                'instructions' => 'Enter the number of reviews for this tour',
                'required' => 0,
                'default_value' => 0,
                'min' => 0,
                'step' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'tours',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(
            'excerpt',
            'discussion',
            'comments',
            'revisions',
            'slug',
            'author',
            'format',
            'page_attributes',
            'categories',
            'tags',
            'send-trackbacks',
        ),
    ));
    
    // FAQ - Single Question & Answer Structure
    acf_add_local_field_group(array(
        'key' => 'group_faq',
        'title' => 'FAQ',
        'fields' => array(
            array(
                'key' => 'field_faq_question',
                'label' => 'Question',
                'name' => 'faq_question',
                'type' => 'text',
                'instructions' => 'Enter one question for this FAQ entry',
                'required' => 1,
            ),
            array(
                'key' => 'field_faq_answer',
                'label' => 'Answer',
                'name' => 'faq_answer',
                'type' => 'wysiwyg',
                'instructions' => 'Enter the answer for this FAQ entry',
                'required' => 1,
                'toolbar' => 'basic',
                'media_upload' => 0,
            ),
            array(
                'key' => 'field_faq_category',
                'label' => 'Category',
                'name' => 'faq_category',
                'type' => 'taxonomy',
                'instructions' => 'Select FAQ category (optional)',
                'required' => 0,
                'taxonomy' => 'faq_category',
                'field_type' => 'select',
                'allow_null' => 1,
                'add_term' => 1,
                'save_terms' => 1,
                'load_terms' => 1,
                'return_format' => 'id',
                'multiple' => 0,
            ),
            array(
                'key' => 'field_faq_applicable_to',
                'label' => 'Applicable To',
                'name' => 'faq_applicable_to',
                'type' => 'checkbox',
                'instructions' => 'Select which post types this FAQ applies to',
                'required' => 0,
                'choices' => array(
                    'trekking' => 'Trekking',
                    'tours' => 'Tours',
                    'general' => 'General (All)',
                ),
                'default_value' => array('general'),
                'layout' => 'vertical',
                'toggle' => 0,
                'return_format' => 'value',
            ),
            array(
                'key' => 'field_faq_order',
                'label' => 'Display Order',
                'name' => 'faq_order',
                'type' => 'number',
                'instructions' => 'Enter a number to control the display order (lower numbers appear first)',
                'required' => 0,
                'default_value' => 10,
                'min' => 1,
                'max' => 999,
                'step' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'faq',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(
            'excerpt',
            'discussion',
            'comments',
            'revisions',
            'slug',
            'author',
            'format',
            'page_attributes',
            'categories',
            'tags',
            'send-trackbacks',
        ),
    ));
    
    // FAQ Selection Fields for Tours and Trekking
    acf_add_local_field_group(array(
        'key' => 'group_faq_selection',
        'title' => 'FAQ Selection',
        'fields' => array(
            array(
                'key' => 'field_selected_faqs',
                'label' => 'Select FAQs',
                'name' => 'selected_faqs',
                'type' => 'post_object',
                'instructions' => 'Select FAQs to display for this tour/trek',
                'required' => 0,
                'post_type' => array('faq'),
                'taxonomy' => '',
                'allow_null' => 1,
                'multiple' => 1,
                'return_format' => 'object',
                'ui' => 1,
            ),
            array(
                'key' => 'field_show_general_faqs',
                'label' => 'Show General FAQs',
                'name' => 'show_general_faqs',
                'type' => 'true_false',
                'instructions' => 'Also display general FAQs applicable to all tours/treks',
                'required' => 0,
                'default_value' => 1,
                'ui' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'tours',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'trekking',
                ),
            ),
        ),
        'menu_order' => 20,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(),
    ));
    
    // Blog Fields
    acf_add_local_field_group(array(
        'key' => 'group_blog_fields',
        'title' => 'Blog Fields',
        'fields' => array(
            array(
                'key' => 'field_blog_title',
                'label' => 'Title',
                'name' => 'blog_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_blog_details',
                'label' => 'Details',
                'name' => 'blog_details',
                'type' => 'wysiwyg',
            ),
            array(
                'key' => 'field_meta_keywords',
                'label' => 'Meta Keywords',
                'name' => 'meta_keywords',
                'type' => 'text',
                'instructions' => 'Comma-separated keywords.',
            ),
            array(
                'key' => 'field_meta_description',
                'label' => 'Meta Description',
                'name' => 'meta_description',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_blog_hashtags',
                'label' => 'Hashtags',
                'name' => 'hashtags',
                'type' => 'text',
                'instructions' => 'Use # to tag topics, separated by spaces.',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'blog',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(
            'excerpt',
            'discussion',
            'comments',
            'revisions',
            'slug',
            'author',
            'format',
            'page_attributes',
            'categories',
            'tags',
            'send-trackbacks',
        ),
    ));
}
add_action('acf/init', 'tznew_register_acf_fields');

/**
 * Register ACF fields for Region taxonomy
 */
function tznew_register_region_taxonomy_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    // Region Taxonomy Fields
    acf_add_local_field_group(array(
        'key' => 'group_region_fields',
        'title' => 'Region Details',
        'fields' => array(
            array(
                'key' => 'field_region_coordinates',
                'label' => 'Region Coordinates',
                'name' => 'region_coordinates',
                'type' => 'group',
                'instructions' => 'Enter the central coordinates for this region',
                'sub_fields' => array(
                    array(
                        'key' => 'field_region_latitude',
                        'label' => 'Latitude',
                        'name' => 'latitude',
                        'type' => 'text',
                        'instructions' => 'Enter latitude (e.g., 28.0000)',
                        'required' => 1,
                        'placeholder' => '28.0000',
                    ),
                    array(
                        'key' => 'field_region_longitude',
                        'label' => 'Longitude',
                        'name' => 'longitude',
                        'type' => 'text',
                        'instructions' => 'Enter longitude (e.g., 85.0000)',
                        'required' => 1,
                        'placeholder' => '85.0000',
                    ),
                ),
            ),
            array(
                'key' => 'field_region_show_on_map',
                'label' => 'Show on Regional Adventure Map',
                'name' => 'show_on_map',
                'type' => 'true_false',
                'instructions' => 'Toggle to show/hide this region polygon on the front page map',
                'required' => 0,
                'default_value' => 1,
                'ui' => 1,
                'ui_on_text' => 'Show',
                'ui_off_text' => 'Hide',
            ),
            array(
                'key' => 'field_region_description',
                'label' => 'Region Description',
                'name' => 'region_description',
                'type' => 'textarea',
                'instructions' => 'Brief description of this region for map tooltips',
                'required' => 0,
                'rows' => 3,
            ),
            array(
                'key' => 'field_region_color',
                'label' => 'Map Polygon Color',
                'name' => 'polygon_color',
                'type' => 'color_picker',
                'instructions' => 'Choose color for this region\'s polygon on the map',
                'required' => 0,
                'default_value' => '#3388ff',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'region',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    ));
}
add_action('acf/init', 'tznew_register_region_taxonomy_fields');

/**
 * Helper function to get ACF field and handle errors
 */
function tznew_get_field($field_name, $post_id = false, $format_value = true) {
    if (!function_exists('get_field')) {
        return false;
    }
    
    return get_field($field_name, $post_id, $format_value);
}

/**
 * Helper function to check if ACF repeater has rows
 */
function tznew_have_rows($field_name, $post_id = false) {
    if (!function_exists('have_rows')) {
        return false;
    }
    
    return have_rows($field_name, $post_id);
}

/**
 * Helper function to process hashtags
 */
function tznew_process_hashtags($post_id) {
    if (!function_exists('get_field')) {
        return;
    }
    
    $hashtags = tznew_get_field_safe('hashtags', $post_id);
    
    if (!empty($hashtags)) {
        $tags = explode(',', $hashtags);
        $tag_ids = array();
        
        foreach ($tags as $tag) {
            $tag = trim($tag);
            if (!empty($tag)) {
                $term = term_exists($tag, 'acf_tag');
                
                if (!$term) {
                    $term = wp_insert_term($tag, 'acf_tag');
                }
                
                if (!is_wp_error($term)) {
                    $tag_ids[] = (int) $term['term_id'];
                }
            }
        }
        
        if (!empty($tag_ids)) {
            wp_set_object_terms($post_id, $tag_ids, 'acf_tag');
        }
    }
}
add_action('acf/save_post', 'tznew_process_hashtags', 20);

/**
 * Add meta tags to head
 */
function tznew_add_meta_tags() {
    global $post;
    
    if (!is_singular() || !$post) {
        return;
    }
    
    $meta_title = '';
    $meta_description = '';
    $meta_keywords = '';
    
    if (is_singular('trekking')) {
        $meta_title = tznew_get_field('meta_title', $post->ID);
        $meta_description = tznew_get_field('meta_description', $post->ID);
    } elseif (is_singular('tours')) {
        $meta_title = tznew_get_field('meta_title', $post->ID);
        $meta_description = tznew_get_field('meta_description', $post->ID);
    } elseif (is_singular('blog')) {
        $meta_title = get_the_title($post->ID);
        $meta_description = tznew_get_field('meta_description', $post->ID);
        $meta_keywords = tznew_get_field('meta_keywords', $post->ID);
    }
    
    if (!empty($meta_title)) {
        echo '<meta name="title" content="' . esc_attr($meta_title) . '" />' . "\n";
    }
    
    if (!empty($meta_description)) {
        echo '<meta name="description" content="' . esc_attr($meta_description) . '" />' . "\n";
    }
    
    if (!empty($meta_keywords)) {
        echo '<meta name="keywords" content="' . esc_attr($meta_keywords) . '" />' . "\n";
    }
}
add_action('wp_head', 'tznew_add_meta_tags');

/**
 * Add schema markup
 */
function tznew_add_schema_markup() {
    global $post;
    
    if (!is_singular() || !$post) {
        return;
    }
    
    $schema = array();
    
    if (is_singular('trekking')) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'TouristAttraction',
            'name' => get_the_title($post->ID),
            'description' => tznew_get_field('overview', $post->ID),
            'url' => get_permalink($post->ID),
        );
        
        $region = tznew_get_field('region', $post->ID);
        if (!empty($region)) {
            $schema['address'] = array(
                '@type' => 'PostalAddress',
                'addressRegion' => $region,
                'addressCountry' => 'Nepal'
            );
        }
        
        $gallery = tznew_get_field('gallery', $post->ID);
        if (!empty($gallery)) {
            $schema['image'] = array();
            foreach ($gallery as $image) {
                $schema['image'][] = $image['url'];
            }
        }
    } elseif (is_singular('tours')) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'TouristTrip',
            'name' => get_the_title($post->ID),
            'description' => tznew_get_field('overview', $post->ID),
            'url' => get_permalink($post->ID),
        );
        
        $region = tznew_get_field('region', $post->ID);
        if (!empty($region)) {
            $schema['touristType'] = $region;
        }
        
        $gallery = tznew_get_field('gallery', $post->ID);
        if (!empty($gallery)) {
            $schema['image'] = array();
            foreach ($gallery as $image) {
                $schema['image'][] = $image['url'];
            }
        }
    } elseif (is_singular('blog')) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => get_the_title($post->ID),
            'description' => tznew_get_field('excerpt', $post->ID),
            'url' => get_permalink($post->ID),
            'datePublished' => get_the_date('c', $post->ID),
            'dateModified' => get_the_modified_date('c', $post->ID),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author_meta('display_name', $post->post_author),
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => 'Techzen Corporation',
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '',
                )
            ),
        );
        
        $featured_image = tznew_get_field('featured_image', $post->ID);
        if (!empty($featured_image)) {
            $schema['image'] = $featured_image['url'];
        } elseif (has_post_thumbnail($post->ID)) {
            $schema['image'] = get_the_post_thumbnail_url($post->ID, 'full');
        }
    }
    
    if (!empty($schema)) {
        echo '<script type="application/ld+json">' . json_encode($schema) . '</script>' . "\n";
    }
}
add_action('wp_head', 'tznew_add_schema_markup');

// Include ACF Database Optimization module
require_once get_template_directory() . '/inc/acf-database-optimization.php';

// Include ACF Admin Interface module
require_once get_template_directory() . '/inc/acf-admin-interface.php';