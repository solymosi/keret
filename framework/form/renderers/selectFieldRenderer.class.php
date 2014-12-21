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
				implode("", array_map(
					function($key) use ($field) {
						return $this->tag(
							"option",
							Helpers::escapeHtml($field->getItems()[$key]),
							array(
								"value" => $key,
								"selected" => $field->getValue() == $key ? "selected" : null,
							)
						);
					},
					array_keys($field->getItems())
				)),
				$this->fieldParams()
			);
		}
	}