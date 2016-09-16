<?php
/*
Plugin Name: Contact Form 7 Map Field
Plugin URI: http://wordpress.org/extend/plugins/contact-form-7-map-field/
Description: This plugin provides a new field to Contact Form 7: a map with a coordinates marker, letting the user mark a location.
Version: 2.3
Author: Ofir Shemesh
Author URI: https://shemesh.wordpress.com/
License: GPLv2 or later
*/
/*  
Copyright (C) 2012 Ofir Shemesh (email: shemeshi at gmail.com)
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/
$mapFieldName;
add_action("wpcf7_before_send_mail", "wpcf7_parse_before_send");
function wpcf7_parse_before_send($cfdata) {
    global $mapFieldName;
    $value = explode(";", $cfdata->posted_data[$mapFieldName]);
    $cfdata->posted_data[$mapFieldName] = $value[1];
    return $cfdata;
}

add_action('plugins_loaded', 'contact_form_7_map_field');
function contact_form_7_map_field() {
	global $pagenow;
	if(function_exists('wpcf7_add_shortcode')) {
		wpcf7_add_shortcode('map', 'wpcf7_map_shorcode_handler', true);
		wpcf7_add_shortcode('map*', 'wpcf7_map_shorcode_handler', true);
	} else {
		if($pagenow != 'plugins.php') { return; }
		add_action('admin_notices', 'cfhiddenfieldserror');
		wp_enqueue_script('thickbox');
		function cfhiddenfieldserror() {
			$out = '<div class="error" id="messages"><p>';
			if(file_exists(WP_PLUGIN_DIR.'/contact-form-7/wp-contact-form-7.php')) {
				$out .= 'The Contact Form 7 is installed, but <strong>you must activate it</strong> in order for the Contact Form 7 Map Field plugin to work.';
			} else {
				$out .= 'The Contact Form 7 plugin must be installed for the Contact Form 7 Map Field plugin to work. <a href="'.admin_url('plugin-install.php?tab=plugin-information&plugin=contact-form-7&from=plugins&TB_iframe=true&width=600&height=550').'" class="thickbox" title="Contact Form 7">Install Now.</a>';
			}
			$out .= '</p></div>';	
			echo $out;
		}
	}
}

/* Shortcode handler */
function wpcf7_map_shorcode_handler($tag){
	if ( ! is_array( $tag ) )
		return '';
	$type = $tag['type'];
	$name = $tag['name'];
	if ( empty( $name ) )
		return '';
	$validation_error = wpcf7_get_validation_error( $name );
	$atts = '';
	$class_att = '';
	$class_att = wpcf7_form_controls_class( $type );
	if ( $validation_error )
		$class_att .= ' wpcf7-not-valid';
	if ( $class_att )
		$atts .= ' class="' . trim( $class_att ) . '"';
	$folder = dirname(__FILE__);
	$codeFile = "$folder/map-code-leaflet.html";
	$fh = fopen($codeFile, 'r');
	$theCode = fread($fh, filesize($codeFile));
	fclose($fh);
	$html = '<span class="wpcf7-form-control-wrap ' . $name . '">' . $theCode . $validation_error . '<input id="CF7MapLocationHidden" type="hidden" ' . $atts . ' name="' . $name . '" /></span>';
	
	return $html;
}

/* Validation filter */
add_filter( 'wpcf7_validate_map', 'wpcf7_map_validation_filter', 10, 2 );
add_filter( 'wpcf7_validate_map*', 'wpcf7_map_validation_filter', 10, 2 );
function wpcf7_map_validation_filter( $result, $tag ) {
	$type = $tag['type'];
	$name = $tag['name'];
	$options = (array) $tag['options'];
	$minZoom = 3;
	if ( preg_match( '%^zoom:([-0-9a-zA-Z_]+)$%', $options[0], $matches ) ) {
		if(is_numeric($matches[1]) && $matches[1] > $minZoom)$minZoom = $matches[1];
	}
	global $mapFieldName;
	$mapFieldName = $name;
	$_POST[$name] = trim( strtr( (string) $_POST[$name], "\n", " " ) );
	if ( 'map*' == $type ) {
		if ( '' == $_POST[$name] ) {
			$result['valid'] = false;
			$result['reason'][$name] = "Por favor selecciona una ubicación haciendo click sobre el mapa.";
		}else{
			$value = explode(";", $_POST[$name]);
			if($value[0] < $minZoom){
				$result['valid'] = false;
				$result['reason'][$name] = "Por razones de exactitud debes acercarte en el mapa y seleccionar una ubicación más precisa.";
			}
		}
	}
	return $result;
}

/* Tag generator */
add_action( 'admin_init', 'wpcf7_add_tag_generator_map', 20 );
function wpcf7_add_tag_generator_map() {
	if ( ! function_exists( 'wpcf7_add_tag_generator' ) )
		return;
	wpcf7_add_tag_generator( 'map', __( 'Map', 'wpcf7' ),
		'wpcf7-tg-pane-map', 'wpcf7_tg_pane_map' );
}
function wpcf7_tg_pane_map( &$contact_form ) {
?>
<div id="wpcf7-tg-pane-map" class="hidden">
<form action="">
<table>
<tr><td><input type="checkbox" name="required" />&nbsp;<?php echo esc_html( __( 'Required field?', 'wpcf7' ) ); ?></td></tr>
</table>
<table>
<tr><td><?php echo esc_html( __( 'Name', 'wpcf7' ) ); ?><br /><input type="text" name="name" class="tg-name oneline" /></td><td></td></tr>
</table>
<table>
<tr>
<td><code>Minimum Zoom</code><br />
<input type="text" name="zoom" class="option" value="8" /></td>
</tr>
</table>
<div class="tg-tag"><?php echo esc_html( __( "Copy this code and paste it into the form left.", 'wpcf7' ) ); ?><br /><input type="text" name="map" class="tag" readonly="readonly" onfocus="this.select()" /></div>
<div class="tg-mail-tag"><?php echo esc_html( __( "And, put this code into the Mail fields below.", 'wpcf7' ) ); ?><br /><span class="arrow">&#11015;</span>&nbsp;<input type="text" class="mail-tag" readonly="readonly" onfocus="this.select()" /></div>
</form>
</div>
<?php
}
?>