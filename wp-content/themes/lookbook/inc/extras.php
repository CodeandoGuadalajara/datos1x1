<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Lookbook
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function lookbook_page_menu_args( $args ) {
	//$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'lookbook_page_menu_args' );

add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function lookbook_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'lookbook_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function lookbook_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() ) {
		return $title;
	}

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'lookbook' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'lookbook_wp_title', 10, 2 );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function lookbook_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'lookbook_setup_author' );

/**
 * Set the Featured Image or Placeholder
 */
function lookbook_thumbnail( $post_id ) {
	if ( has_post_thumbnail( $post_id ) ) {
  		echo get_the_post_thumbnail( $post_id, 'thumbnail' );
	} else {
		echo '<img src="' . get_template_directory_uri() . '/images/placeholder.png" />';
	}
}

/**
 * Header image styling
 *
 * @since Lookbook 1.0
 */
function lookbook_header_image() {
	$header_image = get_header_image();

	if ( ! empty( $header_image ) ) {
		echo 'style="background-image:url(' . $header_image . ');height:' . get_custom_header()->height . 'px;"';
	}
}

/**
* Get theme version number from WP_Theme object (cached)
*
* @since Lookbook 1.0
*/
function lookbook_get_theme_version() {
	$lookbook_theme_file = get_template_directory() . '/style.css';
	$lookbook_theme = new WP_Theme( basename( dirname( $lookbook_theme_file ) ), dirname( dirname( $lookbook_theme_file ) ) );
	return $lookbook_theme->get( 'Version' );
}


/**
* Replace Gallery Shortcode
*
* @since Lookbook 1.0
*/
remove_shortcode( 'gallery', 'gallery_shortcode' );
add_shortcode( 'gallery', 'lookbook_gallery_shortcode' );
function lookbook_gallery_shortcode($attr) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'link'       => ''
	), $attr, 'gallery'));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$icontag = tag_escape($icontag);
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) )
		$itemtag = 'dl';
	if ( ! isset( $valid_tags[ $captiontag ] ) )
		$captiontag = 'dd';
	if ( ! isset( $valid_tags[ $icontag ] ) )
		$icontag = 'dt';

	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
			/* see gallery_shortcode() in wp-includes/media.php */
		</style>";
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		//$attach_url = wp_get_attachment_url( $id );
		$attach_url =  wp_get_attachment_image_src( $id, 'large' );
		$image_output = wp_get_attachment_link( $id, $size, true, false );

		$image_meta  = wp_get_attachment_metadata( $id );
		$orientation = '';
		if ( isset( $image_meta['height'], $image_meta['width'] ) )
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

		$output .= "<{$itemtag} class='gallery-item grid'>";
		$output .= "
			<{$icontag} class='grid-item gallery-icon {$orientation}'>";
		$output .= $image_output;
		$output .= '<div class="post-meta">
			<div class="title-wrapper">';
		$output .= '<h1 class="image-title"><a href="'.$attach_url[0].'" class="featured-image-link">';
		if ( $captiontag && trim($attachment->post_excerpt) ) {
					$output .= "
						<{$captiontag} class='wp-caption-text gallery-caption'>
						" . wptexturize($attachment->post_excerpt) . "
						</{$captiontag}>";
		} else {
			$output .= "&nbsp;";
		}
		$output .= "</a></h1>";
		$output .= gpp_lookbook_add_to_link( $id );
		$output .= "</div></div>";
		$output .= "</{$icontag}>";

		$output .= "</{$itemtag}>";
		/*if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= '<br style="clear: both" />';*/
	}

	$output .= "
			<br style='clear: both;' />
		</div>\n";

	return $output;
}

/**
 * Check if Sell Media is active plugin in options array
 *
 * @since Lookbook 1.0
 */
function lookbook_sell_media_check() {
	$plugins = get_option( 'active_plugins' );
	if ( in_array ( 'sell-media/sell-media.php', $plugins ) )
		return true;
}