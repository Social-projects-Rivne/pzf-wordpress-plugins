<?php 
/**
 * List View Single Event
 * This file contains one event in the list view
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/single-event.php
 *
 * @package TribeEventsCalendar
 * @since  3.0
 * @author Modern Tribe Inc.
 *
 */

if ( !defined('ABSPATH') ) { die('-1'); } ?>

<?php 
global $post;
// Setup an array of venue details for use later in the template
$venue_details = array();

if ($venue_name = tribe_get_meta( 'tribe_event_venue_name' ) ) {
	$venue_details[] = $venue_name;	
}

if ($venue_address = tribe_get_meta( 'tribe_event_venue_address' ) ) {
	$venue_details[] = $venue_address;	
}
// Venue microformats
$has_venue_address = ( $venue_address ) ? ' location': '';

// Organizer
$organizer = tribe_get_organizer();

?>

<!-- Event Image -->
<?php echo tribe_event_featured_image( null, 'medium' ) ?>

<!-- Event Time -->
<?php
$time_format = get_option( 'time_format', Tribe__Events__Date_Utils::TIMEFORMAT );
$date_format = get_option('date_format', 'j M');
$time_range_separator = tribe_get_option('timeRangeSeparator', ' - ');

$start_time = tribe_get_start_date( null, false, $time_format );
$end_time = tribe_get_end_date( null, false,  $time_format );
?>

<div class="tribe-events-info">
	<div class="tribe-events-date icon-calendar"><?php echo date_i18n($date_format, strtotime($post->EventStartDate)); ?></div>
	<div class="tribe-events-time icon-clock-1"><?php echo $start_time . '<br /> ' . $time_range_separator . ' ' . $end_time; ?></div>
</div>

<!-- Event Title -->
<?php do_action( 'tribe_events_before_the_event_title' ) ?>
<div class="tribe-list-title-container">
	<h2 class="tribe-events-list-event-title entry-title summary">
		<a class="url" href="<?php echo tribe_get_event_link() ?>" title="<?php the_title() ?>" rel="bookmark">
			<?php the_title() ?>
		</a>
	</h2>
</div>
<?php do_action( 'tribe_events_after_the_event_title' ) ?>

<!-- Event Content -->
<?php do_action( 'tribe_events_before_the_content' ) ?>
<div class="tribe-events-list-event-description tribe-events-content description entry-summary">
	<?php 
	if ( strlen(strip_tags($post->post_content)) > 150 ) {
		echo '<p>'.substr(strip_tags($post->post_content), 0, 150) . '..</p>';
	} else {
		echo '<p>'.strip_tags($post->post_content).'</p>';
	} ?>
	<a href="<?php echo tribe_get_event_link() ?>" class="tribe-events-read-more wpb_button wpb_btn-transparent wpb_small" rel="bookmark"><?php _e( 'Read more', 'vh' ) ?></a>
</div><!-- .tribe-events-list-event-description -->
<?php do_action( 'tribe_events_after_the_content' ) ?>
