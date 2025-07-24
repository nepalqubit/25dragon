<?php
/**
 * Elementor Theme Builder Integration
 *
 * @package TZNEW
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register comprehensive Elementor and Theme Builder support
 */
function tznew_elementor_support() {
    // Add theme support for Elementor
    add_theme_support( 'elementor' );
    
    // Add support for Elementor header/footer
    add_theme_support( 'elementor-header-footer' );
    
    // Add support for Elementor Pro Theme Builder
    if ( class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) {
        add_theme_support( 'elementor-pro-header-footer' );
        add_theme_support( 'elementor-theme-builder' );
    }
    
    // Set Elementor content width
    $GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'tznew_elementor_support' );

/**
 * Register custom dynamic tag groups
 */
function tznew_register_elementor_dynamic_tag_groups( $dynamic_tags ) {
    $dynamic_tags->register_group( 'tznew-acf', [
        'title' => esc_html__( 'TZNEW ACF Fields', 'tznew' ),
    ] );
    
    $dynamic_tags->register_group( 'tznew-theme', [
        'title' => esc_html__( 'TZNEW Theme Data', 'tznew' ),
    ] );
}
add_action( 'elementor/dynamic_tags/register_tags', 'tznew_register_elementor_dynamic_tag_groups', 5 );

/**
 * Register custom dynamic tags for Elementor
 */
function tznew_register_elementor_dynamic_tags( $dynamic_tags ) {
    // Include custom dynamic tags classes
    if ( file_exists( TZNEW_INC_DIR . '/elementor/dynamic-tags/acf-text.php' ) ) {
        require_once TZNEW_INC_DIR . '/elementor/dynamic-tags/acf-text.php';
        $dynamic_tags->register_tag( 'TZNEW_ACF_Text_Tag' );
    }
    
    if ( file_exists( TZNEW_INC_DIR . '/elementor/dynamic-tags/acf-image.php' ) ) {
        require_once TZNEW_INC_DIR . '/elementor/dynamic-tags/acf-image.php';
        $dynamic_tags->register_tag( 'TZNEW_ACF_Image_Tag' );
    }
    
    if ( file_exists( TZNEW_INC_DIR . '/elementor/dynamic-tags/acf-url.php' ) ) {
        require_once TZNEW_INC_DIR . '/elementor/dynamic-tags/acf-url.php';
        $dynamic_tags->register_tag( 'TZNEW_ACF_URL_Tag' );
    }
    
    if ( file_exists( TZNEW_INC_DIR . '/elementor/dynamic-tags/company-info.php' ) ) {
        require_once TZNEW_INC_DIR . '/elementor/dynamic-tags/company-info.php';
        $dynamic_tags->register_tag( 'TZNEW_Company_Info_Tag' );
    }
    
    if ( file_exists( TZNEW_INC_DIR . '/elementor/dynamic-tags/booking-info.php' ) ) {
        require_once TZNEW_INC_DIR . '/elementor/dynamic-tags/booking-info.php';
        $dynamic_tags->register_tag( 'TZNEW_Booking_Info_Tag' );
    }
}
add_action( 'elementor/dynamic_tags/register_tags', 'tznew_register_elementor_dynamic_tags', 10 );

/**
 * Add Elementor support for custom post types
 */
function tznew_add_elementor_cpt_support() {
    // Add Elementor support for custom post types
    $cpt_support = get_option( 'elementor_cpt_support' );
    
    if ( ! $cpt_support ) {
        $cpt_support = [ 'page', 'post', 'trekking', 'tours', 'blog', 'faq' ];
        update_option( 'elementor_cpt_support', $cpt_support );
    } else if ( ! in_array( 'trekking', $cpt_support ) || ! in_array( 'tours', $cpt_support ) || ! in_array( 'blog', $cpt_support ) || ! in_array( 'faq', $cpt_support ) ) {
        $cpt_support = array_merge( $cpt_support, [ 'trekking', 'tours', 'blog', 'faq' ] );
        $cpt_support = array_unique( $cpt_support );
        update_option( 'elementor_cpt_support', $cpt_support );
    }
}
add_action( 'wp_loaded', 'tznew_add_elementor_cpt_support' );

/**
 * Force Elementor to recognize custom post types immediately
 */
function tznew_force_elementor_cpt_support() {
    // Force update the option immediately
    $cpt_support = [ 'page', 'post', 'trekking', 'tours', 'blog', 'faq' ];
    update_option( 'elementor_cpt_support', $cpt_support );
    
    // Clear any cached data
    if ( function_exists( 'wp_cache_flush' ) ) {
        wp_cache_flush();
    }
    
    // Force clear Elementor cache if available
    if ( class_exists( '\Elementor\Plugin' ) ) {
        try {
            \Elementor\Plugin::$instance->files_manager->clear_cache();
        } catch ( Exception $e ) {
            // Silently handle any errors
        }
    }
}
add_action( 'elementor/loaded', 'tznew_force_elementor_cpt_support' );
add_action( 'init', 'tznew_force_elementor_cpt_support', 999 );
add_action( 'wp_loaded', 'tznew_force_elementor_cpt_support', 999 );
add_action( 'admin_init', 'tznew_force_elementor_cpt_support', 999 );

