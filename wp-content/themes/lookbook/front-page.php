<?php
/**
 * The frontpage template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Lookbook
 */

get_header(); ?>

	<div id="content" class="content-area">
		<main id="main" class="site-main" role="main">
<?php $cat_id = 3;
$latest_cat_post = new WP_Query( array('posts_per_page' => 1, 'category__in' => array($cat_id)));
if( $latest_cat_post->have_posts() ) : while( $latest_cat_post->have_posts() ) : $latest_cat_post->the_post();  ?>
<header class="entry-header">
<h1 class="entry-title hoy-en-1x1-home">Hoy en 1x1 <a href="<?php echo post_permalink(); ?> "><?php echo get_the_title(); ?></a></h1>	
</header>
<?php the_content(); ?>
	<div class="fotodeldia"><img src="<?php the_field('foto'); ?>" title="&copy; <?php the_field('autor'); ?>" /></div>
			<div class="fotodeldia-data bloque-left"><?php the_field('titulo'); ?> <?php the_field('serie'); ?><br>
			<?php the_field('autor'); ?><br>
			<?php the_field('lugar'); ?><br>
			<a href="<?php the_field('portafolio'); ?>" target="_blank"rel="nofollow">Ver portafolio</a>
			</div>
<div class="fotodeldia-data bloque-right">
			<?php if (function_exists('exifography_display_exif'))
					echo exifography_display_exif();
			?>
			</div>
			<div class=" bloque-center"><div class="wowlikes" id="wow"><?php printLikes(get_the_ID()); ?></div></div>

<?php endwhile; endif; ?>
		
		

		</main><!-- #main -->
	</div><!-- #content -->

<?php get_footer(); ?>