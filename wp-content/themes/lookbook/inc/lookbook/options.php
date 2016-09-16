<?php

/**
 * Add our Lookbook tab
 */
gpp_register_theme_option_tab( array(
    "name" => "lookbook_tab",
    "title" => __( "Lookbook", GPP_LOOKBOOK_TEXTDOMAIN ),
    "sections" => array(
        "lookbook_section_1" => array(
            "name" => "lookbook_section_1",
            "title" => __( "Lookbook", GPP_LOOKBOOK_TEXTDOMAIN ),
            "description" => ""
        )
    )
) );

/**
 * Add our Lookbook options
 */
$options["lookbook_enabled"] = array(
        "tab" => "lookbook_tab",
        "name" => "lookbook_enabled",
        "title" => __("Enable Lookbook",GPP_LOOKBOOK_TEXTDOMAIN),
        "description" => __( "If enabled select items will have the option for users to add the item to their Lookbook and download it as a PDF.", GPP_LOOKBOOK_TEXTDOMAIN ),
        "section" => "lookbook_section_1",
        "since" => "1.0",
        "id" => "lookbook_section_1",
        "type" => "select",
        "default" => "no",
        "valid_options" => array(
                "yes" => array(
                    "name" => "yes",
                    "title" => __( "Yes", GPP_LOOKBOOK_TEXTDOMAIN )
                ),
                "no" => array(
                    "name" => "no",
                    "title" => __( "No", GPP_LOOKBOOK_TEXTDOMAIN )
                )
        )
    );

$options["required_type"] = array(
        "tab" => "lookbook_tab",
        "name" => "required_type",
        "title" => __("Require Email",GPP_LOOKBOOK_TEXTDOMAIN),
        "description" => __( "If email is required to download the user must add their name and email address, which will be saved as a Lookbook entry and can easily be exported as a CSV file.", GPP_LOOKBOOK_TEXTDOMAIN ),
        "section" => "lookbook_section_1",
        "since" => "1.0",
        "id" => "lookbook_section_1",
        "type" => "select",
        "default" => "",
        "valid_options" => array(
                "yes" => array(
                    "name" => "freedownload",
                    "title" => __( "No", GPP_LOOKBOOK_TEXTDOMAIN )
                ),
                "no" => array(
                    "name" => "email",
                    "title" => __( "Yes", GPP_LOOKBOOK_TEXTDOMAIN )
                )
        )
    );

gpp_register_theme_options( $options );