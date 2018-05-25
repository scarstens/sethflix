<?php
/**
 * Primary nginx file, loads first.
 *
 * @package sethflix
 */

use Sethflix\Theme\App;

if ( function_exists( 'get_header' ) ) {
	get_header();
} else {
	include_once 'header.php';
}
?>
	<div class="container theme-showcase" role="main">
		<div class="jumbotron">
			<div class="container">
				<div class="row">
					<div class="col-md-2">
						<div class="imgAbt">
							<?php App::the_logo(); ?>
						</div>
					</div>
					<div class="col-md-10">
						<h1><?php echo esc_attr( App::get_title() ); ?></h1>
					</div>
				</div>
			</div>
			<p>Welcome to the new age of streaming media.</p>
			<a href="https://plex.tv">
				<button type="button" class="btn btn-default btn-upsize" aria-label="Left Align">
					<span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span>
					Plex Media Player
				</button>
			</a>
			<a href="https://server.sethflix.com:3579/">
				<button type="button" class="btn btn-default btn-upsize" aria-label="Left Align">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					Plex Requests
				</button>
			</a>
			<a href="<?php echo esc_attr( '/friends' . ROUTE_SUFFIX ); ?>">
				<button type="button" class="btn btn-default btn-upsize" aria-label="Left Align">
					<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
					Sethflix Friends
				</button>
			</a>
		</div>
		<?php
		$status = Plex_API_SDK_Redux::get_server_status();
		if ( empty( $status ) ) {
			$status_style   = 'danger';
			$status_comment = 'OFFLINE';
		} else {
			$status_style   = 'success';
			$status_comment = 'OK';
		}
		?>
		<div class="bs-callout bs-callout-<?php echo esc_attr( $status_style ); ?>" id="callout-alerts-no-default">
			<h4>Sethflix Status: <?php echo esc_attr( $status_comment ); ?></h4>
			<p>If this is red, something is wrong with Sethflix servers. Usually this is temporary and the servers will
				be
				restarted shortly. If this occurs for 24 hours, please contact your Sethflix advisor for more
				information.</p></div>
		<div class="recently-added-movies">
			<?php Plex_API_SDK_Redux::print_recently_added_movies(); ?>
		</div>
	</div>
<?php

if ( function_exists( 'get_footer' ) ) {
	get_footer();
} else {
	include_once 'footer.php';
}
