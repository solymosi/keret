<?php
	
	class RadioGroup extends Node
	{
		public function __construct($name, $items = array(), $value = null, $params = array())
		{
			parent::__construct("div", array("class" => "group"));
			
			foreach($items as $v => $label)
			{
				$this->addChild($v, new RadioButton($name, $v, $label, false, $params));
			}
			
			$this->setValue($value);
		}
		
		public function setValue($value)
		{
			foreach($this->getChildren() as $id => $item)
			{
				$this->getChild($id)->select($id == $value);
			}
			return $this;
		}
		
		public function getValue()
		{
			foreach($this->getChildren() as $id => $item)
			{
				if($this->getChild($id)->selected())
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
			$this->select($selected);
			
			parent::__construct("radio", $name, $value, self::mergeParams($params, array("class" => "+inline")));
			
			if(is_string($label))
			{
				$this->label = new Label($label, $this, array("class" => "+inline"));
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
			$this->selected() ? $this->setParam("checked", "checked") : $this->clearParam("checked");
			return '<div class="field">' . parent::render() . $this->label->render() . '</div>';
		}
	}
	
?>