/* global imagesLoaded */
/**
 * Masonry-style JS for HM Juicer masonry layout.
 *
 * Source: https://medium.com/@andybarefoot/a-masonry-style-layout-using-css-grid-8c663d355ebb
 */

function resizeGridItem( item ) {
	// Get total height of all children.
	let itemChildren = item.children,
		itemTotalHeight = 0;
	for ( let i = 0; i < itemChildren.length; i++ ) {
		itemTotalHeight += itemChildren[i].getBoundingClientRect().height;
	}

	// Get grid styles (row height, row gap), set row span for item based on height of children.
	const grid = document.getElementsByClassName( 'juicer-grid' )[0],
		rowHeight = parseInt( window.getComputedStyle( grid ).getPropertyValue( 'grid-auto-rows' ) ),
		rowGap = parseInt( window.getComputedStyle( grid ).getPropertyValue( 'grid-row-gap' ) ),
		rowSpan = Math.ceil( ( itemTotalHeight + rowGap )/( rowHeight + rowGap ) );
	item.style.gridRowEnd = 'span ' + rowSpan;
}

// Resize all items.
export function resizeAllGridItems() {
	let allItems = document.getElementsByClassName( 'juicer-grid__item' );
	for ( let x = 0; x < allItems.length; x++ ) {
		resizeGridItem( allItems[x] );
	}
}

// Resize a single item.
export function resizeInstance( instance ) {
	let item = instance.elements[0];
	resizeGridItem( item );
}

// When images are finished loading, resize each item.
export function resizeNewItems() {
	let allItems = document.getElementsByClassName( 'juicer-grid__item' );
	for ( let x = 0; x < allItems.length; x++ ) {
		imagesLoaded( allItems[x], resizeInstance );
	}
}

// On load or resize, resize the items.
window.onload = resizeAllGridItems();
window.addEventListener( 'resize', resizeAllGridItems );
resizeNewItems();
