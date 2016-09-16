<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Lookbook
 */

get_header(); ?>

	<?php if ( have_posts() ) : ?>

        <header class="entry-header">
            <h1 class="entry-title">
                <?php
                    if ( is_category() ) :
                        single_cat_title();

                    elseif ( is_tag() ) :
                        single_tag_title();

                    elseif ( is_author() ) :
                        printf( __( 'Author: %s', 'lookbook' ), '<span class="vcard">' . get_the_author() . '</span>' );

                    elseif ( is_day() ) :
                        printf( __( 'Day: %s', 'lookbook' ), '<span>' . get_the_date() . '</span>' );

                    elseif ( is_month() ) :
                        printf( __( 'Month: %s', 'lookbook' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'lookbook' ) ) . '</span>' );

                    elseif ( is_year() ) :
                        printf( __( 'Year: %s', 'lookbook' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'lookbook' ) ) . '</span>' );

                    elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
                        _e( 'Asides', 'lookbook' );

                    elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
                        _e( 'Galleries', 'lookbook');

                    elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
                        _e( 'Images', 'lookbook');

                    elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
                        _e( 'Videos', 'lookbook' );

                    elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
                        _e( 'Quotes', 'lookbook' );

                    elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
                        _e( 'Links', 'lookbook' );

                    elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
                        _e( 'Statuses', 'lookbook' );

                    elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
                        _e( 'Audios', 'lookbook' );

                    elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
                        _e( 'Chats', 'lookbook' );

                    else :
                        _e( 'Archives', 'lookbook' );

                    endif;
                ?>
            </h1>
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

        

        <?php
            global $wp_query;
            $big = 99999999;
            echo paginate_links(array(
                'base' => str_replace($big, '%#%', get_pagenum_link($big)),
                'format' => '?paged=%#%',
                'total' => $wp_query->max_num_pages,
                'current' => max(1, get_query_var('paged')),
                'show_all' => false,
                'end_size' => 2,
                'mid_size' => 3,
                'prev_next' => true,
                'prev_text' => '',
                'next_text' => '',
                'type' => 'list'
            ));
        ?>

    <?php else : ?>

        <?php get_template_part( 'content', 'none' ); ?>

    <?php endif; ?>

<?php get_footer(); ?>
