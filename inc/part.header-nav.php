<?php
/**
 * Header navigation (dynamic)
 *
 * @package sethflix
 */

?>
<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
					aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
			</button>
			<span class="navbar-brand">Sethflix</span>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li id="nav-portal"><a href="/">Portal</a></li>
				<?php \Sethflix\Theme\App::nav_login_logout_link(); ?>
			</ul>
		</div>
		<!--/.nav-collapse -->
	</div>
</nav>
