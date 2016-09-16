<?php
/*
Plugin Name: Annual Archive
Text Domain: anual-archive
Domain Path: /languages
Plugin URI: http://plugins.twinpictures.de/plugins/annual-archive/
Description: Display daily, weekly, monthly, yearly, postbypost and alpha archives with a sidebar widget or shortcode.
Version: 1.4.5
Author: Twinpictures
Author URI: http://www.twinpictures.de/
License: GPL2
*/

/**
 * Class WP_Plugin_Annual_Archive
 * @package WP_plugin
 * @category WordPress Plugins
 */

class WP_Plugin_Annual_Archive {

	/**
	 * Plugin vars
	 * @var string
	 */
	var $plugin_name = 'Annual Archive';
	var $version = '1.4.5';
	var $domain = 'anarch';

	/**
	 * Options page
	 * @var string
	 */
	var $plguin_options_page_title = 'Annunal Archive Options';
	var $plugin_options_menue_title = 'Annual Archive';
	var $plugin_options_slug = 'annual-archive-optons';

	/**
	 * Name of the options
	 * @var string
	 */
	var $options_name = 'WP_AnnualArchive_options';

	/**
	 * @var array
	 */
	var $options = array(
		'custom_css' => '',
	);

	/**
	 * PHP5 constructor
	 */
	function __construct() {
		// set option values
		$this->_set_options();

		// load text domain for translations
		load_plugin_textdomain( $this->domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// add actions
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_actions' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action('wp_head', array( $this, 'plugin_head_inject' ) );

		// add shortcode
		add_shortcode('archives', array($this, 'shortcode'));

		// Add shortcode support for widgets
		add_filter('widget_text', 'do_shortcode');
	}

	//plugin header inject
	function plugin_head_inject(){
		// custom css
		if( !empty( $this->options['custom_css'] ) ){
			echo "\n<style>\n";
			echo $this->options['custom_css'];
			echo "\n</style>\n";
		}
	}

	/**
	 * Callback admin_menu
	 */
	function admin_menu() {
		if ( function_exists( 'add_options_page' ) AND current_user_can( 'manage_options' ) ) {
			// add options page
			$options_page = add_options_page($this->plguin_options_page_title, $this->plugin_options_menue_title, 'manage_options', $this->plugin_options_slug, array( $this, 'options_page' ));
		}
	}

	/**
	 * Callback admin_init
	 */
	function admin_init() {
		// register settings
		register_setting( $this->domain, $this->options_name );
	}

	/**
	 * Callback shortcode
	 */
	function shortcode($atts, $content = null){
		extract(shortcode_atts(array(
			'type' => 'yearly',
			'limit' => '',
			'format' => 'html', //html, option, link
			'before' => '',
			'after' => '',
			'showcount' => '0',
			'tag' => 'ul',
			'order' => 'DESC',
			'select_text' => '',
		), $atts));

		if ($format == 'option') {
			if( !empty($select_text) ){
				$dtitle = $select_text;
			}
			else{
				$dtitle = __('Select Year', 'anual-archive');
				if ($type == 'monthly'){
					$dtitle = __('Select Month', 'anual-archive');
				}
				else if($type == 'weekly'){
					$dtitle = __('Select Week', 'anual-archive');
				}
				else if($type == 'daily'){
					$dtitle = __('Select Day', 'anual-archive');
				}
				else if($type == 'postbypost' || $type == 'alpha'){
					$dtitle = __('Select Post', 'anual-archive');
				}
			}
			$arc = '<select name="archive-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;"> <option value="">'.esc_attr($dtitle).'</option>';
			$arc .= wp_get_archives(array('type' => $type, 'limit' => $limit, 'format' => 'option', 'show_post_count' => $showcount, 'order' => $order, 'echo' => 0)).'</select>';
		} else {
			$arc = '<'.$tag.'>';
			$arc .= wp_get_archives(array('type' => $type, 'limit' => $limit, 'format' => $format, 'before' => $before, 'after' => $after, 'show_post_count' => $showcount, 'order' => $order, 'echo' => 0));
			$arc .= '</'.$tag.'>';
		}
		return $arc;
	}

	// Add link to options page from plugin list
	function plugin_actions($links) {
		$new_links = array();
		$new_links[] = '<a href="options-general.php?page='.$this->plugin_options_slug.'">' . __('Settings', 'anual-archive') . '</a>';
		return array_merge($new_links, $links);
	}

	/**
	 * Admin options page
	 */
	function options_page() {
		$like_it_arr = array(
						__('really tied the room together', 'anual-archive'),
						__('made you feel all warm and fuzzy on the inside', 'anual-archive'),
						__('restored your faith in humanity... even if only for a fleeting second', 'anual-archive'),
						__('rocked your world', 'provided a positive vision of future living', 'anual-archive'),
						__('inspired you to commit a random act of kindness', 'anual-archive'),
						__('encouraged more regular flossing of the teeth', 'anual-archive'),
						__('helped organize your life in the small ways that matter', 'anual-archive'),
						__('saved your minutes--if not tens of minutes--writing your own solution', 'anual-archive'),
						__('brightened your day... or darkened if if you are trying to sleep in', 'anual-archive'),
						__('caused you to dance a little jig of joy and joyousness', 'anual-archive'),
						__('inspired you to tweet a little @twinpictues social love', 'anual-archive'),
						__('tasted great, while also being less filling', 'anual-archive'),
						__('caused you to shout: "everybody spread love, give me some mo!"', 'anual-archive'),
						__('helped you keep the funk alive', 'anual-archive'),
						__('<a href="http://www.youtube.com/watch?v=dvQ28F5fOdU" target="_blank">soften hands while you do dishes</a>', 'anual-archive'),
						__('helped that little old lady <a href="http://www.youtube.com/watch?v=Ug75diEyiA0" target="_blank">find the beef</a>', 'anual-archive')
					);
	$rand_key = array_rand($like_it_arr);
	$like_it = $like_it_arr[$rand_key];
	?>
		<div class="wrap">
			<h2><?php echo $this->plugin_name; ?></h2>
		</div>

		<div class="postbox-container metabox-holder meta-box-sortables" style="width: 69%">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'anual-archive' ) ?>"><br/></div>
					<h3 class="handle"><?php _e( 'Annual Archive Settings', 'anual-archive' ) ?></h3>
					<div class="inside">
						<form method="post" action="options.php">
							<?php
								settings_fields( $this->domain );
								$this->_set_options();
								$options = $this->options;
							?>

							<fieldset class="options">
								<table class="form-table">
									<tr>
										<th><?php _e( 'Custom Style', 'anual-archive' ) ?>:</th>
										<td><label><textarea id="<?php echo $this->options_name ?>[custom_css]" name="<?php echo $this->options_name ?>[custom_css]" style="width: 100%; height: 150px;"><?php echo $options['custom_css']; ?></textarea>
											<br /><span class="description"><?php _e( 'Custom CSS style for <em>ultimate flexibility</em>', 'anual-archive' ) ?></span></label>
										</td>
									</tr>

									<tr>
										<th><?php _e( 'Go Pro', 'anual-archive' ) ?>:</th>
										<td>
											<p><?php printf(__( '%sArchive-Pro-Matic%s adds the ability to display archives by <strong>post type</strong>, <strong>custom post type</strong> and <strong>category</strong>.  In addition it comes with next-level support that alone is well worth the price of admission.', 'anual-archive' ), '<a href="http://plugins.twinpictures.de/premium-plugins/archive-pro-matic/?utm_source=annual-archive&utm_medium=plugin-settings-page&utm_content=archive-pro-matic&utm_campaign=archive-pro-level-up">', '</a>'); ?></p>
										</td>
									</tr>

									<tr>
										<th><?php _e( 'Free Advice', 'anual-archive' ) ?></th>
										<td>
											<p><?php _e( '<p>Congradulations! You have reach the very deapths of your Dashboard. This is probably least visited corner of your site, and yet here you are, reading this, hopeing to be enlightened or rewarded in some way.</p><p>Well, we hate to leave you hanging, all disapointed, so here is a little tip or two for you.</p> <p>Get back to work!</p> <p>The sooner you finish that task, check that box of your list, wrap your day up, the sooner you can get on with the more important things in life that matter.</p> <p>Things like: <ul><li>playing with your dog</li><li>calling your mother</li><li>changing the sheets on the bed</li><li>flossing your teeth</li><li>simply sipping a cool beverage in a hammock</li></ul><p>Now get on with it, there is a whole world out there to see!</p>', 'anual-archive' ); ?></p>
										</td>
									</tr>

								</table>
							</fieldset>

							<p class="submit" style="margin-bottom: 20px;">
								<input class="button-primary" type="submit" value="<?php _e( 'Save Changes', 'anual-archive' ) ?>" style="float: right;" />
							</p>
					</div>
				</div>
			</div>
		</div>

		<div class="postbox-container side metabox-holder meta-box-sortables" style="width:29%;">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'anual-archive' ) ?>"><br/></div>
					<h3 class="handle"><?php _e( 'About', 'anual-archive' ) ?></h3>
					<div class="inside">
						<h4><?php echo $this->plugin_name; ?> <?php _e('Version', 'anual-archive'); ?> <?php echo $this->version; ?></h4>
						<p><?php printf( __('Annual Archive widget extends the default WordPress Archive widget to allow daily, weekly, monthly, yearly, postbypost and alpha archives to be displayed.  Archives can be displyed in the sidebar using a widget&mdash;and even placed in a post or page by using a shortcode. A %scomplete listing of shortcode options and attribute demos%s are available, as well as %sfree, open-source community support%s. The Annual Archive widget&mdash;A better archive widget.  Yup, that is pretty much it.  Oh, one more thing: The plugin can be translated into any language using our %scommunity translation tool%s. Ok, that is really it.', 'anual-archive') ,'<a href="http://plugins.twinpictures.de/plugins/annual-archive/documentation/">','</a>', '<a href="http://wordpress.org/support/plugin/anual-archive">', '</a>', '<a href="http://translate.twinpictures.de/projects/anual-archive">', '</a>') ?></p>
						<ul>
							<li>
								<?php printf( __( '%sDetailed documentation%s, complete with working demonstrations of all shortcode attributes, is available for your instructional enjoyment.', 'anual-archive'), '<a href="http://plugins.twinpictures.de/plugins/annual-archive/documentation/" target="_blank">', '</a>'); ?>
							</li>
							<li><?php printf( __('If this plugin %s, please consider %ssharing your story%s with others.', 'anual-archive'), $like_it, '<a href="http://www.facebook.com/twinpictures" target="_blank">', '</a>' ) ?></li>
							<li><?php printf( __('Your %sreviews%s, %sbug-reports, feedback%s and %scocktail recipes%s are always welcomed.', 'anual-archive'), '<a href="http://wordpress.org/support/view/plugin-reviews/anual-archive">', '</a>', '<a href="http://wordpress.org/support/plugin/anual-archive">', '</a>', '<a href="http://www.facebook.com/twinpictures">', '</a>'); ?></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>

