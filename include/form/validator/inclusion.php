<?php

	class InclusionValidator extends Validator
	{
		protected $list = array();
		
		public function __construct($list, $messages = array())
		{
			Node::whenNot(is_array($list), "The allowed values list for the inclusion validator must be an array.");
			
			$this->list = $list;
			
			parent::__construct($messages);
		}
		
		protected function perform($field)
		{
			if(!in_array($field->getValue(), count(array_filter(array_keys($this->list), "is_string")) > 0 ? array_keys($this->list) : $this->list))
			{
				$field->addError($this->getMessage("invalid"));
			}
		}
		
		protected function initializeMessages()
		{
			return array(
				"invalid" => "A megadott érték nem megfelelő."
			);
		}
		
		protected function initializeParams()
		{
			return array(
				"list" => implode(", ", $this->list),
			);
		}
	}

?>