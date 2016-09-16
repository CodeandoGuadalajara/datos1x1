<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Lookbook
 */
?>

		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="uno-buscador-footer"><?php get_search_form( ); ?></div>
			<?php get_sidebar(); ?>
			<div class="site-info">
				&copy; <?php the_date('Y'); ?> <a href="http://generador.mx/" title="Generador Proyectos Fotográficos" target="_blank">Generador Proyectos Fotográficos</a> | <a href="http://proyecto1x1.com/aviso-de-privacidad/" title="Aviso de privacidad">Aviso de privacidad</a>
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	</div><!-- #page -->
</div><!-- #content -->
</div>
<?php wp_footer(); ?>


</body>
</html>