/**
 * Plugin functions file.
 *
 */
if( "undefined"==typeof jQuery )throw new Error( "Advanced Posts Widget's JavaScript requires jQuery" );

(function ( $ ) {

    'use strict';

	function change_avatar_div( e ){
		var field = $( e.currentTarget );
		var apw_avatar_wrap = field.closest( '.apw-thumb-size-wrap' );
		var apw_avatar_div = apw_avatar_wrap.find( '.apw-avatar-preview' );

		if( apw_avatar_div.length ) {
			var avatar = $( '.apw-avatar', apw_avatar_div );
			var width = parseInt ( ( $.trim( $( '.apw-thumb-width', apw_avatar_wrap ).val() ) * 1 ) + 0 );
			var height = parseInt ( ( $.trim( $( '.apw-thumb-height', apw_avatar_wrap ).val() ) * 1 ) + 0 );

			//var size = parseInt ( ( $.trim( field.val() ) * 1 ) + 0 );
			apw_avatar_div.css( {
				'height' : height + 'px',
				'width' : width + 'px'
			} );
			avatar.css( { 'font-size' : height + 'px' } );
		}

		return;
	};

	// Change thumb size when form field changes
	$( '#customize-controls, #wpcontent' ).on( 'change', '.apw-thumb-size', function ( e ) {
		change_avatar_div( e );
		return;
	});

	// Change thumb size as user types
	$( '#customize-controls, #wpcontent' ).on( 'keyup', '.apw-thumb-size', function ( e ) {
		setTimeout( function(){
			change_avatar_div( e );
		}, 300 );
		return;
	});

	
	function change_excerpt_size( e ) {
		var field = $( e.currentTarget );
		var apw_excerpt_div = field.closest( '.apw-excerpt-size-wrap' ).find( '.apw-excerpt' );
		var size = parseInt ( ( $.trim( field.val() ) * 1 ) + 0 );

		if( apw_excerpt_div.length ) {
			var words = apw_script_vars.sample_excerpt.match(/\S+/g).length;
			var trimmed = '';
			if ( words > size ) {
				trimmed = apw_script_vars.sample_excerpt.split(/\s+/, size).join(" ");
			} else {
				trimmed = apw_script_vars.sample_excerpt;
			}
			
			apw_excerpt_div.html( trimmed + "&hellip;" );
			
			//apw_excerpt_div.html( apw_script_vars.sample_excerpt.substring( 0, size) + "&hellip;" );
		}
	}
	
	function update_excerpt_size( event, widget ){
		var field = widget.find( '.apw-excerpt-length' );
		var apw_excerpt_div = field.closest( '.apw-excerpt-size-wrap' ).find( '.apw-excerpt' );
		var size = parseInt ( ( $.trim( field.val() ) * 1 ) + 0 );

		if( apw_excerpt_div.length ) {
			var words = apw_script_vars.sample_excerpt.match(/\S+/g).length;
			var trimmed = '';
			if ( words > size ) {
				trimmed = apw_script_vars.sample_excerpt.split(/\s+/, size).join(" ");
			} else {
				trimmed = apw_script_vars.sample_excerpt;
			}
			
			apw_excerpt_div.html( trimmed + "&hellip;" );
			
			//apw_excerpt_div.html( apw_script_vars.sample_excerpt.substring( 0, size) + "&hellip;" );
		}
	}
	
	$( document ).on( 'widget-updated', update_excerpt_size );

	// Change excerpt size when form field changes
	$( '#customize-controls, #wpcontent' ).on( 'change', '.apw-excerpt-length', function ( e ) {
		change_excerpt_size( e );
		return;
	});

	// Change excerpt size as user types
	$( '#customize-controls, #wpcontent' ).on( 'keyup', '.apw-excerpt-length', function ( e ) {
		setTimeout( function(){
			change_excerpt_size( e );
		}, 300 );
		return;
	});
	
	
	
	function apw_close_accordions( widget ){
		var $sections = widget.find('.apw-section');
		var $first_section = $sections.first();

		$first_section.addClass('expanded').find('.apw-section-top').addClass('apw-active');
		$first_section.siblings('.apw-section').find('.apw-settings').hide();
	}
	
	function apw_on_form_update( event, widget ) {
		apw_close_accordions( widget );
	}
	
	$( document ).on( 'widget-added widget-updated', apw_on_form_update );
	
	$( '#widgets-right .widget:has(.apw-widget-form)' ).each( function () {
		apw_close_accordions( $( this ) );
	} );
	
	
	$( '#widgets-right, #accordion-panel-widgets' ).on( 'click', '.apw-section-top', function( e ){
		var $header = $( this );
		var $section = $header.closest( '.apw-section' );
		var $fieldset_id = $header.data( 'fieldset' );
		var $target_fieldset = $( 'fieldset[data-fieldset-id="' + $fieldset_id + '"]', $section );
		var $content = $section.find( '.apw-settings' )
		
		$header.toggleClass( 'apw-active' );
		$target_fieldset.addClass( 'targeted');
		$content.slideToggle( 300, function () {
			$section.toggleClass( 'expanded' );
		});
	});


}(jQuery) );