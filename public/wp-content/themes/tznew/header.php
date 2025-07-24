<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package TZnew
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Preloader -->
<div id="preloader" class="fixed inset-0 bg-white z-50 flex items-center justify-center">
    <div class="animate-spin rounded-full h-32 w-32 border-b-2 border-blue-600"></div>
</div>

<script>
// Universal preloader functionality - works on all pages
(function() {
    function hidePreloader() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            preloader.style.opacity = '0';
            preloader.style.transition = 'opacity 0.5s ease-out';
            setTimeout(function() {
                preloader.style.display = 'none';
            }, 500);
        }
    }
    
    // Hide on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', hidePreloader);
    } else {
        hidePreloader();
    }
    
    // Also hide on window load
    window.addEventListener('load', hidePreloader);
    
    // Fallback: Hide after 2 seconds maximum
    setTimeout(hidePreloader, 2000);
})();
</script>

<?php
// Check if Elementor Theme Builder header exists
if ( function_exists( 'tznew_elementor_location_exists' ) && tznew_elementor_location_exists( 'header' ) ) {
    // Use Elementor Theme Builder header
    tznew_elementor_do_location( 'header' );
} else {
    // Fallback to default header
    ?>
    <header class="bg-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Site Branding -->
                <div class="flex items-center">
                    <?php if ( has_custom_logo() ) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <h1 class="text-2xl font-bold text-gray-800">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-blue-600 transition-colors">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </h1>
                    <?php endif; ?>
                </div>

                <!-- Main Navigation -->
                <nav class="hidden lg:block">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'flex space-x-8',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ) );
                    ?>
                </nav>

                <!-- Mobile Menu Toggle -->
                <button id="mobile-menu-toggle" class="lg:hidden p-2 rounded-md hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Navigation -->
            <nav id="mobile-menu" class="lg:hidden hidden pb-4">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'space-y-2',
                    'container'      => false,
                    'fallback_cb'    => false,
                ) );
                ?>
            </nav>
        </div>
    </header>
    <?php
}
?>

<!-- Breadcrumbs Container -->
<div id="breadcrumbs-container"></div>

	<div id="content" class="site-content pt-20">