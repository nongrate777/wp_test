<?php

/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package unite
 */
global $post;

$realestate_posts = get_posts([
	'posts_per_page' => 6,
	'post_type' => 'flats',
]);


get_header(); ?>

<div id="primary" class="content-area col-sm-12 col-md-8">
	<main id="main" class="site-main" role="main">

		<?php if (have_posts()) : ?>
			<?php

			foreach ($realestate_posts as $post) {
				setup_postdata($post);
			?>
				<div class="col-sm-12 col-md-6 lighting">
					<?php
					get_template_part('content-main', get_post_format());
					?>
				</div>
			<?php
			}
			wp_reset_postdata(); ?>



		<?php endif; ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>