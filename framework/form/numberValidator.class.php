<?php

	class NumberValidator extends Validator
	{
		protected $decimals = null;
		protected $minimum = null;
		protected $maximum = null;
		protected $allowBlank = null;
		
		public function __construct($minimum = null, $maximum = null, $decimals = false, $allowBlank = false, $messages = array())
		{
			Node::whenNot(is_null($decimals) || $decimals === false || is_int($decimals), "The maximum number of decimals must be az integer or false or null for integers only.");
			Node::when($decimals && $decimals < 0, "The maximum number of decimals must be at least zero.");
			Node::whenNot(is_null($minimum) || is_int($minimum) || is_float($minimum), "The minimum value must be an integer, a float or null.");
			Node::whenNot(is_null($maximum) || is_int($maximum) || is_float($maximum), "The maximum value must be an integer, a float or null.");
			Node::whenNot(is_bool($allowBlank), "The allow blank parameter must be either true or false.");
			
			$this->decimals = $decimals;
			$this->minimum = $minimum;
			$this->maximum = $maximum;
			$this->allowBlank = $allowBlank;
			
			parent::__construct($messages);
		}
		
		protected function perform($field)
		{
			if($this->allowBlank && ($field->getValue() == "" || is_null($field->getValue())))
			{
				return;
			}
			
			if(!preg_match($this->decimals ? "/^[+-]?[0-9]+(\.[0-9]{1," . $this->decimals . "})?$/" : "/^[+-]?[0-9]+$/", strval($field->getValue())))
			{
				$field->addError($this->getMessage("invalid"));
			}
			else
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
		}
		
		protected function initializeMessages()
		{
			return array(
				"invalid" => "The specified number is invalid.",
				"minimum" => "The specified number must be at least #{minimum}",
				"maximum" => "The specified number must not be more than #{maximum}"
			);
		}
		
		protected function initializeParams()
		{
			return array(
				"minimum" => round($this->minimum, 10),
				"maximum" => round($this->maximum, 10),
				"decimals" => $this->decimals ? $this->decimals : "0",
			);
		}
	}

?>