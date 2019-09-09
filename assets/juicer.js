/**
 * Masonry-style JS for HM Juicer masonry layout.
 *
 * Source: https://medium.com/@andybarefoot/a-masonry-style-layout-using-css-grid-8c663d355ebb
 */

function resizeGridItem( item ) {
	const grid = document.getElementsByClassName( 'juicer-grid' )[0],
		rowHeight = parseInt( window.getComputedStyle( grid ).getPropertyValue( 'grid-auto-rows' ) ),
		rowGap = parseInt( window.getComputedStyle( grid ).getPropertyValue( 'grid-row-gap' ) ),
		rowSpan = Math.ceil( ( item.querySelector( '.juicer-post__inner' ).getBoundingClientRect().height + rowGap )/( rowHeight + rowGap ) );
	item.style.gridRowEnd = 'span ' + rowSpan;
}

function resizeAllGridItems() {
	let allItems = document.getElementsByClassName( 'juicer-grid__item' );
	for ( let x=0; x<allItems.length; x++ ) {
		resizeGridItem( allItems[x] );
	}
}

function resizeInstance( instance ) {
	let item = instance.elements[0];
	resizeGridItem( item );
}

window.onload = resizeAllGridItems();
window.addEventListener( 'resize', resizeAllGridItems );

let allItems = document.getElementsByClassName( 'juicer-grid__item' );
for ( let x=0; x<allItems.length; x++ ) {
	imagesLoaded( allItems[x], resizeInstance );
}