		<div class="postbox-container side metabox-holder meta-box-sortables" style="width:29%;">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle' ) ?>"><br/></div>
					<h3 class="handle"><?php _e( 'Level Up!' ) ?></h3>
					<div class="inside">
						<p><?php printf(__( '%sArchive-Pro-Matic%s is our premium plugin that adds the ability to display archives by <strong>post type</strong> or <strong>category</strong>', 'anual-archive' ), '<a href="http://plugins.twinpictures.de/premium-plugins/archive-pro-matic/?utm_source=annual-archive&utm_medium=plugin-settings-page&utm_content=archive-pro-matic&utm_campaign=archive-pro-level-up">', '</a>'); ?></p>
						<!--<p style="padding: 5px; border: 1px dashed #cccc66; background: #EEE;"><strong>Special Offer:</strong> <a href="http://plugins.twinpictures.de/premium-plugins/archive-pro-matic/?utm_source=annual-archive&utm_medium=plugin-settings-page&utm_content=archive-pro-matic&utm_campaign=archive-pro-may-the-forth">Update to Archive-Pro-Matic</a> with discount code: <strong>MAYTHEFORTH</strong> on or before May 4th, 2015 and get a 15% discount. Why? Because Star Wars, that's why.</p>-->
						<h4><?php _e('Reasons To Go Pro', 'anual-archive'); ?></h4>
						<ol>
							<li><?php _e('I am an advanced user and want/need advanced features', 'anual-archive'); ?></li>
							<li><?php _e('Annual Archive was just what I needed. Here, have some money.', 'anual-archive'); ?></li>
							<!--<li>Special Offer: May the forth be with you.</li>->
						</ol>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	<?php
	}

	/**
	 * Set options from save values or defaults
	 */
	function _set_options() {
		// set options
		$saved_options = get_option( $this->options_name );

		// backwards compatible (old values)
		if ( empty( $saved_options ) ) {
			$saved_options = get_option( $this->domain . 'options' );
		}

		// set all options
		if ( ! empty( $saved_options ) ) {
			foreach ( $this->options AS $key => $option ) {
				$this->options[ $key ] = ( empty( $saved_options[ $key ] ) ) ? '' : $saved_options[ $key ];
			}
		}
	}

} // end class WP_Plugin_Template

