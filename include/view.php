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
		
		static public function setParams($params)
		{
			if(!is_array($params))
			{
				throw new Exception("The variable passed to setParams() must be an array");
			}
			self::$params = $params;
		}
		
		static public function getParams()
		{
			return self::$params;
		}
		
		static public function getContent($file, $params = array(), $layout = "layout")
		{
			$template = new Template($file, array_merge(self::$params, $params));
			$content = $template->getContent();
			
			if(is_null($layout))
			{
				return $content;
			}
			else
			{
				$params = $template->getParams();
				
				$layout = new Template($layout, array_merge($params, array("content" => $content)));
				return $layout->getContent();
			}
		}
		
		static public function render($file, $params = array(), $layout = "layout")
		{
			print self::getContent($file, $params, $layout);
		}
		
		static public function renderWithoutLayout($file, $params = array())
		{
			print self::getContent($file, $params, null);
		}
	}

?>