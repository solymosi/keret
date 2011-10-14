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
		
		<p><strong>The system encountered a fatal error.</strong></p>
		
		<p><small><?= Helpers::h($e->getMessage()) ?></small></p>
		
	</body>
</html>