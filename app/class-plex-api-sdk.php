<?php
/**
 * Plex_API_SDK_Redux
 *
 * @package sethflix
 */

namespace Sethflix\Theme;

/**
 * Class Plex_API_SDK_Redux
 * https://github.com/mjs7231/python-plexapi/wiki/Unofficial-Plex-API-Documentation
 * consider https://github.com/jc21/plex-api or https://github.com/nickbart/php-plex
 */
class Plex_API_SDK_Redux {

	/**
	 * URL to the private server including port if required.
	 *
	 * @var string
	 */
	public static $plex_private = [
		0 => 'http://server.sethflix.com:32400',
		1 => 'https://192-168-1-135.42220d51d23947baa44cf9e323c90563.plex.direct:32400',
		2 => 'https://192.168.1.10:32400',
	];

	/**
	 * URL to node app that loads Plex client, plex.tv is always viable.
	 *
	 * @var string
	 */
	public static $plex_public = 'https://plex.tv';

	/**
	 * Get friends list from the Plex API
	 *
	 * @param int $server
	 *
	 * @return array|bool
	 */
	static function get_friends( $server = 0 ) {

		$request  = \EasyRequest\Client::request(
			self::$plex_public . '/api/users',
			'GET',
			[
				'header' => [
					'User-Agent'   => 'EasyRequest',
					'X-Plex-Token' => apply_filters( 'PLEX_SERVER_TOKEN', '' )[ $server ],
				],
			]
		);
		$response = $request->send();
		if ( empty( $response ) ) {
			return false;
		}
		$xml_response = $response->getBody()->getContents();

		$data = self::plex_users_parser( $xml_response );
		$data = array_merge( $data, self::get_account_admin() );
		ksort( $data );

		return $data;
	}

	/**
	 * Get account admin based on server token.
	 *
	 * @param int $server
	 *
	 * @return array|bool
	 */
	static function get_account_admin( $server = 0 ) {

		$request  = \EasyRequest\Client::request(
			self::$plex_public . '/users/account',
			'GET',
			[
				'header' => [
					'User-Agent'   => 'EasyRequest',
					'X-Plex-Token' => apply_filters( 'PLEX_SERVER_TOKEN', '' )[ $server ],
				],
			]
		);
		$response = $request->send();
		if ( empty( $response ) ) {
			return false;
		}
		$xml_response = $response->getBody()->getContents();

		$data = self::plex_account_parser( $xml_response );

		return $data;
	}


	/**
	 * Handle parsing the users data into PHP Array
	 *
	 * @param string $xml_string XML string returned from Plex API.
	 *
	 * @return array
	 */
	static function plex_account_parser( $xml_string ) {
		$new_data = [];
		$data     = new \SimpleXMLElement( $xml_string );
		$username = (string) $data->attributes()->username;
		if ( stristr( $username, '@' ) ) {
			$username = substr( $username, 0, strpos( $username, '@' ) );
		}
		foreach ( $data->children() as $field => $part ) {
			if ( is_string( $part ) ) {
				$new_data[ $username ][ $field ] = $part;
			}
		}
		foreach ( $data->attributes() as $key => $user_data ) {
			$new_data[ $username ][ $key ] = (string) $user_data;
		}

		return $new_data;
	}

	/**
	 * Handle parsing the users data into PHP Array
	 *
	 * @param string $xml_string XML string returned from Plex API.
	 *
	 * @return array
	 */
	static function plex_users_parser( $xml_string ) {
		$new_data = [];
		$data     = new \SimpleXMLElement( $xml_string );

		foreach ( $data->children() as $user ) {
			$username = (string) $user->attributes()->username;
			$username = substr( $username, 0, strpos( $username, '@' ) );
			if ( empty( $username ) ) {
				$username = (string) $user->attributes()->title;
			}
			foreach ( $user->attributes() as $key => $user_data ) {
				$new_data[ $username ][ $key ] = (string) $user_data;
			}
		}

		return $new_data;
	}

	/**
	 * Plex sections parser
	 *
	 * @param string $xml_string XML String from Plex API.
	 * @param string $each       What to parse from the sections.
	 * @param string $name_att   What to consider the title of each object.
	 *
	 * @return array
	 */
	static function plex_sections_parser( $xml_string, $each = 'Video', $name_att = 'title' ) {
		$new_data = [];
		if ( empty( $xml_string ) ) {
			echo 'BAD XML RESPONSE. SEE RESPONSE BELOW:' . PHP_EOL;
			echo __FUNCTION__ . PHP_EOL;
			print_r( $xml_string );
		}

		try {
			$data = new \SimpleXMLElement( $xml_string );
		} catch ( Exception $e ) {
			echo 'BAD XML RESPONSE. SEE RESPONSE BELOW:' . PHP_EOL;
			echo __FUNCTION__ . PHP_EOL;
			print_r( $xml_string );
			exit;
		}

		$i = 0;
		foreach ( $data->children() as $child ) {
			$i ++;
			$label = (string) $child->attributes()->$name_att;
			$label = substr( $label, 0, strpos( $label, '@' ) );
			if ( empty( $label ) ) {
				$label = (string) $child->attributes()->title;
			}
			if ( empty( $label ) ) {
				$label = 'no_label_id__' . $i;
			}
			foreach ( $child->attributes() as $key => $data ) {
				$new_data[ $label ][ $key ] = (string) $data;
			}
		}

		return $new_data;
	}

	/**
	 * Helper function to get auth code.
	 *
	 * @see my.plexapp.com/users/sign_in.xml
	 */
	static function get_auth_code() {

	}

	/**
	 * Helper function to get all servers related to account.
	 * plex.tv/api/resources
	 */
	static function get_servers() {

	}

