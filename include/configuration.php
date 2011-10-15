<?php
	
	// Biztonsági okok miatt letiltjuk a PHP hibaüzenetek megjelenítését a böngészoben
	// ini_set("display_errors", 0);

	// Adatbázis konfiguráció

	define("DB_HOST", "localhost");
	define("DB_USER", "");
	define("DB_PASSWORD", "");
	define("DB_DATABASE", "");
	
	// Session süti beállításai
	
	define("SESSION_COOKIE_NAME", "app_session");
	define("SESSION_COOKIE_EXPIRES", 3600 * 24 * 7);
	define("SESSION_COOKIE_DOMAIN", "");
	define("SESSION_COOKIE_SECURE", true);
	
	
	// UI beállítások
	
	define("ASSETS_URL", dirname(Helpers::getBaseUri()) . "/assets");

?>