/**
 * Filter Elementor supported post types to include custom post types
 */
function tznew_elementor_cpt_support_filter( $post_types ) {
    if ( ! is_array( $post_types ) ) {
        $post_types = [ 'page', 'post' ];
    }
    
    $custom_post_types = [ 'trekking', 'tours', 'blog', 'faq' ];
    $post_types = array_merge( $post_types, $custom_post_types );
    $post_types = array_unique( $post_types );
    
    return $post_types;
}
add_filter( 'elementor/utils/get_the_archive_posts/post_types', 'tznew_elementor_cpt_support_filter' );

/**
 * Force Elementor to always include custom post types in supported list
 */
function tznew_elementor_force_cpt_support( $post_types ) {
    if ( ! is_array( $post_types ) ) {
        $post_types = [];
    }
    
    $custom_types = [ 'trekking', 'tours', 'blog', 'faq' ];
    return array_unique( array_merge( $post_types, $custom_types ) );
}
add_filter( 'elementor/documents/get_post_types', 'tznew_elementor_force_cpt_support' );
add_filter( 'elementor/core/common/modules/finder/categories/create/get_post_types', 'tznew_elementor_force_cpt_support' );

/**
 * Hook into Elementor's post type support check
 */
function tznew_elementor_post_type_support( $is_supported, $post_type, $feature ) {
    if ( $feature === 'elementor' && in_array( $post_type, [ 'trekking', 'tours', 'blog', 'faq' ] ) ) {
        return true;
    }
    return $is_supported;
}
add_filter( 'post_type_supports', 'tznew_elementor_post_type_support', 10, 3 );

/**
 * Ensure custom post types are available in Elementor editor
 */
function tznew_elementor_editor_support() {
    if ( ! class_exists( '\Elementor\Plugin' ) ) {
        return;
    }
    
    // Get current supported post types
    $supported_post_types = get_option( 'elementor_cpt_support', [ 'page', 'post' ] );
    
    // Add our custom post types if not already present
    $custom_types = [ 'trekking', 'tours', 'blog', 'faq' ];
    foreach ( $custom_types as $type ) {
        if ( ! in_array( $type, $supported_post_types ) ) {
            $supported_post_types[] = $type;
        }
    }
    
    // Update the option
    update_option( 'elementor_cpt_support', $supported_post_types );
}
add_action( 'admin_init', 'tznew_elementor_editor_support' );
add_action( 'wp_loaded', 'tznew_elementor_editor_support' );

/**
 * Hook into Elementor's initialization to force CPT recognition
 */
function tznew_elementor_init_cpt_support() {
    if ( ! class_exists( '\Elementor\Plugin' ) ) {
        return;
    }
    
    // Force update CPT support during Elementor initialization
    $custom_types = [ 'trekking', 'tours', 'blog', 'faq' ];
    $current_support = get_option( 'elementor_cpt_support', [ 'page', 'post' ] );
    $updated_support = array_unique( array_merge( $current_support, $custom_types ) );
    
    // Only update if there are changes
    if ( array_diff( $updated_support, $current_support ) ) {
        update_option( 'elementor_cpt_support', $updated_support );
    }
    
    // Ensure post type support is added
    foreach ( $custom_types as $post_type ) {
        if ( post_type_exists( $post_type ) ) {
            add_post_type_support( $post_type, 'elementor' );
            add_post_type_support( $post_type, 'editor' );
        }
    }
}
add_action( 'elementor/init', 'tznew_elementor_init_cpt_support', 5 );
add_action( 'plugins_loaded', 'tznew_elementor_init_cpt_support', 20 );

/**
 * Enable comprehensive Elementor Theme Builder support
 */
function tznew_elementor_theme_builder_support() {
    if ( ! class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) {
        return;
    }
    
    // Add custom post types to theme builder supported post types
    add_filter( 'elementor/theme/posts_location_query', function( $query ) {
        if ( isset( $query['post_type'] ) ) {
            if ( is_array( $query['post_type'] ) ) {
                $query['post_type'] = array_merge( $query['post_type'], [ 'trekking', 'tours', 'blog', 'faq' ] );
            } else {
                $query['post_type'] = [ $query['post_type'], 'trekking', 'tours', 'blog', 'faq' ];
            }
        }
        return $query;
    });
    
    // Register theme locations
    add_action( 'elementor/theme/register_locations', 'tznew_register_elementor_locations' );
}
add_action( 'init', 'tznew_elementor_theme_builder_support' );

/**
 * Register Elementor theme locations
 */
function tznew_register_elementor_locations( $elementor_theme_manager ) {
    $elementor_theme_manager->register_location( 'header' );
    $elementor_theme_manager->register_location( 'footer' );
    $elementor_theme_manager->register_location( 'single' );
    $elementor_theme_manager->register_location( 'archive' );
    $elementor_theme_manager->register_location( 'search-results' );
    $elementor_theme_manager->register_location( '404' );
}

/**
 * Check if Elementor Theme Builder template exists for current location
 */
