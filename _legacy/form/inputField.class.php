<?php
	
	class InputField extends Field
	{
		public function __construct($type, $name, $value = null, $params = array())
		{
			parent::__construct($name, $value, $params);
			
			$this->setParam("type", $type);
		}
	}