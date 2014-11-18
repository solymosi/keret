<?php

	class Checkbox extends Field
	{
		protected $label = null;
		
		public function __construct($name, $label, $selected = false, $params = array())
		{
			parent::__construct($name, $selected, $params);
			
			$this->setLabel($label);
		}
		
		public function getLabel()
		{
			return $this->label;
		}
		
		public function setLabel($label)
		{
			Helpers::whenNot(is_string($label), "The checkbox label must be a string.");
			
			$this->label = $label;
			
			return $this;
		}
	}