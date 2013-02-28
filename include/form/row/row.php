<?php

	class Row extends Node
	{
		public function __construct($label, $field, $help = null, $params = array())
		{
			$this->order = array("label", "field", "error", "help");
			
			if(is_string($label))
			{
				$label = new Label($label, $field instanceof Field ? $field : null);
			}
			
			if(is_string($help))
			{
				$help = new Help($help);
			}
			
			parent::__construct("div", self::mergeParams($params, array("class" => "+row")));
			
			if(!is_null($label))
			{
				$this->addChild("label", $label);
			}
			
			$this->addChild("field", $field);
			
			if(!is_null($help))
			{
				$this->addChild("help", $help);
			}
		}
		
		public function getValue()
		{
			return $this->getChild("field")->getValue();
		}
		
		public function setValue($value)
		{
			$this->getChild("field")->setValue($value);
			return $this;
		}
		
		public function hasValue()
		{
			return $this->getChild("field")->hasValue();
		}
		
		public function clearValue()
		{
			$this->getChild("field")->clearValue();
			return $this;
		}
		
		public function addError($error)
		{
			$this->getChild("field")->addError($error);
			return $this;
		}
		
		public function getErrors()
		{
			return $this->getChild("field")->getErrors();
		}
		
		public function hasErrors()
		{
			return $this->getChild("field")->hasErrors();
		}
		
		public function clearErrors()
		{
			$this->getChild("field")->clearErrors();
			return $this;
		}
		
		public function render()
		{
			if(!is_null($this->getChild("field")) && ($this->getChild("field") instanceof Field) && $this->getChild("field")->hasErrors())
			{
				$this->addChild("error", new Error(implode("<br />", $this->getChild("field")->getErrors())));
			}
			
			return parent::render();
		}
	}

?>