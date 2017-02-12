<?php

  class FormRenderer extends Renderer
  {
    use RenderableFields;

    public function render()
    {
      return $this->tag(
        "form",
        $this->renderErrors($this) .
        $this->renderFields(),
        $this->fieldParams(array(
          "action" => $this->getParam("action"),
          "method" => $this->getParam("method"),
        ))
      );
    }
  }
