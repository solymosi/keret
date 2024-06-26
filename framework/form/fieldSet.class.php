<?php

  class FieldSet extends Field
  {
    protected $children = array();
    protected $order = array();

    public function __construct($name, $params = array(), $children = array())
    {
      parent::__construct($name, array(), $params);
      $this->addChildren($children);
    }

    /* Children */

    public function addChild($child)
    {
      Helpers::whenNot($child instanceof Field, "An instance of Field is required.");

      $this->children[$child->getName()] = $child;
      $child->setParent($this);

      return $this;
    }

    public function addChildren($children)
    {
      Helpers::whenNot(is_array($children), "The child list must be an array.");

      foreach($children as $child)
      {
        $this->addChild($child);
      }

      return $this;
    }

    public function removeChild($name)
    {
      Helpers::whenNot(is_string($name), "The name of the child must be a string.");

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
      Helpers::whenNot(is_string($name), "The name of the child must be a string.");

      return $this->hasChild($name) ? $this->children[$name] : null;
    }

    public function hasChild($name)
    {
      Helpers::whenNot(is_string($name), "The name of the child must be a string.");

      return isset($this->children[$name]);
    }

    public function getChildren()
    {
      return $this->children;
    }

    public function setChildren($children)
    {
      Helpers::whenNot(is_array($children), "The child list must be an array.");

      $this->clearChildren();
      $this->addChildren($children);

      return $this;
    }

    public function clearChildren()
    {
      $this->children = array();
      return $this;
    }

    /* Order */

    public function getOrder()
    {
      return $this->order;
    }

    public function setOrder($order)
    {
      Helpers::whenNot(is_array($order), "The order list must be an array.");

      $this->order = $order;

      return $this;
    }

    public function getOrderedChildren()
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

    /* Value */

    public function getValue()
    {
      return $this->getValues();
    }

    public function getValueOrNull()
    {
      return $this->getValue();
    }

    public function setValue($value)
    {
      return $this->setValues($value);
    }

    public function hasValue()
    {
      return $this->hasValues();
    }

    public function clearValue()
    {
      return $this->clearValues();
    }

    /* Values */

    public function getValues()
    {
      return array_map(function($child) {
        return $child->getValue();
      }, $this->getChildren());
    }

    public function setValues($values)
    {
      Helpers::whenNot(is_array($values), "The value list must be an array.");

      foreach($values as $name => $value)
      {
        if($this->hasChild($name))
        {
          $this->getChild($name)->setValue($value);
        }
      }

      return $this;
    }

    public function hasValues()
    {
      return array_reduce(
        $this->getChildren(),
        function($value, $child) {
          return $value || $child->hasValue();
        },
        false
      );
    }

    public function isBlank()
    {
      return array_reduce(
        $this->getChildren(),
        function($blank, $child) {
          return $blank && $child->isBlank();
        },
        true
      );
    }

    public function clearValues()
    {
      foreach($this->getChildren() as $name => $child)
      {
        $child->clearValue();
      }

      return $this;
    }

    /* Errors */

    public function addError($field, $message = null)
    {
      if(is_null($message))
      {
        $message = $field;
        $field = null;
      }

      if(is_null($field))
      {
        parent::addError($message);
      }
      else
      {
        $this->getChild($field)->addError($message);
      }

      return $this;
    }

    public function getErrors($recursive = false)
    {
      return array_filter(array_reduce(
        $recursive ?
          $this->getChildren() :
          array(),
        function($errors, $child) {
          return array_merge(
            $errors,
            array(
              $child->getName() => $child instanceof FieldSet ?
                $child->getErrors(true) :
                $child->getErrors()
            )
          );
        },
        parent::getErrors()
      ));
    }

    public function hasErrors($recursive = false)
    {
      return count($this->getErrors($recursive)) > 0;
    }

    public function clearErrors($recursive = false)
    {
      parent::clearErrors();

      if($recursive)
      {
        foreach($this->getChildren() as $name => $child)
        {
          $child instanceof FieldSet ?
            $child->clearErrors(true) :
            $child->clearErrors();
        }
      }
    }

    /* Validators */

    public function isValid($recursive = true)
    {
      if($recursive)
      {
        array_map(function($child) {
          $child->isValid();
        }, $this->getChildren());
      }
      parent::isValid();

      return !$this->hasErrors(true);
    }
  }
