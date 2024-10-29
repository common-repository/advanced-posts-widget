<?php

/**
 * Advanced_Posts_Widget_Utils Class
 *
 * All methods are static, this is basically a namespacing class wrapper.
 *
 * @package Advanced_Posts_Widget
 * @subpackage Advanced_Posts_Widget_Utils
 *
 * @since 1.0
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


/**
 * Advanced_Posts_Widget_Utils Class
 *
 * Group of utility methods for use by Advanced_Posts_Widget
 *
 * @since 1.0
 */
class Advanced_Posts_Widget_Utils
{

	/**
	 * Generates path to plugin root
	 *
	 * @uses WordPress plugin_dir_path()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return string $path Path to plugin root.
	 */
	public static function get_apw_path()
	{
		$path = plugin_dir_path( ADVANCED_POSTS_WIDGET_FILE );
		return $path;
	}


	/**
	 * Generates path to subdirectory of plugin root
	 *
	 * @see Advanced_Posts_Widget_Utils::get_apw_path()
	 *
	 * @uses WordPress trailingslashit()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $directory The name of the requested subdirectory.
	 *
	 * @return string $sub_path Path to requested sub directory.
	 */
	public static function get_apw_sub_path( $directory )
	{
		if( ! $directory ){
			return false;
		}

		$path = self::get_apw_path();

		$sub_path = $path . trailingslashit( $directory );

		return $sub_path;
	}


	/**
	 * Generates url to plugin root
	 *
	 * @uses WordPress plugin_dir_url()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return string $url URL of plugin root.
	 */
	public static function get_apw_url()
	{
		$url = plugin_dir_url( ADVANCED_POSTS_WIDGET_FILE );
		return $url;
	}


	/**
	 * Generates url to subdirectory of plugin root
	 *
	 * @see Advanced_Posts_Widget_Utils::get_apw_url()
	 *
	 * @uses WordPress trailingslashit()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $directory The name of the requested subdirectory.
	 *
	 * @return string $sub_url URL of requested sub directory.
	 */
	public static function get_apw_sub_url( $directory )
	{
		if( ! $directory ){
			return false;
		}

		$url = self::get_apw_url();

		$sub_url = $url . trailingslashit( $directory );

		return $sub_url;
	}


	/**
	 * Generates basename to plugin root
	 *
	 * @uses WordPress plugin_basename()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return string $basename Filename of plugin root.
	 */
	public static function get_apw_basename()
	{
		$basename = plugin_basename( ADVANCED_POSTS_WIDGET_FILE );
		return $basename;
	}


	/**
	 * Sets default parameters
	 *
	 * Use 'apw_instance_defaults' filter to modify accepted defaults.
	 *
	 * @uses WordPress current_theme_supports()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return array $defaults The default values for the widget.
	 */
	public static function instance_defaults()
	{
		$_item_format = current_theme_supports( 'html5' ) ? 'html5' : 'xhtml';
		$_list_style = ( 'html5' == $_item_format ) ? 'div' : 'ul' ;

		$_defaults = array(
			'title'          => __( 'Recent Posts' ),
			'post_type'      => 'post',
			'number'         => 5,
			'order'          => 'desc',
			'orderby'        => 'date',
			'tax_term'       => '',
			'show_thumb'     => 1,
			'thumb_size'     => 0,
			'thumb_size_w'   => 55,
			'thumb_size_h'   => 55,
			'show_excerpt'   => 1,
			'excerpt_length' => 15,
			'item_format'    => $_item_format,
			'list_style'     => $_list_style,
			'show_date'      => 0,
			'date_format'    => 'F j, Y',
			'css_default'    => 0,
		);

		$defaults = apply_filters( 'apw_instance_defaults', $_defaults );

		return $defaults;
	}


