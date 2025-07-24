<?php
/**
 * ACF URL Dynamic Tag for Elementor
 *
 * @package TZNEW
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Controls_Manager;

class TZNEW_ACF_URL_Tag extends Tag {

    public function get_name() {
        return 'tznew-acf-url';
    }

    public function get_title() {
        return esc_html__( 'ACF URL Field', 'tznew' );
    }

    public function get_group() {
        return 'tznew-acf';
    }

    public function get_categories() {
        return [ TagsModule::URL_CATEGORY ];
    }

    protected function register_controls() {
        $this->add_control(
            'acf_field_key',
            [
                'label' => esc_html__( 'ACF Field', 'tznew' ),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_acf_url_fields(),
                'default' => '',
            ]
        );

        $this->add_control(
            'fallback_url',
            [
                'label' => esc_html__( 'Fallback URL', 'tznew' ),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '',
                ],
            ]
        );
    }

    public function render() {
        $field_key = $this->get_settings( 'acf_field_key' );
        $fallback = $this->get_settings( 'fallback_url' );

        if ( empty( $field_key ) ) {
            echo esc_url( $fallback['url'] ?? '' );
            return;
        }

        $value = '';
        
        // Check if it's a theme options field
        if ( strpos( $field_key, 'options_' ) === 0 ) {
            $field_name = str_replace( 'options_', '', $field_key );
            $value = tznew_get_field_safe( $field_name, 'option' );
        } else {
            $value = tznew_get_field_safe( $field_key );
        }

        // Handle different ACF URL field formats
        if ( is_array( $value ) && isset( $value['url'] ) ) {
            echo esc_url( $value['url'] );
        } elseif ( is_string( $value ) && ! empty( $value ) ) {
            echo esc_url( $value );
        } else {
            echo esc_url( $fallback['url'] ?? '' );
        }
    }

    private function get_acf_url_fields() {
        $fields = [];
        
        if ( ! function_exists( 'acf_get_field_groups' ) ) {
            return $fields;
        }

        $field_groups = acf_get_field_groups();
        
        foreach ( $field_groups as $group ) {
            $group_fields = acf_get_fields( $group['key'] );
            
            if ( $group_fields ) {
                foreach ( $group_fields as $field ) {
                    if ( in_array( $field['type'], [ 'url', 'link', 'email' ] ) ) {
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