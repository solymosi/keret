<?php

	class FormRenderer extends FieldSetRenderer
	{
		public function render()
		{
			return $this->tag(
				"form",
				$this->renderFields(),
				$this->fieldParams(array(
					"action" => $this->getParam("action"),
					"method" => $this->getParam("method"),
				))
			);
		}
	}