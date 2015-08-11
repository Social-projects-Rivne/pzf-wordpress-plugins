<?php

/**
 *
 * Please see single-event.php in this directory for detailed instructions on how to use and modify these templates.
 *
 */

?>

<script type="text/html" id="tribe_tmpl_month_mobile_day_header">
	<div class="tribe-mobile-day" data-day="[[=date]]">[[ if(date_name.length) { ]]<h3 class="tribe-mobile-day-heading">Events for <span>[[=raw date_name]]</span></h3>[[ } ]]</div>
</script>

<script type="text/html" id="tribe_tmpl_month_mobile">
	<div class="tribe-events-mobile hentry vevent tribe-clearfix tribe-events-mobile-event-[[=eventId]][[ if(categoryClasses.length) { ]] [[= categoryClasses]][[ } ]]">
	<div class="tribe-month-info">
		<div class="tribe-month-date icon-calendar">[[=startDateFormated]]</div>
		<div class="tribe-month-time icon-clock-1">
			[[=startTimeFormated]]
			[[ if ( endTimeFormated.length ) { ]]
				<br />
				<span>-</span>
				[[=endTimeFormated]]
			[[}]]
		</div>
	</div>
	<h4 class="entry-title summary">[[=title]]</h4>
	
	<div class="tribe-events-event-body">
		[[ if(imageTooltipSrc.length) { ]]
		<div class="tribe-events-event-thumb">
			<img src="[[=imageTooltipSrc]]" alt="[[=title]]" />
		</div>
		[[ } ]]
		[[ if(excerpt.length) { ]]
		<p class="entry-summary description">[[=raw excerpt]]</p>
		[[ } ]]
		<div class="tribe-month-readmore"><a href="[[=readMore]]" class="wpb_button wpb_btn-transparent wpb_small">[[=readMoreText]]</a></div>
	</div>
	<div class="clearfix"></div>
</div>
</script>