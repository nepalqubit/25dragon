<?php
/**
 * Elementor Canvas Template
 *
 * This template is used when Elementor Canvas page template is selected.
 * It provides a blank canvas for Elementor to work with.
 *
 * @package TZNEW
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Remove all actions from wp_head and wp_footer
remove_all_actions( 'wp_head' );
remove_all_actions( 'wp_print_styles' );
remove_all_actions( 'wp_print_head_scripts' );
remove_all_actions( 'wp_footer' );

// Handle `wp_head`
add_action( 'wp_head', 'wp_enqueue_scripts', 1 );
add_action( 'wp_head', 'wp_print_styles', 8 );
add_action( 'wp_head', 'wp_print_head_scripts', 9 );
add_action( 'wp_head', 'wp_site_icon' );
add_action( 'wp_head', 'wp_meta' );

// Handle `wp_footer`
add_action( 'wp_footer', 'wp_print_footer_scripts', 20 );

// Handle `wp_enqueue_scripts`
remove_all_actions( 'wp_enqueue_scripts' );

// Also remove all scripts hooked into after_wp_tiny_mce.
remove_all_actions( 'after_wp_tiny_mce' );

add_action( 'wp_enqueue_scripts', function() {
    global $wp_styles, $wp_scripts;

    if ( ! empty( $wp_styles->registered ) ) {
        foreach ( $wp_styles->registered as $handle => $style ) {
            if ( in_array( $handle, [ 'elementor-frontend', 'elementor-post', 'elementor-global' ] ) ) {
                wp_enqueue_style( $handle );
            }
        }
    }

    if ( ! empty( $wp_scripts->registered ) ) {
        foreach ( $wp_scripts->registered as $handle => $script ) {
            if ( in_array( $handle, [ 'elementor-frontend', 'elementor-frontend-modules' ] ) ) {
                wp_enqueue_script( $handle );
            }
        }
    }
} );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    
    <div id="page" class="site">
        <div id="content" class="site-content">
            <div class="content-area">
                <main id="main" class="site-main">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        ?>
                        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <div class="entry-content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    ?>
                </main>
            </div>
        </div>
    </div>
    
    <?php wp_footer(); ?>
</body>
</html>