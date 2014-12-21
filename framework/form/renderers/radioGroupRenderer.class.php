<?php

	class RadioGroupRenderer extends GroupRenderer
	{
		public function __construct($field, $parent = null, $params = array())
		{
			parent::__construct($field, $parent, $params);
		}
		
		public function renderFields()
		{
			$field = $this->getField();
			
			return implode("", array_map(
				function($name) use ($field) {
					return $this->renderRow($name);
				},
				array_keys($field->getItems())
			));
		}
		
		public function renderRow($name)
		{
			return
				$this->renderField($name) .
				$this->renderLabel($name);
		}
		
		protected function renderField($name)
		{
			return $this->singleTag(
				"input",
				$this->fieldParams(array_merge(
					array(
						"type" => "radio",
						"value" => $name,
						"id" => $this->getId() . "_" . $name,
					),
					$this->getField()->getValue() == $name ?
						array("checked" => "checked") :
						array()
				))
			);
		}
		
		protected function renderLabel($name)
		{
			return $this->tag(
				"label",
				$this->getField()->getItems()[$name],
				array("for" => $this->getId() . "_" . $name)
			);
		}
	}