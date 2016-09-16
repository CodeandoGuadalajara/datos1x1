<?php
/*
Plugin Name: Post List
Plugin URI: http://www.iwebrays.com/
Description: This plugin can be used to fetch a list of all posts from specific category using shortcode.
Version: 1.1
Author: vijaybidla
Author URI: http://www.iwebrays.com/
*/

add_shortcode('postlist', 'postlist');

function postlist($attr){
	
	extract(shortcode_atts(array(
		'cat' => '',
		'tags' => '',
		'number' => '-1',
		'requesttype' => '0',
	), $attr));

	if($requesttype == '1'){
		$tags_array = explode ( ',', $tags );
		$size = sizeof($tags_array) - 1;
		$tag = "";
		if($size == 0){
			$tag = $tags_array[0];
		}elseif($size >= 1){
			for($i = 0; $i < $size; $i++){
				$tag .= $tags_array[$i] . "+";
			}
			$tag .= $tags_array[$size];
		}
	}else{
		$tag = $tags;
	}

	$query = new WP_Query();
	$query->query("category_name=$cat&tag=$tag&showposts=$number");
	global $post;
	if ( $query->have_posts() ) :
		$out = "<ul>";
			while ( $query->have_posts() ) : $query->the_post();
				$out .= "<li><a href='".get_permalink($post->ID)."'>" .get_the_title($post->ID). "</a></li>";
			endwhile;
		$out .= "</ul>";

		return $out;
	else:
		return "<p>No Post found.</p>";
	endif;
	
	wp_reset_query();

}