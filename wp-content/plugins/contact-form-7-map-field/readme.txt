=== Contact Form 7 Map Field ===
Contributors: shemesh
Donate link: http://goo.gl/6d0XS
Tags: Contact Form 7, form, forms, contactform7, contact form, cf7, cforms, Contact Forms, geo, google map, gpx, location by latitude/longitude, map short code, marker, coordinates, openstreetmap, osm, leaflet, geolocation
Requires at least: 3.0.1
Tested up to: 3.4.2
Stable tag: 2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin provides a new field to Contact Form 7: a map with a coordinates marker, letting the user mark a location.

== Description ==

This plugin provides a new field to Contact Form 7: a map with a coordinates marker, letting the user mark a location.
The marker coordinates (longitude & latitude) are than passed to the contact form.

in version 2.0 i've added geolocation, making the map and marker to be focused on the supposed user location.

== Installation ==

You MUST have Contact Form 7 installed!

Best way is to use WordPress' built-in "Add New" installer:

1. in WordPress admin bar go to Plugins.
1. Press the "Add New".
1. search for "Contact Form 7 Map Field".
1. Press "Install Now".
1. Press "Activate Plugin".

Or:

1. Download the zip file.
1. Extract the folder (contact-form-7-map-field) inside the zip to ...wp-content/plugins/ directory of your WordPress installation.
1. Activate the plugin from plugins page.

Now that the plugin is installed you can start using it:

1. Edit a form in Contact Form 7.
1. Choose "Map" from the Generate Tag dropdown.
1. Follow the instructions on the page.

== Frequently Asked Questions ==

= What map does it use by default? =
The map data is provided by <a href="http://openstreetmap.org">OpenStreetMap</a>. and is powered by <a href="http://leaflet.cloudmade.com/">Leaflet</a>.

= Can i use a different map provider? =
I really recommend using Leaflet with OSM. But if you insist than you can, as long as you know some basic html, javascript and your map api.

= I like this plugin, how can i donate to it? =
Simply go to the <a href="http://goo.gl/6d0XS">donate link</a>. 

== Screenshots ==

1. How it looks in the form edititing.
2. Example of a form with map (location) input field.

== Changelog ==

= 1.0 =
Initial plugin release.

= 1.1 =
Switched to work with Leaflet & OpenStreetMap.

= 1.3 =
Some code fixing and cleaning.

= 1.4 =
Fixed marker icon wrong path, collision with Leaflet Maps Marker plugin.

= 1.5 =
Better code structure.

= 2.0 =
Added geolocation, now the map and marker are focused on the supposed user location.

= 2.1 =
Bug fixes in geolocation.

= 2.2 =
Added validation.

= 2.3 =
Added minimum map zoom validation.

== Upgrade Notice ==

= 1.3 =
This is a better version.

= 1.4 =
Fixed marker icon problem, collision with Leaflet Maps Marker plugin.

= 1.5 =
Better code structure.

= 2.0 =
Added geolocation.

= 2.1 =
Bug fixes in geolocation.

= 2.2 =
Added validation.

= 2.3 =
Added minimum map zoom validation.