/**
 * Create instance
 */
$WP_Plugin_Annual_Archive = new WP_Plugin_Annual_Archive;


//Widget
class Annual_Archive_Widget extends WP_Widget {
    /** constructor */
	function __construct() {

		$widget_ops = array(
			'classname'		=> 'Annual_Archive_Widget',
			'description'	=> __( 'Display daily, weekly, monthly or annual archives with a sidebar widget or shortcode', 'anual-archive' )
		);

		parent::__construct( 'Annual_Archive_Widget', __( 'Annual Archive', 'anual-archive' ), $widget_ops );

	}

    /** Widget */
    function widget($args, $instance) {
	//$options = get_option('WP_AnnualArchive_options');
	extract( $args );

	$format = empty($instance['format']) ? 'html' : apply_filters('widget_format', $instance['format']);
	$type = empty($instance['type']) ? 'yearly' : apply_filters('widget_type', $instance['type']);
	$before = empty($instance['before']) ? '' : apply_filters('widget_before', $instance['before']);
	$after = empty($instance['after']) ? '' : apply_filters('widget_after', $instance['after']);
	$limit = apply_filters('widget_limit', $instance['limit']);
	$title = apply_filters('widget_title', empty($instance['title']) ? __('Annual Archive', 'anual-archive') : $instance['title'], $instance, $this->id_base);
	$count = empty($instance['count']) ? 0 : $instance['count'];
	$order = empty($instance['order']) ? 'DESC' : apply_filters('widget_order', $instance['order']);
	$select_text = empty($instance['select_text']) ? '' : apply_filters('widget_slelect_text', $instance['select_text']);

	echo $before_widget;
	if ( $title )
		echo $before_title . $title . $after_title;

	if ($format == 'option') {
		if($select_text){
			$dtitle = $select_text;
		}
		else{
			$dtitle = __('Select Year', 'anual-archive');
			if ($type == 'monthly'){
				$dtitle = __('Select Month', 'anual-archive');
			}
			else if($type == 'weekly'){
				$dtitle = __('Select Week', 'anual-archive');
			}
			else if($type == 'daily'){
				$dtitle = __('Select Day', 'anual-archive');
			}
			else if($type == 'postbypost' || $type == 'alpha'){
				$dtitle = __('Select Post', 'anual-archive');
			}
		}
	?>
	<select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'> <option value=""><?php echo esc_attr(__($dtitle, 'anual-archive')); ?></option> <?php wp_get_archives(apply_filters('widget_archive_dropdown_args', array('type' => $type, 'format' => 'option', 'show_post_count' => $count, 'limit' => $limit, 'order' => $order))); ?> </select>
	<?php
	} else {
	?>
	<ul>
	<?php wp_get_archives(apply_filters('widget_archive_args', array('type' => $type, 'limit' => $limit, 'format' => $format, 'before' => $before, 'after' => $after, 'show_post_count' => $count, 'order' => $order))); ?>
	</ul>
	<?php
	}

	echo $after_widget;
    }

