<?php
/*
* @package awps
*/

get_header(); ?>

<div class="container">

	<div class="row">

		<div class="col-sm-8">

			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

					<?php
					if (have_posts()) :

						if (is_home() && !is_front_page()) : ?>
							<header>
								<h1 class="page-title"><?php single_post_title(); ?></h1>
							</header>

						<?php
						endif;

						/* Start the Loop */
						while (have_posts()) : the_post();

							get_template_part('views/content/content', get_post_format());

						endwhile;

						the_posts_navigation();

					else:

						get_template_part('views/content/content', 'none');

					endif; ?>

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
