<?php
/**
 * Main theme application instance
 *
 * @package sethflix
 */

namespace Sethflix\Theme;

/**
 * Class App
 */
class App {

	/**
	 * Assets URL for images, css, and js
	 *
	 * @var string
	 */
	public static $assets_uri = APP_SERVER_URI . 'assets';

	/**
	 * Non WP hosted sites use custom cookie authentication.
	 *
	 * @var string
	 */
	public static $auth_cookie_name = 'x-passkey';

	/**
	 * App constructor.
	 */
	public function __construct() {
		if ( self::is_authenticated() ) {
			self::maybe_logout();
		} else {
			// Default - you must be authenticated to view the site.
			self::maybe_login();
		}
	}

	/**
	 * Handle logout conditions and redirects
	 *
	 * @return bool
	 */
	public static function maybe_logout() {
		if ( isset( $_GET['reauth'] ) ) {
			$reauth = sanitize_text_field( wp_unslash( $_GET['reauth'] ) );
		} else {
			// Don't logout without reauth param.
			return false;
		}

		// Do not continue if the value is anything but logout.
		if ( 'logout' !== strtolower( $reauth ) ) {
			return false;
		}

		// Handle logout of the custom cookie authentication.
		unset( $_COOKIE[ self::$auth_cookie_name ] );
		// Empty value and expiration one hour before.
		setcookie( self::$auth_cookie_name, '', time() - 3600 );

		// Handle logging out when the site is WordPress.
		if ( function_exists( 'wp_logout' ) ) {
			wp_logout();
			wp_safe_redirect( get_site_url() . '/?reauth=logged_out' );
			die( 'logged out.' );
		}

		return true;
	}

	/**
	 * Helper function to determine action and direct user to proper login form.
	 */
	public static function maybe_login() {

		// Do not continue to redirects if already on the login page.
		if ( self::is_login_page() ) {
			return;
		}

		// If WordPress use relative login redirection function, otherwise use custom login page.
		if ( function_exists( 'auth_redirect' ) ) {
			auth_redirect();
		} else {
			// @codingStandardsIgnoreLine
			header( 'Location: ' . ( isset( $_SERVER['HTTPS'] ) ? 'https' : 'http' ) . "://$_SERVER[HTTP_HOST]/login.php?reath=Login_Redirect" );
			die();
		}
	}

	/**
	 * Determine if the current page is the login page.
	 *
	 * @return bool
	 */
	public static function is_login_page() {
		// @codingStandardsIgnoreLine
		$current_url = ( isset( $_SERVER['HTTPS'] ) ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if ( stristr( $current_url, 'login.php' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns HTML of the logo.
	 */
	public static function the_logo() {
		$style = 'style = "width: 100%; height: auto;"';
		$img   = ' < img src = "' . App::$assets_uri . '/images/sethflix-icon.png\" ' . $style . '/>';
		echo wp_kses( $img, wp_kses_allowed_html( 'post' ) );
	}

	/**
	 * Build HTML for login or logout link depending on auth state.
	 */
	public static function nav_login_logout_link() {
		if ( ! static::is_authenticated() ) {
			$label = 'Login';
		} else {
			$label = 'Logout';
		}
		$suffix = ROUTE_SUFFIX;
		echo wp_kses( " < li id = \"nav-login\"><a href=\"/login{$suffix}?reauth={$label}\">{$label}</a></li>", wp_kses_allowed_html( 'post' ) );
	}

	/**
	 * Returns true if a user is properly logged in.
	 *
	 * @return bool
	 */
	public static function is_authenticated() {
		if ( function_exists( 'is_user_logged_in' ) ) {
			return \is_user_logged_in();
		}

		return true;
	}

	/**
	 * Use the Plex API to fetch status of the server and return a set of information for bootstrap css.
	 *
	 * @return array
	 */
	public static function get_plex_server_status() {
		$server_status    = \Plex_API_SDK_Redux::get_server_status();
		$status['server'] = $server_status;
		if ( empty( $server_status ) ) {
			$status['style']   = 'danger';
			$status['comment'] = 'OFFLINE';
		} else {
			$status['style']   = 'success';
			$status['comment'] = 'OK';
		}

		return $status;
	}

	/**
	 * Title helper function.
	 *
	 * @return string
	 */
	public static function get_title() {
		$title = 'My Plex';
		if ( function_exists( 'get_bloginfo' ) ) {
			$title = \get_bloginfo( 'title' );
		} elseif ( defined( 'SITE_TITLE' ) ) {
			$title = SITE_TITLE;
		}

		return $title;
	}
}
