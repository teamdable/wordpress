// Dable for WordPress: Admin
jQuery( function($){
	$( 'input[name="dable-settings\\[widget_type\\]"]' ).on( 'click', function() {
		$( 'fieldset[class*="dable-widget-"]' )
			.addClass( 'hidden' )
			.filter( '[class*="dable-widget-' + this.value + '"]' )
				.removeClass( 'hidden' );
	} );
} );
