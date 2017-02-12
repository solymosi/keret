<?php

  class NumberValidator extends Validator
  {
    protected $decimals = null;
    protected $minimum = null;
    protected $maximum = null;

    public function __construct($minimum = null, $maximum = null, $decimals = false, $messages = array())
    {
      Helpers::whenNot(is_null($decimals) || $decimals === false || is_int($decimals), "The maximum number of decimals must be az integer or false or null for integers only.");
      Helpers::when($decimals && $decimals < 0, "The maximum number of decimals must be at least zero.");
      Helpers::whenNot(is_null($minimum) || is_int($minimum) || is_float($minimum), "The minimum value must be an integer, a float or null.");
      Helpers::whenNot(is_null($maximum) || is_int($maximum) || is_float($maximum), "The maximum value must be an integer, a float or null.");

      $this->decimals = $decimals;
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

      if(!preg_match("/^[+-]?[0-9]+(\.[0-9]+)?$/", strval($field->getValue())))
      {
        $field->addError($this->getMessage("invalid"));
      }
      else
      {
        $value = floatval($field->getValue());

        if($value != round($value, $this->decimals))
        {
          $field->addError($this->getMessage("invalid"));
        }

        if(!is_null($this->minimum) && $value < $this->minimum)
        {
          $field->addError($this->getMessage("too_small"));
        }

        if(!is_null($this->maximum) && $value > $this->maximum)
        {
          $field->addError($this->getMessage("too_large"));
        }
      }
    }

    protected function getMessageParams()
    {
      return array(
        "minimum" => round($this->minimum, 10),
        "maximum" => round($this->maximum, 10),
        "decimals" => $this->decimals ? $this->decimals : "0",
      );
    }
  }
