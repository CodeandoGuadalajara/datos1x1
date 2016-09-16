<?php
/**
 * @package Lookbook
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'grid' ); ?>>
	<div class="entry-content grid-item">
	<?php lookbook_thumbnail( $post->ID ); ?>
		<div class="post-meta">
			<div class="title-wrapper">
				<h1 class="image-title"><a href="<?php the_permalink(); ?>" class="featured-image-link" rel="bookmark"><?php the_title(); ?></a></h1>
				<?php
					if( has_post_thumbnail( $post->ID ) ) {
						echo  gpp_lookbook_add_to_link( get_post_thumbnail_id( $post->ID ) );
					}
				?>
			</div>
		</div>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
