<?php
	
	class RadioGroup extends Field
	{
		public function __construct($name, $items = array(), $value = null, $params = array())
		{
			$this->group = true;
			
			parent::__construct("div", null, $value, array("class" => "group"));
			
			foreach($items as $id => $label)
			{
				$this->addChild($id, new RadioButton($name, $id, $label, $params));
			}
			
			$this->setValue($value);
		}
	}
	
	class RadioButton extends InputField
	{
		protected $label = null;
		protected $suffix = null;
		
		public function __construct($name, $value, $label, $params = array())
		{
			parent::__construct("radio", $name, $value, self::mergeParams($params, array("class" => "inline")));
			
			$this->suffix = rand(100000, 999999);
			$this->setLabel($label);
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
		
		public function getID()
		{
			return parent::getID() . "_" . $this->suffix;
		}
		
		public function render()
		{
			$this->getParent()->getValue() == $this->getValue() ? $this->setParam("checked", "checked") : $this->clearParam("checked");
			return '<div class="field">' . parent::render() . $this->label->render() . '</div>';
		}
	}
	
?>