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
				foreach(array(lcfirst($class), strtolower($class), $class) as $current)
				{
					$file = $path . "/" . $current . ".class.php";
					
					if(is_file($file))
					{
						require $file;
						return;
					}
				}
			}
		}
	}