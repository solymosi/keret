<?php

	class RegexValidator extends Validator
	{
		protected $pattern = null;
		
		public function __construct($pattern, $messages = array())
		{
			Helpers::whenNot(is_string($pattern), "The regex pattern must be a string.");
			
			$this->pattern = $pattern;
			
			parent::__construct($messages);
		}
		
		protected function perform($field)
		{
			$value = strval($field->getValue());
			
			if(!preg_match($this->pattern, $value))
			{
				$field->addError($this->getMessage("invalid"));
			}
		}
		
		protected function initializeParams()
		{
			return array();
		}
	}

?>