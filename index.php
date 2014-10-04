<?php
	
	require_once("include/all.php");
	
	ob_start();
	
	try
	{
		try
		{
			DB::connect();
			
			Session::initialize();
			
			//===============
			// ROUTING RULES
			//===============
			
			
			
			// Not found
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
			Helpers::sendMail(ADMIN_EMAIL, "[Exception] " . $e->getMessage(), "An unhandled " . get_class($e) . " occured on " . date("l, j F Y H:i:s A") . ":\r\n\r\n" . $e->getMessage() . "\r\nRequest URI: " . $_SERVER["REQUEST_URI"] . "\r\n\r\n" . print_r(@$_POST, true));
		}
		
		require_once("include/templates/errorMessage.php");
	}

	ob_end_flush();

?>