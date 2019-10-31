<img src="https://humanmade.com/content/themes/humanmade/lib/hm-pattern-library/assets/images/logos/logo-red.svg" width="100" alt="Human Made Logo" />

# <img src="https://avatars3.githubusercontent.com/u/4896003?s=20" alt="Juicer.io logo"> HM Juicer

Integrates with Juicer API for social feeds and allows accessible display of social media content.

## Installation

### With Git

#### Step 1
`cd` into your desired directory (either `wp-content/plugins` or `wp-content/mu-plugins`) and clone the repository locally.

```bash
git clone git@github.com:humanmade/hm-juicer.git
```

### With Composer

At this time, `humanmade/hm-juicer` does not exist in packagist, so you will need to edit your `composer.json` file to add it as a repository. Vendor packages are installed as WordPress plugins and will need to be activated manually from the Plugins page (or explicitly loaded if installing as a `mu-plugin`).

#### Step 1
Add the `humanmade/hm-juicer` GitHub repository as a repository in your `composer.json`.

```json
"repositories": [
	{
		"type": "vcs",
		"url": "git@github.com:humanmade/hm-juicer.git"
	}
]
```

#### Step 2
Install the plugin via `composer`.

```bash
composer require humanmade/hm-juicer
```

### For both: Configure environment
You may want to define environment variables that define your Juicer account and your site name and URLs. All of these are optional, but if any of them are not defined, a Settings page will appear in your admin to define these settings.

#### Step 1
Add the Juicer feed name to the `wp-config.php` file via the `JUICER_ID` constant.

```php
define( 'JUICER_ID', 'myaccountname' );
```

This is the ID that is used in your Juicer feed URL, e.g. `https://www.juicer.io/feeds/myaccountname`.

#### Step 2
Define your site name. This will affect how links appear in the Juicer feed.

```php
define( 'JUICER_SITE_NAME', 'My Cool Site' );
```

#### Step 3
Add the Juicer long and short URLs. This is used to determine links that are coming from your site. 

Note: While the short URL is intended for URL shorteners like a custom URL or a service like bit.ly, this can be any URL that links back to your site.

```php
define( 'JUICER_SHORT_URL', 'short.url' );
define( 'JUICER_LONG_URL', 'mydomain.com' );
```

### For both: Install dependencies
There are a number of dependencies that need to be installed if you are going to be using the Juicer plugin for development. At this time, this also needs to be done if you are not defining the constants above. To do this, `cd` into the directory and run the setup command.

```bash
npm run setup
```

## Usage
To add the Juicer feed to a page, simply use the function `juicer_feed` in your template with the correct number of posts to display (and optionally, the page to display from). Documentation on the `juicer_feed` function is available in the [`inc/README.md`](inc/README.md) file.

## Credits

Created by Human Made to render and display accessible social media feeds via the Juicer API.

Maintained by [Chris Reynolds](https://github.com/jazzsequence).

Contributors:  
* [Joeleen Kennedy](https://github.com/joeleenk)  
* [Rian Rietveld](https://github.com/rianrietveld)

This plugin depends on these libraries/technologies by some awesome developers:

* [CMB2/CMB2](https://github.com/CMB2/CMB2)
* [humanmade/webpack-helpers](https://github.com/humanmade/webpack-helpers)
* [humanmade/asset-loader](https://github.com/humanmade/asset-loader)
* [Babel](https://babeljs.io/)
* [ESLint](https://eslint.org/)
* [Webpack](https://webpack.js.org/)
* [Sass](https://sass-lang.com/)

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