	/**
	 * Get recently added movies
	 *
	 * @param string $section Section where movies are found in API response.
	 *
	 * @return array|bool
	 */
	static function get_recently_added_movies( $section = '1', $server = 0 ) {
		$request  = \EasyRequest\Client::request(
			self::$plex_private[ $server ] . '/library/sections/' . $section . '/recentlyAdded',
			'GET',
			[
				'header' => [
					'User-Agent'   => 'Plex_API_SDK_Redux(LinuxClient)',
					'X-Plex-Token' => apply_filters( 'PLEX_SERVER_TOKEN', '' )[ $server ],
				],
			]
		);
		$response = $request->send();
		if ( empty( $response ) ) {
			return false;
		}
		$xml_response = $response->getBody()->getContents();
		$data         = self::plex_sections_parser( $xml_response );

		return $data;
	}

	/**
	 * Echo out recently added movies
	 *
	 * @param string $section Place in array where movies are stored in API response.
	 *
	 * TODO: fix security by forcing login and custom per login token.
	 * @param int    $server
	 */
	public static function print_recently_added_movies( $section = '1', $server = 0 ) {
		$movies = self::get_recently_added_movies( $section );
		$i      = 0;
		$active = ' active';
		foreach ( $movies as $label => $movie ) {
			$i ++;

			$thumb = self::$plex_private[ $server ] . $movie['thumb'] . '?X-Plex-Token=' . apply_filters( 'PLEX_SERVER_TOKEN', '' )[ $server ];
			echo '<div id="item_' . $i . '" class="item' . $active . '"><div id="thumb_' . $i . '" class="col-xs-6 col-md-3"><a href="#" class="thumbnail"><img src="' . $thumb . '" alt="..." class="img-responsive"></a></div></div>';
			$active = '';
			if ( $i > 6 ) {
				break;
			}
		}
	}

	/**
	 * Get the servers status (online or not)
	 *
	 * @param int $server
	 *
	 * @return mixed
	 */
	public static function get_server_status( $server = 0 ) {
		$request  = \EasyRequest\Client::request(
			self::$plex_private[ $server ] . '/',
			'GET',
			[
				'header' => [
					'User-Agent'   => 'Plex_API_SDK_Redux(LinuxClient)',
					'X-Plex-Token' => apply_filters( 'PLEX_SERVER_TOKEN', '' )[ $server ],
				],
			]
		);
		$response = $request->send();
		if ( empty( $response ) ) {
			return false;
		}
		$xml_response = $response->getBody()->getContents();
		$data         = self::plex_sections_parser( $xml_response );

		return true;
	}

	/**
	 * Get decades options of movies in plex
	 *
	 * @param string $section Section where movies are found in API response.
	 *
	 * @param int    $server
	 *
	 * @return array|bool
	 */
	static function get_decades( $section = '1', $server = 0 ) {
		$request_url = self::$plex_private[ $server ] . '/library/sections/' . $section . '/decade';
		self::print_x( 'Request URL: ' . $request_url . PHP_EOL );
		$request  = \EasyRequest\Client::request(
			$request_url,
			'GET',
			[
				'header' => [
					'User-Agent'   => 'Plex_API_SDK_Redux(LinuxClient)',
					'X-Plex-Token' => apply_filters( 'PLEX_SERVER_TOKEN', '' )[ $server ],
				],
			]
		);
		$response = $request->send();
		self::print_x( 'Response Data: ' . PHP_EOL );
		self::print_x( $response );
		self::print_x( PHP_EOL );
		if ( empty( $response ) ) {
			return false;
		}
		$xml_response = $response->getBody()->getContents();
		$data         = self::plex_sections_parser( $xml_response );

		return $data;
	}

	/**
	 * Get decades options of movies in plex
	 *
	 * @param string $section Section where movies are found in API response.
	 * @param string $decade  The year of the decade to get movies from
	 * @param int    $server
	 *
	 * @return array|bool
	 */
	static function get_decades_assets( $section = '1', $decade = '2000', $server = 0 ) {
		$request_url = self::$plex_private[ $server ] . '/library/sections/' . $section . '/decade/' . $decade . '?checkFiles=1&includeExtras=1&includeBandwidths=1';
		self::print_x( 'Request URL: ' . $request_url . PHP_EOL );
		$request  = \EasyRequest\Client::request(
			$request_url,
			'GET',
			[
				'header' => [
					'User-Agent'   => 'Plex_API_SDK_Redux(LinuxClient)',
					'X-Plex-Token' => apply_filters( 'PLEX_SERVER_TOKEN', '' )[ $server ],
				],
			]
		);
		$response = $request->send();
		if ( empty( $response ) ) {
			return false;
		}
		$xml_response = $response->getBody()->getContents();
		$data         = self::plex_sections_parser( $xml_response );

		return $data;
	}

	/**
	 * Get decades options of movies in plex
	 *
	 * @param     $asset_id
	 * @param int $server
	 *
	 * @return array|bool
	 */
	static function get_asset_metadata( $asset_id, $server = 0 ) {
		$request  = \EasyRequest\Client::request(
			self::$plex_private[ $server ] . '/library/metadata/' . $asset_id,
			'GET',
			[
				'header' => [
					'User-Agent'   => 'Plex_API_SDK_Redux(LinuxClient)',
					'X-Plex-Token' => apply_filters( 'PLEX_SERVER_TOKEN', '' )[ $server ],
				],
			]
		);
		$response = $request->send();
		if ( empty( $response ) ) {
			return false;
		}
		$xml_response = $response->getBody()->getContents();
		$data         = self::plex_sections_parser( $xml_response );

		return $data;
	}

	public static function print_x( $data ) {
		if ( ! empty( $_REQUEST['debug'] ) ) {
			print_r( $data );
		}
	}
}
