<?php
	
	class Checkbox extends InputField
	{
		protected $label = null;
		protected $selected = false;
		
		public function __construct($name, $label, $selected = false, $params = array())
		{
			parent::__construct("checkbox", $name, $selected, $params);
			
			if(is_string($label))
			{
				$this->label = new Label($label, $this);
			}
		}
		
		public function setValue($value)
		{
			$this->selected = $value;
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
			$this->label = $label;
			return $this;
		}
		
		public function render()
		{
			$this->getValue() ? $this->set("checked", "checked") : $this->clear("checked");
			$this->value = "1";
			return '<div class="checkbox">' . parent::render() . $this->label->render() . '</div>';
		}
	}
	
?>