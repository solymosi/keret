<?php

	class FieldSetRenderer extends Renderer
	{
		public function render()
		{
			return $this->getParam("tag") ?
				$this->tag(
					"fieldset",
					$this->renderLegend() .
					$this->renderFields(),
					$this->fieldParams()
				) :
				$this->renderFields();
		}
		
		public function renderLegend()
		{
			return $this->getParam("legend") ? 
				$this->tag("legend", $this->getParam("legend")) :
				"";
		}
		
		public function renderFields()
		{
			return implode("", array_map(
				function($field) {
					return $field->render($this);
				},
				$this->getField()->getOrderedChildren()
			));
		}
	}