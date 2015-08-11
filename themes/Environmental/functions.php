<?php
/**
 * Environmental functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 */

// Define file directories
define('VH_HOME', get_template_directory());
define('VH_FUNCTIONS', get_template_directory() . '/functions');
define('VH_GLOBAL', get_template_directory() . '/functions/global');
define('VH_WIDGETS', get_template_directory() . '/functions/widgets');
define('VH_CUSTOM_PLUGINS', get_template_directory() . '/functions/plugins');
define('VH_ADMIN', get_template_directory() . '/functions/admin');
define('VH_ADMIN_IMAGES', get_template_directory_uri() . '/functions/admin/images');
define('VH_METABOXES', get_template_directory() . '/functions/admin/metaboxes');
define('VH_SIDEBARS', get_template_directory() . '/functions/admin/sidebars');

// Define theme URI
define('VH_URI', get_template_directory_uri() .'/');
define('VH_GLOBAL_URI', VH_URI . 'functions/global');

define('THEMENAME', 'Environmental');
define('SHORTNAME', 'VH');
define('VH_HOME_TITLE', 'Front page');
define('VH_DEVELOPER_NAME_DISPLAY', 'Cohhe themes');
define('VH_DEVELOPER_URL', 'http://cohhe.com');

define('TESTENVIRONMENT', FALSE);

add_action('after_setup_theme', 'vh_setup');
add_filter('widget_text', 'do_shortcode');

// Set max content width
if (!isset($content_width)) {
	$content_width = 900;
}

if (!function_exists('vh_setup')) {

	function vh_setup() {

		// Load Admin elements
		require_once(VH_ADMIN . '/theme-options.php');
		require_once(VH_ADMIN . '/admin-interface.php');
		require_once(VH_ADMIN . '/menu-custom-field.php');
		require_once(VH_FUNCTIONS . '/get-the-image.php');
		require_once(VH_METABOXES . '/layouts.php');
		require_once(VH_METABOXES . '/contact_map.php');
		require_once(VH_METABOXES . '/donations.php');
		require_once(VH_SIDEBARS . '/multiple_sidebars.php');
		require_once(VH_FUNCTIONS . '/installer/importer/widgets-importer.php');
		require_once(VH_FUNCTIONS . '/installer/functions-themeinstall.php');

		// Widgets list
		$widgets = array (
			VH_WIDGETS . '/contactform.php',
			VH_WIDGETS . '/googlemap.php',
			VH_WIDGETS . '/social_links.php',
			VH_WIDGETS . '/advertisement.php',
			VH_WIDGETS . '/recent-posts-plus.php',
			VH_WIDGETS . '/fast-flickr-widget.php',
		);

		// Load Widgets
		load_files($widgets);

		// Load global elements
		require_once(VH_GLOBAL . '/wp_pagenavi/wp-pagenavi.php');

		// if (file_exists(VH_CUSTOM_PLUGINS . '/landing-pages/landing-pages.php')) {
		// 	require_once(VH_CUSTOM_PLUGINS . '/landing-pages/landing-pages.php');
		// }

		// TGM plugins activation
		require_once(VH_FUNCTIONS . '/tgm-activation/class-tgm-plugin-activation.php');

		// Extend Visual Composer
		if (defined('WPB_VC_VERSION')) {
			require_once(VH_FUNCTIONS . '/visual_composer_extended.php');
		}

		// Shortcodes list
		$shortcodes = array (
			//VH_SHORTCODES . '/test.php'
		);

		// Load shortcodes
		load_files($shortcodes);

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// Add default posts and comments RSS feed links to <head>.
		add_theme_support('automatic-feed-links');

		// If theme is activated them send to options page
		// if (is_admin() && isset($_GET['activated'])) {
		// 	wp_redirect(admin_url('admin.php?page=themeoptions'));
		// }

		register_taxonomy( 'vh_categories',
			array (
				0 => 'animals_places',
			),
			array( 
				'hierarchical' => true, 
				'label' => 'Categories',
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array('slug' => ''),
				'singular_label' => 'Category'
			) 
		);

		register_taxonomy( 'vh_donations',
			array (
				0 => 'donations',
			),
			array( 
				'hierarchical' => true, 
				'label' => 'Categories',
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array('slug' => ''),
				'singular_label' => 'Category'
			) 
		);
	}
}

add_action( 'init', 'vh_create_post_type' );
function vh_create_post_type() {
	register_post_type( 'animals_places',
		array(
		'labels' => array(
			'name' => __( 'Animals & Places', "vh" ),
			'singular_name' => __( 'Animal & Place', "vh" )
		),
		'taxonomies' => array('animal_categories'),
		'rewrite' => array('slug'=>'animals_places','with_front'=>false),
		'public' => true,
		'has_archive' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail',
			'comments',
			'post-templates'
			)
		)
	);

	register_post_type( 'donations',
		array(
		'labels' => array(
			'name' => __( 'Donations', "vh" ),
			'singular_name' => __( 'Donation', "vh" )
		),
		'taxonomies' => array('vh_donations'),
		'rewrite' => array('slug'=>'donation','with_front'=>false),
		'public' => true,
		'has_archive' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail',
			'comments',
			'post-templates'
			)
		)
	);
}

add_action( 'load-post.php', 'vh_metabox_setup' );
add_action( 'load-post-new.php', 'vh_metabox_setup' );

function localization() {
	$lang = get_template_directory() . '/languages';
	load_theme_textdomain('vh', $lang);
}
add_action('after_setup_theme', 'localization');

function vh_register_widgets () {
	register_sidebar( array(
		'name'          => __( 'Normal', 'vh' ),
		'id'            => 'sidebar-1',
		'class'         => 'normal',
		'before_widget' => '<div class="widget">',
		'after_widget'  => '<div class="clearfix"></div></div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>'
	) );

	register_sidebar( array(
		'name'          => __( 'Main', 'vh' ),
		'id'            => 'sidebar-2',
		'class'         => 'recent-news',
		'before_widget' => '<div class="widget">',
		'after_widget'  => '<div class="clearfix"></div></div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>'
	) );
}
add_action( 'widgets_init', 'vh_register_widgets' );

function vh_metabox_setup() {

	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'vh_add_metabox' );

	/* Save post meta on the 'save_post' hook. */
	add_action( 'save_post', 'animal_location_metabox', 10, 2 );
}

function vh_add_metabox() {

	add_meta_box(
		'animals_places_metabox',                           // Unique ID
		esc_html__( 'Advanced fields', 'vh' ),              // Title
		'animals_places_metabox_function',                  // Callback function
		'animals_places',                                   // Admin page (or post type)
		'normal',                                           // Context
		'high'                                              // Priority
	);
}

