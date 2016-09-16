<?php

/**
 * Builds the name used for the lookbook cookie
 *
 * @return (string) Name used for lookbook cookie
 */
function gpp_lookbook_cookie_name(){
    return '_gpp_lookbook_' . str_replace( array(':','.','/','-'), '', get_site_url());
}


/**
 * Various function to be ran during init
 *
 * @package GPP Lookbook
 */
function gpp_lookbook_init(){

    $theme = wp_get_theme();
    define('GPP_LOOKBOOK_TEXTDOMAIN', $theme->get('TextDomain') );

    include_once( dirname( __FILE__ ) . '/options.php');

    $theme_options = get_option( gpp_get_current_theme_id() . '_options' );
    if ( isset( $theme_options['lookbook_enabled'] ) && $theme_options['lookbook_enabled'] == 'yes' ) {
        gpp_lookbook_register_post_type();
    }

    require_once ( dirname( __FILE__ ) . '/admin.php');

}
add_action('init', 'gpp_lookbook_init');


/**
 * Register our custom post type
 *
 * @package GPP Lookbook
 */
function gpp_lookbook_register_post_type(){

    $theme = wp_get_theme();

    $args = array(
        'public' => true,
        'label'  => __('Lookbook', GPP_LOOKBOOK_TEXTDOMAIN ),
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_in_nav_menus' => false,
        'supports' => array(
            'title'
            )
        );

    register_post_type( 'gpp-lookbook', $args );
}


/**
 * Remove the "add new"
 */
function gpp_lookbook_hide_add_new(){
    global $submenu;
    unset($submenu['edit.php?post_type=gpp-lookbook'][10]);
}
add_action('admin_menu', 'gpp_lookbook_hide_add_new');


/**
 * Determines if the global $post item is in the lookbook
 *
 * @return (bool) True if it is, false if it is not
 */
function gpp_in_lookbook( $post_id=null ){

    if ( empty( $post_id ) ){
        global $post;
        $post_id = $post->ID;
    }

    $cookie_name = gpp_lookbook_cookie_name();
    $lightbox=array();
    if(isset($_COOKIE[$cookie_name])) {
        $cookie= $_COOKIE[$cookie_name];
        $lightbox=explode(',', $cookie);
    }
    if(in_array($post_id,$lightbox))
        return true;
}


/**
 * Adds a single item to the lookbook
 */
function gpp_lookbook_add_to(){

    // Build cookie name
    $cookie_name = gpp_lookbook_cookie_name();

    $id = $_POST['id'];

    // If we already have a lookbook cookie add the new values
    if ( isset( $_COOKIE[ $cookie_name ] ) && ! in_array( $id, explode( ',', $_COOKIE[ $cookie_name ] ) ) ) {
        $value = $_COOKIE[ $cookie_name ] . ',' . $id;
    } else {
        $value = $id;
    }

    // generate the response
    // since set cookie returns true/false we can pass the result of that as the success
    $response = array(
            'success' => setcookie( $cookie_name, $value, time() + 3600 * 24 * 365, '/' ),
            'postID' => $id
        );

    wp_send_json( $response );
}
add_action('wp_ajax_gpp_lookbook_add_to', 'gpp_lookbook_add_to');
add_action('wp_ajax_nopriv_gpp_lookbook_add_to', 'gpp_lookbook_add_to');


/**
 * Removes a single item to the lookbook
 */
function gpp_lookbook_remove_from(){

    // get post ID
    $id = $_POST['id'];

    // Get cookie and build array of post ids
    $cookie_name = gpp_lookbook_cookie_name();
    $post_ids = explode(',', $_COOKIE[ $cookie_name ] );

    // remove post ID from lightbox cookie
    if( in_array( $id, $post_ids ) ) {
        unset( $post_ids[ array_search( $id, $post_ids ) ] );
        $post_ids = implode(',', $post_ids);
    }

    // set lightbox cookie
    setcookie( $cookie_name, $post_ids, time() + 3600 * 24 * 365, '/');

    // generate the response
    wp_send_json( array(
            'success' => true,
            'postID' => $id
        ) );
}
add_action('wp_ajax_gpp_lookbook_remove_from', 'gpp_lookbook_remove_from');
add_action('wp_ajax_nopriv_gpp_lookbook_remove_from', 'gpp_lookbook_remove_from');


/**
 * Enqueue the needed JavaScript files
 */
