<?php

	class LengthValidator extends Validator
	{
		protected $minimum = null;
		protected $maximum = null;
		
		public function __construct($minimum = null, $maximum = null, $messages = array())
		{
			Node::whenNot(is_null($minimum) || is_int($minimum), "The length minimum must be an integer or null.");
			Node::whenNot(is_null($maximum) || is_int($maximum), "The length maximum must be an integer or null.");
			
			$this->minimum = $minimum;
			$this->maximum = $maximum;
			
			parent::__construct($messages);
		}
		
		protected function perform($field)
		{
			$value = $field->getValue();
			
			$field->addError($this->getMessage("minimum"));
			$field->addError($this->getMessage("maximum"));
		}
		
		protected function initializeMessages()
		{
			return array(
				"blank" => "Kötelező kitölteni ezt a mezőt.",
				"minimum" => "A megadott érték rövidebb #{minimum} karakternél.",
				"maximum" => "A megadott érték hosszabb #{maximum} karakternél."
			);
		}
		
		protected function initializeParams()
		{
			return array(
				"minimum" => $this->minimum,
				"maximum" => $this->maximum
			);
		}
	}

?>