<?php

	class CheckboxRenderer extends Renderer
	{
		public function render()
		{
			return
				$this->renderHidden() .
				$this->renderField() .
				$this->renderLabel();
		}
		
		protected function renderHidden()
		{
			return $this->singleTag(
				"input",
				array(
					"name" => $this->getName(),
					"type" => "hidden",
					"value" => 0,
				)
			);
		}
		
		protected function renderField()
		{
			return $this->singleTag(
				"input",
				$this->fieldParams(array_merge(
					array(
						"type" => "checkbox",
						"value" => 1,
					),
					$this->getField()->getValue() ?
						array("checked" => "checked") :
						array()
				))
			);
		}
		
		protected function renderLabel()
		{
			return $this->tag(
				"label",
				$this->getField()->getLabel(),
				array("for" => $this->getId())
			);
		}
	}