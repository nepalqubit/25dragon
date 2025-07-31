<?php
/**
 * Elementor Full Width Template
 *
 * This template is used when Elementor Full Width page template is selected.
 * It includes header and footer but provides full width content area.
 *
 * @package TZNEW
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();
?>

<div class="elementor-page elementor-page-<?php the_ID(); ?>">
    <?php
    while ( have_posts() ) :
        the_post();
        ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class( 'elementor-full-width' ); ?>>
            <div class="entry-content">
                <?php
                the_content();
                
                wp_link_pages(
                    array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'tznew' ),
                        'after'  => '</div>',
                    )
                );
                ?>
            </div>
        </div>
        <?php
    endwhile;
    ?>
</div>

<?php
get_footer();