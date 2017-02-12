<?php

  class NumberField extends Field
  {
    public function __construct($name, $value = null, $minimum = null, $maximum = null, $decimals = false, $params = array())
    {
      parent::__construct($name, $value, $params);

      $this->addParams(array(
        "minimum" => $minimum,
        "maximum" => $maximum,
        "decimals" => $decimals,
      ));

      $this->addValidator(
        new NumberValidator($minimum, $maximum, $decimals, array_filter(array(
          "invalid" => $this->getParam("invalid"),
          "too_small" => $this->getParam("too_small"),
          "too_large" => $this->getParam("too_large"),
        )))
      );
    }
  }
