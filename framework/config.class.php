<?php

	class Config
	{
		static protected $values = array();
		
		static public function has($key)
		{
			self::validateKey($key);
			$value = Fset::get(self::$values, $key);
			return !is_null($value);
		}
		
		static public function get($key)
		{
			if(!self::has($key))
			{
				throw new Exception("The config key '" . $key . "' does not exist");
			}
			
			return Fset::get(self::$values, $key);
		}
		
		static public function getAll()
		{
			return self::$values;
		}
		
		static public function set($key, $value)
		{
			self::validateKey($key);
			Fset::set(self::$values, $key, $value);
		}
		
		static public function setDefault($key, $value)
		{
			if(!self::has($key))
			{
				self::set($key, $value);
			}
		}
		
		static protected function validateKey($key)
		{
			if(!is_string($key) || !preg_match("/^([a-z0-9_]+\.)*[a-z0-9_]+$/", $key))
			{
				throw new Exception("Invalid configuration key");
			}
		}
	}