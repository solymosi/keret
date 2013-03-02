<?php
	
	class CheckGroup extends Field
	{
		public function __construct($name, $items = array(), $selectedItems = array(), $params = array())
		{
			$this->group = true;
			
			self::whenNot(is_array($selectedItems), "The selected items parameter must be an array.");
			
			foreach($items as $id => $label)
			{
				$this->addChild($id, new Checkbox($id, $label, false, $params));
			}
			
			parent::__construct("div", $name, $selectedItems, array("class" => "group"));
		}
		
		public function setValue($items)
		{
			if(is_null($items))
			{
				$items = array();
			}
			
			self::whenNot(is_array($items), "The items parameter must be an array.");
			
			foreach($this->getChildren() as $id => $item)
			{
				$this->getChild($id)->setValue(in_array($id, $items));
			}
			
			return $this;
		}
		
		public function getValue()
		{
			$selected = array();
			
			foreach($this->getChildren() as $id => $item)
			{
				if($this->getChild($id)->getValue())
				{
					$selected[] = $id;
				}
			}
			
			return $selected;
		}
		
		public function hasValue()
		{
			return count($this->getValue()) > 0;
		}
		
		public function clearValue()
		{
			$this->setValue(array());
			return $this;
		}
	}
	
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