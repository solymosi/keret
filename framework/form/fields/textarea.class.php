<?php

	class Textarea extends Field
	{
		public function __construct($name, $value = null, $params = array())
		{
			$params = array_merge(
				array("rows" => 5),
				$params
			);
			
			Helpers::whenNot(is_int($params["rows"]), "The row count must be an integer.");
			
			parent::__construct($name, $value, $params);
		}
	}