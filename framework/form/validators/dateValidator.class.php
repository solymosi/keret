<?php

	class DateValidator extends Validator
	{
		protected $earliest = null;
		protected $latest = null;
		
		const DATE = 1;
		const TIME = 2;
		const DATE_TIME = 3;
		
		public function __construct($type = self::DATE_TIME, $earliest = null, $latest = null, $messages = array())
		{
			if(is_string($earliest))
			{
				$earliest = strtotime($earliest);
			}
			
			if(is_string($latest))
			{
				$latest = strtotime($latest);
			}
			
			Helpers::whenNot(is_null($earliest) || is_int($earliest), "The earliest date must be a timestamp, well-formatted string or null.");
			Helpers::whenNot(is_null($latest) || is_int($latest), "The latest date must be a timestamp, well-formatted string or null.");
			
			$this->earliest = $earliest;
			$this->latest = $latest;
			
			parent::__construct($messages);
		}
		
		protected function perform($field)
		{
			if($field->isBlank())
			{
				return;
			}
			
			$value = strval($field->getValue());
			
			if(!preg_match("/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/", $value))
			{
				$field->addError($this->getMessage("invalid_format"));
			}
			else
			{
				$year = mb_substr($value, 0, 4);
				$month = mb_substr($value, 5, 2);
				$day = mb_substr($value, 8, 2);
				
				if(!@checkdate($month, $day, $year))
				{
					$field->addError($this->getMessage("invalid_date"));
				}
				else
				{
					$time = mktime(0, 0, 0, $month, $day, $year);
					
					if(!is_null($this->earliest) && $time < $this->earliest)
					{
						$field->addError($this->getMessage("too_early"));
					}
					
					if(!is_null($this->latest) && $time > $this->latest)
					{
						$field->addError($this->getMessage("too_late"));
					}
				}
			}
		}
		
		protected function getMessageParams()
		{
			return array(
				"earliest" => date("Y-m-d", $this->earliest),
				"latest" => date("Y-m-d", $this->latest)
			);
		}
	}