<?php

  class InclusionValidator extends Validator
  {
    protected $list = array();

    public function __construct($list, $messages = array())
    {
      Helpers::whenNot(is_array($list), "The allowed values list for the inclusion validator must be an array.");

      $this->list = $list;

      parent::__construct($messages);
    }

    protected function perform($field)
    {
      if(!in_array($field->getValue(), Helpers::isAssoc($this->list) ? array_keys($this->list) : $this->list))
      {
        $field->addError($this->getMessage("invalid"));
      }
    }

    protected function getMessageParams()
    {
      return array(
        "list" => implode(", ", $this->list),
      );
    }
  }
