<?php

/**
 * Register Theme Settings
 *
 * Register theme options array to hold all theme options.
 *
 * @link	http://codex.wordpress.org/Function_Reference/register_setting	Codex Reference: register_setting()
 *
 * @param	string		$option_group		Unique Settings API identifier; passed to settings_fields() call
 * @param	string		$option_name		Name of the wp_options database table entry
 * @param	callback	$sanitize_callback	Name of the callback function in which user input data are sanitized
 */
register_setting(
	// $option_group
	gpp_get_current_theme_id() . '_options',
	// $option_name
	gpp_get_current_theme_id() . '_options',
	// $sanitize_callback
	'gpp_options_validate'
);

/**
 * Register Global Admin Javascript Variables
 *
 * Register JS variables used by theme options admin Javascript.
 *
 * @global	array	Settings Page Tab definitions
 *
 */

function gpp_register_admin_js_globals(){

	global $gpp_tabs;

	$tab = '';
	$selected_tab = '';

	$selected_tab = $selected_tab ? $gpp_tabs[0]['name'] : $tab;
	$themedata = wp_get_theme();
    $theme_title = $themedata->title;
	$theme_name = strtolower( $theme_title );
	$theme_url = get_template_directory_uri();

	echo "<script type=\"text/javascript\">\n";
	echo "var gpp = {\n";
	echo "    'theme' : '$theme_name',\n";

	if( isset( $_GET['page']) && esc_attr( $_GET['page'] ) == 'gpp-settings' && $selected_tab )
		echo "    'current_tab' : '$selected_tab',\n";

	echo "    'theme_url' : '$theme_url'\n";
	echo "}" . "\n";
	echo "</script>" . "\n";

}

add_action( 'admin_enqueue_scripts', 'gpp_register_admin_js_globals', 1 );

/**
 * Theme register_setting() sanitize callback
 *
 * Validate and whitelist user-input data before updating Theme
 * Options in the database. Only whitelisted options are passed
 * back to the database, and user-input data for all whitelisted
 * options are sanitized.
 *
 * @link	http://codex.wordpress.org/Data_Validation	Codex Reference: Data Validation
 *
 * @param	array	$input	Raw user-input data submitted via the Theme Settings page
 * @return	array	$input	Sanitized user-input data passed to the database
 *
 * @global	array	Settings Page Tab definitions
 *
 */
