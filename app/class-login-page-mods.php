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
class Login_Page_Mods {

	/**
	 * Main function registers all actions and filters.
	 */
	public static function register() {
		add_filter( 'login_redirect', [ get_called_class(), 'custom_login_redirect' ], 10, 3 );
		add_action( 'init', [ get_called_class(), 'login_checked_remember_me' ] );
		add_filter( 'login_headertitle', [ get_called_class(), 'my_login_logo_url_title' ] );
		add_filter( 'login_headerurl', [ get_called_class(), 'my_login_logo_url' ] );
		add_action( 'login_enqueue_scripts', [ get_called_class(), 'custom_login_logo' ] );

	}

	/**
	 * Customize login logo.
	 */
	public static function custom_login_logo() {
		?>
		<style type="text/css">
			#login h1 a, .login h1 a {
				background-image: url(<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/plex-logo.png);
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


	/**
	 * Modify login logo href.
	 *
	 * @return string|void
	 */
	public static function my_login_logo_url() {
		return home_url();
	}

	/**
	 * Modify login logo title.
	 *
	 * @return string
	 */
	public static function my_login_logo_url_title() {
		return 'Sethflix + Plex';
	}

	/**
	 * Default remember me checkbox to checked.
	 */
	public static function login_checked_remember_me() {
		add_filter( 'login_footer', function () {
			echo "<script>document.getElementById('rememberme').checked = true;</script>";
		} );
	}

	/**
	 * Redirect non-admin users back to the homepage.
	 *
	 * @param string   $redirect_to Original redirect url.
	 * @param mixed    $request     Original http request.
	 * @param \WP_User $user        User logging in.
	 *
	 * @return string
	 */
	public static function custom_login_redirect( $redirect_to, $request, $user ) {
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			if ( in_array( 'administrator', $user->roles, true ) ) {
				return admin_url();
			} else {
				return home_url();
			}
		}

		// If not admin redirect to home page.
		return home_url();
	}

}