function gpp_lookbook_scripts(){
    global $theme_options;
    $theme = wp_get_theme();
    $theme_options = get_option( gpp_get_current_theme_id() . '_options' );
    if ( ! empty( $theme_options['lookbook_enabled'] ) && $theme_options['lookbook_enabled'] == 'yes' ){
        $theme = wp_get_theme();
        wp_enqueue_script( 'gpp-lookbook-script', get_template_directory_uri() .'/inc/lookbook/lookbook.js', array( 'jquery' ), $theme->get('Version') );
        wp_localize_script( 'gpp-lookbook-script', 'gpp_lookbook', array(
            'ajaxurl'=>admin_url('admin-ajax.php'),
            'success' => __('Downloaded!', $theme->get( 'TextDomain' ) ),
            'saving' => __( 'Saving...', $theme->get( 'TextDomain' ) ),
            'saved' => __('Saved', $theme->get( 'TextDomain' ) )
            ) );

        wp_register_script( 'gpp-lookbook-script', get_template_directory_uri() . '/inc/lookbook/lookbook.js', array( 'jquery' ), $theme->get('Version') );
        wp_register_style( 'gpp-lookbook-style', get_template_directory_uri() . '/inc/lookbook/lookbook.css', '', $theme->get('Version') );
    }
}
add_action( 'wp_enqueue_scripts', 'gpp_lookbook_scripts' );


/**
 * Builds the lookbook link the user clicks on to add images to their lookbook
 *
 * @param (int)$id the post id for the single attachment to be added, defaults to current id
 *
 * @return Lookbook link including markup
 */
function gpp_lookbook_add_to_link( $id=null ){

    global $theme_options;

    if ( $theme_options['lookbook_enabled'] =='no' ) {
        return;
    }

    $theme = wp_get_theme();

    $id = empty( $id ) ? get_the_ID() : $id;

    if ( gpp_in_lookbook( $id ) ){
        $active_class = 'lookbook-active';
        $saved_class = ' saved-to-lookbook';
        $genericon_class = 'checkmark';
        $text = __('Saved', GPP_LOOKBOOK_TEXTDOMAIN );
    } else {
        $active_class = null;
        $saved_class = null;
        $genericon_class = 'category';
        $text = __('Save', GPP_LOOKBOOK_TEXTDOMAIN );
    }

    $content ='<p class="lookbook">
        <span aria-hidden="true" class="' . $active_class . '"></span>
        <a href="" title="' . $text . '" class="gpp-lookbook-add' . $saved_class . '" id="lookbook-' . $id . '">
            <span class="genericon genericon-' . $genericon_class . '"></span><span class="gpp-lookbook-text">' . $text .'</span></a>
        </p>';
    return $content;
}


/**
 * Builds the mailto or download: link and the needed markup
 *
 * @param (array)$post_ids
 *
 * @return Full mailto: link or download PDF link, i.e., <a href="mailto:"...
 */
function gpp_lookbook_link( $post_ids=array(), $type=null ){

    global $post;

    $post_ids_imploded = implode( ',', $post_ids );
    $theme = wp_get_theme();

    if ( $type == 'mailto' ){
        $icon_class = 'genericon-mail';
        $text = __( 'Email to friend', GPP_LOOKBOOK_TEXTDOMAIN );

        $append = ( $_SERVER['QUERY_STRING'] != '' ) ? '%26' : '%3F';

        $url = 'mailto:?body=' . get_permalink( $post->ID )  . $append . 'lookbook_ids=' . $post_ids_imploded;
    }

    elseif ( $type == 'download' ){
        $icon_class = 'genericon-cloud-download';
        $text = __( 'Download as a PDF', GPP_LOOKBOOK_TEXTDOMAIN );

        $url = get_permalink( $post->ID )  . '?gpp-download-lookbook=true&lookbook_ids=' . $post_ids_imploded;
    }

    else {

    }

    $link = '<a class="gpp-lookbook-link" href="' . $url . '"><span class="genericon ' . $icon_class . '"></span>' . $text . '</a>';

    return $link;
}


/**
 * Attempts to determine the post ids for a current lookbook.
 * Either via $_COOKIE or $_GET.
 *
 * @return (array)$post_ids false on failure
 */
function gpp_lookbook_post_ids(){

    $cookie_name = gpp_lookbook_cookie_name();

    // Get post_ids from cookie
    if ( isset( $_COOKIE[ $cookie_name ] ) ) {
        $post_ids = explode( ',', $_COOKIE[ $cookie_name ] );
    }

    // Get post_ids from URL
    elseif ( isset ( $_GET['lookbook_ids'] ) ) {
        $post_ids = explode( ',', $_GET['lookbook_ids'] );
    }

    // No items
    else {
        $post_ids = false;
    }

    return $post_ids;
}


/**
 * Generates the PDF and forces the download
 */
