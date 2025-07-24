<?php
/**
 * Trek Overview Elementor Widget
 * 
 * Displays comprehensive trekking information including name, overview,
 * region, difficulty, duration, max altitude, and best season.
 *
 * @package TZnew
 */

if (!defined('ABSPATH')) {
    exit;
}

class TZnew_Trek_Overview_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'tznew_trek_overview';
    }
    
    public function get_title() {
        return __('Trek Overview', 'tznew');
    }
    
    public function get_icon() {
        return 'eicon-info-box';
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
            'show_trek_name',
            [
                'label' => __('Show Trek Name', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_trek_number',
            [
                'label' => __('Show Trek Number', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_overview',
            [
                'label' => __('Show Overview', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_region',
            [
                'label' => __('Show Region', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_difficulty',
            [
                'label' => __('Show Difficulty', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_duration',
            [
                'label' => __('Show Duration', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_max_altitude',
            [
                'label' => __('Show Max Altitude', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_best_season',
            [
                'label' => __('Show Best Season', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_hashtags',
            [
                'label' => __('Show Hashtags', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
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
            'title_color',
            [
                'label' => __('Title Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .trek-overview-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .trek-overview-title',
            ]
        );
        
        $this->add_control(
            'content_color',
            [
                'label' => __('Content Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .trek-overview-content' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'difficulty_color',
            [
                'label' => __('Difficulty Badge Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .difficulty-badge' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id = !empty($settings['post_id']) ? $settings['post_id'] : get_the_ID();
        
        // Check if it's a trekking post type
        if (get_post_type($post_id) !== 'trekking') {
            echo '<p>' . __('This widget is only available for Trekking post type.', 'tznew') . '</p>';
            return;
        }
        
        echo '<div class="tznew-trek-overview">';
        
        // Trek Name
        if ($settings['show_trek_name'] === 'yes') {
            $trek_name = tznew_get_elementor_field('name_of_the_trek', $post_id);
            if ($trek_name) {
                echo '<h2 class="trek-overview-title">' . esc_html($trek_name) . '</h2>';
            }
        }
        
        // Trek Number
        if ($settings['show_trek_number'] === 'yes') {
            $trek_number = tznew_get_elementor_field('trek_number', $post_id);
            if ($trek_number) {
                echo '<div class="trek-overview-item"><strong>' . __('Trek Number:', 'tznew') . '</strong> <span class="trek-overview-content">' . esc_html($trek_number) . '</span></div>';
            }
        }
        
        // Overview
        if ($settings['show_overview'] === 'yes') {
            $overview = tznew_get_elementor_field('overview', $post_id);
            if ($overview) {
                echo '<div class="trek-overview-item"><strong>' . __('Overview:', 'tznew') . '</strong><div class="trek-overview-content">' . wp_kses_post($overview) . '</div></div>';
            }
        }
        
        // Region
        if ($settings['show_region'] === 'yes') {
            $region = tznew_get_elementor_field('region', $post_id);
            if ($region) {
                echo '<div class="trek-overview-item"><strong>' . __('Region:', 'tznew') . '</strong> <span class="trek-overview-content">' . esc_html($region) . '</span></div>';
            }
        }
        
        // Difficulty
        if ($settings['show_difficulty'] === 'yes') {
            $difficulty = tznew_get_elementor_field('difficulty', $post_id);
            if ($difficulty) {
                $difficulty_class = 'difficulty-' . strtolower(str_replace(' ', '-', $difficulty));
                echo '<div class="trek-overview-item"><strong>' . __('Difficulty:', 'tznew') . '</strong> <span class="difficulty-badge ' . esc_attr($difficulty_class) . '">' . esc_html($difficulty) . '</span></div>';
            }
        }
        
        // Duration
        if ($settings['show_duration'] === 'yes') {
            $duration = tznew_get_elementor_field('duration', $post_id);
            if ($duration) {
                echo '<div class="trek-overview-item"><strong>' . __('Duration:', 'tznew') . '</strong> <span class="trek-overview-content">' . esc_html($duration) . '</span></div>';
            }
        }
        
        // Max Altitude
        if ($settings['show_max_altitude'] === 'yes') {
            $max_altitude = tznew_get_elementor_field('max_altitude', $post_id);
            if ($max_altitude) {
                echo '<div class="trek-overview-item"><strong>' . __('Max Altitude:', 'tznew') . '</strong> <span class="trek-overview-content">' . esc_html($max_altitude) . '</span></div>';
            }
        }
        
        // Best Season
        if ($settings['show_best_season'] === 'yes') {
            $best_season = tznew_get_elementor_field('best_season', $post_id);
            if ($best_season) {
                echo '<div class="trek-overview-item"><strong>' . __('Best Season:', 'tznew') . '</strong> <span class="trek-overview-content">' . esc_html($best_season) . '</span></div>';
            }
        }
        
        // Hashtags
        if ($settings['show_hashtags'] === 'yes') {
            $hashtags = tznew_get_elementor_field('hashtags', $post_id);
            if ($hashtags) {
                echo '<div class="trek-overview-item"><strong>' . __('Tags:', 'tznew') . '</strong> <span class="trek-overview-content hashtags">' . esc_html($hashtags) . '</span></div>';
            }
        }
        
        echo '</div>';
        
        // Add some basic CSS
        echo '<style>
        .difficulty-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
            font-weight: bold;
            color: white;
        }
        .difficulty-easy { background-color: #28a745; }
        .difficulty-moderate { background-color: #ffc107; color: #212529; }
        .difficulty-challenging { background-color: #fd7e14; }
        .difficulty-strenuous { background-color: #dc3545; }
        .hashtags { font-style: italic; color: #6c757d; }
        .trek-overview-item { margin-bottom: 10px; }
        </style>';
    }
}