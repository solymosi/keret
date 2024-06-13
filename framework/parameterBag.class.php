<?php

  class ParameterBag implements IteratorAggregate, Countable
  {
    /*
      This class implements a simple key-value store for holding parameters
      and is used in multiple other modules of the framework, most notably the
      templating module (for storing template parameters).

      As an advanced feature, custom merger functions may be defined for
      individual keys, implementing custom functionality when a value under
      one of those keys is being replaced by a new value. The default merger
      function implements a simple 'replace' functionality and is used when
      no custom merger function is defined for a key.

      Parameter bags support the following key-level operations:
      - has: returns whether the specified key exists
      - get: returns the value corresponding to the specified key
      - set: replaces the value under the specified key
      - add: merges the value under the specified key with the provided new
             value using the custom merger function defined for that key (if no
             such function is defined, this does the same as 'set')
      - delete: removes the specified key

      The following collection-level operations are available:
      - count: returns the number of parameters stored in the bag
      - keys: returns all parameter keys in an array
      - all: returns all stored parameters in an associative array
      - replace: adds the provided new parameters to the bag, replacing
                 existing parameters under the same names
      - merge: merges the provided new parameters with the existing parameters
               using the defined custom merger functions, if any
      - clear: removes all stored parameters from the bag
    */

    /* Holds the parameters stored in this bag */
    protected $items = array();

    /* Holds the merger functions that were defined for certain keys */
    protected $mergers = array();

    /* Creates a new bag with the specified parameters and merger functions */
    public function __construct($items = array(), $mergers = array())
    {
      $this->replace($items);
      $this->setMergers($mergers);
    }

    /* Items */

    /* Returns whether a parameter exists under the specified name */
    public function has($name)
    {
      Helpers::whenNot(is_string($name), "The parameter name must be a string.");
      return isset($this->items[$name]);
    }

    /* Returns the parameter value stored under the specified name */
    public function get($name)
    {
      return $this->has($name) ? $this->items[$name] : null;
    }

    /* Sets the parameter value stored under the specified name */
    public function set($name, $value)
    {
      Helpers::whenNot(is_string($name), "The parameter name must be a string.");
      $this->items[$name] = $value;
    }

    /*
      Merges the parameter value stored under the specified name with the
      provided new value using the custom merger function defined for that
      parameter. If no such function is defined, this does the same as 'set'.
    */
    public function add($name, $value)
    {
      $merger = $this->getMerger($name);
      $this->set(
        $name,
        $merger($this->get($name), $value)
      );
    }

    /* Removes the parameter with the specified name from the bag */
    public function delete($name)
    {
      if($this->has($name))
      {
        unset($this->items[$name]);
      }
    }

    /* Collection */

    /* Returns the number of parameters stored in the bag */
    public function count()
    {
      return count($this->items);
    }

    /* Returns the names of the parameters stored in the bag */
    public function keys()
    {
      return array_keys($this->items);
    }

    /* Returns all parameters stored in the bag in an associative array */
    public function all()
    {
      return $this->items;
    }

    /*
      Adds the specified new parameters to the bag, replacing already
      existing parameters under the same parameter names.
    */
    public function replace($items)
    {
      Helpers::whenNot(is_array($items), "The parameter list must be an array.");

      /* Use 'set' to set each parameter individually */
      foreach($items as $name => $value)
      {
        $this->set($name, $value);
      }
    }

    /*
      Merges the provided new parameters with the existing parameters in the bag
      using the defined custom merger functions for each parameter. In case of
      parameters with no defined merger functions, this will perform a regular
      replace operation. If no parameters have a custom merger function, the
      entire operation will be the same as with 'replace'.
    */
    public function merge($items)
    {
      $this->replace(array_reduce(
        /* Reduce based on the keys in both arrays */
        array_keys($this->items + $items),
        /* This reduce function is executed for each such key */
        function($array, $name) use ($items) {
          /* Get the merger function to use */
          $merger = $this->getMerger($name);
          /* If we have a new value, do a merge; otherwise, use the old one */
          $array[$name] = isset($items[$name]) ?
            $merger($this->get($name), $items[$name]) :
            $this->get($name);
          /* Move to the next item */
          return $array;
        },
        array()
      ));
    }

    /* Removes all parameters from the bag */
    public function clear()
    {
      $this->items = array();
    }

    /* Returns an iterator which can be used to iterate over the parameters */
    public function getIterator(): Traversable
    {
      return new ArrayIterator($this->items);
    }

    /* Mergers */

    /*
      Returns the custom merger function for the specified parameter name, or
      the default merger function (which does a simple replace) if no such
      function is defined for that parameter.
    */
    public function getMerger($name)
    {
      return isset($this->mergers[$name]) ?
        $this->mergers[$name] :
        /* Default merger function */
        function($old, $new) {
          return $new;
        };
    }

    /* Attaches a custom merger function to the specified parameter */
    public function setMerger($name, $merger)
    {
      Helpers::whenNot(is_callable($name), "The merger function must be a callable.");
      $this->mergers[$name] = $merger;
    }

    /* Returns all custom merger functions in an associative array */
    public function getMergers()
    {
      return $this->mergers;
    }

    /* Sets custom merger functions for multiple parameters at once */
    public function setMergers($mergers)
    {
      Helpers::whenNot(is_array($mergers), "The merger list must be an array.");
      foreach($mergers as $name => $callable)
      {
        $this->setMerger($name, $callable);
      }
    }
  }
