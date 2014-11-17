<?php

	class FormRendererProvider
	{
		static protected $defaults = array(
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
			
			$renderers = array_merge(
				self::$defaults,
				static::getRenderers()
			);
			if(!isset($renderers[$class]))
			{
				throw new Exception("No renderer defined for " . $class . ".");
			}
			
			$renderer = $renderers[$class] . "Renderer";
			foreach(static::getPrefixes() as $prefix)
			{
				if(class_exists($prefix . $renderer))
				{
					$renderer = $prefix . $renderer;
					break;
				}
			}
			
			return new $renderer($field, $parent, $params);
		}
		
		static protected function getPrefixes()
		{
			return array();
		}
		
		static protected function getRenderers()
		{
			return array();
		}
	}