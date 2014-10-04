<?php

	class EmailValidator extends Validator
	{
		protected function perform($field)
		{
			if(!filter_var($field->getValue(), FILTER_VALIDATE_EMAIL))
			{
				$field->addError($this->getMessage("invalid"));
			}
		}
		
		protected function initializeMessages()
		{
			return array(
				"invalid" => "The specified email address is invalid."
			);
		}
	}

?>