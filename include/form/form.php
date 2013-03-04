<?php
	
	class Form extends Field
	{
		public function __construct($name = "form", $action = "", $method = "post", $params = array(), $children = array())
		{
			self::whenNot(is_string($name), "The form name must be a string.");
			self::whenNot(is_string($action), "The form action must be a string.");
			self::whenNot(is_string($method), "The form method must be a string.");
			
			parent::__construct("form", $name, null, $params, $children);
			$this->addParams(array("action" => $action, "method" => mb_strtolower($method), "class" => "-field default " . $name));
			
			if($this->getParam("method") == "post")
			{
				$this->addCSRFField();
			}
			
			$this->order = array("errors");
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
			if(is_null($message))
			{
				parent::addError($field);
			}
			else
			{
				self::whenNot($this->getChild($field) instanceof Row || $this->getChild($field) instanceof Field, "The specified field does not exist or is not a row or a field.");
				$this->getChild($field)->addError($message);
			}
			
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
		
		public function getFormErrors()
		{
			return parent::getErrors();
		}
		
		public function clearFormErrors()
		{
			$this->clearErrors();
			
			foreach($this->getChildren() as $name => $child)
			{
				if($child instanceof Row || $child instanceof Field)
				{
					$child->clearErrors();
				}
			}
			
			return $this;
		}
		
		public function isValid()
		{
			$this->clearFormErrors();
			
			$valid = parent::isValid();
			
			foreach($this->getChildren() as $name => $child)
			{
				if($child instanceof Row || $child instanceof Field)
				{
					$valid = $child->isValid() ? $valid : false;
				}
			}
			
			return $valid;
		}
		
		public function addCSRFField()
		{
			$this->addChild("csrf_field", new HiddenField("csrf_field", Session::CSRFToken()));
			$this->getChild("csrf_field")->addValidator(new CustomValidator(function($field) {
				Session::verifyCSRFToken($field->getValue());
			}));
		}
		
		public function removeCSRFField()
		{
			$this->removeChild("csrf_field");
		}
		
		public function render()
		{
			$errors = $this->getFormErrors();
			
			if(count($errors) > 0)
			{
				$this->addChild("errors", new Error(implode("<br />", $errors), array("class" => "errors")));
			}
			else
			{
				$this->removeChild("errors");
			}
			
			return parent::render();
		}
	}
	
?>