<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 * 
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 * @since  2.1
 * @author Modern Tribe Inc.
 *
 */

if ( !defined('ABSPATH') ) { die('-1'); }

$event_id = get_the_ID();
global $post;

?>

<div id="tribe-events-content" class="tribe-events-single vevent hentry">

	<!-- Event featured image, but exclude link -->
	<?php
	// echo tribe_event_featured_image($event_id, 'large-image', false);
	if ( wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) ) ) {
		$image_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large-image' );
		echo '
		<div class="tribe-events-event-main-image">
		<div class="tribe-events-event-image image_wrapper carousel">
			<ul>
				<li><img src="' .  $image_src[0] . '" title="'. get_the_title( $post->ID ) .'" alt=""/></li>';
		$i                 = 2;
		$attachments_count = 1;
		while( $i <= 5 ) {
			$image_w_source = '';
			$attachment_id  = events_kd_mfi_get_featured_image_id( 'featured-image-' . $i, 'tribe_events' );
			$image_w_source = wp_get_attachment_image_src( $attachment_id, 'large-image' );
			if( $attachment_id ) {
				echo '<li><img src="' . $image_w_source[0] . '" class="open_entry_image" alt="" /></li>';
				$attachments_count = ++$attachments_count;
			}
			$i++;
		}
		echo '</ul>
		</div>';
		if ( $attachments_count > 1 ) {
			echo '
			<div class="events-open-left"></div>
			<div class="events-open-right"></div>
			<div class="imageSliderExt dotstyle dotstyle-dotmove"><ul></ul></div>';
		}
		echo '</div>';
	};

	$time_format = get_option( 'time_format', Tribe__Events__Date_Utils::TIMEFORMAT );
	$date_format = get_option('date_format', 'j M');
	$time_range_separator = tribe_get_option('timeRangeSeparator', ' - ');

	$start_time = tribe_get_start_date( null, false, $time_format );
	$end_time = tribe_get_end_date( null, false,  $time_format );
	?>
	<div class="open-event-infobox">
		<div class="event-post-date icon-calendar"><?php echo date_i18n($date_format, strtotime($post->EventStartDate)).'<span>, '. $start_time . $time_range_separator . $end_time . '</span>'; ?></div>
		<div class="clearfix"></div>
	</div>
	<div class="open-event-social">
		<div class="social-facebook">
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.0";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, "script", "facebook-jssdk"));</script>
			<div class="fb-like" data-href="' . get_post()->guid . '" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
		</div>
		<div class="social-twitter">
			<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script>
		</div>
	</div>
	<div class="clearfix"></div>

	<div class="title-and-utility">
		<div class="page_title"><?php the_title(); ?></div>
	</div>
	<div class="entry-bottom-utility">
		<div class="category-link">
			<span class="entry-utility-prep entry-utility-prep-cat-links"></span>
				<?php
				$categories = tribe_get_event_taxonomy( $post->ID );
				$categories = str_replace("<li>","",$categories);
				$categories = str_replace("</li>","",$categories);
				$categories = str_replace("</a>","</a>, ",$categories);
				echo substr($categories, 0, -2);
				?>
		</div>
		<div class="clearfix"></div>
	</div>

	<!-- Notices -->
	<?php tribe_events_the_notices() ?>

	<?php while ( have_posts() ) :  the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<!-- Event content -->
			<?php do_action( 'tribe_events_single_event_before_the_content' ) ?>
			<div class="tribe-events-single-event-description tribe-events-content entry-content description">
				<?php the_content(); ?>
			</div><!-- .tribe-events-single-event-description -->
			<!-- Event meta -->
			<?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>
				<?php
				/**
				 * The tribe_events_single_event_meta() function has been deprecated and has been
				 * left in place only to help customers with existing meta factory customizations
				 * to transition: if you are one of those users, please review the new meta templates
				 * and make the switch!
				 */
				if ( ! apply_filters( 'tribe_events_single_event_meta_legacy_mode', false ) )
					tribe_get_template_part( 'modules/meta' );
				else echo tribe_events_single_event_meta()
				?>
			<?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>

		</div> <!-- #post-x -->
		<?php if( get_post_type() == Tribe__Events__Main::POSTTYPE && tribe_get_option( 'showComments', false ) ) comments_template() ?>
	<?php endwhile; ?>

</div><!-- #tribe-events-content -->
