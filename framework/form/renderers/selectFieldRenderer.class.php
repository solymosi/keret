<?php

  class SelectFieldRenderer extends Renderer
  {
    public function __construct($field, $parent = null, $params = array())
    {
      parent::__construct($field, $parent, $params);
    }

    public function render()
    {
      $field = $this->getField();

      return $this->tag(
        "select",
        $this->renderOptions(),
        $this->fieldParams()
      );
    }

    protected function renderOptions($items = null)
    {
      $field = $this->getField();
      $items = is_null($items) ? $field->getGroups() : $items;

      return implode("", array_map(
        function($key) use($field, $items) {
          $label = $items[$key];
          return is_array($label) ?
            $this->tag(
              "optgroup",
              $this->renderOptions($label),
              array("label" => $key)
            ) :
            $this->tag(
              "option",
              Helpers::escapeHtml($label),
              array(
                "value" => $key,
                "selected" => $field->getValue() == $key ? "selected" : null,
              )
            );
        },
        array_keys($items)
      ));
    }
  }
