<?php

	class FormRendererProvider
	{
		static protected $renderers = array(
			"FieldSet"      => "FieldSet",
			"Form"          => "Form",
			"TextField"     => "InputField",
			"PasswordField" => "InputField",
			"HiddenField"   => "InputField",
			"Button"        => "Button",
			"SubmitButton"  => "Button",
		);
		
		static public function getRenderer($field, $parent = null, $params = array())
		{
			$class = get_class($field);
			
			if(!isset(static::$renderers[$class]))
			{
				throw new Exception("No renderer defined for " . $class . ".");
			}
			
			$renderer = static::$renderers[$class] . "Renderer";
			return new $renderer($field, $parent, $params);
		}
	}