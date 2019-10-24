<?php
/**
 * Helper functions for unit tests.
 */

/**
 * Path to Juicer API mock data.
 *
 * @return string Juicer API mock data path.
 */
function get_mock_data_path() : string {
	return __DIR__ . '/data/juicer-mock.json';
}

/**
 * Cache the mock API data.
 *
 * We can create a mock API request by simply caching the data in the juicer-mock.json file since Juicer\get_posts checks the cache first.
 */
function cache_mock_data() {
	$api_data = file_get_contents( get_mock_data_path() );
	wp_cache_set( 'response_per_10-page_1', $api_data, 'juicer', DAY_IN_SECONDS );
}
