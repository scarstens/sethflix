<?php
/**
 * Header file starts the DOM output.
 *
 * @package sethflix
 */

$favicon = APP_SERVER_URI . 'assets/images/sethflix.favicon.ico';
?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Dashboard</title>
		<?php
		if ( function_exists( 'wp_head' ) ) {
			wp_head();
		}
		?>
		<link rel="icon" type="image/x-icon" href="<?php echo esc_url( $favicon ); ?>"/>
		<link rel="icon" type="image/png" href="<?php echo esc_url( $favicon ); ?>"/>
		<link rel="icon" type="image/gif" href="<?php echo esc_url( $favicon ); ?>"/>
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/style.css'; ?>">
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="//bootstrapdocs.com/v3.3.6/docs/assets/css/docs.min.css">
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

	</head>
	<body <?php body_class(); ?> role="document">
<?php
include_once( 'inc/part.header-nav.php' );
