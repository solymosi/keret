<?php
	
	class Error extends Node
	{
		public function __construct($content, $params = array())
		{
			parent::__construct("div", self::mergeParams($params, array("class" => "+error")));
			$this->add("content", new Html($content));
		}
	}
	
?>