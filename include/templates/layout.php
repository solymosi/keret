<!DOCTYPE html>
<html>
	<head>
	
		<meta charset="UTF-8" />
		
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		
		<link rel="stylesheet" type="text/css" media="all" href="<?= Helpers::asset("css/all.css") ?>" />
		<link rel="shortcut icon" href="<?= Helpers::asset("images/favicon.ico") ?>" />
		
		<script type="text/javascript" src="<?= Helpers::asset("js/all.js") ?>"></script>
		
		<title><?= $this->title() ?></title>
		
	</head>
	
	<body>
		
		<?= $this->content ?>
		
	</body>
</html>