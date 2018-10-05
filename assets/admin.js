// Dable for WordPress: Admin
jQuery( function($){
	$( 'input[name="dable-widget-settings\\[widget_type\\]"]' ).on( 'click', function() {
		$( 'fieldset[class*="dable-widget-"]' )
			.addClass( 'hidden' )
			.filter( '[class*="dable-widget-' + this.value + '"]' )
				.removeClass( 'hidden' );
	} );

	$( '.wrap.dable button.toggle' ).on('click', function() {
		var $this = $(this).toggleClass('active');
		var $desc = $this.closest('section').find('>.desc');

		if ( $this.hasClass('active') ) {
			$desc.slideDown(200);
		} else {
			$desc.slideUp(200);
		}
	} );

	$('.wrap.dable label.toggle-slide').on('click', function(event) {
		var $input = $(this).find('input:radio');

		if ( $input.length === 0 ) {
			return;
		}

		event.preventDefault();

		if ( $input.filter('[value=""]').is(':checked') ) {
			$input.filter('[value="true"]').prop('checked', true);
		} else {
			$input.filter('[value=""]').prop('checked', true);
		}
	} );
} );
