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
		$status = \Sethflix\Theme\Plex_API_SDK_Redux::get_server_status();
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
		<div class="row ecently-added-movies" style="margin-bottom: 20px">
			<div class="col-md-12 col-md-offset-0">
				<div class="carousel slide" id="myCarousel">
					<div class="carousel-inner">
						<?php \Sethflix\Theme\Plex_API_SDK_Redux::print_recently_added_movies(); ?>
					</div>
					<a class="left carousel-control" href="#myCarousel" data-slide="prev"><i
								class="glyphicon glyphicon-chevron-left"></i></a>
					<a class="right carousel-control" href="#myCarousel" data-slide="next"><i
								class="glyphicon glyphicon-chevron-right"></i></a>
				</div>
			</div>
		</div>
	</div>
<?php
add_action( 'sethflix_footer_scripts', function () {
	?>
	<script type="application/javascript">
			jQuery.noConflict();

			jQuery( document ).ready( function ( $ ) {
				$( '#myCarousel' ).carousel( {
					interval: false
				} );
				window.carousel_data = [];
				window.carousel_count = 0;
				$( '.carousel .item' ).each( function () {
					carousel_data[ carousel_data.length ] = $( this ).children( ':first-child' );
				} );

				$( '.carousel .item' ).each( function ( count ) {
					window.carousel_data_cursor = $( this );
					var total = carousel_data.length;
					var how_many_up = 4;
					var last_array_int = window.carousel_data.length - 1;

					// Dynamically build slide sets based on the how_many_up count.
					var slides = [];
					// Skip the first slide since its already in the grouping.
					for ( fcount = 1; fcount < how_many_up; fcount++ ) {
						slides[ fcount - 1 ] = count + fcount; // 4
						// If the slides count is greater then the total, use the remainder.
						// This is what loops back to beginning slides.
						if ( slides[ fcount - 1 ] >= total ) {
							slides[ fcount - 1 ] = slides[ fcount - 1 ] % total;
						}
					}
					// SLIDE 0 already exists in each group, only append extra thumbs.
					slides.forEach( function ( slide_num ) {
						// window.carousel_data[ slides[ slide_num ] ].clone().appendTo( $( this ) );
						window.carousel_data[ slide_num ].clone().appendTo( window.carousel_data_cursor );
					} );

				} );
			} );

	</script>
	<style>
		/* override position and transform in 3.3.x */
		.carousel-inner .item.left.active {
			transform: translateX(-25%);
		}

		.carousel-inner .item.right.active {
			transform: translateX(25%);
		}

		.carousel-inner .item.next {
			transform: translateX(25%)
		}

		.carousel-inner .item.prev {
			transform: translateX(-25%)
		}

		.carousel-inner .item.right,
		.carousel-inner .item.left {
			transform: translateX(0);
		}

		.carousel-control.left, .carousel-control.right {
			background-image: none;
		}
	</style>
	<?php
} );

if ( function_exists( 'get_footer' ) ) {
	get_footer();
} else {
	include_once 'footer.php';
}
