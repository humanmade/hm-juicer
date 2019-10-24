/**
 * Utility methods for use when generating build configuration objects.
 */
const path = require( 'path' );
const autoprefixer = require( 'autoprefixer' );
const postcssFlexbugsFixes = require( 'postcss-flexbugs-fixes' );

const { config } = require( './config' );

/**
 * Return the specified port on which to run the dev server.
 */
const devServerPort = () => parseInt( process.env.PORT, 10 ) || 9090;

/**
 * Return the absolute file system path to a file within the project.
 * @param  {...String} relPaths Strings describing a file relative to the content/ folder.
 * @returns {String} An absolute file system path.
 */
const filePath = ( ...relPaths ) => path.join( process.cwd(), ...relPaths );

/**
 * Return the relative file system path to a file within the project.
 */
const relPath = relPath => path.resolve( process.cwd(), relPath );

/**
 * An array of file system paths in which to find first-party source code.
 * Used to limit Webpack transforms like Babel to just those folders containing our code.
 */
const srcPaths = config.map( ( { path } ) => filePath( path, 'src' ) );

/**
 * Given a string, returns a new string with dash separators converted to
 * camel-case equivalent. This is not as aggressive as `_.camelCase` in
 * converting to uppercase, where Lodash will convert letters following
 * numbers.
 *
 * @param {string} string Input dash-delimited string.
 *
 * @return {string} Camel-cased string.
 */
function camelCaseDash( string ) {
	return string.replace(
		/-([a-z])/g,
		( match, letter ) => letter.toUpperCase()
	);
}

/**
 * Scripts that can are bundled by WordPress.
 *
 * @TODO - do we want to force all scripts to use this same list? e.g. frontend scripts may wish to bundle their own.
 */
const wpExternalScripts = {
	jquery: 'jQuery',
	tinymce: 'tinymce',
	moment: 'moment',
	react: 'React',
	'react-dom': 'ReactDOM',
	backbone: 'Backbone',
	lodash: 'lodash',
};

const entryPointNames = [
	'components',
	'utils',
	'edit-post',
	'core-blocks',
];


const gutenbergPackages = [
	'a11y',
	'api-fetch',
	'autop',
	'blob',
	'blocks',
	'block-serialization-spec-parser',
	'compose',
	'core-data',
	'data',
	'date',
	'deprecated',
	'dom',
	'dom-ready',
	'editor',
	'element',
	'hooks',
	'html-entities',
	'i18n',
	'is-shallow-equal',
	'keycodes',
	'nux',
	'plugins',
	'shortcode',
	'url',
	'viewport',
	'wordcount',
];

/**
 * Configure externals for Webpack.
 *
 * This maps `wp` globals to their respective `@wordpress` package names
 * and defines all third party JS included by WordPress.
 */
const externals = [
	...entryPointNames,
	...gutenbergPackages,
].reduce( ( externals, name ) => ( {
	...externals,
	[ `@wordpress/${ name }` ]: `wp.${ camelCaseDash( name ) }`,
} ), {
	...wpExternalScripts,
} );

/**
 * Loader configuration objects which can be re-used in the dev and prod build config files.
 */
const loaders = {
	eslint: {
		test: /\.(js|jsx|mjs)$/,
		include: srcPaths,
		enforce: 'pre',
		use: [ {
			options: {
				eslintPath: require.resolve( 'eslint' ),
			},
			loader: require.resolve( 'eslint-loader' ),
		} ],
	},
	url: {
		test: /\.(png|jpg|jpeg|gif|svg|woff|woff2|eot|ttf)$/,
		loader: require.resolve( 'url-loader' ),
		options: {
			limit: 10000,
		},
	},
	js: {
		test: /\.js$/,
		include: srcPaths,
		loader: require.resolve( 'babel-loader' ),
		options: {
			// Cache compilation results in ./node_modules/.cache/babel-loader/
			cacheDirectory: true,
		},
	},
	css: {
		loader: require.resolve( 'css-loader' ),
		options: {
			importLoaders: 1,
		},
	},
	postcss: {
		loader: require.resolve( 'postcss-loader' ),
		options: {
			ident: 'postcss',
			plugins: () => [
				postcssFlexbugsFixes,
				autoprefixer( {
					flexbox: 'no-2009',
					grid: 'autoplace',
				} ),
			],
		},
	},
	sass: {
		loader: require.resolve( 'sass-loader' ),
		options: {
			includePaths: [ filePath( './src/scss' ) ],
		},
	},
	file: {
		// Exclude `js`, `html` and `json`, but match anything else.
		exclude: [ /\.js$/, /\.html$/, /\.json$/ ],
		loader: require.resolve( 'file-loader' ),
	},
};

module.exports = {
	devServerPort,
	relPath,
	filePath,
	srcPaths,
	loaders,
	externals,
};
