<?php
	
	class Label extends Node
	{
		protected $field = null;
		
		public function __construct($content, $field = null, $params = array())
		{
			$this->field = $field;
			$this->addChild("content", new Html($content));
			parent::__construct("label", $params);
		}
		
		public function render()
		{
			if(!is_null($this->field))
			{
				$this->setParam("for", $this->field->getFullID());
			}
			
			return parent::render();
		}
	}
	
?>