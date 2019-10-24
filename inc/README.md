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

Any `juicer_get_` or `juicer_the_` function used outside the Juicer Loop will return a `_doing_it_wrong` error.

## Function Reference

### `juicer_have_posts()`
Found in `functions.php`

Checks whether the Juicer Loop has posts.

#### Return
_(bool)_ Returns true if there are posts remaining, false if not.

### `juicer_the_post()`
Found in `functions.php`

Populates the `$juicer_post` global and removes the current post from the `$juicer_posts` array.

### `juicer_in_the_loop()`
Determine if we're in the Juicer Loop by checking the `$juicer_post` global.

#### Return
_(bool)_ True if we're in the Juicer Loop. False otherwise.

### `juicer_get_post()`
Return the current Juicer post object.

Must be used inside the Juicer Loop.

#### Return
_(object)_ The current Juicer post object.

### `juicer_get_date( string $date_format )`
Return the Juicer post date of the current Juicer post.

Must be used inside the Juicer Loop

#### Parameters
_(string)_ `$date_format` (Optional) The PHP date format string. Defaults to Unix time.

#### See also
[`date()`](https://www.php.net/manual/en/function.date.php)

#### Return
_(string)_ The Juicer post date.

#### `juicer_the_date( string $date_format )`
Display the Juicer post date of the current Juicer post.

#### `juicer_get_humanized_time()`
Return the humanized time (e.g. "1 day ago") of the current Juicer post.

Must be used inside the Juicer Loop.

#### Return
_(string)_ The humanized time of the Juicer post.

#### `juicer_the_humanized_time()`
Display the humanized time (e.g. "1 day ago") of the current Juicer post.

#### `juicer_get_the_content()`
Return the Juicer post content of the current Juicer post.

Must be used inside the Juicer Loop.

#### Return
_(string)_ The content of the Juicer post.

#### `juicer_the_content()`
Display the Juicer post content of the current Juicer post.

#### `juicer_get_image_url()`
Return the URL to the Juicer post featured image.

Must be used inside the Juicer Loop

#### Return
_(string)_ The Juicer social image.

#### `juicer_the_image_url()`
Display the URL to the Juicer post featured image.

### `juicer_get_source()`
Return the social media source for the Juicer post.

Must be used inside the Juicer Loop.

#### Return
_(string)_ The Juicer social media source (e.g. Facebook).

#### `juicer_the_source()`
Display the social media source for the Juicer post.

### `juicer_get_sharing_link()`
Return the sharing link for the posted social media object.

Must be used inside the Juicer Loop.

#### Return
_(string)_ The social media sharing link.

#### `juicer_the_sharing_link()`
Display the sharing link for the posted social media object.

### `juicer_get_like_count()`
The like count for the current social media post.

Must be used inside the Juicer Loop.

#### Return
_(int)_ The post like count.

#### `juicer_the_like_count()`
Display the like count for the current social media post.

### `juicer_get_comment_count()`
The comment count for the social media post.

Must be used inside the Juicer Loop.

#### Return
_(int)_ The post comment count.

#### `juicer_the_comment_count()`
Display the comment count for the social media post.

### `juicer_get_author_name()`
The social media account display name or account name.  If display name exists, display name is used, otherwise the account name is used.

Must be used inside the Juicer Loop.

#### Return
_(string)_ The social media author/account name.

#### `juicer_the_author_name()`
Display the social media account display name or account name.  If display name exists, display name is used, otherwise the account name is used.

### `juicer_get_author_url()`
Return the link to the social media author profile.

Must be used inside the Juicer Loop.

#### Return
_(string)_ The link to the social media author profile.

#### `juicer_the_author_url()`
Display the link to the social media author profile.

### `juicer_get_author_image()`
Return the social media account avatar URL.

Must be used inside the Juicer Loop.

#### Return
_(string)_ The URL to the social media account avatar.

#### `juicer_the_author_image()`
Display the social media account avatar URL.

### `juicer_get_template( string $template )`
Load the Juicer template file. By default, will load the requested template file from the templates directory in the plugin with a prefix of `'part-juicer'`, but both the path to the template directory and the prefix can be filtered.

#### Return
_(string)_ $template (Required) The template to load (e.g. `'feed'` or `'post'`), not including the prefix (`'part-juicer'`).

### `juicer_feed( int $count, int $page )`
Found in: `functions.php`

Display the main Juicer social media feed.

Uses [`Juicer\get_posts`](#get_posts).

#### Parameters
`$count` _(int)_ (Optional) The number of posts to display.

`$page` _(int)_ (Optional) The page to display.

### `juicer_unset_posts()`
Reset (empty) the `$juicer_posts` global.

This is not completely analagous to `wp_reset_query` or `wp_reset_postdata` in that we're not restoring anything to its previous state, we're _just_ emptying the global (which will ensure that `juicer_have_posts()` will return `false`).

#### Return
_(array)_ The empty `$juicer_posts` array.

### `get_id()`
Found in: `namespace.php`

Get the Juicer feed name from the constant or CMB2, whichever is defined.

If neither is defined, returns `false`.

#### Return
_(mixed)_ Either the `JUICER_ID` from the constant defined in `wp-config.php` or options, or `false` if neither is set.

### `api_url()`
Found in: `namespace.php`

Get the Juicer feed API endpoint URL.

This expects that `get_id()` returns a string. If `get_id()` returns false, `api_url()` will return false also.

#### See [get_id()](#get_id)

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

### `get_author_image( object $item )`
Get the author image from the Juicer post item.

Some sources need special handling to get the original author image (avatar). This function takes care of the special handling or returns the source image if it does not need special handling.

Currently only supports Facebook.

#### Parameters
`$item` _(object)_ The Juicer post item.

#### Return
_(string|WP_Error)_ The author image (avatar) if one could be retrieved or a WP_Error if there was a problem.
/

### `maybe_humanize_time( int $timestamp )`
Found in: `api.php`

Humanize time if less than 35 days old. Otherwise, display a formatted date.

#### Parameters
`$timestamp` _(int)_ The Unix timestamp to check.

#### Return
_(string)_ The humanized time or the date string.

## Filter Reference

### `apply_filters( 'juicer_filter_feed_template', string $feed_template )`
Filters the feed template name.

To allow robust custom templating, it might be desirable to change the feed template file name to something totally different. In this case, developers might want to use this filter to change the feed template slug to something other than `'feed'`.

The single post template is called inside the feed template file (by default, although this could be different if a custom feed template was used), so if that template name was customized, it would be reflected in the custom feed template.

#### Parameters
`$feed_template` _(string)_ The Juicer feed template slug. Defaults to `'feed'` (for `part-juicer-feed.php`).

### `apply_filters( 'juicer_filter_the_content', string $post_content )`
Filter the Juicer post content.

`$post_content` _(string)_ The original Juicer post content.

### `apply_filters( 'juicer_filter_template_dir_path', string $template_path )`
Filters the template directory path. Defaults to the templates directory in the plugin.

#### Example usage
```php
add_filter( 'juicer_filter_template_dir_path', function() {
	return get_template_directory() . 'template-parts';
} );
```

#### Parameters
`$template_path` _(string)_ The full path to the template directory.

### `apply_filters( 'juicer_filter_template_prefix', string $template_prefix )`
Allow the template prefix to be filtered, e.g. if you wanted to use something other than `part-juicer`.

#### Example usage

```php
add_filter( 'juicer_filter_template_prefix', function() {
	return 'section-social';
} );
```

#### Parameters
`$template_prefix` _(string)_ The prefix for the template part.