function animals_places_metabox_function( $object, $box ) { ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'animals_places_nonce' ); ?>

	<p>
		<label for="animal_location"><?php _e( "Location", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="animal_location" id="animal_location" value="<?php echo esc_attr( get_post_meta( $object->ID, 'animal_location', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="animal_name"><?php _e( "Scientific Name", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="animal_name" id="animal_name" value="<?php echo esc_attr( get_post_meta( $object->ID, 'animal_name', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="animal_habitat"><?php _e( "Habitat", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="animal_habitat" id="animal_habitat" value="<?php echo esc_attr( get_post_meta( $object->ID, 'animal_habitat', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="animal_height"><?php _e( "Height", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="animal_height" id="animal_height" value="<?php echo esc_attr( get_post_meta( $object->ID, 'animal_height', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="animal_weight"><?php _e( "Weight", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="animal_weight" id="animal_weight" value="<?php echo esc_attr( get_post_meta( $object->ID, 'animal_weight', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="animal_population"><?php _e( "Population", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="animal_population" id="animal_population" value="<?php echo esc_attr( get_post_meta( $object->ID, 'animal_population', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="animal_color"><?php _e( "Animals & Places list color. Accepts color names like yellow, RGB color code like #000000 or our defined colors - env_red, env_orange, env_green. Default: env_green", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="animal_color" id="animal_color" value="<?php echo esc_attr( get_post_meta( $object->ID, 'animal_color', true ) ); ?>" size="30" />
	</p>

<?php }

function animal_location_metabox( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['animals_places_nonce'] ) || !wp_verify_nonce( $_POST['animals_places_nonce'], basename( __FILE__ ) ) )
	return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
	return $post_id;

	$meta_value_array = array('animal_location', 'animal_name', 'animal_habitat', 'animal_height', 'animal_weight', 'animal_population', 'animal_color');

	foreach ($meta_value_array as $value) {
		/* Get the posted data and sanitize it for use as an HTML class. */
		$new_meta_value = ( isset( $_POST[$value] ) ? sanitize_text_field( $_POST[$value] ) : '' );

		/* Get the meta key. */
		$meta_key = $value;

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		/* If a new meta value was added and there was no previous value, add it. */
		if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		/* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

		/* If there is no new meta value but an old value exists, delete it. */
		elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
	}

}

// Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
if(function_exists('vc_set_as_theme')) vc_set_as_theme();

// Add quote post format support
add_theme_support( 'post-formats', array( 'quote' ) );

// Load Widgets
function load_files ($files) {
	foreach ($files as $file) {
		require_once($file);
	}
}

if (function_exists('add_theme_support')) {
	add_theme_support('post-thumbnails');

	// Default Post Thumbnail dimensions
	set_post_thumbnail_size(150, 150);
}

function the_excerpt_max_charlength($charlength) {
	$excerpt = get_the_excerpt();
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo $subex;
		}
		echo '...';
	} else {
		echo $excerpt;
	}
}

function comment_count( $count ) {
	if ( ! is_admin() ) {
		global $id;

		$comments = get_comments('status=approve&post_id=' . $id);
		$separate_comments = separate_comments($comments);

		$comments_by_type = &$separate_comments;
		return count($comments_by_type['comment']);
	} else {
		return $count;
	}
}
add_filter('get_comments_number', 'comment_count', 0);

function tgm_cpt_search( $query ) {
	if ( $query->is_search )
		$query->set( 'post_type', array( 'page', 'post', 'movies' ) );
	return $query;
}
add_filter( 'pre_get_posts', 'tgm_cpt_search' );

// Add new image sizes
if ( function_exists('add_image_size')) {
	add_image_size('open-small', 70, 70, true); // gallery-small gallery size
	add_image_size('large-image', 1200, 719, true); // large-image image size
	add_image_size('post-slider-small', 300, 200, true); // post slider image size
	add_image_size('animals-places-small', 300, 186, true); // post slider image size
	add_image_size('animals-places-mini', 135, 93, true); // post slider image size
	add_image_size('donation-project', 510, 280, true); // post slider image size

	# Gallery image Cropped sizes
	add_image_size('gallery-large', 270, 270, true); // gallery-large gallery size
	add_image_size('gallery-medium', 125, 125, true); // gallery-medium gallery size
	add_image_size('gallery-small', 90, 90, true); // gallery-small gallery size
}

// Public JS scripts
if (!function_exists('vh_scripts_method')) {
	function vh_scripts_method() {
		wp_register_script( 'prettyphoto', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', array( 'jquery' ), '', true);

		wp_enqueue_script('jquery');
		wp_enqueue_script('prettyphoto', array('jquery'), '', TRUE);
		wp_enqueue_script('master', get_template_directory_uri() . '/js/master.js', array('jquery', 'prettyphoto'), '', TRUE);
		wp_enqueue_script('isotope', get_template_directory_uri() . '/js/jquery.isotope.min.js', array('jquery', 'master'), '', TRUE);
		wp_enqueue_script('jquery-ui-tabs');
		
		wp_enqueue_script('google-maps', '//maps.googleapis.com/maps/api/js?sensor=false', array(), '3', true);
		wp_enqueue_script('jquery.pushy', get_stylesheet_directory_uri() . '/js/nav/pushy.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.mousewheel', get_stylesheet_directory_uri() . '/js/jquery.mousewheel.min.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.jcarousel', get_stylesheet_directory_uri() . '/js/jquery.jcarousel.pack.js', array('jquery'), '', TRUE);

		wp_enqueue_script('jquery.classie', get_stylesheet_directory_uri() . '/js/classie.js', array('jquery'), '', TRUE);

		wp_enqueue_script('jquery.kinetic', get_stylesheet_directory_uri() . '/js/smoothscroll/jquery.kinetic.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.smoothdivscroll', get_stylesheet_directory_uri() . '/js/smoothscroll/jquery.smoothDivScroll-1.3-min.js', array('jquery'), '', TRUE);

		wp_enqueue_script('jquery.snap.svg-min', get_stylesheet_directory_uri() . '/js/snap.svg-min.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.svgLoader', get_stylesheet_directory_uri() . '/js/svgLoader.js', array('jquery'), '', TRUE);

		wp_enqueue_script('jquery.coinslider', get_stylesheet_directory_uri() . '/js/coin-slider.min.js', array('jquery'), '', TRUE);

		wp_enqueue_script('jquery.infobox', get_stylesheet_directory_uri() . '/js/infobox.js', array('jquery'), '', TRUE);

		wp_enqueue_script( 'jquery-ui-dialog' );

		wp_enqueue_script('jquery.cookie', get_stylesheet_directory_uri() . '/js/jquery.cookie.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.dots', get_stylesheet_directory_uri() . '/js/dots.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.debouncedresize', get_stylesheet_directory_uri() . '/js/jquery.debouncedresize.js', array('jquery'), '', TRUE);

		wp_enqueue_script('jquery.modernizr', get_stylesheet_directory_uri() . '/js/modernizr.custom.js', array('jquery'), '', FALSE);

		wp_enqueue_script("jquery-effects-core");

		wp_enqueue_script('jquery.dotdotdot', get_stylesheet_directory_uri() . '/js/jquery.dotdotdot.min.js', array('jquery'), '', TRUE);
		

		if ( is_singular() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		wp_localize_script( 'master', 'ajax_login_object', array( 
			'ajaxurl'        => admin_url( 'admin-ajax.php' ),
			'redirecturl'    => home_url(),
			'loadingmessage' => __('Sending user info, please wait...', "vh" )
		));

		wp_localize_script( 'master', 'my_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		wp_localize_script( 'master', 'rev_slider', array( 'apiid' => get_revslider_apiid() ) );
	}
}
add_action('wp_enqueue_scripts', 'vh_scripts_method');

// Admin JS scripts

// function vh_admin_enqueue() {
// 	wp_enqueue_script( 'jquery-ui-dialog' );
// 	wp_enqueue_style('jquery-ui-dialog', get_template_directory_uri() . '/functions/admin/jquery-ui-1.10.4.custom.min.css');
// }
// add_action( 'admin_enqueue_scripts', 'vh_admin_enqueue' );

// Public CSS files
if (!function_exists('vh_style_method')) {
	function vh_style_method() {
		wp_enqueue_style('master-css', get_stylesheet_directory_uri() . '/style.css');
		wp_enqueue_style('vh-normalize', get_stylesheet_directory_uri() . '/css/normalize.css');

		// wp_register_style( 'prettyphoto', get_template_directory_uri() . '/wpbakery/js_composer/assets/lib/prettyphoto/css/prettyPhoto.css', false, '', 'screen' );

		// wp_enqueue_style('ch-jcarousel', get_stylesheet_directory_uri() . '/css/jcarousel.css');

		wp_enqueue_style('js_composer_front');
		wp_enqueue_style('prettyphoto');
		// wp_enqueue_style('superfish', get_stylesheet_directory_uri() . '/css/nav/superfish.css');
		wp_enqueue_style('vh-responsive', get_stylesheet_directory_uri() . '/css/responsive.css');
		wp_enqueue_style('pushy', get_stylesheet_directory_uri() . '/css/nav/pushy.css');

		wp_enqueue_style('component', get_stylesheet_directory_uri() . '/css/component.css');

		// wp_enqueue_style('prettycheckable', get_stylesheet_directory_uri() . '/css/prettyCheckable.css');

		// wp_enqueue_style('jquery-ui-dialog', get_stylesheet_directory_uri() . '/css/jquery-ui-1.10.4.custom.min.css');

		// Load google fonts
		if (file_exists(TEMPLATEPATH . '/css/gfonts.css')) {
			wp_enqueue_style('front-gfonts', get_template_directory_uri() . '/css/gfonts.css');
		}

		/* Color scheme css */
		// wp_enqueue_style('color-schemes-green', get_template_directory_uri() . '/css/color-schemes/green.css');
		// wp_enqueue_style('color-schemes-yellow', get_template_directory_uri() . '/css/color-schemes/yellow.css');
		// wp_enqueue_style('color-schemes-red', get_template_directory_uri() . '/css/color-schemes/red.css');
		// wp_enqueue_style('color-schemes-gray', get_template_directory_uri() . '/css/color-schemes/gray.css');

	}
}
add_action('wp_enqueue_scripts', 'vh_style_method');

function ajax_login() {

	// First check the nonce, if it fails the function will break
	check_ajax_referer( 'ajax-login-nonce', 'security' );

	// Nonce is checked, get the POST data and sign user on
	$info                  = array();
	$info['user_login']    = $_POST['username'];
	$info['user_password'] = $_POST['password'];
	$info['remember']      = true;

	$user_signon = wp_signon( $info, false );
	if ( is_wp_error($user_signon) ){
		echo json_encode(array('loggedin' => false, 'message' => __('Wrong username or password.', "vh" )));
	} else {
		echo json_encode(array('loggedin' => true, 'message' => __('Login successful, redirecting...', "vh" )));
	}

	die();
}

// Enable the user with no privileges to run ajax_login() in AJAX
add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );

/* Filter categories */
function filter_categories($list) {

	$find    = '(';
	$replace = '[';
	$list    = str_replace( $find, $replace, $list );
	$find    = ')';
	$replace = ']';
	$list    = str_replace( $find, $replace, $list );

	return $list;
}
add_filter('wp_list_categories', 'filter_categories');

if (file_exists(VH_ADMIN . '/featured-images/featured-images.php')) {
	require_once(VH_ADMIN . '/featured-images/featured-images.php');
	require_once(VH_ADMIN . '/featured-images/featured-images-animals.php');
	require_once(VH_ADMIN . '/featured-images/featured-images-events.php');
}

if( class_exists( 'vhFeaturedImages' ) ) {
	$i = 2;
	$posts_slideshow = ( get_option('vh_posts_slideshow_number') ) ? get_option('vh_posts_slideshow_number') : 5;

	while($i <= $posts_slideshow) {
		$args = array(
			'id'        => 'featured-image-'.$i,
			'post_type' => 'donations', // Set this to post or page
			'labels'    => array(
				'name'   => 'Featured image '.$i,
				'set'    => 'Set featured image '.$i,
				'remove' => 'Remove featured image '.$i,
				'use'    => 'Use as featured image '.$i,
			)
		);

		new vhFeaturedImages( $args );

		$args = array(
			'id'        => 'featured-image-'.$i,
			'post_type' => 'donations', // Set this to post or page
			'labels'    => array(
				'name'   => 'Featured image '.$i,
				'set'    => 'Set featured image '.$i,
				'remove' => 'Remove featured image '.$i,
				'use'    => 'Use as featured image '.$i,
			)
		);

		new vhFeaturedImages( $args );

		$args = array(
			'id'        => 'featured-image-'.$i,
			'post_type' => 'donations', // Set this to post or page
			'labels'    => array(
				'name'   => 'Featured image '.$i,
				'set'    => 'Set featured image '.$i,
				'remove' => 'Remove featured image '.$i,
				'use'    => 'Use as featured image '.$i,
			)
		);

		new vhFeaturedImages( $args );

		$i++;
	}
}

if( class_exists( 'vhanimalsFeaturedImages' ) ) {
	$i = 2;

	while($i <= $posts_slideshow) {
		$args = array(
			'id'        => 'featured-image-'.$i,
			'post_type' => 'animals_places', // Set this to post or page
			'labels'    => array(
				'name'   => 'Featured image '.$i,
				'set'    => 'Set featured image '.$i,
				'remove' => 'Remove featured image '.$i,
				'use'    => 'Use as featured image '.$i,
			)
		);

		new vhanimalsFeaturedImages( $args );

		$args = array(
			'id'        => 'featured-image-'.$i,
			'post_type' => 'animals_places', // Set this to post or page
			'labels'    => array(
				'name'   => 'Featured image '.$i,
				'set'    => 'Set featured image '.$i,
				'remove' => 'Remove featured image '.$i,
				'use'    => 'Use as featured image '.$i,
			)
		);

		new vhanimalsFeaturedImages( $args );

		$args = array(
			'id'        => 'featured-image-'.$i,
			'post_type' => 'animals_places', // Set this to post or page
			'labels'    => array(
				'name'   => 'Featured image '.$i,
				'set'    => 'Set featured image '.$i,
				'remove' => 'Remove featured image '.$i,
				'use'    => 'Use as featured image '.$i,
			)
		);

		new vhanimalsFeaturedImages( $args );

		$i++;
	}
}

if( class_exists( 'vheventsFeaturedImages' ) ) {
	$i = 2;

	while($i <= $posts_slideshow) {
		$args = array(
			'id'        => 'featured-image-'.$i,
			'post_type' => 'tribe_events', // Set this to post or page
			'labels'    => array(
				'name'   => 'Featured image '.$i,
				'set'    => 'Set featured image '.$i,
				'remove' => 'Remove featured image '.$i,
				'use'    => 'Use as featured image '.$i,
			)
		);

		new vheventsFeaturedImages( $args );

		$args = array(
			'id'        => 'featured-image-'.$i,
			'post_type' => 'tribe_events', // Set this to post or page
			'labels'    => array(
				'name'   => 'Featured image '.$i,
				'set'    => 'Set featured image '.$i,
				'remove' => 'Remove featured image '.$i,
				'use'    => 'Use as featured image '.$i,
			)
		);

		new vheventsFeaturedImages( $args );

		$args = array(
			'id'        => 'featured-image-'.$i,
			'post_type' => 'tribe_events', // Set this to post or page
			'labels'    => array(
				'name'   => 'Featured image '.$i,
				'set'    => 'Set featured image '.$i,
				'remove' => 'Remove featured image '.$i,
				'use'    => 'Use as featured image '.$i,
			)
		);

		new vheventsFeaturedImages( $args );

		$i++;
	}
}

// Custom Login Logo
function vh_login_logo() {
	$login_logo = get_option('vh_login_logo');

	if ($login_logo != false) {
		echo '
	<style type="text/css">
		#login h1 a { background-image: url("' . $login_logo . '") !important; }
	</style>';
	}
}
add_action('login_head', 'vh_login_logo');

function vh_ldc_like_counter_p( $text="Likes: ",$post_id=NULL ) {
	global $post;
	$ldc_return = '';

	if( empty($post_id) ) {
		$post_id = $post->ID;
	}

	if ( function_exists('get_post_ul_meta') ) {
		$ldc_return = "<span class='ldc-ul_cont_likes icon-heart' onclick=\"alter_ul_post_values(this,'$post_id','like')\" >".$text."<span>".get_post_ul_meta($post_id,"like")."</span></span>";
	}

	return $ldc_return;
}

function get_revslider_apiid() {
	global $wpdb;

	$apiid = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."revslider_sliders WHERE alias='Front_Page'");

	return $apiid;
}

add_action( 'wp_ajax_nopriv_donations-filter', 'vh_load_donations_list' );
add_action( 'wp_ajax_donations-filter', 'vh_load_donations_list' );
function vh_load_donations_list() {

	global $post;

	$donation_type = sanitize_text_field($_POST['type']);
	$donation_keyword = sanitize_text_field($_POST['keyword']);
	$times = sanitize_text_field($_POST['time']);

	$output = '';

	// $output = '<script type="text/javascript">
	// var $container = jQuery("#movies_list_content .event_container").isotope({
	// 	layoutMode: "fitRows",
	// 	transformsEnabled: true,
	// 		getSortData: {
	// 			popularity : function ( $elem ) {
	// 				return parseInt($elem.find(".event_list_rating").text());
	// 			},
	// 			release : function ( $elem ) {
					
	// 				var release_val = $elem.find(".info.event_release").text();
	// 				if ( release_val == "" ) {
	// 					release_val = "January 1, 1970";
	// 				}
	// 				return Date.parse(release_val);
	// 			},
	// 			comments : function ( $elem ) {
	// 				return parseInt( $elem.find(".comments").text());
	// 			}
	// 		},
	// 		sortBy: "'.$sorting.'",
	// 		sortAscending: false,
	// 		animationOptions: {
	// 			duration: 250,
	// 			easing: "swing",
	// 			queue: true
	// 		},
	// 		animationEngine : "jquery"
	// });
	// jQuery(".wrapper-dropdown-6 .dropdown li").click(function(e) {
	// 	var sortValue = jQuery(this).find("input[type=hidden]").attr("data-sort-value");
	// 		$container.isotope({
	// 			sortBy: sortValue,
	// 			sortAscending: false
	// 		});
	// });

	// jQuery( ".event_list.isotope-item .movie_list_image" ).mouseenter(function() {
	// 	jQuery(this).find(".bottom_line").show().animate({
	// 		width: "100%",
	// 		left: "0%", 
	// 	}, 300, function() {
	// 		// Animation complete.
	// 	 });
	// });

	// jQuery( ".event_list.isotope-item .movie_list_image" ).mouseleave(function() {
	// 	jQuery(this).find(".bottom_line").show().animate({
	// 		width: "0%",
	// 		left: "50%", 
	// 	}, 300, function() {
	// 		// Animation complete.
	// 	 });
	// });

	// jQuery(document).ajaxComplete(function() {
	// 	var main = 0;
	// 	var sidebar_height = 0;
	// 	var sidebar = "";
	// 	if ( jQuery(".page-wrapper").hasClass("page-sidebar-right") ) {
	// 		sidebar_height = jQuery(".sidebar-right").height();
	// 		sidebar = jQuery(".sidebar-right");
	// 		main = jQuery(".sidebar-right-pull").height();
	// 	} else {
	// 		sidebar_height = jQuery(".sidebar-left").height();
	// 		sidebar = jQuery(".sidebar-left");
	// 		main = jQuery(".sidebar-left-pull").height();
	// 	};

	// 	if ( jQuery(window).width() > 750 && main > sidebar.height() ) {
	// 		sidebar.height(main);
	// 	} else {
	// 		sidebar.height("auto");
	// 	}
	// });
	// </script>';
	
	$args = array(
	'numberposts' => -1,
	'post_type' => 'donations',
	'post_status' => 'publish',
	'event_categories' => $donation_type,
	'orderby' => 'date',
	);

	$the_query = new WP_Query( $args );

	if( $the_query->have_posts() ) {
		$output .= '<ul class="donations_container">';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			$category = get_the_terms($post->ID, 'vh_donations');
			$categories = array();

			foreach ($category as $value) {
				$categories[] .= $value->slug;
			}

			if ( get_post_meta( get_the_id(), '_donations_so_far', true ) == null || get_post_meta( get_the_id(), '_donations_so_far', true ) == '0' ) {
				$donated_so_far = '0';
				$funded_donations = '0';
			} else {
				$donated_so_far = get_post_meta( get_the_id(), '_donations_so_far', true );
				$funded_donations = get_post_meta( get_the_id(), '_donations_so_far', true ) / get_post_meta( get_the_id(), '_how_many_donations_are_needed', true ) * 100;
				if ( $funded_donations > 100 ) {
					$funded_donations = '100';
				}
			}

			if ( $donation_keyword == '' ) {
				if ( $donation_type == 'all' ) {
					$output .= '<li class="project">
					<div class="project-container">
						<div class="project-image">
							<a href="' . get_permalink( $post->ID ) . '">' . get_the_post_thumbnail( $post->ID, 'donation-project' ) . '</a>';
					$output .= '
						</div>
						<div class="project-title">
						<a href="' . get_permalink( $post->ID ) . '">' . get_the_title() . '</a>
						</div>
						<div class="project-content">';
							if ( strlen(strip_tags(get_the_content())) > 150 ) {
								$project_content = '<p>' . substr(strip_tags(get_the_content()), 0, 150) . '..</p>';
							} else {
								$project_content = '<p>' . strip_tags(get_the_content()) . '</a>';
							}
							$output .= 
							$project_content . '
						</div>
						<div class="project-details">
							<div class="donated_so_far"><span>' . get_option('vh_currency_sign') . $donated_so_far . '</span>' . __(' Donated', 'vh') . '</div>
							<div class="funded_donations"><span>' . round($funded_donations, 1) . '%</span> ' . __('Funded', 'vh') . '</div>
							<div class="clearfix"></div>
							<div class="donated_amount"><span style="width:'.round($funded_donations, 1).'%"></span></div>
							<div class="donate_read_more"><a href="' . get_permalink( $post->ID ) . '" class="wpb_button wpb_btn-transparent wpb_small">' . __('Read more', 'vh') . '</a></div>
							<div class="donate_button"><a href="' . get_permalink( $post->ID ) . '#donations-open-container" class="wpb_button wpb_btn-success wpb_small">' . __('donate', 'vh') . '</a></div>
							<div class="clearfix"></div>
						</div>
					</div>';
					$output .= '</li>';
				} else {
					foreach ($categories as $value) {
						if ( $value == $donation_type) {
							$output .= '<li class="project">
							<div class="project-container">
								<div class="project-image">
									' . get_the_post_thumbnail( $post->ID );
							$output .= '
								</div>
								<div class="project-title">
								<a href="' . get_permalink( $post->ID ) . '">' . get_the_title() . '</a>
								</div>
								<div class="project-content">';
									if ( strlen(strip_tags(get_the_content())) > 150 ) {
										$project_content = '<p>' . substr(strip_tags(get_the_content()), 0, 150) . '..</p>';
									} else {
										$project_content = '<p>' . strip_tags(get_the_content()) . '</a>';
									}
									$output .= 
									$project_content . '
								</div>
								<div class="project-details">
									<div class="donated_so_far"><span>' . get_option('vh_currency_sign') . $donated_so_far . '</span>' . __(' Donated', 'vh') . '</div>
									<div class="funded_donations"><span>' . round($funded_donations, 1) . '%</span> ' . __('Funded', 'vh') . '</div>
									<div class="clearfix"></div>
									<div class="donated_amount"><span style="width:'.round($funded_donations, 1).'%"></span></div>
									<div class="donate_read_more"><a href="' . get_permalink( $post->ID ) . '" class="wpb_button wpb_btn-transparent wpb_small">' . __('Read more', 'vh') . '</a></div>
									<div class="donate_button"><a href="' . get_permalink( $post->ID ) . '#donations-open-container" class="wpb_button wpb_btn-success wpb_small">' . __('donate', 'vh') . '</a></div>
									<div class="clearfix"></div>
								</div>
							</div>';
							$output .= '</li>';
						}
					}
				}
			} elseif ( strpos(strtolower($post->post_title), strtolower($donation_keyword)) !== false ) {
				if ( $donation_type == 'all' ) {
					$output .= '<li class="project">
					<div class="project-container">
						<div class="project-image">
							' . get_the_post_thumbnail( $post->ID );
					$output .= '
						</div>
						<div class="project-title">
						<a href="' . get_permalink( $post->ID ) . '">' . get_the_title() . '</a>
						</div>
						<div class="project-content">';
							if ( strlen(strip_tags(get_the_content())) > 150 ) {
								$project_content = '<p>' . substr(strip_tags(get_the_content()), 0, 150) . '..</p>';
							} else {
								$project_content = '<p>' . strip_tags(get_the_content()) . '</a>';
							}
							$output .= 
							$project_content . '
						</div>
						<div class="project-details">
							<div class="donated_so_far"><span>' . get_option('vh_currency_sign') . $donated_so_far . '</span>' . __(' Donated', 'vh') . '</div>
							<div class="funded_donations"><span>' . round($funded_donations, 1) . '%</span> ' . __('Funded', 'vh') . '</div>
							<div class="clearfix"></div>
							<div class="donated_amount"><span style="width:'.round($funded_donations, 1).'%"></span></div>
							<div class="donate_read_more"><a href="' . get_permalink( $post->ID ) . '" class="wpb_button wpb_btn-transparent wpb_small">' . __('Read more', 'vh') . '</a></div>
							<div class="donate_button"><a href="' . get_permalink( $post->ID ) . '#donations-open-container" class="wpb_button wpb_btn-success wpb_small">' . __('donate', 'vh') . '</a></div>
							<div class="clearfix"></div>
						</div>
					</div>';
					$output .= '</li>';
				} else {
					foreach ($categories as $value) {
						if ( $value == $donation_type) {
							$output .= '<li class="project">
							<div class="project-container">
								<div class="project-image">
									' . get_the_post_thumbnail( $post->ID );
							$output .= '
								</div>
								<div class="project-title">
								<a href="' . get_permalink( $post->ID ) . '">' . get_the_title() . '</a>
								</div>
								<div class="project-content">';
									if ( strlen(strip_tags(get_the_content())) > 150 ) {
										$project_content = '<p>' . substr(strip_tags(get_the_content()), 0, 150) . '..</p>';
									} else {
										$project_content = '<p>' . strip_tags(get_the_content()) . '</a>';
									}
									$output .= 
									$project_content . '
								</div>
								<div class="project-details">
									<div class="donated_so_far"><span>' . get_option('vh_currency_sign') . $donated_so_far . '</span>' . __(' Donated', 'vh') . '</div>
									<div class="funded_donations"><span>' . round($funded_donations, 1) . '%</span> ' . __('Funded', 'vh') . '</div>
									<div class="clearfix"></div>
									<div class="donated_amount"><span style="width:'.round($funded_donations, 1).'%"></span></div>
									<div class="donate_read_more"><a href="' . get_permalink( $post->ID ) . '" class="wpb_button wpb_btn-transparent wpb_small">' . __('Read more', 'vh') . '</a></div>
									<div class="donate_button"><a href="' . get_permalink( $post->ID ) . '#donations-open-container" class="wpb_button wpb_btn-success wpb_small">' . __('donate', 'vh') . '</a></div>
									<div class="clearfix"></div>
								</div>
							</div>';
							$output .= '</li>';
						}
					}
				}
			}
		}
		$output .= '</ul><div class="clearfix"></div>';
	}
	
	echo $output;
	
	wp_reset_query(); 
	die(1);
}

function get_donation_categories() {
	$categories = get_terms('vh_donations');
	$output = '';
	$output = '<select>
	<option value="all">'.__('All', 'vh').'</option>';
	foreach ( $categories as $value ) {
		if ( $value->count > 0 ) {
			$output .= '<option value="'.$value->slug.'">'.$value->name.'</option>';
		}
	}
	$output .= '</select>';

	return $output;
}

function get_donation_thank_you() {
	global $wpdb;
	$querystr = "SELECT p.ID,pm.meta_key,pm.meta_value FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta pm WHERE p.post_status = 'publish' AND p.post_type = 'donations' AND (pm.meta_key = '_donations_so_far' OR pm.meta_key = '_how_many_donations_are_needed') AND pm.post_id = p.ID";
	$queryresults = $wpdb->get_results($querystr, 'ARRAY_A');
	$end_array = array('total_donations_needed' => 0, 'total_donations_so_far' => 0);

	for ($i=0; $i < count($queryresults); $i+=2) { 
		$end_array['total_donations_needed'] += $queryresults[$i]['meta_value'];
		$end_array['total_donations_so_far'] += $queryresults[$i+1]['meta_value'];
	}

	return $end_array;
}

// Admin CSS
function vh_admin_css() {
	wp_enqueue_style( 'vh-admin-css', get_template_directory_uri() . '/functions/admin/css/wp-admin.css' );
}

add_action('admin_head','vh_admin_css');

// Sets the post excerpt length to 40 words.
function vh_excerpt_length($length) {
	return 39;
}
add_filter('excerpt_length', 'vh_excerpt_length');

// Returns a "Continue Reading" link for excerpts
function vh_continue_reading_link() {
	return ' </p><p><a href="' . esc_url(get_permalink()) . '" class="read_more_link">' . __('Read more', 'vh') . '</a>';
}

function vh_post_image_module( $value, $data ) {
	/**
	 * @var null|Wp_Post $post ;
	 */
	extract( array_merge( array(
		'post' => null,
	), $data ) );

	$post_id = $post->ID;
	$post_image = get_the_post_thumbnail($post_id, 'gallery-large');
	$output = '';
	$tc = wp_count_comments($post_id);

	if ( $post_image != '' ) {
		$output .= '
		<div class="post-thumb">
			<div class="post-thumb-img-wrapper">
				<div class="blog-post-img-overlay"><a href="' . get_permalink($post->id) . '">' . __('Read article', 'vh') . '</a></div>
				<a href="' . get_the_permalink( $post_id ) . '" class="link_image" title="Permalink to ' . get_the_title( $post_id ) . '">' . $post_image . '</a>
			</div>
			<div class="blog-infobox">
				<div class="blog-post-date icon-calendar">' . get_the_date('j M', $post->id) . '</div>
				<div class="blog-post-comments icon-comment-1">' . $tc->total_comments . '</div>
			</div>
		</div>';
	} else {
		$output .= '
		<div class="post-thumb nothumbnail">
			<div class="blog-infobox">
				<div class="blog-post-date icon-calendar">' . get_the_date('j M', $post->id) . '</div>
				<div class="blog-post-comments icon-comment-1">' . $tc->total_comments . '</div>
			</div>
		</div>';
	}

	return $output;
}
add_filter( 'vc_gitem_template_attribute_vh_post_image_module', 'vh_post_image_module', 10, 2 );

function vh_post_content_module( $value, $data ) {
	/**
	 * @var null|Wp_Post $post ;
	 */
	extract( array_merge( array(
		'post' => null,
	), $data ) );

	$post_id = $post->ID;

	$output = '<div class="entry-content">' . get_the_excerpt() . '</div>';

	return $output;
}
add_filter( 'vc_gitem_template_attribute_vh_post_content_module', 'vh_post_content_module', 10, 2 );

function vh_post_readmore_module( $value, $data ) {
	/**
	 * @var null|Wp_Post $post ;
	 */
	extract( array_merge( array(
		'post' => null,
	), $data ) );

	$post_id = $post->ID;

	$output = '<div class="read_more"><a href="' . get_the_permalink( $post_id ) . '" class="vc_read_more wpb_button wpb_btn-transparent wpb_small" title="Permalink to ' . get_the_title( $post_id ) . '">' . __('Read more', 'vh') . '</a></div>';

	return $output;
}
add_filter( 'vc_gitem_template_attribute_vh_post_readmore_module', 'vh_post_readmore_module', 10, 2 );

function vh_post_category_module( $value, $data ) {
	/**
	 * @var null|Wp_Post $post ;
	 */
	extract( array_merge( array(
		'post' => null,
	), $data ) );

	$post_id = $post->ID;
	$output = '';
	$category_array = get_the_category($post_id);
	if ( !empty($category_array) ) {
		$categories = '';
		foreach ($category_array as $category) {
			$categories .= '<a href="' . $category->term_id . '">' . $category->name . '</a>, ';
		}
			
		$output .= '<div class="blog-post-category">' . rtrim($categories, ', ') . '</div>';
	}

	return $output;
}
add_filter( 'vc_gitem_template_attribute_vh_post_category_module', 'vh_post_category_module', 10, 2 );


// // Remove read more link
// function vh_auto_excerpt_more($more) {
// 	return ' </p><p><a href="' . esc_url(get_permalink()) . '" class="read_more_link">' . __('Read more', 'vh') . '</a>';
// }
// add_filter('excerpt_more', 'vh_auto_excerpt_more');

function my_widget_class($params) {

	// its your widget so you add  your classes
	$classe_to_add = (strtolower(str_replace(array(' '), array(''), $params[0]['widget_name']))); // make sure you leave a space at the end
	$classe_to_add = 'class=" '.$classe_to_add . ' ';
	$params[0]['before_widget'] = str_replace('class="', $classe_to_add, $params[0]['before_widget']);

	return $params;
}
add_filter('dynamic_sidebar_params', 'my_widget_class');

// add_filter('widget_title', vh_my_title);
// function vh_my_title($title) {
// 	$title_parts = explode(' ', $title);
// 	$title = $title_parts[0].' '.'<strong>';

// 	for ($i=1; $i < count($title_parts); $i++) { 
// 		$title .= ' ' . $title_parts[$i];
// 	}
// 	$title .= '</strong>';
//     return $title;
// }

add_action( 'wp_ajax_nopriv_load-filter', 'prefix_load_cat_posts' );
add_action( 'wp_ajax_load-filter', 'prefix_load_cat_posts' );
function prefix_load_cat_posts() {
	$post_type = sanitize_text_field($_POST['post_type']);
	$post_categories = sanitize_text_field($_POST['post_categories']);
	$post_limit = sanitize_text_field($_POST['post_limit']);
	$output = $li_class = '';
	$count = 1;

	if ( $post_type == 'recent' ) {
		$post_order = 'date';
	} elseif ( $post_type == 'popular' ) {
		$post_order = 'comment_count';
	}
	
	$args = array(
		'numberposts' => -1,
		'post_type' => 'post',
		'post_status' => 'publish',
		'orderby' => $post_order,
		'posts_per_page' => $post_limit,
		'category_name' => $post_categories
	);

	$the_query = new WP_Query( $args );

	if( $the_query->have_posts() ) {
		$output .= '<ul>';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			$extra_class = '';

			if ( $count % 2 ) {
				$li_class = 'odd';
			} else {
				$li_class = 'even';
			}

			if ( has_post_thumbnail( get_post()->ID ) == false ) {
				$extra_class = ' nothumb';
			}

			$output .= '<li class="' . $li_class . $extra_class . '"><div class="post_slider_main_container">
			<div class="post_slider_image">
			<div class="post_slider_image_overlay"><a href="' . get_permalink( get_the_ID() ) . '">' . __('Read article', 'vh') . '</a></div>';
			if ( has_post_thumbnail( get_post()->ID ) ) {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_post()->ID ), 'post-slider-small' );
				$output .= '<a href="' . get_permalink( get_the_ID() ) . '"><img src="'. $image[0] .'" alt=""></a>';
			}
			$tc = wp_count_comments( get_post()->ID ); 
			$date_format = get_option('date_format', 'j M');
			$post_timestamp = strtotime(get_post()->post_date);
			$output .= '</div>
			<div class="post_slider_info">
			<div class="post_slider_date icon-calendar">' . date_i18n($date_format, $post_timestamp) . '</div>
				<div class="comments icon-comment-1">' . $tc->total_comments . '</div>
			</div>
			<div class="clearfix"></div>
			<div class="post_slider_content">
				<div class="post_slider_post_title"><a href="' . get_permalink( get_the_ID() ) . '">' . get_the_title() . '</a></div>';
				if ( strlen(strip_tags(get_the_content())) > 150 ) {
					$post_content = substr(strip_tags(get_the_content()), 0, 150) . '..';
				} else {
					$post_content = strip_tags(get_the_content());
				}
				$output .= '<div class="post_slider_post_content">' . $post_content . '</div>
			</div>
			</div></li>';
			$count++;
		}
			
		$output .= '</ul>
		<div class="slider_loading"></div>';
	}

	echo $output;
	
	wp_reset_query(); 
	die(1);
}

add_action( 'wp_ajax_nopriv_load-animals', 'vh_load_animals' );
add_action( 'wp_ajax_load-animals', 'vh_load_animals' );
function vh_load_animals() {
	$post_type = sanitize_text_field($_POST['animal_type']);
	$post_categories = sanitize_text_field($_POST['animal_categories']);
	$post_limit = sanitize_text_field($_POST['animal_limit']);
	$module_link = sanitize_text_field($_POST['animals_link']);
	$random = rand();
	$events = array();
	$count = 0;
	$nav_count = 1;
	$output = $paged_images = $switch = $animals_class = $places_class = $preload_images = '';
	
	$args = array(
		'numberposts' => -1,
		'post_type' => 'animals_places',
		'post_status' => 'publish',
		'posts_per_page' => $post_limit,
		'vh_categories' => $post_categories
	);

	$the_query = new WP_Query( $args );

	if( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$events[] = array('image' => wp_get_attachment_image_src( get_post_thumbnail_id( get_post()->ID ), 'animals-places-small' ), 'image-mini' => wp_get_attachment_image_src( get_post_thumbnail_id( get_post()->ID ), 'animals-places-mini' ), 'title' => get_the_title( get_post()->ID ), 'link' => get_permalink( get_post()->ID ), 'location' => get_post_meta( get_post()->ID, 'animal_location', true ), 'link_last' => $module_link );
			$count++;
		}

		$paged_images .= 'var allImages = {';
		$page_count = ceil(count($events)/7);

		for ($i=0; $i < $page_count; $i++) { 
			if ( $i != $page_count-1 ) {
				$paged_images .= " page" . intval($i+1) . " : ['<a href=\"".$events[$i*7]['link']."\"><img src=\"" . $events[$i*7]['image']['0'] . "\" alt=\"img01\"/><div class=\"event-main\"><span class=\"event-title\">".$events[$i*7]['title']."</span><span class=\"event-location icon-location-1\">".$events[$i*7]['location']."</span></div></a>','<a href=\"".$events[$i*7+1]['link']."\"><img src=\"" . $events[$i*7+1]['image']['0'] . "\" alt=\"img02\"/><div class=\"event-main\"><span class=\"event-title\">".$events[$i*7+1]['title']."</span><span class=\"event-location icon-location-1\">".$events[$i*7+1]['location']."</span></div></a>','<a href=\"".$events[$i*7+2]['link']."\"><img src=\"" . $events[$i*7+2]['image']['0'] . "\" alt=\"img03\"/><div class=\"event-main\"><span class=\"event-title\">".$events[$i*7+2]['title']."</span><span class=\"event-location icon-location-1\">".$events[$i*7+2]['location']."</span></div></a>','<a href=\"".$events[$i*7+3]['link']."\"><img src=\"" . $events[$i*7+3]['image']['0'] . "\" alt=\"img04\"/><div class=\"event-main\"><span class=\"event-title\">".$events[$i*7+3]['title']."</span><span class=\"event-location icon-location-1\">".$events[$i*7+3]['location']."</span></div></a>','<a href=\"".$events[$i*7+4]['link']."\"><img src=\"" . $events[$i*7+4]['image']['0'] . "\" alt=\"img04\"/><div class=\"event-main\"><span class=\"event-title\">".$events[$i*7+4]['title']."</span><span class=\"event-location icon-location-1\">".$events[$i*7+4]['location']."</span></div></a>','<a href=\"".$events[$i*7+5]['link']."\"><img src=\"" . $events[$i*7+5]['image']['0'] . "\" alt=\"img04\"/><div class=\"event-main\"><span class=\"event-title\">".$events[$i*7+5]['title']."</span><span class=\"event-location icon-location-1\">".$events[$i*7+5]['location']."</span></div></a>','<a href=\"".$events[$i*7+6]['link']."\"><img src=\"" . $events[$i*7+6]['image']['0'] . "\" alt=\"img04\"/><div class=\"event-main\"><span class=\"event-title\">".$events[$i*7+6]['title']."</span><span class=\"event-location icon-location-1\">".$events[$i*7+6]['location']."</span><span class=\"event-more\"><span>".($count-7).__(' more', 'vh')."</span></span></div></a>'],";
			} else {
				if ( count($events)%7 != 0 ) {
					$paged_images .= " page" . intval($i+1) . " : [";
					for ($j=0; $j < count($events)%7; $j++) { 
						$paged_images .= "'<a href=\"".$events[$i*7+$j]['link']."\"><img src=\"" . $events[$i*7+$j]['image']['0'] . "\" alt=\"img01\"/><div class=\"event-main\"><span class=\"event-title\">".$events[$i*7+$j]['title']."</span><span class=\"event-location icon-location-1\">".$events[$i*7+$j]['location']."</span></div></a>',";
					}
					$paged_images .= "]";
				} else {
					$paged_images .= " page" . intval($i+1) . " : ['<a href=\"".$events[$i*7]['link']."\"><img src=\"" . $events[$i*7]['image']['0'] . "\" alt=\"img01\"/><div class=\"event-main\"><span class=\"event-title\">".$events[$i*7]['title']."</span><span class=\"event-location icon-location-1\">".$events[$i*7]['location']."</span></div></a>','<a href=\"".$events[$i*7+1]['link']."\"><img src=\"" . $events[$i*7+1]['image']['0'] . "\" alt=\"img02\"/><div class=\"event-main\"><span class=\"event-title\">".$events[$i*7+1]['title']."</span><span class=\"event-location icon-location-1\">".$events[$i*7+1]['location']."</span></div></a>','<a href=\"".$events[$i*7+2]['link']."\"><img src=\"" . $events[$i*7+2]['image']['0'] . "\" alt=\"img03\"/><div class=\"event-main\"><span class=\"event-title\">".$events[$i*7+2]['title']."</span><span class=\"event-location icon-location-1\">".$events[$i*7+2]['location']."</span></div></a>','<a href=\"".$events[$i*7+3]['link']."\"><img src=\"" . $events[$i*7+3]['image']['0'] . "\" alt=\"img04\"/><div class=\"event-main\"><span class=\"event-title\">".$events[$i*7+3]['title']."</span><span class=\"event-location icon-location-1\">".$events[$i*7+3]['location']."</span></div></a>','<a href=\"".$events[$i*7+4]['link']."\"><img src=\"" . $events[$i*7+4]['image']['0'] . "\" alt=\"img04\"/><div class=\"event-main\"><span class=\"event-title\">".$events[$i*7+4]['title']."</span><span class=\"event-location icon-location-1\">".$events[$i*7+4]['location']."</span></div></a>','<a href=\"".$events[$i*7+5]['link']."\"><img src=\"" . $events[$i*7+5]['image']['0'] . "\" alt=\"img04\"/><div class=\"event-main\"><span class=\"event-title\">".$events[$i*7+5]['title']."</span><span class=\"event-location icon-location-1\">".$events[$i*7+5]['location']."</span></div></a>','<a href=\"".$events[$i*7+6]['link']."\"><img src=\"" . $events[$i*7+6]['image']['0'] . "\" alt=\"img04\"/><div class=\"event-main\"><span class=\"event-title\">".$events[$i*7+6]['title']."</span><span class=\"event-location icon-location-1\">".$events[$i*7+6]['location']."</span><span class=\"event-more\"><span>".($count-7).__(' more', 'vh')."</span></span></div></a>'],";
				}
			}
			
		}

		$paged_images .= "};";

		for ($i=0; $i < $page_count-1; $i++) { 
			$switch .= "case ".intval($i+1)." : newImages = allImages.page".intval($i+2)."; break;";
		}

		foreach ($events as $value) {
			$preload_images .= "<image src=\"" . $value['image']['0'] . "\" width=\"1\" height=\"1\" border=\"0\" style=\"display: none\">";
		}

		if ( $post_type == "animals" ) {
			$animals_class = "active";
		} else {
			$places_class = "active";
		}


		$output .= "
		<script type='text/javascript'>
		jQuery(document).ready(function($) {
		
		" . $paged_images . "

		// http://coveroverflow.com/a/11381730/989439
		function mobilecheck() {
			var check = false;
			(function(a){if(/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
			return check;
		}

		var animEndEventNames = {
				'WebkitAnimation' : 'webkitAnimationEnd',
				'OAnimation' : 'oAnimationEnd',
				'msAnimation' : 'MSAnimationEnd',
				'animation' : 'animationend'
			},
			// animation end event name
			animEndEventName = animEndEventNames[ Modernizr.prefixed( 'animation' ) ],
			// event type (if mobile use touch events)
			eventtype = mobilecheck() ? 'touchstart' : 'click',
			// support for css animations
			support = Modernizr.cssanimations;

		function onAnimationEnd( elems, len, callback ) {
			var finished = 0,
				onEndFn = function() {
					this.removeEventListener( animEndEventName, onEndFn );
					++finished;
					if( finished === len ) {
						callback.call();
					}
				};

			elems.forEach( function( el,i ) { el.querySelector('a').addEventListener( animEndEventName, onEndFn ); } );
		}

		function init() {
			[].forEach.call( document.querySelectorAll( '.tt-grid-wrapper' ), function( el ) {

				var grid = el.querySelector( '.tt-grid' ),
					items = [].slice.call( grid.querySelectorAll( 'li' ) ),
					navDots = [].slice.call( el.querySelectorAll( 'nav > a' ) ),
					isAnimating = false,
					current = 0;

				navDots.forEach( function( el, i ) {
					el.addEventListener( eventtype, function( ev ) {
						if( isAnimating || current === i ) return false;
						ev.preventDefault();
						isAnimating = true;
						updateCurrent( i );
						loadNewSet( i );
		
					} );
				} );

				jQuery('.animals_main_next').click(function(e) {
					e.preventDefault();
					var nav_count = 0;
					var total_nav_count = 0;
					jQuery('.animal_slider_right_controls nav a').each(function(){
						if ( jQuery(this).hasClass('tt-current') ) {
							return false;
						}
						nav_count=nav_count+1;
					});
					jQuery('.animal_slider_right_controls nav a').each(function(){
						total_nav_count=total_nav_count+1;
					});
					if ( nav_count+1 < total_nav_count ) {
						updateCurrent( nav_count+1 );
						loadNewSet( nav_count+1 );
					} else {
						updateCurrent( 0 );
						loadNewSet( 0 );
					}
					
				});

				jQuery('.animals_main_prev').click(function(e) {
					e.preventDefault();
					var nav_count = 0;
					var total_nav_count = 0;
					jQuery('.animal_slider_right_controls nav a').each(function(){
						if ( jQuery(this).hasClass('tt-current') ) {
							return false;
						}
						nav_count=nav_count+1;
					});
					jQuery('.animal_slider_right_controls nav a').each(function(){
						total_nav_count=total_nav_count+1;
					});
					if ( nav_count-1 == -1 ) {
						updateCurrent( total_nav_count-1 );
						loadNewSet( total_nav_count-1 );
					} else {
						updateCurrent( nav_count-1 );
						loadNewSet( nav_count-1 );
					}
					
				});

				function updateCurrent( set ) {
					classie.remove( navDots[ current ], 'tt-current' );
					classie.add( navDots[ set ], 'tt-current' );
					current = set;
				}

				// this is just a way we can test this. You would probably get your images with an AJAX request...
				function loadNewSet( set ) {
					var newImages = allImages.page1;
					
					switch( set ) {
						".$switch."
						default : newImages = allImages.page1; break;
					};

					items.forEach( function( el ) {
						var itemChild = el.querySelector( 'a' );
						// add class 'tt-old' to the elements/images that are going to get removed
						if( itemChild ) {
							classie.add( itemChild, 'tt-old' );
						}
					} );

					// apply effect
					setTimeout( function() {
						
						// append new elements
						[].forEach.call( newImages, function( el, i ) { items[ i ].innerHTML += el; } );

						// add 'effect' class to the grid
						classie.add( grid, 'tt-effect-active' );
						
						// wait that animations end
						var onEndAnimFn = function() {
							// remove old elements
							items.forEach( function( el ) {
								// remove old elems
								var old = el.querySelector( 'a.tt-old' );
								if( old ) { el.removeChild( old ); }
								// remove class 'tt-empty' from the empty items
								classie.remove( el, 'tt-empty' );
								// now apply that same class to the items that got no children (special case)
								if ( !el.hasChildNodes() ) {
									classie.add( el, 'tt-empty' );
								};
							} );
							// remove the 'effect' class
							classie.remove( grid, 'tt-effect-active' );
							isAnimating = false;
						};
						if( support ) {
							onAnimationEnd( items, items.length, onEndAnimFn );
						}
						else {
							onEndAnimFn.call();
						}

					}, 25 );
					
				}

			} );
		}

		init();
	});
	</script>";

	$output .= '
		<div class="tt-grid-wrapper">
			<div class="animal_slider_controls">
				<div class="animal_slider_right_controls">
					<a href="' . $module_link . '" class="animal_slider_main_link">+</a>
					<div class="animals_buttons">
						<a href="#" class="animals_main_prev"></a>
						<a href="#" class="animals_main_next"></a>
					</div>
				<nav>';
				for ($i=0; $i < $page_count; $i++) {
					if ( $nav_count == 1 ) {
						$output .= '<a class="tt-current"></a>';
					} else {
						$output .= '<a></a>';
					}
					$nav_count++;
				}
			$output .= '
				</nav>
				</div>
				<a href="#" class="animal_slider_animals ' . $animals_class . '">'.__('Critical species', 'vh').'</a>
				<a href="#" class="animal_slider_places ' . $places_class . '">'.__('Priority places & habitats', 'vh').'</a>
			</div>
			<div class="clearfix"></div>
			<ul class="tt-grid tt-effect-3dflip tt-effect-delay">';

	for ($i=0; $i < 7; $i++) {
		if ( $i < 3 ) {
			$output .= '<li class="regular"><a href="'.$events[$i]['link'].'"><img src="'.$events[$i]['image']['0'].'" alt=""/><div class="event-main"><span class="event-title">'.$events[$i]['title'].'</span><span class="event-location icon-location-1">'.$events[$i]['location'].'</span></div></a></li>';
		} elseif ( $i == 6 ) {
			$output .= '<li class="small"><a href="'.$events[$i]['link_last'].'"><img src="'.$events[$i]['image-mini']['0'].'" alt=""/><div class="event-main"><span class="event-title">'.$events[$i]['title'].'</span><span class="event-location icon-location-1">'.$events[$i]['location'].'</span><span class="event-more "><span>'.($count-7).__(' more', 'vh').'</span></span></div></a></li>';
		} else {
			$output .= '<li class="small"><a href="'.$events[$i]['link'].'"><img src="'.$events[$i]['image-mini']['0'].'" alt=""/><div class="event-main"><span class="event-title">'.$events[$i]['title'].'</span><span class="event-location icon-location-1">'.$events[$i]['location'].'</span></div></a></li>';
		}
	}
	
	$output .= '
	</ul>
	<div class="animal_slider_loading"></div>
	' . $preload_images . '
	</div>';

	}

	echo $output;
	
	wp_reset_query(); 
	die(1);
}

function vh_convertToHoursMins($time, $format = '%d:%d') {
	settype($time, 'integer');
	if ($time < 1) {
		return;
	}
	$hours = floor($time / 60);
	$minutes = ($time % 60);
	return sprintf($format, $hours, $minutes);
}

function vh_wp_tag_cloud_filter($return, $args) {
	return '<div class="tag_cloud_' . $args['taxonomy'] . '">' . $return . '</div>';
}
add_filter('wp_tag_cloud', 'vh_wp_tag_cloud_filter', 10, 2);

// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
function vh_page_menu_args($args) {
	$args['show_home'] = true;
	return $args;
}
add_filter('wp_page_menu_args', 'vh_page_menu_args');

// function vh_tag_cloud_class($tag_string, $args) {
// 	foreach ( $args as $key => $value ) {
// 		$tag_string = preg_replace("/tag-link-(" . $value->id . ")/", $value->slug, $tag_string);
// 	}
// 	return $tag_string;
// }
// add_filter('wp_generate_tag_cloud', 'vh_tag_cloud_class', 10, 3);

// Register menus
function register_vh_menus () {
	register_nav_menus(
		array (
			'primary-menu' => __('Primary Menu', 'vh')
		)
	);
}
add_action('init', 'register_vh_menus');

// Adds classes to the array of body classes.
function vh_body_classes($classes) {

	// if ( class_exists('RevSliderOutput') ) {
	// 	$slider_widget = get_option('widget_rev-slider-widget');

	// 	if ( !empty($slider_widget) ) {
	// 		$rev_slider_options = array_values(get_option('widget_rev-slider-widget'));
	// 		$rev_slider_options = $rev_slider_options[0];

	// 		$homepageCheck = $rev_slider_options["rev_slider_homepage"];
	// 		$homepage = "";
	// 		if($homepageCheck == "on") {
	// 			$homepage = "homepage";
	// 		}

	// 		$pages = $rev_slider_options["rev_slider_pages"];
	// 		if(!empty($pages)){
	// 			if(!empty($homepage)){
	// 				$homepage .= ",";
	// 			}
	// 			$homepage .= $pages;
	// 		}
	// 		$rev_slider = new RevSliderOutput();

	// 		if ( $rev_slider->isPutIn($homepage) && !empty($homepage) ) {
	// 			$classes[] = 'page-with-rev-slider-widget';
	// 		}
	// 	}
	// }

	if (is_singular() && !is_home()) {
		$classes[] = 'singular';
	}

	if ( !is_front_page() ) {
		$classes[] = 'not_front_page';
	}

	if (is_search()) {
		$search_key = array_search('search', $classes);
		if ($search_key !== false) {
			unset($classes[$search_key]);
		}
	}

	// Color scheme class
	$vh_color_scheme = get_theme_mod( 'vh_color_scheme');

	if ( !empty($vh_color_scheme) ) {
		$classes[] = $vh_color_scheme;
	}

	// If blog shortcode
	global $post;
	if (isset($post->post_content) && false !== stripos($post->post_content, '[blog')) {
		$classes[] = 'page-template-blog';
	}
	
	// $fixed_menu = get_option('vh_fixed_menu') ? get_option('vh_fixed_menu') : 'true';
	// if ( $fixed_menu == 'true' ) {
	// 	$classes[] = 'fixed_menu';
	// }

	// Breadcrumbs class
	$disable_breadcrumb = get_option('vh_breadcrumb') ? get_option('vh_breadcrumb') : 'false';
	if (!is_home() && !is_front_page() && $disable_breadcrumb == 'false') {
		$classes[] = 'has_breadcrumb';
	}

	return $classes;
}
add_filter('body_class', 'vh_body_classes');

function vh_css_settings() {

	// Vars
	$css        = array();
	$custom_css = get_option('vh_custom_css');

	// Custom CSS
	if(!empty($custom_css)) {
		array_push($css, $custom_css);
	}

	echo "
		<!-- Custom CSS -->
		<style type='text/css'>\n";

	if(!empty($css)) {
		foreach($css as $css_item) {
			echo $css_item . "\n";
		}
	}

	$fonts[SHORTNAME . "_primary_font_dark"] = ' html .main-inner p, .ac-device .description, .pricing-table .pricing-content .pricing-desc-1, body .vc_progress_bar .vc_single_bar .vc_label, .page-wrapper .member-desc, .page-wrapper .member-position, .page-wrapper .main-inner ul:not(.ui-tabs-nav) li, .page-wrapper .bg-style-2 p';
	$fonts[SHORTNAME . "_sidebar_font_dark"] = ' .sidebar-inner, .environmental-contactform.widget input:not(.btn), .environmental-recentpostsplus.widget .news-item p, .wrapper .text.widget p, .environmental-fastflickrwidget.widget, .widget li, .wrapper .search.widget .sb-search-input, .widget .content-form .textarea.input-block-level, .text.widget .textwidget, .newsletter-email';
	$fonts[SHORTNAME . "_headings_font_h1"]  = ' .wrapper h1';
	$fonts[SHORTNAME . "_headings_font_h2"]  = ' .page-wrapper h2, h2, .content .entry-title, .teaser_grid_container .post-title';
	$fonts[SHORTNAME . "_headings_font_h3"]  = ' .wrapper h3';
	$fonts[SHORTNAME . "_headings_font_h4"]  = ' .wrapper h4';
	$fonts[SHORTNAME . "_headings_font_h5"]  = ' .wrapper h5';
	$fonts[SHORTNAME . "_headings_font_h6"]  = ' .wrapper h6';
	$fonts[SHORTNAME . "_links_font"]        = ' .wpb_wrapper a, #author-link a';
	$fonts[SHORTNAME . "_widget"]            = ' .wrapper .sidebar-inner .item-title-bg h4, .wrapper .sidebar-inner .widget-title, .wrapper h3.widget-title a';
	$fonts[SHORTNAME . "_page_title"]        = ' body .wrapper .page_info .page-title h1';

	// Custom fonts styling
	foreach ($fonts as $key => $font) {
		$output                 = '';
		$current['font-family'] = get_option($key . '_font_face');
		$current['font-size']   = get_option($key . '_font_size');
		$current['line-height'] = get_option($key . '_line_height');
		$current['color']       = get_option($key . '_font_color');
		$current['font-weight'] = get_option($key . '_weight');

		foreach ($current as $kkey => $item) {

			if ( $key == SHORTNAME . '_widget' ) {
				if (!empty($item)) {

					if ($kkey == 'font-size' || $kkey == 'line-height') {
						$ending = 'px';
					} else if ($kkey == 'color') {
						$before = '#';
					} else if ($kkey == 'font-family') {
						$before = "'";
						$ending = "'";
						$item   = str_replace("+", " ", $item);
					} else if ($kkey == 'font-weight' && $item == 'italic') {
						$kkey = 'font-style';
					} else if ($kkey == 'font-weight' && $item == 'bold_italic') {
						$kkey = 'font-style';
						$item = 'italic; font-weight: bold';
					}


					$output .= " " . $kkey . ": " . $before . $item . $ending . ";";
				}

			}

			$ending = '';
			$before = '';
			if (!empty($item) && $key != SHORTNAME . '_widget') {

				if ($kkey == 'font-size' || $kkey == 'line-height') {
					$ending = 'px';
				} else if ($kkey == 'color') {
					$before = '#';
				} else if ($kkey == 'font-family') {
					$before = "'";
					$ending = "'";
					$item   = str_replace("+", " ", $item);
				} else if ($kkey == 'font-weight' && $item == 'italic') {
					$kkey = 'font-style';
				} else if ($kkey == 'font-weight' && $item == 'bold_italic') {
					$kkey = 'font-style';
					$item = 'italic; font-weight: bold';
				}


				$output .= " " . $kkey . ": " . $before . $item . $ending . ";";
			}
		}


		if ( !empty($output) && !empty($font) && $key != SHORTNAME . '_widget' ) {
			echo $font . ' { ' . $output . ' }';
		}
		if ( !empty($output) && !empty($font) && $key == SHORTNAME . '_widget' ) {
			echo '@media (min-width: 1200px) { ' . $font . ' { ' . $output . ' } } ';
		}
	}

	echo "</style>\n";

}
add_action('wp_head','vh_css_settings', 99);

if (!function_exists('vh_posted_on')) {

	// Prints HTML with meta information for the current post.
	function vh_posted_on() {
		printf(__('<span>Posted: </span><a href="%1$s" title="%2$s" rel="bookmark">%4$s</a><span class="by-author"> by <a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span>', 'vh'),
			esc_url(get_permalink()),
			esc_attr(get_the_time()),
			esc_attr(get_the_date('c')),
			esc_html(get_the_date()),
			esc_url(get_author_posts_url(get_the_author_meta('ID'))),
			sprintf(esc_attr__('View all posts by %s', 'vh'), get_the_author()),
			esc_html(get_the_author())
		);
	}
}

function clear_nav_menu_item_id($id, $item, $args) {
	return "";
}
add_filter('nav_menu_item_id', 'clear_nav_menu_item_id', 10, 3);

function add_nofollow_cat( $text ) {
	$text = str_replace('rel="category"', "", $text);
	return $text;
}
add_filter( 'the_category', 'add_nofollow_cat' );

function ajax_contact() {
	if(!empty($_POST)) {
		$sitename = get_bloginfo('name');
		$siteurl  = home_url();
		$to       = isset($_POST['contact_to'])? trim($_POST['contact_to']) : '';
		$name     = isset($_POST['contact_name'])? trim($_POST['contact_name']) : '';
		$email    = isset($_POST['contact_email'])? trim($_POST['contact_email']) : '';
		$content  = isset($_POST['contact_content'])? trim($_POST['contact_content']) : '';

		$error = false;
		$error = ($to === '' || $email === '' || $content === '' || $name === '') ||
				 (!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $email)) ||
				 (!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $to));

		if($error == false) {
			$subject = "$sitename message from $name";
			$body    = "Site: $sitename ($siteurl) \n\nName: $name \n\nEmail: $email \n\nMessage: $content";
			$headers = "From: $name ($sitename) <$email>\r\nReply-To: $email\r\n";
			$sent    = wp_mail($to, $subject, $body, $headers);

			// If sent
			if ($sent) {
				echo 'sent';
				die();
			} else {
				echo 'error';
				die();
			}
		} else {
			echo _e('Please fill all fields!', 'vh');
			die();
		}
	}
}
add_action('wp_ajax_nopriv_contact_form', 'ajax_contact');
add_action('wp_ajax_contact_form', 'ajax_contact');

function addhttp($url) {
	if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
		$url = "http://" . $url;
	}
	return $url;
}

function checkShortcode($string) {
	global $post;
	if (isset($post->post_content) && false !== stripos($post->post_content, $string)) {
		return true;
	} else {
		return false;
	}
}

// custom comment fields
function vh_custom_comment_fields($fields) {
	global $post, $commenter;

	$fields['author'] = '<div class="comment_auth_email"><p class="comment-form-author">
							<input id="author" name="author" type="text" class="span4" placeholder="' . __( 'Name', 'vh' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" aria-required="true" size="30" />
						 </p>';

	$fields['email'] = '<p class="comment-form-email">
							<input id="email" name="email" type="text" class="span4" placeholder="' . __( 'Email', 'vh' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '" aria-required="true" size="30" />
						</p></div>';

	$fields['url'] = '<p class="comment-form-url">
						<input id="url" name="url" type="text" class="span4" placeholder="' . __( 'Website', 'vh' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />
						</p>';

	$fields = array( $fields['author'], $fields['email'] );
	return $fields;
}
add_filter( 'comment_form_default_fields', 'vh_custom_comment_fields' );

if ( ! function_exists( 'vh_comment' ) ) {
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own ac_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 */
	function vh_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>
		<li class="post pingback">
			<p><?php _e( 'Pingback:', 'vh' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'vh' ), '<span class="edit-link button blue">', '</span>' ); ?></p>
		<?php
				break;
			default :
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<div id="comment-<?php comment_ID(); ?>" class="comment">
				<div class="comment-meta">
					<div class="comment-author vcard shadows">
						<?php
							$avatar_size = 70;
							echo get_avatar( $comment, $avatar_size );							
						?>
					</div><!-- .comment-author .vcard -->
				</div>

				<div class="comment-content">
					<?php echo '<span class="fn">' . get_comment_author_link() . '</span>' ?>
						<?php if ( $comment->comment_approved == '0' ) : ?>
						<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'vh' ); ?></em>
					<?php endif; ?>
					<?php comment_text(); ?>
					<div class="reply-edit-container">
						<span class="reply">
							<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'vh' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						</span><!-- end of reply -->
						<?php edit_comment_link( __( 'Edit', 'vh' ), '<span class="edit-link button blue">', '</span>' ); ?>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
			</div><!-- end of comment -->

		<?php
				break;
		endswitch;
	}
}

