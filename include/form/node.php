<?php

	class Node
	{
		protected $tag = null;
		protected $single = false;
		protected $items = array();
		protected $params = array();
		protected $order = array();
		
		public function __construct($tag, $params = array(), $items = array())
		{
			$this->tag = $tag;
			$this->setParams(array_merge($this->params, $params));
			$this->setItems(array_merge($this->items, $items));
		}
		
		/* PARAMETERS */
		
		public function set($key, $value)
		{
			$this->params[$key] = $value;
			return $this;
		}
		
		public function __set($key, $value)
		{
			return $this->set($key, $value);
		}
		
		public function get($key)
		{
			return isset($this->params[$key]) ? $this->params[$key] : null;
		}
		
		public function __get($key)
		{
			return $this->get($key);
		}
		
		public function __isset($key)
		{
			return isset($this->params[$key]);
		}
		
		public function setParams($params)
		{
			if(!is_array($params))
			{
				throw new Exception("The variable passed to setParams() must be an array");
			}
			$this->params = $params;
			return $this;
		}
		
		public function getParams()
		{
			return $this->params;
		}
		
		/* CHILD ITEMS */
		
		public function add($id, $item, $top = false)
		{
			if(!($item instanceof Node))
			{
				throw new Exception("The variable passed to add() must be an instance of Node");
			}
			if($top)
			{
				array_unshift($this->items, $item);
			}
			else
			{
				$this->items[$id] = $item;
			}
			return $this;
		}
		
		public function remove($id)
		{
			if($this->has($id))
			{
				unset($this->items[$id]);
			}
			return $this;
		}
		
		public function item($id)
		{
			return $this->has($id) ? $this->items[$id] : null;
		}
		
		public function has($id)
		{
			return isset($this->items[$id]);
		}
		
		public function getItems()
		{
			return $this->items;
		}
		
		public function setItems($items)
		{
			if(!is_array($items))
			{
				throw new Exception("The variable passed to setItems() must be an array");
			}
			$this->items = $items;
			return $this;
		}
		
		protected function sortItems()
		{
			foreach(array_reverse($this->order) as $id)
			{
				if($this->has($id))
				{
					$item = $this->item($id);
					$this->remove($id);
					$this->add($id, $item, true);
				}
			}
		}
		
		public function render()
		{
			$result = "<" . strtolower($this->tag);
			foreach($this->params as $key => $value)
			{
				$result .= " " . $key . "=\"" . ($value ? Helpers::h($value) : "") . "\"";
			}
			
			if($this->single)
			{
				$result .= " />";
			}
			else
			{
				$result .= ">";
				
				$this->sortItems();
				foreach($this->items as $item)
				{
					$result .= $item->render();
				}
				
				$result .= "</" . $this->tag . ">";
			}
			
			return $result;
		}
		
	}

?>