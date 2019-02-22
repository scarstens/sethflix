<?php
/**
 * Custom WordPress API Endpoints for this App
 *
 * @package sethflix
 */

namespace Sethflix\Theme;

/**
 * Class App
 */
class Api {

	/**
	 * App constructor.
	 */
	public static function register_api() {
		add_action( 'rest_api_init', function () {
			register_rest_route( 'export/v1', '/movies/', [
				'methods'  => 'GET',
				'callback' => __CLASS__ . '::movies_callback',
			] );
			register_rest_route( 'export/v1', '/movies/decade/(?P<id>\d+)', [
				'methods'  => 'GET',
				'callback' => __CLASS__ . '::get_movies_by_decade',
				'args'     => [
					'id' => [
						'validate_callback' => function ( $param, $request, $key ) {
							return is_numeric( $param );
						},
					],
				],
			] );
		} );
	}

	public static function movies_callback( $data ) {
		return 'use decades endpoint...';
	}

	/**
	 *
	 *
	 * @param \WP_REST_Request $request
	 */
	public static function get_movies_by_decade( \WP_REST_Request $request ) {
		$section_id = 1;
		$server     = 0;
		if ( $request->get_param( 'section' ) ) {
			$section_id = $request->get_param( 'section' );
		}
		if ( $request->get_param( 'server' ) ) {
			$server = $request->get_param( 'server' );
		}

		if ( $request->get_param( 'id' ) ) {
			if ( 9999 == $request->get_param( 'id' ) ) {
				$decades_list = \Sethflix\Theme\Plex_API_SDK_Redux::get_decades( $section_id, $server );
			} else {
				$decades_list[ $request->get_param( 'id' ) . 's' ] = [ 'key' => $request->get_param( 'id' ) ];
			}
		} else {
			$decades_list[ $request->get_param( 'id' ) . 's' ] = [ 'key' => '1940' ];
		}
		self::print_x( $decades_list );

		foreach ( $decades_list as $decade => $decade_data ) {
//			echo $decade_data['key'] . PHP_EOL;
			$movies_list = \Sethflix\Theme\Plex_API_SDK_Redux::get_decades_assets( $section_id, $decade_data['key'], $server );
			self::print_x( '$movies_list:' . PHP_EOL );
			self::print_x( $movies_list );
			foreach ( $movies_list as $movie_key => $movie_data ) {
				$asset_id = $movie_data['ratingKey'];
				if ( ( empty( 'CACHE_ON' ) )
				     || ( false === ( $cache_value = get_transient( 'asset_metadata_' . $server . '_' . $asset_id ) ) ) ) {
					// this code runs when there is no valid transient set
					$movie = \Sethflix\Theme\Plex_API_SDK_Redux::get_asset_metadata( $asset_id, $server );
//					print_r( $movie );

					//ex: com.plexapp.agents.imdb://tt0033563?lang=en
					$re  = '/tt(\d+)/m';
					$str = end( $movie )['guid'];
					preg_match_all( $re, $str, $matches, PREG_SET_ORDER, 0 );
					if ( ! isset( $matches[0][0] ) ) {
						$imdb_id = $str;
					} else {
						$imdb_id = $matches[0][0];
					}

					$cache_value = [
						'title'   => end( $movie )['title'],
						'imdb_id' => $imdb_id,
					];
					if ( ! empty( $movie ) ) {
						set_transient( 'asset_metadata_' . $server . '_' . $asset_id, $cache_value, 2 * HOUR_IN_SECONDS );
					}
				}

				$assets_export[] = $cache_value;

			}
		}


		return wp_send_json( $assets_export );
	}

	public static function print_x( $data ) {
		if ( ! empty( $_REQUEST['debug'] ) ) {
			print_r( $data );
		}
	}
}
