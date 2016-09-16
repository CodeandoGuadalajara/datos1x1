<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Lookbook
 */

get_header(); ?>

	<div id="content" class="content-area">
		<main id="main" class="site-main" role="main">
			
		
			
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

		<?php 
		if ( in_category( 'fotos' )) {
		?>	<div class="fotodeldia-data-special"><?php the_field('indicaciones_especiales'); ?></div>
			<div class="fotodeldia"><img src="<?php the_field('foto'); ?>" /></div>
			<div class="fotodeldia-data bloque-left"><?php the_field('titulo'); ?><br>
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


		<?php
		} elseif ( in_category( 'ensayos' )) {
			?>
			<div class="wowlikes" id="wow"><?php printLikes(get_the_ID()); ?></div>
			<?php
		} else {
			
		}
		?>

		<div class="clear"> </div> 

		<div class="fb-blabla">
		<span>Comentarios</span>
		<?php comments_template(); ?>
		</div> 


		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #content -->

<?php get_footer(); ?>