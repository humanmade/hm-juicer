/**
 * .config/webpack.config.prod.js
 * This file defines the production build configuration
 */
const { helpers, externals, presets } = require( '@humanmade/webpack-helpers' );
const { choosePort, cleanOnExit, filePath } = helpers;

// Clean up manifests on exit.
cleanOnExit( [
	filePath( 'build/asset-manifest.json' )
] );

module.exports = choosePort( 8080 ).then( port => [
	presets.development( {
		devServer: {
			port,
		},
		externals,
		entry: {
			juicer: filePath( 'assets/js/juicer.js' ),
			load_more: filePath( 'assets/js/load-more.js' ),
			styles: filePath( 'assets/style.scss' ),
		},
		output: {
			path: filePath( 'build/dev' ),
			publicPath: `https://localhost:${ port }/`
		},
 	} ),
] );
