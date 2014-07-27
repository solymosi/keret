<?php
	
	class TimeField extends SelectField
	{
		public function __construct($name, $value = null, $params = array())
		{
			$options = array();
			foreach(range(0, 23) as $hour)
			{
				foreach(array(0, 15, 30, 45) as $minute)
				{
					$ts = strtotime("2000-01-01 " . $hour . ":" . $minute);
					$options[date("H:i:00", $ts)] = date("g:i A", $ts);
				}
			}
			
			$this->addParams(array("class" => "time"));
			parent::__construct($name, $options, null, $value, $params);
		}
	}
	
?>