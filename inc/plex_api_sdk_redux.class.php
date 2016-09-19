<?php
/**
 * Class Plex_API_SDK_Redux
 * https://github.com/mjs7231/python-plexapi/wiki/Unofficial-Plex-API-Documentation
 */
class Plex_API_SDK_Redux {
	public static $plex_private = 'https://127.0.0.1';
	public static $plex_public = 'https://plex.tv/api';
	private static $plex_server_token = PLEX_SERVER_TOKEN;

	/**
	 * @return array
	 */
	static function get_friends(){

		$request = \EasyRequest\Client::request(
			self::$plex_public.'/users',
			'GET',
			array(
				'header' => array(
					'User-Agent' => 'Firefox 45',
					'X-Plex-Token' => self::$plex_server_token
				),
			)
		);
		$response = $request->send();
		$xml_response = $response->getBody()->getContents();

		$data = self::plex_users_parser($xml_response);

		return $data;
	}

	/**
	 * @param $xml_string
	 *
	 * @return array
	 */
	static function plex_users_parser($xml_string) {
		$new_data = [];
		$data = new SimpleXMLElement($xml_string);

		foreach($data->children() as $user){
			$username = (string) $user->attributes()->username;
			$username = substr($username, 0, strpos($username, "@"));
			if( empty( $username ) ){
				$username = (string) $user->attributes()->title;
			}
			foreach( $user->attributes() as $key => $user_data ){
				$new_data[$username][$key] = (string) $user_data;
			}
		}

		return $new_data;
	}

	/**
	 *
	 * my.plexapp.com/users/sign_in.xml
	 */
	static function get_auth_code(){

	}

	/**
	 *
	 * plex.tv/api/resources
	 */
	static function get_servers(){

	}
}
