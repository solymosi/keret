<?php

	class FieldEqualityValidator extends Validator
	{
		protected $first = null;
		protected $second = null;
		
		public function __construct($first, $second, $messages = array())
		{
			Node::whenNot(is_string($first), "The first field name must be a string.");
			Node::whenNot(is_string($second), "The second field name must be a string.");
			
			$this->first = $first;
			$this->second = $second;
			
			parent::__construct($messages);
		}
		
		protected function perform($field)
		{
			$first = $field->getChild($this->first);
			$second = $field->getChild($this->second);
			
			Node::whenNot($first instanceof Row || $first instanceof Field, "The first field name provided to the validator does not correspond to a row or field.");
			Node::whenNot($second instanceof Row || $second instanceof Field, "The second field name provided to the validator does not correspond to a row or field.");
			
			if($first->getValue() != $second->getValue())
			{
				$second->addError($this->getMessage("invalid"));
			}
		}
		
		protected function initializeMessages()
		{
			return array(
				"invalid" => "The values of the two fields do not match."
			);
		}
	}

?>