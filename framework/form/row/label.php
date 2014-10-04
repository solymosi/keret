<?php
	
	class Label extends Node
	{
		protected $field = null;
		
		public function __construct($content, $field = null, $params = array())
		{
			self::whenNot(is_string($content), "Label content must be a string.");
			
			parent::__construct("label", $params);
			
			if(!is_null($field))
			{
				$this->setField($field);
			}
			
			$this->addChild("content", new Html($content));
		}
		
		public function getField()
		{
			return $this->field;
		}
		
		public function setField($field)
		{
			self::whenNot($field instanceof Field, "The field parameter must be a Field instance.");
			
			$this->field = $field;
			
			return $this;
		}
		
		public function hasField()
		{
			return !is_null($this->field);
		}
		
		public function render()
		{
			if($this->hasField())
			{
				$this->setParam("for", $this->field->getID());
			}
			
			return parent::render();
		}
	}
	
?>