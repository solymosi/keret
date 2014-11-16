<?php

	abstract class Renderer
	{
		protected $field = null;
		protected $parent = null;
		protected $params = null;
		protected $defaults = array(
			"row" => true,
		);
		
		public function __construct($field, $parent = null, $params = array())
		{
			Helpers::whenNot($field instanceof Field, "The object provided to the renderer must be a Field.");
			$this->field = $field;
			
			if(!is_null($parent))
			{
				$this->setParent($parent);
			}
			
			$this->params = new ParameterBag($this->defaults);
			$this->params->setMerger("html", array($this, "mergeHtmlParams"));
			$this->params->merge($field->getParams());
			$this->params->merge($params);
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
		
		public function hasParam($name)
		{
			return $this->params->has($name);
		}
		
		public function getParam($name)
		{
			return $this->params->get($name);
		}
		
		public function setParam($name, $value)
		{
			$this->params->set($name, $value);
		}
		
		public function addParam($name, $value)
		{
			$this->params->add($name, $value);
		}
		
		public function clearParam($name)
		{
			$this->params->delete($name);
		}
		
		public function getParams()
		{
			return $this->params->all();
		}
		
		public function setParams($params)
		{
			$this->params->replace($params);
		}
		
		public function addParams($params)
		{
			$this->params->merge($params);
		}
		
		public function clearParams()
		{
			$this->params->clear();
		}
		
		/* Render */
		
		abstract public function render();
		
		/* HTML */
		
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
			return $this->mergeHtmlParams($this->defaultParams($names), $params);
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
		
		public function mergeHtmlParams($first, $second)
		{
			if(is_null($first))
			{
				$first = array();
			}
			
			Helpers::whenNot(is_array($first), "The first HTML parameter list must be an array.");
			Helpers::whenNot(is_array($second), "The second HTML parameter list must be an array.");
			
			$classes = array();
			
			foreach(array_merge(
					isset($first["class"]) ?
						explode(" ", $first["class"]) :
						array(),
					isset($second["class"]) ?
						explode(" ", $second["class"]) :
						array()
			) as $class)
			{
				if(trim($class) != "")
				{
					if(substr($class, 0, 1) == "-")
					{
						$classes = array_diff($classes, array(substr($class, 1)));
					}
					elseif(!in_array($class, $classes))
					{
						$classes[] = substr($class, substr($class, 0, 1) == "+" ? 1 : 0);
					}
				}
			}
			
			unset($first["class"]);
			unset($second["class"]);
			
			return array_merge(
				$first,
				$second,
				count($classes) > 0 ?
					array("class" => implode(" ", $classes)) :
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