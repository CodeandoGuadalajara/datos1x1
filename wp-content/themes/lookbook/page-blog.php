<?php
/*
Template Name: Blog
 */

/**
 *
 * @package Lookbook
 * @since Lookbook 1.0
 */

get_header(); ?>

<div id="content" class="content-area">

    <?php

		$theme_options = get_option( gpp_get_current_theme_id() . '_options' );
		$blogcat = "";
		if( isset( $theme_options['blog_categories'] ) && "" != $theme_options['blog_categories'] ) {
			foreach( $theme_options['blog_categories'] as $catid ) {
				$blogcat .= get_cat_ID( $catid ) . ",";
			}
		}
		$blogcat = rtrim( $blogcat, "," );
        global $paged, $more;
        $more = 0;
		if ( get_query_var('paged') ) {
			$paged = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$paged = get_query_var('page');
		} else {
			$paged = 1;
		}
        $args = array(
            'paged' => $paged,
			'cat' => $blogcat
        );

        $temp = $wp_query;
        $wp_query = null;

        $wp_query = new WP_Query();
        $wp_query->query( $args );

        ?>
        <div id="content-main" role="main">
            <div id="content-inner">
            <?php if ( have_posts() ) : ?>
			<header id="page-header" class="page-header">
            	<h1 class="entry-title"><?php the_title(); ?></h1>
			</header>
                <?php while ( $wp_query -> have_posts() ) : $wp_query -> the_post(); ?>
                    <?php $do_not_duplicate = $post -> ID; ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php esc_attr( sprintf( __( 'Permalink to %s', 'lookbook' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
						</header>
						<div class="entry-content">
							<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'lookbook' ) ); ?>
                            <?php
                                wp_link_pages( array(
                                    'before' => '<div class="page-links">' . __( 'Pages:', 'lookbook' ),
                                    'after'  => '</div>',
                                ) );
                            ?>
						</div>
                        <footer class="entry-meta">
                            <?php lookbook_posted_on(); ?>.
                            <?php
                                /* translators: used between list items, there is a space after the comma */
                                $category_list = get_the_category_list( __( ', ', 'lookbook' ) );

                                /* translators: used between list items, there is a space after the comma */
                                $tag_list = get_the_tag_list( '', __( ', ', 'lookbook' ) );

                                if ( ! lookbook_categorized_blog() ) {
                                    // This blog only has 1 category so we just need to worry about tags in the meta text
                                    if ( '' != $tag_list ) {
                                        $meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'lookbook' );
                                    } else {
                                        $meta_text = __( 'Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'lookbook' );
                                    }

                                } else {
                                    // But this blog has loads of categories so we should probably display them here
                                    if ( '' != $tag_list ) {
                                        $meta_text = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'lookbook' );
                                    } else {
                                        $meta_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'lookbook' );
                                    }

                                } // end check for categories on this blog

                                printf(
                                    $meta_text,
                                    $category_list,
                                    $tag_list,
                                    get_permalink()
                                );
                            ?>

                            <?php edit_post_link( __( 'Edit', 'lookbook' ), '<span class="edit-link">', '</span>' ); ?>
                        </footer><!-- .entry-meta -->


					</article>

                <?php endwhile; lookbook_paging_nav(); wp_reset_query(); $wp_query = $temp; ?>

                <?php else : ?>

                <article id="post-0" class="post no-results not-found">
                    <header class="entry-header">
                        <h1 class="entry-title"><?php _e( 'Nothing Found', 'lookbook' ); ?></h1>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'lookbook' ); ?></p>
                        <?php get_search_form(); ?>
                    </div><!-- .entry-content -->
                </article><!-- #post-0 -->

            <?php endif; ?>
        </div><!-- .content -->
    </div><!-- .inner -->
</div><!-- #content -->

<?php get_footer(); ?>