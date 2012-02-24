<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Fatal Error</title>
		<style type="text/css">
			body { font-family: arial,helvetica,sans-serif; }
			h1 { color: #D00; letter-spacing: -0.05em; }
			small { color: #999; font-size: 0.7em }
		</style>
	</head>
	
	<body>
		
		<h1>EPIC FAIL :(</h1>
		
		<p><strong>An unexpected error occured while loading this page.</strong></p>
		
		<p>We have been notified about the error and will attempt to fix it as soon as possible.<br />Please return to the <a href="javascript:window.history.back()">previous page</a>.</p>
		
		<p><strong>We are very sorry for the trouble!</strong></p>
		
		<?php if(ini_get("display_errors") == true): ?>
			<p><small><?= Helpers::h($e->getMessage()) ?></small></p>
		<?php endif; ?>
		
	</body>
</html>