    /** Update **/
    function update($new_instance, $old_instance) {
		$instance = array_merge($old_instance, $new_instance);
		$instance['count'] = $new_instance['count'];
		return $instance;
    }

    /** Form **/
    function form($instance) {
		$title = empty($instance['title']) ? '' : stripslashes($instance['title']);
		$count = empty($instance['count']) ? 0 : $instance['count'];
		$format = empty($instance['format']) ? '' : stripslashes($instance['format']);
		$before = empty($instance['before']) ? '' : stripslashes($instance['before']);
		$after = empty($instance['after']) ? '' : stripslashes($instance['after']);
		$type = empty($instance['type']) ? '' : strip_tags($instance['type']);
		$limit = empty($instance['limit']) ? '' : stripslashes($instance['limit']);
		$order = empty($instance['order']) ? 'DESC' : stripslashes($instance['order']);
		$select_text = empty($instance['select_text']) ? '' : stripslashes($instance['select_text']);
        ?>

        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','anual-archive'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
	<p><label for="<?php echo $this->get_field_id('count'); ?>"><input type="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" value="1" <?php checked( $count, 1 ); ?>/>&nbsp;&nbsp;<?php _e('Show post counts', 'anual-archive'); ?></label></p>
	<p><label><?php _e('Archive type:', 'anual-archive'); ?> <select name="<?php echo $this->get_field_name('type'); ?>" id="<?php echo $this->get_field_id('type'); ?>">
		<?php
		$types_arr = array(
			'daily' => __('Daily', 'anual-archive'),
			'weekly' => __('Weekly', 'anual-archive'),
			'monthly' => __('Monthly', 'anual-archive'),
			'yearly' => __('Yearly', 'anual-archive'),
			'postbypost' => __('Post By Post', 'anual-archive'),
			'alpha' => __('Alpha', 'anual-archive')
		);
		foreach($types_arr as $key => $value){
			$selected = '';
			if($key == $type || (!$type && $key == 'yearly')){
				$selected = 'SELECTED';
			}
			echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
		}
		?>
		</select></lable>
	</p>

	<p><label><?php _e('Format:', 'anual-archive'); ?> <select name="<?php echo $this->get_field_name('format'); ?>" id="<?php echo $this->get_field_id('format'); ?>">
		<?php
		$format_arr = array(
			'html' => __('HTML', 'anual-archive'),
			'option' => __('Option', 'anual-archive'),
			'link' => __('Link', 'anual-archive'),
			'custom' => __('Custom', 'anual-archive')
		);
		foreach($format_arr as $key => $value){
			$selected = '';
			if($key == $format || (!$format && $key == 'html')){
				$selected = 'SELECTED';
			}
			echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
		}
		?>
		</select></lable><br/>
		<span class="description"><a href="http://codex.wordpress.org/Function_Reference/wp_get_archives#Parameters" target="_blank"><?php _e('Format details', 'anual-archive'); ?></a></span>
	</p>
	<p><label for="<?php echo $this->get_field_id('before'); ?>"><?php _e('Text Before Link:', 'anual-archive'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('before'); ?>" name="<?php echo $this->get_field_name('before'); ?>" type="text" value="<?php echo $before; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('after'); ?>"><?php _e('Text After Link:', 'anual-archive'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('after'); ?>" name="<?php echo $this->get_field_name('after'); ?>" type="text" value="<?php echo $after; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('select_text'); ?>"><?php _e('Select Text:', 'anual-archive'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('select_text'); ?>" name="<?php echo $this->get_field_name('select_text'); ?>" type="text" value="<?php echo $select_text; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Number of archives to display:', 'anual-archive'); ?></label> <input class="widefat" style="width: 50px;" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Sort order:', 'anual-archive'); ?></label> <input name="<?php echo $this->get_field_name('order'); ?>" type="radio" value="DESC" <?php checked( $order, 'DESC' ); ?> /> DESC <input name="<?php echo $this->get_field_name('order'); ?>" type="radio" value="ASC" <?php checked( $order, 'ASC' ); ?>  />  ASC</p>
	<?php
    }
} // class Annual_Archive_Widget

// register Annual_Archive_Widget
function anarch_register_widget() {
	register_widget( 'Annual_Archive_Widget' );
}
add_action( 'widgets_init', 'anarch_register_widget' );

?>
