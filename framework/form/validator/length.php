<?php

	class LengthValidator extends Validator
	{
		protected $minimum = null;
		protected $maximum = null;
		
		public function __construct($minimum = null, $maximum = null, $messages = array())
		{
			Node::whenNot(is_null($minimum) || is_int($minimum), "The length minimum must be an integer or null.");
			Node::whenNot(is_null($maximum) || is_int($maximum), "The length maximum must be an integer or null.");
			
			$this->minimum = $minimum;
			$this->maximum = $maximum;
			
			parent::__construct($messages);
		}
		
		protected function perform($field)
		{
			$value = strval($field->getValue());
			
			if(!is_null($this->minimum) && mb_strlen($value) < $this->minimum)
			{
				$field->addError($this->getMessage("minimum"));
			}
			
			if(!is_null($this->maximum) && mb_strlen($value) > $this->maximum)
			{
				$field->addError($this->getMessage("maximum"));
			}
		}
		
		protected function initializeMessages()
		{
			return array(
				"minimum" => "The specified value must have at least #{minimum} characters.",
				"maximum" => "The specified value must be shorter than #{maximum} characters."
			);
		}
		
		protected function initializeParams()
		{
			return array(
				"minimum" => $this->minimum,
				"maximum" => $this->maximum
			);
		}
	}

?>