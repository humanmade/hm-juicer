# HM Juicer

## Juicer Settings

If `JUICER_ID` is not defined in the project's `wp-config.php` file, a Juicer Settings page will show up under the main Settings menu. This allows an administrator to set the Juicer feed name in the settings rather than hard-coding it in the `wp-config.php` file, if editing the `wp-config` is not an option.

**Note:** `hm-juicer` expects that [CMB2](https://github.com/CMB2/CMB2) is installed and available to support this settings page. If CMB2 is not found, an error message will appear to say that no `JUICER_ID` exists _and_ CMB2 is not installed. CMB2 can be installed in any context, as a dependency for another plugin, as a `mu-plugin` or as a normal WordPress plugin -- it just needs to exist for HM Juicer to be able to use it.

## Functions

### `juicer_feed( int $count, int $page )`
Found in: `functions.php`

Display the main Juicer social media feed.

Uses [`get_posts`](#get_posts).

#### Parameters
`$count` _(int)_ (Optional) The number of posts to display.

`$page` _(int)_ (Optional) The page to display.

### `juicer_id`
Found in: `namespace.php`

Get the Juicer feed name from the constant or CMB2, whichever is defined.

If neither is defined, returns `false`.

#### Return
_(mixed)_ Either the `JUICER_ID` from the constant defined in `wp-config.php` or options, or `false` if neither is set.

### `juicer_api_url`
Found in: `namespace.php`

Get the Juicer feed API endpoint URL.

This expects that `juicer_id()` returns a string. If `juicer_id()` returns false, `juicer_api_url()` will return false also.

#### See [juicer_id()](#juicer-id)

#### Return

_(mixed)_ Either the full Juicer feed API url or `false` if `juicer_id()` returns `false`.

### `get_posts( int $count, int $page )`
Found in: `api.php`

Get Juicer feed posts.

#### Parameters
`$count` _(int)_ The number of items to fetch. Defaults to 10.

`$page` _(int)_ The page to get items from. `$count` 10 and `$page` 2 would get the next 10 posts in the feed.

 #### Return
_(mixed)_ WP_Error on API error, false if no feed items, an array of item objects if request was successful.
