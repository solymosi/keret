<?php

	abstract class View
	{
		static protected $params = array();
		
		static public function set($key, $value)
		{
			self::$params[$key] = $value;
		}
		
		static public function get($key)
		{
			return isset(self::$params[$key]) ? self::$params[$key] : null;
		}
		
		// Lecseréli a paramétereket a megadott tömbre
		static public function setParams($params)
		{
			if(!is_array($params))
			{
				throw new Exception("The variable passed to setParams() must be an array");
			}
			self::$params = $params;
		}
		
		// Visszaadja az összes paramétert
		static public function getParams()
		{
			return self::$params;
		}
		
		// Betölti a megadott template fájlt, lefuttatja a megadott paraméterekkel, kidekorálja a layout-tal, és visszaadja az eredményt
		static public function render($file, $params = array(), $layout = "layout")
		{
			// Kirendereljük a template-t a $content változóba
			$template = new Template($file, array_merge(self::$params, $params));
			$content = $template->getContent();
			
			// A render után lekérjük a template paramétereit (ugyanis lehet, hogy a getContent() alatt néhány új hozzá lett adva)
			$params = $template->getParams();
			
			// Kirendereljük a layout-ot a template paramétereivel és kirenderelt kódjával, majd kiírjuk az eredményt
			$layout = new Template($layout, array_merge($params, array("content" => $content)));
			print $layout->getContent();
		}
		
		static public function renderWithoutLayout($file, $params = array())
		{
			// Kirendereljük a megadott template-t, majd kiírjuk az eredményt
			$template = new Template($file, array_merge(self::$params, $params));
			print $template->getContent();
		}
	}

?>