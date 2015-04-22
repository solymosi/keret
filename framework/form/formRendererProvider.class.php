<?php

	class FormRendererProvider
	{
		static protected $defaults = array(
			"FieldSet"      => "FieldSet",
			"Form"          => "Form",
			"TextField"     => "InputField",
			"PasswordField" => "InputField",
			"HiddenField"   => "InputField",
			"Textarea"      => "Textarea",
			"Checkbox"      => "Checkbox",
			"CheckboxGroup" => "Group",
			"SelectField"   => "SelectField",
			"RadioGroup"    => "RadioGroup",
			"Button"        => "Button",
			"SubmitButton"  => "Button",
		);
		
		static public function getRenderer($field, $parent = null, $params = array())
		{
			$renderer = $field->getParam("renderer");
			
			if(is_callable($renderer))
			{
				return new CustomRenderer($field, $parent, array_merge($params, array("renderer" => $renderer)));
			}
			elseif(is_string($renderer))
			{
				$class = self::getRendererClass($renderer);
				return new $class($field, $parent, $params);
			}
			else
			{
				return self::getDefaultRenderer($field, $parent, $params);
			}
		}
		
		static public function getDefaultRenderer($field, $parent = null, $params = array())
		{
			$renderer = Helpers::select(
				get_class($field),
				array_merge(
					self::$defaults,
					static::getRenderers()
				)
			);
			
			Helpers::when(is_null($renderer), "No default renderer defined for " . get_class($field) . ".");
			
			$class = self::getRendererClass($renderer);
			return new $class($field, $parent, $params);
		}
		
		static protected function getRendererClass($renderer)
		{
			$class = $renderer . "Renderer";
			
			foreach(static::getPrefixes() as $prefix)
			{
				if(class_exists($prefix . $class))
				{
					return $prefix . $class;
				}
			}
			
			return $class;
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