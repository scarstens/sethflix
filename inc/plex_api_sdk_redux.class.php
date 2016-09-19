<?php

/**
 * Class Plex_API_SDK_Redux
 * https://github.com/mjs7231/python-plexapi/wiki/Unofficial-Plex-API-Documentation
 */
class Plex_API_SDK_Redux {
	public static $plex_private = 'http://server.sethflix.com:32400';
	public static $plex_public = 'https://plex.tv/api';
	private static $plex_server_token = PLEX_SERVER_TOKEN;

	/**
	 * @return array
	 */
	static function get_friends() {

		$request      = \EasyRequest\Client::request(
			self::$plex_public . '/users',
			'GET',
			array(
				'header' => array(
					'User-Agent'   => 'Firefox 45',
					'X-Plex-Token' => self::$plex_server_token
				),
			)
		);
		$response     = $request->send();
		$xml_response = $response->getBody()->getContents();

		$data = self::plex_users_parser( $xml_response );

		return $data;
	}


	/**
	 * @param $xml_string
	 *
	 * @return array
	 */
	static function plex_users_parser( $xml_string ) {
		$new_data = [];
		$data     = new SimpleXMLElement( $xml_string );

		foreach ( $data->children() as $user ) {
			$username = (string) $user->attributes()->username;
			$username = substr( $username, 0, strpos( $username, "@" ) );
			if ( empty( $username ) ) {
				$username = (string) $user->attributes()->title;
			}
			foreach ( $user->attributes() as $key => $user_data ) {
				$new_data[ $username ][ $key ] = (string) $user_data;
			}
		}

		return $new_data;
	}

	static function plex_sections_parser( $xml_string, $each = 'Video', $name_att = 'title' ) {
		$new_data = [];
		$data     = new SimpleXMLElement( $xml_string );
		//echo '<pre style="overflow: scroll; height: 500px;">' . print_r( $data, true ) . '</pre>';
		$i = 0;
		foreach ( $data->children() as $child ) {
			$i ++;
			$label = (string) $child->attributes()->$name_att;
			$label = substr( $label, 0, strpos( $label, "@" ) );
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
	 *
	 * my.plexapp.com/users/sign_in.xml
	 */
	static function get_auth_code() {

	}

	/**
	 *
	 * plex.tv/api/resources
	 */
	static function get_servers() {

	}

	static function get_recently_added_movies( $section = '1' ) {
		$request      = \EasyRequest\Client::request(
			self::$plex_private . '/library/sections/' . $section . '/recentlyAdded',
			'GET',
			array(
				'header' => array(
					'User-Agent'   => 'Plex_API_SDK_Redux(LinuxClient)',
					'X-Plex-Token' => self::$plex_server_token
				),
			)
		);
		$response     = $request->send();
		$xml_response = $response->getBody()->getContents();
		$data         = self::plex_sections_parser( $xml_response );

		return $data;
	}

	/**
	 * @param string $section
	 * TODO: fix security by forcing login and custom per login token
	 */
	public static function print_recently_added_movies( $section = '1' ) {
		$movies = self::get_recently_added_movies( $section );
		echo '<div class="recently-added"><style>.recently-added{padding-top:10px;}.recently-added:before { position: relative; top: -10px; left: 0px; font-size: 12px; font-weight: 700; color: #959595; text-transform: uppercase; letter-spacing: 1px; content: "Recently Added";}</style><div class="row">';
		$i=0;
		foreach ( $movies as $label => $movie ) {
			$i++;
			$thumb = self::$plex_private . $movie['thumb'] . '?X-Plex-Token='.self::$plex_server_token;
			echo '<div class="col-xs-6 col-md-3"><a href="#" class="thumbnail"><img src="'.$thumb.'" alt="..."></a></div>';
			if($i>3){break;}
		}
		echo '</div></div>';
	}
}
