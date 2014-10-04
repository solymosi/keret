<?php

	Config::set("debug", true);
	
	Config::set("app.internal_name", "application");
	Config::set("app.domain", "localhost");
	
	Config::set("default_timezone", "Europe/Zurich");

	//===================
	// Database settings
	//===================

	define("DB_HOST", "localhost");
	define("DB_USER", "");
	define("DB_PASSWORD", "");
	define("DB_DATABASE", "");
	
	//=========================
	// Session cookie settings
	//=========================
	
	// Name of session cookie
	define("SESSION_COOKIE_NAME", "app_session");
	
	// Session cookie expiration in seconds (0: when the browser is closed)
	define("SESSION_COOKIE_EXPIRES", 0);
	
	// Limit session cookie to this domain (security feature)
	define("SESSION_COOKIE_DOMAIN", "");
	
	// Limit session cookie to secure connections (security feature)
	define("SESSION_COOKIE_SECURE", false);
	
	//================
	// Email settings
	//================
	
	// From address for all emails
	define("MAIL_FROM", "Application <application@example.org>");
	
	// Email address of site owner
	define("OWNER_EMAIL", "Owner <owner@example.org>");
	
	// Email address of site administrator
	define("ADMIN_EMAIL", "Administrator <admin@example.org>");
	
	// Mail exceptions to site administrator
	define("MAIL_EXCEPTIONS", false);
	
	//===============
	// View settings
	//===============
	
	// Base URL of asset files, used for asset link generation (should not end with a slash)
	define("ASSETS_URL", str_replace("/index.php", "", Helpers::getBaseUri()) . "/assets");
	
	//==============
	// File uploads
	//==============
	
	// Enable file uploads
	ini_set("file_uploads", 1);
	
	// Max. file size in kilobytes
	define("MAX_FILE_SIZE", 10 * 1024);

?>