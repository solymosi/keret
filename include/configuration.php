<?php
	
	// Biztons�gi okok miatt letiltjuk a PHP hiba�zenetek megjelen�t�s�t a b�ng�szoben
	// ini_set("display_errors", 0);

	// Adatb�zis konfigur�ci�

	define("DB_HOST", "localhost");
	define("DB_USER", "");
	define("DB_PASSWORD", "");
	define("DB_DATABASE", "");
	
	// Session s�ti be�ll�t�sai
	
	define("SESSION_COOKIE_NAME", "app_session");
	define("SESSION_COOKIE_EXPIRES", 3600 * 24 * 7);
	define("SESSION_COOKIE_DOMAIN", "");
	define("SESSION_COOKIE_SECURE", true);
	
	
	// UI be�ll�t�sok
	
	define("ASSETS_URL", dirname(Helpers::getBaseUri()) . "/assets");

?>