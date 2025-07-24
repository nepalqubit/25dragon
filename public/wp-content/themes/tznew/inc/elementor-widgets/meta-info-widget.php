<?php
/**
 * Meta Information Elementor Widget
 * 
 * Displays meta information like hashtags, group discount,
 * and other metadata for tours and trekking posts.
 *
 * @package TZnew
 */

if (!defined('ABSPATH')) {
    exit;
}

class TZnew_Meta_Info_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'tznew_meta_info';
    }
    
    public function get_title() {
        return __('Meta Information', 'tznew');
    }
    
    public function get_icon() {
        return 'eicon-info-circle';
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
            'show_hashtags',
            [
                'label' => __('Show Hashtags', 'tznew'),
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
            'show_languages',
            [
                'label' => __('Show Languages', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Only available for tours', 'tznew'),
            ]
        );
        
        $this->add_control(
            'show_group_size',
            [
                'label' => __('Show Group Size', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Only available for tours', 'tznew'),
            ]
        );
        
        $this->add_control(
            'show_meta_title',
            [
                'label' => __('Show Meta Title', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );
        
        $this->add_control(
            'show_meta_description',
            [
                'label' => __('Show Meta Description', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
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
                    'grid' => __('Grid', 'tznew'),
                    'inline' => __('Inline', 'tznew'),
                    'cards' => __('Cards', 'tznew'),
                ],
            ]
        );
        
        $this->add_control(
            'hashtag_prefix',
            [
                'label' => __('Hashtag Prefix', 'tznew'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '#',
                'condition' => [
                    'show_hashtags' => 'yes',
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
            'item_color',
            [
                'label' => __('Text Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meta-info-item' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'item_typography',
                'selector' => '{{WRAPPER}} .meta-info-item',
            ]
        );
        
        $this->add_control(
            'label_color',
            [
                'label' => __('Label Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meta-info-label' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .meta-info-label',
            ]
        );
        
        $this->add_control(
            'hashtag_color',
            [
                'label' => __('Hashtag Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hashtag' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_hashtags' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'hashtag_background',
            [
                'label' => __('Hashtag Background', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hashtag' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_hashtags' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'discount_color',
            [
                'label' => __('Discount Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .group-discount' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_group_discount' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'discount_background',
            [
                'label' => __('Discount Background', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .group-discount' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_group_discount' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'item_spacing',
            [
                'label' => __('Item Spacing', 'tznew'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .meta-info-grid' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .meta-info-list .meta-info-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .meta-info-inline .meta-info-item' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'item_padding',
            [
                'label' => __('Item Padding', 'tznew'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meta-info-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .meta-info-item',
            ]
        );
        
        $this->add_control(
            'item_border_radius',
            [
                'label' => __('Border Radius', 'tznew'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meta-info-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        
        $meta_items = [];
        
        // Get hashtags
        if ($settings['show_hashtags'] === 'yes') {
            $hashtags = tznew_get_elementor_field('hashtags', $post_id);
            if ($hashtags) {
                $hashtag_array = explode(',', $hashtags);
                $hashtag_html = '';
                foreach ($hashtag_array as $tag) {
                    $tag = trim($tag);
                    if (!empty($tag)) {
                        $hashtag_html .= '<span class="hashtag">' . esc_html($settings['hashtag_prefix'] . $tag) . '</span> ';
                    }
                }
                if (!empty($hashtag_html)) {
                    $meta_items[] = [
                        'label' => __('Tags', 'tznew'),
                        'value' => $hashtag_html,
                        'class' => 'hashtags-item'
                    ];
                }
            }
        }
        
        // Get group discount
        if ($settings['show_group_discount'] === 'yes') {
            $group_discount = tznew_get_elementor_field('group_discount', $post_id);
            if ($group_discount) {
                $meta_items[] = [
                    'label' => __('Group Discount', 'tznew'),
                    'value' => '<span class="group-discount">' . esc_html($group_discount) . '</span>',
                    'class' => 'discount-item'
                ];
            }
        }
        
        // Get languages (tours only)
        if ($settings['show_languages'] === 'yes' && $post_type === 'tours') {
            $languages = tznew_get_elementor_field('languages', $post_id);
            if ($languages) {
                $meta_items[] = [
                    'label' => __('Languages', 'tznew'),
                    'value' => esc_html($languages),
                    'class' => 'languages-item'
                ];
            }
        }
        
        // Get group size (tours only)
        if ($settings['show_group_size'] === 'yes' && $post_type === 'tours') {
            $group_size = tznew_get_elementor_field('group_size', $post_id);
            if ($group_size) {
                $meta_items[] = [
                    'label' => __('Group Size', 'tznew'),
                    'value' => esc_html($group_size),
                    'class' => 'group-size-item'
                ];
            }
        }
        
        // Get meta title
        if ($settings['show_meta_title'] === 'yes') {
            $meta_title = tznew_get_elementor_field('meta_title', $post_id);
            if ($meta_title) {
                $meta_items[] = [
                    'label' => __('Meta Title', 'tznew'),
                    'value' => esc_html($meta_title),
                    'class' => 'meta-title-item'
                ];
            }
        }
        
        // Get meta description
        if ($settings['show_meta_description'] === 'yes') {
            $meta_description = tznew_get_elementor_field('meta_description', $post_id);
            if ($meta_description) {
                $meta_items[] = [
                    'label' => __('Meta Description', 'tznew'),
                    'value' => esc_html($meta_description),
                    'class' => 'meta-description-item'
                ];
            }
        }
        
        if (empty($meta_items)) {
            echo '<p>' . __('No meta information found.', 'tznew') . '</p>';
            return;
        }
        
        $layout_class = 'meta-info-' . $settings['layout_style'];
        
        echo '<div class="tznew-meta-info ' . esc_attr($layout_class) . '">';
        
        foreach ($meta_items as $item) {
            echo '<div class="meta-info-item ' . esc_attr($item['class']) . '">';
            
            if ($settings['layout_style'] !== 'inline') {
                echo '<span class="meta-info-label">' . esc_html($item['label']) . ':</span> ';
            }
            
            echo '<span class="meta-info-value">' . $item['value'] . '</span>';
            echo '</div>';
        }
        
        echo '</div>';
        
        // Add CSS
        echo '<style>
        .tznew-meta-info {
            margin: 20px 0;
        }
        .tznew-meta-info .meta-info-item {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 10px 15px;
        }
        .tznew-meta-info .meta-info-label {
            font-weight: bold;
            color: #495057;
        }
        .tznew-meta-info .hashtag {
            display: inline-block;
            background-color: #007cba;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .tznew-meta-info .group-discount {
            background-color: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        /* List Layout */
        .meta-info-list .meta-info-item {
            margin-bottom: 10px;
        }
        
        /* Grid Layout */
        .meta-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        
        /* Inline Layout */
        .meta-info-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .meta-info-inline .meta-info-item {
            margin-bottom: 0;
        }
        
        /* Cards Layout */
        .meta-info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .meta-info-cards .meta-info-item {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .meta-info-cards .meta-info-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        @media (max-width: 768px) {
            .meta-info-grid,
            .meta-info-cards {
                grid-template-columns: 1fr;
            }
            .meta-info-inline {
                flex-direction: column;
            }
        }
        </style>';
    }
}