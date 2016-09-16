<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * @package Lookbook
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses lookbook_header_style()
 * @uses lookbook_admin_header_style()
 * @uses lookbook_admin_header_image()
 *
 * @package Lookbook
 */
function lookbook_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'lookbook_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => '666666',
		'width'                  => 1280,
		'height'                 => 500,
		'flex-height'            => true,
		'wp-head-callback'       => 'lookbook_header_style',
		'admin-head-callback'    => 'lookbook_admin_header_style',
		'admin-preview-callback' => 'lookbook_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'lookbook_custom_header_setup' );

if ( ! function_exists( 'lookbook_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see lookbook_custom_header_setup().
 */
function lookbook_header_style() {
	$header_text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == $header_text_color ) {
		return;
	}

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $header_text_color ) :
	?>
		h1.site-title,
		h2.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		h1.site-title a,
		h2.site-description {
			color: #<?php echo $header_text_color; ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // lookbook_header_style

if ( ! function_exists( 'lookbook_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see lookbook_custom_header_setup().
 */
function lookbook_admin_header_style() {
?>
	<style type="text/css">
		.appearance_page_custom-header #headimg {
			border: none;
            position: relative;
		}
        .header-text {
            position: absolute;
            top: 0;
            left: 0;
        }
		#headimg h1,
		#desc {
		}
		#headimg h1 {
            float: left;
            font-size: 1.5em;
            font-weight: bold;
            letter-spacing: -3px;
            line-height: 1;
            margin-left: 20px;
            margin-right: 10px;
            text-transform: uppercase;
            font-family: Montserrat, sans-serif;
            font-size: 2em;
            
		}
		#headimg h1 a {
            text-decoration: none;
		}
		#desc {
            float: left;
            display: inline;
            font-family: 'EB Garamond',sans-serif;
            font-size: 14px;
            margin-top: 10px;
		}
		#headimg img {
		}
	</style>
<?php
}
endif; // lookbook_admin_header_style

if ( ! function_exists( 'lookbook_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see lookbook_custom_header_setup().
 */
function lookbook_admin_header_image() {
	$style = sprintf( ' style="color:#%s;"', get_header_textcolor() );
?>
	<div id="headimg">
        <div class="header-text">
		  <h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		  <h2 class="displaying-header-text" id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></h2>
		</div>
        <?php if ( get_header_image() ) : ?>
		<img src="<?php header_image(); ?>" alt="">
		<?php endif; ?>
	</div>
<?php
}
endif; // lookbook_admin_header_image
