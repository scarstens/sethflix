<?php
/**
 * Main init file for site
 *
 * @package sethflix
 */

namespace Sethflix\Theme;

require_once 'server-config.php';
require_once 'vendor/autoload.php';
include_once 'app/class-plex-api-sdk.php';
include_once 'app/class-app.php';
include_once 'app/class-plex-auth.php';
include_once 'app/class-login-page-mods.php';
new App();
Login_Page_Mods::register();
Plex_Auth::register();