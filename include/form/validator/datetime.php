<?php

	class DateTimeValidator extends DateValidator
	{
		protected function perform($field)
		{
			$value = strval($field->getValue());
			
			if(preg_match("/^[0-9]{4}\-[0-9]{2}\-[0-9]{2} ([0-1][0-9]|2[0-3]):[0-5][0-9]$/", $value))
			{
				$time = strtotime($value);
				
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
		
		protected function initializeMessages()
		{
			return array(
				"minimum" => "The specified date and time cannot be earlier than #{minimum}.",
				"maximum" => "The specified date and time cannot be later than #{maximum}.",
				"invalid" => "The specified date and time is invalid."
			);
		}
		
		protected function initializeParams()
		{
			return array(
				"minimum" => date("Y-m-d g:i A", $this->minimum),
				"maximum" => date("Y-m-d g:i A", $this->maximum)
			);
		}
	}

?>