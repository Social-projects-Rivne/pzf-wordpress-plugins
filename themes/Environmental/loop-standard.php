<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage Jobera
 */

global $vh_from_home_page, $post;

$tc = 0;
$excerpt = get_the_excerpt();
$img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large-image');

if ( $vh_from_home_page == TRUE ) {
	$span_class_index = 'vc_col-sm-6';
} else {
	if(LAYOUT != 'sidebar-no') {
		$span_class_index = 'vc_col-sm-10';
	} else {
		$span_class_index = 'vc_col-sm-12';
	}
}
$tc = wp_count_comments($post->ID);
$date_format = get_option('date_format', 'j M');
$post_timestamp = strtotime($post->post_date);
?>
	<li class="isotope-item <?php echo $span_class_index; ?>">
		<div class="post-grid-item-wrapper">
			<div  <?php post_class(); ?>>
				<?php if ( empty($img[0]) ) { ?>
					<div class="standard-infobox">
						<div class="standard-date icon-calendar"><?php echo date_i18n($date_format, $post_timestamp); ?></div>
						<div class="standard-comments icon-comment-1"><?php echo $tc->approved; ?></div>
						<div class="clearfix"></div>
					</div>
					<div class="post-title nothumbnail">
						<?php
						if ( get_post_type() == 'post' && !empty($post->ID) && !is_search() && !is_front_page() && !is_archive() ) {
							echo '<div class="post_info_text">';
							echo '<span class="comments icon-comment">' . $tc->approved . '</span>';

								if ( function_exists('get_post_ul_meta') ) {
									echo '
									<span class="blog_likes icon-heart">' . get_post_ul_meta($post->ID, "like") . '</span>';
								}
								echo '
							</div>';
						}
						?>
						<a class="link_title" href="<?php echo get_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'vh'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a>
						<?php
						if ( 'post' == get_post_type() ) {

							/* translators: used between list items, there is a space after the comma */
							$categories_list = get_the_category_list( __( ', ', 'vh' ) );
							if ( $categories_list ) {
								echo '
								<div class="category-link">
								' . sprintf( __( '<span class="%1$s"></span> %2$s', 'vh' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
								$show_sep = TRUE;
								echo '</div>';
							}
						}
						?>
					</div>
				<?php } else { ?>
					<div class="post-thumb">
						<?php
							if ( get_post_type() == 'post' && !empty($post->ID) && !is_search() && !is_front_page() && !is_archive() ) {
								echo '<div class="post_info_img">';
								echo '<span class="comments icon-comment">' . $tc->approved . '</span>';

								if ( function_exists('get_post_ul_meta') ) {
									echo '
									<span class="blog_likes icon-heart">' . get_post_ul_meta($post->ID, "like") . '</span>';
								}
								echo '
								</div>';
							}
						?>
						<div class="post-thumb-img-wrapper shadows">
							<div class="bottom_line"></div>
							<a class="link_image" href="<?php echo get_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'vh'), the_title_attribute('echo=0')); ?>">
								<img src="<?php echo $img[0]; ?>" alt="">
							</a>
						</div>
					</div>
					<div class="standard-infobox">
						<div class="standard-date icon-calendar"><?php echo date_i18n($date_format, $post_timestamp); ?></div>
						<div class="standard-comments icon-comment-1"><?php echo $tc->approved; ?></div>
						<div class="clearfix"></div>
					</div>
					<div class="post-title">
						<a class="link_title" href="<?php echo get_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'vh'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a>
						<?php
						if ( 'post' == get_post_type() ) {

							/* translators: used between list items, there is a space after the comma */
							$categories_list = get_the_category_list( __( ', ', 'vh' ) );
							if ( $categories_list ) {
								echo '
								<div class="category-link">
								' . sprintf( __( '<span class="%1$s"></span> %2$s', 'vh' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
								$show_sep = TRUE;
								echo '</div>';
							}
						}
						?>
					</div>
				<?php } ?>
				<div class="entry-content <?php echo get_post_type(); ?>">
					<?php
						if ( is_search() ) {
							if( empty($excerpt) ) {
								echo '<p>' . __( 'No excerpt for this posting.', 'vh' ) . '</p>';
							} else {
								the_excerpt();
							}
						} else {
							the_excerpt(__('Read more', 'vh'));
						}
					?>
				</div>
				<div class="clearfix"></div>
				<?php if ( !is_search() && !is_front_page() && !is_archive() ) { ?>
					<div class="blog_author"><div class="blog_time icon-clock"><?php echo get_the_date('j.n.Y'); ?></div>
					<span class="author icon-user"><a href="<?php echo get_author_posts_url( get_the_author_meta('ID') );?>"><?php echo __('by', 'vh') . ' ' . get_the_author_link(); ?></a></span></div>
				<?php } ?>
			</div>
		</div>
	</li>