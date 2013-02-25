<?php
	
	class Form extends Node
	{
		public function __construct($action = "", $method = "post", $params = array(), $items = array())
		{
			parent::__construct("form", array_merge($params, array("action" => $action, "method" => $method)), $items);
		}
		
		public function bind($values)
		{
			foreach($values as $id => $value)
			{
				if($this->has($id) && $this->item($id) instanceof Row)
				{
					$this->item($id)->setValue($value);
				}
			}
			return $this;
		}
		
		public function data()
		{
			$data = array();
			foreach($this->items as $id => $item)
			{
				if($this->item($id) instanceof Row)
				{
					$data[$id] = $item->getValue();
				}
			}
			return $data;
		}
		
		public function error($id, $error)
		{
			if($this->item($id) instanceof Row)
			{
				$this->item($id)->addError($error);
			}
			return $this;
		}
		
		public function errors()
		{
			$errors = array();
			foreach($this->items as $id => $item)
			{
				if($this->item($id) instanceof Row)
				{
					$errors[$id] = $item->getErrors();
				}
			}
			return $errors;
		}
	}
	
?>