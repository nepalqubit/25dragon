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

// Header transparency and logo visibility on scroll
(function() {
    function handleScroll() {
        const header = document.querySelector('header');
        const headerLogo = document.querySelector('header .custom-logo');
        const footerLogo = document.querySelector('footer h3');
        
        if (header) {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
                // Hide header logo
                if (headerLogo) {
                    headerLogo.style.opacity = '0';
                    headerLogo.style.visibility = 'hidden';
                    headerLogo.style.transform = 'translateY(-20px)';
                }
                // Create and show floating logo
                let floatingLogo = document.getElementById('floating-logo');
                if (!floatingLogo && headerLogo) {
                    floatingLogo = document.createElement('div');
                    floatingLogo.id = 'floating-logo';
                    floatingLogo.innerHTML = '<img src="' + headerLogo.src + '" alt="' + headerLogo.alt + '" style="height: 40px; width: auto; border-radius: 8px;">';
                    document.body.appendChild(floatingLogo);
                }
                if (floatingLogo) {
                    floatingLogo.style.position = 'fixed';
                    floatingLogo.style.top = '15px';
                    floatingLogo.style.left = '20px';
                    floatingLogo.style.zIndex = '50';
                    floatingLogo.style.opacity = '1';
                    floatingLogo.style.visibility = 'visible';
                    floatingLogo.style.transform = 'translateY(0)';
                    floatingLogo.style.transition = 'all 0.3s ease';
                    floatingLogo.style.background = 'rgba(255, 255, 255, 0.9)';
                    floatingLogo.style.padding = '8px';
                    floatingLogo.style.borderRadius = '12px';
                    floatingLogo.style.backdropFilter = 'blur(10px)';
                    floatingLogo.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
                }
            } else {
                header.classList.remove('scrolled');
                // Show header logo
                if (headerLogo) {
                    headerLogo.style.opacity = '1';
                    headerLogo.style.visibility = 'visible';
                    headerLogo.style.transform = 'translateY(0)';
                }
                // Hide floating logo
                const floatingLogo = document.getElementById('floating-logo');
                if (floatingLogo) {
                    floatingLogo.style.opacity = '0';
                    floatingLogo.style.visibility = 'hidden';
                    floatingLogo.style.transform = 'translateY(-20px)';
                }
            }
        }
    }
    
    // Add scroll event listener
    window.addEventListener('scroll', handleScroll);
    
    // Check initial scroll position
    handleScroll();
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
    <header class="sticky top-0 z-40" style="background: linear-gradient(135deg, rgba(22, 163, 74, 0.95) 0%, rgba(37, 99, 235, 0.95) 100%); backdrop-filter: blur(10px); box-shadow: 0 4px 20px rgba(22, 163, 74, 0.15);">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Site Branding -->
                <div class="flex items-center">
                    <?php if ( has_custom_logo() ) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <h1 class="text-2xl font-bold text-white">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-yellow-200 transition-colors">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </h1>
                    <?php endif; ?>
                </div>

                <!-- Main Navigation -->
                <nav class="hidden lg:block mega-menu-nav">
                    <?php
                    // Check if Max Mega Menu is active and handling the primary menu
                    if (class_exists('Mega_Menu') && function_exists('max_mega_menu_is_enabled') && max_mega_menu_is_enabled('primary')) {
                        // Let Max Mega Menu handle the output with standard wp_nav_menu
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'fallback_cb'    => 'tznew_fallback_menu',
                        ));
                    } else {
                        // Use theme's custom mega menu walker
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'menu_class'     => 'mega-menu',
                            'container'      => false,
                            'walker'         => new TZnew_Mega_Menu_Walker(),
                            'fallback_cb'    => 'tznew_fallback_menu',
                        ));
                    }
                    ?>
                </nav>

                <!-- Mobile Menu Toggle -->
                <button id="mobile-menu-toggle" class="lg:hidden p-2 rounded-md hover:bg-white hover:bg-opacity-20" aria-label="Toggle mobile menu" aria-expanded="false" style="color: white;">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

            <!-- Mobile Navigation -->
            <nav id="mobile-menu" class="lg:hidden pb-4">
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

	<div id="content" class="site-content <?php echo is_front_page() ? 'pt-0' : 'pt-20'; ?>">