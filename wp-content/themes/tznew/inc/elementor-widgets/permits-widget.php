<?php
/**
 * Permits & Regulations Elementor Widget
 * 
 * Displays permit requirements, guide requirements,
 * and restricted area information for tours and trekking.
 *
 * @package TZnew
 */

if (!defined('ABSPATH')) {
    exit;
}

class TZnew_Permits_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'tznew_permits';
    }
    
    public function get_title() {
        return __('Permits & Regulations', 'tznew');
    }
    
    public function get_icon() {
        return 'eicon-document-file';
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
            'show_permits',
            [
                'label' => __('Show Permits', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_guide_requirement',
            [
                'label' => __('Show Guide Requirement', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_restricted_area',
            [
                'label' => __('Show Restricted Area', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'layout_style',
            [
                'label' => __('Layout Style', 'tznew'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'list',
                'options' => [
                    'list' => __('List', 'tznew'),
                    'cards' => __('Cards', 'tznew'),
                    'inline' => __('Inline', 'tznew'),
                ],
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
                    '{{WRAPPER}} .permits-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .permits-title',
            ]
        );
        
        $this->add_control(
            'content_color',
            [
                'label' => __('Content Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .permits-content' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'mandatory_color',
            [
                'label' => __('Mandatory Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .guide-mandatory' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'optional_color',
            [
                'label' => __('Optional Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .guide-optional' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'restricted_color',
            [
                'label' => __('Restricted Area Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .restricted-yes' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .permits-container',
                'condition' => [
                    'layout_style' => 'cards',
                ],
            ]
        );
        
        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'tznew'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .permits-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_style' => 'cards',
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
        
        $permits_regulations = tznew_get_elementor_field('permits_regulations', $post_id);
        
        if (!$permits_regulations) {
            echo '<p>' . __('No permits and regulations information found.', 'tznew') . '</p>';
            return;
        }
        
        $layout_class = 'permits-layout-' . $settings['layout_style'];
        
        echo '<div class="tznew-permits ' . esc_attr($layout_class) . '">';
        
        if ($settings['layout_style'] === 'cards') {
            echo '<div class="permits-cards-container">';
        }
        
        // Permits
        if ($settings['show_permits'] === 'yes' && !empty($permits_regulations['permits'])) {
            $permits_class = $settings['layout_style'] === 'cards' ? 'permits-container permits-card' : 'permits-container';
            echo '<div class="' . esc_attr($permits_class) . '">';
            echo '<h4 class="permits-title">' . __('Required Permits', 'tznew') . '</h4>';
            echo '<div class="permits-content">' . wp_kses_post($permits_regulations['permits']) . '</div>';
            echo '</div>';
        }
        
        // Guide Requirement
        if ($settings['show_guide_requirement'] === 'yes' && !empty($permits_regulations['guide_requirement'])) {
            $guide_class = $settings['layout_style'] === 'cards' ? 'permits-container permits-card' : 'permits-container';
            echo '<div class="' . esc_attr($guide_class) . '">';
            echo '<h4 class="permits-title">' . __('Guide Requirement', 'tznew') . '</h4>';
            
            $guide_requirement = $permits_regulations['guide_requirement'];
            $guide_status_class = 'guide-' . strtolower($guide_requirement);
            
            echo '<div class="permits-content ' . esc_attr($guide_status_class) . '">';
            
            if ($guide_requirement === 'Mandatory') {
                echo '<span class="guide-status guide-mandatory">' . __('Mandatory', 'tznew') . '</span>';
                echo '<p>' . __('A licensed guide is required for this trek/tour.', 'tznew') . '</p>';
            } elseif ($guide_requirement === 'Optional') {
                echo '<span class="guide-status guide-optional">' . __('Optional', 'tznew') . '</span>';
                echo '<p>' . __('A guide is recommended but not mandatory.', 'tznew') . '</p>';
            } else {
                echo '<span class="guide-status">' . esc_html($guide_requirement) . '</span>';
            }
            
            echo '</div>';
            echo '</div>';
        }
        
        // Restricted Area
        if ($settings['show_restricted_area'] === 'yes' && !empty($permits_regulations['restricted_area'])) {
            $restricted_class = $settings['layout_style'] === 'cards' ? 'permits-container permits-card' : 'permits-container';
            echo '<div class="' . esc_attr($restricted_class) . '">';
            echo '<h4 class="permits-title">' . __('Restricted Area', 'tznew') . '</h4>';
            
            $restricted_area = $permits_regulations['restricted_area'];
            $restricted_status_class = 'restricted-' . strtolower($restricted_area);
            
            echo '<div class="permits-content ' . esc_attr($restricted_status_class) . '">';
            
            if ($restricted_area === 'Yes') {
                echo '<span class="restricted-status restricted-yes">' . __('Restricted Area', 'tznew') . '</span>';
                echo '<p>' . __('Special permits and regulations apply to this area.', 'tznew') . '</p>';
            } elseif ($restricted_area === 'No') {
                echo '<span class="restricted-status restricted-no">' . __('Open Area', 'tznew') . '</span>';
                echo '<p>' . __('No special restrictions apply to this area.', 'tznew') . '</p>';
            } else {
                echo '<span class="restricted-status">' . esc_html($restricted_area) . '</span>';
            }
            
            echo '</div>';
            echo '</div>';
        }
        
        if ($settings['layout_style'] === 'cards') {
            echo '</div>'; // .permits-cards-container
        }
        
        echo '</div>'; // .tznew-permits
        
        // Add CSS based on layout
        echo '<style>
        .tznew-permits .permits-container {
            margin-bottom: 15px;
        }
        .tznew-permits .permits-title {
            margin-bottom: 10px;
            font-weight: bold;
            color: #2c3e50;
        }
        .tznew-permits .permits-content {
            color: #495057;
        }
        
        /* Cards Layout */
        .permits-layout-cards .permits-cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .permits-layout-cards .permits-card {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
        }
        
        /* Inline Layout */
        .permits-layout-inline .permits-container {
            display: inline-block;
            margin-right: 30px;
            vertical-align: top;
        }
        
        /* Status Colors */
        .guide-mandatory {
            color: #dc3545;
            font-weight: bold;
        }
        .guide-optional {
            color: #28a745;
            font-weight: bold;
        }
        .restricted-yes {
            color: #fd7e14;
            font-weight: bold;
        }
        .restricted-no {
            color: #28a745;
            font-weight: bold;
        }
        .guide-status, .restricted-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
            margin-bottom: 8px;
        }
        </style>';
    }
}