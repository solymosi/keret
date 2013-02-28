<?php
	
	class ResetButton extends Button
	{
		public function __construct($label, $name = null, $params = array())
		{
			parent::__construct($label, $name, $params);
			$this->setParam("type", "reset");
		}
	}
	
?>