<?php
	
	class Form extends Node
	{
		public function __construct($action = "", $method = "post", $params = array(), $items = array())
		{
			parent::__construct("form", array_merge($params, array("action" => $action, "method" => $method)), $items);
		}
	}
	
?>