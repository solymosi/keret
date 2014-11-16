<?php

	class Form extends FieldSet
	{
		public function __construct($name = "form", $action = "", $method = "post", $params = array(), $children = array())
		{
			Helpers::whenNot(is_string($name), "The form name must be a string.");
			Helpers::whenNot(is_string($action), "The form action must be a string.");
			Helpers::whenNot(is_string($method), "The form method must be a string.");
			
			parent::__construct($name, $params, $children);
			$this->addParams(array(
				"action" => $action,
				"method" => mb_strtolower($method),
			));
			
			if($this->getParam("method") == "post")
			{
				$this->addCsrfField();
			}
		}
		
		public function addCsrfField()
		{
			$this->addChild(new HiddenField("csrf_field", Session::csrfToken()));
			$this->getChild("csrf_field")->addValidator(
				new CustomValidator(function($field) {
					Session::verifyCsrfToken($field->getValue());
				})
			);
		}
		
		public function removeCsrfField()
		{
			$this->removeChild("csrf_field");
		}
	}