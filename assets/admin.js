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

	var $news_template = $('.news__entry.template').remove().removeClass('template');

	$.ajax( '/wp-json/dable/v1/news' ).done( function(data) {
		// TODO : error 처리

		console.log(data);
		if ( ! data || ! data.list ) return;

		var $news = $('ul.news').empty();

		data.list.forEach( function(entry) {
			var $entry = $news_template.clone();

			// title
			$entry.find('.news__title').text( entry.title );

			// thumbnail
			if ( entry.thumbnail_link ) {
				$entry.find('.news__thumbnail').css('background-image', 'url(' + entry.thumbnail_link + ')');
			} else {
				$entry.find('.news__thumbnail').remove();
			}

			// description
			var $paragraph = $entry.find('.news__content').text( entry.description );

			$entry.appendTo( $news );

			// clamp
			$clamp($paragraph.get(0), {clamp: 'auto'});
		} );
	} );
} );
