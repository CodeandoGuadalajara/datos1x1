<?php
/**
 * Define the Tabs appearing on the Theme Options page
 * Tabs contains sections
 * Options are assigned to both Tabs and Sections
 * See README.md for a full list of option types
 */

$general_settings_tab = array(
    "name" => "general_tab",
    "title" => __( "General", "gpp" ),
    "sections" => array(
        "general_section_1" => array(
            "name" => "general_section_1",
            "title" => __( "General", "gpp" ),
            "description" => ""
        )
    )
);

gpp_register_theme_option_tab( $general_settings_tab );

$colors_tab = array(
    "name" => "colors_tab",
    "title" => __( "Style", "gpp" ),
    "sections" => array(
        "colors_section_1" => array(
            "name" => "colors_section_1",
            "title" => __( "Style", "gpp" ),
            "description" => ""
        )
    )
);

gpp_register_theme_option_tab( $colors_tab );

/**
* The following example shows you how to register theme options and assign them to tabs and sections:
*/
$options = array(
    'logo' => array(
        "tab" => "general_tab",
        "name" => "logo",
        "title" => __( "Logo", "gpp" ),
        "description" => __( "Use a transparent png or jpg image", "gpp" ),
        "section" => "general_section_1",
        "since" => "1.0",
        "id" => "general_section_1",
        "type" => "image",
        "default" => ""
    ),
    'favicon' => array(
        "tab" => "general_tab",
        "name" => "favicon",
        "title" => __( "Favicon", "gpp" ),
        "description" => __( "Use a transparent png or ico image", "gpp" ),
        "section" => "general_section_1",
        "since" => "1.0",
        "id" => "general_section_1",
        "type" => "image",
        "default" => ""
    ),
    'font' => array(
        "tab" => "general_tab",
        "name" => "font",
        "title" => __( "Headline Font", "gpp" ),
        "description" => __( '<a href="' . get_option('siteurl') . '/wp-admin/admin-ajax.php?action=fonts&font=header&height=600&width=640" class="thickbox">Preview and choose a font</a>', "gpp" ),
        "section" => "general_section_1",
        "since" => "1.0",
        "id" => "general_section_1",
        "type" => "select",
        "default" => "",
        "valid_options" => gpp_font_array()
    ),
    'font_alt' => array(
        "tab" => "general_tab",
        "name" => "font_alt",
        "title" => __( "Body Font", "gpp" ),
        "description" => __( '<a href="' . get_option('siteurl') . '/wp-admin/admin-ajax.php?action=fonts&font=body&height=600&width=640" class="thickbox">Preview and choose a font</a>', "gpp" ),
        "section" => "general_section_1",
        "since" => "1.0",
        "id" => "general_section_1",
        "type" => "select",
        "default" => "",
        "valid_options" => gpp_font_array()
    ),
    'blog_categories' => array(
        "tab" => "general_tab",
        "name" => "blog_categories",
        "title" => __( "Blog Category", "gpp" ),
        "description" => __( 'Select your blog category', "gpp" ),
        "section" => "general_section_1",
        "since" => "1.0",
        "id" => "general_section_1",
        "type" => "checkbox",
        "default" => array("uncategorized"),
        "valid_options" => gpp_get_taxonomy_list()
    ),
    "css" => array(
        "tab" => "colors_tab",
        "name" => "css",
        "title" => __( "Custom CSS", "gpp" ),
        "description" => __( "Add some custom CSS to your theme.", "gpp" ),
        "section" => "colors_section_1",
        "since" => "1.0",
        "id" => "colors_section_1",
        "type" => "textarea",
        "sanitize" => "html",
        "default" => ""
    )
);

gpp_register_theme_options( $options );

?>