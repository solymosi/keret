<?php
	
	abstract class Field
	{
		protected $name = null;
		protected $value = null;
		protected $parent = null;
		protected $errors = array();
		protected $params = null;
		protected $disabled = false;
		protected $validators = array();
		
		public function __construct($name, $value = null, $params = array())
		{
			Helpers::whenNot(is_string($name), "The field name must be a string.");
			$this->name = $name;
			
			$this->setValue($value);
			$this->params = new ParameterBag($params);
		}
		
		/* Name */
		
		public function getName()
		{
			return $this->name;
		}
		
		/* Value */
		
		public function getValue()
		{
			return $this->value;
		}
		
		public function getValueOrNull()
		{
			return $this->isBlank() ? null : $this->getValue();
		}
		
		public function setValue($value)
		{
			$this->value = $value;
			return $this;
		}
		
		public function hasValue()
		{
			return !is_null($this->value);
		}
		
		public function clearValue()
		{
			$this->value = null;
			
			return $this;
		}
		
		public function isBlank()
		{
			$value = $this->getValue();
			
			return
				$value === false || is_null($value) ||
				(is_string($value) && trim($value) === "") ||
				(is_array($value) && count($value) === 0);
		}
		
		/* Errors */
		
		public function addError($message)
		{
			Helpers::whenNot(is_string($message), "The error message must be a string.");
			
			$this->errors[] = $message;
			
			return $this;
		}
		
		public function getErrors()
		{
			return $this->errors;
		}
		
		public function hasErrors()
		{
			return count($this->errors) > 0;
		}
		
		public function clearErrors()
		{
			$this->errors = array();
			
			return $this;
		}
		
		/* Validators */
		
		public function addValidator($validator)
		{
			Helpers::whenNot($validator instanceof Validator, "The validator must be a Validator instance.");
			
			$this->validators[] = $validator;
			
			return $this;
		}
		
		public function addValidators($validators)
		{
			Helpers::whenNot(is_array($validators), "The validator list must be an array.");
			
			foreach($validators as $validator)
			{
				$this->addValidator($validator);
			}
			
			return $this;
		}
		
		public function hasValidators()
		{
			return count($this->validators) > 0;
		}
		
		public function getValidators()
		{
			return $this->validators;
		}
		
		public function setValidators($validators)
		{
			Helpers::whenNot(is_array($validators), "The validator list must be an array.");
			
			$this->clearValidators();
			$this->addValidators($validators);
			
			return $this;
		}
		
		public function clearValidators()
		{
			$this->validators = array();
			
			return $this;
		}
		
		public function isValid()
		{
			$this->clearErrors();
			
			foreach($this->getValidators() as $validator)
			{
				$validator->validate($this);
			}
			
			return !$this->hasErrors();
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
			return $this;
		}
		
		public function addParam($name, $value)
		{
			$this->params->add($name, $value);
			return $this;
		}
		
		public function clearParam($name)
		{
			$this->params->delete($name);
			return $this;
		}
		
		public function getParams()
		{
			return $this->params->all();
		}
		
		public function setParams($params)
		{
			$this->params->replace($params);
			return $this;
		}
		
		public function addParams($params)
		{
			$this->params->merge($params);
			return $this;
		}
		
		public function clearParams()
		{
			$this->params->clear();
			return $this;
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
			Helpers::whenNot($parent instanceof FieldSet, "An instance of FieldSet is required.");
			
			foreach($parent->getChildren() as $child)
			{
				if($child === $this)
				{
					$this->parent = $parent;
					return $this;
				}
			}
			
			throw new Exception("This field is not a child of the specified parent.");
		}
		
		protected function clearParent()
		{
			foreach($this->getParent()->getChildren() as $child)
			{
				Helpers::when($child === $this, "This field is still a child of its parent.");
			}
			
			$this->parent = null;
			return $this;
		}
		
		/* Form */
		
		public function getForm()
		{
			if($this instanceof Form)
			{
				return $this;
			}
			else
			{
				return $this->hasParent() ? $this->getParent()->getForm() : null;
			}
		}
		
		/* Render */
		
		public function getRenderer($parent = null, $params = array())
		{
			$provider = Config::get("view.form_renderer_provider_class");
			
			return $provider::getRenderer($this, $parent, $params);
		}
		
		public function getDefaultRenderer($parent = null, $params = array())
		{
			$provider = Config::get("view.form_renderer_provider_class");
			
			return $provider::getDefaultRenderer($this, $parent, $params);
		}
		
		public function setRenderer($renderer)
		{
			$this->setParam("renderer", $renderer);
			return $this;
		}
		
		public function render($parent = null, $params = array())
		{
			return $this->getRenderer($parent, $params)->render();
		}
		
		/* Disabled */
		
		public function setDisabled($disabled = true)
		{
			$this->disabled = $disabled;
		}
		
		public function isDisabled()
		{
			return $this->disabled || ($this->hasParent() && $this->getParent()->isDisabled());
		}
	}