<?php

	abstract class Renderer
	{
		protected $field = null;
		protected $parent = null;
		protected $params = array();
		
		public function __construct($field, $parent = null, $params = array())
		{
			Helpers::whenNot($field instanceof Field, "The object provided to the renderer must be a Field.");
			$this->field = $field;
			
			if(!is_null($parent))
			{
				$this->setParent($parent);
			}
			
			$this->setParams(array_merge(
				$field->getParams(),
				$params
			));
		}
		
		/* Field */
		
		public function getField()
		{
			return $this->field;
		}
		
		/* Parent */
		
		public function getParent()
		{
			return $this->parent;
		}
		
		public function hasParent()
		{
			return $this->parent != null;
		}
		
		protected function setParent($parent)
		{
			Helpers::whenNot($parent instanceof Renderer, "A Renderer instance is required.");
			
			$this->parent = $parent;
		}
		
		/* Parameters */
		
		public function setParam($name, $value)
		{
			$this->params[$name] = $value;
		}
		
		public function getParam($name)
		{
			return $this->hasParam($name) ? $this->params[$name] : null;
		}
		
		public function hasParam($name)
		{
			return isset($this->params[$name]);
		}
		
		public function getParams()
		{
			return $this->params;
		}
		
		public function setParams($params)
		{
			Helpers::whenNot(is_array($params), "The parameter list must be an array.");
			
			$this->params = $params;
		}
		
		/* Render */
		
		abstract public function render();
		
		/* Naming */
		
		protected function getName()
		{
			return $this->hasParent() ?
				$this->getParent()->getName() . "[" . $this->getField()->getName() . "]" :
				$this->getField()->getName();
		}
		
		protected function getId()
		{
			return $this->hasParent() ?
				$this->getParent()->getId() . "_" . $this->getField()->getName() :
				$this->getField()->getName();
		}
		
		protected function fieldParams($params = array(), $names = true)
		{
			return array_merge($this->defaultParams($names), $params);
		}
		
		protected function defaultParams($names = true)
		{
			return array_merge(
				$names ?
					array(
						"name" => $this->getName(),
						"id" => $this->getId()
					) :
					array(),
				$this->hasParam("html") ?
					$this->getParam("html") :
					array()
			);
		}
		
		/* Tags */
		
		protected function tag($name, $content = null, $params = array())
		{
			return $content === false ?
				$this->singleTag($name, $params) :
				$this->openTag($name, $params) . $content . $this->closeTag($name);
		}
		
		protected function openTag($name, $params = array())
		{
			return "<" . strtolower($name) . $this->parameterList($params) . ">";
		}
		
		protected function closeTag($name)
		{
			return "</" . strtolower($name) . ">";
		}
		
		protected function singleTag($name, $params = array())
		{
			return "<" . strtolower($name) . $this->parameterList($params) . " />";
		}
		
		protected function parameterList($params)
		{
			return implode("", array_map(
				function($key) use ($params) {
					return " " . strtolower($key) . '="' . Helpers::escapeHtml($params[$key]) . '"';
				},
				array_keys($params)
			));
		}
	}