<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package TZnew
 */

?>

	</div><!-- #content -->

<?php
// Check if Elementor Theme Builder footer exists
if ( function_exists( 'tznew_elementor_location_exists' ) && tznew_elementor_location_exists( 'footer' ) ) {
    // Use Elementor Theme Builder footer
    tznew_elementor_do_location( 'footer' );
} else {
    // Fallback to default footer
    ?>
    <footer id="colophon" class="site-footer bg-gradient-to-b from-green-900 to-teal-900 text-white relative overflow-hidden">
		<!-- Background decoration -->
		<div class="absolute inset-0 opacity-10">
			<div class="absolute top-10 left-10 w-20 h-20 bg-teal-500 rounded-full animate-pulse"></div>
			<div class="absolute bottom-10 right-10 w-16 h-16 bg-green-500 rounded-full animate-pulse delay-1000"></div>
			<div class="absolute top-1/2 left-1/4 w-12 h-12 bg-yellow-500 rounded-full animate-pulse delay-500"></div>
		</div>
		
		<div class="container mx-auto px-4 py-16 relative z-10">
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
				<div class="footer-section animate-fade-in-up">
					<div class="mb-6">
						<h3 class="text-2xl font-bold mb-4 bg-gradient-to-r from-green-400 to-teal-400 bg-clip-text text-transparent">
							<?php bloginfo('name'); ?>
						</h3>
						<p class="text-gray-300 leading-relaxed"><?php bloginfo('description'); ?></p>
					</div>
					<div class="flex items-center space-x-2 text-sm text-gray-400">
						<i class="fas fa-award text-yellow-500"></i>
						<span>Licensed & Insured</span>
					</div>
				</div>
				
				<div class="footer-section animate-fade-in-up delay-200">
					<h3 class="text-lg font-bold mb-6 text-white">Quick Links</h3>
					<?php
					wp_nav_menu(array(
						'theme_location' => 'footer',
						'menu_class' => 'footer-menu space-y-3',
						'container' => false,
						'fallback_cb' => 'tznew_footer_fallback_menu',
					));
					?>
				</div>
				
				<div class="footer-section animate-fade-in-up delay-400">
					<h3 class="text-lg font-bold mb-6 text-white">Contact Info</h3>
					<div class="space-y-4 text-gray-300">
						<?php if (tznew_get_company_address()): ?>
						<div class="flex items-start space-x-3 hover:text-white transition-colors duration-300">
							<i class="fas fa-location-dot text-teal-400 mt-1"></i>
							<span><?php echo esc_html(tznew_get_company_address()); ?></span>
						</div>
						<?php endif; ?>
						<?php if (tznew_get_company_phone()): ?>
						<div class="flex items-center space-x-3 hover:text-white transition-colors duration-300">
							<i class="fas fa-phone text-green-400"></i>
							<a href="tel:<?php echo esc_attr(tznew_get_company_phone()); ?>" class="hover:text-teal-400 transition-colors"><?php echo esc_html(tznew_get_company_phone()); ?></a>
						</div>
						<?php endif; ?>
						<?php if (tznew_get_company_email()): ?>
						<div class="flex items-center space-x-3 hover:text-white transition-colors duration-300">
							<i class="fas fa-envelope text-yellow-400"></i>
							<a href="mailto:<?php echo esc_attr(tznew_get_company_email()); ?>" class="hover:text-teal-400 transition-colors"><?php echo esc_html(tznew_get_company_email()); ?></a>
						</div>
						<?php endif; ?>
						<div class="flex items-center space-x-3 hover:text-white transition-colors duration-300">
							<i class="fas fa-clock text-purple-400"></i>
							<span>24/7 Support Available</span>
						</div>
					</div>
				</div>
				
				<div class="footer-section animate-fade-in-up delay-600">
					<h3 class="text-lg font-bold mb-6 text-white">Follow Our Journey</h3>
					<div class="flex flex-wrap gap-4 mb-6">
						<?php 
						$social_links = tznew_get_social_media_links();
						$social_configs = [
							'facebook' => ['icon' => 'fab fa-facebook-f', 'bg' => 'bg-blue-600 hover:bg-blue-700'],
							'twitter' => ['icon' => 'fab fa-twitter', 'bg' => 'bg-blue-400 hover:bg-blue-500'],
							'instagram' => ['icon' => 'fab fa-instagram', 'bg' => 'bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700'],
							'youtube' => ['icon' => 'fab fa-youtube', 'bg' => 'bg-red-600 hover:bg-red-700'],
							'whatsapp' => ['icon' => 'fab fa-whatsapp', 'bg' => 'bg-green-600 hover:bg-green-700'],
							'linkedin' => ['icon' => 'fab fa-linkedin-in', 'bg' => 'bg-blue-800 hover:bg-blue-900'],
							'tripadvisor' => ['icon' => 'fab fa-tripadvisor', 'bg' => 'bg-green-500 hover:bg-green-600']
						];
						
						foreach ($social_links as $platform => $url):
							if (!empty($url) && isset($social_configs[$platform])):
								$link_url = ($platform === 'whatsapp') ? 'https://wa.me/' . str_replace(['+', '-', ' '], '', $url) : $url;
						?>
						<a href="<?php echo esc_url($link_url); ?>" class="<?php echo esc_attr($social_configs[$platform]['bg']); ?> p-3 rounded-full transition-all duration-300 transform hover:scale-110 hover:shadow-lg" title="<?php echo esc_attr(ucfirst($platform)); ?>" target="_blank" rel="noopener">
							<i class="<?php echo esc_attr($social_configs[$platform]['icon']); ?> text-lg"></i>
						</a>
						<?php 
							endif;
						endforeach;
						
						// Add WhatsApp if available but not in social links
						if (tznew_get_company_whatsapp() && empty($social_links['whatsapp'])):
							$whatsapp_url = 'https://wa.me/' . str_replace(['+', '-', ' '], '', tznew_get_company_whatsapp());
						?>
						<a href="<?php echo esc_url($whatsapp_url); ?>" class="bg-green-600 hover:bg-green-700 p-3 rounded-full transition-all duration-300 transform hover:scale-110 hover:shadow-lg" title="WhatsApp" target="_blank" rel="noopener">
							<i class="fab fa-whatsapp text-lg"></i>
						</a>
						<?php endif; ?>
					</div>
					
					<div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-4 border border-gray-700">
						<h4 class="font-semibold mb-2 text-blue-400">Newsletter</h4>
						<p class="text-sm text-gray-300 mb-3">Get adventure updates & exclusive offers</p>
						<form class="flex gap-2">
							<input type="email" placeholder="Your email" class="flex-1 px-3 py-2 bg-gray-700 border border-gray-600 rounded text-sm focus:outline-none focus:border-blue-500 transition-colors" />
							<button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-sm font-medium transition-colors">
								<i class="fas fa-paper-plane"></i>
							</button>
						</form>
					</div>
				</div>
			</div>
			
			<!-- Trust badges -->
			<div class="border-t border-gray-700 mt-12 pt-8">
				<div class="flex flex-wrap justify-center items-center gap-8 mb-8 text-sm text-gray-400">
					<div class="flex items-center space-x-2">
						<i class="fas fa-shield-alt text-green-400"></i>
						<span>Secure Booking</span>
					</div>
					<div class="flex items-center space-x-2">
						<i class="fas fa-medal text-yellow-400"></i>
						<span>15+ Years Experience</span>
					</div>
					<div class="flex items-center space-x-2">
						<i class="fas fa-users text-blue-400"></i>
						<span>10,000+ Happy Travelers</span>
					</div>
					<div class="flex items-center space-x-2">
						<i class="fas fa-star text-yellow-400"></i>
						<span>4.9/5 Rating</span>
					</div>
				</div>
				
				<div class="text-center text-gray-400">
					<p class="mb-2">
						<?php echo esc_html(tznew_get_footer_copyright()); ?>
						<?php if (tznew_get_privacy_url()): ?>
						<span class="mx-2">|</span>
						<a href="<?php echo esc_url(tznew_get_privacy_url()); ?>" class="hover:text-blue-400 transition-colors">Privacy Policy</a>
						<?php endif; ?>
						<?php if (tznew_get_terms_url()): ?>
						<span class="mx-2">|</span>
						<a href="<?php echo esc_url(tznew_get_terms_url()); ?>" class="hover:text-blue-400 transition-colors">Terms & Conditions</a>
						<?php endif; ?>
					</p>
					<p class="text-sm">
						Powered by <a href="https://techzeninc.com" target="_blank" class="text-blue-400 hover:text-blue-300 transition duration-300">TechZen Corporation</a>
					</p>
				</div>
			</div>
		</div>
		
		<!-- Back to top button -->
		<div class="back-to-top fixed bottom-8 right-8 bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110 cursor-pointer opacity-0 invisible" id="back-to-top">
			<i class="fas fa-arrow-up"></i>
		</div>
	</footer><!-- #colophon -->
    <?php
}
?>
</div><!-- #page -->

<?php 
// Include floating contact buttons on all pages
get_template_part('template-parts/floating-contact-buttons'); 
?>

<?php wp_footer(); ?>

</body>
</html>