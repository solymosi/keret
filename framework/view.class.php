<?php

  abstract class View
  {
    /*
      This class provides methods for rendering templates using the Template
      class, decorating them with layouts, as well as setting global template
      parameters which are made available in every template in addition to the
      own (local) parameters of the template.
    */

    /*
      The layout to decorate rendered templates with. If this is specified,
      every template rendered using View::render or View::getContent will be
      decorated using this layout, except when another layout is explicitly
      defined when calling these methods. If this is not specified, the
      layout defined by the config variable 'view.default_layout' will be used.
    */
    static protected $layout = null;

    /*
      Holds global template parameters, which are made available in every
      template rendered using View::render or View:::getContent, in addition
      to the own (local) variables of the template.
    */
    static protected $params = array();

    /* Returns the value of the specified global template variable */
    static public function get($key)
    {
      return isset(self::$params[$key]) ? self::$params[$key] : null;
    }

    /* Sets the value of the specified global template variable */
    static public function set($key, $value)
    {
      self::$params[$key] = $value;
    }

    /* Returns all global template variables in an associative array */
    static public function getParams()
    {
      return self::$params;
    }

    /* Replaces all global template variables with the provided new ones */
    static public function setParams($params)
    {
      if(!is_array($params))
      {
        throw new Exception("The variable passed to setParams() must be an array");
      }
      self::$params = $params;
    }

    /* Merges existing global template variables with the provided new ones */
    static public function addParams($params)
    {
      if(!is_array($params))
      {
        throw new Exception("The variable passed to addParams() must be an array");
      }
      self::setParams(array_merge(self::$params, $params));
    }

    /* Returns the currently specified layout */
    static public function getLayout()
    {
      return self::$layout;
    }

    /*
      Sets the specified layout as the default when rendering templates.
      If 'null' is given, the value of the config variable 'view.default_layout'
      will be used. If 'false' is provided, no layout will be used by default.
    */
    static public function setLayout($layout)
    {
      self::$layout = $layout;
    }

    /*
      Renders the specified template and then decorates it with the specified
      layout, or the default one if that argument is not provided, passing in
      the specified parameters and any defined global parameters to both the
      template and the layout. If 'null' is provided for the layout, the value
      of the config variable 'view.default_layout' will be used. If 'false' is
      given or the default is 'false', no layout decoration will be performed.
      The result of the rendering is provided as the return value, in contrast
      to View::render, where the result is immediately sent to the browser.
    */
    static public function getContent($file, $params = array(), $layout = null)
    {
      /* If the default layout is 'null', use the one from the configuration */
      if(is_null(self::$layout))
      {
        self::$layout = Config::get("view.default_layout");
      }

      /* If no layout is provided, set it to the default layout */
      if(is_null($layout))
      {
        $layout = self::$layout;
      }

      /* Find out which class to use for rendering templates */
      $class = Config::get("view.template_class");

      /*
        Instantiate the configured template class using the provided template
        path and an array containing both global and local template parameters.
      */
      $template = new $class($file, array_merge(self::$params, $params));

      /* Perform the rendering and retrieve the result */
      $content = $template->getContent();

      if($layout === false)
      {
        /* No layout decoration if the configured layout is 'false' */
        return $content;
      }
      else
      {
        /*
          Otherwise, we need to render the layout as well. First, we retrieve
          all parameters from the template object. We need to do this because
          the template parameters might have been changed during the rendering
          of the template, and we need to pass an up-to-date list to the layout.
          This lets you pass values (such as the page title) from your
          templates to the layout.
        */
        $params = $template->getParams();

        /*
          Create a new instance of the configured template class for the layout
          and pass in the parameters we got out of the rendered template,
          together with the result of the rendering in the 'content' variable.
        */
        $layout = new $class($layout, array_merge($params, array("content" => $content)));

        /* Render the layout and return the result */
        return $layout->getContent();
      }
    }

    /*
      Renders the specified template and then decorates it with the specified
      layout, or the default one if that argument is not provided, passing in
      the specified parameters and any defined global parameters to both the
      template and the layout. If 'null' is provided for the layout, the value
      of the config variable 'view.default_layout' will be used. If 'false' is
      given or the default is 'false', no layout decoration will be performed.
      The result of the rendering is printed to the output and subsequently
      sent down to the browser, in contrast to View::getContent, where it is
      provided as the return value of the function.
    */
    static public function render($file, $params = array(), $layout = null)
    {
      print self::getContent($file, $params, $layout);
    }
  }
