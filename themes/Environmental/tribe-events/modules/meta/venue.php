<?php
/**
 * Single Event Meta (Venue) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/venue.php
 *
 * @package TribeEventsCalendar
 */

if ( ! tribe_address_exists() ) return;
$phone = tribe_get_phone();
$website = tribe_get_venue_website_link();
?>

<div class="tribe-events-meta-group tribe-events-meta-group-venue">
	<div class="event-details-container">
		<?php do_action( 'tribe_events_single_meta_venue_section_start' ) ?>

		<div class="event-details-item">
			<span class="icon-info"><?php _e( 'Venue:', 'vh' ) ?> </span>
			<div class="tribe-events-abbr updated published"> <?php echo tribe_get_venue(); ?> </div>
		</div>

		<?php
		$gmap_link = tribe_show_google_map_link() ? tribe_get_map_link_html() : '';
		$gmap_link = apply_filters( 'tribe_event_meta_venue_address_gmap', $gmap_link );

		if ( tribe_address_exists() ) { ?>
			<div class="event-details-item">
				<span class="icon-info"><?php _e( 'Address:', 'vh' ) ?> </span>
				<div class="tribe-events-abbr updated published address">
					<?php
					echo tribe_get_full_address();
					// Display if appropriate
					if ( tribe_address_exists() ) echo " " . $gmap_link;
					?>
				</div>
			</div>
		<?php } ?>

		<?php if ( ! empty( $phone ) ): ?>
			<div class="event-details-item">
				<span class="icon-info"><?php _e( 'Phone:', 'vh' ) ?> </span>
				<div class="tribe-events-abbr updated published"><?php echo $phone ?> </div>
			</div>
		<?php endif ?>

		<?php if ( ! empty( $website ) ): ?>
			<div class="event-details-item">
				<span class="icon-info"><?php _e( 'Website:', 'vh' ) ?> </span>
				<div class="tribe-events-abbr updated published"><?php echo $website ?> </div>
			</div>
		<?php endif ?>

		<?php do_action( 'tribe_events_single_meta_venue_section_end' ) ?>
	</div>
</div>