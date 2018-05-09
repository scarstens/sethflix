<?php
if ( ! empty( $_REQUEST['debug'] ) || ! empty( $_COOKIE['debug'] ) ) {
	ini_set( 'display_errors', 'On' );
	error_reporting( E_ALL );
}

require_once APP_SERVER_DIR . '/vendor/autoload.php';
include_once APP_SERVER_DIR . '/server_config.php';
include_once APP_SERVER_DIR . '/inc/plex_api_sdk_redux.class.php';
include_once APP_SERVER_DIR . '/inc/class.auth.php';
//header('Content-Type: text/plain');
//print_r( Plex_API_SDK_Redux::get_friends() );

if ( ! defined( 'BYPASS_AUTH' ) ) {
	Simple_Cookie_Auth::auth_check();
}
