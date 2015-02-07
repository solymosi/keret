<?php

	class DateValidator extends Validator
	{
		protected $type = null;
		protected $earliest = null;
		protected $latest = null;
		
		const DATE = 1;
		const TIME = 2;
		const DATE_TIME = 3;
		
		protected static $patterns = array(
			self::DATE => "(?P<year>[0-9]{4})\-(?P<month>[0-9]{2})\-(?P<day>[0-9]{2})",
			self::TIME => "(?P<hour>[0-9]{2})\:(?P<minute>[0-9]{2})(?:\:(?P<second>[0-9]{2}))?",
			self::DATE_TIME => "(?P<year>[0-9]{4})\-(?P<month>[0-9]{2})\-(?P<day>[0-9]{2}) (?P<hour>[0-9]{2})\:(?P<minute>[0-9]{2})(?:\:(?P<second>[0-9]{2}))?",
		);
		
		protected static $formats = array(
			self::DATE => "Y-m-d",
			self::TIME => "H:i:s",
			self::DATE_TIME => "Y-m-d H:i:s",
		);
		
		public function __construct($type = self::DATE, $earliest = null, $latest = null, $messages = array())
		{
			Helpers::whenNot(
				in_array($type, array(self::DATE, self::TIME, self::DATE_TIME)),
				"The provided validation type is not supported."
			);
			$this->type = $type;
			
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
			
			if(!preg_match("/^" . self::$patterns[$this->type] . "$/", $value, $matches))
			{
				$field->addError($this->getMessage("invalid_format"));
				return;
			}
			
			$date = array_merge(
				array(
					"year"   => intval(date("Y")),
					"month"  => intval(date("m")),
					"day"    => intval(date("d")),
					"hour"   => 0,
					"minute" => 0,
					"second" => 0,
				),
				array_map("intval", $matches)
			);
			
			if(!@checkdate($date["month"], $date["day"], $date["year"]))
			{
				$field->addError($this->getMessage("invalid_date"));
				return;
			}
			
			if(!in_array($date["hour"], range(0, 23)) || !in_array($date["minute"], range(0, 59)) || !in_array($date["second"], range(0, 59)))
			{
				$field->addError($this->getMessage("invalid_time"));
				return;
			}
			
			$time = mktime(
				$date["hour"], $date["minute"], $date["second"],
				$date["month"], $date["day"], $date["year"]
			);
			
			if(!is_null($this->earliest) && $time < $this->earliest)
			{
				$field->addError($this->getMessage("too_early"));
			}
			
			if(!is_null($this->latest) && $time > $this->latest)
			{
				$field->addError($this->getMessage("too_late"));
			}
		}
		
		protected function getMessageParams()
		{
			return array(
				"earliest" => date(self::$formats[$this->type], $this->earliest),
				"latest" => date(self::$formats[$this->type], $this->latest)
			);
		}
	}