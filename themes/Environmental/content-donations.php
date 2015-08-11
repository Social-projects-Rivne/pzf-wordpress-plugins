<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package WordPress
 * @subpackage Environmental
 */

global $vh_blog_image_layout;

$show_sep       = FALSE;
$style          = '';
$clear          = '';
$excerpt        = get_the_excerpt();
$top_left       = "";
$small_image    = FALSE;
$post_date_d    = get_the_date( 'd. M' );
$post_date_m    = get_the_date( 'Y' );
$is_author_desc = '';
$post_id = $post->ID;

$show_date = isset( $show_date ) ? $show_date : NULL;

if ( get_the_author_meta( 'description' ) ) { 
	$is_author_desc = ' is_author_desc';
}

// Determine blog image size
if ( LAYOUT == 'sidebar-no' ) {
	$clear     = ' style="float: none;"';
	$img_style = ' style="margin-left: 0;"';
} else {
	$small_image = TRUE;
	$img_style   = ' style="margin-left: 0;"';
}
$img           = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large-image' );
$entry_utility = '';

if ( get_post_meta( get_the_id(), '_donations_so_far', true ) == null || get_post_meta( get_the_id(), '_donations_so_far', true ) == '0' ) {
	$funded_donations = '0';
	$donations_to_go =  get_post_meta( get_the_id(), '_how_many_donations_are_needed', true ) - get_post_meta( get_the_id(), '_donations_so_far', true );
	if ( $donations_to_go < 0 ) {
		$donations_to_go = '0';
	}
} else {
	$funded_donations = get_post_meta( get_the_id(), '_donations_so_far', true ) / get_post_meta( get_the_id(), '_how_many_donations_are_needed', true ) * 100;
	if ( $funded_donations > 100 ) {
		$funded_donations = '100';
	}
	$donations_to_go =  get_post_meta( get_the_id(), '_how_many_donations_are_needed', true ) - get_post_meta( get_the_id(), '_donations_so_far', true );
	if ( $donations_to_go < 0 ) {
		$donations_to_go = '0';
	}
}

get_header();

// Get paypal parameters
if (TESTENVIRONMENT === TRUE) {
	$paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
} else {
	$paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
}

// Which payment gateway to use?
$payment_gateway      = get_option('vh_payment_gateway') ? get_option('vh_payment_gateway') : 'paypal';

// PayPal payment gateway configuration
$paypal_email         = get_option('vh_paypal_email');
$paypal_thankyou      = get_option('vh_paypal_thankyou');
$paypallogo           = get_option('vh_paypallogo') ? get_option('vh_paypallogo') : '';
$paypal_currency_code = get_option('vh_paypal_currency_code');
$currency_sign        = get_option('vh_currency_sign') ? get_option('vh_currency_sign') : '$';

// Authorize.net payment gateway configuration
$auhorize_api_login_id     = get_option('vh_api_login_id') ? get_option('vh_api_login_id') : '';
$authorize_transaction_key = get_option('vh_transaction_key') ? get_option('vh_transaction_key') : '';

if ( $payment_gateway == 'paypal') {
	$error_msg = 'PayPal';
}

