<?php
/**
 * Single Event Meta (Organizer) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/details.php
 *
 * @package TribeEventsCalendar
 */

$phone = tribe_get_organizer_phone();
$email = tribe_get_organizer_email();
$website = tribe_get_organizer_website_link();
?>

<div class="tribe-events-meta-group tribe-events-meta-group-organizer">
	<div class="event-details-container">
		<?php do_action( 'tribe_events_single_meta_organizer_section_start' ) ?>

		<div class="event-details-item">
			<span class="icon-info"><?php _e( 'Host:', 'vh' ) ?> </span>
			<div class="tribe-events-abbr updated published"> <?php echo tribe_get_organizer(); ?> </div>
		</div>

		<?php if ( ! empty( $phone ) ): ?>
			<div class="event-details-item">
				<span class="icon-info"><?php _e( 'Phone:', 'vh' ) ?> </span>
				<div class="tribe-events-abbr updated published"> <?php echo $phone ?> </div>
			</div>
		<?php endif ?>

		<?php if ( ! empty( $email ) ): ?>
			<div class="event-details-item">
				<span class="icon-info"><?php _e( 'Email:', 'vh' ) ?> </span>
				<div class="tribe-events-abbr updated published"> <?php echo $email ?> </div>
			</div>
		<?php endif ?>

		<?php if ( ! empty( $website ) ): ?>
			<div class="event-details-item">
				<span class="icon-info"><?php _e( 'Website:', 'vh' ) ?> </span>
				<div class="tribe-events-abbr updated published"> <?php echo $website ?> </div>
			</div>
		<?php endif ?>

		<?php do_action( 'tribe_events_single_meta_organizer_section_end' ) ?>
	</div>
</div>