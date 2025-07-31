<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package TZnew
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area p-4 lg:p-8 bg-gray-50 rounded-lg">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->