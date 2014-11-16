<?php

	class InputFieldRenderer extends Renderer
	{
		public function __construct($field, $parent = null, $params = array())
		{
			parent::__construct($field, $parent, $params);
			
			if($this->getType() == "hidden")
			{
				$this->setParam("row", false);
			}
		}
		
		public function render()
		{
			return $this->singleTag(
				"input",
				$this->fieldParams(array(
					"type" => $this->getType(),
					"value" => $this->getField()->getValue(),
				))
			);
		}
		
		protected function getType()
		{
			$field = $this->getField();
			
			if($field instanceof HiddenField)   { return "hidden";   }
			if($field instanceof PasswordField) { return "password"; }
			if($field instanceof TextField)     { return "text";     }
			
			throw new Exception("Cannot determine input type parameter for " . get_class($field));
		}
	}