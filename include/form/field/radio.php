<?php
	
	class RadioGroup extends Node
	{
		public function __construct($name, $items = array(), $value = null, $params = array())
		{
			parent::__construct("div", array("class" => "radiogroup"));
			
			foreach($items as $v => $label)
			{
				$this->add($v, new RadioButton($name, $v, $label, false, $params));
			}
			
			$this->setValue($value);
		}
		
		public function setValue($value)
		{
			foreach($this->getItems() as $id => $item)
			{
				$this->item($id)->select($id == $value);
			}
			return $this;
		}
		
		public function getValue()
		{
			foreach($this->getItems() as $id => $item)
			{
				if($this->item($id)->selected())
				{
					return $id;
				}
			}
			return null;
		}
		
		public function hasValue()
		{
			return !is_null($this->getValue());
		}
		
		public function clearValue()
		{
			$this->setValue(null);
			return $this;
		}
	}
	
	class RadioButton extends InputField
	{
		protected $label = null;
		protected $selected = false;
		
		public function __construct($name, $value, $label, $selected = false, $params = array())
		{
			$this->selected = $selected;
			$this->prefix = $name . "_" . $value;
			parent::__construct("radio", $name, $value, $params);
			
			if(is_string($label))
			{
				$this->label = new Label($label, $this);
			}
		}
		
		public function select($selected)
		{
			$this->selected = $selected;
			return $this;
		}
		
		public function selected()
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
			$this->selected() ? $this->set("checked", "checked") : $this->clear("checked");
			return '<div class="radio">' . parent::render() . $this->label->render() . '</div>';
		}
	}
	
?>