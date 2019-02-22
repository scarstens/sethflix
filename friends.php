<?php
//initialize application
include_once('server_config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once('inc/part.head.php'); ?>
</head>
<body role="document">
<?php include_once('inc/part.header-nav.php'); ?>
<div class="container theme-showcase" role="main">
	<div class="jumbotron">
		<div class="container">
		 <div class="row">
        <div class="col-md-2">
            <div class="imgAbt">
                <img src="images/sethflix-icon.png" style="width: 100%; height: auto;" />
            </div>
        </div>
        <div class="col-md-10">
            <h1>Sethflix</h1>
        </div>
    </div>
		</div>
		<p>Say hello to your fellow SethFlixers.</p>
		<ul style="font-size: 1.4em; line-height: 24px; list-style: none;">
			<?php
			$friends = Plex_API_SDK_Redux::get_friends();

			foreach ($friends as $username => $data){
				$dat = print_r($data, true);
				$av = $data['thumb'];
				$extra  = '';
				if( ! empty($_REQUEST['extra']) || ! empty( $_COOKIE['extra'] ) ){
					$extra = "<br /><div class=\"data\" style=\"\">$dat</div>";
				}
				$li_style = 'background: url(' . $av . ') no-repeat; background-size: 24px; padding: 0px 0px 5px 30px;';
				echo "<li style=\"$li_style\">$username$extra</li>".PHP_EOL;
			}
			?>
		</ul>
	</div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</body>
</html>
