<?php
	
	class Error extends Node
	{
		public function __construct($text, $params = array())
		{
			parent::__construct("div", array_merge(array("class" => "error"), $params));
			$this->add("text", new HtmlNode($text));
		}
	}
	
?>