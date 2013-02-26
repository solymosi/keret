<?php
	
	class SelectField extends Field
	{
		protected $options = array();
		
		public function __construct($name, $options = array(), $value = null, $params = array())
		{
			$this->options = $options;
			parent::__construct("select", $name, $value, $params);
		}
		
		public function render()
		{
			$this->removeAll();
			foreach($this->options as $value => $label)
			{
				$this->add($value, new SelectOption($value, $label, $this->getValue() == $value));
			}
			return parent::render();
		}
	}
	
	class SelectOption extends Node
	{
		public function __construct($value, $label, $selected = false, $params = array())
		{
			parent::__construct("option", array("value" => $value));
			
			$this->add("content", new Html($label));
			
			if($selected)
			{
				$this->set("selected", "selected");
			}
		}
	}
	
?>