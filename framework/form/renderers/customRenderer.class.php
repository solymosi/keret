<?php

  class CustomRenderer extends Renderer
  {
    public function __construct($field, $parent = null, $params = array())
    {
      parent::__construct($field, $parent, $params);

      Helpers::whenNot($this->hasParam("renderer"), "Custom renderer function not specified.");
      Helpers::whenNot(is_callable($this->getParam("renderer")), "Custom renderer function must be a callable.");
    }

    public function render()
    {
      return call_user_func(
        $this->getParam("renderer"),
        $this, $this->getField(), $this->getParent(), $this->getParams()
      );
    }
  }
