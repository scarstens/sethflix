<?php
/**
 * Friends of the server page.
 *
 * @package sethflix.
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
			<p>Say hello to your fellow SethFlixers.</p>
		</div>
		<ul style="font-size: 1.4em; line-height: 24px; list-style: none;">
			<?php
			$friends = Plex_API_SDK_Redux::get_friends();

			foreach ( $friends as $username => $data ) {
				$av    = $data['thumb'];
				echo '
						<div class="col-xs-6 col-md-3">
							<a href="#" class="thumbnail" style="text-align: center;">
							  <img src="' . esc_url( $av ) . '" alt="friend-avatar-' . esc_attr( $username ) . '">
							  ' . esc_attr( $username ) . '
							</a>
						</div>
						';
			}
			echo '<br style="clear: both;" />'
			?>
		</ul>
	</div>
<?php

if ( function_exists( 'get_footer' ) ) {
	get_footer();
} else {
	include_once 'footer . php';
}
