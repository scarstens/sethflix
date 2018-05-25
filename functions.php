<?php
/**
 * If WordPress Theme, this will load automatically
 *
 * @package sethflix
 */

require_once 'server-config.php';
require_once 'vendor/autoload.php';
include_once 'app/class-plex-api-sdk.php';

include_once 'app/class-app.php';
new \Sethflix\Theme\App();

add_filter( 'authenticate', 'apply_custom_authentication', 50, 3 );

/**
 * Add custom logic to WP Authentication to allow plex.tv members to authenticate if they are "friends" or members of
 * the server.
 *
 * @see https://github.com/Arcanemagus/plex-api/wiki/Plex.tv
 *
 * @param \WP_User|\WP_Error|null $user     WordPress user if username and password were valid, void if not.
 * @param string                  $username Username from login form.
 * @param string                  $password Password from login form.
 *
 * @return false|\WP_Error|\WP_User
 */
function apply_custom_authentication( $user, $username, $password ) {
	/**
	 * Maybe register user by Plex.tv account. If the user cannot be found in the WordPress site, attempt to use
	 * credentials against the Plex API to validate user. If user validates, also confirm user is a member (friend)
	 * of the Plex Server Admin. Upon valid plex member (and friend to server), create WP user account login user.
	 */
	if ( empty( $user ) || ( is_wp_error( $user ) && 'invalid_username' === $user->get_error_code() ) ) {    // If user does not exist, maybe register.
		if ( ! empty( $password ) ) { // If password was sent, maybe register.
			// Validate user is a plex.tv account holder.
			$plex_user = plex_login_user( $username, $password );

			// If invalid credentials, return the API error to the visitor.
			if ( isset( $plex_user['error'] ) ) {
				$user = new WP_Error( 'authentication_failed', '<strong>ERROR</strong>: ' . $plex_user['error'] );

				return $user;
			} else { // If there are no errors, $plex_user has plex member information.
				// Confirm that the plex.tv user has access to this plex server.
				if ( is_servers_plex_friend( $plex_user['user']['username'] ) ) {
					// Create a WordPress users to enable valid plex member and friend to access the site.
					$user_id = wp_create_user( $plex_user['user']['username'], $password, $plex_user['user']['email'] );

					$account_admin = array_pop( Plex_API_SDK_Redux::get_account_admin() );
					if ( $account_admin['username'] === $plex_user['user']['username'] ) {
						$u = new WP_User( $user_id );
						$u->set_role( 'administrator' );
					}

					// Returning the newly created user here, logs the user in.
					return get_user_by( 'id', $user_id );
				} else {
					// This plex.tv member is not a friend of the server admin, and has no access.
					return new WP_Error( 'authentication_failed', '<strong>ERROR</strong>: We found your plex account, but you are not a member of this plex server, please contact the system administrator to request access.' );
				}
			}
		}

		// Returning allows standard WordPress login to continue when password is empty.
		return $user;
	}

	/**
	 * Arriving at this logic means that the Plex user has logged into this system before. As such, WordPress has found
	 * a matching username or email address for this plex user.
	 *
	 * @var \WP_User $userinfo
	 */
	// If the $user is an error object, WP authentication failed.
	if ( is_wp_error( $user ) ) {
		// Try falling back to plex API and updating local password if it validates.
		// Validate user is a plex.tv account holder.
		$plex_user = plex_login_user( $username, $password );
		if ( isset( $plex_user['error'] ) ) {
			$user = new WP_Error( 'authentication_failed', '<strong>ERROR</strong>: ' . $plex_user['error'] );
		} else {
			$user = get_user_by( 'login', $plex_user['user']['username'] );
			wp_set_password( $password, $user->ID );
		}

		return $user; // Returning error and message.
	}

	// Confirm this user is still a friend of the server admin, and has permissions to the server.
	if ( is_servers_plex_friend( $user->user_login ) || user_can( $user, 'manage_options' ) ) {
		// 👍👍 SUCCESS 👍👍 User authenticated! Return user to log them in.
		return $user;
	} else { // User no longer has permissions to this server, return an error message and do not log the user in.
		$user = new WP_Error( 'authentication_failed', '<strong>ERROR</strong>: You are not a member of this plex server, please contact the system adminsitrator to request access.' );

		return $user; // Returning error and message.
	}
}

/**
 * Return bool true if this plex user a member of the server
 *
 * @param string $user_name Username from user to match username from Plex friends.
 *
 * @return bool
 */
function is_servers_plex_friend( $user_name ) {
	$friends   = Plex_API_SDK_Redux::get_friends();
	$is_friend = isset( $friends[ $user_name ] );

	return $is_friend;
}

/**
 * Use Plex API to validate user credentials and gather information about user.
 *
 * @param $user_email
 * @param $password
 *
 * @return array|mixed|object
 */
function plex_login_user( $user_email, $password ) {
	// https://github.com/Arcanemagus/plex-api/wiki/Plex.tv for more info.
	$host  = "https://plex.tv/users/sign_in.json";
	$token = curl_init( $host );
	curl_setopt(
		$token,
		CURLOPT_POSTFIELDS,
		http_build_query(
			[
				'user[login]'    => $user_email,
				'user[password]' => $password,
			]
		)
	);
	curl_setopt( $token, CURLOPT_HTTPHEADER, [
		'X-Plex-Client-Identifier: ' . hash( 'md5', 'sethflix-server-auth-' . $user_email ),
		'X-Plex-Device: Web Client',
		'X-Plex-Device-Name: Authenticator',
		'X-Plex-Version: 1.0.7',
	] );
	curl_setopt( $token, CURLOPT_TIMEOUT, 30 );
	curl_setopt( $token, CURLOPT_POST, 1 );
	curl_setopt( $token, CURLOPT_RETURNTRANSFER, true );
	$json = curl_exec( $token );
	$json = json_decode( $json, true );
	curl_close( $token );

	return $json;
}


function custom_login_logo() { ?>
	<style type="text/css">
		#login h1 a, .login h1 a {
			background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/plex-logo.png);
			background-size: 184px;
			width: 184px;
		}

		p#nav {
			display: none;
		}

		p#backtoblog {
			display: none;
		}
	</style>
<?php }

add_action( 'login_enqueue_scripts', 'custom_login_logo' );

function my_login_logo_url() {
	return home_url();
}

add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
	return 'Sethflix + Plex';
}

add_filter( 'login_headertitle', 'my_login_logo_url_title' );

function login_checked_remember_me() {
	add_filter( 'login_footer', function () {
		echo "<script>document.getElementById('rememberme').checked = true;</script>";
	} );
}

add_action( 'init', 'login_checked_remember_me' );


function custom_login_redirect( $redirect_to, $request, $user ) {
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		if ( in_array( 'administrator', $user->roles ) ) {
			return admin_url();
		} else {
			return home_url();
		}
	} else {
		return home_url();
	}
}

add_filter( 'login_redirect', 'custom_login_redirect', 10, 3 );