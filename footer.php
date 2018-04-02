<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package awps
 */

?>

	</div><!-- #content -->

	<?php if ( is_customize_preview() ) echo '<div id="awps-footer-control" style="margin-top:-30px;position:absolute;"></div>'; ?>

	<footer id="colophon" class="site-footer container-fluid" role="contentinfo">

		<div class="site-info">
			<a <?php if ( is_customize_preview() ) echo 'id="awps-footer-copy-control"'; ?> href="<?php
				/* translators: %s: Github repo URL. */
				echo esc_url( __( 'https://github.com/Alecaddd/awps', 'awps' ) ); ?>"><?php echo Awps\Api\Customizer::text( 'awps_footer_copy_text' ); ?></a>
			<span class="sep"> | </span>
			<?php
				/* translators: %1: Theme name. */

				/* translators: %2: Author name. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'awps' ), 'AWPS', '<a href="http://alecaddd.com/" rel="designer">Alecaddd</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
