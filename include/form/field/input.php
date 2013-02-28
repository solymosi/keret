<?php
	
	class InputField extends Field
	{
		public function __construct($type, $name, $value = null, $params = array())
		{
			$this->single = true;
			
			parent::__construct("input", $name, $value, $params);
			
			$this->setParam("type", $type);
		}
		
		public function render()
		{
			$this->setParam("value", $this->value);
			
			return parent::render();
		}
	}
	
?>