	/**
	 * Builds a sample excerpt
	 *
	 * Use 'apw_sample_excerpt' filter to modify excerpt text.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return string $excerpt Excerpt text.
	 */
	public static function sample_excerpt()
	{
		$excerpt = __( 'The point of the foundation is to ensure free access, in perpetuity, to the software projects we support. People and businesses may come and go, so it is important to ensure that the source code for these projects will survive beyond the current contributor base, that we may create a stable platform for web publishing for generations to come. As part of this mission, the Foundation will be responsible for protecting the WordPress, WordCamp, and related trademarks. A 501(c)3 non-profit organization, the WordPress Foundation will also pursue a charter to educate the public about WordPress and related open source software.');
		return apply_filters( 'apw_sample_excerpt', $excerpt );
	}


	/**
	 * Retrieves post types to use in widget
	 *
	 * Use 'apw_get_post_type_args' to filter arguments for retrieving post types.
	 * Use 'apw_allowed_post_types' to filter post types that can be selected in the widget.
	 *
	 * @see Advanced_Posts_Widget_Utils::sanitize_select_array()
	 *
	 * @uses WordPress get_post_types()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return array $types Allowed post types.
	 */
	public static function get_apw_post_types()
	{
		$args = apply_filters( 'apw_get_post_type_args', array( 'public' => true) );
		$post_types = get_post_types( $args, 'objects' );

		if( empty( $post_types ) ){
			return false;
		}

		$_ptypes = array();
		$_ptypes['all'] = __('All');

		foreach( $post_types as $post_type ){
			$query_var = ( ! $post_type->query_var ) ? $post_type->name : $post_type->query_var ;
			$_ptypes[ $query_var ] = $post_type->labels->singular_name;
		}

		$types = apply_filters( 'apw_allowed_post_types', $_ptypes );
		$types = self::sanitize_select_array( $types );

		return $types;
	}


	/**
	 * Retrieves taxonomies associated with allowed post types
	 *
	 * Use 'apw_allowed_taxonomies' to filter taxonomies that can be selected in the widget.
	 *
	 * @see Advanced_Posts_Widget_Utils::get_apw_post_types()
	 * @see Advanced_Posts_Widget_Utils::sanitize_select_array()
	 *
	 * @uses WordPress get_object_taxonomies()
	 * @uses WordPress get_taxonomy()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return array $taxes Allowed taxonomies.
	 */
	public static function get_apw_taxonomies()
	{
		$_ptaxes = array();

		$post_types = self::get_apw_post_types();

		if( is_array( $post_types ) ) {

			if( ! empty( $post_types['all'] ) ) {
				unset( $post_types['all'] );
			}

			$_post_type_taxes = array();

			// get all taxonomies associated with our allowed post_types
			foreach( $post_types as $name => $label ) {
				$_otaxes = get_object_taxonomies( $name );

				if( count( $_otaxes ) ) {
					foreach ( $_otaxes as $_otax ) {
						$_post_type_taxes[] = $_otax;
					}
				}

			}

			$_post_type_taxes = array_unique( $_post_type_taxes );

			if( count( $_post_type_taxes ) ){
				foreach ( $_post_type_taxes as $_post_type_tax) {
					$_taxonomy = get_taxonomy( $_post_type_tax );
					if( $_taxonomy ) {
						$_ptaxes[ $_post_type_tax ] = $_taxonomy->labels->singular_name;
					}
				}
			}
		}

		// post_format is a registered taxonomy, but may not be supported by the theme
		if( ! empty( $_ptaxes['post_format'] ) && ! current_theme_supports( 'post-formats' ) ) {
			unset( $_ptaxes['post_format'] );
		}

		// screw that, post_formats are for display, not classification
		if( ! empty( $_ptaxes['post_format'] ) ) {
			unset( $_ptaxes['post_format'] );
		}

		$taxes = apply_filters( 'apw_allowed_taxonomies', $_ptaxes );
		$taxes = self::sanitize_select_array( $taxes );

		return $taxes;
	}


