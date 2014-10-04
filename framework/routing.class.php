<?php

	class Routing
	{
		static protected $filters = array();
		static protected $params = array();
		
		static public function match($pattern, $controller, $action, $params = array())
		{
			self::addParams($params);
			
			if(preg_match("/^\/" . $pattern . "$/", Helpers::getUri(), $matches))
			{
				self::set("_controller", $controller);
				self::set("_action", $action);
				self::addParams($matches);
				
				foreach(self::$filters as $filter)
				{
					call_user_func($filter, self::getParams());
				}
				call_user_func(array(ucfirst(self::get("_controller")) . "Controller", self::get("_action")), self::getParams());
				
				throw new ProcessingFinished();
			}
		}
		
		static public function registerFilter($callback)
		{
			if(!is_callable($callback))
			{
				throw new Exception("Filter method must be callable.");
			}
			self::$filters[] = $callback;
		}
		
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
	}
	
	class ProcessingFinished extends Exception { }

?>