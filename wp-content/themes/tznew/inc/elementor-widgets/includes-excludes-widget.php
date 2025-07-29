<?php
/**
 * Includes & Excludes Elementor Widget
 * 
 * Displays what's included and excluded in the tour/trek package
 * with customizable styling and layout options.
 *
 * @package TZnew
 */

if (!defined('ABSPATH')) {
    exit;
}

class TZnew_Includes_Excludes_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'tznew_includes_excludes';
    }
    
    public function get_title() {
        return __('Includes & Excludes', 'tznew');
    }
    
    public function get_icon() {
        return 'eicon-check-circle';
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
            'show_includes',
            [
                'label' => __('Show Includes', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_excludes',
            [
                'label' => __('Show Excludes', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'layout_style',
            [
                'label' => __('Layout Style', 'tznew'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'side-by-side',
                'options' => [
                    'side-by-side' => __('Side by Side', 'tznew'),
                    'stacked' => __('Stacked', 'tznew'),
                    'tabs' => __('Tabs', 'tznew'),
                ],
            ]
        );
        
        $this->add_control(
            'includes_title',
            [
                'label' => __('Includes Title', 'tznew'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('What\'s Included', 'tznew'),
                'condition' => [
                    'show_includes' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'excludes_title',
            [
                'label' => __('Excludes Title', 'tznew'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('What\'s Not Included', 'tznew'),
                'condition' => [
                    'show_excludes' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'includes_icon',
            [
                'label' => __('Includes Icon', 'tznew'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-check-circle',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_includes' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'excludes_icon',
            [
                'label' => __('Excludes Icon', 'tznew'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-times-circle',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_excludes' => 'yes',
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
                    '{{WRAPPER}} .includes-excludes-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .includes-excludes-title',
            ]
        );
        
        $this->add_control(
            'content_color',
            [
                'label' => __('Content Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .includes-excludes-content' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'includes_color',
            [
                'label' => __('Includes Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .includes-section .includes-excludes-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .includes-section .section-icon' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'excludes_color',
            [
                'label' => __('Excludes Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .excludes-section .includes-excludes-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .excludes-section .section-icon' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .includes-excludes-section' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .includes-excludes-section',
            ]
        );
        
        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'tznew'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .includes-excludes-section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'padding',
            [
                'label' => __('Padding', 'tznew'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .includes-excludes-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        
        // Get field names based on post type
        if ($post_type === 'tours') {
            $includes_field = 'inclusion';
            $excludes_field = 'exclusion';
        } else {
            $includes_field = 'includes';
            $excludes_field = 'excludes';
        }
        
        $includes = tznew_get_elementor_field($includes_field, $post_id);
        $excludes = tznew_get_elementor_field($excludes_field, $post_id);
        
        if (!$includes && !$excludes) {
            echo '<p>' . __('No includes/excludes information found.', 'tznew') . '</p>';
            return;
        }
        
        $layout_class = 'layout-' . $settings['layout_style'];
        
        echo '<div class="tznew-includes-excludes ' . esc_attr($layout_class) . '">';
        
        if ($settings['layout_style'] === 'tabs') {
            echo '<div class="includes-excludes-tabs">';
            echo '<div class="tab-buttons">';
            if ($settings['show_includes'] === 'yes' && $includes) {
                echo '<button class="tab-button active" data-tab="includes">' . esc_html($settings['includes_title']) . '</button>';
            }
            if ($settings['show_excludes'] === 'yes' && $excludes) {
                echo '<button class="tab-button" data-tab="excludes">' . esc_html($settings['excludes_title']) . '</button>';
            }
            echo '</div>';
            echo '<div class="tab-content">';
        }
        
        // Includes Section
        if ($settings['show_includes'] === 'yes' && $includes) {
            $tab_class = $settings['layout_style'] === 'tabs' ? 'tab-pane active' : '';
            echo '<div class="includes-excludes-section includes-section ' . esc_attr($tab_class) . '" data-tab-content="includes">';
            
            echo '<h3 class="includes-excludes-title">';
            if (!empty($settings['includes_icon']['value'])) {
                \Elementor\Icons_Manager::render_icon($settings['includes_icon'], ['class' => 'section-icon']);
            }
            echo esc_html($settings['includes_title']) . '</h3>';
            
            echo '<div class="includes-excludes-content">' . wp_kses_post($includes) . '</div>';
            echo '</div>';
        }
        
        // Excludes Section
        if ($settings['show_excludes'] === 'yes' && $excludes) {
            $tab_class = $settings['layout_style'] === 'tabs' ? 'tab-pane' : '';
            echo '<div class="includes-excludes-section excludes-section ' . esc_attr($tab_class) . '" data-tab-content="excludes">';
            
            echo '<h3 class="includes-excludes-title">';
            if (!empty($settings['excludes_icon']['value'])) {
                \Elementor\Icons_Manager::render_icon($settings['excludes_icon'], ['class' => 'section-icon']);
            }
            echo esc_html($settings['excludes_title']) . '</h3>';
            
            echo '<div class="includes-excludes-content">' . wp_kses_post($excludes) . '</div>';
            echo '</div>';
        }
        
        if ($settings['layout_style'] === 'tabs') {
            echo '</div>'; // .tab-content
            echo '</div>'; // .includes-excludes-tabs
        }
        
        echo '</div>'; // .tznew-includes-excludes
        
        // Add CSS and JavaScript
        echo '<style>
        .tznew-includes-excludes .includes-excludes-section {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .tznew-includes-excludes .includes-excludes-title {
            margin-bottom: 15px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .tznew-includes-excludes .section-icon {
            font-size: 1.2em;
        }
        .tznew-includes-excludes .includes-section .includes-excludes-title,
        .tznew-includes-excludes .includes-section .section-icon {
            color: #28a745;
        }
        .tznew-includes-excludes .excludes-section .includes-excludes-title,
        .tznew-includes-excludes .excludes-section .section-icon {
            color: #dc3545;
        }
        
        /* Side by Side Layout */
        .layout-side-by-side {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        @media (max-width: 768px) {
            .layout-side-by-side {
                grid-template-columns: 1fr;
            }
        }
        
        /* Tabs Layout */
        .includes-excludes-tabs .tab-buttons {
            display: flex;
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 20px;
        }
        .includes-excludes-tabs .tab-button {
            background: none;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-weight: bold;
            color: #6c757d;
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .includes-excludes-tabs .tab-button.active {
            color: #007cba;
            border-bottom-color: #007cba;
        }
        .includes-excludes-tabs .tab-pane {
            display: none;
        }
        .includes-excludes-tabs .tab-pane.active {
            display: block;
        }
        </style>';
        
        if ($settings['layout_style'] === 'tabs') {
            echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                const tabButtons = document.querySelectorAll(".tab-button");
                const tabPanes = document.querySelectorAll(".tab-pane");
                
                tabButtons.forEach(button => {
                    button.addEventListener("click", function() {
                        const targetTab = this.getAttribute("data-tab");
                        
                        // Remove active class from all buttons and panes
                        tabButtons.forEach(btn => btn.classList.remove("active"));
                        tabPanes.forEach(pane => pane.classList.remove("active"));
                        
                        // Add active class to clicked button and corresponding pane
                        this.classList.add("active");
                        document.querySelector(`[data-tab-content="${targetTab}"]`).classList.add("active");
                    });
                });
            });
            </script>';
        }
    }
}