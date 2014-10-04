<?php

	abstract class View
	{
		static protected $layout = DEFAULT_LAYOUT;
		static protected $params = array();
		
		static public function get($key)
		{
			return isset(self::$params[$key]) ? self::$params[$key] : null;
		}
		
		static public function set($key, $value)
		{
			self::$params[$key] = $value;
		}
		
		static public function getParams()
		{
			return self::$params;
		}
		
		static public function setParams($params)
		{
			if(!is_array($params))
			{
				throw new Exception("The variable passed to setParams() must be an array");
			}
			self::$params = $params;
		}
		
		static public function addParams($params)
		{
			if(!is_array($params))
			{
				throw new Exception("The variable passed to addParams() must be an array");
			}
			self::setParams(array_merge(self::$params, $params));
		}
		
		static public function getLayout()
		{
			return self::$layout;
		}
		
		static public function setLayout($layout)
		{
			self::$layout = $layout;
		}
		
		static public function getContent($file, $params = array(), $layout = null)
		{
			if(!is_null($layout))
			{
				$layout = self::$layout;
			}
			
			$template = new Template($file, array_merge(self::$params, $params));
			$content = $template->getContent();
			
			if($layout === false)
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
		
		static public function render($file, $params = array(), $layout = null)
		{
			print self::getContent($file, $params, $layout);
		}
	}
	
?>