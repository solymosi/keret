<?php

	class ErrorHandler
	{
		static public function install()
		{
			set_error_handler("ErrorHandler::handle");
		}
		
		static public function handle($errno, $errstr, $errfile, $errline)
		{
			if(!(error_reporting() & $errno))
			{
				return;
			}
			
			throw new Exception($errstr . " [" . $errfile . " @ line " . $errline . "]");
			return true;
		}
		
		static public function mailException($e)
		{
			Helpers::sendMail(
				Config::get("exceptions.mail_to"),
				"[" . Config::get("app.internal_name") . "] " . $e->getMessage(),
				"An unhandled " . get_class($e) . " occured on " . date("l, j F Y H:i:s A") . ":\r\n\r\n" .
					$e->getMessage() . "\r\n" .
					"Request URI: " . $_SERVER["REQUEST_URI"] . "\r\n\r\n" .
					print_r(@$_REQUEST, true)
			);
		}
	}