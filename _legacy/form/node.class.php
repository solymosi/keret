<?php

	class Node
	{
		protected $tag = null;
		protected $parent = null;
		protected $single = false;
		
		
		
		public function __construct($tag, $params = array(), $children = array())
		{
			self::whenNot(is_string($tag), "The tag name must be a string.");
			
			$this->tag = $tag;
			$this->addParams($params);
			$this->addChildren($children);
		}
		
		
		
		
		
		
		
		
		protected function getPath()
		{
			$parent = $this->hasParent() ? $this->getParent()->getPath() : array();
			return isset($this->name) && !is_null($this->name) ? array_merge($parent, array($this->name)) : $parent;
		}
		
		public function render()
		{
			$result = "<" . mb_strtolower($this->tag);
			
			foreach($this->params as $name => $value)
			{
				$result .= " " . $name . '="' . Helpers::escapeHtml($value) . '"';
			}
			
			if($this->single)
			{
				$result .= " />";
			}
			else
			{
				$result .= ">";
				
				foreach($this->getOrderedChildren() as $child)
				{
					$result .= $child->render();
				}
				
				$result .= "</" . mb_strtolower($this->tag) . ">";
			}
			
			return $result;
		}
		
		static public function mergeParams($first, $second)
		{
			self::whenNot(is_array($first), "The first parameter list must be an array.");
			self::whenNot(is_array($second), "The second parameter list must be an array.");
			
			$classes = array();
			
			foreach(array_merge(isset($first["class"]) ? explode(" ", $first["class"]) : array(), isset($second["class"]) ? explode(" ", $second["class"]) : array()) as $class)
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
			
			return array_merge($first, $second, count($classes) > 0 ? array("class" => implode(" ", $classes)) : array());
		}
		
	}

?>