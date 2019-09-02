# HM Juicer

## Juicer Settings

If `JUICER_ID` is not defined in the project's `wp-config.php` file, a Juicer Settings page will show up under the main Settings menu. This allows an administrator to set the Juicer feed name in the settings rather than hard-coding it in the `wp-config.php` file, if editing the `wp-config` is not an option.

**Note:** `hm-juicer` expects that [CMB2](https://github.com/CMB2/CMB2) is installed and available to support this settings page. If CMB2 is not found, an error message will appear to say that no `JUICER_ID` exists _and_ CMB2 is not installed. CMB2 can be installed in any context, as a dependency for another plugin, as a `mu-plugin` or as a normal WordPress plugin -- it just needs to exist for HM Juicer to be able to use it.

## Functions

### `juicer_id`
Get the Juicer feed name from the constant or CMB2, whichever is defined.

If neither is defined, returns `false`.

#### Return
`mixed` Either the `JUICER_ID` from the constant defined in `wp-config.php` or options, or `false` if neither is set.

### `juicer_api_url`
Get the Juicer feed API endpoint URL.

This expects that `juicer_id()` returns a string. If `juicer_id()` returns false, `juicer_api_url()` will return false also.

#### See [juicer_id()](#juicer-id)

#### Return

`mixed` Either the full Juicer feed API url or `false` if `juicer_id()` returns `false`.
