<?php
	
	class InputField extends Field
	{
		public function __construct($type, $name, $value = null, $params = array())
		{
			$this->id = $name . "_" . substr(Helpers::randomToken(), 0, 8);
			$this->single = true;
			parent::__construct("input", array_merge(array("type" => $type, "name" => $name, "value" => $value), $params));
		}
	}
	
?>