function gpp_options_validate( $input ) {

	global $gpp_tabs;

	// This is the "whitelist": current settings
	$valid_input = (array) gpp_get_options();
	// Get the array of Theme settings, by Settings Page tab
	$settingsbytab = gpp_get_settings_by_tab();
	// Get the array of option parameters
	$option_parameters = gpp_get_option_parameters();
	// Get the array of option defaults
	$option_defaults = gpp_get_option_defaults();
	// Get list of tabs

	// Determine what type of submit was input
	$submittype = 'submit';
	foreach ( $gpp_tabs as $tab ) {
		$resetname = 'reset-' . $tab['name'];
		if ( ! empty( $input[$resetname] ) ) {
			$submittype = 'reset';
		}
	}

	// Determine what tab was input
	$submittab = '';
	foreach ( $gpp_tabs as $tab ) {
		$submitname = 'submit-' . $tab['name'];
		$resetname = 'reset-' . $tab['name'];
		if ( ! empty( $input[ $submitname ] ) || ! empty( $input[ $resetname ] ) ) {
			$submittab = $tab['name'];
		}
	}
	// Get settings by tab
	$tabsettings = $settingsbytab[ $submittab ];

	// Loop through each tab setting
	foreach ( $tabsettings as $setting ) {

		// If no option is selected, set the default
		$valid_input[ $setting ] = ( ! isset( $input[ $setting ] ) ? $option_defaults[ $setting ] : $input[ $setting ] );

		// If submit, validate/sanitize $input
		if ( 'submit' == $submittype ) {

			// Get the setting details from the defaults array
			$optiondetails = $option_parameters[ $setting ];
			// Get the array of valid options, if applicable
			$valid_options = ( isset( $optiondetails['valid_options'] ) ? $optiondetails['valid_options'] : false );

			// Validate checkbox fields
			if ( 'checkbox' == $optiondetails['type'] ) {
				// If input value is set and is true, return true; otherwise return false
				if( isset( $input[ $setting ] ) && is_array( $input[ $setting ] ) ) :
					foreach( $input[ $setting ] as $key => $checkbox ) :
						if( isset( $checkbox ) && 'on' == $checkbox ) {
							$valid_input[ $setting ][] =  true;
						}
					endforeach;
				else:
					$valid_input[ $setting ] = ( ( isset( $input[ $setting ] ) && true == $input[ $setting ] ) ? true : false );
				endif;
			}
			// Validate radio button fields
			else if ( 'radio' == $optiondetails['type'] ) {
				// Only update setting if input value is in the list of valid options
				$valid_input[ $setting ] = ( array_key_exists( $input[ $setting ], $valid_options ) ? $input[ $setting ] : $valid_input[ $setting ] );
			}
			// Validate select fields
			else if ( 'select' == $optiondetails['type'] ) {
				// Only update setting if input value is in the list of valid options
				$valid_input[ $setting ] = ( array_key_exists( $setting, $valid_options ) ? $input[ $setting ] : $valid_input[ $setting ] );
			}
			// Validate text input and textarea fields
			else if ( ( 'text' == $optiondetails['type'] || 'textarea' == $optiondetails['type'] ) ) {
				// Validate no-HTML content
				if ( 'nohtml' == $optiondetails['sanitize'] ) {
					// Pass input data through the wp_filter_nohtml_kses filter
					$valid_input[ $setting ] = wp_filter_nohtml_kses( $input[ $setting ] );
				}
				// Validate HTML content
				if ( 'html' == $optiondetails['sanitize'] ) {
					// Pass input data through the wp_filter_kses filter
					$valid_input[ $setting ] = addslashes( $input[ $setting ] );
				}
			}
		}
		// If reset, reset defaults
		elseif ( 'reset' == $submittype ) {
			// Set $setting to the default value
			$valid_input[ $setting ] = $option_defaults[ $setting ];
		}
	}
	return $valid_input;

}

/**
 * Globalize the variable that holds
 * the Settings Page tab definitions
 *
 * @global	array	Settings Page Tab definitions
 */
global $gpp_tabs;

/**
 * Call add_settings_section() for each Settings
 *
 * Loop through each Theme Settings page tab, and add
 * a new section to the Theme Settings page for each
 * section specified for each tab.
 *
 * @link	http://codex.wordpress.org/Function_Reference/add_settings_section	Codex Reference: add_settings_section()
 *
 * @param	string		$sectionid	Unique Settings API identifier; passed to add_settings_field() call
 * @param	string		$title		Title of the Settings page section
 * @param	callback	$callback	Name of the callback function in which section text is output
 * @param	string		$pageid		Name of the Settings page to which to add the section; passed to do_settings_sections()
 */
foreach ( $gpp_tabs as $tab ) {
	$tabname = $tab['name'];
	$tabsections = $tab['sections'];
	foreach ( $tabsections as $section ) {

		$icon = '';
		if ( isset( $section['icon'] ) && '' != $section['icon'] )
			$icon = '<span class="gpp_icon gpp_icon_' . $section['icon'] . '"></span>';

		add_settings_section( 'gpp_' . $section['name'] . '_section', '<span class="tab_section ' . $tabname . '" id="' . $section['name'] . '">' . $icon . $section['title'] . '</span>', 'gpp_sections_callback', 'gpp_' . $tabname . '_tab' );

	}
}

/**
 * Callback for add_settings_section()
 *
 * Generic callback to output the section text
 * for each Plugin settings section.
 *
 * @param	array	$section_passed	Array passed from add_settings_section()
 */
