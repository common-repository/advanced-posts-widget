<?php

/**
* Advanced_Posts_Widget_Fields Class
*
* Handles generation of widget form fields.
* All methods are static, this is basically a namespacing class wrapper.
*
* @package Advanced_Posts_Widget
* @subpackage Advanced_Posts_Widget_Fields
*
* @since 1.0
*/

class Advanced_Posts_Widget_Fields
{

	public function __construct(){}


	/**
	 * Loads fields for a specific fieldset for widget form
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $fieldset Name (slug) of fieldset.
	 * @param array  $fields   Fields to load.
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function load_fieldset( $fieldset = 'general', $fields, $instance, $widget )
	{
		if( ! is_array( $fields ) ) {
			return;
		}

		$keys        = array_keys( $fields );
		$first_field = reset( $keys );
		$last_field  = end( $keys );

		ob_start();

		foreach ( $fields as $name => $field ) {

			if ( $first_field === $name ) {
				do_action( "apw_form_before_fields_{$fieldset}", $instance, $widget );
			}

			do_action( "apw_form_before_field_{$name}", $instance, $widget );

			// output the actual field
			echo apply_filters( "apw_form_field_{$name}", $field, $instance, $widget ) . "\n";

			do_action( "apw_form_after_field_{$name}", $instance, $widget );

			if ( $last_field === $name ) {
				do_action( "apw_form_after_fields_{$fieldset}", $instance, $widget );
			}

		}

		$fieldset = ob_get_clean();

		echo $fieldset;
	}


	/**
	 * Build section header for widget form
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $fieldset Slug of fieldset.
	 * @param array  $title    Name of fieldset.
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_section_header( $fieldset = 'general', $title = 'General Settings', $instance, $widget )
	{
		ob_start();
		?>

		<div class="apw-section-top" data-fieldset="<?php echo $fieldset; ?>">
			<div class="apw-top-action">
				<a class="apw-action-indicator hide-if-no-js" data-fieldset="<?php echo $fieldset; ?>" href="#"></a>
			</div>
			<div class="apw-section-title">
				<h4 class="apw-section-heading" data-fieldset="<?php echo $fieldset; ?>">
					<?php printf( __( '%s', 'advanced-posts-widget' ), $title ); ?>
				</h4>
			</div>
		</div>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: title
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_title( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<label for="<?php echo $widget->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'advanced-posts-widget' ); ?></label>
			<input class="widefat" id="<?php echo $widget->get_field_id( 'title' ); ?>" name="<?php echo $widget->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: post_type
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_post_type( $instance, $widget )
	{
		$post_types = Advanced_Posts_Widget_Utils::get_apw_post_types();
		if( ! $post_types ){
			return '';
		}
		ob_start();
		?>

		<p>
			<label for="<?php echo $widget->get_field_id('post_type'); ?>">
				<?php _e( 'Post Type:', 'advanced-posts-widget' ); ?>
			</label>
			<select name="<?php echo $widget->get_field_name('post_type'); ?>" id="<?php echo $widget->get_field_id('post_type'); ?>" class="widefat">
				<?php foreach( $post_types as $query_var => $label  ) { ?>
					<option value="<?php echo esc_attr( $query_var ); ?>" <?php selected( $instance['post_type'] , $query_var ); ?>><?php echo esc_html( $label ); ?></option>
				<?php } ?>
			</select>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: number
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_number( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<label for="<?php echo $widget->get_field_id( 'number' ); ?>">
				<?php _e( 'Number of Posts to Show:', 'advanced-posts-widget' ); ?>
			</label>
			<input class="widefat apw-number" id="<?php echo $widget->get_field_id( 'number' ); ?>" name="<?php echo $widget->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo absint( $instance['number'] ); ?>" size="3" />
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: orderby
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_orderby( $instance, $widget )
	{
		$_params = self::get_orderby_parameters();
		ob_start();
		?>

		<p>
			<label for="<?php echo $widget->get_field_id('orderby'); ?>">
				<?php _e( 'Order By:', 'advanced-posts-widget' ); ?>
			</label>
			<select name="<?php echo $widget->get_field_name('orderby'); ?>" id="<?php echo $widget->get_field_id('orderby'); ?>" class="widefat">
				<?php foreach( $_params as $query_var => $label  ) { ?>
					<option value="<?php echo esc_attr( $query_var ); ?>" <?php selected( $instance['orderby'] , $query_var ); ?>><?php echo esc_html( $label ); ?></option>
				<?php } ?>
			</select>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Retrieves orderby parameters
	 *
	 * Use 'apw_allowed_orderby_params' to filter parameters that can be selected in the widget.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return array $params Filtered array of orderby parameters.
	 */
	public static function get_orderby_parameters()
	{
		$_orderby = array(
			'date'       => __( 'Published Date', 'advanced-posts-widget' ),
			'modified'   => __( 'Last Modified Date', 'advanced-posts-widget' ),
			'title'      => __( 'Title', 'advanced-posts-widget' ),
			'menu_order' => __( 'Menu Order', 'advanced-posts-widget' ),
			'rand'       => __( 'Random', 'advanced-posts-widget' ),
		);

		$params = apply_filters( 'apw_allowed_orderby_params', $_orderby );
		$params = Advanced_Posts_Widget_Utils::sanitize_select_array( $params );

		return $params;
	}


