<?php

	// Kiv�telt dobunk, ha egy PHP hiba l�p fel

	set_error_handler(function($errno, $errstr, $errfile, $errline)
	{
		if(!(error_reporting() & $errno))
		{
			return;
		}
		
		throw new Exception($errstr . " [" . $errfile . " @  line " . $errline . "]");
		return true;
	});

?>