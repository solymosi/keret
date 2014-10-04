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