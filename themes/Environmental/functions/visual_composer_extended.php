<?php
/*
* Extended Visual Composer plugin
*/

// Remove sidebar element
vc_remove_element("vc_widget_sidebar");
vc_remove_element("vc_images_carousel");
vc_remove_element("vc_toggle");
vc_remove_element("vc_tour");
vc_remove_element("vc_carousel");
vc_remove_element("vc_cta_button");

// Remove default WordPress widgets
vc_remove_element("vc_wp_search");
vc_remove_element("vc_wp_meta");
vc_remove_element("vc_wp_recentcomments");
vc_remove_element("vc_wp_calendar");
vc_remove_element("vc_wp_pages");
vc_remove_element("vc_wp_tagcloud");
vc_remove_element("vc_wp_custommenu");
vc_remove_element("vc_wp_text");
vc_remove_element("vc_wp_posts");
vc_remove_element("vc_wp_links");
vc_remove_element("vc_wp_categories");
vc_remove_element("vc_wp_archives");
vc_remove_element("vc_wp_rss");

vc_add_param("vc_row", array(
	"type"        => "dropdown",
	"class"       => "",
	"heading"     => __("Row style", "vh"),
	"admin_label" => true,
	"param_name"  => "type",
	"value"       => array(
		__( "Default", "vh" )           => "0",
		__( "Full Width - White", "vh" ) => "1"
	),
	"description" => ""
));

// Gap
vc_map( array(
	"name"     => __( "Gap", "vh" ),
	"base"     => "vh_gap",
	"icon"     => "icon-wpb-ui-gap-content",
	"class"    => "vh_vc_sc_gap",
	"category" => __( "by Environmental", "vh" ),
	"params"   => array(
		array(
			"type"        => "textfield",
			"class"       => "",
			"heading"     => __( "Gap height", "vh" ),
			"admin_label" => true,
			"param_name"  => "height",
			"value"       => "10",
			"description" => __( "In pixels", "vh" )
		)
	)
) );

$colors_arr = array(
	__("Red", "vh")    => "red",
	__("Blue", "vh")   => "blue",
	__("Yellow", "vh") => "yellow",
	__("Green", "vh")  => "green"
);

