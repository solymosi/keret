<?php
	
	class SelectField extends Field
	{
		protected $options = array();
		
		public function __construct($name, $options = array(), $blank = null, $value = null, $params = array())
		{
			self::whenNot(is_array($options), "The select options must be an array.");
			
			$this->options = Helpers::isAssoc($options) ? $options : array_combine($options, array_map("strval", $options));
			
			if(!is_null($blank))
			{
				$this->options = array("" => $blank) + $this->options;
			}
			
			parent::__construct("select", $name, $value, $params);
			
			$this->addParams(array("class" => "text"));
			
			foreach($this->options as $value => $label)
			{
				$this->addChild("_" . $value, new SelectOption($value, $label));
			}
			
			$this->addValidator(new InclusionValidator($this->options));
		}
	}
	
	class SelectOption extends Node
	{
		protected $value = null;
		
		public function __construct($value, $label, $params = array())
		{
			self::whenNot(is_string($label), "The select option label must be a string.");
			
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