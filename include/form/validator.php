<?php

	abstract class Validator
	{
		protected $messages = array();
		
		public function __construct($messages = array())
		{
			$this->messages = array_merge($this->initializeMessages(), $messages);
		}
		
		public function validate($field)
		{
			Node::whenNot($field instanceof Field, "The field parameter must be a Field instance.");
			
			$this->perform($field);
		}
		
		abstract protected function perform($field);
		
		protected function getMessage($id)
		{
			return preg_replace_callback('/\#\{([a-zA-Z0-9]+)\}/', function($matches) {
				$params = $this->initializeParams();
				return $params[$matches[1]];
			}, $this->messages[$id]);
		}
		
		protected function initializeMessages()
		{
			return array();
		}
		
		protected function initializeParams()
		{
			return array();
		}
	}

?>