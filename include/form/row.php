<?php

	class Row extends Node
	{
		public function __construct($label, $field, $help = null, $params = array())
		{
			$this->order = array("label", "field", "error", "help");
			
			if(is_string($label))
			{
				$label = new Label($label, $field);
			}
			
			if(is_string($help))
			{
				$help = new Help($help);
			}
			
			parent::__construct("div", array_merge(array("class" => "row"), $params));
			$this->add("label", $label);
			$this->add("field", $field);
			
			if(!is_null($help))
			{
				$this->add("help", $help);
			}
		}
		
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
		
		public function render()
		{
			if(!is_null($this->item("field")) && ($this->item("field") instanceof Field) && $this->item("field")->hasErrors())
			{
				$this->add("error", new Error(implode("<br />", $this->item("field")->getErrors())));
			}
			
			return parent::render();
		}
	}

?>