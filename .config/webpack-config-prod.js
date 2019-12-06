/**
 * .config/webpack.config.prod.js
 * This file defines the production build configuration
 */
const { helpers, externals, presets } = require( '@humanmade/webpack-helpers' );
const { filePath } = helpers;

module.exports = presets.production( {
	externals,
	entry: {
		juicer: filePath( 'assets/js/juicer.js' ),
		load_more: filePath( 'assets/js/load-more.js' ),
		styles: filePath( 'assets/style.scss' ),
	},
	output: {
		path: filePath( 'build' ),
	},
 } );
