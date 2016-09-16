<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Lookbook
 */
?>
<?php $theme_options = get_option( gpp_get_current_theme_id() . '_options' ); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta property="fb:app_id" content="576581482363455" /> 
  <meta property="og:type"   content="website" /> 
  <meta property="og:url"    content="<?php echo post_permalink(); ?>" /> 
  <meta property="og:title"  content="<?php echo get_the_title(); ?>" />
  <meta property="og:image"  content="<?php $image_id = get_post_thumbnail_id();
										$image_url = wp_get_attachment_image_src($image_id,'medium', true);
										echo $image_url[0];  ?>" /> 
  <meta property="og:description"  content="Plataforma web que publica y difunde el trabajo de autores contemporáneos sobre fotografía." /> 
  <meta property="og:locale"  content="es_ES" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<?php if ( isset( $theme_options['favicon'] ) && '' != $theme_options['favicon'] ) : ?>
	<link rel="shortcut icon" href="<?php echo esc_url( $theme_options['favicon'] ); ?>" />
<?php endif; ?>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
<?php
	if ( isset ( $theme_options[ 'homepage' ] ) && 'vertical' == $theme_options[ 'homepage' ] ) {
		$homepage_style = '';
	} else {
		$homepage_style = ' horizontal';
	}
	if( "" != gpp_lookbook_post_ids() ){
    	$lookbook_count = count( gpp_lookbook_post_ids() );
    } else {
    	$lookbook_count = 0;
    }
?>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<script src="<?php bloginfo('template_directory'); ?>/js/modernizr.custom.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41140794-1', 'auto');
  ga('send', 'pageview');

</script>
</head>

<body <?php body_class(); ?>>
	<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '576581482363455',
      xfbml      : true,
      version    : 'v2.1'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>

<?php $lookbook_page = get_page_by_path('lookbook'); ?>

<div id="page" class="hfeed site<?php echo $homepage_style; ?>">
<div id="mp-pusher" class="mp-pusher">
	<nav id="mp-menu" class="mp-menu">
		<h1 class="menu-toggle"><?php _e( 'Menu', 'lookbook' ); ?></h1>
		<a class="skip-link screen-reader-text" href="#content-main"><?php _e( 'Skip to content', 'lookbook' ); ?></a>
		<?php if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array( 'theme_location' => 'primary', 'container' => 'false', 'menu_class' => 'mp-level', 'walker' => new Lookbook_Nav_Menu() ) );
		} else { ?>
			<ul class="mp-level">
				<?php wp_list_pages( array( 'title_li' => '', 'walker' => new Lookbook_Walker_Page() ) ); ?>
			</ul>
		<?php } ?>
	</nav><!-- #site-navigation -->
	<div id="content-main" class="site-content">
		<div id="site-branding" class="site-branding" <?php lookbook_header_image(); ?>>
			<div id="header-container" class="header-container">
            <div id="site-title-description">
				<h1 class="site-title">
					<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				    	<?php if ( ! empty( $theme_options['logo'] ) ) : ?>
				    	<img class="sitetitle" src="<?php echo esc_url( $theme_options['logo'] ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
				    	<?php else : ?>
				    		<?php bloginfo( 'name' ); ?>
				    	<?php endif; ?>
			    	</a>
			    </h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div>
			<a href="#" id="trigger" class="genericon genericon-menu"></a>

            <?php if ( lookbook_sell_media_check() ) { ?>
                <?php $settings = sell_media_get_plugin_options(); $checkout_page_id = $settings->checkout_page; ?>
                <div id="lookbook-checkout">
                    <a class="genericon genericon-cart" href="<?php echo home_url('/?p='.$checkout_page_id); ?>"><span class='menu-cart-items sellMediaCart_quantity'></span></a>
                </div>
            <?php } ?>

            <?php if ( ! empty( $lookbook_page ) && "no" != $theme_options['lookbook_enabled'] ): ?>
                <div id="lookbook-menu" class="lookbook-menu <?php if( 0 == $lookbook_count ){ echo  'lookbook-menu-hide'; } ?>">
                    <a class="genericon genericon-category" href="<?php echo home_url('/?p='.$lookbook_page->ID); ?>"><span class='lookbook-counter'><?php echo $lookbook_count; ?></span></a>
                </div>
            <?php endif; ?>

            </div>

		</div>
		<?php do_action( 'before' ); ?>