function vh_breadcrumbs() {

	$disable_breadcrumb = get_option('vh_breadcrumb') ? get_option('vh_breadcrumb') : 'false';
	$delimiter          = get_option('vh_breadcrumb_delimiter') ? get_option('vh_breadcrumb_delimiter') : '<span class="delimiter icon-angle-circled-right"></span>';

	$home   = __('Home', 'vh'); // text for the 'Home' link
	$before = '<span class="current">'; // tag before the current crumb
	$after  = '</span>'; // tag after the current crumb

	if (!is_home() && !is_front_page() && $disable_breadcrumb == 'false') {
		global $post;
		$homeLink = home_url();

		$output = '<div class="breadcrumb">';
		$output .= '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';

		if (is_category()) {
			global $wp_query;
			$cat_obj   = $wp_query->get_queried_object();
			$thisCat   = $cat_obj->term_id;
			$thisCat   = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0)
				$output .= get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
			$output .= $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
		} elseif (is_day()) {
			$output .= '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			$output .= '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
			$output .= $before . get_the_time('d') . $after;
		} elseif (is_month()) {
			$output .= '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			$output .= $before . get_the_time('F') . $after;
		} elseif (is_year()) {
			$output .= $before . get_the_time('Y') . $after;
		} elseif (is_single() && !is_attachment()) {
			if (get_post_type() != 'post') {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				$output .= '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
				$output .= $before . get_the_title() . $after;
			} else {
				$cat = get_the_category();
				$cat = $cat[0];
				$output .= get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
				$output .= $before . get_the_title() . $after;
			}
		} elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
			$post_type = get_post_type_object(get_post_type());
			$output .= $before . $post_type->labels->singular_name . $after;
		} elseif (is_attachment()) {
			$parent = get_post($post->post_parent);
			$cat    = get_the_category($parent->ID);
			if ( isset($cat[0]) ) {
				$cat = $cat[0];
			}

			//$output .= get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
			$output .= '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
			$output .= $before . get_the_title() . $after;
		} elseif (is_page() && !$post->post_parent) {
			$output .= $before . get_the_title() . $after;
		} elseif (is_page() && $post->post_parent) {
			$parent_id   = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page          = get_page($parent_id);
				$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id     = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb) {
				$output .= $crumb . ' ' . $delimiter . ' ';
			}
			$output .= $before . get_the_title() . $after;
		} elseif (is_search()) {
			$output .= $before . 'Search results for "' . get_search_query() . '"' . $after;
		} elseif (is_tag()) {
			$output .= $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
		} elseif (is_author()) {
			global $vh_author;
			$userdata = get_userdata($vh_author);
			$output .= $before . 'Articles posted by ' . get_the_author() . $after;
		} elseif (is_404()) {
			$output .= $before . 'Error 404' . $after;
		}

		if (get_query_var('paged')) {
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
				$output .= ' (';
			$output .= __('Page', 'vh') . ' ' . get_query_var('paged');
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
				$output .= ')';
		}

		$output .= '</div>';

		return $output;
	}
}

