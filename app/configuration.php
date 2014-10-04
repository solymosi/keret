<?php

	// Turn on debug mode
	Config::set("debug", true);
	
	// Title of the application
	Config::set("app.title", "Application");
	
	// Internal name of application; use only a-z, 0-9 and underscore (_)
	Config::set("app.internal_name", "application");
	
	// Domain name the application is running on
	Config::set("app.domain", "localhost");
	
	// Set default time zone
	Config::set("default_timezone", "Europe/Zurich");
	
	// Database settings
	Config::set("database.host", "localhost");
	Config::set("database.user", "");
	Config::set("database.password", "");
	Config::set("database.name", "");
	
	// Email address of site administrator
	Config::set("mail.admin_email", "Administrator <admin@example.org>");