function gpp_lookbook_download_handler(){

    if ( ! empty( $_GET['gpp-download-lookbook'] ) ){

        require_once dirname( __FILE__ ) . "/dompdf-master/dompdf_config.inc.php";

        $post_ids = explode(',', $_GET['lookbook_ids'] );


        // Build an array of values to be later filtered
        $wp_upload_dir = wp_upload_dir();
        $files = array();

        foreach( $post_ids as $id ) {

            // We need the SERVER PATH for our PDF. Since WP doesn't have a native function
            // to get the path for a custom image we build it ourselves.
            $attachment = wp_get_attachment_metadata( $id );
            if ( ! empty( $attachment ) ){
                $upload_partial = dirname( $attachment['file'] );
                $attachment_file = empty( $attachment['sizes']['large']['file'] ) ? $attachment['file'] : $upload_partial . '/' . $attachment['sizes']['large']['file'];

                $path = $wp_upload_dir['basedir'] . '/' . $attachment_file;
                $url = $wp_upload_dir['baseurl'] . '/' . $attachment_file;
            } else {
                $path = '';
                $url = '';
            }


            $tmp[] = array(
                'title' => get_the_title( $id ),
                'path' =>  $path,
                'id'   =>  $id,
                'url'  =>  $url
                );
            $files = $tmp;
        }


        // Allow developers to filter the files
        $filtered_files = apply_filters( 'gpp_lookbook_attached_files', $files );


        // Lets assign this to an array just to better organize it.
        $contact = array(
            'site_name' => get_bloginfo( 'name' ),
            'site_description' => get_bloginfo( 'description' ),
            'url' => preg_replace( '#^https?://#', '', get_bloginfo( 'url' ) ),
            'email' => get_bloginfo( 'admin_email' )
        );


        // Add our additional user meta fields
        $user_obj = get_user_by('email', $contact['email']);
        $contact['name'] = $user_obj->first_name . ' ' . $user_obj->last_name;
        $contact['phone'] = get_user_meta( $user_obj->ID, 'gpp_lookbook_phone', true );
        $contact['address'] = get_user_meta( $user_obj->ID, 'gpp_lookbook_address', true );

        // Build our markup from the filtered files
        $contents = '<div class="frontpage">';
        $theme_options = get_option( gpp_get_current_theme_id() . '_options' );
        if( ! empty( $theme_options['logo'] ) ) {
            $logo_url = explode( "uploads/", $theme_options['logo'] );
            $site_title = '<span class="site-logo"><img src="' . $wp_upload_dir['basedir'] . '/' . $logo_url[1] . '" /></span>';
        } else {
            $site_title = '<h1>' . $contact['site_name'] . '</h1>';
        }
        $contents .= '<div class="title">' . strtoupper( $site_title ) . '</div>';
        $contents .= '<div class="info">';
        $contents .= '<span>' . $contact['name'] . '</span><br />';
        $contents .= '<span>' . $contact['address'] . '</span><br />';
        $contents .= '<span>' . $contact['phone'] . '</span><br />';
        $contents .= '<span>' . $contact['email'] . '</span><br />';
        $contents .= '<span>' . $contact['url'] . '</span>';
        $contents .= '</div>'; // info close
        $contents .= '</div>'; // Frontpage close
        $contents .= '<hr />';

        foreach( $filtered_files as $file ){
            if ( ! empty( $file['path'] ) ){
                $contents .= '<div class="look-img"><img src="' . $file['path'] . '" /><br />';
                $contents .= '<span class="look-title">' . $file['title'] . '</span></div>';
                $contents .= '<hr />';
            }
        }

        // Ideally this non-sense would come from an html/php/template file
        // We can fine tune it to be "invoked via the web"
        $html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">
            <head>
            <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
            <title>Header and Footer example</title>
                <style type="text/css">
                    @page {
                        margin: 0.5cm;
                    }

                    body {
                        font-family: century-gothic;
                        margin: 0.5cm 0;
                        text-align: justify;
                    }

                    #header,
                    #footer {
                        position: fixed;
                        left: 0;
                        right: 0;
                        color: #888;
                        font-size: 0.5em;
                    }

                    #header table,
                    #footer table {
                        width: 100%;
                        border-collapse: collapse;
                        border: none;
                    }

                    #footer {
                        bottom: 0;
                        border-top: 0.5pt solid #aaa;
                    }

                    #header {
                        top: 0;
                        border-bottom: 0.5pt solid #aaa;
                    }

                    hr {
                        page-break-after: always;
                        border: 0;
                    }

                    img {
                        max-width: 100%;
                        max-height: 93%;
                    }
                    div.look-img {
                        text-align: center;
                    }
                    div.look-img img {
                        margin-bottom:10px;
                    }
                    div.look-img .look-title {
                        font-family: georgia;
                        font-style: italic;
                    }

                    .frontpage {
                        position: absolute;
                        bottom: 100px;
                        left: 100px;
                        clear: both;
                    }
                    .title h1 {
                        margin-bottom: 0;
                        font-size: 3em;
                    }
                    .title span {
                        font-size: .5em;
                    }
                    .info {
                        font-size: .75em;
                    }

                </style>
            </head>
            <body>
                <div id="header">'. $contact['site_name'].'</div>
                <div id="footer">
                    <table>
                        <tr>
                            <td>' .
                            $contact['site_name'] . ' &bull; ' .
                            $contact['url'] . ' &bull; ' .
                            $contact['email'] . ' &bull; ' .
                            $contact['name'] . ' &bull; ' .
                            $contact['address'] . ' &bull; ' .
                            $contact['phone'] .
                            '</td>
                        </tr>
                    </table>
                </div>
                '.$contents.'
            </body>
        </html>';


        // Build our lookbook name
        $blogname = sanitize_title( get_bloginfo( 'name' ) );
        $filename = "lookbook-{$blogname}.pdf";

        // Call DOMPDF, and force the download of the PDF
        $dompdf = new DOMPDF();
        $dompdf->load_html( $html );
        $dompdf->set_paper('letter', 'landscape');
        $dompdf->render();
        $dompdf->stream( $filename );
    }
}
add_action('init','gpp_lookbook_download_handler');


