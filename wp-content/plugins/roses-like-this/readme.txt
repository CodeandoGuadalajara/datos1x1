=== Like This ===
Contributors: RosemarieP
Tags: karma, likes, post
Requires at least: 3.0
Tested up to: 3.6
Stable tag: trunk

A simple 'I like this' plugin inspired by the facebook 'like' functionality.

== Description ==
A simple 'I like this' plugin inspired by the facebook 'like' functionality.  For visitors who don't want to bother with commenting.

== Installation ==

1. Upload the files into a folder named `roses-like-this` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php printLikes(get_the_ID()); ?>` in 'the loop' of your posts wherever you want the 'like this' link to appear.

== Frequently Asked Questions ==

= How can I make the 'like this' link look prettier? =

You can use CSS.
- LikeThis links have the class `likeThis`
- Links that have been liked also have the class `done` (`.likeThis.done`)

== Changelog ==

= 1.0 =
* The very first version of this plugin :)

= 1.01 =
* Made a small change for those of you installing directly from wordpress.org.  This changes the default directory from `likeThis` to `roses-like-this` in order to coincide with what wordpress will install.  Should lead to less confusion!

= 1.1 =
* Major bug fix! :) Anyone having an issue where the likeThis link clicking wasn't saving in the database should find it fixed.

= 1.2 =
* Bug Fix for those having issues with cookies not being saved correctly.

= 1.3 =
* Added sidebar widget for displaying most liked posts

= 1.4 =
* Add plugin option section for customizing the like this text

= 1.5 =
* Add likes column to post management list

= 1.6 =
* Code Cleanup

= 1.6.1 =
* Bug fix: Don't allow likes go into the negatives

= 1.6.2 =
* Bug fix: Cookie bug fix
