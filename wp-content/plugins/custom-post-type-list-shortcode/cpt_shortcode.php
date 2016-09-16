<?php
/*
	Plugin Name: Custom Post Type Shortcode
	Plugin URI: http://blackbirdi.com/blog
	Description: List custom post type posts using shortcode with any page or post. For documentation on how to use this plugin please visit the <a href="http://blog.blackbirdi.com" title="Blackbird Interactive">Blackbird Interactive Blog</a>
	Version: 1.4.4
	Author: Blackbird Interactive
	Author URI: http://blackbirdi.com
	License: GPL2
*/
	
/** load main class */
require_once(dirname(__FILE__).'/lib/class.main.php');

/** initalize the plugin */
add_action('init',array('cpt_shortcode','initialize'));