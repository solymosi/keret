<?php
	
	class Help extends Node
	{
		public function __construct($content, $params = array())
		{
			parent::__construct("div", self::mergeParams($params, array("class" => "+help")));
			$this->add("content", new Html($content));
		}
	}
	
?>