<?php
/**
 * Itinerary Elementor Widget
 * 
 * Displays detailed day-by-day itinerary for tours and trekking
 * including all sub-fields like coordinates, accommodation, meals, etc.
 *
 * @package TZnew
 */

if (!defined('ABSPATH')) {
    exit;
}

class TZnew_Itinerary_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'tznew_itinerary';
    }
    
    public function get_title() {
        return __('Itinerary Details', 'tznew');
    }
    
    public function get_icon() {
        return 'eicon-timeline';
    }
    
    public function get_categories() {
        return ['tznew-tours-trekking'];
    }
    
    protected function _register_controls() {
        
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'tznew'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'post_id',
            [
                'label' => __('Post ID', 'tznew'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'description' => __('Leave empty to use current post', 'tznew'),
            ]
        );
        
        $this->add_control(
            'show_day_title',
            [
                'label' => __('Show Day Title', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_description',
            [
                'label' => __('Show Description', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_place_name',
            [
                'label' => __('Show Place Name', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_altitude',
            [
                'label' => __('Show Altitude', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_transportation',
            [
                'label' => __('Show Transportation', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_walking_time',
            [
                'label' => __('Show Walking Time', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_distance',
            [
                'label' => __('Show Distance', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_accommodation',
            [
                'label' => __('Show Accommodation', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_meals',
            [
                'label' => __('Show Meals', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_images',
            [
                'label' => __('Show Images', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_videos',
            [
                'label' => __('Show Videos', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );
        
        $this->add_control(
            'show_coordinates',
            [
                'label' => __('Show Coordinates', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );
        
        $this->add_control(
            'show_recommendation',
            [
                'label' => __('Show Recommendation', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'tznew'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'day_title_color',
            [
                'label' => __('Day Title Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .itinerary-day-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'day_title_typography',
                'selector' => '{{WRAPPER}} .itinerary-day-title',
            ]
        );
        
        $this->add_control(
            'content_color',
            [
                'label' => __('Content Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .itinerary-content' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'border_color',
            [
                'label' => __('Border Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .itinerary-day' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id = !empty($settings['post_id']) ? $settings['post_id'] : get_the_ID();
        
        // Check if it's a tours or trekking post type
        $post_type = get_post_type($post_id);
        if (!in_array($post_type, ['tours', 'trekking'])) {
            echo '<p>' . __('This widget is only available for Tours and Trekking post types.', 'tznew') . '</p>';
            return;
        }
        
        $itinerary_details = tznew_get_elementor_field('itinerary_details', $post_id);
        
        if (!$itinerary_details || !is_array($itinerary_details)) {
            echo '<p>' . __('No itinerary details found.', 'tznew') . '</p>';
            return;
        }
        
        echo '<div class="tznew-itinerary">';
        
        foreach ($itinerary_details as $index => $day) {
            $day_number = $index + 1;
            echo '<div class="itinerary-day" data-day="' . esc_attr($day_number) . '">';
            
            // Day Title
            if ($settings['show_day_title'] === 'yes' && !empty($day['title'])) {
                echo '<h3 class="itinerary-day-title">Day ' . $day_number . ': ' . esc_html($day['title']) . '</h3>';
            }
            
            echo '<div class="itinerary-details">';
            
            // Place Name
            if ($settings['show_place_name'] === 'yes' && !empty($day['place_name'])) {
                echo '<div class="itinerary-item"><strong>' . __('Place:', 'tznew') . '</strong> <span class="itinerary-content">' . esc_html($day['place_name']) . '</span></div>';
            }
            
            // Altitude
            if ($settings['show_altitude'] === 'yes' && !empty($day['altitude'])) {
                echo '<div class="itinerary-item"><strong>' . __('Altitude:', 'tznew') . '</strong> <span class="itinerary-content">' . esc_html($day['altitude']) . '</span></div>';
            }
            
            // Transportation
            if ($settings['show_transportation'] === 'yes' && !empty($day['transportation'])) {
                echo '<div class="itinerary-item"><strong>' . __('Transportation:', 'tznew') . '</strong> <span class="itinerary-content">' . esc_html($day['transportation']) . '</span></div>';
            }
            
            // Walking Time
            if ($settings['show_walking_time'] === 'yes' && !empty($day['walking_time'])) {
                echo '<div class="itinerary-item"><strong>' . __('Walking Time:', 'tznew') . '</strong> <span class="itinerary-content">' . esc_html($day['walking_time']) . '</span></div>';
            }
            
            // Distance
            if ($settings['show_distance'] === 'yes' && !empty($day['distance'])) {
                echo '<div class="itinerary-item"><strong>' . __('Distance:', 'tznew') . '</strong> <span class="itinerary-content">' . esc_html($day['distance']) . ' km</span></div>';
            }
            
            // Accommodation
            if ($settings['show_accommodation'] === 'yes' && !empty($day['accommodation'])) {
                echo '<div class="itinerary-item"><strong>' . __('Accommodation:', 'tznew') . '</strong> <span class="itinerary-content">' . esc_html($day['accommodation']) . '</span></div>';
            }
            
            // Meals
            if ($settings['show_meals'] === 'yes' && !empty($day['meals'])) {
                echo '<div class="itinerary-item"><strong>' . __('Meals:', 'tznew') . '</strong> <span class="itinerary-content">' . esc_html($day['meals']) . '</span></div>';
            }
            
            // Description
            if ($settings['show_description'] === 'yes' && !empty($day['description'])) {
                echo '<div class="itinerary-item"><strong>' . __('Description:', 'tznew') . '</strong><div class="itinerary-content">' . wp_kses_post($day['description']) . '</div></div>';
            }
            
            // Images
            if ($settings['show_images'] === 'yes' && !empty($day['images']) && is_array($day['images'])) {
                echo '<div class="itinerary-images">';
                foreach ($day['images'] as $image) {
                    if (is_array($image) && !empty($image['url'])) {
                        echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt'] ?? '') . '" class="itinerary-image" />';
                    }
                }
                echo '</div>';
            }
            
            // Videos
            if ($settings['show_videos'] === 'yes' && !empty($day['video_urls']) && is_array($day['video_urls'])) {
                echo '<div class="itinerary-videos">';
                foreach ($day['video_urls'] as $video) {
                    if (!empty($video['video_url'])) {
                        echo '<div class="itinerary-video"><a href="' . esc_url($video['video_url']) . '" target="_blank">' . __('Watch Video', 'tznew') . '</a></div>';
                    }
                }
                echo '</div>';
            }
            
            // Coordinates
            if ($settings['show_coordinates'] === 'yes' && !empty($day['coordinates'])) {
                $lat = $day['coordinates']['latitude'] ?? '';
                $lng = $day['coordinates']['longitude'] ?? '';
                if ($lat && $lng) {
                    echo '<div class="itinerary-item"><strong>' . __('Coordinates:', 'tznew') . '</strong> <span class="itinerary-content">' . esc_html($lat) . ', ' . esc_html($lng) . '</span></div>';
                }
            }
            
            // Recommendation
            if ($settings['show_recommendation'] === 'yes' && !empty($day['recommendation'])) {
                echo '<div class="itinerary-item"><strong>' . __('Recommendation:', 'tznew') . '</strong><div class="itinerary-content recommendation">' . wp_kses_post($day['recommendation']) . '</div></div>';
            }
            
            echo '</div>'; // .itinerary-details
            echo '</div>'; // .itinerary-day
        }
        
        echo '</div>'; // .tznew-itinerary
        
        // Add basic CSS
        echo '<style>
        .tznew-itinerary .itinerary-day {
            border: 1px solid #e0e0e0;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
        }
        .tznew-itinerary .itinerary-day-title {
            margin-bottom: 15px;
            color: #2c3e50;
        }
        .tznew-itinerary .itinerary-item {
            margin-bottom: 8px;
        }
        .tznew-itinerary .itinerary-images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .tznew-itinerary .itinerary-image {
            max-width: 150px;
            height: auto;
            border-radius: 4px;
        }
        .tznew-itinerary .recommendation {
            background-color: #f8f9fa;
            padding: 10px;
            border-left: 4px solid #007cba;
            margin-top: 5px;
        }
        </style>';
    }
}