<?php
	
	class Button extends Field
	{
		public function __construct($label, $name = null, $params = array())
		{
			if(is_null($name))
			{
				$name = "button_" . Helpers::randomToken();
			}
			
			parent::__construct($name, $label, $params);
			
			$this->addParams(array("class" => "button"));
		}
	}
	
?>