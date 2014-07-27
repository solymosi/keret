<?php
	
	class DateTimeField extends Field
	{
		public function __construct($name, $value = null, $params = array())
		{
			$this->group = true;
			
			$this->addChild("date", new DateField("date", null, $params))->
				getChild("date")->addValidator(new DateValidator(null, null));
				
			$this->addChild("time", new TimeField("time", null, $params));
			
			parent::__construct("div", $name, null, array("class" => "group"));
		}
		
		public function getValue()
		{
			return $this->getChild("date")->getValue() . " " . $this->getChild("time")->getValue();
		}
		
		public function setValue($value)
		{
			if(is_string($value))
			{
				$space = mb_strpos($value, " ");
				if($space !== false)
				{
					$value = array("date" => mb_substr($value, 0, $space), "time" => mb_substr($value, $space + 1));
				}
			}
			
			if(!is_array($value))
			{
				$this->getChild("date")->setValue(null);
				$this->getChild("time")->setValue(null);
			}
			
			$this->getChild("date")->setValue(@$value["date"]);
			$this->getChild("time")->setValue(@$value["time"]);
			
			return $this;
		}
		
		public function hasValue()
		{
			return !is_null($this->getValue());
		}
		
		public function clearValue()
		{
			$this->getChild("date")->clearValue();
			$this->getChild("time")->clearValue();
			
			return $this;
		}
		
		public function isValid()
		{
			$this->getChild("date")->isValid();
			$this->getChild("time")->isValid();
			
			return parent::isValid();
		}
		
		public function getErrors()
		{
			return array_merge(
				parent::getErrors(),
				$this->getChild("date")->getErrors(),
				$this->getChild("time")->getErrors()
			);
		}
		
		public function hasErrors()
		{
			return count($this->getErrors()) > 0;
		}
		
		public function clearErrors()
		{
			parent::clearErrors();
			
			$this->getChild("date")->clearErrors();
			$this->getChild("time")->clearErrors();
			
			return $this;
		}
	}
	
?>