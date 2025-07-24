<?php
/**
 * Booking Info Dynamic Tag for Elementor
 *
 * @package TZNEW
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Controls_Manager;

class TZNEW_Booking_Info_Tag extends Tag {

    public function get_name() {
        return 'tznew-booking-info';
    }

    public function get_title() {
        return esc_html__( 'Booking Information', 'tznew' );
    }

    public function get_group() {
        return 'tznew-theme';
    }

    public function get_categories() {
        return [ TagsModule::TEXT_CATEGORY ];
    }

    protected function register_controls() {
        $this->add_control(
            'booking_type',
            [
                'label' => esc_html__( 'Booking Information Type', 'tznew' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'email' => esc_html__( 'Booking Email', 'tznew' ),
                    'phone' => esc_html__( 'Booking Phone', 'tznew' ),
                    'success_message' => esc_html__( 'Success Message', 'tznew' ),
                    'terms_url' => esc_html__( 'Terms & Conditions URL', 'tznew' ),
                    'privacy_url' => esc_html__( 'Privacy Policy URL', 'tznew' ),
                    'cancellation_policy' => esc_html__( 'Cancellation Policy', 'tznew' ),
                ],
                'default' => 'email',
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
    }

    public function render() {
        $booking_type = $this->get_settings( 'booking_type' );
        $fallback = $this->get_settings( 'fallback' );

        $value = '';

        switch ( $booking_type ) {
            case 'email':
                $value = function_exists( 'tznew_get_booking_email' ) ? tznew_get_booking_email() : '';
                break;
            case 'phone':
                $value = function_exists( 'tznew_get_company_phone' ) ? tznew_get_company_phone() : '';
                break;
            case 'success_message':
                $value = function_exists( 'tznew_get_booking_success_message' ) ? tznew_get_booking_success_message() : '';
                break;
            case 'terms_url':
                $value = function_exists( 'tznew_get_terms_url' ) ? tznew_get_terms_url() : '';
                break;
            case 'privacy_url':
                $value = function_exists( 'tznew_get_privacy_url' ) ? tznew_get_privacy_url() : '';
                break;
            case 'cancellation_policy':
                $value = function_exists( 'tznew_get_cancellation_policy' ) ? tznew_get_cancellation_policy() : '';
                break;
        }

        if ( empty( $value ) ) {
            echo esc_html( $fallback );
        } else {
            echo wp_kses_post( $value );
        }
    }
}