if ( !empty($_GET['amount']) ) {
	$amount = $_GET['amount'];
} else {
	$amount = '';
}

		if ( $payment_gateway == 'authorizenet' && !empty($_POST['authorizenet_process']) ) { ?>
			<div><?php _e( 'Processing...', 'vh' ); ?></div>
			<?php require_once(VH_HOME . '/authorizenet_submit.php');
		} elseif (((empty($paypal_email) || empty($paypal_thankyou)) && $payment_gateway == 'paypal') || ((empty($authorize_transaction_key) || empty($auhorize_api_login_id) || empty($paypal_thankyou)) && $payment_gateway == 'authorizenet')) {
			$entry_utility .= '<div class="page_title missing_conf">';
			$entry_utility .= '<div>' . __( 'Missing configuration!', 'vh' ) . '</div>';
			$entry_utility .= '<div>' . __( 'Please configure', 'vh');
			$entry_utility .= ' ' . $error_msg . ' ';
			$entry_utility .= __('settings on \'Theme Options\' page', 'vh') . '</div>';
			$entry_utility .= '</div>';
		} else {
			$percents                      = 0;
			$how_many_donations_are_needed = (get_post_meta( get_the_id(), '_how_many_donations_are_needed', true )) ? get_post_meta( get_the_id(), '_how_many_donations_are_needed', true ) : 0;
			$current_fundrainers           = (get_post_meta( get_the_id(), '_fundraisers', true )) ? get_post_meta( get_the_id(), '_fundraisers', true ) : 0;
			$current_donations             = (get_post_meta( get_the_id(), '_donations_so_far', true )) ? get_post_meta( get_the_id(), '_donations_so_far', true ) : 0;
			$custom_paypal_email           = (get_post_meta( get_the_id(), '_custom_paypal_email', true )) ? get_post_meta( get_the_id(), '_custom_paypal_email', true ) : '';

			// If not empty cause specific paypal email then use it instead
			if ( !empty($custom_paypal_email) ) {
				$paypal_email = $custom_paypal_email;
			}
			
			if ( $how_many_donations_are_needed > 0 ) {
				$percents = number_format( ( $current_donations / $how_many_donations_are_needed ) * 100, 0 );
			}

			// Cause image & video
			$img   = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large-image');
			$video = (get_post_meta( get_the_id(), '_video_code', true )) ? get_post_meta( get_the_id(), '_video_code', true ) : '';
			

			$entry_utility .= '
			<div id="donations-open-container" class="page_title">
				<div class="donations-open-left">
					<div class="donation-open-goal">
						' . __('Donation Goal For This Project is', 'vh') . ' <span>' . get_option('vh_currency_sign') . get_post_meta( get_the_id(), '_how_many_donations_are_needed', true ) . '</span>
					</div>
					<div class="clearfix"></div>
					<div class="donation-open-info">
						<span>' . round($funded_donations) . '%</span> ' . __('Donated', 'vh') . ' / <span>' . get_option('vh_currency_sign') . $donations_to_go . '</span> ' . __('To go', 'vh') . '
					</div>
				</div>
				<div class="donations-open-button">';
				if ( $payment_gateway == 'paypal' ) {
					$entry_utility .= '<form action="' . $paypal_url . '" method="post">
						<input type="hidden" name="cmd" value="_donations">
						<input type="hidden" name="business" value="' . $paypal_email . '">
						<input type="hidden" name="item_name" value="Donate to the ' . get_the_title() . ' cause">
						<input type="hidden" name="no_shipping" value="0">
						<input type="hidden" name="no_note" value="1">
						<input type="hidden" name="currency_code" value="' . $paypal_currency_code . '">
						<input type="hidden" name="return" value="' . get_permalink( $paypal_thankyou ) . '">
						<input type="hidden" name="notify_url" value="' . home_url() . '">
						<input type="hidden" name="image_url" value="' . $paypallogo . '">
						<input type="hidden" name="custom" value="' . get_the_ID() . '">
						<div class="input-append">
							<input type="submit" class="btn btn-primary wpb_button wpb_btn-success wpb_btn-large" value="' . __('Donate/pay in your money', 'vh') . '">
						</div>
					</form>';
				}
				$entry_utility .= '</div>
				<div class="clearfix"></div>
				<div class="donated_amount"><span style="width:'.round($funded_donations, 1).'%"></span></div>
				</div>';
			}
$entry_utility_bottom = '<div class="entry-bottom-utility">';
	if ( 'post' == get_post_type() ) {

		// $entry_utility_bottom .= '<div class="blog_like_dislike"><span class="post_dislikes icon-heart-broken"></span>';
		// $entry_utility_bottom .= '<span class="post_likes icon-heart"></span></div>';


		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'vh' ) );
		if ( $categories_list ) {
			$entry_utility_bottom .= '
			<div class="category-link">
			' . sprintf( __( '<span class="%1$s"></span> %2$s', 'vh' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
			$show_sep = TRUE;
			$entry_utility_bottom .= '
			</div>';
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'vh' ) );
		if ( $tags_list ) {
			$style = '';
			$entry_utility_bottom .= '
			<div class="tag-link"' . $style . '>
			<i class="entypo_icon icon-tag-3"></i>
			' . sprintf( __( '<span class="%1$s"></span> %2$s', 'vh' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
			$show_sep = true;
			$entry_utility_bottom .= '
			</div>';
		}

	}
