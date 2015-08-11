<?php
/**
 * Single Event Meta (Details) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/details.php
 *
 * @package TribeEventsCalendar
 */
?>

<div class="tribe-events-meta-group tribe-events-meta-group-details">
	<div class="event-details-container">

		<?php
		do_action( 'tribe_events_single_meta_details_section_start' );

		$time_format = get_option( 'time_format', Tribe__Events__Date_Utils::TIMEFORMAT );
		$time_range_separator = tribe_get_option('timeRangeSeparator', ' - ');

		$start_datetime = tribe_get_start_date();
		$start_date = tribe_get_start_date( null, false );
		$start_time = tribe_get_start_date( null, false, $time_format );
		$start_ts = tribe_get_start_date( null, false, Tribe__Events__Date_Utils::DBDATEFORMAT );

		$end_datetime = tribe_get_end_date();
		$end_date = tribe_get_end_date( null, false );
		$end_time = tribe_get_end_date( null, false,  $time_format );
		$end_ts = tribe_get_end_date( null, false, Tribe__Events__Date_Utils::DBDATEFORMAT );

		// All day (multiday) events
		if ( tribe_event_is_all_day() && tribe_event_is_multiday() ) :
		?>

			<div class="event-details-item">
				<span class="icon-info"><?php _e( 'Start:', 'vh' ) ?> </span>
				<div class="tribe-events-abbr updated published dtstart" title="<?php echo $start_ts; ?>"> <?php echo $start_date; ?> </div>
			</div>

			<div class="event-details-item">
				<span class="icon-info"><?php _e( 'End:', 'vh' ) ?> </span>
				<div class="tribe-events-abbr dtend" title="<?php echo $end_ts; ?>"> <?php echo $end_date; ?>  </div>
			</div>

		<?php
		// All day (single day) events
		elseif ( tribe_event_is_all_day() ):
			?>

			<div class="event-details-item">
				<span class="icon-info"><?php _e( 'Date:', 'vh' ) ?> </span>
				<div class="tribe-events-abbr updated published dtstart" title="<?php echo $start_ts; ?>"> <?php echo $start_date; ?> </div>
			</div>

		<?php
		// Multiday events
		elseif ( tribe_event_is_multiday() ) :
			?>

			<div class="event-details-item">
				<span class="icon-info"><?php _e( 'Start:', 'vh' ) ?> </span>
				<div class="tribe-events-abbr updated published dtstart" title="<?php echo $start_ts; ?>"> <?php echo $start_datetime; ?> </div>
			</div>

			<div class="event-details-item">
				<span class="icon-info"><?php _e( 'End:', 'vh' ) ?> </span>
				<div class="tribe-events-abbr dtend" title="<?php echo $end_ts; ?>"> <?php echo $end_datetime; ?> </div>
			</div>

		<?php
		// Single day events
		else :
			?>

			<div class="event-details-item">
				<span class="icon-info"><?php _e( 'Date:', 'vh' ) ?></span>
				<div class="tribe-events-abbr updated published dtstart" title="<?php echo $start_ts; ?>"> <?php echo $start_date; ?></div>
			</div>

			<div class="event-details-item">
				<span class="icon-info"><?php _e( 'Time:', 'vh' ) ?></span>
				<div class="tribe-events-abbr updated published dtstart" title="<?php echo $end_ts; ?>">
					<?php if ( $start_time == $end_time ) echo $start_time; else echo $start_time . $time_range_separator . $end_time; ?>
				</div>
			</div>

		<?php endif ?>

		<?php
		$cost = tribe_get_formatted_cost();
		if ( ! empty( $cost ) ):
		?>
			<div class="event-details-item">
				<span class="icon-info"><?php _e( 'Cost:', 'vh' ) ?> </span>
				<div class="tribe-events-abbr tribe-events-event-cost"> <?php esc_html_e( tribe_get_formatted_cost() ) ?> </div>
			</div>
		<?php endif ?>

		<?php
		echo tribe_get_event_categories( get_the_id(),array(
			'before' => '',
			'sep' => ', ',
			'after' => '',
			'label' => null, // An appropriate plural/singular label will be provided
			'label_before' => '<div class="event-details-item"><span class="icon-info">',
			'label_after' => '</span>',
			'wrap_before' => '<div class="tribe-events-abbr tribe-events-event-categories">',
			'wrap_after' => '</div></div>'
		) );
		?>

		<?php 
		$list = get_the_term_list( get_the_ID(), 'post_tag', '<div class="event-details-item"><span class="icon-info">'.__('Event tags:', 'vh').'</span><div class="tribe-events-abbr tribe-event-tags">', ', ', '</div></div>' );
		$label = $separator = '';
		$echo = true;
		$list = apply_filters( 'tribe_meta_event_tags', $list, $label, $separator, $echo ); echo $list; ?>

		<?php
		$website = tribe_get_event_website_link();
		if ( ! empty( $website ) ):
		?>
			<div class="event-details-item">
				<span class="icon-info"><?php _e( 'Website:', 'vh' ) ?> </span>
				<div class="tribe-events-abbr tribe-events-event-url"> <?php echo $website ?> </div>
			</div>
		<?php endif ?>

		<?php do_action( 'tribe_events_single_meta_details_section_end' ) ?>
	</div>
</div>