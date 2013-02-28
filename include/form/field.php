<?php
	
	abstract class Field extends Node
	{
		protected $name = null;
		protected $value = null;
		protected $group = false;
		protected $errors = array();
		
		public function __construct($tag, $name = null, $value = null, $params = array(), $children = array())
		{
			self::whenNot(($this->group && is_null($name)) || is_string($name), "Name must be a string.");
			$this->name = $name;
			
			parent::__construct($tag, $params, $children);
			
			if(!$this->group)
			{
				$this->addParams(array("name" => $name, "class" => "field"));
			}
			
			$this->setValue($value);
		}
		
		public function getValue()
		{
			return $this->value;
		}
		
		public function setValue($value)
		{
			$this->value = $value;
			return $this;
		}
		
		public function hasValue()
		{
			return !is_null($this->value);
		}
		
		public function clearValue()
		{
			$this->value = null;
			return $this;
		}
		
		public function addError($message, $none = null)
		{
			self::whenNot(is_null($none), "The second parameter must not be used.");
			self::whenNot(is_string($message), "Error message must be a string.");
			
			$this->errors[] = $message;
			
			return $this;
		}
		
		public function getErrors()
		{
			return $this->errors;
		}
		
		public function hasErrors()
		{
			return count($this->errors) != 0;
		}
		
		public function clearErrors()
		{
			$this->errors = array();
			return $this;
		}
		
		public function getName()
		{
			$path = $this->getPath();
			return $path[0] . (count($path) > 1 ? ("[" . implode("][", array_slice($path, 1)) . "]") : "");
		}
		
		public function getID()
		{
			return implode("_", $this->getPath());
		}
		
		public function render()
		{
			if(!$this->group)
			{
				$this->setParam("name", $this->getName());
				$this->setParam("id", $this->getID());
			}
			
			$this->addParams(array("class" => ($this->hasErrors() ? "+" : "-") . "has-error"));
			
			return parent::render();
		}
	}
	
?>