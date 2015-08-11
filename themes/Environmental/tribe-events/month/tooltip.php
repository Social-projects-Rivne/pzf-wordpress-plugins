<?php

/**
 *
 * Please see single-event.php in this directory for detailed instructions on how to use and modify these templates.
 *
 */

?>

<script type="text/html" id="tribe_tmpl_tooltip">

	<div id="tribe-events-tooltip-[[=eventId]]" class="tribe-events-tooltip">
	<div class="tribe-month-info">
		<div class="tribe-month-date icon-calendar">[[=startDateFormated]]</div>
		<div class="tribe-month-time icon-clock-1">
			[[=startTimeFormated]]
			[[ if ( endTimeFormated.length ) { ]]
				<br />
				<span>[[=timeRangeSeparator]]</span>
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
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
</div>
</script>