function gpp_sections_callback( $section_passed ) {
	global $gpp_tabs;
	foreach ( $gpp_tabs as $tabname => $tab ) {
		$tabsections = $tab['sections'];
		foreach ( $tabsections as $sectionname => $section ) {
			if ( 'gpp_' . $sectionname . '_section' == $section_passed['id'] ) {
				?>
				<p><?php echo $section['description']; ?></p>
				<?php
			}
		}
	}
}

/**
 * Globalize the variable that holds
 * all the Theme option parameters
 *
 * @global	array	Theme options parameters
 */
global $option_parameters;
$option_parameters = gpp_get_option_parameters();

/**
 * Call add_settings_field() for each Setting Field
 *
 * Loop through each Theme option, and add a new
 * setting field to the Theme Settings page for each
 * setting.
 *
 * @link	http://codex.wordpress.org/Function_Reference/add_settings_field	Codex Reference: add_settings_field()
 *
 * @param	string		$settingid	Unique Settings API identifier; passed to the callback function
 * @param	string		$title		Title of the setting field
 * @param	callback	$callback	Name of the callback function in which setting field markup is output
 * @param	string		$pageid		Name of the Settings page to which to add the setting field; passed from add_settings_section()
 * @param	string		$sectionid	ID of the Settings page section to which to add the setting field; passed from add_settings_section()
 * @param	array		$args		Array of arguments to pass to the callback function
 */
foreach ( $option_parameters as $option ) {
	$optionname = $option['name'];
	$optiontitle = $option['title'];
	$optiontab = $option['tab'];
	$optionsection = $option['section'];
	add_settings_field(
		// $settingid
		'gpp_setting_' . $optionname,
		// $title
		$optiontitle,
		// $callback
		'gpp_setting_callback',
		// $pageid
		'gpp_' . $optiontab . '_tab',
		// $sectionid
		'gpp_' . $optionsection . '_section',
		// $args
		$option
	);
}

/**
 * Callback for get_settings_field()
 */
function gpp_setting_callback( $option ) {
	$gpp_options = (array) gpp_get_options();

	$option_parameters = gpp_get_option_parameters();
	$optionname = $option['name'];
	$optiontitle = $option['title'];
	$fieldtype = $option['type'];
	$attr = $option_parameters[ $option['name'] ];
	$value = $gpp_options[ $optionname ];

    //Determine the type of input field
    switch ( $fieldtype ) {

        //Render Text Input
        case 'text': gpp_field_text( $value, $attr );
		echo '<span class="option-description">' . $option['description'] . '</span>';
        break;

        //Render Hidden Input
        case 'hidden': gpp_field_hidden( $value, $attr );
        break;

        //Render textarea options
        case 'textarea': gpp_field_textarea( $value, $attr );
		echo '<span class="option-description">' . $option['description'] . '</span>';
        break;

        //Render select dropdowns
        case 'select': gpp_field_select( $value, $attr );
		echo '<span class="option-description">' . $option['description'] . '</span>';
        break;

        //Render radio dropdowns
        case 'radio': gpp_field_radio( $value, $attr );
		echo '<span class="option-description">' . $option['description'] . '</span>';
        break;

        //Render radio image dropdowns
        case 'radio_image': gpp_field_radio_image( $value, $attr );
		echo '<span class="option-description">' . $option['description'] . '</span>';
        break;

        //Render checkboxes
        case 'checkbox': gpp_field_checkbox( $value, $attr );
		echo '<span class="option-description">' . $option['description'] . '</span>';
        break;

        //Render color picker
        case 'color': gpp_field_color( $value, $attr );
		echo '<span class="option-description">' . $option['description'] . '</span>';
        break;

        //Render uploaded image
        case 'image': gpp_field_image( $value, $attr );
		echo '<span class="option-description">' . $option['description'] . '</span>';
        break;

         //Render uploaded gallery
        case 'gallery': gpp_field_gallery( $value, $attr );
		echo '<span class="option-description">' . $option['description'] . '</span>';
        break;

	    default:
	    break;

	}
}