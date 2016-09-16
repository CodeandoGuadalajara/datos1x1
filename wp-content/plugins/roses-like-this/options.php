<?php
// create custom plugin settings menu
add_action('admin_menu', 'like_this_create_menu');
add_action('activated_plugin', 'like_this_add_settings');
add_action('admin_init', 'register_mysettings');

function like_this_create_menu() {
  //create new top-level menu
  add_options_page(__('Like This Plugin Settings', 'like_this'), __('Like This Settings', 'like_this'), 'administrator', 'like-this-settings', 'like_this_settings_page');
}

function like_this_add_settings() {
  add_option("no_likes", "Like this");
  add_option("one_like", "% person likes this");
  add_option("some_likes", "% people like this");
}

function register_mysettings() {
  //register our settings
  register_setting('like-this-settings-group', 'no_likes');
  register_setting('like-this-settings-group', 'one_like');
  register_setting('like-this-settings-group', 'some_likes');
}

function like_this_settings_page() {
?>
<div class="wrap">
<h2>Like This</h2>

<form method="post" action="options.php">
    <?php
  settings_fields('like-this-settings-group');
?>
    <p><?php
  print __("Enter like this text for no likes, one like, and many likes.", "like_this");
?>

     <?php
  print __("The", "like_this");
?> <strong>%</strong>
     <?php
  print __("symbol will be replaced by the number of likes.", "like_this");
?></p>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php
  print __("Text for no likes", "like_this");
?></th>
        <td><input type="text" name="no_likes" value="<?php
  echo get_option('no_likes');
?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php
  print __("Text for one like", "like_this");
?></th>
        <td><input type="text" name="one_like" value="<?php
  echo get_option('one_like');
?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php
  print __("Text for many likes", "like_this");
?></th>
        <td><input type="text" name="some_likes" value="<?php
  echo get_option('some_likes');
?>" /></td>
        </tr>
    </table>

    <?php
  submit_button();
?>

</form>
</div>
<?php
}
?>
