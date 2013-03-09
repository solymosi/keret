<?php
	
	class SelectField extends Field
	{
		protected $options = array();
		
		public function __construct($name, $options = array(), $value = null, $params = array())
		{
			self::whenNot(is_array($options), "The select options must be an array.");
			
			$this->options = Helpers::isAssoc($options) ? $options : array_combine($options, array_map("strval", $options));
			
			parent::__construct("select", $name, $value, $params);
			
			$this->addParams(array("class" => "text"));
			
			foreach($this->options as $value => $label)
			{
				$this->addChild("_" . $value, new SelectOption($value, $label, false));
			}
			
			$this->addValidator(new InclusionValidator($this->options));
		}
	}
	
	class SelectOption extends Node
	{
		protected $value = null;
		
		public function __construct($value, $label, $selected = false, $params = array())
		{
			self::whenNot(is_string($label), "The select option label must be a string.");
			self::whenNot(is_bool($selected), "The selected parameter must be either true of false.");
			
			$this->value = $value;
			
			parent::__construct("option", array("value" => $value));
			
			$this->addChild("content", new Html($label));
		}
		
		public function render()
		{
			if($this->getParent()->getValue() == $this->value)
			{
				$this->setParam("selected", "selected");
			}
			
			return parent::render();
		}
	}
	
?>