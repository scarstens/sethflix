<?php

//disable auth requirement on login page
define('BYPASS_AUTH', true);

//initialize application
include_once('server_config.php');

$auth_cookie_name = 'x-passkey';
if( ! empty( $_REQUEST['noredirect'] ) ){
	$noredirect = 1;
} else {
	$noredirect = 0;
}

if( isset( $_POST[$auth_cookie_name] ) ){
	$res_set = setcookie( $auth_cookie_name, $_POST[$auth_cookie_name], time()+(60*60*24) );
	if( empty( $noredirect ) ){
		header('Location: http://sethflix.com/?login=true_'.time().'&res='.$res_set, true, 302);
		exit;
	}
}

if(isset($_REQUEST['reauth']) && stristr($_REQUEST['reauth'], 'logout')){
	unset($_COOKIE[$auth_cookie_name]);
	// empty value and expiration one hour before
	$res_unset = setcookie($auth_cookie_name, '', time() - 3600);

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once('inc/part.head.php'); ?>
</head>
<body role="document">
<?php include_once('inc/part.header-nav.php'); ?>
<div class="container">
	<?php if( ! empty($noredirect) ): ?>
	<div class="well"><pre>
		<?php
			var_export($_POST);
			echo "<br><br>";
			var_export($_COOKIE);
			echo "<br><br>";
			echo 'res_set: '.$res_set;
			echo "<br><br>";
			echo 'res_unset: '.$res_unset;
		 ?>
	</pre></div>
	<?php endif; ?>
	<form class="form-signin" method="post" action="/login.php?reauth=login">
	  <h2 class="form-signin-heading">Please sign in</h2>
	  <label for="inputPassword" class="sr-only">Invite Code</label>
	  <input type="password" id="<?php echo $auth_cookie_name; ?>" name="<?php echo $auth_cookie_name; ?>" class="form-control" placeholder="passkey" required>
		<input type="hidden" name="noredirect" value="<?php echo $noredirect ?>" />
	  <div class="checkbox">
	    <label>
	      <input type="checkbox" value="remember-me"> Remember me
	    </label>
	  </div>
	  <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
	</form>
</div> <!-- /container -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</body>
</html>
