=== Post List ===
Tags: post, list, shortcode
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 1.1
Contributors: vijaybidla
License: GPLv2

This plugin can be used to fetch a list of all posts from specific category using shortcode [postslist].

This plugin can be used to fetch a list of all posts from specific category using shortcode [postslist].
Here is an example of this:
`[postlist cat="cat1,cat2" tags="tag1,tag2,tag3" requesttype="1"]`

`[postlist cat="cat1,cat2" tags="tag1,tag2,tag3" requesttype="1"]`
where
cat : category slug not category name
tages : tag slug not tag name
requesttype : 1 (all of the tags should be present)
              0 (any of the tag should be present)
number : the number of posts you want show (use -1 to show all posts)

== Description ==

This plugin can be used to fetch a list of all posts from specific category using shortcode [postslist].
Here is an example of this:
`[postlist cat="cat1,cat2" tags="tag1,tag2,tag3" requesttype="1"]`

== How to Use ==
`[postlist cat="cat1,cat2" tags="tag1,tag2,tag3" requesttype="1"]`
where
cat : category slug not category name
tages : tag slug not tag name
requesttype : 1 (all of the tags should be present)
              0 (any of the tag should be present)
number : the number of posts you want show (use -1 to show all posts)

== Installation ==
1. Upload the entire `postslist` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add the shortcode `[postslist]` to the post/page of your choice.