<?php

	class Routing
	{
		public static function match($pattern, $controller, $action)
		{
			if(preg_match("/^\/" . $pattern . "$/", Helpers::getUri(), $matches))
			{
				call_user_func(array(ucfirst($controller) . "Controller", $action), $matches);
				throw new ProcessingFinished();
			}
		}
	
	}
	
	class ProcessingFinished extends Exception { }

?>