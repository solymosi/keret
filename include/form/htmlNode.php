<?php

	class HtmlNode extends Node
	{
		public function __construct($html)
		{
			$this->html = $html;
		}
		
		public function render()
		{
			return $this->html;
		}
	}

?>