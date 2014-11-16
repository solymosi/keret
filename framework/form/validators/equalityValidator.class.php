<?php

	class EqualityValidator extends Validator
	{
		protected $target = null;
		
		public function __construct($target, $messages = array())
		{
			$this->target = $target;
			
			parent::__construct($messages);
		}
		
		protected function perform($field)
		{
			if($field->getValue() != $this->target)
			{
				$field->addError($this->getMessage("invalid"));
			}
		}
		
		protected function getMessageParams()
		{
			return array(
				"target" => $this->target
			);
		}
	}

?>