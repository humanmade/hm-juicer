/* global hmJuicerLoadMore */

import { resizeNewItems } from './juicer.js';

// Use CustomEvent polyfill for IE11 compat.
const CustomEvent = require( 'custom-event' );

( function ( window, $ ) {

	if ( typeof hmJuicerLoadMore === 'undefined' ) {
		return;
	}

	let event = new CustomEvent( 'hm-juicer-load-more-posts' ),
		$button = $( '.juicer-feed__load-more' ),
		$loading = $( '.juicer-feed__loading' ),
		$posts = $( hmJuicerLoadMore.args.list_class );

	let loadMorePosts = _.throttle( function () {

		$button.hide();
		$loading.addClass( 'loading' );

		let data = {
			action: 'juicer_load_more',
			args: hmJuicerLoadMore.args
		};

		$.post( hmJuicerLoadMore.ajaxurl, data, function ( response ) {
			if ( response.success ) {

				// Get last child before we append the next batch of children.
				let lastChild = $posts.children().last();

				// Only do stuff if the response body has content.
				if ( response.data.body ) {
					$posts.append( response.data.body );

					// Find the first 'a' element in the new batch of children and select it.
					lastChild.next().find( 'a' ).first().focus();

					// Update our page count after loading posts.
					let lastPage = parseInt( hmJuicerLoadMore.args.page, 10 );

					hmJuicerLoadMore.args.page = ( lastPage > 1 ) ? lastPage + 1 : 2;

					document.dispatchEvent( event );

					// When images are finished loading, resize each item.
					resizeNewItems();

					// Show the button when we're done.
					$button.show();
				}

				// Done fetcing, remove the loading animation.
				$loading.removeClass( 'loading' );
			}
		} );
	}, 300 );

	$button.on( 'click', function ( e ) {
		e.preventDefault();

		loadMorePosts();
	} );

} )( window, jQuery );
