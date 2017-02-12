<?php

  class CustomValidator extends Validator
  {
    protected $callback = null;

    public function __construct($callback)
    {
      Helpers::whenNot(is_callable($callback), "The parameter passed to custom validator is not a valid callback.");

      $this->callback = $callback;

      parent::__construct();
    }

    protected function perform($field)
    {
      call_user_func($this->callback, $field);
    }
  }
