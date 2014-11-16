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
					I18n::initialize();
					
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
				catch(CsrfException $e)
				{
					Helpers::clearOutput();
					Helpers::setStatusCode("403 Forbidden");
					View::render("invalidToken");
				}
			}
			catch(Exception $e)
			{
				Helpers::clearOutput();
				Helpers::setStatusCode("500 Internal Server Error");
				
				if(Config::get("exceptions.send_mail"))
				{
					ErrorHandler::mailException($e);
				}
				
				$customTemplate = self::root() . "/templates/internalError.php";
				require_once(is_file($customTemplate) ? $customTemplate : dirname(__FILE__) . "/templates/internalError.php");
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
			
			Config::setDefault("app.long_title", Config::get("app.title"));
			
			Config::setDefault("database.host", "localhost");
			Config::setDefault("database.name", Config::get("app.internal_name"));
			
			Config::setDefault("session.expires", 0);
			Config::setDefault("session.cookie_name", Config::get("app.internal_name") . "_session");
			Config::setDefault("session.cookie_expires", Config::get("session.expires"));
			Config::setDefault("session.cookie_domain", Config::get("app.domain"));
			Config::setDefault("session.cookie_secure", false);
			
			Config::setDefault("mail.default_from", Config::get("app.title") . " <system@" . Config::get("app.domain") . ">");
			Config::setDefault("mail.smtp_server", "localhost");
			Config::setDefault("mail.smtp_port", 25);
			
			Config::setDefault("exceptions.send_mail", !Config::get("debug"));
			Config::setDefault("exceptions.mail_to", Config::get("mail.admin_email"));
			
			Config::setDefault("view.default_layout", "layout");
			Config::setDefault("view.template_class", "Template");
			Config::setDefault("view.form_renderer_provider_class", "FormRendererProvider");
			
			Config::setDefault("assets.url_prefix", str_replace("/index.php", "", Helpers::getBaseUri()) . "/assets");
			
			Config::setDefault("uploads.enabled", true);
			Config::setDefault("uploads.max_file_size", 50 * 1024 * 1024);
			
			Config::setDefault("i18n.locales", array("en_US"));
			Config::setDefault("i18n.locale_class", "LocaleInstance");
			Config::setDefault("i18n.default_locale", Config::get("i18n.locales")[0]);
			Config::setDefault("i18n.translation_provider_class", "TranslationProvider");
			
			ini_set("display_errors", Config::get("debug") ? 1 : 0);
			ini_set("error_reporting", E_ALL | E_STRICT);
			
			ini_set("SMTP", Config::get("mail.smtp_server"));
			ini_set("smtp_port", Config::get("mail.smtp_port"));
			
			ini_set("file_uploads", Config::get("uploads.enabled") ? 1 : 0);
			
			header("Content-Type: text/html; charset=UTF-8");
			mb_internal_encoding("utf-8");
			
			date_default_timezone_set(Config::get("default_timezone"));
		}
	}