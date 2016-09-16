<?php
/**
 * The template for displaying search forms in Lookbook
 *
 * @package Lookbook
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php _ex( 'Busqueda:', 'label', 'lookbook' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Buscar en el archivo 1x1', 'placeholder', 'lookbook' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	</label>
	<button type="submit" class="search-submit"><i class="fa fa-search"></i></button>
</form>
