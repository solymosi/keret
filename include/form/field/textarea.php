<?php
	
	class Textarea extends Field
	{
		public function __construct($name, $value = null, $params = array())
		{
			parent::__construct("textarea", $name, $value, $params);
			$this->addParams(array("rows" => 5, "class" => "text"));
		}
		
		public function render()
		{
			$this->addChild("content", new Html($this->value));
			return parent::render();
		}
	}
	
?>