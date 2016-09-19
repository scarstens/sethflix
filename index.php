<?php
//initialize application
include_once( 'server_config.php' );
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once( 'inc/part.head.php' ); ?>
</head>
<body role="document">
<?php include_once( 'inc/part.header-nav.php' ); ?>
<div class="container theme-showcase" role="main">
	<div class="jumbotron">
		<div class="container">
			<div class="row">
				<div class="col-md-2">
					<div class="imgAbt">
						<img src="images/sethflix-icon.png" style="width: 100%; height: auto;"/>
					</div>
				</div>
				<div class="col-md-10">
					<h1>Sethflix</h1>
				</div>
			</div>
		</div>
		<p>Welcome to the new age of streaming media.</p>
		<a href="http://plex.tv">
			<button type="button" class="btn btn-default btn-upsize" aria-label="Left Align">
				<span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span>
				Plex Media Player
			</button>
		</a>
		<a href="http://server.sethflix.com:3000/">
			<button type="button" class="btn btn-default btn-upsize" aria-label="Left Align">
				<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
				Plex Requests
			</button>
		</a>
		<a href="http://status.sethflix.com/">
			<button type="button" class="btn btn-default btn-upsize" aria-label="Left Align">
				<span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
				Sethflix Status
			</button>
		</a>
		<a href="http://sethflix.com/friends.php">
			<button type="button" class="btn btn-default btn-upsize" aria-label="Left Align">
				<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
				Sethflix Friends
			</button>
		</a>
	</div>
	<div class="recently-added-movies">
		<?php Plex_API_SDK_Redux::print_recently_added_movies(); ?>
	</div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</body>
</html>
