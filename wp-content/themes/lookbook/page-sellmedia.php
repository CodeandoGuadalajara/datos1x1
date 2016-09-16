
<?php
/*
Template Name: Sell Media Items
*/
get_header(); ?>
<?php if( lookbook_sell_media_check() == true ) { ?>
    <?php
        global $paged;
    	if ( get_query_var('paged') ) {
    		$paged = get_query_var('paged');
    	} elseif ( get_query_var('page') ) {
    		$paged = get_query_var('page');
    	} else {
    		$paged = 1;
    	}
        $args = array(
            'post_type' => 'sell_media_item',
            'post_status' => 'publish',
            'paged' => $paged
        );

        $wp_query = null;

        $wp_query = new WP_Query();
        $wp_query->query( $args );
    ?>

    <?php if ( $wp_query->have_posts() ) : ?>

    <header class="entry-header">
        <h1 class="entry-title"><?php the_title(); ?></h1>
    </header>

    <div id="content" class="content-area">

        <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

            <article class="grid portfolio">
                <?php
                //Get Post Attachment ID
                $sell_media_attachment_id = get_post_meta( $post->ID, '_sell_media_attachment_id', true );
                if ( $sell_media_attachment_id ){
                    $attachment_id = $sell_media_attachment_id;
                } else {
                    $attachment_id = get_post_thumbnail_id( $post->ID );
                }
                ?>

                <div class="grid-item">
                <?php sell_media_item_icon( $attachment_id ); ?>
                    <div class="post-meta">
                        <div class="title-wrapper">
                            <h1 class="image-title"><a href="<?php the_permalink(); ?>" class="featured-image-link" rel="bookmark"><?php the_title(); ?></a></h1>
                            <p class="sell-media-buy lookbook"><?php sell_media_item_buy_button( $post->ID, 'text', '<span class="genericon genericon-cart"></span><span class="sell-media-buy-text">Buy</span>' ); ?></p>
                        </div>
                    </div>
                </div><!-- .entry-content -->

            </article>
        <?php endwhile;  wp_reset_query(); $args = null; ?>

        </div><!-- #content -->

        <?php sell_media_pagination_filter(); ?>

    <?php else : ?>

        <?php get_template_part( 'content', 'none' ); ?>

    <?php endif; ?>
<?php } ?>
<?php get_footer(); ?>
