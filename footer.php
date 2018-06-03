<?php
/**
 * Footer for site
 *
 * @package sethflix
 */

if ( function_exists( 'wp_footer' ) ) {
	wp_footer();
}
?>
<div class="navbar navbar-default navbar-fixed-bottom">
	<div class="container">
		<p class="navbar-text pull-left">© <?php echo date( "Y" ); ?> - 📺
			<a href="https://plex.tv" target="_blank">Plex.tv</a>
		</p>

		<a href="https://github.com/scarstens/sethflix" class="navbar-btn btn-info btn pull-right">
			<span class="glyphicon glyphicon-star"></span> Check us out on github.</a>
	</div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<!-- Custom Footer scripts -->
<?php
do_action( 'sethflix_footer_scripts' );
?>
</body >
</html >
