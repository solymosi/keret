<?php
	
	class PasswordField extends InputField
	{
		public function __construct($name, $value = null, $params = array())
		{
			parent::__construct("password", $name, $value, $params);
			
			$this->addParams(array("class" => "text"));
		}
	}
	
?>