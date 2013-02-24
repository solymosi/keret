<?php
	
	class Label extends Node
	{
		protected $caption = null;
		protected $field = null;
		
		public function __construct($caption, $field, $params = array())
		{
			$this->caption = $caption;
			$this->field = $field;
			parent::__construct("label", array_merge(array("for" => $field->id), $params));
			$this->add("caption", new HtmlNode($caption));
		}
	}
	
?>