	/**
	 * Retrieves registered image sizes
	 *
	 * Use 'apw_allowed_image_sizes' to filter image sizes that can be selected in the widget.
	 *
	 * @see Advanced_Posts_Widget_Utils::sanitize_select_array()
	 *
	 * @global $_wp_additional_image_sizes
	 *
	 * @uses get_intermediate_image_sizes()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return array $_sizes Filtered array of image sizes.
	 */
	public static function get_apw_image_sizes( $fields = 'name' )
	{
		global $_wp_additional_image_sizes;
		$wp_defaults = array( 'thumbnail', 'medium', 'medium_large', 'large' );

		$_sizes = get_intermediate_image_sizes();

		if( count( $_sizes ) ) {
			sort( $_sizes );
			$_sizes = array_combine( $_sizes, $_sizes );
		}

		$_sizes = apply_filters( 'apw_allowed_image_sizes', $_sizes );
		$sizes = self::sanitize_select_array( $_sizes );

		if( count( $sizes )&& 'all' === $fields ) {

			$image_sizes = array();
			asort( $sizes, SORT_NATURAL );

			foreach ( $sizes as $_size ) {
				if ( in_array( $_size, $wp_defaults ) ) {
					$image_sizes[$_size]['name']   = $_size;
					$image_sizes[$_size]['width']  = absint( get_option( "{$_size}_size_w" ) );
					$image_sizes[$_size]['height'] = absint(  get_option( "{$_size}_size_h" ) );
					$image_sizes[$_size]['crop']   = (bool) get_option( "{$_size}_crop" );
				} else if( isset( $_wp_additional_image_sizes[ $_size ] )  ) {
					$image_sizes[$_size]['name']   = $_size;
					$image_sizes[$_size]['width']  = absint( $_wp_additional_image_sizes[ $_size ]['width'] );
					$image_sizes[$_size]['height'] = absint( $_wp_additional_image_sizes[ $_size ]['height'] );
					$image_sizes[$_size]['crop']   = (bool) $_wp_additional_image_sizes[ $_size ]['crop'];
				}
			}

			$sizes = $image_sizes;

		};

		return $sizes;
	}


	/**
	 * Retrieves specific image size
	 *
	 * @see Advanced_Posts_Widget_Utils::get_apw_image_sizes()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return string  Name of image size.
	 *         array   Image size settings; name, width, height, crop.
	 *		   boolean False if size doesn't exist.
	 */
	public static function get_apw_image_size( $size = 'thumbnail', $fields = 'all' )
	{
		$sizes = self::get_apw_image_sizes( $_fields = 'all' );

		if( count( $sizes ) && isset( $sizes[$size] ) ) :
			if( 'all' === $fields ) {
				return $sizes[$size];
			} else {
				return $sizes[$size]['name'];
			}
		endif;

		return false;
	}


	/**
	 * Builds html for post thumbnail
	 *
	 * Use 'apw_post_thumb_class' to modify image classes.
	 * Use 'apw_post_thumbnail_html' to modify thumbnail output.
	 *
	 * @see Advanced_Posts_Widget_Utils::get_apw_image_size()
	 *
	 * @uses WordPres get_post()
	 * @uses WordPres has_post_thumbnail()
	 * @uses WordPres get_the_post_thumbnail()
	 * @uses WordPres the_title_attribute()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return
	 */
	public static function get_apw_post_thumbnail( $post = 0, $instance = array() )
	{
		$_post = get_post( $post );

		if ( empty( $_post ) ) {
			return '';
		}

		$_classes = array();
		$_classes[] = 'apw-post-image';
		$_classes[] = 'apw-alignleft';

		// was registered size selected?
		$_size = $instance['thumb_size'];

		// custom size entered
		if( empty( $_size ) ){
			$_w = absint( $instance['thumb_size_w'] );
			$_h = absint( $instance['thumb_size_h'] );
			$_size = "apw-thumbnail-{$_w}-{$_h}";
		}

		// check if the size is registered
		$_size_exists = self::get_apw_image_size( $_size );

		// no thumbnail
		// @todo placeholder?
		if( ! has_post_thumbnail( $_post ) ) {
			return '';
		}

		if( $_size_exists ){
			$_get_size = $_size;
			$_w = absint( $_size_exists['width'] );
			$_h = absint( $_size_exists['height'] );
			$_classes[] = "size-{$_size}";
		} else {
			$_get_size = array( $_w, $_h);
		}

		$classes = apply_filters( 'apw_post_thumb_class', $_classes, $_post, $instance );
		$classes = ( ! is_array( $classes ) ) ? (array) $classes : $classes ;
		$classes = array_map( 'sanitize_html_class', $classes );

		$class_str = implode( ' ', $classes );

		$_thumb = get_the_post_thumbnail(
			$_post,
			$_get_size,
			array(
				'class' => $class_str,
				'alt' => the_title_attribute( 'echo=0' )
				)
			);

		$thumb = apply_filters( 'apw_post_thumbnail_html', $_thumb, $_post, $instance );

		return $thumb;

	}




