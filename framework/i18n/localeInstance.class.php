<?php

	class LocaleInstance
	{
		protected $code;
		
		public function __construct($code)
		{
			if(!is_string($code))
			{
				throw new Exception("Locale code must be a string.");
			}
			
			$this->code = $code;
		}
		
		public function getCode()
		{
			return $this->code;
		}
		
		public function __call($name, $arguments)
		{
			return call_user_func_array(array("Locale", $name), array_merge(array($this->code), $arguments));
		}
	}