/**
 * Prints the image used in the lookbook
 *
 * @param (int)$post_id the post ID to derive the attached image from
 */
function gpp_lookbook_image( $post_id=null ){

    $image = wp_get_attachment_image( $post_id );
    $image = apply_filters( 'gpp_lookbook_image', $image, $post_id );

    echo $image;
}


/**
 * Displays the email form for lookbook.
 */
function gpp_lookbook_email_form(){
    $theme = wp_get_theme(); ?>
    <form action="" id="gpp_lookbook_form" method="POST">
        <p class="gpp-lookbook-description"><?php _e('Please enter your name and email to download your Lookbook', GPP_LOOKBOOK_TEXTDOMAIN ); ?></p>
        <?php wp_nonce_field( 'gpp_lookbook_form_handler', 'gpp_lookbook_nonce' ); ?>
        <input type="text" name="badger" value="gpp" id="gpp_lookbook_badger" style="display: none;" />
        <p><label><?php _e( 'Name', GPP_LOOKBOOK_TEXTDOMAIN ); ?><input type="text" id="gpp_lookbook_name_field" name="name" autocomplete="off" /></p>
        <p><label><?php _e( 'Email', GPP_LOOKBOOK_TEXTDOMAIN ); ?><input type="text" id="gpp_lookbook_email_field" name="email" autocomplete="off" /></p>
        <p><input type="submit" value="<?php _e('Download Lookbook', GPP_LOOKBOOK_TEXTDOMAIN ); ?>" disabled id="gpp_lookbook_submit" /></p>
    </form>
<?php }


/**
 * We recored the lookbookers information when the email is required.
 */
function gpp_lookbook_form_handler(){

    check_ajax_referer( 'gpp_lookbook_form_handler', 'security' );

    $email = $_POST['email'];

    // Check our honeypot
    if ( $_POST['badger'] != 'gpp' ){

        $msg['status'] = 'error';
        $msg['message'] = 'Invalid badger';

    // Yes, we double check email, its already validated via JS
    } elseif ( is_email( $email ) ){

        $name = wp_kses( $_POST['name'], '' );

        $post_id = wp_insert_post( array(
            'post_type' => 'gpp-lookbook',
            'post_title' => $name,
            'post_status' => 'publish'
            ) );

        if ( is_wp_error( $post_id ) ){

            $msg['status'] = 'error';
            $msg['message'] = 'Error inserting post: ' . print_r( $post_id, true );

        } else {

            $meta_email_id = update_post_meta( $post_id, '_gpp_lookbook_email', $email );
            $meta_post_ids = update_post_meta( $post_id, '_gpp_lookbook_items', implode(',', gpp_lookbook_post_ids() ) );

            $msg['status'] = 'success';
            $msg['message'] = 'Inserted post: ' . $post_id . ' with meta keys: ' . print_r( $meta_email_id, true ) . ' and ' . print_r( $meta_post_ids, true );
            $msg['download_url'] = get_permalink( $post_id )  . '?gpp-download-lookbook=true&lookbook_ids=' . implode(',', gpp_lookbook_post_ids() );
        }

    } else {
        $msg['status'] = 'error';
        $msg['message'] = 'Invalid email: ' . $email;
    }
    wp_send_json( $msg );
}
add_action('wp_ajax_gpp_lookbook_form_handler', 'gpp_lookbook_form_handler');
add_action('wp_ajax_nopriv_gpp_lookbook_form_handler', 'gpp_lookbook_form_handler');


