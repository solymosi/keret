<?php

	abstract class Validator
	{
		protected $messages = array();
		
		public function __construct($messages = array())
		{
			$this->messages = $messages;
		}
		
		public function validate($field)
		{
			Helpers::whenNot($field instanceof Field, "The field parameter must be a Field instance.");
			
			$this->perform($field);
		}
		
		abstract protected function perform($field);
		
		protected function getMessage($id, $params = array())
		{
			Helpers::whenNot(is_array($params), "The parameter list must be an array.");
			
			$params = array_merge(
				$this->getMessageParams(),
				$params
			);
			
			if(isset($this->messages[$id]))
			{
				return Helpers::interpolate($this->messages[$id], $params);
			}
			else
			{
				$class = strtolower(
					preg_replace(
						"/([a-z])([A-Z])/", "$1_$2",
						str_replace("Validator", "", get_class($this))
					)
				);
				
				return I18n::translate("errors." . $class . "." . $id, $params);
			}
		}
		
		protected function getMessageParams()
		{
			return array();
		}
	}