	/**
	 * Builds form field: order
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_order( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<label for="<?php echo $widget->get_field_id('order'); ?>">
				<?php _e( 'Order:', 'advanced-posts-widget' ); ?>
			</label>
			<select name="<?php echo $widget->get_field_name('order'); ?>" id="<?php echo $widget->get_field_id('order'); ?>" class="widefat">
				<option value="desc" <?php selected( $instance['order'] , 'desc' ); ?>><?php _e( 'Newer posts first', 'advanced-posts-widget' ); ?></option>
				<option value="asc" <?php selected( $instance['order'] , 'asc' ); ?>><?php _e( 'Older posts first', 'advanced-posts-widget' ); ?></option>
			</select>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: tax_term
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_tax_term( $instance, $widget )
	{
		ob_start();
		?>

		<?php
		$apw_taxonomies = Advanced_Posts_Widget_Utils::get_apw_taxonomies();

		if( count( $apw_taxonomies ) ) :
			foreach ( $apw_taxonomies as $name => $label ) {
				self::build_term_select( $name, $label, $instance, $widget );
			}
		endif;

		?>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds select drop down for form field: tax_term
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $taxonomy The registered name of the taxonomy. e.g., post_tag
	 * #param string $label    The common name of the taxonomy. e.g., Post Tag
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_term_select( $taxonomy, $label, $instance, $widget )
	{
		$args = apply_filters( 'apw_build_term_select_args', array( 'hide_empty' => 0, 'number' => 99 ) );
		$args['fields'] = 'all'; // don't allow override
		$_terms = get_terms( $taxonomy, $args );

		if( empty( $_terms )&& 'post_format' === $taxonomy ){
			$formats = get_theme_support( 'post-formats' );
			if ( is_array( $formats[0] ) ) {
				sort( $formats[0]);
				foreach( $formats[0] as $format ) :
					$_format = new stdClass();
					$_format->slug = $format;
					$_format->taxonomy = 'post_format';
					$_format->name = get_post_format_string( $format );
					$_terms[] = $_format;
				endforeach;
			}
		}

		if( empty( $_terms ) || is_wp_error( $_terms ) ) {
			return;
		}
		?>
		<p>
			<label for="<?php echo $widget->get_field_id( 'tax_term-' . $taxonomy ); ?>">
				<?php printf( __( '%s:', 'advanced-posts-widget' ), $label )?>
			</label>
			
			<select name="<?php echo $widget->get_field_name( 'tax_term' ) . '[' . $taxonomy . ']'; ?>" id="<?php echo $widget->get_field_id( 'tax_term-' . $taxonomy ); ?>" class="widefat">
				<option value></option>
				<?php
					foreach( $_terms as $_term ) {
						$selected = ( isset( $instance['tax_term'][$_term->taxonomy] ) && $_term->slug === $instance['tax_term'][$_term->taxonomy] ) ? 'selected="selected"' : '' ;
						printf( '<option data-term-slug="%1$s" data-term-tax="%2$s" value="%1$s" %3$s>%4$s</option>' . "\n",
							esc_attr( $_term->slug ),
							esc_attr( $_term->taxonomy ),
							$selected,
							sprintf( __( '%s', 'advanced-posts-widget' ), $_term->name )
						);
					}
				?>
			</select>
		</p>
		<?php
	}

	/**
	 * Builds form field: show_thumb
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_show_thumb( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<input class="checkbox" type="checkbox" id="<?php echo $widget->get_field_id( 'show_thumb' ); ?>" name="<?php echo $widget->get_field_name( 'show_thumb' ); ?>" <?php checked( $instance['show_thumb'], 1 ); ?>/>
			<label for="<?php echo $widget->get_field_id( 'show_thumb' ); ?>">
				<?php _e( 'Display Thumbnail?', 'advanced-posts-widget' ); ?>
			</label>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: thumb_size
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_thumb_size( $instance, $widget )
	{
		ob_start();
		?>
		<p class="apw-thumb-size-defaults">
			<label for="<?php echo $widget->get_field_id('thumb_size'); ?>">
				<?php _e( 'Choose Registered Image Size:', 'advanced-posts-widget' ); ?>
			</label>
			<?php self::build_img_select( $instance, $widget ); ?>
		</p>
		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds select drop down for form field: thumb_size
	 *
	 * @uses Advanced_Posts_Widget_Utils::get_apw_image_sizes()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 *
	 * @return string <select> drop down for widget form
	 */
	public static function build_img_select( $instance, $widget )
	{
		$sizes = Advanced_Posts_Widget_Utils::get_apw_image_sizes( $fields = 'all' );

		if( count( $sizes ) ) : ?>

			<select name="<?php echo $widget->get_field_name('thumb_size'); ?>" id="<?php echo $widget->get_field_id('thumb_size'); ?>" class="widefat">
				<option value></option>
				<?php foreach( $sizes as $name => $size  ) {
					$width = absint( $size['width'] );
					$height = absint( $size['height'] );
					$dimensions = ' (' . $width . ' x ' . $height . ')';
					printf( '<option data-width="%1$s" data-height="%2$s" value="%3$s" %4$s>%5$s%6$s</option>' . "\n",
						$width,
						$height,
						esc_attr( $name ),
						selected( $instance['thumb_size'] , $name, false ),
						esc_html( $size['name'] ),
						$dimensions
					);
				} ?>
			</select>

		<?php endif;
	}


