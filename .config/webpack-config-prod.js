/**
 * .config/webpack.config.prod.js
 * This file defines the production build configuration
 */
const { helpers, externals, presets } = require( '@humanmade/webpack-helpers' );
const { filePath } = helpers;

module.exports = presets.production( {
	externals,
	entry: {
		'hm-juicer': [
			filePath( 'assets/style.scss' ),
			filePath( 'assets/js/juicer.js' ),
		],
		'hm-juicer-load-more': filePath( 'assets/js/load-more.js' ),
	},
	output: {
		path: filePath( 'build/prod' ),
	},
 } );
