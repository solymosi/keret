<?php

	class Autoload
	{
		static protected $paths = array();
		
		static public function initialize()
		{
			spl_autoload_register("self::load");
		}
		
		static public function registerPath($path)
		{
			self::$paths[] = $path;
		}
		
		static public function load($class)
		{
			foreach(self::$paths as $path)
			{
				$file = $path . "/" . lcfirst($class) . ".class.php";
				
				if(is_file($file))
				{
					require $file;
				}
			}
		}
	}