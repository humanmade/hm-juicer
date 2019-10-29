<img src="https://humanmade.com/content/themes/humanmade/lib/hm-pattern-library/assets/images/logos/logo-red.svg" width="100" alt="Human Made Logo" />

# <img src="https://avatars3.githubusercontent.com/u/4896003?s=20" alt="Juicer.io logo"> HM Juicer

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
* [Rian Rietveld](https://github.com/rianrietveld)

## Contributing

If you want to contribute, you'll need to get your local environment setup. We've provided an easy setup script to get all the NPM dependencies and any required Composer dependencies installed. Simply run the following command after checking out the repository and `cd`ing into it:

```bash
npm run setup
```

In order to run unit tests locally, you'll need to make sure that you have a local version of PHP and MySQL/MariaDB installed (or run inside a virtual machine/Docker container). If your machine meets the requirements, you can run this command to get unit tests set up in your environment:

```bash
npm run setup:tests
```

(This is not run as part of the normal setup script because not every environment will support it.)


The development process follows [the standard Human Made development process](http://engineering.hmn.md/how-we-work/process/development/).

Here's a quick summary:

* Assign issues you're working on to yourself.
* Work on a branch per issue, something like `name-of-feature`. One branch per feature/bug, please.
* File a PR early so it can be used for tracking progress.
* When you're finished, mark the PR for review by labelling with "Reviewer Needed".
* Get someone to review your code, and assign to them; if no one is around, the project lead can review.

---------------------

Made with ❤️ by [Human Made](https://humanmade.com)
