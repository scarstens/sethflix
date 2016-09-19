<?php

class Simple_Cookie_Auth{
	static $cookie_name = 'x-passkey';

	// auth check intended for all pages except for login.php
	public static function auth_check($type = 'redirect'){

		// check for passkey cookie
		if(! isset( $_COOKIE['x-passkey'] ) ){
			//no passkey cookie found
			if('redirect' == $type){
				header('Location: http://sethflix.com/login.php?reauth=nocookie_'.time(), true, 302);
				#var_export($_COOKIE);
				exit;
			} else {
				return false;
			}
		}

		// passkey found, validate it follows the rules
		$validation = static::validate_auth($_COOKIE['x-passkey']);
		if( false == $validation ){
			//invalid passkey found
			if('redirect' == $type){
				header('Location: http://sethflix.com/login.php?reauth=logout&reason=badkey', true, 302);
				exit;
			} else {
				return false;
			}
		}

		// passkey found and validated
		define('USER_LOGGED_IN', true);
		return true;
	}

	//validate the the passkey is allowed
	protected static function validate_auth($passkey, $user=''){
		// validate passkey value
		if( ! (strlen( $passkey ) > 10 && stristr( $passkey, 'watch' ) ) ){
			return false;
		}
		return true;
	}

	public static function user_logged_in(){
		return defined('USER_LOGGED_IN');
	}
}