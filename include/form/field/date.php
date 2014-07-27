<?php
	
	class DateField extends TextField
	{
		public function __construct($name, $value = null, $params = array())
		{
			$this->addParams(array("data-default" => "YYYY-MM-DD"));
			parent::__construct($name, $value, $params);
		}
	}
	
?>