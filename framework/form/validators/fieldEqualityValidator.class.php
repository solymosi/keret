<?php

  class FieldEqualityValidator extends Validator
  {
    protected $first = null;
    protected $second = null;

    public function __construct($first, $second, $messages = array())
    {
      Helpers::whenNot(is_string($first), "The first field name must be a string.");
      Helpers::whenNot(is_string($second), "The second field name must be a string.");

      $this->first = $first;
      $this->second = $second;

      parent::__construct($messages);
    }

    protected function perform($field)
    {
      Helpers::whenNot($field instanceof FieldSet, "Field equality validators can only be attached to field sets.");

      $first = $field->getChild($this->first);
      $second = $field->getChild($this->second);

      Helpers::when(is_null($first), "The first field name provided to the validator does not correspond to an existing field.");
      Helpers::when(is_null($second), "The second field name provided to the validator does not correspond to an existing field.");
      Helpers::when($first instanceof FieldSet, "The first field name provided to the validator corresponds to a field set which cannot be validated for equality.");
      Helpers::when($second instanceof FieldSet, "The second field name provided to the validator corresponds to a field set which cannot be validated for equality.");

      if($first->getValue() != $second->getValue())
      {
        $second->addError($this->getMessage("invalid"));
      }
    }
  }
