<?php

  class LengthValidator extends Validator
  {
    protected $minimum = null;
    protected $maximum = null;

    public function __construct($minimum = null, $maximum = null, $messages = array())
    {
      Helpers::whenNot(is_null($minimum) || is_int($minimum), "The length minimum must be an integer or null.");
      Helpers::whenNot(is_null($maximum) || is_int($maximum), "The length maximum must be an integer or null.");

      $this->minimum = $minimum;
      $this->maximum = $maximum;

      parent::__construct($messages);
    }

    protected function perform($field)
    {
      if($field->isBlank())
      {
        return;
      }

      $value = strval($field->getValue());
      $length = mb_strlen($value);

      if(!is_null($this->minimum) && $length < $this->minimum)
      {
        $field->addError($this->getMessage("too_short", array("length" => $length)));
      }

      if(!is_null($this->maximum) && $length > $this->maximum)
      {
        $field->addError($this->getMessage("too_long", array("length" => $length)));
      }
    }

    protected function getMessageParams()
    {
      return array(
        "minimum" => $this->minimum,
        "maximum" => $this->maximum
      );
    }
  }
