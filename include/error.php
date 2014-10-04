<?php

	set_error_handler("errorHandler");
	
	function errorHandler($errno, $errstr, $errfile, $errline)
	{
		if(!(error_reporting() & $errno))
		{
			return;
		}
		
		throw new Exception($errstr . " [" . $errfile . " @ line " . $errline . "]");
		return true;
	}

?>