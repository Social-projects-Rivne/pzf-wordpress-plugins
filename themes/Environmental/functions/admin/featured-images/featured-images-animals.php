<?php
if( !class_exists( 'vhanimalsFeaturedImages' ) ) {
	
	class vhanimalsFeaturedImages {
		
		private $id            = '';
		private $post_type     = '';
		private $labels        = array();
		
		private $metabox_id    = '';
		
		private $post_meta_key = '';
		
		private $nonce         = '';

		private $default_labels     = array(
			'name'   => 'Featured Image 2',
			'set'    => 'Set featured image 2',
			'remove' => 'Remove featured image 2',
			'use'    => 'Use as featured image 2',
		);
		
		private $default_args       = array(
			'id'        => 'featured-image-2',
			'post_type' => 'page',
		);
		
		/**
		 * Initialize the plugin
		 * 
		 * @param array $args 
		 * @return void
		 */
		public function __construct( $args ) {
			$this->labels = wp_parse_args( $args['labels'], $this->default_labels );
			unset( $args['labels'] );
			$args                = wp_parse_args( $args, $this->default_args );
			$this->id            = $args['id'];
			$this->post_type     = $args['post_type'];
			
			$this->metabox_id    = $this->id.'_'.$this->post_type;
			
			$this->post_meta_key = 'kd_'.$this->id.'_'.$this->post_type.'_id';
			
			$this->nonce         = 'mfi-'.$this->id.$this->post_type;
			
			if( !current_theme_supports( 'post-thumbnails' ) ) {
				add_theme_support( 'post-thumbnails' );
			}

			add_action( 'admin_init', array( &$this, 'animals_kd_admin_init' ) );
			add_action( 'add_meta_boxes', array( &$this, 'animals_kd_add_meta_box' ) );
			add_filter( 'attachment_fields_to_edit', array( &$this, 'animals_kd_add_attachment_field' ), 11, 2 );

			add_action( 'wp_ajax_set-MuFeaImg-'.$this->id.'-'.$this->post_type, array( &$this, 'animals_kd_ajax_set_image' ) );
			
			add_action( 'delete_attachment', array( &$this, 'animals_kd_delete_attachment' ) );

		}
		
		/**
		 * Add admin-Javascript
		 * 
		 * @return void 
		 */
		public function animals_kd_admin_init() {
			wp_enqueue_script(
					'kd-multiple-featured-images',
					get_template_directory_uri().'/functions/admin/featured-images/js/kd-admin.js',
					'jquery'
			);
		}
 
		/**
		 * Add admin metabox for choosing additional featured images
		 * 
		 * @return void 
		 */
		public function animals_kd_add_meta_box() {
			add_meta_box(
				$this->metabox_id,
				$this->labels['name'],
				array( $this, 'animals_kd_meta_box_content' ),
				$this->post_type,
				'side',
				'low'
			);
		}

		/**
		 * Output the metabox content
		 * 
		 * @global object $post 
		 * @return void
		 */
		public function animals_kd_meta_box_content() {
			global $post;
			
			$image_id = get_post_meta( 
				$post->ID,
				$this->post_meta_key,
				true
			);
			
		   echo $this->animals_kd_meta_box_output( $image_id );
		}

		/**
		 * Generate the metabox content
		 * 
		 * @global int $post_ID
		 * @param int $image_id
		 * @return string 
		 */
		public function animals_kd_meta_box_output( $image_id = NULL ) {
			global $post_ID;
			
			$output = '';
			
			$setImageLink = sprintf(
					'<p class="hide-if-no-js"><a title="%2$s" href="%1$s" id="kd_%3$s" class="thickbox">%%s</a></p>',
					get_upload_iframe_src( 'image' ),
					$this->labels['set'],
					$this->id
			);
			
			if( $image_id && get_post( $image_id ) ) {
				$nonce_field = wp_create_nonce( $this->nonce.$post_ID );
				
				$thumbnail = wp_get_attachment_image( $image_id, array( 266, 266 ) );
				$output.= sprintf( $setImageLink, $thumbnail );
				$output.= '<p class="hide-if-no-js">';
				$output.= sprintf(
						'<a href="#" id="remove-%1$s-image" onclick="kdMuFeaImgRemove( \'%1$s\', \'%2$s\', \'%3$s\' ); return false;">',
						$this->id,
						$this->post_type,
						$nonce_field
				);
				$output.= $this->labels['remove'];
				$output.= '</a>';
				$output.= '</p>';
				
				return $output;
			}
			else {
				return sprintf( $setImageLink, $this->labels['set'] );
			}
				
		}
		
		/**
		 * Create a new field in the image upload form
		 * 
		 * @param string $form_fields
		 * @param object $post
		 * @return string 
		 */
		public function animals_kd_add_attachment_field( $form_fields, $post ) {
			$calling_id = 0;
			if( isset( $_GET['post_id'] ) ) {
				$calling_id = absint( $_GET['post_id'] );
			}
			elseif( isset( $_POST ) && count( $_POST ) ) {
				$calling_id = $post->post_parent;
			}
			
			$calling_post = get_post( $calling_id );
			
			if( is_null( $calling_post ) || $calling_post->post_type != $this->post_type ) {
				return $form_fields;
			}
			
			$nonce_field = wp_create_nonce( $this->nonce.$calling_id );

			$output = sprintf(
					'<a href="#" id="%1$s-featuredimage" onclick="kdMuFeaImgSet( %3$s, \'%1$s\', \'%2$s\', \'%6$s\' ); return false;">%5$s</a>',
					$this->id,
					$this->post_type,
					$post->ID,
					$this->labels['name'],
					$this->labels['use'],
					$nonce_field
			);
			
			$form_fields['MuFeaImg-'.$this->id.'-'.$this->post_type] = array(
				'label' => $this->labels['name'],
				'input' => 'html',
				'html'  => $output
			);
			
			return $form_fields;            
		}
		
		/**
		 * Ajax function: set and delete featured image
		 * 
		 * @global int $post_ID 
		 * @return void
		 */
		public function animals_kd_ajax_set_image() {
			global $post_ID;
			
			$post_ID = intval( $_POST['post_id'] );
			
			if( !current_user_can( 'edit_post', $post_ID ) ) {
				die( '-1' );
			}
			
			$thumb_id = intval( $_POST['thumbnail_id'] );
			
			check_ajax_referer( $this->nonce.$post_ID );
			
			if( $thumb_id == '-1' ) {
				delete_post_meta( $post_ID, $this->post_meta_key );
				
				die( $this->animals_kd_meta_box_output( NULL ) );
			}
			
			if( $thumb_id && get_post( $thumb_id) ) {
				$thumb_html = wp_get_attachment_image( $thumb_id, 'thumbnail' );
				
				if( !empty( $thumb_html ) ) {
					update_post_meta( $post_ID, $this->post_meta_key, $thumb_id );
					
					die( $this->animals_kd_meta_box_output( $thumb_id ) );
				}
			}
			
			die( '0' );
			
		}
		
		/**
		 * Delete custom featured image if attachmet is deleted
		 * 
		 * @global object $wpdb
		 * @param int $post_id 
		 * @return void
		 */
		public function animals_kd_delete_attachment( $post_id ) {
			global $wpdb;

			$wpdb->query( 
					$wpdb->prepare( 
							"DELETE FROM $wpdb->postmeta WHERE meta_key = '%s' AND meta_value = %d", 
							$this->post_meta_key, 
							$post_id 
					)
			);
		}
		
		/**
		 * Retrieve the id of the featured image
		 * 
		 * @global object $post
		 * @param string $image_id
		 * @param string $post_type
		 * @param int $post_id
		 * @return int 
		 */
		public static function animals_get_featured_image_id( $image_id, $post_type, $post_id = NULL) {
			global $post;
			
			if( is_null( $post_id ) ) {
				$post_id = get_the_ID();
			}
			
			return get_post_meta( $post_id, "kd_{$image_id}_{$post_type}_id", true);
		}

		/**
		 * Return the featured image url
		 * 
		 * @param string $image_id
		 * @param string $post_type
		 * @param int $post_id
		 * @return string 
		 */
		public static function animals_get_featured_image_url( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
			$id = self::animals_get_featured_image_id( $image_id, $post_type, $post_id);
			
			if( $size != 'full' ) {
				$url = wp_get_attachment_image_src( $id, $size );
				$url = $url[0];
			}
			else {
				$url = wp_get_attachment_url( $id );
			}

			return $url;
		}
		
		/**
		 * Return the featured image html output
		 * 
		 * @param string $image_id
		 * @param string $post_type
		 * @param string $size
		 * @param int $post_id
		 * @return string
		 */
		public static function animals_get_the_featured_image( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
			$id = self::animals_get_featured_image_id( $image_id, $post_type, $post_id);

			$output = '';
			
			if( $id ) {
				$output = wp_get_attachment_image(
						$id,
						$size,
						false
				);
			}
			
			return $output;
		}
		
		/**
		 * Output the featured image html output
		 * 
		 * @param string $image_id
		 * @param string $post_type
		 * @param string $size
		 * @param int $post_id 
		 * @return void
		 */
		public static function animals_the_featured_image( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
			echo self::animals_get_the_featured_image( $image_id, $post_type, $size, $post_id );
		}
	}
}

function animals_kd_mfi_get_featured_image_id( $image_id, $post_type, $post_id = NULL ) {
	return vhanimalsFeaturedImages::animals_get_featured_image_id( $image_id, $post_type, $post_id );
}

function animals_kd_mfi_get_featured_image_url( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
	return vhanimalsFeaturedImages::animals_get_featured_image_url( $image_id, $post_type, $size, $post_id );
}

function animals_kd_mfi_get_the_featured_image( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
	return vhanimalsFeaturedImages::animals_get_the_featured_image( $image_id, $post_type, $size, $post_id );
}

function animals_kd_mfi_the_featured_image( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
	return vhanimalsFeaturedImages::animals_the_featured_image( $image_id, $post_type, $size, $post_id );
}