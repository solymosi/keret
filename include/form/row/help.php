<?php
	
	class Help extends Node
	{
		public function __construct($message, $params = array())
		{
			self::whenNot(is_string($message), "Help message must be a string.");
			
			parent::__construct("div", $params);
			
			$this->addParams(array("class" => "help"));
			$this->addChild("message", new Html($message));
		}
	}
	
?>