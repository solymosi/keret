<?php
	
	abstract class Field extends Node
	{
		protected $errors = array();
		
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