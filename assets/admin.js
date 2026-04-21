// Dable for WordPress: Admin
jQuery( function($){
	$( 'input[name="dable-widget-settings\\[widget_type\\]"]' ).on( 'click', function() {
		$( '.dable-widget-responsive, .dable-widget-platform' )
			.addClass( 'hidden' );
		$( '.dable-widget-' + this.value )
			.removeClass( 'hidden' );
	} );

	// Category tabs
	$( '.dable-category-tabs a' ).on( 'click', function(e) {
		e.preventDefault();
		var $this = $(this);
		var target = $this.data('tab');
		var $section = $this.closest('section');

		$section.find('.dable-category-tabs a').removeClass('nav-tab-active');
		$this.addClass('nav-tab-active');

		$section.find('.dable-category-panel').addClass('hidden');
		$section.find('.dable-category-panel[data-category="' + target + '"]').removeClass('hidden');
	} );

	$( '.wrap.dable button.toggle' ).on('click', function() {
		var $this = $(this).toggleClass('active');
		var $desc = $this.closest('h2,h3').nextAll('p.desc').eq(0);

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
