<?php

  class Template
  {
    /*
      This class represents a single template which consists of a template file
      whose path is stored in the 'file' variable, and template parameters which
      are the main way for passing in dynamic data for the template to display.

      You will probably not instantiate and use this class directly, since the
      View class provides methods ('render' and 'getContent') which make that
      easier for you. Nevertheless, it is useful to know that regular templates,
      layouts and partials are all rendered using this class behind the scenes.

      Since all template files are rendered by this class, the $this variable
      in every template points to the corresponding Template instance, providing
      easy access to the template parameters ($this->get, $this->set and their
      "magic" getter and setter versions). The framework also gives you the
      possibility to extend this class with custom functions and then use that
      new class for the rendering of every template (you can configure which
      custom class to use using the config variable 'view.template_class').
      A 'CustomTemplate' class that does exactly this is already present and
      configured in the 'lib' folder of the provided app skeleton.
    */

    /* Holds the path to the template file */
    protected $file = null;

    /* Holds the template parameters */
    protected $params = array();

    /* Creates a new instance with the given template file path and parameters */
    public function __construct($file, $params = array())
    {
      /* Store the file path */
      $this->file = $file;

      /*
        Instead of simply overwriting the entire parameter array with the
        parameters that were passed in, we need to merge them, as another
        constructor of a child class might have already put something in there.
      */
      $this->setParams(array_merge($this->getParams(), $params));
    }

    /*
      Magic method that makes it possible to check the existence of template
      parameters by calling 'isset' directly on $this->[name of parameter].
    */
    public function __isset($key)
    {
      return isset($this->params[$key]);
    }

    /*
      Returns the value of the template parameter under the specified name,
      or null if no such parameter exists.
    */
    public function get($key)
    {
      return isset($this->params[$key]) ? $this->params[$key] : null;
    }

    /*
      Magic method that makes it possible to use $this->[name of parameter]
      for fetching a template parameter, instead of calling $this->get( name ).
    */
    public function __get($key)
    {
      return $this->get($key);
    }

    /* Sets the value of the template parameter under the specified name */
    public function set($key, $value)
    {
      $this->params[$key] = $value;
    }

    /*
      Magic method that makes it possible to simply assign a value to
      $this->[name of parameter] instead of calling $this->set( name, value ).
    */
    public function __set($key, $value)
    {
      $this->set($key, $value);
    }

    /* Returns all template parameters in an associative array */
    public function getParams()
    {
      return $this->params;
    }

    /* Replaces all existing template parameters with the provided new ones */
    public function setParams($params)
    {
      if(!is_array($params))
      {
        throw new Exception("The variable passed to setParams() must be an array");
      }
      $this->params = $params;
    }

    /* Adds the provided parameters with the already existing ones by merging them */
    public function addParams($params)
    {
      if(!is_array($params))
      {
        throw new Exception("The variable passed to addParams() must be an array");
      }
      $this->setParams(array_merge($this->params, $params));
    }

    /* Renders this template and returns the resulting content */
    public function getContent()
    {
      /* Start an output buffer */
      ob_start();

      /* Include the template file */
      include(Application::root() . "/templates/" . $this->file . ".php");

      /* Retrieve the contents of the buffer, then delete the buffer */
      $content = ob_get_contents();
      ob_end_clean();

      /* Return the content */
      return $content;
    }

    /*
      Renders the specified template similar to View::render, but this method
      passes all parameters of the current template to the new template as well.
      This makes it very easy to render partials from within templates, making
      all parameters of the outer template also available within the partial.
      The name 'insert' reinforces that it is best used to "insert" partials.

      Example:  <?= $this->insert("_product", array("product" => $item)) ?>
      This renders the '_product' template, passing in a 'product' parameter.
      The convention is to start partial names with an underscore ('_').
    */
    public function insert($file, $params = array())
    {
      return View::getContent($file, array_merge($this->params, $params), false);
    }
  }
