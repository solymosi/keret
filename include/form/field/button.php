<?php
	
	class Button extends InputField
	{
		public function __construct($label, $name = null, $params = array())
		{
			if(is_null($name))
			{
				$name = "button" . rand(100000, 999999);
			}
			
			parent::__construct("button", $name, $label, $params);
			
			$this->addParams(array("class" => "button"));
		}
	}
	
?>