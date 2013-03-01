<?php

	abstract class Validator
	{
		protected $messages = array();
		
		public function __construct($messages = array())
		{
			$this->messages = array_merge($this->defaultMessages(), $messages);
		}
		
		public function validate($field)
		{
			Node::whenNot($field instanceof Field, "The field parameter must be a Field instance.");
			
			$this->perform($field);
		}
		
		abstract protected function perform($field);
		
		protected function defaultMessages()
		{
			return array();
		}
	}

?>