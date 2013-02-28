<?php

	class Html extends Node
	{
		public function __construct($html)
		{
			self::whenNot(is_string($html), "HTML content must be a string.");
			
			$this->html = $html;
		}
		
		public function render()
		{
			return $this->html;
		}
	}

?>