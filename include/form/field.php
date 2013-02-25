<?php
	
	abstract class Field extends Node
	{
		protected $value = null;
		protected $errors = array();
		
		public function __construct($tag, $value = null, $params = array(), $items = array())
		{
			parent::__construct($tag, $params, $items);
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
	}
	
?>