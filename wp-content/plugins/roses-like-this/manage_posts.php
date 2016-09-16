<?php
add_action('manage_posts_custom_column', 'likeThisDisplayPostLikes', 10, 2);
function likeThisDisplayPostLikes($column, $post_id) {
  $likes = get_post_meta($post_id, "_likes");
  if ($likes) {
    echo $likes[0];
  } else {
    echo 0;
  }
}

add_filter('manage_posts_columns', 'likeThisAddColumns');
function likeThisAddColumns($columns) {
  return array_merge($columns, array(
    'likes' => __("Likes", "like_this")
  ));
}

add_filter('manage_edit-post_sortable_columns', 'likeThisSortableLikes');
function likeThisSortableLikes($columns) {
  $columns['likes'] = '_likes';
  return $columns;
}

add_filter('request', 'likeThisOrderBy');
function likeThisOrderBy($vars) {
  if (isset($vars['orderby']) && '_likes' == $vars['orderby']) {
    $vars = array_merge($vars, array(
      'meta_key' => '_likes',
      'orderby' => 'meta_value_num'
    ));
  }

  return $vars;
}
?>
