<?php
	
	class InputField extends Field
	{
		public function __construct($type, $name, $value = null, $params = array())
		{
			$this->id = $name . "_" . substr(Helpers::randomToken(), 0, 8);
			$this->single = true;
			parent::__construct("input", $value, array_merge(array("type" => $type, "name" => $name), $params));
		}
		
		public function render()
		{
			$this->set("value", $this->value);
			return parent::render();
		}
	}
	
?>