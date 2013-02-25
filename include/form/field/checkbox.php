<?php
	
	class Checkbox extends InputField
	{
		protected $label = null;
		
		public function __construct($name, $label, $checked = false, $params = array())
		{
			parent::__construct("checkbox", $name, $checked, $params);
			
			if(is_string($label))
			{
				$this->label = new Label($label, $this);
			}
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
			$this->set("value", $this->value ? "1" : "0");
			return '<div class="checkbox">' . parent::render() . $this->label->render() . '</div>';
		}
	}
	
?>