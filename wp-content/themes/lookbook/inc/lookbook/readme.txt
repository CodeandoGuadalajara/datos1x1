=== GPP Lookbook ===

Allow your visitors to add images to their lookbok and download them as a PDF.

== Description ==

Allows your site visitors to add items to their Lookbook. Once they provide a name and email address and download they can download all the items as a PDF. Each requested lookbooker is stored in the WordPress admin as a "lookbook" entry. From here you can view various information such as; their name, email, and items requested. Each entry can be easily exported as a CSV file to be imported into your favorite email campaign system. Exports can also be done in bulk.

== Installation ==

1. Include the file
Include the file `lookbook.php` from your `functions.php` or appropriate file.
`require_once ( get_template_directory() . '/lib/lookbook/lookbook.php' );`. This will add the theme options, which will create a new tab called "Lookbook", with the following sections: Enable Lookbook – If this is enabled a page called Lookbook will be created that contains the `[gpp_lookbook]` shortcode. Require Email – If the email is required than the name, email and items downloaded are saved in the "Lookbook" post type.
2. Add the function `<?php gpp_lookbook_add_to_link( $id=null ); ?>` where you want the "Add to Lookbook" icon and text to show. The default will be the current ID.

== Developers ==

= Filters =

`gpp_lookbook_attached_files` This allows you to filter the items being printed into the PDF. See example below:

<pre>
/**
 * Filters the path and URL being passed into the PDF to use the Sell Media image
 *
 * @param (array)$files
 *
 * @return (array)$files Adds new path to the correct key containing the update path and URL
 */

function sell_media_lookbook_attached_files( $files ) {

    $wp_upload_dir = wp_upload_dir();

    foreach( $files as $k => $v ){
        if ( get_post_type( $files[ $k ]['id'] ) == 'sell_media_item' ){

            // Build our path and URLs for only sell media items
            $attachment_id = get_post_meta( $files[ $k ]['id'], '_sell_media_attachment_id', true );
            $attachment = wp_get_attachment_metadata( $attachment_id );
            if ( ! empty( $attachment ) ){

                $upload_partial = dirname( $attachment['file'] );
                $attachment_file = empty( $attachment['sizes']['large']['file'] ) ? $attachment['file'] : $upload_partial . '/' . $attachment['sizes']['large']['file'];

                $files[ $k ]['path'] = $wp_upload_dir['basedir'] . '/' . $attachment_file;
                $files[ $k ]['url'] = $wp_upload_dir['baseurl'] . '/' . $attachment_file;
            }
        }
    }
    return $files;
}
add_filter('gpp_lookbook_attached_files', 'sell_media_lookbook_attached_files');
</pre>

`gpp_lookbook_image` This allows you to filter the image being displayed on the Lookbook page. See example below:

<pre>
/**
 * Checks the post type to see if this is a Sell Media item. If it is
 * the Sell Media image is used for the lookbook.
 *
 * @param (string)$image The current image
 * @param (int)$post_id The id of the post to be filtered
 *
 * @return (string)$image The new filtered image or the current image
 */
function sell_media_lookbook_image( $image, $post_id ){
    if ( get_post_type( $post_id ) == 'sell_media_item' ){
        $sell_media_attachment_id = get_post_meta( $post_id, '_sell_media_attachment_id', true );
        if ( $sell_media_attachment_id ){
            $attachment_id = $sell_media_attachment_id;
        } else {
            $attachment_id = get_post_thumbnail_id( $post_id );
        }
        $image = sell_media_item_icon( $attachment_id, 'medium', false );
    }
    return $image;
}
add_filter('gpp_lookbook_image', 'sell_media_lookbook_image', 15, 2);
</pre>

== Changelog ==


= 1.0.2 =
    * Front page added
    * Lookbook pdf orientaton changed to landscape

= 1.0.1 =
    * Blank pages in pdf fix

= 1.0 =
    * First public release