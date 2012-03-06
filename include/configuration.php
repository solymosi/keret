<?php
	
	//=================
	// PHP beállítások
	//=================
	
	// Biztonsági okok miatt letiltjuk a PHP hibaüzenetek megjelenítését a böngészőben
	ini_set("display_errors", 0);
	
	// Beállítjuk, hogy minden létező hiba jelentésre kerüljön
	ini_set("error_reporting", E_ALL | E_STRICT);
	
	// Beállítjuk az oldal karakterkódolását
	header("Content-Type: text/html; charset=UTF-8");
	
	// Beállítjuk a multibyte szövegfüggvények alapértelmezett nyelvét
	mb_internal_encoding("utf-8");
	
	// Beállítjuk az oldal időzónáját
	date_default_timezone_set("Europe/Budapest");

	//=======================
	// Adatbázis beállításai
	//=======================

	define("DB_HOST", "localhost");
	define("DB_USER", "");
	define("DB_PASSWORD", "");
	define("DB_DATABASE", "");
	
	//==========================
	// Session süti beállításai
	//==========================
	
	// A süti neve a böngészőben
	define("SESSION_COOKIE_NAME", "app_session");
	
	// A süti ennyi másodpercig érvényes (0: a böngésző bezárásáig)
	define("SESSION_COOKIE_EXPIRES", 0);
	
	// A süti csak ezen a domain-en működik (biztonsági feature)
	define("SESSION_COOKIE_DOMAIN", "");
	
	// A süti csak HTTPS kapcsolaton működik (biztonsági feature)
	define("SESSION_COOKIE_SECURE", true);
	
	//===================
	// Email beállítások
	//===================
	
	// Erről az email címről mennek az automatikus üzenetek
	define("MAIL_FROM", "SolymosiNet <system@solymosi.eu>");
	
	// Hibaüzenetek automatikus elküldése emailben
	define("MAIL_EXCEPTIONS", false); //true);
	
	// A tulajdonos email címe
	define("OWNER_EMAIL", "");
	
	// Az üzemeltető email címe
	define("ADMIN_EMAIL", "Solymosi Máté <mate@solymo.si>");
	
	//================
	// UI beállítások
	//================
	
	// Ezt a Helpers::asset() függvény használja, hogy tudja, mit fűzzön a megadott útvonal elé.
	// Példa az értékére: https://apro.kozgaz.net/assets
	define("ASSETS_URL", str_replace("/index.php", "", Helpers::getBaseUri()) . "/assets");

?>