<?php

	class PresenceValidator extends Validator
	{
		protected function perform($field)
		{
			$value = $field->getValue();
			
			if(!$value || (is_string($value) && !trim($value)))
			{
				$field->addError($this->getMessage("blank"));
			}
		}
		
		protected function initializeMessages()
		{
			return array(
				"blank" => "Kötelező kitölteni ezt a mezőt."
			);
		}
	}

?>