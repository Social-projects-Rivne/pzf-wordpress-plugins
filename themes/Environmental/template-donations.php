<?php
/**
 * Template Name: Donations
 */
get_header();

$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large-image' );

if ( LAYOUT == 'sidebar-no' ) {
	$span_size = 'span12';
} else {
	$span_size = 'span9';
}

?>
<div class="page-<?php echo LAYOUT; ?> page-wrapper <?php if ( !is_front_page() ) { echo 'not_front_page';}?>">
	<div class="clearfix"></div>
	<div class="page_info">
		<?php
		if ( !is_front_page() && !is_home() ) {
			echo vh_breadcrumbs();
		} ?>
		<?php
		if ( !is_front_page() && !is_home() ) { ?>
			<div class="page-title">
				<?php echo  the_title( '<h1>', '</h1>' ); ?>
			</div>
		<?php } ?>
	</div>
	<div class="content vc_row wpb_row vc_row-fluid">
		<?php
		wp_reset_postdata();
		if (LAYOUT == 'sidebar-left') {
		?>
		<div class="vc_col-sm-3 <?php echo LAYOUT; ?>">
			<div class="sidebar-inner">
			<?php
				global $vh_is_in_sidebar;
				$vh_is_in_sidebar = true;
				generated_dynamic_sidebar();
			?>
			</div>
		</div><!--end of sidebars-->
		<?php } ?>
		<div class="<?php echo LAYOUT; ?>-pull <?php echo (LAYOUT != 'sidebar-no') ? 'vc_col-sm-9' : 'vc_col-sm-12'; ?>">
			<div class="main-content">
				<?php
				if ( isset($img[0]) ) { ?>
					<div class="entry-image">
						<img src="<?php echo $img[0]; ?>" class="open_entry_image <?php echo $span_size; ?>" alt="" />
					</div>
				<?php } ?>
				<div class="main-inner">
					<?php

					echo '
					<div class="donation-filters">
						<div class="donation-search">
							<span>' . __('Search:', 'vh') . '</span>
							<div class="clearfix"></div>
							<input type="text" id="donation_search" placeholder="' . __('By keyword', 'vh') . '">
						</div>
						<div class="donation-type">
							<span>' . __('Type:', 'vh') . '</span>
							<div class="clearfix"></div>
							'.get_donation_categories().'
						</div>
						<div class="donation-search-button">
							<a href="#" id="donation-filter-submit" class="icon-search"></a>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="donation-content vc_col-sm-12">';

					if (have_posts ()) {
						while (have_posts()) {
							the_post();
							the_content();
						}
					} else {
						echo '
							<h2>Nothing Found</h2>
							<p>Sorry, it appears there is no content in this section.</p>';
					}

					$donations_total = get_donation_thank_you();
					$funded_donations = $donations_total['total_donations_so_far'] / $donations_total['total_donations_needed'] * 100;

					echo '
					</div>
					<div class="clearfix"></div>
					<div class="donations-thank-you">
						<div class="donations-thank-you-left">
							<div class="donations-top-text">
								<span>'.__('Thank you!', 'vh').'</span>
							</div>
							<div class="clearfix"></div>
							<div class="donations-bottom-text">
								' . __('You\'ve helped us raise a staggering', 'vh') . '<span> ' . get_option('vh_currency_sign') . $donations_total['total_donations_so_far'] . '</span> ' . __('so far!', 'vh') . '
							</div>
						</div>
						<div class="donations-thank-you-right">
							<a href="'.get_permalink().'#donation_list" class="wpb_button wpb_btn-success wpb_small">'.__('Donate/pay in your money', 'vh').'</a>
							<div class="donation-thank-you-social">
								<div class="social-facebook">
									<div id="fb-root"></div>
									<script>(function(d, s, id) {
										var js, fjs = d.getElementsByTagName(s)[0];
										if (d.getElementById(id)) return;
										js = d.createElement(s); js.id = id;
										js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.0";
										fjs.parentNode.insertBefore(js, fjs);
									}(document, "script", "facebook-jssdk"));</script>
									<div class="fb-like" data-href="' . get_permalink() . '" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
								</div>
								<div class="social-twitter">
									<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
									<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="donated_amount"><span style="width:'.round($funded_donations, 1).'%"></span></div>
					</div>';

					echo '<h2 id="donation_list" class="module_title">'.__('Our ongoing projects:', 'vh').'</h2>';
					echo '<div id="donations_list_content"></div>';
					echo '<div class="donations_loading"></div>';
					?>
				</div>
			</div>
		</div>
		<?php
		if (LAYOUT == 'sidebar-right') {
		?>
		<div class="vc_col-sm-3 pull-right <?php echo LAYOUT; ?>">
			<div class="sidebar-inner">
			<?php
				global $vh_is_in_sidebar;
				$vh_is_in_sidebar = true;
				generated_dynamic_sidebar();
			?>
			<div class="clearfix"></div>
			</div>
		</div><!--end of span3-->
		<?php } ?>
		<?php $vh_is_in_sidebar = false; ?>
		<div class="clearfix"></div>
	</div><!--end of content-->
	<div class="clearfix"></div>
</div><!--end of page-wrapper-->
<?php get_footer();