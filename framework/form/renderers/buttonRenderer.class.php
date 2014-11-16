<?php

	class ButtonRenderer extends Renderer
	{
		public function render()
		{
			return $this->tag(
				"button",
				$this->getField()->getLabel(),
				$this->fieldParams(array(
					"type" => $this->getType(),
				))
			);
		}
		
		protected function getType()
		{
			$field = $this->getField();
			
			if($field instanceof SubmitButton) { return "submit"; }
			if($field instanceof ResetButton)  { return "reset";  }
			if($field instanceof Button)       { return "button"; }
			
			throw new Exception("Cannot determine input type parameter for " . get_class($field));
		}
	}