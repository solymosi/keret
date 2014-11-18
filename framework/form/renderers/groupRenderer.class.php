<?php

	class GroupRenderer extends Renderer
	{
		use RenderableFields;
		
		public function __construct($field, $parent = null, $params = array())
		{
			parent::__construct($field, $parent, $params);
			
			$this->setParam("row", true);
		}
		
		public function render()
		{
			return $this->renderFields();
		}
		
		protected function renderRow($renderer)
		{
			return $this->renderField($renderer);
		}
	}