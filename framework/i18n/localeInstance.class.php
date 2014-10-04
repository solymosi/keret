<?php

	class LocaleInstance
	{
		protected $locale;
		
		public function __construct($locale)
		{
			if(!is_string($locale))
			{
				throw new Exception("Locale must be a string.");
			}
			
			$this->locale = $locale;
		}
		
		public function __call($name, $arguments)
		{
			return call_user_func_array(array("Locale", $name), array_merge(array($this->locale), $arguments));
		}
	}