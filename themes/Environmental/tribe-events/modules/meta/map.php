<?php
/**
 * Single Event Meta (Map) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/details.php
 *
 * @package TribeEventsCalendar
 */

$map = apply_filters( 'tribe_event_meta_venue_map', tribe_get_embedded_map() );
if ( empty( $map ) ) return;
?>

<div class="tribe-events-venue-map">
	<div class="event-map-info">
		<span class="event-map-title"><?php _e('Venue', 'vh'); ?></span>
		<div class="clearfix"></div>
		<span class="event-map-address icon-location-1"><?php echo tribe_get_full_address(); ?></span>
		<div class="clearfix"></div>
		<?php
			$gmap_link = tribe_get_map_link_html();
			$gmap_link = apply_filters( 'tribe_event_meta_venue_address_gmap', $gmap_link );
			$gmap_link = str_replace("+ Google Map",__('Get directions', 'vh'),$gmap_link);
			$gmap_link = str_replace("class=\"tribe-events-gmap\"","class=\"tribe-events-gmap wpb_button wpb_btn-transparent\"",$gmap_link);
			echo $gmap_link;			
		?>
	</div>
	<?php
	do_action( 'tribe_events_single_meta_map_section_start' );
	echo $map;
	do_action( 'tribe_events_single_meta_map_section_end' );
	?>
</div>
<div class="clearfix"></div>