/**
 * Generate the needed markup for the remove all link
 */
function gpp_lookbook_remove_all_link(){
    $theme = wp_get_theme();
    return '<a href="#" class="gpp-lookbook-remove-all" data-gpp-lookbook-nonce="' . wp_create_nonce( 'gpp_lookbook_remove_all' ) . '" data-gpp-lookbook-id="' . implode( ',', gpp_lookbook_post_ids() ) . '"><span class="genericon genericon-trash"></span>' . __( 'Remove all', GPP_LOOKBOOK_TEXTDOMAIN ) . '</a>';
}


/**
 * Remove all items from the lookbook cookie
 */
function gpp_lookbook_remove_all(){
    check_ajax_referer( 'gpp_lookbook_remove_all', 'security' );

    $cookie_name = gpp_lookbook_cookie_name();

    if ( isset( $_COOKIE[ $cookie_name ] ) ) {
        setcookie( $cookie_name, "", time()-3600, '/' );
        $msg = array( 'success' => true, 'message' => 'deleted lookbook cookie' );
    } else {
        $msg = array( 'success' => false, 'message' => 'failed to delete lookbook cookie' );
    }
    wp_send_json( $msg );
}
add_action('wp_ajax_gpp_lookbook_remove_all', 'gpp_lookbook_remove_all');
add_action('wp_ajax_nopriv_gpp_lookbook_remove_all', 'gpp_lookbook_remove_all');


/**
 * Use this in the page-lookbook.php or where ever you want to display the
 */
function gpp_lookbook_download_type(){
    global $theme_options;
    $lookbook_post_ids = gpp_lookbook_post_ids();
     if ( $lookbook_post_ids ) : ?>
        <?php if ( ! empty( $theme_options['required_type'] ) && $theme_options['required_type'] == 'email' ) : ?>
            <?php gpp_lookbook_email_form(); ?>
        <?php endif; ?>

        <?php if ( ! empty( $theme_options['required_type'] ) && $theme_options['required_type'] == 'freedownload' ) : ?>
            <?php echo gpp_lookbook_link( $lookbook_post_ids, 'download' ); ?>
        <?php endif; ?>
        <?php echo gpp_lookbook_remove_all_link(); ?>
    <?php endif; ?>
<?php }


/**
 * Returns the html used by the lookbook shortcode
 */
function gpp_lookbook_shortcode(){
    global $theme_options;
    wp_enqueue_script( 'gpp-lookbook-script' );
    wp_enqueue_style( 'gpp-lookbook-style' );
    $theme = wp_get_theme();
    if ( ! empty( $theme_options['required_type'] ) && $theme_options['required_type'] == 'email' ) {
        $form_enabled = " lookbook-form";
    } else {
        $form_enabled = "";
    }
    ob_start(); ?>
    <div class="gpp-lookbook<?php echo $form_enabled; ?>">

        <header class="entry-header">
            <?php gpp_lookbook_download_type(); ?>
        </header><!-- .entry-header -->

        <div id="lookbook">
            <?php if ( gpp_lookbook_post_ids() ) : foreach( gpp_lookbook_post_ids() as $id ) :
                $image_src = wp_get_attachment_image_src( $id, 'large' ); ?>
                <div class="gpp-lookbook-grid third grid">
                    <a href="" title="<?php _e( 'Remove from lookbook', GPP_LOOKBOOK_TEXTDOMAIN ); ?>" class="gpp-lookbook-remove" style="display: none;" id="lookbook-<?php echo $id; ?>"><div class="genericon genericon-close"></div></a>
                    <div class="gpp-lookbook-item-details-inner">
                        <a href="<?php echo $image_src[0]; ?>"><?php gpp_lookbook_image( $id ); ?></a>
                    </div>
                </div>
            <?php endforeach; else : ?>
                <?php _e( 'Your lookbook is empty', GPP_LOOKBOOK_TEXTDOMAIN ); ?>
            <?php endif; ?>
        </div><!-- #lookbook -->

    </div>
<?php return ob_get_clean(); }
add_shortcode('gpp_lookbook', 'gpp_lookbook_shortcode');