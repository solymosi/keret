<?php
	
	abstract class Field extends Node
	{
		protected $value = null;
		protected $errors = array();
		protected $prefix = null;
		
		public function __construct($tag, $name, $value = null, $params = array(), $items = array())
		{
			$this->id = (is_null($this->prefix) ? $name : $this->prefix) . "_" . substr(Helpers::randomToken(), 0, 8);
			parent::__construct($tag, self::mergeParams(array("class" => "+field"), array_merge(array("name" => $name), $params)), $items);
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
		
		public function addError($error)
		{
			$this->errors[] = $error;
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
		
		public function render()
		{
			$this->setParams(self::mergeParams($this->getParams(), array("class" => ($this->hasErrors() ? "+" : "-") . "has-error")));
			return parent::render();
		}
	}
	
?>