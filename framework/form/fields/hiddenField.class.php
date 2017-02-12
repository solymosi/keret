<?php

  class HiddenField extends Field
  {
    public function __construct($name, $value = null, $params = array())
    {
      parent::__construct($name, $value, $params);
    }
  }
