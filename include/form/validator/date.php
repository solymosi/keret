<?php

	class DateValidator extends Validator
	{
		protected $minimum = null;
		protected $maximum = null;
		
		public function __construct($minimum = null, $maximum = null, $messages = array())
		{
			if(is_string($minimum))
			{
				$minimum = strtotime($minimum);
			}
			
			if(is_string($maximum))
			{
				$maximum = strtotime($maximum);
			}
			
			Node::whenNot(is_null($minimum) || is_int($minimum), "The minimum date must be a timestamp, well-formatted string or null.");
			Node::whenNot(is_null($maximum) || is_int($maximum), "The maximum date must be a timestamp, well-formatted string or null.");
			
			$this->minimum = $minimum;
			$this->maximum = $maximum;
			
			parent::__construct($messages);
		}
		
		protected function perform($field)
		{
			$value = strval($field->getValue());
			
			if(!preg_match("/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/", $value))
			{
				$field->addError($this->getMessage("format"));
			}
			else
			{
				$year = mb_substr($value, 0, 4);
				$month = mb_substr($value, 5, 2);
				$day = mb_substr($value, 8, 2);
				
				if(!@checkdate($month, $day, $year))
				{
					$field->addError($this->getMessage("invalid"));
				}
				else
				{
					$time = mktime(0, 0, 0, $month, $day, $year);
					
					if(!is_null($this->minimum) && $time < $this->minimum)
					{
						$field->addError($this->getMessage("minimum"));
					}
					
					if(!is_null($this->maximum) && $time > $this->maximum)
					{
						$field->addError($this->getMessage("maximum"));
					}
				}
			}
		}
		
		protected function initializeMessages()
		{
			return array(
				"minimum" => "A megadott dátum nem lehet #{minimum} előtti.",
				"maximum" => "A megadott dátum nem lehet #{maximum} utáni.",
				"format" => "A megadott dátum nem felel meg az ÉÉÉÉ-HH-NN formátumnak.",
				"invalid" => "A megadott dátum nem megfelelő."
			);
		}
		
		protected function initializeParams()
		{
			return array(
				"minimum" => date("Y-m-d", $this->minimum),
				"maximum" => date("Y-m-d", $this->maximum)
			);
		}
	}

?>