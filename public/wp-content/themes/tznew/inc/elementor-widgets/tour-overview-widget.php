<?php
/**
 * Tour Overview Elementor Widget
 * 
 * Displays comprehensive tour information including name, overview,
 * places covered, type, duration, group size, and languages.
 *
 * @package TZnew
 */

if (!defined('ABSPATH')) {
    exit;
}

class TZnew_Tour_Overview_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'tznew_tour_overview';
    }
    
    public function get_title() {
        return __('Tour Overview', 'tznew');
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
            'show_tour_name',
            [
                'label' => __('Show Tour Name', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_tour_number',
            [
                'label' => __('Show Tour Number', 'tznew'),
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
            'show_places_covered',
            [
                'label' => __('Show Places Covered', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_tour_type',
            [
                'label' => __('Show Tour Type', 'tznew'),
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
            'show_group_size',
            [
                'label' => __('Show Group Size', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_languages',
            [
                'label' => __('Show Languages', 'tznew'),
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
                    '{{WRAPPER}} .tour-overview-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .tour-overview-title',
            ]
        );
        
        $this->add_control(
            'content_color',
            [
                'label' => __('Content Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tour-overview-content' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id = !empty($settings['post_id']) ? $settings['post_id'] : get_the_ID();
        
        // Check if it's a tours post type
        if (get_post_type($post_id) !== 'tours') {
            echo '<p>' . __('This widget is only available for Tours post type.', 'tznew') . '</p>';
            return;
        }
        
        echo '<div class="tznew-tour-overview">';
        
        // Tour Name
        if ($settings['show_tour_name'] === 'yes') {
            $tour_name = tznew_get_elementor_field('name_of_the_tour', $post_id);
            if ($tour_name) {
                echo '<h2 class="tour-overview-title">' . esc_html($tour_name) . '</h2>';
            }
        }
        
        // Tour Number
        if ($settings['show_tour_number'] === 'yes') {
            $tour_number = tznew_get_elementor_field('tour_number', $post_id);
            if ($tour_number) {
                echo '<div class="tour-overview-item"><strong>' . __('Tour Number:', 'tznew') . '</strong> <span class="tour-overview-content">' . esc_html($tour_number) . '</span></div>';
            }
        }
        
        // Overview
        if ($settings['show_overview'] === 'yes') {
            $overview = tznew_get_elementor_field('overview', $post_id);
            if ($overview) {
                echo '<div class="tour-overview-item"><strong>' . __('Overview:', 'tznew') . '</strong><div class="tour-overview-content">' . wp_kses_post($overview) . '</div></div>';
            }
        }
        
        // Places Covered
        if ($settings['show_places_covered'] === 'yes') {
            $places_covered = tznew_get_elementor_field('places_covered', $post_id);
            if ($places_covered && is_array($places_covered)) {
                echo '<div class="tour-overview-item"><strong>' . __('Places Covered:', 'tznew') . '</strong> <span class="tour-overview-content">' . esc_html(implode(', ', $places_covered)) . '</span></div>';
            }
        }
        
        // Tour Type
        if ($settings['show_tour_type'] === 'yes') {
            $tour_type = tznew_get_elementor_field('type', $post_id);
            if ($tour_type) {
                echo '<div class="tour-overview-item"><strong>' . __('Tour Type:', 'tznew') . '</strong> <span class="tour-overview-content">' . esc_html($tour_type) . '</span></div>';
            }
        }
        
        // Duration
        if ($settings['show_duration'] === 'yes') {
            $duration = tznew_get_elementor_field('duration', $post_id);
            if ($duration) {
                echo '<div class="tour-overview-item"><strong>' . __('Duration:', 'tznew') . '</strong> <span class="tour-overview-content">' . esc_html($duration) . '</span></div>';
            }
        }
        
        // Group Size
        if ($settings['show_group_size'] === 'yes') {
            $group_size = tznew_get_elementor_field('group_size', $post_id);
            if ($group_size) {
                echo '<div class="tour-overview-item"><strong>' . __('Group Size:', 'tznew') . '</strong> <span class="tour-overview-content">' . esc_html($group_size) . '</span></div>';
            }
        }
        
        // Languages
        if ($settings['show_languages'] === 'yes') {
            $languages = tznew_get_elementor_field('languages', $post_id);
            if ($languages) {
                echo '<div class="tour-overview-item"><strong>' . __('Languages:', 'tznew') . '</strong> <span class="tour-overview-content">' . esc_html($languages) . '</span></div>';
            }
        }
        
        // Best Season
        if ($settings['show_best_season'] === 'yes') {
            $best_season = tznew_get_elementor_field('best_season', $post_id);
            if ($best_season) {
                echo '<div class="tour-overview-item"><strong>' . __('Best Season:', 'tznew') . '</strong> <span class="tour-overview-content">' . esc_html($best_season) . '</span></div>';
            }
        }
        
        echo '</div>';
    }
}