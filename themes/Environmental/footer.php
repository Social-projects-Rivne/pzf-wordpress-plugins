<?php
/**
 * The template for displaying the footer.
 */

global $vh_is_footer;
$vh_is_footer = true;

// // Get theme footer logo
$footer_logo = get_option('vh_site_footer_logo');
if($footer_logo == false) {
	$footer_logo = get_template_directory_uri() . '/images/footer-logo.png';
}

$retina_logo_class = '';
$logo_size_html = '';
$map_class = '';

// // Get theme footer logo dimensions
$website_footer_logo_retina_ready = filter_var(get_option('vh_site_footer_retina'), FILTER_VALIDATE_BOOLEAN);
if ((bool)$website_footer_logo_retina_ready != false) {
	$logo_size = getimagesize($footer_logo);
	$logo_size_html = ' style="width: ' . ($logo_size[0] / 2) . 'px; height: ' . ($logo_size[1] / 2) . 'px;" width="' . ($logo_size[0] / 2) . '" height="' . ($logo_size[1] / 2) . '"';
	$retina_logo_class = 'retina';
}

// Footer copyright
$copyrights = get_option('vh_footer_copyright') ? get_option('vh_footer_copyright') : '&copy; [year], Environmental by <a href="http://cohhe.com">Cohhe</a>';
$copyrights = str_replace( '[year]', date_i18n('Y'), $copyrights);

// Scroll to top option
$scroll_to_top = filter_var(get_option('vh_scroll_to_top'), FILTER_VALIDATE_BOOLEAN);
?>
			</div><!--end of main-->
		</div><!--end of wrapper-->
		<div class="footer-wrapper">
			<div class="footer-container vc_row wpb_row vc_row-fluid <?php if ( !is_front_page() ) { echo 'not_front_page';}?>">
				<?php
				if ( is_page_template( 'template-with-post-slider-and-map.php' ) || is_page_template( 'template-only-with-map.php' ) ) { ?>
					<div class="footer_clouds"><img src="<?php echo get_template_directory_uri();?>/images/footer-clouds.png" alt=""></div>
				<?php } ?>
				<?php if ( (bool)$scroll_to_top != false ) { ?>
				<div class="scroll-to-top-container">
					<div class="scroll-to-top"></div>
				</div>
				<?php } ?>
				<?php
				if ( is_page_template( 'template-with-post-slider-and-map.php' ) || is_page_template( 'template-only-with-map.php' ) ) {
					$map_class = "map-active";
				}
				?>
				<div class="map_container">
					<div id="map" class="<?php echo $map_class; ?>"></div>
					<?php
					$vh_map_lat     = (get_post_meta( $post->ID, 'vh_map_lat', true )) ? get_post_meta( $post->ID, 'vh_map_lat', true ) : '';
					$vh_map_long    = (get_post_meta( $post->ID, 'vh_map_long', true )) ? get_post_meta( $post->ID, 'vh_map_long', true ) : '';
					$vh_map_address = (get_post_meta( $post->ID, 'vh_map_address', true )) ? get_post_meta( $post->ID, 'vh_map_address', true ) : '';
					$vh_map_phone   = (get_post_meta( $post->ID, 'vh_map_phone', true )) ? get_post_meta( $post->ID, 'vh_map_phone', true ) : '';
					$vh_map_email   = (get_post_meta( $post->ID, 'vh_map_email', true )) ? get_post_meta( $post->ID, 'vh_map_email', true ) : '';
					$contact_us     = __('Contact us', 'vh' );
					$directions     = __('Get directions', 'vh' );
					?>
					<?php if ( $vh_map_lat != '' || $vh_map_long != '' ) { ?>
					<div class="infobox">
						<div class="infobox-content">
							<div class="map_contact"><?php echo $contact_us; ?></div>
							<div>
								<span class="map_phone icon-mobile"><?php echo $vh_map_phone; ?></span>
								<span class="map_email icon-mail-alt"><a href="mailto:<?php echo $vh_map_email; ?>" target="_top"><?php echo $vh_map_email; ?></a></span>
								<span class="map_address icon-location-1"><?php echo $vh_map_address; ?></span>
								<div class="clearfix"></div>
								<a href="https://www.google.com/maps/dir//<?php echo $vh_map_lat; ?>,<?php echo $vh_map_long; ?>/@<?php echo $vh_map_lat; ?>,<?php echo $vh_map_long; ?>,15z" class="map_directions wpb_button wpb_btn-transparent"><?php echo $directions; ?></a>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<?php } ?>
				</div>
				
				<div class="footer-inner vc_col-sm-12">
					<div class="footer_bg_grass"></div>
					<div class="footer_bg_tree"></div>
					<div class="footer_info">
						<a href="<?php echo home_url(); ?>"><img src="<?php echo $footer_logo; ?>"<?php echo $logo_size_html ; ?> class="footer-logo <?php echo $retina_logo_class; ?>" alt="<?php bloginfo('name'); ?>" /></a>
						<div class="copyright"><?php echo $copyrights; ?></div>
					</div>
				</div>
			</div>
		</div>
		<?php
			$fixed_menu    = filter_var(get_option('vh_fixed_menu'), FILTER_VALIDATE_BOOLEAN);
			$tracking_code = get_option( 'vh_tracking_code' ) ? get_option( 'vh_tracking_code' ) : '';
			if ( !empty( $tracking_code ) ) { ?>
				<!-- Tracking Code -->
				<?php
				echo '
					' . $tracking_code;
			}
		?>
		</div><!-- end of vh_wrapper -->
		<?php wp_footer(); ?>
	</body>
</html>