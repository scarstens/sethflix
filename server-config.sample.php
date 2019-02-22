<?php
/**
 * Create and include these types of settings in the server_config.php file.
 *
 * @package sethflix
 */

add_filter( 'PLEX_SERVER_TOKEN', function () {
	return [
		0 => 'x', // Server 0
		1 => 'y', // Server 1
		2 => 'z', // Server 2
	];
} );
define( 'ROUTE_SUFFIX', '/' );
define( 'APP_SERVER_DIR', get_stylesheet_directory() . '/' );
define( 'APP_SERVER_URI', get_stylesheet_directory_uri() . '/' );
define( 'CACHE_ON', true );
