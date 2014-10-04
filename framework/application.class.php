<?php

	class Application
	{
		static protected $root;
		static protected $configuration = array();
		
		static public function initialize($root)
		{
			self::$root = $root;
			
			ErrorHandler::install();
			
			Autoload::registerPath($root . "/controllers");
			Autoload::registerPath($root . "/lib");
			Autoload::registerPath($root . "/vendor");
			
			self::configure();
		}
		
		static public function run()
		{
			ob_start();
			
			try
			{
				try
				{
					DB::connect();
					
					Session::initialize();
					
					require self::root() . "/routes.php";
					
					Helpers::notFound();
				}
				catch(ProcessingFinished $e) { }
				catch(NotFoundException $e)
				{
					Helpers::clearOutput();
					Helpers::setStatusCode("404 Not Found");
					View::render("notFound");
				}
			}
			catch(Exception $e)
			{
				Helpers::clearOutput();
				Helpers::setStatusCode("500 Internal Server Error");
				
				if(MAIL_EXCEPTIONS)
				{
					Helpers::sendMail(ADMIN_EMAIL, "[ProUni] " . $e->getMessage(), "An unhandled " . get_class($e) . " occured on " . date("l, j F Y H:i:s A") . ":\r\n\r\n" . $e->getMessage() . "\r\nRequest URI: " . $_SERVER["REQUEST_URI"] . "\r\n\r\n" . print_r(@$_POST, true));
				}
				
				require_once("include/templates/errorMessage.php");
			}

			ob_end_flush();
		}
		
		static public function root()
		{
			return self::$root;
		}
		
		static protected function configure()
		{
			require(self::root() . "/configuration.php");
			
			Config::setDefault("debug", false);
			
			Config::setDefault("database.host", "localhost");
			
			Config::setDefault("session.expires", 0);
			Config::setDefault("session.cookie_name", Config::get("app.internal_name") . "_session");
			Config::setDefault("session.cookie_expires", Config::get("session.expires"));
			Config::setDefault("session.cookie_domain", Config::get("app.domain"));
			Config::setDefault("session.cookie_secure", false, false);
			
			Config::setDefault("mail.default_from", "system@" . Config::get("app.domain"));
			Config::setDefault("mail.send_exceptions", !Config::get("debug"));
			
			Config::setDefault("assets.url_prefix", str_replace("/index.php", "", Helpers::getBaseUri()) . "/assets");
			
			Config::setDefault("uploads.enabled", true);
			Config::setDefault("uploads.max_file_size", 50 * 1024 * 1024);
			
			ini_set("display_errors", Config::get("debug") ? 1 : 0);
			ini_set("error_reporting", E_ALL | E_STRICT);
			
			header("Content-Type: text/html; charset=UTF-8");
			mb_internal_encoding("utf-8");
			
			date_default_timezone_set(Config::get("default_timezone"));
		}
	}