<?php

	class SelectField extends Field
	{
		protected $items = array();
		
		public function __construct($name, $items = array(), $value = null, $params = array())
		{
			parent::__construct($name, $value, $params);
			
			$this->setItems($items);
			
			$this->addValidator(new InclusionValidator($this->getItems()));
		}
		
		public function getItems()
		{
			return $this->items;
		}
		
		public function setItems($items)
		{
			Helpers::whenNot(is_array($items), "The item list must be an array.");
			
			$this->items = Helpers::isAssoc($items) ? $items : array_combine($items, array_map("strval", $items));
			
			return $this;
		}
	}