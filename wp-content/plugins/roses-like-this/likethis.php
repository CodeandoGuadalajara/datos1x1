<?php
/*
Plugin Name: Like This
Plugin URI: http://r.osey.me/code/likeThis
Description: Integrates a "Like This" option for posts. For visitors who want to let the author know that they enjoyed the post, but don't want to go to the effort of commenting.
Version: 1.6.2
Author: Rose Pritchard
Author URI: http://lifeasrose.ca
License: GPL2

Copyright 2011  Rose Pritchard  (email : rose@r.osey.me)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
include(WP_PLUGIN_DIR . "/roses-like-this/options.php");
include(WP_PLUGIN_DIR . "/roses-like-this/widget.php");
include(WP_PLUGIN_DIR . "/roses-like-this/manage_posts.php");

function likeThis($post_id, $action = 'get', $direction = 1) {

  if (!is_numeric($post_id)) {
    error_log("Error: Value submitted for post_id was not numeric");
    return;
  } //if

  switch ($action) {

    case 'get':
      $data = get_post_meta($post_id, '_likes');
      if (!$data) {
        $data = array(
          '0' => 0
        );
      }
      if (!is_numeric($data[0])) {
        $data[0] = 0;
        add_post_meta($post_id, '_likes', '0', true);
      } //if

      return $data[0];
      break;

    case 'update':
      $currentValue = get_post_meta($post_id, '_likes');

      if (!$currentValue || !is_numeric($currentValue[0])) {
        $currentValue = array("0" => 0);
        add_post_meta($post_id, '_likes', '1', true);
      } //if

      $currentValue[0] += $direction;
      if($currentValue[0] < 0) {$currentValue[0] = 0;}
      update_post_meta($post_id, '_likes', $currentValue[0]);

      break;

  } //switch

} //likeThis

function printLikes($post_id) {
  print generateLikeString($post_id, isset($_COOKIE["like_" . $post_id]));
}

function generateLikeString($post_id, $value) {
  $likes = likeThis($post_id);

  $who = str_replace("%", $likes, get_option('some_likes'));

  if ($likes == 1) {
    $who = str_replace("%", $likes, get_option('one_like'));
  } //if

  if ($likes == 0) {
    $who = str_replace("%", $likes, get_option('no_likes'));
  }

  if ($value) {
    return '<a href="#" class="likeThis done" id="like-' . $post_id . '"  data-post-id="' . $post_id . '">' . $who . '</a>';
  } //if

  return '<a href="#" class="likeThis" id="like-' . $post_id . '" data-post-id="' . $post_id . '">' . $who . '</a>';
}

function likeThisSetUpPostLikes($post_id) {
  if (!is_numeric($post_id)) {
    error_log("Error: Value submitted for post_id was not numeric");
    return;
  } //if

  add_post_meta($post_id, '_likes', '0', true);
} //setUpPost

function likeThisCheckHeaders() {
  if (isset($_POST["likepost"])) {
    $id        = $_POST["likepost"];
    $direction = $_POST["direction"];
    likeThis($id, 'update', $direction);
    $resp = array(
      "element" => generateLikeString($id, $direction > 0),
      "add" => $direction == 1,
      "id" => $id
    );
    header('Content-type: application/json');
    $out = json_encode($resp);
    die(print($out));
  } //if
} //checkHeaders


function likeThisJsIncludes() {
  wp_enqueue_script('jquery');
  wp_register_script('likesScript', WP_PLUGIN_URL . '/roses-like-this/likesScript.js');
  wp_localize_script('likesScript', 'like_this_ajax_object', array(
    'ajax_url' => admin_url('admin-ajax.php')
  ));
  wp_enqueue_script('likesScript', array(
    'jquery'
  ));
} //jsIncludes

add_action('publish_post', 'likeThisSetUpPostLikes');
add_action('wp_enqueue_scripts', 'likeThisJsIncludes');

if (is_admin()) {
  add_action('wp_ajax_like_this_like_post', 'likeThisCheckHeaders');
  add_action('wp_ajax_nopriv_like_this_like_post', 'likeThisCheckHeaders');
}
?>
