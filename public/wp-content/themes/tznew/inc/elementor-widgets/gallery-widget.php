<?php
/**
 * Gallery Elementor Widget
 * 
 * Displays images from itinerary and other media fields
 * for tours and trekking posts with customizable layout options.
 *
 * @package TZnew
 */

if (!defined('ABSPATH')) {
    exit;
}

class TZnew_Gallery_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'tznew_gallery';
    }
    
    public function get_title() {
        return __('Tour/Trek Gallery', 'tznew');
    }
    
    public function get_icon() {
        return 'eicon-gallery-grid';
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
                'label' => __('Gallery Title', 'tznew'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Photo Gallery', 'tznew'),
            ]
        );
        
        $this->add_control(
            'image_source',
            [
                'label' => __('Image Source', 'tznew'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'itinerary',
                'options' => [
                    'itinerary' => __('Itinerary Images', 'tznew'),
                    'featured' => __('Featured Image Only', 'tznew'),
                    'both' => __('Both Sources', 'tznew'),
                ],
            ]
        );
        
        $this->add_control(
            'layout_style',
            [
                'label' => __('Layout Style', 'tznew'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => __('Grid', 'tznew'),
                    'masonry' => __('Masonry', 'tznew'),
                    'slider' => __('Slider', 'tznew'),
                    'lightbox' => __('Lightbox Grid', 'tznew'),
                ],
            ]
        );
        
        $this->add_control(
            'columns',
            [
                'label' => __('Columns', 'tznew'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '2' => __('2 Columns', 'tznew'),
                    '3' => __('3 Columns', 'tznew'),
                    '4' => __('4 Columns', 'tznew'),
                    '5' => __('5 Columns', 'tznew'),
                ],
                'condition' => [
                    'layout_style!' => 'slider',
                ],
            ]
        );
        
        $this->add_control(
            'image_size',
            [
                'label' => __('Image Size', 'tznew'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'medium',
                'options' => [
                    'thumbnail' => __('Thumbnail', 'tznew'),
                    'medium' => __('Medium', 'tznew'),
                    'large' => __('Large', 'tznew'),
                    'full' => __('Full Size', 'tznew'),
                ],
            ]
        );
        
        $this->add_control(
            'max_images',
            [
                'label' => __('Maximum Images', 'tznew'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 12,
                'min' => 1,
                'max' => 50,
            ]
        );
        
        $this->add_control(
            'show_captions',
            [
                'label' => __('Show Captions', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'enable_lightbox',
            [
                'label' => __('Enable Lightbox', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'layout_style!' => 'slider',
                ],
            ]
        );
        
        // Slider specific controls
        $this->add_control(
            'slider_heading',
            [
                'label' => __('Slider Settings', 'tznew'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'layout_style' => 'slider',
                ],
            ]
        );
        
        $this->add_control(
            'slides_to_show',
            [
                'label' => __('Slides to Show', 'tznew'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 6,
                'condition' => [
                    'layout_style' => 'slider',
                ],
            ]
        );
        
        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'tznew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'layout_style' => 'slider',
                ],
            ]
        );
        
        $this->add_control(
            'autoplay_speed',
            [
                'label' => __('Autoplay Speed (ms)', 'tznew'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3000,
                'condition' => [
                    'layout_style' => 'slider',
                    'autoplay' => 'yes',
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
                    '{{WRAPPER}} .gallery-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .gallery-title',
            ]
        );
        
        $this->add_control(
            'image_border_radius',
            [
                'label' => __('Image Border Radius', 'tznew'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .gallery-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'image_spacing',
            [
                'label' => __('Image Spacing', 'tznew'),
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
                    '{{WRAPPER}} .gallery-grid' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gallery-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'caption_color',
            [
                'label' => __('Caption Color', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gallery-caption' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_captions' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'caption_background',
            [
                'label' => __('Caption Background', 'tznew'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gallery-caption' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_captions' => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow',
                'selector' => '{{WRAPPER}} .gallery-item img',
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
        
        $images = [];
        
        // Get featured image
        if (in_array($settings['image_source'], ['featured', 'both'])) {
            $featured_image_id = get_post_thumbnail_id($post_id);
            if ($featured_image_id) {
                $images[] = [
                    'id' => $featured_image_id,
                    'caption' => get_the_title($post_id) . ' - ' . __('Featured Image', 'tznew'),
                    'day' => '',
                ];
            }
        }
        
        // Get itinerary images
        if (in_array($settings['image_source'], ['itinerary', 'both'])) {
            $itinerary = tznew_get_elementor_field('itinerary_details', $post_id);
            
            if ($itinerary && is_array($itinerary)) {
                foreach ($itinerary as $day) {
                    if (!empty($day['images']) && is_array($day['images'])) {
                        foreach ($day['images'] as $image) {
                            if (is_array($image) && !empty($image['ID'])) {
                                $images[] = [
                                    'id' => $image['ID'],
                                    'caption' => !empty($day['day_title']) ? $day['day_title'] : '',
                                    'day' => !empty($day['day_title']) ? $day['day_title'] : '',
                                ];
                            } elseif (is_numeric($image)) {
                                $images[] = [
                                    'id' => $image,
                                    'caption' => !empty($day['day_title']) ? $day['day_title'] : '',
                                    'day' => !empty($day['day_title']) ? $day['day_title'] : '',
                                ];
                            }
                        }
                    }
                }
            }
        }
        
        // Remove duplicates and limit
        $unique_images = [];
        $seen_ids = [];
        
        foreach ($images as $image) {
            if (!in_array($image['id'], $seen_ids)) {
                $unique_images[] = $image;
                $seen_ids[] = $image['id'];
            }
        }
        
        $images = array_slice($unique_images, 0, $settings['max_images']);
        
        if (empty($images)) {
            echo '<p>' . __('No images found.', 'tznew') . '</p>';
            return;
        }
        
        $layout_class = 'layout-' . $settings['layout_style'];
        $columns_class = 'columns-' . $settings['columns'];
        
        echo '<div class="tznew-gallery ' . esc_attr($layout_class) . ' ' . esc_attr($columns_class) . '">';
        
        if (!empty($settings['title'])) {
            echo '<h2 class="gallery-title">' . esc_html($settings['title']) . '</h2>';
        }
        
        if ($settings['layout_style'] === 'slider') {
            echo '<div class="gallery-slider" data-slides="' . esc_attr($settings['slides_to_show']) . '" data-autoplay="' . esc_attr($settings['autoplay']) . '" data-speed="' . esc_attr($settings['autoplay_speed']) . '">';
        } else {
            echo '<div class="gallery-grid">';
        }
        
        foreach ($images as $index => $image) {
            $image_url = wp_get_attachment_image_url($image['id'], $settings['image_size']);
            $image_full_url = wp_get_attachment_image_url($image['id'], 'full');
            $image_alt = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
            
            if (!$image_url) {
                continue;
            }
            
            $lightbox_attr = '';
            if ($settings['enable_lightbox'] === 'yes' && $settings['layout_style'] !== 'slider') {
                $lightbox_attr = 'data-lightbox="gallery" href="' . esc_url($image_full_url) . '"';
            }
            
            echo '<div class="gallery-item">';
            
            if ($lightbox_attr) {
                echo '<a ' . $lightbox_attr . '>';
            }
            
            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" loading="lazy">';
            
            if ($settings['show_captions'] === 'yes' && !empty($image['caption'])) {
                echo '<div class="gallery-caption">' . esc_html($image['caption']) . '</div>';
            }
            
            if ($lightbox_attr) {
                echo '</a>';
            }
            
            echo '</div>';
        }
        
        echo '</div>'; // .gallery-grid or .gallery-slider
        echo '</div>'; // .tznew-gallery
        
        // Add CSS
        echo '<style>
        .tznew-gallery .gallery-title {
            margin-bottom: 20px;
            font-weight: bold;
        }
        .tznew-gallery .gallery-grid {
            display: grid;
            gap: 10px;
        }
        .tznew-gallery.columns-2 .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .tznew-gallery.columns-3 .gallery-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        .tznew-gallery.columns-4 .gallery-grid {
            grid-template-columns: repeat(4, 1fr);
        }
        .tznew-gallery.columns-5 .gallery-grid {
            grid-template-columns: repeat(5, 1fr);
        }
        @media (max-width: 768px) {
            .tznew-gallery .gallery-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }
        @media (max-width: 480px) {
            .tznew-gallery .gallery-grid {
                grid-template-columns: 1fr !important;
            }
        }
        
        .tznew-gallery .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
        }
        .tznew-gallery .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .tznew-gallery .gallery-item:hover img {
            transform: scale(1.05);
        }
        .tznew-gallery .gallery-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 10px;
            font-size: 14px;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }
        .tznew-gallery .gallery-item:hover .gallery-caption {
            transform: translateY(0);
        }
        
        /* Masonry Layout */
        .layout-masonry .gallery-grid {
            column-count: var(--columns, 3);
            column-gap: 10px;
        }
        .layout-masonry .gallery-item {
            break-inside: avoid;
            margin-bottom: 10px;
        }
        .layout-masonry .gallery-item img {
            height: auto;
        }
        
        /* Lightbox Layout */
        .layout-lightbox .gallery-item {
            cursor: pointer;
        }
        .layout-lightbox .gallery-item::after {
            content: "\f00e";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 24px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .layout-lightbox .gallery-item:hover::after {
            opacity: 1;
        }
        .layout-lightbox .gallery-item:hover::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
        }
        
        /* Slider Layout */
        .layout-slider .gallery-slider {
            position: relative;
            overflow: hidden;
        }
        .layout-slider .gallery-item {
            min-width: calc(100% / var(--slides-to-show, 3));
            padding: 0 5px;
        }
        </style>';
        
        // Add JavaScript for slider
        if ($settings['layout_style'] === 'slider') {
            echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                const slider = document.querySelector(".gallery-slider");
                if (slider) {
                    const slidesToShow = parseInt(slider.getAttribute("data-slides")) || 3;
                    const autoplay = slider.getAttribute("data-autoplay") === "yes";
                    const speed = parseInt(slider.getAttribute("data-speed")) || 3000;
                    
                    slider.style.setProperty("--slides-to-show", slidesToShow);
                    
                    if (autoplay) {
                        let currentSlide = 0;
                        const items = slider.querySelectorAll(".gallery-item");
                        const totalSlides = items.length;
                        
                        setInterval(() => {
                            currentSlide = (currentSlide + 1) % totalSlides;
                            const translateX = -(currentSlide * (100 / slidesToShow));
                            slider.style.transform = `translateX(${translateX}%)`;
                        }, speed);
                    }
                }
            });
            </script>';
        }
        
        // Add lightbox functionality
        if ($settings['enable_lightbox'] === 'yes' && $settings['layout_style'] !== 'slider') {
            echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                const lightboxLinks = document.querySelectorAll("[data-lightbox]");
                lightboxLinks.forEach(link => {
                    link.addEventListener("click", function(e) {
                        e.preventDefault();
                        // Simple lightbox implementation
                        const overlay = document.createElement("div");
                        overlay.style.cssText = "position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);z-index:9999;display:flex;align-items:center;justify-content:center;cursor:pointer;";
                        
                        const img = document.createElement("img");
                        img.src = this.href;
                        img.style.cssText = "max-width:90%;max-height:90%;object-fit:contain;";
                        
                        overlay.appendChild(img);
                        document.body.appendChild(overlay);
                        
                        overlay.addEventListener("click", () => {
                            document.body.removeChild(overlay);
                        });
                    });
                });
            });
            </script>';
        }
    }
}