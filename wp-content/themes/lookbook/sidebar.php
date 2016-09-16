<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Lookbook
 */
?>
<?php do_action( 'before_sidebar' ); ?>
<?php
	/* The footer widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if (  is_active_sidebar( 'footer-1' ) ||  is_active_sidebar( 'footer-2' ) ||  is_active_sidebar( 'footer-3' ) ) :
	// If we get this far, we have widgets. Let do this.
?>
<div id="footer-widgets">
	<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
	<div id="first" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'footer-1' ); ?>
	</div><!-- #first .widget-area -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
	<div id="second" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'footer-2' ); ?>
	</div><!-- #second .widget-area -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
	<div id="third" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'footer-3' ); ?>
	</div><!-- #third .widget-area -->
	<?php endif; ?>
</div><!-- #footer-widgets -->

<?php endif; ?>

