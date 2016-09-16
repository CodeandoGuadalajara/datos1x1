<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Lookbook
 */

get_header(); ?>

	<?php if ( have_posts() ) : ?>

    <header class="entry-header">
        <h1 class="entry-title"><?php printf( __( 'Search Results for: %s', 'lookbook' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
    </header>

	<div id="content" class="content-area">

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>

			</div><!-- #content -->

			<?php lookbook_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>


<?php get_footer(); ?>