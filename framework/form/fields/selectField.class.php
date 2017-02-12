<?php

  class SelectField extends ListField
  {
    protected $groups = array();

    public function __construct($name, $items = array(), $value = null, $params = array())
    {
      parent::__construct($name, $items, $value, $params);
    }

    public function getGroups()
    {
      return $this->groups;
    }

    public function setItems($items)
    {
      parent::setItems(
        Helpers::flatten($items)
      );

      $this->groups = $items;

      return $this;
    }
  }
