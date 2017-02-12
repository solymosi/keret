<?php

  class TextareaRenderer extends Renderer
  {
    public function render()
    {
      return $this->tag(
        "textarea",
        Helpers::escapeHtml($this->getField()->getValue()),
        $this->fieldParams(array(
          "rows" => $this->getField()->getParam("rows"),
        ))
      );
    }
  }
