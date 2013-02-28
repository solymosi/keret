<?php
	
	class Form extends Field
	{
		public function __construct($name = "form", $action = "", $method = "post", $params = array(), $children = array())
		{
			self::whenNot(is_string($name), "The form name must be a string.");
			self::whenNot(is_string($action), "The form action must be a string.");
			self::whenNot(is_string($method), "The form method must be a string.");
			
			parent::__construct("form", $name, null, $params, $children);
			$this->addParams(array("action" => $action, "method" => $method, "class" => "default"));
		}
		
		public function setValues($values)
		{
			self::whenNot(is_array($values), "The value list must be an array.");
			
			foreach($values as $name => $value)
			{
				if($this->hasChild($name) && ($this->getChild($name) instanceof Row || $this->getChild($name) instanceof Field))
				{
					$this->getChild($name)->setValue($value);
				}
			}
			
			return $this;
		}
		
		public function getValues()
		{
			$values = array();
			
			foreach($this->getChildren() as $name => $child)
			{
				if($child instanceof Row || $child instanceof Field)
				{
					$values[$name] = $child->getValue();
				}
			}
			
			return $values;
		}
		
		public function getValue() { }
		
		public function setValue($value) { }
		
		public function addError($field, $message = null)
		{
			self::when(is_null($message), "The error message is required.");
			self::whenNot($this->getChild($field) instanceof Row || $this->getChild($field) instanceof Field, "The specified field does not exist or is not a row or a field.");
			
			$this->getChild($field)->addError($message);
			
			return $this;
		}
		
		public function getErrors()
		{
			$errors = array();
			
			foreach($this->getChildren() as $name => $child)
			{
				if($child instanceof Row || $child instanceof Field)
				{
					$errors[$name] = $child->getErrors();
				}
			}
			
			return $errors;
		}
	}
	
?>