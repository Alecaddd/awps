<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package awps
 */

if ( ! is_active_sidebar( 'awps-sidebar' ) ) :
	return;
endif;
?>

<?php
if ( is_customize_preview() ) {
	echo '<div id="awps-sidebar-control"></div>';
}
?>

<aside id="secondary" class="widget-area" role="complementary">
	<?php
	dynamic_sidebar( 'awps-sidebar' );
	?>
</aside><!-- #secondary -->
