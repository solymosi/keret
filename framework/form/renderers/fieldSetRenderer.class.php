<?php

	class FieldSetRenderer extends Renderer
	{
		use RenderableFields;
		
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
	}