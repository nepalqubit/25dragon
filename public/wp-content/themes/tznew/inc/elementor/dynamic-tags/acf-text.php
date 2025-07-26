<?php
/**
 * ACF Text Dynamic Tag for Elementor
 *
 * @package TZNEW
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Controls_Manager;

class TZNEW_ACF_Text_Tag extends Tag {

    public function get_name() {
        return 'tznew-acf-text';
    }

    public function get_title() {
        return esc_html__( 'ACF Text Field', 'tznew' );
    }

    public function get_group() {
        return 'tznew-acf';
    }

    public function get_categories() {
        return [ TagsModule::TEXT_CATEGORY ];
    }

    protected function register_controls() {
        $this->add_control(
            'acf_field_key',
            [
                'label' => esc_html__( 'ACF Field', 'tznew' ),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_acf_text_fields(),
                'default' => '',
            ]
        );

        $this->add_control(
            'fallback',
            [
                'label' => esc_html__( 'Fallback', 'tznew' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );
    }

    public function render() {
        $field_key = $this->get_settings( 'acf_field_key' );
        $fallback = $this->get_settings( 'fallback' );

        if ( empty( $field_key ) ) {
            echo esc_html( $fallback );
            return;
        }

        $value = '';
        
        // Check if it's a theme options field
        if ( strpos( $field_key, 'options_' ) === 0 ) {
            $field_name = str_replace( 'options_', '', $field_key );
            $value = get_field( $field_name, 'option' );
        } else {
            $value = get_field( $field_key );
        }

        if ( empty( $value ) ) {
            echo esc_html( $fallback );
        } else {
            echo wp_kses_post( $value );
        }
    }

    private function get_acf_text_fields() {
        $fields = [];
        
        if ( ! function_exists( 'acf_get_field_groups' ) ) {
            return $fields;
        }

        $field_groups = acf_get_field_groups();
        
        foreach ( $field_groups as $group ) {
            $group_fields = acf_get_fields( $group['key'] );
            
            if ( $group_fields ) {
                foreach ( $group_fields as $field ) {
                    if ( in_array( $field['type'], [ 'text', 'textarea', 'wysiwyg', 'email', 'url', 'number' ] ) ) {
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