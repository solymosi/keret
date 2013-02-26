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
			
			parent::__construct("div", self::mergeParams($params, array("class" => "+row")));
			
			if(!is_null($label))
			{
				$this->add("label", $label);
			}
			
			$this->add("field", $field);
			
			if(!is_null($help))
			{
				$this->add("help", $help);
			}
		}
		
		public function getValue()
		{
			return $this->item("field")->getValue();
		}
		
		public function setValue($value)
		{
			$this->item("field")->setValue($value);
			return $this;
		}
		
		public function hasValue()
		{
			return $this->item("field")->hasValue();
		}
		
		public function clearValue()
		{
			$this->item("field")->clearValue();
			return $this;
		}
		
		public function addError($error)
		{
			$this->item("field")->addError($error);
			return $this;
		}
		
		public function getErrors()
		{
			return $this->item("field")->getErrors();
		}
		
		public function hasErrors()
		{
			return $this->item("field")->hasErrors();
		}
		
		public function clearErrors()
		{
			$this->item("field")->clearErrors();
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