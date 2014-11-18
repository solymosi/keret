<?php

	class CheckboxGroup extends FieldSet
	{
		public function __construct($name, $items = array(), $selected = array(), $params = array())
		{
			parent::__construct($name, $params);
			
			foreach($items as $id => $label)
			{
				$this->addChild(new Checkbox($id, $label));
			}
		}
	}