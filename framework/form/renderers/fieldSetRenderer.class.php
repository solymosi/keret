<?php

	class FieldSetRenderer extends Renderer
	{
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
		
		protected function renderFields()
		{
			return implode("", array_map(
				function($field) {
					$renderer = $field->getRenderer($this);
					
					return $renderer->getParam("row") ?
						$this->renderRow($renderer) :
						$renderer->render();
				},
				$this->getField()->getOrderedChildren()
			));
		}
		
		protected function renderRow($renderer)
		{
			return $this->tag(
				"div",
				$this->renderField($renderer),
				array("class" => "row")
			);
		}
		
		protected function renderField($renderer)
		{
			return
				$this->renderLabel($renderer) .
				$renderer->render() .
				$this->renderErrors($renderer);
		}
		
		protected function renderLabel($renderer)
		{
			return $renderer->getParam("label") ?
				$this->tag(
					"label",
					$renderer->getParam("label"),
					array("for" => $renderer->getId())
				) :
				"";
		}
		
		protected function renderErrors($renderer)
		{
			$errors = $renderer->getField()->getErrors();
			return count($errors) > 0 ?
				$this->tag(
					"div",
					implode("",
						array_map(function($error) {
						return $this->tag("div", $error, array("class" => "error"));
						}, $errors)
					),
					array("class" => "errors")
				) :
				"";
		}
	}