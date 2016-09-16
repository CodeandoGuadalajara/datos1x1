=== Custom Post Type List Shortcode ===
Contributors: justingreerbbi, Blackbird Interactive
Donate link: http://blackbirdi.com/donate/
Tags: custom post-type list, custom post-type, post list, shortcode, cpt, custom field, taxonomy list, list taxonomies
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 1.4.4

 A shortcode with which you can easily list all of the posts within a post-type and sort by regular or custom fields.

== Description ==

UPGRADE AT YOUR OWN RISK: We've added a legacy feature to the plugin which has been tested and is  working on our local WordPress install. However, every environment is different, if you run into problem please visit http://blackbirdi.com/blog for support.

When used with Custom Post Type UI plug-in (http://wordpress.org/extend/plugins/custom-post-type-ui/) and Advanced Custom Fields plug-in (http://wordpress.org/extend/plugins/advanced-custom-fields/), you can easily list all of the posts within a post-type and sort by regular or custom fields.

Updated and tested on latest version of Wordpress (4.0)

Most updates are because of users responding with requests. If you feel there is something that you would like to see in the plugin visit our site and post a comment.

Enjoy!
You can find documentation for the CPT_List @ (http://blackbirdi.com/blog/)

== Installation ==

1. Upload `cpt_shortcode` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Place shortcode `[cpt_list]` (see our site for documentation http://blackbirdi.com/blog ) in any content area/page that allows shortcode.

== Frequently Asked Questions ==

= Do I have to use Custom Post-types? =

You do not have to use custom post-types, Custom Post Type List Shortcode pulls `posts` by default, but the real power of this plugin lies in the use of custom post-types. 

= Is there paging? =

Paging is not supported in this version, though it is planned for a future release.

= What can I do with this plugin =

You can easily create a list of any specified custom post-type in any content area that allows shortcode to be executed. Some examples of use are:
1. Custom image gallery managed via `gallery` custom post-type.
2. Resturant menu page, menu items managed via `menu` custom post-type
3. Show schedule listing, where shows are managed via `shows` custom post-type.
4. Employee directory with names/photos/info, managed via `employees` custom post-type.

== Screenshots ==

1. Use of the shortcode with some options in a page content area
2. Custom post-type driven gallery after some jQuery and CSS.

== Changelog ==

= 1.4.4 =
* Checked and ensured that the code worked on latest WP install.
* Updated directory structure of plugin. Nothing major added/removed or tweaked code wise.

= 1.4.3 =
* Added legacy mode to make it support older installtions as well
* Added inline styles for images to keep themes from overiding the thumbnail sizes
* Added support for over under layout( Traditionally the plugin supported only side by side content )
* Wrapped content and images in class's for easier modification with CSS
* Minor cleanup based on user submittions at our blog http://blackbirdi.com/blog

= 1.4.2 =
* Added `filter_content` tag which allows for other plugins to hook into the plugin
* Added support for ordering by custom fields. `meta_key`


= 1.4.1 =
* Added check for show date to rid the empty H3 tags

= 1.4.0 =
* Added more taxonomy features - Special thanks: Chris Sigler
* Removed bulky documentation from admin area

= 1.3.9 =
* Fixed typo's
* Added support for Taxonomies
* Cleaned house some for future additions

= 1.3.8 =
* Fixed typo's

= 1.3.5 =
* Added menu in the admin panel contianing documentation and information.
* Added new Arguments: show_date, images_only, imgaes_only_num, excerpt_only
* Added Donation button
* Fixed category bug

= 1.3.4 =
* Fixed boolean checks that were bugs when users input "false" for an option
* Major bug-fixes

= 1.3.3 =
* Added a `div` with class of `cpt_item_entry_content` around the entry content of each cpt item

= 1.3.2 =
* Added new Argument: `wrap_with`
* Minor bug-fixes

= 1.3.1 =
* Added new Arguments: `thumb_link`, `thumb_height`, `thumb_width`
* Minor bug-fixes

= 1.3 =
* Added new Arguments: `category`, `use_single_blocks`, `title_links`, `link_to_file`, `attachment`, `show_thumbs`, `show_post_content`, `read_more_link`, `list_title`, `file_extension`
* Changed Loop Structure
* Added New Classes

= 1.0 =
* Initial build

== Upgrade Notice ==

= 1.3.4 =
Fixed Boolean checks. Highly recomended upgrade, especially if your having problems with some of the shortcode options.

= 1.3.3 =
Unreleased

= 1.3.2 =
Added subtle options and bugfixes. Not necessary, but recomended upgrade.

= 1.3.1 =
More thumbnail controls and bugfixes. Recomended upgrade.

= 1.3 =
Significant structure changes and feature additions. Recomended upgrade.

= 1.0 =
Initial Build. Upgrade to latest.