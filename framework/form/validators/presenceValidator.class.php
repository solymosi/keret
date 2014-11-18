<?php

	class PresenceValidator extends Validator
	{
		protected function perform($field)
		{
			if($field->isBlank())
			{
				$field->addError($this->getMessage("blank"));
			}
		}
	}

?>