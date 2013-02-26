<?php
	
	class Label extends Node
	{
		protected $field = null;
		
		public function __construct($content, $field, $params = array())
		{
			$this->field = $field;
			parent::__construct("label", self::mergeParams(array("for" => $field->id), $params));
			$this->add("content", new Html($content));
		}
	}
	
?>