	/**
	 * Generates publish date
	 *
	 * Use 'get_apw_post_date' filter to modify post date before output.
	 *
	 * @uses WordPres get_the_date()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param object $post Post to display.
	 * @param array  $instance Widget instance.
	 *
	 * @return string $time Publish date.
	 */
	public static function get_apw_post_date( $post = 0, $instance = array() )
	{
		$post = get_post( $post );

		if ( empty( $post ) ) {
			return '';
		}

		$_time = get_the_date( $instance['date_format'], $post->ID );

		return apply_filters( 'get_apw_post_date', $_time, $post, $instance );
	}


	/**
	 * Generates unique post id based on widget instance and post (obj) ID
	 *
	 * Note: The output is not just the ID of the post. It includes the widget instance as well.
	 * This allows for multiple post lists to be created, each with unique IDs.
	 *
	 * Use 'apw_post_id' filter to modify post ID before output.
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @param object $post Post to display.
	 * @param array  $instance Widget instance.
	 *
	 * @return string $apw_post_id Filtered post ID.
	 */
	public static function get_apw_post_id( $post = 0, $instance = array() )
	{
		$post = get_post( $post );

		if ( empty( $post ) ) {
			return '';
		}

		$apw_post_id = $instance['widget_id'] . '-post-' . $post->ID;

		return apply_filters( 'apw_post_id', $apw_post_id, $post, $instance );
	}


	/**
	 * Generate post classes
	 *
	 * Use 'apw_post_class' filter to modify post classes before output.
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @param object $post     Post to display.
	 * @param array  $instance Widget instance.
	 *
	 * @return string $class_str CSS classes.
	 */
	public static function get_apw_post_class( $post = 0, $instance = array() )
	{
		$post = get_post( $post );

		if ( empty( $post ) ) {
			return '';
		}

		$type = ( empty( $post->post_type ) ) ? 'post' : $post->post_type;

		$_classes = array();
		$_classes[] = 'apw-post';
		$_classes[] = 'type-' . $type;
		$_classes[] = 'apw-type-' . $type;

		if ( $post->post_parent > 0 ) {
			$_classes[] = 'child-post';
			$_classes[] = 'parent-' . $post->post_parent;
		}

		$classes = apply_filters( 'apw_post_class', $_classes, $post, $instance );
		$classes = ( ! is_array( $classes ) ) ? (array) $classes : $classes ;
		$classes = array_map('sanitize_html_class', $classes);

		$class_str = implode(' ', $classes);

		return $class_str;
	}


	/**
	 * Retrieves post content
	 *
	 * Use 'apw_protected_post_notice to modify password-protected notice.
	 * Use 'apw_post_excerpt' to modify the text before output.
	 * Use 'apw_post_excerpt_length' to modify the text length before output.
	 * Use 'apw_post_excerpt_more' to modify the readmore text before output.
	 *
	 * Uses WordPress post_password_required()
	 * Uses WordPress strip_shortcodes()
	 * Uses WordPress wp_html_excerpt()
	 * Uses WordPress wp_trim_words()
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @param object $post     Post to display.
	 * @param array  $instance Widget instance.
	 * @param string $trim     Flag to trim by word or character.
	 *
	 * @return string $text Filtered post content.
	 */
	public static function get_apw_post_excerpt( $post = 0, $instance = array(), $trim = 'words' )
	{
		$post = get_post( $post );

		if ( empty( $post ) ) {
			return '';
		}

		if ( post_password_required() ) {
			$_protected = __( 'This is a protected post.' );
			return apply_filters( 'apw_protected_post_notice', $_protected );
		}

		$_text = $post->post_excerpt;

		if( '' === $_text ) {
			$_text = $post->post_content;
			$_text = strip_shortcodes( $_text );
			$_text = str_replace(']]>', ']]&gt;', $_text);
		}

		$text = apply_filters( 'apw_post_excerpt', $_text, $post, $instance );

		$_length = ( ! empty( $instance['excerpt_length'] ) ) ? absint( $instance['excerpt_length'] ) : 55 ;
		$length = apply_filters( 'apw_post_excerpt_length', $_length );

		$_aposiopesis = ( ! empty( $instance['excerpt_more'] ) ) ? $instance['excerpt_more'] : '&hellip;' ;
		$aposiopesis = apply_filters( 'apw_post_excerpt_more', $_aposiopesis );

		if( 'chars' === $trim ){
			$text = wp_html_excerpt( $text, $length, $aposiopesis );
		} else {
			$text = wp_trim_words( $text, $length, $aposiopesis );
		}

		return $text;
	}


