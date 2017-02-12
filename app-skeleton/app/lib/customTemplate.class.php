<?php

  class CustomTemplate extends Template
  {
    public function __construct($file, $params = array())
    {
      parent::__construct($file, $params);
    }

    /*
      Example: a function for generating the page title consisting of the
      application title and an optional custom title set in the template:
      $this->title = "Home page";

      Since this function needs access to the 'title' template parameter,
      it needs to be put in this class, instead of another such as 'Misc'.

      public function title()
      {
        if($this->title)
        {
          return $this->title . " - " . Config::get("app.title");
        }
        else
        {
          return Config::get("app.title");
        }
      }

      Accessing this function from the layout (or any other template) for
      displaying the generated title is really easy:

      <title><?= $this->title() ?></title>
    */
  }
