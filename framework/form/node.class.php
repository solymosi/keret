<?php

	class Node
	{
		protected $tag = null;
		protected $parent = null;
		protected $single = false;
		protected $params = array();
		protected $children = array();
		protected $order = array();
		
		public function __construct($tag, $params = array(), $children = array())
		{
			self::whenNot(is_string($tag), "The tag name must be a string.");
			
			$this->tag = $tag;
			$this->addParams($params);
			$this->addChildren($children);
		}
		
		public function setParam($name, $value)
		{
			self::whenNot(is_string($name), "The parameter name must be a string.");
			
			$this->params[$name] = $value;
			
			return $this;
		}
		
		public function getParam($name)
		{
			self::whenNot(is_string($name), "The parameter name must be a string.");
			
			return isset($this->params[$name]) ? $this->params[$name] : null;
		}
		
		public function clearParam($name)
		{
			self::whenNot(is_string($name), "The parameter name must be a string.");
			
			if(isset($this->params[$name]))
			{
				unset($this->params[$name]);
			}
			
			return $this;
		}
		
		public function addParams($params)
		{
			self::whenNot(is_array($params), "The parameter list must be an array.");
			
			$this->setParams(self::mergeParams($this->params, $params));
			return $this;
		}
		
		public function getParams()
		{
			return $this->params;
		}
		
		public function setParams($params)
		{
			self::whenNot(is_array($params), "The parameter list must be an array.");
			
			$this->clearParams();
			
			foreach($params as $name => $value)
			{
				$this->setParam($name, $value);
			}
			
			return $this;
		}
		
		public function clearParams()
		{
			$this->params = array();
			return $this;
		}
		
		public function addChild($name, $child, $top = false)
		{
			self::whenNot(is_string($name), "The name of the child node must be a string.");
			self::whenNot($child instanceof Node, "An instance of Node is required.");
			
			$this->children[$name] = $child;
			$child->setParent($this);
			
			return $this;
		}
		
		public function addChildren($children)
		{
			self::whenNot(is_array($children), "The child list must be an array.");
			
			foreach($children as $name => $child)
			{
				$this->addChild($name, $child);
			}
			
			return $this;
		}
		
		public function removeChild($name)
		{
			self::whenNot(is_string($name), "The name of the child node must be a string.");
			
			if($this->hasChild($name))
			{
				$child = $this->getChild($name);
				unset($this->children[$name]);
				$child->clearParent();
			}
			
			return $this;
		}
		
		public function getChild($name)
		{
			self::whenNot(is_string($name), "The name of the child node must be a string.");
			
			return $this->hasChild($name) ? $this->children[$name] : null;
		}
		
		public function hasChild($name)
		{
			self::whenNot(is_string($name), "The name of the child node must be a string.");
			
			return isset($this->children[$name]);
		}
		
		public function getChildren()
		{
			return $this->children;
		}
		
		public function setChildren($children)
		{
			self::whenNot(is_array($children), "The child list must be an array.");
			
			$this->clearChildren();
			$this->addChildren($children);
			
			return $this;
		}
		
		public function clearChildren()
		{
			$this->children = array();
			return $this;
		}
		
		protected function getParent()
		{
			return $this->parent;
		}
		
		protected function hasParent()
		{
			return $this->parent != null;
		}
		
		protected function setParent($parent)
		{
			self::whenNot($parent instanceof Node, "An instance of Node is required.");
			
			foreach($parent->getChildren() as $child)
			{
				if($child === $this)
				{
					$this->parent = $parent;
					return $this;
				}
			}
			
			throw new Exception("Node is not child of the specified parent.");
		}
		
		protected function clearParent()
		{
			foreach($this->getParent()->getChildren() as $child)
			{
				self::when($child === $this, "Node is still a child of its parent.");
			}
			
			$this->parent = null;
			return $this;
		}
		
		public function getOrder()
		{
			return $this->order;
		}
		
		public function setOrder($order)
		{
			self::whenNot(is_array($order), "The order list must be an array.");
			
			$this->order = $order;
			
			return $this;
		}
		
		protected function getOrderedChildren()
		{
			$children = array();
			
			foreach($this->getOrder() as $name)
			{
				if($this->hasChild($name))
				{
					$children[$name] = $this->getChild($name);
				}
			}
			
			foreach($this->getChildren() as $name => $child)
			{
				if(!in_array($name, $this->getOrder()))
				{
					$children[$name] = $child;
				}
			}
			
			return $children;
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
		
		static public function when($expression, $message)
		{
			if($expression)
			{
				throw new Exception($message);
			}
		}
		
		static public function whenNot($expression, $message)
		{
			self::when(!$expression, $message);
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