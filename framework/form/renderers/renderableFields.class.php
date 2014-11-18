<?php

	trait RenderableFields
	{
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
			if($renderer instanceof GroupRenderer)
			{
				return $this->renderLabel($renderer) .
					$this->renderHelp($renderer) .
					$this->renderErrors($renderer) .
					$renderer->render();
			}
			else
			{
				return $this->renderLabel($renderer) .
					$renderer->render() .
					$this->renderErrors($renderer) .
					$this->renderHelp($renderer);
			}
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
		
		protected function renderHelp($renderer)
		{
			return $renderer->getParam("help") ?
				$this->tag(
					"div",
					$renderer->getParam("help"),
					array("class" => "help")
				) :
				"";
		}
	}