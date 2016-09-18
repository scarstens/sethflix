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
        <?php if( ! Simple_Cookie_Auth::user_logged_in() ):  ?>
        <li id="nav-login"><a href="/login.php?reauth=login">Login</a></li>
        <?php elseif(true) : ?>
        <li id="nav-logout"><a href="/login.php?reauth=logout">Logout</a></li>
        <?php endif; ?>
			</ul>
		</div>
		<!--/.nav-collapse -->
	</div>
</nav>