	/**
	 * Builds form fields: thumb_size_w / thumb_size_h
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_thumb_custom( $instance, $widget )
	{
		ob_start();
		?>
		<div class="apw-thumb-size-wrap">

			<p>
				<label><?php _e( 'Set Custom Size:', 'advanced-posts-widget' ); ?></label><br />
				<label class="apw-thumbsize" for="<?php echo $widget->get_field_id( 'thumb_size_w' ); ?>">
					<?php _e( 'Width:', 'advanced-posts-widget' ); ?>
				</label>
				<input class="small-text apw-thumb-size apw-thumb-width" id="<?php echo $widget->get_field_id( 'thumb_size_w' ); ?>" name="<?php echo $widget->get_field_name( 'thumb_size_w' ); ?>" type="number" value="<?php echo absint( $instance['thumb_size_w'] ); ?>" />

				<br />

				<label class="apw-thumbsize" for="<?php echo $widget->get_field_id( 'thumb_size_h' ); ?>">
					<?php _e( 'Height:', 'advanced-posts-widget' ); ?>
				</label>
				<input class="small-text apw-thumb-size apw-thumb-height" id="<?php echo $widget->get_field_id( 'thumb_size_h' ); ?>" name="<?php echo $widget->get_field_name( 'thumb_size_h' ); ?>" type="number" value="<?php echo absint( $instance['thumb_size_h'] ); ?>" />
			</p>

			<p>
				<?php _e( 'Preview:', 'advanced-posts-widget' ); ?><br />
				<span class="apw-preview-container">
					<span class="apw-avatar-preview" style="font-size: <?php echo absint( $instance['thumb_size_h'] ); ?>px; height:<?php echo absint( $instance['thumb_size_h'] ); ?>px; width:<?php echo absint( $instance['thumb_size_w'] ); ?>px">
						<i class="apw-avatar dashicons dashicons-format-image"></i>
					</span>
				</span>
			</p>

		</div>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: show_excerpt
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_show_excerpt( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<input id="<?php echo $widget->get_field_id( 'show_excerpt' ); ?>" name="<?php echo $widget->get_field_name( 'show_excerpt' ); ?>" type="checkbox" <?php checked( $instance['show_excerpt'], 1 ); ?> />
			<label for="<?php echo $widget->get_field_id( 'show_excerpt' ); ?>">
				<?php _e( 'Display Post Excerpt?', 'advanced-posts-widget' ); ?>
			</label>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: excerpt_length
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_excerpt_length( $instance, $widget )
	{
		ob_start();
		?>

		<p class="apw-excerpt-size-wrap">
			<label for="<?php echo $widget->get_field_id( 'excerpt_length' ); ?>">
				<?php _e( 'Excerpt Length:', 'advanced-posts-widget' ); ?>
			</label>
			<input class="widefat apw-excerpt-length" id="<?php echo $widget->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $widget->get_field_name( 'excerpt_length' ); ?>" type="number" step="1" min="0" value="<?php echo absint( $instance['excerpt_length'] ); ?>" />

			<label class="apw-preview-l">
				<?php _e( 'Preview:', 'advanced-posts-widget' ); ?>
			</label>

			<span class="apw-excerpt-preview">
				<span class="apw-excerpt"><?php echo wp_trim_words( Advanced_Posts_Widget_Utils::sample_excerpt(), 15, '&hellip;' ); ?></span>
			</span>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: list_style
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_list_style( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<label for="<?php echo $widget->get_field_id('list_style'); ?>">
				<?php _e( 'List Format:', 'advanced-posts-widget' ); ?>
			</label>
			<select name="<?php echo $widget->get_field_name('list_style'); ?>" id="<?php echo $widget->get_field_id('list_style'); ?>" class="widefat">
				<option value="ul" <?php selected( $instance['list_style'] , 'ul' ); ?>><?php _e( 'Unordered List (ul)', 'advanced-posts-widget' ); ?></option>
				<option value="ol" <?php selected( $instance['list_style'] , 'ol' ); ?>><?php _e( 'Ordered List (ol)', 'advanced-posts-widget' ); ?></option>
				<option value="div" <?php selected( $instance['list_style'] , 'div' ); ?>><?php _e( 'Div (div)', 'advanced-posts-widget' ); ?></option>
			</select>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: item_format
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_item_format( $instance, $widget )
	{
		ob_start();
		?>

		<hr />
		
		<p>
			<?php _e( 'List Item Format:', 'advanced-posts-widget' ); ?><br />
			<label>
				<input class="radio" id="<?php echo $widget->get_field_id( 'item_format' ); ?>" name="<?php echo $widget->get_field_name( 'item_format' ); ?>" type="radio" value="html5" <?php checked( $instance['item_format'], 'html5'); ?>/>
				HTML5 &nbsp;
			</label>
			<label>
				<input class="radio" id="<?php echo $widget->get_field_id( 'item_format' ); ?>" name="<?php echo $widget->get_field_name( 'item_format' ); ?>" type="radio" value="xhtml" <?php checked( $instance['item_format'], 'xhtml'); ?>/>
				XHTML
			</label>
		</p>
		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: show_date
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_show_date( $instance, $widget )
	{
		ob_start();
		?>

		<hr />

		<p>
			<input id="<?php echo $widget->get_field_id( 'show_date' ); ?>" name="<?php echo $widget->get_field_name( 'show_date' ); ?>" type="checkbox" <?php checked( $instance['show_date'], 1 ); ?> />
			<label for="<?php echo $widget->get_field_id( 'show_date' ); ?>">
				<?php _e( 'Display Post Date?', 'advanced-posts-widget' ); ?>
			</label>
		</p>
		
		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: date_format
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_date_format( $instance, $widget )
	{
		$date_formats = array_unique( apply_filters( 'date_formats', array( __( 'F j, Y' ), 'Y-m-d', 'm/d/Y', 'd/m/Y' ) ) );

		ob_start();
		?>

		<p>
			<?php _e( 'Date Format:', 'advanced-posts-widget' ); ?><br />
			<?php
			foreach( $date_formats as $format ) {
				$checked = ( isset( $instance['date_format'] ) && $format === $instance['date_format'] ) ? ' checked="checked"' : '' ;
				printf( '<label class="apw-radio apw-radio-block"><input class="radio" id="%1$s" name="%2$s" type="radio" value="%3$s"%4$s /><span class="apw-date-time-text date-time-text format-i18n">%5$s</span><code>%6$s</code></label>' . "\n",
					$widget->get_field_id( 'date_format' ),   // id
					$widget->get_field_name( 'date_format' ), // name
					esc_attr( $format ),                      // value
					$checked,                                 // checked
					date_i18n( $format ),                     // sample text
					esc_html( $format )                       // format string
				);
			}
			?>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: css_default
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_css_default( $instance, $widget )
	{
		ob_start();
		?>
		
		<p>
			<input id="<?php echo $widget->get_field_id( 'css_default' ); ?>" name="<?php echo $widget->get_field_name( 'css_default' ); ?>" type="checkbox" <?php checked( $instance['css_default'], 1 ); ?> />
			<label for="<?php echo $widget->get_field_id( 'css_default' ); ?>">
				<?php _e( 'Use Default Styles?', 'advanced-posts-widget' ); ?>
			</label>
		</p>
		
		<?php
		$field = ob_get_clean();

		return $field;
	}

}
