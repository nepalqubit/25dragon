<?php
/**
 * FAQ Elementor Widget
 * 
 * Displays frequently asked questions for tours and trekking posts
 * with customizable styling and layout options.
 *
 * @package TZnew
 */

if (!defined('ABSPATH')) {
    exit;
}

class TZnew_FAQ_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'tznew_faq';
    }
    
    public function get_title() {
        return __('FAQ', 'tznew');
    }
    
    public function get_icon() {
        return 'eicon-help-o';
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
            'title',
            [
                'label' => __('Section Title', 'tznew'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Frequently Asked Questions', 'tznew'),
            ]
        );
        
        $this->add_control(
            'show_general_faqs',
            [
                'label' => __('Show General FAQs', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Include general FAQs along with post-specific ones', 'tznew'),
            ]
        );
        
        $this->add_control(
            'layout_style',
            [
                'label' => __('Layout Style', 'tznew'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'accordion',
                'options' => [
                    'accordion' => __('Accordion', 'tznew'),
                    'list' => __('Simple List', 'tznew'),
                    'cards' => __('Cards', 'tznew'),
                ],
            ]
        );
        
        $this->add_control(
            'accordion_icon',
            [
                'label' => __('Accordion Icon', 'tznew'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'layout_style' => 'accordion',
                ],
            ]
        );
        
        $this->add_control(
            'accordion_icon_active',
            [
                'label' => __('Accordion Active Icon', 'tznew'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-minus',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'layout_style' => 'accordion',
                ],
            ]
        );
        
        $this->add_control(
            'max_faqs',
            [
                'label' => __('Maximum FAQs to Display', 'tznew'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 1,
                'max' => 50,
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
                    '{{WRAPPER}} .faq-section-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .faq-section-title',
            ]
        );
        
        $this->add_control(
            'question_color',
            [
                'label' => __('Question Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faq-question' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'question_typography',
                'selector' => '{{WRAPPER}} .faq-question',
            ]
        );
        
        $this->add_control(
            'answer_color',
            [
                'label' => __('Answer Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faq-answer' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'answer_typography',
                'selector' => '{{WRAPPER}} .faq-answer',
            ]
        );
        
        $this->add_control(
            'item_background',
            [
                'label' => __('Item Background', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faq-item' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .faq-item',
            ]
        );
        
        $this->add_control(
            'item_border_radius',
            [
                'label' => __('Border Radius', 'tznew'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .faq-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .faq-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'item_margin',
            [
                'label' => __('Item Margin', 'tznew'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .faq-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        
        // Get FAQ selection field
        $faq_selection = tznew_get_elementor_field('faq_selection', $post_id);
        $show_general_faqs = tznew_get_elementor_field('show_general_faqs', $post_id);
        
        $faqs = [];
        
        // Get selected FAQs
        if ($faq_selection && is_array($faq_selection)) {
            foreach ($faq_selection as $faq_id) {
                $faq_post = get_post($faq_id);
                if ($faq_post && $faq_post->post_type === 'faq') {
                    $faqs[] = [
                        'question' => get_field('question', $faq_id),
                        'answer' => get_field('answer', $faq_id),
                        'category' => get_field('category', $faq_id),
                        'order' => get_field('display_order', $faq_id) ?: 999,
                    ];
                }
            }
        }
        
        // Get general FAQs if enabled
        if (($settings['show_general_faqs'] === 'yes' && $show_general_faqs) || empty($faqs)) {
            $general_faqs = get_posts([
                'post_type' => 'faq',
                'posts_per_page' => $settings['max_faqs'],
                'meta_query' => [
                    [
                        'key' => 'applicable_to',
                        'value' => 'general',
                        'compare' => 'LIKE',
                    ],
                ],
                'orderby' => 'meta_value_num',
                'meta_key' => 'display_order',
                'order' => 'ASC',
            ]);
            
            foreach ($general_faqs as $faq_post) {
                $faqs[] = [
                    'question' => get_field('question', $faq_post->ID),
                    'answer' => get_field('answer', $faq_post->ID),
                    'category' => get_field('category', $faq_post->ID),
                    'order' => get_field('display_order', $faq_post->ID) ?: 999,
                ];
            }
        }
        
        // Sort FAQs by display order
        usort($faqs, function($a, $b) {
            return $a['order'] - $b['order'];
        });
        
        // Limit FAQs
        $faqs = array_slice($faqs, 0, $settings['max_faqs']);
        
        if (empty($faqs)) {
            echo '<p>' . __('No FAQs found.', 'tznew') . '</p>';
            return;
        }
        
        $layout_class = 'layout-' . $settings['layout_style'];
        
        echo '<div class="tznew-faq ' . esc_attr($layout_class) . '">';
        
        if (!empty($settings['title'])) {
            echo '<h2 class="faq-section-title">' . esc_html($settings['title']) . '</h2>';
        }
        
        echo '<div class="faq-container">';
        
        foreach ($faqs as $index => $faq) {
            if (empty($faq['question']) || empty($faq['answer'])) {
                continue;
            }
            
            $item_id = 'faq-item-' . $index;
            
            echo '<div class="faq-item" data-index="' . $index . '">';
            
            if ($settings['layout_style'] === 'accordion') {
                echo '<div class="faq-question accordion-trigger" data-target="' . $item_id . '">';
                echo '<span class="question-text">' . esc_html($faq['question']) . '</span>';
                echo '<span class="accordion-icon">';
                \Elementor\Icons_Manager::render_icon($settings['accordion_icon'], ['class' => 'icon-closed']);
                \Elementor\Icons_Manager::render_icon($settings['accordion_icon_active'], ['class' => 'icon-open']);
                echo '</span>';
                echo '</div>';
                echo '<div class="faq-answer accordion-content" id="' . $item_id . '">';
                echo wp_kses_post($faq['answer']);
                echo '</div>';
            } else {
                echo '<div class="faq-question">' . esc_html($faq['question']) . '</div>';
                echo '<div class="faq-answer">' . wp_kses_post($faq['answer']) . '</div>';
            }
            
            echo '</div>';
        }
        
        echo '</div>'; // .faq-container
        echo '</div>'; // .tznew-faq
        
        // Add CSS
        echo '<style>
        .tznew-faq .faq-item {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
        }
        .tznew-faq .faq-question {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .tznew-faq .faq-answer {
            line-height: 1.6;
        }
        
        /* Accordion Layout */
        .layout-accordion .faq-question.accordion-trigger {
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .layout-accordion .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            padding-top: 0;
        }
        .layout-accordion .accordion-content.active {
            max-height: 1000px;
            padding-top: 15px;
        }
        .layout-accordion .accordion-icon {
            transition: transform 0.3s ease;
        }
        .layout-accordion .icon-open {
            display: none;
        }
        .layout-accordion .accordion-trigger.active .icon-closed {
            display: none;
        }
        .layout-accordion .accordion-trigger.active .icon-open {
            display: inline;
        }
        
        /* Cards Layout */
        .layout-cards .faq-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .layout-cards .faq-item {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .layout-cards .faq-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        /* List Layout */
        .layout-list .faq-item {
            border-left: 4px solid #007cba;
            background-color: transparent;
            border-top: none;
            border-right: none;
            border-bottom: 1px solid #e9ecef;
            border-radius: 0;
            padding-left: 20px;
        }
        .layout-list .faq-item:last-child {
            border-bottom: none;
        }
        </style>';
        
        // Add JavaScript for accordion
        if ($settings['layout_style'] === 'accordion') {
            echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                const accordionTriggers = document.querySelectorAll(".accordion-trigger");
                
                accordionTriggers.forEach(trigger => {
                    trigger.addEventListener("click", function() {
                        const target = document.getElementById(this.getAttribute("data-target"));
                        const isActive = this.classList.contains("active");
                        
                        // Close all accordion items
                        accordionTriggers.forEach(t => {
                            t.classList.remove("active");
                            const content = document.getElementById(t.getAttribute("data-target"));
                            if (content) {
                                content.classList.remove("active");
                            }
                        });
                        
                        // Open clicked item if it was not active
                        if (!isActive) {
                            this.classList.add("active");
                            target.classList.add("active");
                        }
                    });
                });
            });
            </script>';
        }
    }
}