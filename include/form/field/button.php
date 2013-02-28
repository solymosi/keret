<?php
	
	class Button extends InputField
	{
		public function __construct($label, $name = null, $params = array())
		{
			if(is_null($name))
			{
				$name = "button_" . substr(Helpers::randomToken(), 0, 8);
			}
			
			parent::__construct("button", $name, $label, self::mergeParams($params, array("class" => "+button")));
		}
	}
	
?>