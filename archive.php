<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package awps
 */

get_header(); ?>

<div class="container">

	<div class="row">

		<div class="col-sm-8">

			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

				<?php
				if ( have_posts() ) :
				?>

					<header>
						<?php
							the_archive_title( '<h1 class="page-title">', '</h1>' );
							the_archive_description( '<div class="archive-description">', '</div>' );
						?>
					</header>

					<?php
					/* Start the Loop */
					while ( have_posts() ) :

						the_post();

						get_template_part( 'views/content', get_post_format() );

					endwhile;

					the_posts_navigation();

				else :

					get_template_part( 'views/content', 'none' );

				endif;
				?>

				</main><!-- #main -->
			</div><!-- #primary -->

		</div><!-- .col- -->

		<div class="col-sm-4">
			<?php get_sidebar(); ?>
		</div><!-- .col- -->

	</div><!-- .row -->

</div><!-- .container -->

<?php
get_footer();
