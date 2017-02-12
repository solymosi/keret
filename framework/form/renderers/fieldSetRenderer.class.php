<?php

  class FieldSetRenderer extends Renderer
  {
    use RenderableFields;

    public function __construct($field, $parent = null, $params = array())
    {
      parent::__construct($field, $parent, $params);

      $this->setParam("row", false);
    }

    public function render()
    {
      return $this->getParam("tag") ?
        $this->tag(
          "fieldset",
          $this->renderLegend() .
          $this->renderErrors($this) .
          $this->renderFields(),
          $this->fieldParams()
        ) :
        $this->renderErrors($this) .
        $this->renderFields();
    }

    protected function renderLegend()
    {
      return $this->getParam("legend") ?
        $this->tag("legend", $this->getParam("legend")) :
        "";
    }
  }
