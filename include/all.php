<?php

	// Minden megosztott fájlt betöltünk
	
	// Mindenféle segédfüggvények
	require_once("helpers.php");
	
	// Kostansok
	require_once("constants.php");
	
	// Megjelenítés-kezelő
	require_once("view.php");
	require_once("template.php");
	
	// Hibakezelő
	require_once("error.php");
	
	// Beállítások
	require_once("configuration.php");
	
	// Adatbázis-kezelő
	require_once("database.php");
	
	// Session-kezelő
	require_once("session.php");
	
	// Route kezelő
	require_once("routing.php");
	
	// Form építő
	require_once("form/all.php");

?>