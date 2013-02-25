<?php
	
	class Help extends Node
	{
		public function __construct($content, $params = array())
		{
			parent::__construct("div", array_merge(array("class" => "help"), $params));
			$this->add("content", new HtmlNode($content));
		}
	}
	
?>