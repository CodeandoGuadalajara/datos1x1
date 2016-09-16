<?php


/**
 * Lookbook admin init
 */
function gpp_lookbook_admin_init(){
    $theme = wp_get_theme();
    $theme_name = sanitize_title( $theme->get('Name') );
    add_filter( "sanitize_option_{$theme_name}_options", "gpp_lookbook_create_page" );
}
add_action('admin_init', 'gpp_lookbook_admin_init');


/**
 * Display a message to our user when
 */
function gpp_lookbook_admin_notice() {
    $theme = wp_get_theme();

    $lookbook_page = get_page_by_title( 'Lookbook' );
    // Check if we have a newly created lookbook page
    // If so we display our admin notice
    global $theme_options;

    $flag = ! empty( $lookbook_page->ID ) ? get_post_meta( $lookbook_page->ID, '_gpp_lookbook_automate_page', true ) : null;

    if ( ! empty( $flag )
        && ! empty( $theme_options['lookbook_enabled'] ) && $theme_options['lookbook_enabled'] == 'yes'
        && isset( $_GET['tab'] ) && $_GET['tab'] == 'lookbook_tab' && isset( $_GET['settings-updated'] ) ) : ?>
        <div class="updated">
            <p><?php printf(
                '%s <a href="'.get_edit_post_link( $lookbook_page->ID ).'">%s</a> %s',
                __('We created the', GPP_LOOKBOOK_TEXTDOMAIN ),
                __('lookbook page', GPP_LOOKBOOK_TEXTDOMAIN ),
                __('and assigned the lookbook page template.', GPP_LOOKBOOK_TEXTDOMAIN )
             ); ?></p>
        </div>
    <?php endif; ?>
<?php }
add_action( 'admin_notices', 'gpp_lookbook_admin_notice' );


/**
 * Create our lookbook page if its not already created
 */
function gpp_lookbook_create_page( $input ){

    $lookbook_page = get_page_by_title( 'Lookbook' );

    // Add our lookbook page
    if ( empty( $lookbook_page ) || ! empty( $lookbook_page ) && $lookbook_page->post_status == 'trash' ){

        $post_id = wp_insert_post( array(
            'post_type' => 'page',
            'post_title' => 'Lookbook',
            'post_status' => 'publish',
            'post_content' => '[gpp_lookbook]'
            ) );

        // Add a flag to know this page was generated via GPP Lookbook
        update_post_meta( $post_id, '_gpp_lookbook_automate_page', true );
    }

    return $input;
}


/**
 * Add our option to the bulk actions select box only if the
 * user can "manage_options" AND we are on the gpp-lookbook page
 *
 * @package GPP Lookbook
 */
function gpp_lookbook_add_bulk_actions_via_javascript() {
    global $post_type;
    if ( current_user_can( 'manage_options' ) && $post_type == 'gpp-lookbook' ) : ?>
        <?php $theme = wp_get_theme(); ?>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('select[name^="action"] option:last-child').before('<option value="gpp_bulk_export_lookbookers"><?php echo esc_attr( __( 'Export lookbookers', GPP_LOOKBOOK_TEXTDOMAIN ) ); ?></option>');
            });
        </script>
    <?php endif;
}
add_action( 'admin_head-edit.php', 'gpp_lookbook_add_bulk_actions_via_javascript' );


/**
 * Add our "export" option the row actions, which are displayed when users
 * hover over the row in the bulk post table.
 *
 * @package GPP Lookbook
 */
function gpp_lookbook_add_row_action( $actions, $post ) {

    if ( current_user_can( 'manage_options' ) && $post->post_type == 'gpp-lookbook' ){
        $theme = wp_get_theme();

        $url = add_query_arg( '_wpnonce', wp_create_nonce( 'bulk-posts' ), admin_url(
            'edit.php?s=&post_type=gpp-lookbook&&action=gpp_bulk_export_lookbookers&post=' . $post->ID
            ) );

        $actions['gpp_export_lookbookers'] = '<a href="' . esc_url( $url ) . '" title="' . esc_attr( __( 'Export the lookbookers for this single item', GPP_LOOKBOOK_TEXTDOMAIN ) ) . '">' . __( 'Export Lookbookers', GPP_LOOKBOOK_TEXTDOMAIN ) . '</a>';
    }

    return $actions;
}
add_filter( 'post_row_actions', 'gpp_lookbook_add_row_action', 10, 2 );


/**
 * Export our posts as a CSV file
 *
 * @package GPP Lookbook
 */
