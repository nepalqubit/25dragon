<?php
/**
 * Company Info Dynamic Tag for Elementor
 *
 * @package TZNEW
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Controls_Manager;

class TZNEW_Company_Info_Tag extends Tag {

    public function get_name() {
        return 'tznew-company-info';
    }

    public function get_title() {
        return esc_html__( 'Company Information', 'tznew' );
    }

    public function get_group() {
        return 'tznew-theme';
    }

    public function get_categories() {
        return [ TagsModule::TEXT_CATEGORY ];
    }

    protected function register_controls() {
        $this->add_control(
            'info_type',
            [
                'label' => esc_html__( 'Information Type', 'tznew' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'name' => esc_html__( 'Company Name', 'tznew' ),
                    'tagline' => esc_html__( 'Company Tagline', 'tznew' ),
                    'address' => esc_html__( 'Company Address', 'tznew' ),
                    'phone' => esc_html__( 'Phone Number', 'tznew' ),
                    'email' => esc_html__( 'Email Address', 'tznew' ),
                    'website' => esc_html__( 'Website URL', 'tznew' ),
                    'facebook' => esc_html__( 'Facebook URL', 'tznew' ),
                    'twitter' => esc_html__( 'Twitter URL', 'tznew' ),
                    'instagram' => esc_html__( 'Instagram URL', 'tznew' ),
                    'youtube' => esc_html__( 'YouTube URL', 'tznew' ),
                    'linkedin' => esc_html__( 'LinkedIn URL', 'tznew' ),
                    'whatsapp' => esc_html__( 'WhatsApp Number', 'tznew' ),
                ],
                'default' => 'name',
            ]
        );

        $this->add_control(
            'fallback',
            [
                'label' => esc_html__( 'Fallback Text', 'tznew' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'format_phone',
            [
                'label' => esc_html__( 'Format Phone Number', 'tznew' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'info_type' => [ 'phone', 'whatsapp' ],
                ],
            ]
        );
    }

    public function render() {
        $info_type = $this->get_settings( 'info_type' );
        $fallback = $this->get_settings( 'fallback' );
        $format_phone = $this->get_settings( 'format_phone' ) === 'yes';

        $value = '';

        switch ( $info_type ) {
            case 'name':
                $value = function_exists( 'tznew_get_company_name' ) ? tznew_get_company_name() : get_bloginfo( 'name' );
                break;
            case 'tagline':
                $value = function_exists( 'tznew_get_company_tagline' ) ? tznew_get_company_tagline() : get_bloginfo( 'description' );
                break;
            case 'address':
                $value = function_exists( 'tznew_get_company_address' ) ? tznew_get_company_address() : '';
                break;
            case 'phone':
                $value = function_exists( 'tznew_get_company_phone' ) ? tznew_get_company_phone() : '';
                if ( $format_phone && ! empty( $value ) ) {
                    $value = $this->format_phone_number( $value );
                }
                break;
            case 'email':
                $value = function_exists( 'tznew_get_company_email' ) ? tznew_get_company_email() : get_option( 'admin_email' );
                break;
            case 'website':
                $value = function_exists( 'tznew_get_company_website' ) ? tznew_get_company_website() : home_url();
                break;
            case 'facebook':
                $value = function_exists( 'tznew_get_social_media_url' ) ? tznew_get_social_media_url( 'facebook' ) : '';
                break;
            case 'twitter':
                $value = function_exists( 'tznew_get_social_media_url' ) ? tznew_get_social_media_url( 'twitter' ) : '';
                break;
            case 'instagram':
                $value = function_exists( 'tznew_get_social_media_url' ) ? tznew_get_social_media_url( 'instagram' ) : '';
                break;
            case 'youtube':
                $value = function_exists( 'tznew_get_social_media_url' ) ? tznew_get_social_media_url( 'youtube' ) : '';
                break;
            case 'linkedin':
                $value = function_exists( 'tznew_get_social_media_url' ) ? tznew_get_social_media_url( 'linkedin' ) : '';
                break;
            case 'whatsapp':
                $value = function_exists( 'tznew_get_social_media_url' ) ? tznew_get_social_media_url( 'whatsapp' ) : '';
                if ( $format_phone && ! empty( $value ) ) {
                    $value = $this->format_phone_number( $value );
                }
                break;
        }

        if ( empty( $value ) ) {
            echo esc_html( $fallback );
        } else {
            echo wp_kses_post( $value );
        }
    }

    private function format_phone_number( $phone ) {
        // Remove all non-numeric characters
        $phone = preg_replace( '/[^0-9+]/', '', $phone );
        
        // Add formatting for display
        if ( strlen( $phone ) >= 10 ) {
            return $phone; // Return as-is for international numbers
        }
        
        return $phone;
    }
}