function tznew_elementor_location_exists( $location ) {
    if ( ! class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) {
        return false;
    }
    
    try {
        $location_manager = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_locations_manager();
        $location_obj = $location_manager->get_location( $location );
        
        // Check if location object exists and has get_document method
        if ( ! $location_obj || ! is_object( $location_obj ) || ! method_exists( $location_obj, 'get_document' ) ) {
            return false;
        }
        
        $document = $location_obj->get_document();
        return ! empty( $document );
    } catch ( Exception $e ) {
        // Log error for debugging
        error_log( 'Elementor location check error: ' . $e->getMessage() );
        return false;
    }
}

/**
 * Render Elementor theme location
 */
function tznew_elementor_do_location( $location ) {
    if ( ! class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) {
        return false;
    }
    
    try {
        $location_manager = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_locations_manager();
        
        // Check if location manager exists and has do_location method
        if ( ! $location_manager || ! method_exists( $location_manager, 'do_location' ) ) {
            return false;
        }
        
        return $location_manager->do_location( $location );
    } catch ( Exception $e ) {
        // Log error for debugging
        error_log( 'Elementor do_location error: ' . $e->getMessage() );
        return false;
    }
}

/**
 * Register Elementor widgets categories
 *
 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
 */
function tznew_register_elementor_categories( $elements_manager ) {
    $elements_manager->add_category(
        'tznew-elements',
        [
            'title' => esc_html__( 'TZNEW Elements', 'tznew' ),
            'icon'  => 'fa fa-plug',
        ]
    );
}
add_action( 'elementor/elements/categories_registered', 'tznew_register_elementor_categories' );

/**
 * Clear Elementor cache when theme is activated
 */
function tznew_clear_elementor_cache() {
    if ( class_exists( '\Elementor\Plugin' ) ) {
        \Elementor\Plugin::$instance->files_manager->clear_cache();
    }
}
add_action( 'after_switch_theme', 'tznew_clear_elementor_cache' );

/**
 * Ensure ACF fields are available in Elementor
 */
function tznew_elementor_acf_integration() {
    if ( ! class_exists( '\Elementor\Plugin' ) || ! function_exists( 'acf_get_field_groups' ) ) {
        return;
    }
    
    // Force ACF integration
    add_filter( 'elementor/dynamic_tags/tags', function( $tags ) {
        // Ensure our custom ACF tags are registered
        if ( ! isset( $tags['tznew-acf-text'] ) ) {
            $tags['tznew-acf-text'] = 'TZNEW_ACF_Text_Tag';
        }
        if ( ! isset( $tags['tznew-acf-image'] ) ) {
            $tags['tznew-acf-image'] = 'TZNEW_ACF_Image_Tag';
        }
        if ( ! isset( $tags['tznew-acf-url'] ) ) {
            $tags['tznew-acf-url'] = 'TZNEW_ACF_URL_Tag';
        }
        return $tags;
    });
    
    // Ensure ACF fields are available in Elementor editor
    add_action( 'elementor/editor/before_enqueue_scripts', function() {
        if ( function_exists( 'acf_get_field_groups' ) ) {
            $field_groups = acf_get_field_groups();
            $acf_fields = [];
            
            foreach ( $field_groups as $group ) {
                $fields = acf_get_fields( $group['key'] );
                if ( $fields ) {
                    foreach ( $fields as $field ) {
                        $acf_fields[] = [
                            'key' => $field['name'],
                            'label' => $field['label'],
                            'type' => $field['type']
                        ];
                    }
                }
            }
            
            wp_localize_script( 'elementor-editor', 'tznew_acf_fields', $acf_fields );
        }
    });
}
add_action( 'elementor/init', 'tznew_elementor_acf_integration' );

/**
 * Fix content area detection for Elementor
 */
function tznew_elementor_content_area_detection() {
    if ( ! class_exists( '\Elementor\Plugin' ) ) {
        return;
    }
    
    // Ensure content area is properly detected
    add_action( 'elementor/frontend/before_render', function() {
        // Force content area detection
        if ( ! did_action( 'elementor/theme/before_do_single' ) ) {
            do_action( 'elementor/theme/before_do_single' );
        }
    });
    
    // Ensure proper content rendering
    add_filter( 'elementor/frontend/builder_content_data', function( $data, $post_id ) {
        if ( empty( $data ) && \Elementor\Plugin::$instance->db->is_built_with_elementor( $post_id ) ) {
            // Force rebuild content data
            $document = \Elementor\Plugin::$instance->documents->get( $post_id );
            if ( $document ) {
                return $document->get_elements_data();
            }
        }
        return $data;
    }, 10, 2 );
}
add_action( 'wp', 'tznew_elementor_content_area_detection' );

/**
 * Ensure custom post types work with Elementor after theme activation
 */
function tznew_elementor_theme_activation() {
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Clear Elementor cache
    tznew_clear_elementor_cache();
    
    // Force update Elementor CPT support
    tznew_add_elementor_cpt_support();
}
add_action( 'after_switch_theme', 'tznew_elementor_theme_activation' );