<?php

	class Routing
	{
	
		public static function match($pattern, $controller)
		{
			if(preg_match("/^" . $pattern . "$/", Helpers::getUri()))
			{
				include_once("include/controllers/" . $controller . ".php");
				throw new ProcessingFinished();
			}
		}
	
	}
	
	class ProcessingFinished extends Exception { }

?>