<?php
	
	class InputField extends Field
	{
		public function __construct($type, $name, $value = null, $params = array())
		{
			$this->single = true;
			parent::__construct("input", $name, $value, array_merge(array("type" => $type), $params));
		}
		
		public function render()
		{
			$this->set("value", $this->value);
			return parent::render();
		}
	}
	
?>