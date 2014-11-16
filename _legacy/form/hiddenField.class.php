<?php
	
	class HiddenField extends InputField
	{
		public function __construct($name, $value = null, $params = array())
		{
			parent::__construct("hidden", $name, $value, $params);
		}
	}