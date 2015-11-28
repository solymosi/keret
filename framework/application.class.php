<?php

	class Application
	{
		/* Holds the path of the 'app' folder of the application */
		static protected $root;
		
		/*
			Initializes the application and loads its configuration
			This function must be called before Application::run.
			The first parameter should specify the 'app' folder where the
			application files are located.
		*/
		static public function initialize($root)
		{
			/* Store the provided root path */
			self::$root = $root;
			
			/* Install the error handlers */
			ErrorHandler::install();
			
			/* Register important application folders for autoloading */
			Autoload::registerPath($root . "/controllers");
			Autoload::registerPath($root . "/models");
			Autoload::registerPath($root . "/lib");
			Autoload::registerPath($root . "/vendor");
			
			/*
				Include the 'autoload.php' of the application which may register
				additional custom folders for autoloading
			*/
			require self::root() . "/autoload.php";
			
			/* Load and set up application configuration */
			self::configure();
		}
		
		/*
			Runs the application to process the current request
			This performs additional preparations, dispatches the request to the
			router (which in turn calls the corresponding controller action) and
			handles any errors that occur during these operations.
		*/
		static public function run()
		{
			/*
				Start an output buffer, as we do not want stuff that is printed out
				to be sent straight to the browser (for example, if an error occurs,
				we need to be able to clear everything in the buffer and display an
				error message instead).
			*/
			ob_start();
			
			try
			{
				try
				{
					/* Connect to the database */
					DB::connect();
					
					/* Initialize the user's session, as well as the i18n module */
					Session::initialize();
					I18n::initialize();
					
					/*
						Execute the routes defined in the 'routes.php' of the application
						This is the line that actually "runs" the application. If a route
						in 'routes.php' is matched, the corresponding controller action will
						be executed by the routing engine. After the execution, a
						ProcessingFinished exception is thrown, which we catch below.
					*/
					require self::root() . "/routes.php";
					
					/*
						If this line is reached, we can be certain that none of the routes
						have been matched (since that would have thrown a ProcessingFinished
						exception and thus redirected the execution to the line below
						where we catch that exception). So we call Helpers::notFound, which
						in turn triggers a NotFoundException (that we also catch below).
					*/
					Helpers::notFound();
				}
				catch(ProcessingFinished $e)
				{
					/*
						A route has been matched and the corresponding controller action
						has been successfully executed. Nothing to do here.
					*/
				}
				catch(NotFoundException $e)
				{
					/*
						A 'not found' error has been thrown, most likely by calling
						Helpers::notFound. We display the corresponding error message.
					*/
					
					/* Clear the output buffer */
					Helpers::clearOutput();
					
					/* Set the proper status code and render the 'not found' template */
					Helpers::setStatusCode("404 Not Found");
					View::render("notFound");
				}
				catch(CsrfException $e)
				{
					/*
						A CSRF token validation has failed, most likely because the user
						submitted a form after their session had already expired. We display
						a 'session expired' error message (which is not always the cause of
						the failure, but the user does not care anyway).
					*/
					
					/* Clear the output buffer */
					Helpers::clearOutput();
					
					/* Set the proper status code and render the 'session expired' page */
					Helpers::setStatusCode("403 Forbidden");
					View::render("invalidToken");
				}
			}
			catch(Exception $e)
			{
				/*
					An unexpected error has occured, which is most likely caused by a
					bug or unhandled error in the application. We display an 'unexpected
					error' message and apologize to the user.
				*/
				
				/* Clear the output buffer */
				Helpers::clearOutput();
				
				/* Set the proper status code */
				Helpers::setStatusCode("500 Internal Server Error");
				
				/*
					If exception notifications are enabled, we send an email to the
					technical contact address specified in the configuration.
				*/
				if(Config::get("exceptions.send_mail"))
				{
					ErrorHandler::mailException($e);
				}
				
				/*
					Instead of rendering the template using View::render, we simply
					include the template of the error message here. The reason for this
					is that rendering is a complex procedure and we already know that
					something has gone wrong. If by any chance the error is in the
					templating module or the layout, we would only make matters worse.
					
					If the application has a custom error message template at
					'templates/internalError.php', we use that. Otherwise, we use the
					default one that ships with the framework.
				*/
				$customTemplate = self::root() . "/templates/internalError.php";
				require_once(
					is_file($customTemplate) ?
						$customTemplate :
						dirname(__FILE__) . "/templates/internalError.php"
				);
			}
			
			/* Finally, we send the contents of the output buffer to the browser */
			ob_end_flush();
		}
		
		/* Returns the path of the 'app' folder containing the application files */
		static public function root()
		{
			return self::$root;
		}
		
		/* Loads the application configuration and sets the default config values */
		static protected function configure()
		{
			/* Include the configuration file of the application */
			require(self::root() . "/configuration.php");
			
			/*
				In the following lines Config::setDefault is used instead of
				Config::set to set the default config values. The difference between
				them is that Config::setDefault only sets the specified variable if it
				has not yet been set by the application config file above.
			*/
			
			/* Do not run in debug mode by default */
			Config::setDefault("debug", false);
			
			/* The "full" app title is the same as the "regular" title by default */
			Config::setDefault("app.long_title", Config::get("app.title"));
			
			/* Default database settings */
			Config::setDefault("database.host", "localhost");
			Config::setDefault("database.name", Config::get("app.internal_name"));
			
			/* Default session settings */
			Config::setDefault("session.expires", 0);
			Config::setDefault("session.save_path", Application::root() . "/sessions");
			Config::setDefault("session.cookie_name", Config::get("app.internal_name") . "_session");
			Config::setDefault("session.cookie_expires", Config::get("session.expires"));
			Config::setDefault("session.cookie_domain", Config::get("app.domain"));
			Config::setDefault("session.cookie_secure", false);
			
			/* Default settings for emails sent using Helpers::sendMail */
			Config::setDefault("mail.default_from", Config::get("app.title") . " <system@" . Config::get("app.domain") . ">");
			Config::setDefault("mail.smtp_server", "localhost");
			Config::setDefault("mail.smtp_port", 25);
			Config::setDefault("mail.content_type", "text/plain; charset=UTF-8");
			
			/* Default options for exception notification emails */
			Config::setDefault("exceptions.send_mail", !Config::get("debug"));
			Config::setDefault("exceptions.mail_to", Config::get("mail.admin_email"));
			
			/*
				Default settings for the templating module
				- default_layout: default layout file to use for rendering templates
				- template_class: lets you use a custom template class with custom
						functions added to it (example: CustomTemplate)
				- form_renderer_provider_class: lets you use a custom class for deciding
						which renderers to use for which form fields in the form framework
			*/
			Config::setDefault("view.default_layout", "layout");
			Config::setDefault("view.template_class", "Template");
			Config::setDefault("view.form_renderer_provider_class", "FormRendererProvider");
			
			/* Assets are accessible under the '/assets' path by default */
			Config::setDefault("assets.url_prefix", str_replace("/index.php", "", Helpers::getBaseUri()) . "/assets");
			
			/* Default settings for uploads */
			Config::setDefault("uploads.enabled", true);
			Config::setDefault("uploads.max_file_size", 50 * 1024 * 1024);
			
			/*
				Default settings for the i18n module
				- locales: contains a list of the locales that are supported
				- locale_class: lets you specify a custom class for locales, in case
						you need to add custom functionality to the locale handling code
				- default_locale: the default locale is the first in the locales array
				- translation_provider_class: lets you use a custom class for retrieving
						translations, in case they are not stored in their default location
						(for example, they are stored in a database instead)
			*/
			Config::setDefault("i18n.locales", array("en_US"));
			Config::setDefault("i18n.locale_class", "LocaleInstance");
			Config::setDefault("i18n.default_locale", Config::get("i18n.locales")[0]);
			Config::setDefault("i18n.translation_provider_class", "TranslationProvider");
			
			/* Only display errors if in debug mode */
			ini_set("display_errors", Config::get("debug") ? 1 : 0);
			
			/* Catch all PHP errors, warnings, notices and other messages */
			ini_set("error_reporting", E_ALL | E_STRICT);
			
			/* Set SMTP settings for the 'mail' function */
			ini_set("SMTP", Config::get("mail.smtp_server"));
			ini_set("smtp_port", Config::get("mail.smtp_port"));
			
			/* Enable or disable file uploads based on the configuration */
			ini_set("file_uploads", Config::get("uploads.enabled") ? 1 : 0);
			
			/*
				Specify UTF-8 encoding in the Content-Type header and for
				multibyte string functions.
			*/
			header("Content-Type: text/html; charset=UTF-8");
			mb_internal_encoding("utf-8");
			
			/* Set the default timezone as configured */
			date_default_timezone_set(Config::get("default_timezone"));
		}
	}