<?php 
/**
 * Day View Single Event
 * This file contains one event in the day view
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/day/single-event.php
 *
 * @package TribeEventsCalendar
 * @since  3.0
 * @author Modern Tribe Inc.
 *
 */

if ( !defined('ABSPATH') ) { die('-1'); } ?>

<?php 

$venue_details = array();

if ($venue_name = tribe_get_meta( 'tribe_event_venue_name' ) ) {
	$venue_details[] = $venue_name;	
}

if ($venue_address = tribe_get_meta( 'tribe_event_venue_address' ) ) {
	$venue_details[] = $venue_address;	
}
// Venue microformats
$has_venue = ( $venue_details ) ? ' vcard': '';
$has_venue_address = ( $venue_address ) ? ' location': '';
?>

<!-- Schedule & Recurrence Details -->
<div class="updated published time-details">
	<?php
	global $post;

	$time_format = get_option( 'time_format', Tribe__Events__Date_Utils::TIMEFORMAT );
	$time_range_separator = tribe_get_option('timeRangeSeparator', ' - ');

	$start_time = tribe_get_start_date( $post, false, $time_format );
	$end_time = tribe_get_end_date( $post, false,  $time_format );

	echo $start_time . ' '.$time_range_separator.' ' . $end_time;
	?>
</div>

<!-- Event Title -->
<?php do_action( 'tribe_events_before_the_event_title' ) ?>
<h2 class="tribe-events-list-event-title summary">
	<a class="url" href="<?php echo tribe_get_event_link() ?>" title="<?php the_title() ?>" rel="bookmark">
		<?php the_title() ?>
	</a>
</h2>
<?php do_action( 'tribe_events_after_the_event_title' ) ?>

<!-- Event Image -->
<?php echo tribe_event_featured_image( null, 'medium' ) ?>

<!-- Event Content -->
<?php do_action( 'tribe_events_before_the_content' ) ?>
<div class="tribe-events-list-event-description tribe-events-content description entry-summary">
	<?php 
	if ( strlen(strip_tags($post->post_content)) > 150 ) {
		echo '<p>'.substr(strip_tags($post->post_content), 0, 150) . '..</p>';
	} else {
		echo '<p>'.strip_tags($post->post_content).'</p>';
	} ?>
	<div class="clearfix"></div>
	<a href="<?php echo tribe_get_event_link() ?>" class="tribe-events-read-more wpb_button wpb_btn-transparent wpb_small" rel="bookmark"><?php _e( 'Read more', 'vh' ) ?></a>
</div><!-- .tribe-events-list-event-description -->
<?php do_action( 'tribe_events_after_the_content' ) ?>
