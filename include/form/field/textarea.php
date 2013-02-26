<?php
	
	class Textarea extends Field
	{
		public function __construct($name, $value = null, $params = array())
		{
			parent::__construct("textarea", $name, $value, self::mergeParams(array("rows" => 5), $params));
		}
		
		public function render()
		{
			$this->add("content", new Html($this->value));
			return parent::render();
		}
	}
	
?>