	/**
	 * Stores a selected image size
	 *
	 * @see Advanced_Posts_Widget_Utils::build_field_thumb_custom()
	 *
	 * @uses WordPress sanitize_key()
	 * @uses WordPress update_option()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array $size Width and Height values of the selected size.
	 */
	public static function stick_image_size( $size )
	{

		$apw_img_sizes = get_option( 'apw_img_sizes' );

		$new_size = array();
		$new_size['width']  = absint( $size['width'] );
		$new_size['height'] = absint( $size['height'] );

		$name = 'apw-thumbnail-' . $new_size['width'] . '-' . $new_size['height'];
		$key = sanitize_key( $name );

		$new_size['name'] = $name;

		if ( ! is_array($apw_img_sizes) ) {
			$apw_img_sizes = array( $key => $new_size );
		} else {
			$apw_img_sizes[$key] = $new_size;
		}

		update_option( 'apw_img_sizes', $apw_img_sizes );
	}


	/**
	 * Removes a selected image size from the apw_img_sizes option
	 *
	 * @see Advanced_Posts_Widget_Utils::build_field_thumb_custom()
	 *
	 * @uses WordPress get_option()
	 * @uses WordPress sanitize_key()
	 * @uses WordPress update_option()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $name Name of the image size to remove.
	 */
	public function unstick_image_size( $name )
	{
		$apw_img_sizes = get_option( 'apw_img_sizes' );

		$key = sanitize_key( $name );

		if( ! $key ) {
			return;
		}

		if ( ! is_array( $apw_img_sizes ) ) {
			return;
		}

		if ( ! array_key_exists( $key, $apw_img_sizes ) ) {
			return;
		}

		unset( $apw_img_sizes[ $key ] );

		update_option( 'apw_img_sizes', $apw_img_sizes );

	}


	/**
	 * Retrieves a APW template file
	 *
	 * @see Advanced_Posts_Widget_Utils::get_apw_sub_path()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string  $file         Template file to search for.
	 * @param boolean $load         If true the template file will be loaded if it is found.
	 * @param boolean $require_once Whether to require_once or require. Default true. Has no effect if $load is false.
	 * @param array   $instance     Widget instance.
	 *
	 * @return string $located The template filename if one is located.
	 */
	public static function get_template( $file, $load = false, $require_once = true, $instance = array() )
	{
		$located = '';

		$template_name = "{$file}.php";
		$template_path = self::get_apw_sub_path('tpl');

		if ( file_exists( $template_path . $template_name ) ) {
			$located = $template_path . $template_name;
		}

		if ( $load && '' != $located ){
			if ( $require_once ) {
				require_once( $located );
			} else {
				require( $located );
			}
		}

		return $located;
	}


	/**
	 * Cleans array of keys/values used in select drop downs
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array   $options Values used for select options
	 * @param boolean $sort    Flag to sort the values alphabetically.
	 *
	 * @return array $options Sanitized values.
	 */
	public static function sanitize_select_array( $options, $sort = true )
	{
		$options = ( ! is_array( $options ) ) ? (array) $options : $options ;

		// Clean the values (since it can be filtered by other plugins)
		$options = array_map( 'esc_html', $options );

		// Flip to clean the keys (used as <option> values in <select> field on form)
		$options = array_flip( $options );
		$options = array_map( 'sanitize_key', $options );

		// Flip back
		$options = array_flip( $options );

		if( $sort ) {
			asort( $options );
		};

		return $options;
	}


