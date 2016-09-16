<?php
/**
 * Lookbook functions and definitions
 *
 * @package Lookbook
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1280; /* pixels */
}

if ( ! function_exists( 'lookbook_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function lookbook_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Lookbook, use a find and replace
	 * to change 'lookbook' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'lookbook', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	update_option( 'thumbnail_size_w', 640 );
	update_option( 'thumbnail_size_h', '' );
	update_option( 'thumbnail_crop', false );
	update_option( 'medium_size_w', 640, true );
	update_option( 'medium_size_h', '', true );
	update_option( 'large_size_w', 1280, true );
	update_option( 'large_size_h', '', true );
	add_image_size( 'sell_media_item', 640, '', true ); // sell media image size

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'lookbook' ),
	) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'lookbook_custom_background_args', array(
		'default-color' => 'eeeeee',
		'default-image' => '',
	) ) );
}
endif; // lookbook_setup
add_action( 'after_setup_theme', 'lookbook_setup' );

/**
 * Register widgetized area.
 */
function lookbook_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Footer Left', 'lookbook' ),
		'id' => 'footer-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
	register_sidebar( array(
		'name' => __( 'Footer Center', 'lookbook' ),
		'id' => 'footer-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
	register_sidebar( array(
		'name' => __( 'Footer Right', 'lookbook' ),
		'id' => 'footer-3',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', 'lookbook_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function lookbook_scripts() {
	$theme_options = get_option( gpp_get_current_theme_id() . '_options' );

	wp_enqueue_style( 'lookbook-genericons', get_template_directory_uri() . '/images/genericons/genericons.css', '', lookbook_get_theme_version() );
	wp_enqueue_style( 'lookbook-magnific-popup', get_template_directory_uri() . '/js/magnific/magnific-popup.css', '', lookbook_get_theme_version() );
	wp_enqueue_style( 'lookbook-style', get_stylesheet_uri(), '', lookbook_get_theme_version() );
	wp_enqueue_script( 'lookbook-navigation', get_template_directory_uri() . '/js/navigation.js', array(), lookbook_get_theme_version(), true );
	wp_enqueue_script( 'lookbook-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), lookbook_get_theme_version(), true );
	wp_enqueue_script( 'lookbook-magnific-popup', get_template_directory_uri() . '/js/magnific/jquery.magnific-popup.js', array( 'jquery' ), lookbook_get_theme_version(), true );
	wp_enqueue_script( 'lookbook-mainjs', get_template_directory_uri() . '/js/main.js', array( 'jquery' ), lookbook_get_theme_version(), true );
    wp_enqueue_script( 'lookbook-verticaljs', get_template_directory_uri() . '/js/vertical.js', array( 'jquery' ), lookbook_get_theme_version(), true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'lookbook_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
* Theme Options.
*/
if ( file_exists( get_template_directory() . '/options/options.php' ) )
	require( get_template_directory() . '/options/options.php' );
if ( file_exists( get_template_directory() . '/theme-options.php' ) )
	require( get_template_directory() . '/theme-options.php' );

/**
* Lookbook feature.
*/
require_once ( get_template_directory() . '/inc/lookbook/lookbook.php' );


/**
* Add class mp-level to all ul elements
*/
class Lookbook_Nav_Menu extends Walker_Nav_Menu {
	function start_lvl(&$output, $depth = 0, $args = Array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"mp-level\">\n";
	}
}
class Lookbook_Walker_Page extends Walker_Page {
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class='mp-level'>\n";
    }
}

/**
 * Register Google Fonts
 */
function lookbook_register_fonts() {
	$protocol = is_ssl() ? 'https' : 'http';
	wp_register_style( 'Montserrat', "$protocol://fonts.googleapis.com/css?family=Montserrat:700" );
	wp_register_style( 'EB Garamond', "$protocol://fonts.googleapis.com/css?family=EB+Garamond" );
}
add_action( 'init', 'lookbook_register_fonts' );

/**
 * Enqueue Google Fonts on Front End
 */
function lookbook_fonts() {
	wp_enqueue_style( 'Montserrat' );
	wp_enqueue_style( 'EB Garamond' );
}
add_action( 'wp_enqueue_scripts', 'lookbook_fonts' );

/**
 * Enqueue Google Fonts on Custom Header Page
 */
function lookbook_admin_fonts( $hook_suffix ) {
	if ( 'appearance_page_custom-header' != $hook_suffix )
		return;

	wp_enqueue_style( 'Montserrat' );
	wp_enqueue_style( 'EB Garamond' );
}
add_action( 'admin_enqueue_scripts', 'lookbook_admin_fonts' );

/**
 * Dequeue font styles when Typekit is active
 */
function lookbook_dequeue_fonts() {
	/**
	 * We don't want to enqueue the font scripts if the blog
	 * has WP.com Custom Design and is using a 'Headings' font.
	 */
	if ( class_exists( 'TypekitData' ) && class_exists( 'CustomDesign' ) ) {
		if ( CustomDesign::is_upgrade_active() ) {
			$customfonts = TypekitData::get( 'families' );

			if ( ! $customfonts )
				return;

			$site_title = $customfonts[ 'site-title' ];

			$headings = $customfonts[ 'headings' ];

			if ( $site_title[ 'id' ] || $headings[ 'id' ] ) {
				wp_dequeue_style( 'Montserrat' );
			}

			$body_text = $customfonts[ 'body-text' ];

			if ( $body_text[ 'id' ] )
				wp_dequeue_style( 'EB Garamond' );

		}
	}
}

add_action( 'wp_enqueue_scripts', 'lookbook_dequeue_fonts' );
