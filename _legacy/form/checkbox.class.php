<?php
	
	class Checkbox extends InputField
	{
		protected $label = null;
		protected $selected = false;
		
		public function __construct($name, $label, $selected = false, $params = array())
		{
			self::whenNot(is_bool($selected), "The selected parameter must be either true or false.");
			
			parent::__construct("checkbox", $name, $selected, $params);
			
			$this->addParams(array("class" => "inline"));
			$this->setLabel($label);
		}
		
		public function setValue($value)
		{
			$this->selected = !!$value;
			
			return $this;
		}
		
		public function getValue()
		{
			return $this->selected;
		}
		
		public function getLabel()
		{
			return $this->label;
		}
		
		public function setLabel($label)
		{
			if(is_string($label))
			{
				$label = new Label($label, $this, array("class" => "inline"));
			}
			
			self::whenNot($label instanceof Label, "The label must be a string.");
			
			$this->label = $label;
			
			return $this;
		}
		
		public function render()
		{
			$this->value = "1";
			$this->getValue() ? $this->setParam("checked", "checked") : $this->clearParam("checked");
			return '<div class="field">' . parent::render() . $this->label->render() . '</div>';
		}
	}
	
?>