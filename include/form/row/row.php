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
			
			parent::__construct("div", $params);
			
			$this->addParams(array("class" => "row"));
			
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
		
		public function isField()
		{
			return $this->getChild("field") instanceof Field;
		}
		
		public function getValue()
		{
			return $this->isField() ? $this->getChild("field")->getValue() : null;
		}
		
		public function setValue($value)
		{
			if($this->isField())
			{
				$this->getChild("field")->setValue($value);
			}
			
			return $this;
		}
		
		public function hasValue()
		{
			return $this->isField() ? $this->getChild("field")->hasValue() : false;
		}
		
		public function clearValue()
		{
			if($this->isField())
			{
				$this->getChild("field")->clearValue();
			}
			
			return $this;
		}
		
		public function addError($message)
		{
			if($this->isField())
			{
				$this->getChild("field")->addError($message);
			}
			
			return $this;
		}
		
		public function getErrors()
		{
			return $this->isField() ? $this->getChild("field")->getErrors() : array();
		}
		
		public function hasErrors()
		{
			return $this->isField() ? $this->getChild("field")->hasErrors() : false;
		}
		
		public function clearErrors()
		{
			if($this->isField())
			{
				$this->getChild("field")->clearErrors();
			}
			
			return $this;
		}
		
		public function addValidator($validator)
		{
			if($this->isField())
			{
				$this->getChild("field")->addValidator($validator);
			}
			
			return $this;
		}
		
		public function addValidators($validators)
		{
			if($this->isField())
			{
				$this->getChild("field")->addValidators($validators);
			}
			
			return $this;
		}
		
		public function hasValidators()
		{
			return $this->isField() ? $this->getChild("field")->hasValidators() : false;
		}
		
		public function getValidators()
		{
			return $this->isField() ? $this->getChild("field")->getValidators() : array();
		}
		
		public function setValidators($validators)
		{
			if($this->isField())
			{
				$this->getChild("field")->setValidators($validators);
			}
			
			return $this;
		}
		
		public function clearValidators()
		{
			if($this->isField())
			{
				$this->getChild("field")->clearValidators();
			}
			
			return $this;
		}
		
		public function isValid()
		{
			return $this->isField() ? $this->getChild("field")->isValid() : true;
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