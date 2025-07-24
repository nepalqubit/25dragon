<?php
/**
 * ACF Image Dynamic Tag for Elementor
 *
 * @package TZNEW
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Core\DynamicTags\Data_Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Controls_Manager;

class TZNEW_ACF_Image_Tag extends Data_Tag {

    public function get_name() {
        return 'tznew-acf-image';
    }

    public function get_title() {
        return esc_html__( 'ACF Image Field', 'tznew' );
    }

    public function get_group() {
        return 'tznew-acf';
    }

    public function get_categories() {
        return [ TagsModule::IMAGE_CATEGORY ];
    }

    protected function register_controls() {
        $this->add_control(
            'acf_field_key',
            [
                'label' => esc_html__( 'ACF Field', 'tznew' ),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_acf_image_fields(),
                'default' => '',
            ]
        );

        $this->add_control(
            'fallback_image',
            [
                'label' => esc_html__( 'Fallback Image', 'tznew' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );
    }

    public function get_value( array $options = [] ) {
        $field_key = $this->get_settings( 'acf_field_key' );
        $fallback = $this->get_settings( 'fallback_image' );

        if ( empty( $field_key ) ) {
            return $fallback;
        }

        $image = null;
        
        // Check if it's a theme options field
        if ( strpos( $field_key, 'options_' ) === 0 ) {
            $field_name = str_replace( 'options_', '', $field_key );
            $image = tznew_get_field_safe( $field_name, 'option' );
        } else {
            $image = tznew_get_field_safe( $field_key );
        }

        if ( empty( $image ) ) {
            return $fallback;
        }

        // Handle different ACF image return formats
        if ( is_array( $image ) ) {
            // Image array format
            return [
                'id' => $image['ID'] ?? '',
                'url' => $image['url'] ?? '',
                'alt' => $image['alt'] ?? '',
            ];
        } elseif ( is_numeric( $image ) ) {
            // Image ID format
            $image_data = wp_get_attachment_image_src( $image, 'full' );
            return [
                'id' => $image,
                'url' => $image_data[0] ?? '',
                'alt' => get_post_meta( $image, '_wp_attachment_image_alt', true ),
            ];
        } elseif ( is_string( $image ) ) {
            // Image URL format
            return [
                'id' => '',
                'url' => $image,
                'alt' => '',
            ];
        }

        return $fallback;
    }

    private function get_acf_image_fields() {
        $fields = [];
        
        if ( ! function_exists( 'acf_get_field_groups' ) ) {
            return $fields;
        }

        $field_groups = acf_get_field_groups();
        
        foreach ( $field_groups as $group ) {
            $group_fields = acf_get_fields( $group['key'] );
            
            if ( $group_fields ) {
                foreach ( $group_fields as $field ) {
                    if ( in_array( $field['type'], [ 'image', 'gallery' ] ) ) {
                        $key = $field['name'];
                        
                        // Add options prefix for theme options
                        if ( isset( $group['location'] ) && $this->is_options_page_group( $group['location'] ) ) {
                            $key = 'options_' . $key;
                        }
                        
                        $fields[ $key ] = $field['label'] . ' (' . $field['type'] . ')';
                    }
                }
            }
        }

        return $fields;
    }

    private function is_options_page_group( $locations ) {
        foreach ( $locations as $location ) {
            foreach ( $location as $rule ) {
                if ( $rule['param'] === 'options_page' ) {
                    return true;
                }
            }
        }
        return false;
    }
}