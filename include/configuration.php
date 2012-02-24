<?php
	
	ini_set("display_errors", 0);
	ini_set("error_reporting", E_ALL | E_STRICT);
	
	header("Content-Type: text/html; charset=UTF-8");
	
	mb_internal_encoding("utf-8");
	
	date_default_timezone_set("Europe/Budapest");

	define("DB_HOST", "localhost");
	define("DB_USER", "");
	define("DB_PASSWORD", "");
	define("DB_DATABASE", "");
	
	define("SESSION_COOKIE_NAME", "app_session");
	define("SESSION_COOKIE_EXPIRES", 0);
	define("SESSION_COOKIE_DOMAIN", "");
	define("SESSION_COOKIE_SECURE", true);
	
	define("MAIL_FROM", "SolymosiNet <system@solymosi.eu>");
	define("MAIL_EXCEPTIONS", false); //true);
	define("OWNER_EMAIL", "");
	define("ADMIN_EMAIL", "Solymosi Máté <mate@solymo.si>");
	
	define("ASSETS_URL", str_replace("/index.php", "", Helpers::getBaseUri()) . "/assets");

?>