/*
 * This theme supports custom background color and image, and here
 * we also set up the default background color.
 */
add_theme_support( 'custom-background', array(
	'default-color'       => 'fff',
	'default-image'       => '%1$s/images/background.png',
	'default-repeat'      => 'no-repeat',
	'default-position-x'  => 'center'
) );

/**
 * Add postMessage support for the Theme Customizer.
 */
function vh_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->add_section( 'color_scheme_section', array(
		'title'    => __( 'Color Scheme', 'vh' ),
		'priority' => 35,
	) );

	$wp_customize->add_setting( 'vh_color_scheme', array(
		'default'   => 'default-color-scheme',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( new Customize_Scheme_Control( $wp_customize, 'vh_color_scheme', array(
		'label'    => 'Choose color scheme',
		'section'  => 'color_scheme_section',
		'settings' => 'vh_color_scheme',
	) ) );
}
add_action( 'customize_register', 'vh_customize_register' );

/**
 * Binds CSS and JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function vh_customize_preview_js_css() {
	wp_enqueue_script( 'vh-customizer-js', get_template_directory_uri() . '/functions/admin/js/theme-customizer.js', array( 'jquery', 'customize-preview' ), '', true );
}
add_action( 'customize_preview_init', 'vh_customize_preview_js_css' );

if (class_exists('WP_Customize_Control')) {
	class Customize_Scheme_Control extends WP_Customize_Control {
		public $type = 'radio';

		public function render_content() {
		?>
			<style>

				/* Customizer */
				.input_hidden {
					position: absolute;
					left: -9999px;
				}

				.radio-images img {
					margin-right: 4px;
					border: 2px solid #fff;
				}

				.radio-images img.selected {
					border: 2px solid #888;
					border-radius: 5px;
				}

				.radio-images label {
					display: inline-block;
					cursor: pointer;
				}
			</style>
			<script type="text/javascript">
				jQuery('.radio-images input:radio').addClass('input_hidden');
				jQuery('.radio-images img').live('click', function() {
					jQuery('.radio-images img').removeClass('selected');
					jQuery(this).addClass('selected');
				});
			</script>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<div class="radio-images">
				<input type="radio" class="input_hidden" name="vh_color_scheme" <?php $this->link(); ?> id="default-color-scheme" value="default-color-scheme" />
				<label for="default-color-scheme">
					<img src="<?php echo get_template_directory_uri() . '/functions/admin/images/schemes/color-scheme-default.png'; ?>"<?php echo ( $this->value() == 'default-color-scheme' ) ? ' checked="checked" class="selected"' : ''; ?> style="width: 50px; height: 50px;" alt="Default Color Scheme" />
				</label>
				<input type="radio" class="input_hidden" name="vh_color_scheme" <?php $this->link(); ?> id="green-color-scheme" value="green-color-scheme" />
				<label for="green-color-scheme">
					<img src="<?php echo get_template_directory_uri() . '/functions/admin/images/schemes/color-scheme-green.png'; ?>"<?php echo ( $this->value() == 'green-color-scheme' ) ? ' checked="checked" class="selected"' : ''; ?> style="width: 50px; height: 50px;" alt="Green Color Scheme" />
				</label>
				<input type="radio" class="input_hidden" name="vh_color_scheme" <?php $this->link(); ?> id="red-color-scheme" value="red-color-scheme" />
				<label for="red-color-scheme">
					<img src="<?php echo get_template_directory_uri() . '/functions/admin/images/schemes/color-scheme-red.png'; ?>"<?php echo ( $this->value() == 'red-color-scheme' ) ? ' checked="checked" class="selected"' : ''; ?> style="width: 50px; height: 50px;" alt="Red Color Scheme" />
				</label>
				<input type="radio" class="input_hidden" name="vh_color_scheme" <?php $this->link(); ?> id="yellow-color-scheme" value="yellow-color-scheme" />
				<label for="yellow-color-scheme">
					<img src="<?php echo get_template_directory_uri() . '/functions/admin/images/schemes/color-scheme-yellow.png'; ?>"<?php echo ( $this->value() == 'yellow-color-scheme' ) ? ' checked="checked" class="selected"' : ''; ?> style="width: 50px; height: 50px;" alt="Yellow Color Scheme" />
				</label>
				<input type="radio" class="input_hidden" name="vh_color_scheme" <?php $this->link(); ?> id="gray-color-scheme" value="gray-color-scheme" />
				<label for="gray-color-scheme">
					<img src="<?php echo get_template_directory_uri() . '/functions/admin/images/schemes/color-scheme-gray.png'; ?>"<?php echo ( $this->value() == 'gray-color-scheme' ) ? ' checked="checked" class="selected"' : ''; ?> style="width: 50px; height: 50px;" alt="Gray Color Scheme" />
				</label>
			</div>
		<?php
		}
	}
}

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function vh_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
			'name'     				=> 'Environmental Functionality', // The plugin name
			'slug'     				=> 'environmental-plugin', // The plugin slug (typically the folder name)
			'source'   				=> get_stylesheet_directory() . '/functions/tgm-activation/plugins/environmental-plugin.zip', // The plugin source
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '1.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'               => 'WPBakery Visual Composer', // The plugin name
			'slug'               => 'js_composer', // The plugin slug (typically the folder name)
			'source'             => get_stylesheet_directory() . '/functions/tgm-activation/plugins/js_composer.zip', // The plugin source
			'required'           => true, // If false, the plugin is only 'recommended' instead of required
			'version'            => '4.5.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url'       => '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'     				=> 'Slider Revolution', // The plugin name
			'slug'     				=> 'revslider', // The plugin slug (typically the folder name)
			'source'   				=> get_stylesheet_directory() . '/functions/tgm-activation/plugins/revslider.zip', // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '4.6.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'     				=> 'Newsletter', // The plugin name
			'slug'     				=> 'newsletter', // The plugin slug (typically the folder name)
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '3.6.7', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'     				=> 'The Events Calendar', // The plugin name
			'slug'     				=> 'the-events-calendar', // The plugin slug (typically the folder name)
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '3.9.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'     				=> 'Contact Form 7', // The plugin name
			'slug'     				=> 'contact-form-7', // The plugin slug (typically the folder name)
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '4.0.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		)
	);

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'       		=> 'vh',         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', 'vh' ),
			'menu_title'                       			=> __( 'Install Plugins', 'vh' ),
			'installing'                       			=> __( 'Installing Plugin: %s', 'vh' ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', 'vh' ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', 'vh' ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'vh' ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'vh' ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'vh_register_required_plugins' );

function vh_vcSetAsTheme() {
	vc_set_as_theme( true );
}
add_action( 'vc_before_init', 'vh_vcSetAsTheme' );