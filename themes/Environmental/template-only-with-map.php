<?php
/**
 * Template Name: Only with map
 */
get_header();

$vh_map_lat     = (get_post_meta( $post->ID, 'vh_map_lat', true )) ? get_post_meta( $post->ID, 'vh_map_lat', true ) : '';
$vh_map_long    = (get_post_meta( $post->ID, 'vh_map_long', true )) ? get_post_meta( $post->ID, 'vh_map_long', true ) : '';
$vh_map_address = (get_post_meta( $post->ID, 'vh_map_address', true )) ? get_post_meta( $post->ID, 'vh_map_address', true ) : '';
$vh_map_phone   = (get_post_meta( $post->ID, 'vh_map_phone', true )) ? get_post_meta( $post->ID, 'vh_map_phone', true ) : '';
$vh_map_email   = (get_post_meta( $post->ID, 'vh_map_email', true )) ? get_post_meta( $post->ID, 'vh_map_email', true ) : '';
$contact_us     = __('Contact us', 'vh' );
$directions     = __('Get directions', 'vh' );


$vh_map_text    = (get_post_meta( $post->ID, 'vh_map_text', true )) ? get_post_meta( $post->ID, 'vh_map_text', true ) : '';

?>
<script type="text/javascript">
(function() {
	//var map, gMap = google.maps;
	window.onload = function() {

	var secheltLoc = new google.maps.LatLng(<?php echo $vh_map_lat; ?>, <?php echo $vh_map_long; ?>);

		var myMapOptions = {
			 zoom: 15,
			 center: secheltLoc,
			 scrollwheel: false,
			 mapTypeId: google.maps.MapTypeId.ROADMAP,
			 disableDefaultUI: true
		};
		var theMap = new google.maps.Map(document.getElementById("map"), myMapOptions);

		//var myIcon = new google.maps.MarkerImage("<?php echo get_template_directory_uri(); ?>/images/map-marker.png", null, null, null, new google.maps.Size(22,35));

		var marker = new google.maps.Marker({
			map: theMap,
			draggable: true,
			position: new google.maps.LatLng(<?php echo $vh_map_lat; ?>, <?php echo $vh_map_long; ?>),
			visible: true,
			//icon: myIcon
		});

		var myOptions = {
			// content: boxText,
			 disableAutoPan: false
			,maxWidth: 0
			,pixelOffset: new google.maps.Size(-215, -50)
			,zIndex: null
			,alignBottom: true
			,boxStyle: {
				background: ""
				,opacity: 1
				,padding: "50px 0 4px 0"
				,top: "126px"
				,width: "auto"
			}
			,closeBoxURL: ""
			,infoBoxClearance: new google.maps.Size(222, 100)
			,isHidden: false
			,pane: "floatPane"
			,enableEventPropagation: false
		};

		google.maps.event.addListener(marker, "click", function (e) {
			ib.open(theMap, this);
		});

		// var ib = new InfoBox(myOptions);
		// ib.setContent('<div class="infobox"><div class="infobox-content"><div class="map_contact"><?php echo $contact_us; ?></div><a href="#" class="map_directions wpb_button wpb_btn-transparent"><?php echo $directions; ?></a><span class="map_phone icon-mobile"><?php echo $vh_map_phone; ?></span><span class="map_email icon-mail-alt"><a href="mailto:<?php echo $vh_map_email; ?>" target="_top"><?php echo $vh_map_email; ?></a></span><span class="map_address icon-location-1"><?php echo $vh_map_address; ?></span></div></div>');
		// ib.open(theMap, marker);
	}
})();

</script>
<?php

$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large-image' );

if ( LAYOUT == 'sidebar-no' ) {
	$span_size = 'span12';
} else {
	$span_size = 'span9';
}

?>
<div class="page-<?php echo LAYOUT; ?> page-wrapper <?php if ( !is_front_page() ) { echo 'not_front_page';}?>">
	<div class="clearfix"></div>
	<div class="page_info">
		<?php
		if ( !is_front_page() && !is_home() ) {
			echo vh_breadcrumbs();
		} ?>
		<?php
		if ( !is_front_page() && !is_home() ) { ?>
			<div class="page-title">
				<?php echo  the_title( '<h1>', '</h1>' ); ?>
			</div>
		<?php } ?>
	</div>
	<div class="content vc_row wpb_row vc_row-fluid">
		<?php
		wp_reset_postdata();
		if (LAYOUT == 'sidebar-left') {
		?>
		<div class="vc_col-sm-3 <?php echo LAYOUT; ?>">
			<div class="sidebar-inner">
			<?php
				global $vh_is_in_sidebar;
				$vh_is_in_sidebar = true;
				generated_dynamic_sidebar();
			?>
			</div>
		</div><!--end of sidebars-->
		<?php } ?>
		<div class="<?php echo LAYOUT; ?>-pull <?php echo (LAYOUT != 'sidebar-no') ? 'vc_col-sm-9' : 'vc_col-sm-12'; ?>">
			<div class="main-content">
				<?php
				if ( isset($img[0]) ) { ?>
					<div class="entry-image">
						<img src="<?php echo $img[0]; ?>" class="open_entry_image <?php echo $span_size; ?>" alt="" />
					</div>
				<?php } ?>
				<div class="main-inner">
					<?php
					if (have_posts ()) {
						while (have_posts()) {
							the_post();
							the_content();
						}
					} else {
						echo '
							<h2>Nothing Found</h2>
							<p>Sorry, it appears there is no content in this section.</p>';
					}
					?>
				</div>
			</div>
		</div>
		<?php
		if (LAYOUT == 'sidebar-right') {
		?>
		<div class="vc_col-sm-3 pull-right <?php echo LAYOUT; ?>">
			<div class="sidebar-inner">
			<?php
				global $vh_is_in_sidebar;
				$vh_is_in_sidebar = true;
				generated_dynamic_sidebar();
			?>
			<div class="clearfix"></div>
			</div>
		</div><!--end of span3-->
		<?php } ?>
		<?php $vh_is_in_sidebar = false; ?>
		<div class="clearfix"></div>
	</div><!--end of content-->
	<div class="clearfix"></div>
</div><!--end of page-wrapper-->
<?php get_footer();