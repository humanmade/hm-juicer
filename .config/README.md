# Project Webpack Build Process.

We have a single webpack config for the whole project. All custom themes and plugins use this single configuration and it handles both JS and CSS.

This means we can always write modern JS and SCSS as standard everywhere without having to configure this for each theme and plugin.

It ensures that all the code we serve to visitors is optimised for production. But also during development we have features available to help us work effectively.

## Usage

First of all you will need to have run `npm install` from the `content` directory.

### Building for production.

To build the production ready JS and CSS run:

```
npm run build
```

* This will build the all the assets for the plugin.
* This will create production ready assets. (Minified, comments stripped, trees shaken etc.)
* Built assets are tracked in version control so they are bundled with the plugin.

### Running development builds

Whilst you are developing the project you should run:

```
npm run start
```

This will build all assets, and then watch for changes and rebuild assets as required.

It is much faster then the production build as it only has to rebuild the thing that changed, and doesn't have to do any optimisation.

You must use the helper functions in `hm-asset-loader.php` to register/enqueue scripts and styles so that the development assets can be loaded instead of the production ones whilst this task is running. More on this below.

* Development assets are served by webpack dev server.
* Changes in JS files rebuild the file and trigger a page reload automatically.
* Development CSS is injected into the page by a development JS script, which allows it to live update styles without a page reload.
* Source maps.

## Adding a new theme/plugin.

Refer to our webpack config config at `.webpack/config.js` :)

This file exports an array of projects. Each project should include the following:

```js
{
	// Project (theme/plugin) name
	name: 'my-project-name',
	// Project path relative to content directory.
	path: `themes/my-project-name`,
	// Object containing entry points.
	// Keys are a unique asset name.
	// Values are the asset file path (relative to the project path defined above)
	// You can add both JS files and SCSS files.
	// NOTE: asset names MUST be unique - even across JS and CSS files.
	entry: {
		'my-project-main': './src/js/index.js',
		'my-project-theme': './src/scss/theme.scss',
	},
}
```

This is all that is required to include your project in the build process. When you run `npm run build` you should see a `build` directory containing production ready files for all the specified entry points.

For your project to be included in the development build process, you will need to add new tasks to `scripts` object of the `package.json` file with the following format:

```js
"dev:eh-theme": "BUILD_TARGET=eh-theme webpack-dev-server --config .webpack/webpack-config-dev.js",
```

The key, e.g, `dev:{project-name}` and `BUILD_TARGET={project-name}` should use the same project name value that is added to the `.webpack/config.js` file. And you should also follow the pattern of other projects in the main `dev` task to add the script task for your project, e.g., `"dev": "concurrently npm:dev:eh-theme npm:dev:eh-blog-theme npm:dev:{project-name}"`.

## Enqueueing assets

All that remains now is to register and enqueue the files in your theme or plugin. The HM Asset Loader code provides helper functions to register/enqueue your scripts and styles that will load either built files or development versions depending on whether you are running the development task or not. You should use these instead of the core functions for registering and enqueueing scripts and styles.

The only real difference between these and the core functions is that they accept a few additional arguments, and all arguments are passed as a single array.

```php
HM\Asset_Loader\register_script( $args )
```

Args
* **name** string (required) Unique asset name as defined in the config. (Unique across both scripts and styles).
* **handle** string (required) Unique handle used by WordPress. This can be the same as `name`, but doesn't HAVE to be.
* **build_dir** string (required) Full path to the project build directory. e.g. `get_template_directory() . '/build';`
* **deps** array (optional) Dependencies. An array of script handles.
* **version** array (optional) If empty will use the deployment revision.
* **in_footer** bool (optional) Should the script be loaded in the footer.

```php
HM\Asset_Loader\register_style( $args )
```

Args
* **name** string (required) Unique asset name as defined in the config. (Unique across both scripts and styles).
* **handle** string (required) Unique handle used by WordPress. This can be the same as `name`, but doesn't HAVE to be.
* **build_dir** string (required) Full path to the project build directory. e.g. `get_template_directory() . '/build';`
* **deps** array (optional) Dependencies. An array of script handles.
* **version** array (optional) If empty will use the deployment revision.

The following functions to register and enqueue scripts/styles are also available and accept the same args as the counterpart function for registering. If you just want to enqueue something that is already registered - you can use the core function e.g. `wp_enqueue_script`

```php
HM\Asset_Loader\enqueue_script()
HM\Asset_Loader\enqueue_style()
```
