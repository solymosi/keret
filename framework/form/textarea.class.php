<?php
	
	class Textarea extends Field
	{
		public function __construct($name, $value = null, $params = array())
		{
			$this->addParams(array("rows" => 5, "class" => "text"));
			parent::__construct("textarea", $name, $value, $params);
		}
		
		public function render()
		{
			if(is_string($this->value))
			{
				$this->addChild("content", new Html($this->value));
			}
			
			return parent::render();
		}
	}
	
?>