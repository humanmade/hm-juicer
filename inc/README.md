# HM Juicer

## Juicer Settings

If `JUICER_ID` is not defined in the project's `wp-config.php` file, a Juicer Settings page will show up under the main Settings menu. This allows an administrator to set the Juicer feed name in the settings rather than hard-coding it in the `wp-config.php` file, if editing the `wp-config` is not an option.

**Note:** `hm-juicer` expects that [CMB2](https://github.com/CMB2/CMB2) is installed and available to support this settings page. If CMB2 is not found, an error message will appear to say that no `JUICER_ID` exists _and_ CMB2 is not installed. CMB2 can be installed in any context, as a dependency for another plugin, as a `mu-plugin` or as a normal WordPress plugin -- it just needs to exist for HM Juicer to be able to use it.

## The Juicer Loop

HM Juicer uses a "Juicer Loop" that is modelled after the WordPress Loop. The syntax used is almost identical:

```php
if ( juicer_have_posts() ) :
	while ( juicer_have_posts() ) :
		juicer_the_post();
		// Template code here.
	endwhile;
endif;
```

The Juicer Loop uses two global variables that are similar to the `$wp_query` and `$post` globals.

### `$juicer_posts`
_(array)_

Stores the current Juicer query (requested by `Juicer\get_posts()`, possibly via `juicer_feed()`). The `$juicer_posts` global is set when the `get_posts` function is run to query posts from the Juicer API. When `juicer_the_post` is run, the current feed item is stored in [`$juicer_post`](#$juicer_post) and the `$juicer_posts` array is updated to remove the current Juicer feed item (now stored in `$juicer_post`).

### `$juicer_post`
_(object)_

Stores the current Juicer feed item object. This global is updated when `juicer_the_post` is run.

## `juicer_get_` and `juicer_the_` functions
Just like `get_` and `the_` functions in WordPress core, `juicer_get_` and `juicer_the_` functions must be used inside the Juicer Loop because they use the current `$juicer_post` object.

Also, similar to WordPress core functions, **`juicer_get_`** functions will _return_ a value, while **`juicer_the_`** functions will _echo_ it. It's generally recommended when building templates that the `juicer_the_` function is used to display the relevant data.

## Functions

### `juicer_have_posts()`
Found in `functions.php`

Checks whether the Juicer Loop has posts.

#### Return
_(bool)_ Returns true if there are posts remaining, false if not.

### `juicer_the_post()`
Found in `functions.php`

Populates the `$juicer_post` global and removes the current post from the `$juicer_posts` array.

### `juicer_feed( int $count, int $page )`
Found in: `functions.php`

Display the main Juicer social media feed.

Uses [`Juicer\get_posts`](#get_posts).

#### Parameters
`$count` _(int)_ (Optional) The number of posts to display.

`$page` _(int)_ (Optional) The page to display.

### `juicer_id`
Found in: `functions.php`

Wrapper for `Juicer\get_id` found in `namespace.php`.

Get the Juicer feed name from the constant or CMB2, whichever is defined.

If neither is defined, returns `false`.

#### Return
_(mixed)_ Either the `JUICER_ID` from the constant defined in `wp-config.php` or options, or `false` if neither is set.

### `juicer_api_url`
Found in: `functions.php`

Wrapper for `Juicer\api_url` found in `namespace.php`.

Get the Juicer feed API endpoint URL.

This expects that `juicer_id()` returns a string. If `juicer_id()` returns false, `juicer_api_url()` will return false also.

#### See [juicer_id()](#juicer_id)

#### Return

_(mixed)_ Either the full Juicer feed API url or `false` if `juicer_id()` returns `false`.

### `get_posts( int $count, int $page )`
Found in: `api.php`

Get Juicer feed posts.

#### Parameters
`$count` _(int)_ (Optional) The number of items to fetch. Defaults to 10.

`$page` _(int)_ (Optional) The page to get items from. `$count` 10 and `$page` 2 would get the next 10 posts in the feed.

 #### Return
_(mixed)_ WP_Error on API error, `false` if no feed items, an array of item objects if request was successful.