$entry_utility_bottom .= '</div>';
?>
<div class="entry no_left_margin first-entry <?php echo $is_author_desc; ?> <?php if ( !isset($img[0]) ) { echo ' no-image'; } ?><?php echo (LAYOUT != 'sidebar-no') ? ' vc_col-sm-12' : ' vc_col-sm-12'; ?>">
	<div class="entry-image vh_animate_when_almost_visible with_full_image <?php echo $vh_blog_image_layout . $is_author_desc; ?>"<?php echo $clear; ?>>
		<div class="main_image_wrapper">
			<?php
			$i                 = 2;
			$attachments_count = 1;
			

			if ( isset($img[0]) ) { ?>
				<div class="image_wrapper carousel">
					<ul class="donation-open-carousel">
						<li><img src="<?php echo $img[0]; ?> "<?php echo $img_style; ?> class="open_entry_image" alt="" /></li>
					<?php 
					while( $i <= 5 ) {
						$attachment_id = kd_mfi_get_featured_image_id( 'featured-image-' . $i, 'donations' );
						if( $attachment_id ) {
							$featured_img = wp_get_attachment_image_src( $attachment_id, 'large-image' );
							echo '<li><img src="'.$featured_img[0].'" class="open_entry_image" alt="" /></li>';
							$attachments_count = ++$attachments_count;
						}
						$i++;
					} ?>
					</ul>
					<?php if ( $attachments_count > 1 ) { ?>
						<div class="donation-open-left"></div>
						<div class="donation-open-right"></div>
					<?php } ?>
				</div>
				<?php if ( $attachments_count > 1 ) { ?>
					<div class="imageSliderExt dotstyle dotstyle-dotmove"><ul></ul></div>
				<?php } ?>
			<?php } ?>
		</div>
		<div class="entry-content">
				<?php
					$time_format = get_option( 'time_format', 'G:H' );
					$date_format = get_option('date_format', 'j M');
					$post_timestamp = strtotime($post->post_date);
					echo '<div class="open-blog-infobox">';
					echo '<div class="blog-post-date icon-calendar">'.date_i18n($date_format, strtotime($post->post_date)).', <span>'.date_i18n($time_format, strtotime($post->post_date)).'</span></div>';
					echo '<div class="clearfix"></div>';
					echo '</div>';
					echo '<div class="open-donation-social">';
					echo '<div class="social-facebook">
							<div id="fb-root"></div>
							<script>(function(d, s, id) {
								var js, fjs = d.getElementsByTagName(s)[0];
								if (d.getElementById(id)) return;
								js = d.createElement(s); js.id = id;
								js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.0";
								fjs.parentNode.insertBefore(js, fjs);
							}(document, "script", "facebook-jssdk"));</script>
							<div class="fb-like" data-href="' . get_permalink( get_the_ID() ) . '" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
						</div>
						<div class="social-twitter">
							<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script>
						</div>
					</div>';
					echo '<div class="title-and-utility';
					if ( $show_date == 'false' ) { echo ' no_left_margin'; };
					if ( !isset($img[0]) ) { echo ' no_image'; };
					echo '">';
					echo $entry_utility;
					echo '<div class="clearfix"></div>';
					echo '</div>';
					echo $entry_utility_bottom;
				?>
			<div class="clearfix"></div>
			<?php
			if ( is_search() ) {
				the_excerpt();
				if( empty($excerpt) )
					echo 'No excerpt for this posting.';

			} else {
				echo '<div class="blog-open-content">';
					the_content(__('Read more', 'vh'));
					wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'vh' ) . '</span>', 'after' => '</div>', 'link_before' => '<span class="page-link-wrap">', 'link_after' => '</span>', ) );
				echo '</div>';
			}
			?>
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
	<?php
	// If a user has filled out their description, show a bio on their entries
	if ( get_post_type( $post ) == 'post' && get_the_author_meta( 'description' ) ) { ?>
	<div id="author-info">
		<span class="author-text"><?php _e('Author:', 'vh'); ?></span>
		<span class="author"><?php echo get_the_author(); ?></span>
		<div class="author-infobox">
			<div id="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'vh_author_bio_avatar_size', 70 ) ); ?>
			</div>
			<div id="author-description">
				<p><?php the_author_meta( 'description' ); ?></p>
			</div><!-- end of author-description -->
		</div>
		<div id="author-link">
			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
				<?php printf( __( 'View all posts', 'vh' ), get_the_author() ); ?>
			</a>
		</div><!-- end of author-link	-->
		<div class="clearfix"></div>
	</div><!-- end of entry-author-info -->
	<?php } ?>
</div>