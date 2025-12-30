<div class="header">
	<h1>Unauthorized</h1>
</div>

<nav class="breadcrumbs">
	<ul>
		<li><a href="/">Movies</a></li>
		<li>Unauthorized</li>
	</ul>
</nav>

<div class="content">
	<div class="row">
		<div class="alert alert-warning">
			<p>
				<?php
				$message = $_GET['message'];
				if (isset($message) && $message !== '') {
					echo urldecode($message);
				} else {
				?>
					You are not authorized for this utility.
				<?php
				}
				?>
			</p>
		</div>
		<p>Please contact <a href="mailto:awspock@gmail.com?subject=Unauthorized Access">Alex</a> if you think this message is in error.</p>
	</div>
</div>