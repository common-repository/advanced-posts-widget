<?php
/**
 * Widget Form
 *
 * Builds out the html for the widget settings form.
 *
 * @uses Advanced_Posts_Widget_Fields::build_field_{name-of-field}() to generate the individual form fields.
 * @uses Advanced_Posts_Widget_Fields::load_fieldset() to output the actual fieldsets.
 * @uses Advanced_Posts_Widget_Fields::build_section_header() to output the header for each form section.
 *
 * @package Advanced_Posts_Widget
 *
 * @since 1.0
 */
?>

<div class="apw-widget-form">

	<div class="apw-section">

		<?php echo Advanced_Posts_Widget_Fields::build_section_header( $fieldset = 'general', $title = 'General Settings', $instance, $this ); ?>

		<fieldset data-fieldset-id="general" class="apw-settings apw-fieldset settings-general">
		
			<legend class="screen-reader-text"><span><?php _e('General Settings') ?></span></legend>
			
			<?php
			$_general_fields =  array(
				'title'     => Advanced_Posts_Widget_Fields::build_field_title( $instance, $this ),
				'post_type' => Advanced_Posts_Widget_Fields::build_field_post_type( $instance, $this ),
				'number'    => Advanced_Posts_Widget_Fields::build_field_number( $instance, $this ),
				'orderby'   => Advanced_Posts_Widget_Fields::build_field_orderby( $instance, $this ),
				'order'     => Advanced_Posts_Widget_Fields::build_field_order( $instance, $this ),
			);
			$general_fields = apply_filters( "apw_form_fields_general", $_general_fields, $instance, $this );

			Advanced_Posts_Widget_Fields::load_fieldset( 'general', $general_fields, $instance, $this );
			?>
		</fieldset>

	</div><!-- /.apw-section -->

	
	<div class="apw-section">
	
		<?php echo Advanced_Posts_Widget_Fields::build_section_header( $fieldset = 'filters', $title = 'Filters', $instance, $this ); ?>

		<fieldset data-fieldset-id="filters" class="apw-settings apw-fieldset settings-filters">
		
			<legend class="screen-reader-text"><span><?php _e('Filters') ?></span></legend>
			
			<?php
			$_intro = __( 'Use the following fields to limit your list to a certain category, tag, or custom taxonomy. Note: not all post types support all taxonomies.' );
			$intro = apply_filters( 'apw_intro_text_filters', $_intro );
			?>

			<div class="description apw-description">
				<?php echo wpautop( __( $intro ) ); ?>
			</div>

			<?php
			$_filters_fields =  array(
				'tax_term' => Advanced_Posts_Widget_Fields::build_field_tax_term( $instance, $this ),
			);
			$filters_fields = apply_filters( "apw_form_fields_filters", $_filters_fields, $instance, $this );

			Advanced_Posts_Widget_Fields::load_fieldset( 'filters', $filters_fields, $instance, $this );
			?>
		</fieldset>

	</div><!-- /.apw-section -->

	
	<div class="apw-section">
		
		<?php echo Advanced_Posts_Widget_Fields::build_section_header( $fieldset = 'thumbnails', $title = 'Post Thumbnail', $instance, $this ); ?>

		<fieldset data-fieldset-id="thumbnails" class="apw-settings apw-fieldset settings-thumbnails">
		
			<legend class="screen-reader-text"><span><?php _e('Post Thumbnail') ?></span></legend>
		
			<?php
			$_intro = __( "If you choose to display a thumbnail of the featured image, you can either select from an image size registered with your site, or set a custom size." );
			$intro = apply_filters( 'apw_intro_text_thumbnails', $_intro );
			?>

			<div class="description apw-description">
				<?php echo wpautop( __( $intro ) ); ?>
			</div>

			<?php
			$_thumbnail_fields =  array(
				'show_thumb'   => Advanced_Posts_Widget_Fields::build_field_show_thumb( $instance, $this ),
				'thumb_size'   => Advanced_Posts_Widget_Fields::build_field_thumb_size( $instance, $this ),
				'thumb_custom' => Advanced_Posts_Widget_Fields::build_field_thumb_custom( $instance, $this ),
			);
			$thumbnail_fields = apply_filters( "apw_form_fields_thumbnails", $_thumbnail_fields, $instance, $this );

			Advanced_Posts_Widget_Fields::load_fieldset( 'thumbnails', $thumbnail_fields, $instance, $this );
			?>
		</fieldset>

	</div><!-- /.apw-section -->


	<div class="apw-section">
		
		<?php echo Advanced_Posts_Widget_Fields::build_section_header( $fieldset = 'excerpts', $title = 'Post Excerpt', $instance, $this ); ?>

		<fieldset data-fieldset-id="excerpts" class="apw-settings apw-fieldset settings-excerpts">
		
			<legend class="screen-reader-text"><span><?php _e('Post Excerpt') ?></span></legend>
			
			<?php
			$_excerpt_fields =  array(
				'show_excerpt'   => Advanced_Posts_Widget_Fields::build_field_show_excerpt( $instance, $this ),
				'excerpt_length' => Advanced_Posts_Widget_Fields::build_field_excerpt_length( $instance, $this ),
			);
			$excerpt_fields = apply_filters( "apw_form_fields_excerpts", $_excerpt_fields, $instance, $this );

			Advanced_Posts_Widget_Fields::load_fieldset( 'excerpts', $excerpt_fields, $instance, $this );
			?>
		</fieldset>

	</div><!-- /.apw-section -->

	
	<div class="apw-section">
		
		<?php echo Advanced_Posts_Widget_Fields::build_section_header( $fieldset = 'format', $title = 'Format', $instance, $this ); ?>

		<fieldset data-fieldset-id="format" class="apw-settings apw-fieldset settings-format">
			
			<legend class="screen-reader-text"><span><?php _e('Format') ?></span></legend>
			
			<?php
			$current_theme_support = current_theme_supports( 'html5' ) ? 'HTML5' : 'XHTML';
			$_intro = __("If your current theme supports HTML5, you can display your posts list in semantic HTML5 markup.  Fallback is semantic standard HTML markup.");
			$intro = apply_filters( 'apw_intro_text_format', $_intro );
			?>

			<div class="description apw-description">
				<?php echo wpautop( __( $intro ) ); ?>
				<?php printf( '<p><b>%s: <code>%s</code></b></p>',
					__( 'Current theme supports' ),
					$current_theme_support
				); ?>
			</div>

			<?php
			$_format_fields =  array(
				'list_style'  => Advanced_Posts_Widget_Fields::build_field_list_style( $instance, $this ),
				'item_format' => Advanced_Posts_Widget_Fields::build_field_item_format( $instance, $this ),
				'show_date'   => Advanced_Posts_Widget_Fields::build_field_show_date( $instance, $this ),
				'date_format' => Advanced_Posts_Widget_Fields::build_field_date_format( $instance, $this ),
			);
			$format_fields = apply_filters( "apw_form_fields_format", $_format_fields, $instance, $this );

			Advanced_Posts_Widget_Fields::load_fieldset( 'format', $format_fields, $instance, $this );
			?>
		</fieldset>
		
	</div><!-- /.apw-section -->
	
	
	<div class="apw-section">

		<?php echo Advanced_Posts_Widget_Fields::build_section_header( $fieldset = 'layout', $title = 'Styles & Layout', $instance, $this ); ?>

		<fieldset data-fieldset-id="layout" class="apw-settings apw-fieldset settings-layout">
		
			<legend class="screen-reader-text"><span><?php _e('Styles & Layout') ?></span></legend>
			
			<?php
			$_intro = __( 'Selecting the Default Styles option below will give you a quick start to styling your posts widget.  Additionally, the widget has a number of classes available to further customize its appearance to match your theme.' );
			$intro = apply_filters( 'apw_intro_text_layout', $_intro );
			?>

			<div class="description apw-description">
				<?php echo wpautop( __( $intro ) ); ?>
			</div>			
			
			<?php
			$_layout_fields =  array(
				'css_default' => Advanced_Posts_Widget_Fields::build_field_css_default( $instance, $this ),
			);
			$layout_fields = apply_filters( "apw_form_fields_layout", $_layout_fields, $instance, $this );

			Advanced_Posts_Widget_Fields::load_fieldset( 'layout', $layout_fields, $instance, $this );
			?>
		</fieldset>

	</div><!-- /.apw-section -->	

</div>