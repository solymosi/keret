<?php
	
	class CheckGroup extends Node
	{
		protected $name = null;
		
		public function __construct($name, $items = array(), $selectedItems = array(), $params = array())
		{
			$this->name = $name;
			
			parent::__construct("div", array("class" => "group"));
			
			foreach($items as $n => $label)
			{
				$this->addChild($n, new Checkbox($n, $label, in_array($n, $selectedItems), $params));
			}
		}
		
		public function setValue($items)
		{
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
		
		public function render()
		{
			return parent::render();
		}
	}
	
	class Checkbox extends InputField
	{
		protected $label = null;
		protected $selected = false;
		
		public function __construct($name, $label, $selected = false, $params = array())
		{
			parent::__construct("checkbox", $name, $selected, self::mergeParams($params, array("class" => "+inline")));
			
			if(is_string($label))
			{
				$this->label = new Label($label, $this, array("class" => "+inline"));
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
			$this->getValue() ? $this->setParam("checked", "checked") : $this->clearParam("checked");
			$this->value = "1";
			return '<div class="field">' . parent::render() . $this->label->render() . '</div>';
		}
	}
	
?>