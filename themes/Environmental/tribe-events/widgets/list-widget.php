<?php
/**
 * Events List Widget Template
 * This is the template for the output of the events list widget. 
 * All the items are turned on and off through the widget admin.
 * There is currently no default styling, which is needed.
 *
 * This view contains the filters required to create an effective events list widget view.
 *
 * You can recreate an ENTIRELY new events list widget view by doing a template override,
 * and placing a list-widget.php file in a tribe-events/widgets/ directory 
 * within your theme directory, which will override the /views/widgets/list-widget.php.
 *
 * You can use any or all filters included in this file or create your own filters in 
 * your functions.php. In order to modify or extend a single filter, please see our
 * readme on templates hooks and filters (TO-DO)
 *
 * @return string
 *
 * @package TribeEventsCalendar
 *
 */
if ( !defined('ABSPATH') ) { die('-1'); } 
$posts = tribe_get_list_widget_events();
//Check if any posts were found
if ( $posts ) {
?>

<ol class="hfeed vcalendar">
<?php
	foreach( $posts as $post ) :
		setup_postdata( $post );

		$time_format = get_option( 'time_format', Tribe__Events__Date_Utils::TIMEFORMAT );
		$date_format = get_option('date_format', 'j M');
		$time_range_separator = tribe_get_option('timeRangeSeparator', ' - ');

		$start_time = tribe_get_start_date( $post, false, $time_format );
		$end_time = tribe_get_end_date( $post, false,  $time_format );
?>
	<li class="tribe-events-list-widget-events <?php tribe_events_event_classes() ?>">

		<div class="event-info-box">
			<div class="event-date">
				<span class="icon-calendar"><?php echo date_i18n($date_format, strtotime($post->EventStartDate)); ?></span>
			</div>
			<div class="event-time">
				<span class="clock icon-clock-1"></span>
				<span class="event-start"><?php echo __('In: ', 'vh') . $start_time; ?></span>
				<span class="event-end"><?php echo __('Out: ', 'vh') . $end_time; ?></span>
			</div>
		</div>
	
		<?php do_action( 'tribe_events_list_widget_before_the_event_title' ); ?>
		<!-- Event Title -->
		<h4 class="entry-title summary">
			<a href="<?php echo tribe_get_event_link(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h4>
		
		<?php do_action( 'tribe_events_list_widget_after_the_event_title' ); ?>	
		<!-- Event Time -->
		
		<?php do_action( 'tribe_events_list_widget_before_the_meta' ) ?>

		<div class="tribe-events-list-widget-text">
			<?php 
				if ( strlen(strip_tags(get_the_content())) > 110 ) {
					$event_content = substr(strip_tags(get_the_content()), 0, 110) . '..';
				} else {
					$event_content = strip_tags(get_the_content());
				}
				echo $event_content;
			?>
		</div>
		
		<?php do_action( 'tribe_events_list_widget_after_the_meta' ) ?>
		
		
	</li>
<?php
	endforeach;
?>
</ol><!-- .hfeed -->

<?php
//No Events were Found
} else {
?>
	<p><?php _e( 'There are no upcoming events at this time.', 'vh' ); ?></p>
<?php
}
?>
