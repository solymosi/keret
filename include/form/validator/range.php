<?php

	class RangeValidator extends Validator
	{
		protected $minimum = null;
		protected $maximum = null;
		
		public function __construct($minimum = null, $maximum = null, $messages = array())
		{
			Node::whenNot(is_null($minimum) || is_int($minimum) || is_float($minimum), "The minimum value must be an integer, a float or null.");
			Node::whenNot(is_null($maximum) || is_int($maximum) || is_float($maximum), "The maximum value must be an integer, a float or null.");
			
			$this->minimum = $minimum;
			$this->maximum = $maximum;
			
			parent::__construct($messages);
		}
		
		protected function perform($field)
		{
			$value = floatval($field->getValue());
			
			if(!is_null($this->minimum) && $value < $this->minimum)
			{
				$field->addError($this->getMessage("minimum"));
			}
			
			if(!is_null($this->maximum) && $value > $this->maximum)
			{
				$field->addError($this->getMessage("maximum"));
			}
		}
		
		protected function initializeMessages()
		{
			return array(
				"minimum" => "A megadott szám nem lehet kisebb ennél: #{minimum}",
				"maximum" => "A megadott szám nem lehet nagyobb ennél: #{maximum}"
			);
		}
		
		protected function initializeParams()
		{
			return array(
				"minimum" => round($this->minimum, 10),
				"maximum" => round($this->maximum, 10)
			);
		}
	}

?>