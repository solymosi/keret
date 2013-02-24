<?php

	class Row extends Node
	{
		protected $help = null;
		
		public function __construct($label, $field, $params = array())
		{
			if(is_string($label))
			{
				$label = new Label($label, $field);
			}
			parent::__construct("div", array_merge(array("class" => "row"), $params));
			$this->add("label", $label);
			$this->add("field", $field);
		}
		
		public function getHelp()
		{
			return $this->help;
		}
		
		public function setHelp($help)
		{
			$this->help = $help;
			return $this;
		}
		
		public function hasHelp()
		{
			return !is_null($this->help);
		}
		
		public function render()
		{
			$result = parent::render();
			
			if(!is_null($this->item("field")) && ($this->item("field") instanceof Field) && $this->item("field")->hasError())
			{
				$result .= (new Error($this->item("field")->getError()))->render();
			}
			
			if($this->hasHelp())
			{
				$result .= $this->getHelp()->render();
			}
			
			return $result;
		}
	}

?>