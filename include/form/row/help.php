<?php
	
	class Help extends Node
	{
		public function __construct($content, $params = array())
		{
			$this->addChild("content", new Html($content));
			$this->addParams(array("class" => "help"));
			parent::__construct("div", $params);
		}
	}
	
?>