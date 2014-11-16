<?php

	class SelectOption extends Node
	{
		protected $value = null;
		
		public function __construct($value, $label, $selected = false, $params = array())
		{
			self::whenNot(is_string($label), "The select option label must be a string.");
			self::whenNot(is_bool($selected), "The selected parameter must be either true of false.");
			
			$this->value = $value;
			
			parent::__construct("option", array("value" => $value));
			
			$this->addChild("content", new Html($label));
		}
		
		public function render()
		{
			if($this->getParent()->getValue() == $this->value)
			{
				$this->setParam("selected", "selected");
			}
			
			return parent::render();
		}
	}