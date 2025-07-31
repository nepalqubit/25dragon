<?php
/**
 * Cost Information Elementor Widget
 * 
 * Displays pricing information including cost details,
 * pricing types, and group discounts for tours and trekking.
 *
 * @package TZnew
 */

if (!defined('ABSPATH')) {
    exit;
}

class TZnew_Cost_Info_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'tznew_cost_info';
    }
    
    public function get_title() {
        return __('Cost Information', 'tznew');
    }
    
    public function get_icon() {
        return 'eicon-price-table';
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
            'show_price',
            [
                'label' => __('Show Price', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_pricing_type',
            [
                'label' => __('Show Pricing Type', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_group_discount',
            [
                'label' => __('Show Group Discount', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'currency_symbol',
            [
                'label' => __('Currency Symbol', 'tznew'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '$',
            ]
        );
        
        $this->add_control(
            'show_currency_before',
            [
                'label' => __('Show Currency Before Price', 'tznew'),
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
            'price_color',
            [
                'label' => __('Price Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cost-price' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .cost-price',
            ]
        );
        
        $this->add_control(
            'label_color',
            [
                'label' => __('Label Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cost-label' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'discount_color',
            [
                'label' => __('Discount Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cost-discount' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cost-info-container' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .cost-info-container',
            ]
        );
        
        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'tznew'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cost-info-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .cost-info-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        
        $cost_information = tznew_get_elementor_field('cost_information', $post_id);
        $group_discount = tznew_get_elementor_field('group_discount', $post_id);
        
        if (!$cost_information && !$group_discount) {
            echo '<p>' . __('No cost information found.', 'tznew') . '</p>';
            return;
        }
        
        $currency = $settings['currency_symbol'];
        $currency_before = $settings['show_currency_before'] === 'yes';
        
        echo '<div class="tznew-cost-info">';
        echo '<div class="cost-info-container">';
        
        // Main Price Information
        if ($settings['show_price'] === 'yes' && $cost_information) {
            $price_usd = $cost_information['price_usd'] ?? '';
            $pricing_type = $cost_information['pricing_type'] ?? '';
            
            if ($price_usd) {
                echo '<div class="cost-item cost-main-price">';
                echo '<span class="cost-label">' . __('Price:', 'tznew') . '</span> ';
                
                $formatted_price = $currency_before ? $currency . $price_usd : $price_usd . ' ' . $currency;
                echo '<span class="cost-price">' . esc_html($formatted_price) . '</span>';
                
                // Show pricing type
                if ($settings['show_pricing_type'] === 'yes' && $pricing_type) {
                    echo ' <span class="cost-pricing-type">(' . esc_html($pricing_type) . ')</span>';
                }
                
                echo '</div>';
            }
        }
        
        // Group Discount
        if ($settings['show_group_discount'] === 'yes' && $group_discount) {
            echo '<div class="cost-item cost-group-discount">';
            echo '<span class="cost-label">' . __('Group Discount:', 'tznew') . '</span> ';
            echo '<span class="cost-discount">' . wp_kses_post($group_discount) . '</span>';
            echo '</div>';
        }
        
        echo '</div>'; // .cost-info-container
        echo '</div>'; // .tznew-cost-info
        
        // Add basic CSS
        echo '<style>
        .tznew-cost-info .cost-info-container {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
        }
        .tznew-cost-info .cost-item {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        .tznew-cost-info .cost-item:last-child {
            margin-bottom: 0;
        }
        .tznew-cost-info .cost-label {
            font-weight: bold;
            color: #495057;
            margin-right: 8px;
        }
        .tznew-cost-info .cost-price {
            font-size: 1.5em;
            font-weight: bold;
            color: #28a745;
        }
        .tznew-cost-info .cost-pricing-type {
            font-size: 0.9em;
            color: #6c757d;
            font-style: italic;
            margin-left: 5px;
        }
        .tznew-cost-info .cost-discount {
            color: #dc3545;
            font-weight: 500;
        }
        .tznew-cost-info .cost-main-price {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        </style>';
    }
}