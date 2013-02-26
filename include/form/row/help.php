<?php
	
	class Help extends Node
	{
		public function __construct($content, $params = array())
		{
			parent::__construct("div", self::mergeParams(array("class" => "help"), $params));
			$this->add("content", new Html($content));
		}
	}
	
?>