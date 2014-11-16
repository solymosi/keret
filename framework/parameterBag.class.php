<?php

	class ParameterBag implements IteratorAggregate, Countable
	{
		protected $items = array();
		protected $mergers = array();
		
		public function __construct($items = array(), $mergers = array())
		{
			$this->replace($items);
			$this->setMergers($mergers);
		}
		
		/* Items */
		
		public function has($name)
		{
			Helpers::whenNot(is_string($name), "The parameter name must be a string.");
			return isset($this->items[$name]);
		}
		
		public function get($name)
		{
			return $this->has($name) ? $this->items[$name] : null;
		}
		
		public function set($name, $value)
		{
			Helpers::whenNot(is_string($name), "The parameter name must be a string.");
			$this->items[$name] = $value;
		}
		
		public function add($name, $value)
		{
			$merger = $this->getMerger($name);
			$this->set(
				$name,
				$merger($this->get($name), $value)
			);
		}
		
		public function delete($name)
		{
			if($this->has($name))
			{
				unset($this->items[$name]);
			}
		}
		
		/* Collection */
		
		public function count()
		{
			return count($this->items);
		}
		
		public function keys()
		{
			return array_keys($this->items);
		}
		
		public function all()
		{
			return $this->items;
		}
		
		public function replace($items)
		{
			Helpers::whenNot(is_array($items), "The parameter list must be an array.");
			foreach($items as $name => $value)
			{
				$this->set($name, $value);
			}
		}
		
		public function merge($items)
		{
			$this->replace(array_reduce(
				array_keys($this->items + $items),
				function($array, $name) use ($items) {
					$merger = $this->getMerger($name);
					$array[$name] = isset($items[$name]) ?
						$merger($this->get($name), $items[$name]) :
						$this->get($name);
					return $array;
				},
				array()
			));
		}
		
		public function clear()
		{
			$this->items = array();
		}
		
		public function getIterator()
		{
			return new ArrayIterator($this->items);
		}
		
		/* Mergers */
		
		public function getMerger($name)
		{
			return isset($this->mergers[$name]) ?
				$this->mergers[$name] :
				function($old, $new) {
					return $new;
				};
		}
		
		public function setMerger($name, $merger)
		{
			Helpers::whenNot(is_callable($name), "The merger function must be a callable.");
			$this->mergers[$name] = $merger;
		}
		
		public function getMergers()
		{
			return $this->mergers;
		}
		
		public function setMergers($mergers)
		{
			Helpers::whenNot(is_array($mergers), "The merger list must be an array.");
			foreach($mergers as $name => $callable)
			{
				$this->setMerger($name, $callable);
			}
		}
	}