<?php

  class Button extends Field
  {
    protected $label = null;

    public function __construct($label, $name = null, $params = array())
    {
      if(is_null($name))
      {
        $name = "button_" . Helpers::randomToken();
      }

      parent::__construct($name, null, $params);

      $this->setLabel($label);
    }

    public function getLabel()
    {
      return $this->label;
    }

    public function setLabel($label)
    {
      Helpers::whenNot(is_string($label), "The button label must be a string.");

      $this->label = $label;

      return $this;
    }
  }