// Pricing table
wpb_map( array(
		"name"      => __( "Pricing Table", "vh" ),
		"base"      => "vh_pricing_table",
		"class"     => "vh-pricing-tables-class",
		"icon"      => "icon-wpb-ui-pricing_table-content",
		"category"  => __( "by Environmental", "vh" ),
		"params"    => array(
			array(
				"type"        => "dropdown",
				"heading"     => __("Color", "vh"),
				"param_name"  => "pricing_block_color",
				"value"       => $colors_arr,
				"description" => __("Pricing block color.", "vh")
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Title", "vh" ),
				"param_name"  => "pricing_title",
				"value"       => "",
				"description" => __( "Please add offer title.", "vh" )
			),
			array(
				"type"        => "textarea",
				"heading"     => __( "Description text 1", "vh" ),
				"param_name"  => "content1",
				"value"       => "",
				"description" => __( "Please add first part of your offer text.", "vh" )
			),
			array(
				"type"        => "textarea_html",
				"heading"     => __( "Description text 2", "vh" ),
				"param_name"  => "content2",
				"value"       => "",
				"description" => __( "Please add second part of your offer text.", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Price", "vh" ),
				"param_name"  => "price",
				"value"       => "",
				"description" => __( "Please add offer price.", "vh" )
			),
			array(
				"type"        => "vc_link",
				"heading"     => __( "", "vh" ),
				"param_name"  => "button_link",
				"value"       => "",
				"description" => __( "Please add offer button link.", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Extra class name", "vh" ),
				"param_name"  => "el_class",
				"value"       => "",
				"description" => __( "If you wish to style particular content element differently, then use this field to add a class name.", "vh" )
			)
		)
	)
);

// Update Buttons map
$colors_arr = array(__("Transparent", "vh") => "btn-transparent", __("Blue", "vh") => "btn-primary", __("Light Blue", "vh") => "btn-info", __("Green", "vh") => "btn-success", __("Yellow", "vh") => "btn-warning", __("Red", "vh") => "btn-danger", __("Inverse", "vh") => "btn-inverse");

$icons_arr = array(
	__("None", "vh")                     => "none",
	__("Address book icon", "vh")        => "wpb_address_book",
	__("Alarm clock icon", "vh")         => "wpb_alarm_clock",
	__("Anchor icon", "vh")              => "wpb_anchor",
	__("Application Image icon", "vh")   => "wpb_application_image",
	__("Arrow icon", "vh")               => "wpb_arrow",
	__("Asterisk icon", "vh")            => "wpb_asterisk",
	__("Hammer icon", "vh")              => "wpb_hammer",
	__("Balloon icon", "vh")             => "wpb_balloon",
	__("Balloon Buzz icon", "vh")        => "wpb_balloon_buzz",
	__("Balloon Facebook icon", "vh")    => "wpb_balloon_facebook",
	__("Balloon Twitter icon", "vh")     => "wpb_balloon_twitter",
	__("Battery icon", "vh")             => "wpb_battery",
	__("Binocular icon", "vh")           => "wpb_binocular",
	__("Document Excel icon", "vh")      => "wpb_document_excel",
	__("Document Image icon", "vh")      => "wpb_document_image",
	__("Document Music icon", "vh")      => "wpb_document_music",
	__("Document Office icon", "vh")     => "wpb_document_office",
	__("Document PDF icon", "vh")        => "wpb_document_pdf",
	__("Document Powerpoint icon", "vh") => "wpb_document_powerpoint",
	__("Document Word icon", "vh")       => "wpb_document_word",
	__("Bookmark icon", "vh")            => "wpb_bookmark",
	__("Camcorder icon", "vh")           => "wpb_camcorder",
	__("Camera icon", "vh")              => "wpb_camera",
	__("Chart icon", "vh")               => "wpb_chart",
	__("Chart pie icon", "vh")           => "wpb_chart_pie",
	__("Clock icon", "vh")               => "wpb_clock",
	__("Fire icon", "vh")                => "wpb_fire",
	__("Heart icon", "vh")               => "wpb_heart",
	__("Mail icon", "vh")                => "wpb_mail",
	__("Play icon", "vh")                => "wpb_play",
	__("Shield icon", "vh")              => "wpb_shield",
	__("Video icon", "vh")               => "wpb_video"
);

$target_arr = array(__("Same window", "vh") => "_self", __("New window", "vh") => "_blank");
$size_arr = array(__("Regular size", "vh") => "wpb_regularsize", __("Large", "vh") => "btn-large", __("Small", "vh") => "btn-small", __("Mini", "vh") => "btn-mini");

vc_map( array(
  "name" => __("Button", "vh"),
  "base" => "vc_button",
  "icon" => "icon-wpb-ui-button",
  "category" => __('Content', 'vh'),
  "params" => array(
	array(
	  "type" => "textfield",
	  "heading" => __("Text on the button", "vh"),
	  "holder" => "button",
	  "class" => "wpb_button",
	  "param_name" => "title",
	  "value" => __("Text on the button", "vh"),
	  "description" => __("Text on the button.", "vh")
	),
	array(
	  "type" => "textfield",
	  "heading" => __("URL (Link)", "vh"),
	  "param_name" => "href",
	  "description" => __("Button link.", "vh")
	),
	array(
	  "type" => "dropdown",
	  "heading" => __("Target", "vh"),
	  "param_name" => "target",
	  "value" => $target_arr,
	  "dependency" => Array('element' => "href", 'not_empty' => true)
	),
	array(
	  "type" => "dropdown",
	  "heading" => __("Color", "vh"),
	  "param_name" => "color",
	  "value" => $colors_arr,
	  "description" => __("Button color.", "vh")
	),
	array(
	  "type" => "dropdown",
	  "heading" => __("Icon", "vh"),
	  "param_name" => "icon",
	  "value" => $icons_arr,
	  "description" => __("Button icon.", "vh")
	),
	array(
	  "type" => "dropdown",
	  "heading" => __("Size", "vh"),
	  "param_name" => "size",
	  "value" => $size_arr,
	  "description" => __("Button size.", "vh")
	),
	array(
	  "type" => "textfield",
	  "heading" => __("Extra class name", "vh"),
	  "param_name" => "el_class",
	  "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "vh")
	)
  ),
  "js_view" => 'VcButtonView'
) );

$effect_array = array('linear', 'swing', 'easeInQuad', 'easeOutQuad', 'easeInOutQuad', 'easeInCubic', 'easeOutCubic', 'easeInOutCubic', 'easeInQuart', 'easeOutQuart', 'easeInOutQuart', 'easeInQuint', 'easeOutQuint', 'easeInOutQuint', 'easeInExpo', 'easeOutExpo', 'easeInOutExpo', 'easeInSine', 'easeOutSine', 'easeInOutSine', 'easeInCirc', 'easeOutCirc', 'easeInOutCirc', 'easeInElastic', 'easeOutElastic', 'easeInOutElastic', 'easeInBack', 'easeOutBack', 'easeInOutBack', 'easeInBounce', 'easeOutBounce', 'easeInOutBounce');

// Post slider
wpb_map( array(
		"name"      => __( "Post slider", "vh" ),
		"base"      => "vh_post_slider",
		"class"     => "",
		"icon"      => "icon-wpb-ui-pricing_table-content",
		"category"  => __( "by Environmental", "vh" ),
		"params"    => array(
			array(
				"type"        => "textfield",
				"heading"     => __("Categories", "vh"),
				"param_name"  => "post_slider_category",
				"value"       => '',
				"description" => __("Which category posts to display. Multiple categories separated with comma.", "vh")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Animation effect", "vh"),
				"param_name" => "post_slider_animation",
				"value" => $effect_array,
				"description" => __("Animation for post slider.", "vh")
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Auto play", "vh" ),
				"param_name"  => "post_slider_auto",
				"value"       => "false",
				"description" => __( "Auto play slider when website loads. true/false/time in ms.", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Animation speed", "vh" ),
				"param_name"  => "post_slider_speed",
				"value"       => "2000",
				"description" => __( "Post slider animation speed. Time in ms.", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Post limit", "vh" ),
				"param_name"  => "post_slider_limit",
				"value"       => "0",
				"description" => __( "How many posts should be displayed", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Slider link", "vh" ),
				"param_name"  => "post_slider_link",
				"value"       => "http://example.com",
				"description" => __( "Link for the \"+\" sign above slider.", "vh" )
			)
		)
	)
);

// Event slider
wpb_map( array(
		"name"      => __( "Event slider", "vh" ),
		"base"      => "vh_event_slider",
		"class"     => "",
		"icon"      => "icon-wpb-ui-pricing_table-content",
		"category"  => __( "by Environmental", "vh" ),
		"params"    => array(
			array(
				"type"        => "textfield",
				"heading"     => __( "Title", "vh" ),
				"param_name"  => "event_slider_title",
				"value"       => "",
				"description" => __( "Title for this module.", "vh" )
			),
			array(
				"type" => "dropdown",
				"heading" => __("Animation effect", "vh"),
				"param_name" => "event_slider_animation",
				"value" => $effect_array,
				"description" => __("Animation for event slider.", "vh")
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Auto play", "vh" ),
				"param_name"  => "event_slider_auto",
				"value"       => "false",
				"description" => __( "Auto play slider when website loads. true/false/time in ms.", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Animation speed", "vh" ),
				"param_name"  => "event_slider_speed",
				"value"       => "2000",
				"description" => __( "Event slider animation speed. Time in ms.", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Event limit", "vh" ),
				"param_name"  => "event_slider_limit",
				"value"       => "0",
				"description" => __( "How many events should be displayed.", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Slider link", "vh" ),
				"param_name"  => "event_slider_link",
				"value"       => "http://example.com",
				"description" => __( "Link for the \"+\" sign above slider.", "vh" )
			)
		)
	)
);

// Animals & Places
wpb_map( array(
		"name"      => __( "Animals & Places", "vh" ),
		"base"      => "vh_animals_places",
		"class"     => "",
		"icon"      => "icon-wpb-ui-pricing_table-content",
		"category"  => __( "by Environmental", "vh" ),
		"params"    => array(
			array(
				"type" => "textfield",
				"heading" => __("Extra link", "vh"),
				"param_name" => "animals_places_link",
				"value" => "http://example.com",
				"description" => __("Link for the \"+\" sign above slider.", "vh")
			),
			array(
				"type" => "textfield",
				"heading" => __("Animal categories", "vh"),
				"param_name" => "animals_places_animals",
				"value" => "",
				"description" => __("Animal categories to display entries from.", "vh")
			),
			array(
				"type" => "textfield",
				"heading" => __("Places categories", "vh"),
				"param_name" => "animals_places_places",
				"value" => "",
				"description" => __("Place categories to display entries from.", "vh")
			),
			array(
				"type" => "textfield",
				"heading" => __("Limit", "vh"),
				"param_name" => "animals_places_limit",
				"value" => "",
				"description" => __("Limit entries to specific count.", "vh")
			)
		)
	)
);

// Newsletter
wpb_map( array(
		"name"      => __( "Newsletter", "vh" ),
		"base"      => "vh_newsletter",
		"class"     => "",
		"icon"      => "icon-wpb-ui-pricing_table-content",
		"category"  => __( "by Environmental", "vh" ),
		"params"    => array(
			array(
				"type"        => "textfield",
				"heading"     => __( "Title", "vh" ),
				"param_name"  => "newsletter_title",
				"value"       => "",
				"description" => __( "Title for this module.", "vh" )
			),
			array(
				"type" => "textfield",
				"heading" => __("Introduction", "vh"),
				"param_name" => "newsletter_introduction",
				"value" => "",
				"description" => __("Newsletter introduction.", "vh")
			)
		)
	)
);

// Team member
wpb_map( array(
		"name"      => __( "Team member", "vh" ),
		"base"      => "vh_team_member",
		"class"     => "",
		"icon"      => "icon-wpb-ui-pricing_table-content",
		"category"  => __( "by Environmental", "vh" ),
		"params"    => array(
			array(
				"type"        => "textfield",
				"heading"     => __("Name / Surname", "vh"),
				"param_name"  => "team_member_name",
				"value"       => '',
				"description" => __("Name and surname for team member.", "vh")
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Description", "vh" ),
				"param_name"  => "team_member_description",
				"value"       => "",
				"description" => __( "Team member short description.", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Picture ID", "vh" ),
				"param_name"  => "team_member_picture",
				"value"       => "",
				"description" => __( "ID of the picture from media library", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Members facebook", "vh" ),
				"param_name"  => "team_member_facebook",
				"value"       => "",
				"description" => __( "Members facebook link.", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Members twitter", "vh" ),
				"param_name"  => "team_member_twitter",
				"value"       => "",
				"description" => __( "Members twitter link.", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Members youtube", "vh" ),
				"param_name"  => "team_member_youtube",
				"value"       => "",
				"description" => __( "Members youtube link.", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Contact form ID", "vh" ),
				"param_name"  => "team_member_form",
				"value"       => "",
				"description" => __( "Contact form 7 shortcode ID.", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Contact form title", "vh" ),
				"param_name"  => "team_member_title",
				"value"       => "",
				"description" => __( "Title for contact form.", "vh" )
			)
		)
	)
);

wpb_map( array(
		"name"      => __( "Contact form", "vh" ),
		"base"      => "vh_contact_form",
		"class"     => "",
		"icon"      => "icon-wpb-ui-pricing_table-content",
		"category"  => __( "by Environmental", "vh" ),
		"params"    => array(
			array(
				"type"        => "textfield",
				"heading"     => __("Contact form IDs", "vh"),
				"param_name"  => "contact_form_ids",
				"value"       => '',
				"description" => __("IDs for contact members.", "vh")
			)
		)
	)
);

wpb_map( array(
		"name"      => __( "Twitter", "vh" ),
		"base"      => "vh_twitter",
		"class"     => "",
		"icon"      => "icon-wpb-ui-pricing_table-content",
		"category"  => __( "by Environmental", "vh" ),
		"params"    => array(
			array(
				"type"        => "textfield",
				"heading"     => __("Twitter username", "vh"),
				"param_name"  => "twitter_username",
				"value"       => '',
				"description" => __("From which you want to display tweets from.", "vh")
			),
			array(
				"type"        => "textfield",
				"heading"     => __("Tweet limit", "vh"),
				"param_name"  => "twitter_limit",
				"value"       => '5',
				"description" => __("How many tweets to display.", "vh")
			),
			array(
				"type"        => "textfield",
				"heading"     => __("Extra link", "vh"),
				"param_name"  => "twitter_link",
				"value"       => 'http://example.com',
				"description" => __("Link for the \"+\" sign above slider.", "vh")
			)
		)
	)
);

wpb_map( array(
		"name"      => __( "Animals & Places table", "vh" ),
		"base"      => "vh_animals_places_table",
		"class"     => "",
		"icon"      => "icon-wpb-ui-pricing_table-content",
		"category"  => __( "by Environmental", "vh" ),
		"params"    => array(
		)
	)
);

$animals_places_list = array('Animals', 'Places');
wpb_map( array(
		"name"      => __( "Animals & Places list", "vh" ),
		"base"      => "vh_animals_places_list",
		"class"     => "",
		"icon"      => "icon-wpb-ui-pricing_table-content",
		"category"  => __( "by Environmental", "vh" ),
		"params"    => array(
			array(
				"type"        => "textfield",
				"heading"     => __("Category", "vh"),
				"param_name"  => "animals_places_list_category",
				"value"       => '',
				"description" => __("Which category posts will be displayed.", "vh")
			),
			array(
				"type"        => "textfield",
				"heading"     => __("Post limit", "vh"),
				"param_name"  => "animals_places_list_limit",
				"value"       => '20',
				"description" => __("How many posts to display.", "vh")
			),
			array(
				"type"        => "dropdown",
				"heading"     => __("Module type", "vh"),
				"param_name"  => "animals_places_list_type",
				"value"       => $animals_places_list,
				"description" => __("Which type of posts will be displayed.", "vh")
			)
		)
	)
);

function vh_add_vc_grid_shortcodes( $shortcodes ) {
	$shortcodes['vh_vcgi_image'] = array(
		"name" => __("Environmental - Image"),
		"base" => "vh_vcgi_image",
		"class" => "",
		"icon" => "icon-wpb-ui-gap-content",
		"category" => __( "by Environmental", "vh" ),
		"params" => array(),
		"post_type" => Vc_Grid_Item_Editor::postType(),
		"show_settings_on_create" => false
	);

	$shortcodes['vh_vcgi_content'] = array(
		"name" => __("Environmental - Content"),
		"base" => "vh_vcgi_content",
		"class" => "",
		"icon" => "icon-wpb-ui-gap-content",
		"category" => __( "by Environmental", "vh" ),
		"params" => array(),
		"post_type" => Vc_Grid_Item_Editor::postType(),
		"show_settings_on_create" => false
	);

	$shortcodes['vh_vcgi_readmore'] = array(
		"name" => __("Environmental - Read more"),
		"base" => "vh_vcgi_readmore",
		"class" => "",
		"icon" => "icon-wpb-ui-gap-content",
		"category" => __( "by Environmental", "vh" ),
		"params" => array(),
		"post_type" => Vc_Grid_Item_Editor::postType(),
		"show_settings_on_create" => false
	);

	$shortcodes['vh_vcgi_categories'] = array(
		"name" => __("Environmental - Categories"),
		"base" => "vh_vcgi_categories",
		"class" => "",
		"icon" => "icon-wpb-ui-gap-content",
		"category" => __( "by Environmental", "vh" ),
		"params" => array(),
		"post_type" => Vc_Grid_Item_Editor::postType(),
		"show_settings_on_create" => false
	);

	return $shortcodes;
}
add_filter( 'vc_grid_item_shortcodes', 'vh_add_vc_grid_shortcodes', 100 );

?>