function gpp_lookbook_bulk_action_handler(){

    // Check to make sure we are on our "lookbook" admin page
    if ( empty( $_REQUEST['action'] )
        || ( 'gpp_bulk_export_lookbookers' != $_REQUEST['action']
        && 'gpp_bulk_export_lookbookers' != $_REQUEST['action2'] )
        || $_REQUEST['post_type'] != 'gpp-lookbook' ){
            return;
    }

    check_admin_referer( 'bulk-posts' );


    // Get our submissions
    $my_posts = New WP_Query( array(
        'post_type' => 'gpp-lookbook',
        'post__in' => is_array( $_REQUEST['post'] ) ? $_REQUEST['post'] : (array)$_REQUEST['post'], // Force post to be an array if its not
        'post_status' => 'publish',
        'ignore_sticky_posts' => true
        ) );


    // If we have no posts we just exit;
    if ( empty( $my_posts->posts ) )
        exit;


    // Create our CSV headers
    $csv_headers = array();
    foreach( array('Name', 'Email') as $header ){
        $csv_headers[0][] = $header;
    }


    // For the format of the array we're building reference
    // php.net http://us2.php.net/fputcsv
    // Note at the end we merge our headers for our CSV with the array
    // we've built
    foreach( $my_posts->posts as $post ){
        $t = array();

        // Note the order of the entries pushed into our array
        // MUST be the same order as the $csv_headers array!
        foreach( $csv_headers as $header ){

            // Push the post title as the "name"
            $name = $post->post_title;
            $t[] = empty( $name ) ? '' : $name;

            // Push the email
            $email = get_post_meta( $post->ID, '_gpp_lookbook_email', true );
            $t[] = empty( $email ) ? '' : $email;
        }
        $export[] = $t;
    }
    $final_export = array_merge( $csv_headers, $export );


    // Make our cache dir
    $cache_dir = dirname( __FILE__ ) . "/cache";
    if ( ! file_exists( $cache_dir ) ){
        wp_mkdir_p( $cache_dir );
    }


    // Make our file and path
    $date = date('Y-m-d');
    $file = "$cache_dir/submission-{$date}.csv";


    // Put our CSV file
    $fp = fopen( $file, 'w');
    foreach ($final_export as $fields) {
        fputcsv($fp, $fields);
    }
    fclose($fp);


    // Finally force the download and delete the cached file when done
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-Type: text/html; charset=utf-16'); // try utf-16 if this doesn't work
    header("Content-Transfer-Encoding: UTF-16"); // utf-16 here also
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header("Content-Transfer-Encoding: binary");
    readfile($file);
    unlink( $file );
    die();
}
add_action( 'admin_action_gpp_bulk_export_lookbookers', 'gpp_lookbook_bulk_action_handler' ); // Top select box
add_action( 'admin_action_-1', 'gpp_lookbook_bulk_action_handler' ); // Bottom select box


/**
 * Assign our post meta box
 *
 * @package GPP Lookbook
 */
function gpp_lookbook_post_meta() {
    $theme = wp_get_theme();
    add_meta_box(
        'gpp_lookbook_meta_section',
        __( 'Detail', GPP_LOOKBOOK_TEXTDOMAIN ),
        'gpp_lookbook_post_meta_cb',
        'gpp-lookbook'
    );
}
add_action( 'add_meta_boxes', 'gpp_lookbook_post_meta' );


/**
 * Add additional post meta to the gpp-lookbook post type
 *
 * @package GPP Lookbook
 */
function gpp_lookbook_post_meta_cb( $post ){
    $theme = wp_get_theme();
    $email = get_post_meta( $post->ID, '_gpp_lookbook_email', true );
    $items = explode(',', get_post_meta( $post->ID, '_gpp_lookbook_items', true ) ); ?>
    <style type="text/css">
    #gpp_lookbook_meta_section .gpp-lookbook-thumbnail {
        max-width: 75px;
        height: auto;
        float: left;
        margin: 0 10px 10px;
        }

    #gpp_lookbook_meta_section .gpp-lookbook-thumbnail img {
        max-width: 100%;
        }

    #gpp_lookbook_meta_section li {
        clear: both;
        }
    </style>
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="gpp_lookbook_email"><?php _e('Email', GPP_LOOKBOOK_TEXTDOMAIN); ?></label></th>
                <td><input name="_gpp_lookbook_email" type="text" id="gpp_lookbook_email" value="<?php echo $email; ?>" disabled class="disabled regular-text"></td>
            </tr>
            <?php if ( ! empty( $items ) ) : ?>
                <tr valign="top">
                    <th scope="row"><label for="gpp_lookbook_email"><?php _e('Items Added', GPP_LOOKBOOK_TEXTDOMAIN); ?></label></th>
                    <td>
                        <ul>
                            <?php foreach( $items as $item_id ) : ?>
                                <li><?php

                                // echo wp_get_attachment_image( $item_id, array(32,32) );
                                $image = wp_get_attachment_image( $item_id, array( 75, 75 ) );
                                $image = apply_filters( 'gpp_lookbook_image', $image, $item_id );
                                // print_r( $image );
                                ?>
                                <div class="gpp-lookbook-thumbnail">
                                    <a href="<?php echo get_edit_post_link( $item_id ); ?>"><?php echo $image; ?></a>
                                </div>
                                <a href="<?php echo get_edit_post_link( $item_id ); ?>"><?php echo get_the_title( $item_id ); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php }


/**
 * Add filter for the user profile fields and add the phone number and address
 *
 * @param (array)$profile_fields The profile field array to add our new values to
 */
function gpp_lookbook_contact_methods( $profile_fields ) {

    $theme = wp_get_theme();

    $profile_fields['gpp_lookbook_phone'] = __( 'Phone Number', $theme->get('TextDomain' ) );
    $profile_fields['gpp_lookbook_address'] = __( 'Address', $theme->get('TextDomain' ) );

    return $profile_fields;
}
add_filter( 'user_contactmethods', 'gpp_lookbook_contact_methods' );



/**
 * This is used to distinguish between the page we automatically create when
 * lookbook is enabled vs. a page the user creates and adds the lookbook to.
 */
function gpp_lookbook_remove_flag( $post_id ){
    $flag = get_post_meta( $post_id, '_gpp_lookbook_automate_page', true );
    if ( $flag ){
        delete_post_meta( $post_id,'_gpp_lookbook_automate_page' );
    }
    return;
}
add_action('save_post', 'gpp_lookbook_remove_flag');