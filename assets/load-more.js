/* global hmJuicerLoadMore */

// Use CustomEvent polyfill for IE11 compat.
const CustomEvent = require( 'custom-event' );

( function ( window, $ ) {

	if ( typeof hmJuicerLoadMore === 'undefined' ) {
		return;
	}

	let event = new CustomEvent( 'hm-juicer-load-more-posts' ),
		$button = $( '.juicer-feed__load-more' ),
		$posts = $( hmJuicerLoadMore.args.list_class );

	let loadMorePosts = _.throttle( function () {

		$button.prop( 'disabled', true );

		let data = {
			action: 'hm_juicer_load_more_handler',
			args: hmJuicerLoadMore.args
		};

		$.post( hmJuicerLoadMore.ajaxurl, data, function ( response ) {
			if ( response.success ) {

				// Get last child before we append the next batch of children.
				let lastChild = $posts.children().last();
				let maxNumPages = response.data.max_num_pages + hmJuicerLoadMore.args.paged_offset;

				$posts.append( response.data.body );

				// Find the first 'a' element in the new batch of children and select it.
				lastChild.next().find( 'a' ).first().focus();

				// Update our page count after loading posts.
				let lastPage = parseInt( hmJuicerLoadMore.args.paged, 10 );

				hmJuicerLoadMore.args.paged = ( lastPage > 1 ) ? lastPage + 1 : 2;

				if ( hmJuicerLoadMore.args.paged >= maxNumPages ) {
					$button.parent().hide();
				}

				document.dispatchEvent( event );

				$button.prop( 'disabled', false );
			}
		} );
	}, 300 );

	$button.on( 'click', function ( e ) {
		e.preventDefault();

		loadMorePosts();
	} );

} )( window, jQuery );