	/**
	 * Adds a widget to the apw_use_css option
	 *
	 * If css_default option is selected in the widget, this will add a reference to that
	 * widget instance in the apw_use_css option.  The 'apw_use_css' option determines if the
	 * default stylesheet is enqueued on the front end.
	 *
	 * @uses WordPress get_option()
	 * @uses WordPress update_option()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $widget_id Widget instance ID.
	 */
	public static function stick_css( $widget_id )
	{
		$widgets = get_option( 'apw_use_css' );

		if ( ! is_array( $widgets ) ) {
			$widgets = array( $widget_id );
		}

		if ( ! in_array( $widget_id, $widgets ) ) {
			$widgets[] = $widget_id;
		}

		update_option('apw_use_css', $widgets);
	}


	/**
	 * Removes a widget from the apw_use_css option
	 *
	 * If css_default option is unselected in the widget, this will remove (if applicable) a
	 * reference to that widget instance in the apw_use_css option. The 'apw_use_css' option
	 * determines if the default stylesheet is enqueued on the front end.
	 *
	 * @uses WordPress get_option()
	 * @uses WordPress update_option()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $widget_id Widget instance ID.
	 */
	public static function unstick_css( $widget_id )
	{
		$widgets = get_option( 'apw_use_css' );

		if ( ! is_array( $widgets ) ) {
			return;
		}

		if ( ! in_array( $widget_id, $widgets ) ) {
			return;
		}

		$offset = array_search($widget_id, $widgets);

		if ( false === $offset ) {
			return;
		}

		array_splice( $widgets, $offset, 1 );

		update_option( 'apw_use_css', $widgets );
	}


	/**
	 * Runs a post query based on widget instance settings
	 *
	 * Use 'apw_widget_posts_query_args' to filter the post query args.
	 *
	 * @uses WordPress WP_Query()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current widget settings.
	 * @param object $widget   Widget Object.
	 *
	 * @return object $r WP_Query object.
	 */
	public static function get_apw_posts( $instance, $widget )
	{

		// post types
		$_post_types = ( ! is_array( $instance['post_type'] ) ) ? (array) $instance['post_type'] : $instance['post_type'] ;

		// taxonomies
		$_tax_query = '';
		$_tax_terms = array_filter( (array) $instance['tax_term'] );

		if( is_array( $_tax_terms ) && count( $_tax_terms ) ) {
			foreach( $_tax_terms as $_taxonomy => $_term ) {
				$_tax_query[] = array(
					'taxonomy' => $_taxonomy,
					'field' => 'slug',
					'terms' => (array) $_term,
				);
			}
			$_tax_query['relation'] = 'AND';
		}

		// query
		$_query_args = array(
			'post_type'           => $_post_types,
			'posts_per_page'      => absint( $instance['number'] ),
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'order'               => $instance['order'],
			'orderby'             => $instance['orderby'],
			'tax_query'           => $_tax_query,
		);

		$query_args = apply_filters( 'apw_widget_posts_query_args', $_query_args );

		$r = new WP_Query( $query_args );

		return $r;

	}


	/**
	* Prints link to default widget stylesheet
	 *
	 * Actual stylesheet is enqueued if the user selects to use default styles
	 *
	 * @see Widget_APW_Recent_Posts::widget()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array   $instance Current widget settings.
	 * @param object  $widget   Widget Object.
	 * @param boolean $echo     Flag to echo|return output.
	 *
	 * @return string $css_url Stylesheet link.
	 */
	public static function css_preview( $instance, $widget, $echo = true )
	{
		$_css_url =  self::get_apw_sub_url('css') . 'apw-defaults.css' ;

		$css_url = sprintf('<link rel="stylesheet" href="%s" type="text/css" media="all" />',
			esc_url( $_css_url )
		);

		if( $echo ) {
			echo $css_url;
		} else {
			return $css_url;
		}
	}




}