<?php

	class Template
	{
		protected $file = null;
		protected $params = array();
		
		public function __construct($file, $params = array())
		{
			$this->file = $file;
			$this->setParams(array_merge($this->getParams(), $params));
		}
		
		public function __isset($key)
		{
			return isset($this->params[$key]);
		}
		
		public function get($key)
		{
			return isset($this->params[$key]) ? $this->params[$key] : null;
		}
		
		public function __get($key)
		{
			return $this->get($key);
		}
		
		public function set($key, $value)
		{
			$this->params[$key] = $value;
		}
		
		public function __set($key, $value)
		{
			$this->set($key, $value);
		}
		
		public function getParams()
		{
			return $this->params;
		}
		
		public function setParams($params)
		{
			if(!is_array($params))
			{
				throw new Exception("The variable passed to setParams() must be an array");
			}
			$this->params = $params;
		}
		
		public function addParams($params)
		{
			if(!is_array($params))
			{
				throw new Exception("The variable passed to addParams() must be an array");
			}
			$this->setParams(array_merge($this->params, $params));
		}
		
		public function getContent()
		{
			ob_start();
			include("templates/" . $this->file . ".php");
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
		
		public function insert($file, $params = array())
		{
			return View::getContent($file, array_merge($this->params, $params), null);
		}
	}

?>