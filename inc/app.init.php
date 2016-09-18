<?php

include_once('class.auth.php');

if( ! defined('BYPASS_AUTH') ){
	Simple_Cookie_Auth::auth_check();
}