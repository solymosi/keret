<?php
	
	class TextField extends InputField
	{
		public function __construct($name, $value = null, $params = array())
		{
			parent::__construct("text", $name, $value, $params);
		}
	}
	
?>