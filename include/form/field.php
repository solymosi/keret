<?php
	
	abstract class Field extends Node
	{
		protected $error = null;
		
		public function setError($error)
		{
			$this->error = $error;
			return $this;
		}
		
		public function getError()
		{
			return $this->error;
		}
		
		public function hasError()
		{
			return !is_null($this->error);
		}
		
		public function clearError()
		{
			$this->error = null;
			return $this;
		}
	}
	
?>