<?php
	
	class Error extends Node
	{
		public function __construct($message, $params = array())
		{
			self::whenNot(is_string($message), "Error message must be a string.");
			
			parent::__construct("div", $params);
			
			$this->addParams(array("class" => "error"));
			$this->addChild("message", new Html($message));
		}
	}
	
?>