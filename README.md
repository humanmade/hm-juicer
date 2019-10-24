<img src="https://hmn.md/content/themes/hmnmd/assets/images/hm-logo.svg" width="100" alt="Human Made Logo" />

# HM Juicer

Integrates with Juicer API for social feeds and allows accessible display of social media content.

## Dependencies

This plugin expects the following other libraries to exist:

* [CMB2](https://github.com/CMB2/CMB2)

## Installation

### Step 1
Install the plugin via `composer`. (Note, composer integration not implemented yet, so this won't work.  TODO: Update this and remove this note 😅.)

```bash
composer require humanmade/hm-juicer
```

### Step 2
Add the Juicer feed name to the `wp-config.php` file via the `JUICER_ID` constant.

```php
const JUICER_ID = 'myaccountname';
```

Alternately, if no `JUICER_ID` is set, the feed name can be set via a settings page that appears if this constant does not exist.

## Usage
To add the Juicer feed to a page, simply use the function `juicer_feed` in your template with the correct number of posts to display (and optionally, the page to display from). Documentation on the `juicer_feed` function is available in the [`inc/README.md`](inc/README.md) file.

## Credits

Created by Human Made to render and display accessible social media feeds via the Juicer API.

Maintained by [Chris Reynolds](https://github.com/jazzsequence).

Contributors:
* [Joeleen Kennedy](https://github.com/joeleenk)

## Contributing

The development process follows [the standard Human Made development process](http://engineering.hmn.md/how-we-work/process/development/).

Here's a quick summary:

* Assign issues you're working on to yourself.
* Work on a branch per issue, something like `name-of-feature`. One branch per feature/bug, please.
* File a PR early so it can be used for tracking progress.
* When you're finished, mark the PR for review by labelling with "Reviewer Needed".
* Get someone to review your code, and assign to them; if no one is around, the project lead can review.

---------------------

Made with ❤️ by [Human Made](https://humanmade.com)
