<?php

/**
 *Use composer to load dependencies for Rackspace SKD. To do so,
 *use the following the same directory as this file.
 *
 *composer require rackspace/php-opencloud:dev-master
 * 
 */

require 'vendor/autoload.php';

use OpenCloud\Rackspace;
use Guzzle\Http\Exception\ClientErrorResponseException;

class Rackspace_Utility {

	/**
	 * Initialize rackspace connection and return container object.
	 *
	 * @return object FS API container object.
	 */
	protected static function init() {

		// Instantiate a Rackspace client.
		$client = new Rackspace( Rackspace::US_IDENTITY_ENDPOINT, array(
			'username' => 'fansidedrack',
			'apiKey'   => '9da4195de6184dc596682c857bd42eb7'
		));

		// Set the Region.
		$objectStoreService = $client->objectStoreService( null, 'DFW' );

		// This is files container that stores the data backups.
		$container = $objectStoreService->getContainer('api.fansided.com');

		return $container;
	}


	/**
	 * Uploads given full path of file.
	 *
	 * @param  string $file Full path of file.
	 * @return mixed        Returns false if 404 is returned.
	 */
	public static function upload_file( $file ) {

		$container = self::init();

		if ( ! file_exists( $file ) ) {
			echo "Error: No file entered...", PHP_EOL;
			return false;
		}

		$pathinfo = pathinfo( $file );
		$localFileName  = $file;
		$remoteFileName = $pathinfo['basename'];

		$handle = fopen( $localFileName, 'r' );
		try {
			return $container->uploadObject( $remoteFileName, $handle );
		} catch ( ClientErrorResponseException $e ) {
			if ( $e->getResponse()->getStatusCode() == 404 ) {
				echo 'failure';
				return false;
			}
		}
	}

	/**
	 * Clean Rackspace container of objects older than 1 year.
	 *
	 * @return null
	 */
	public static function clean_files() {
		$container = self::init();

		$files = $container->objectList();

		foreach ($files as $file) {
			$last_modified = $file->getLastModified();
			if ( strtotime( $last_modified ) < strtotime('-1 min') ) {
				$file->delete();
			}
		}
	}

}
