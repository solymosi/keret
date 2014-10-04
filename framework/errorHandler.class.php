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
	}