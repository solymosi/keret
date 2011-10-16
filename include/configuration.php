<?php
	
	// ini_set("display_errors", 0);
	
	ini_set("error_reporting", E_ALL | E_STRICT);
	
	mb_internal_encoding("utf-8");

	define("DB_HOST", "localhost");
	define("DB_USER", "");
	define("DB_PASSWORD", "");
	define("DB_DATABASE", "");
	
	define("SESSION_COOKIE_NAME", "app_session");
	define("SESSION_COOKIE_EXPIRES", 0);
	define("SESSION_COOKIE_DOMAIN", "");
	define("SESSION_COOKIE_SECURE", true);
	
	define("ASSETS_URL", dirname(Helpers::getBaseUri()) . "/assets");

?>