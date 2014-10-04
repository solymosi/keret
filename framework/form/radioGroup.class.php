<?php
	
	class RadioGroup extends Field
	{
		public function __construct($name, $items = array(), $value = null, $params = array())
		{
			$this->group = true;
			$items = Helpers::isAssoc($items) ? $items : array_combine($items, array_map("strval", $items));
			
			parent::__construct("div", null, $value, array("class" => "group"));
			
			foreach($items as $id => $label)
			{
				$this->addChild("_" . $id, new RadioButton($name, $id, $label, $params));
			}
			
			$this->setValue($value);
			
			$this->addValidator(new InclusionValidator($items));
		}
	}
	
?>