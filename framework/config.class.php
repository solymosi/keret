<?php

  class Config
  {
    /*
      This nested array holds all config values of the framework and the
      application. The values of the array can be retrieved using dot notation,
      as every accessor function (has, get, set, setDefault) uses the Fset
      library to access the array.

      For example, given the following values:

      self::$values = array(
        "debug" => false,
        "app" => array(
          "title" => "Example Application",
          "internal_name" => "example-app",
        ),
        "database" => array(
          "host" => "localhost",
          "name" => "example_db"
        ),
      );

      You can access nested values using dot notation:

      Config::get("debug")          =>  false
      Config::get("app.title")      =>  Example Application
      Config::get("database.name")  =>  example_db

      The same works with has, set and setDefault as well:

      Config::has("app.title")      =>  true
      Config::set("database.name", "other_db")
    */
    static protected $values = array();

    /* Returns true if the specified config variable exists and is not null */
    static public function has($key)
    {
      /* Let's make sure we have a valid variable name */
      self::validateKey($key);

      /* Fset::get returns null if the specified variable does not exist */
      $value = Fset::get(self::$values, $key);
      return !is_null($value);
    }

    /* Returns the value of the specified config variable */
    static public function get($key)
    {
      /* If the config variable has no value, throw an exception */
      if(!self::has($key))
      {
        throw new Exception("The config key '" . $key . "' does not exist");
      }

      /* Otherwise, fetch and return the value */
      return Fset::get(self::$values, $key);
    }

    /* Returns the entire array that holds all config values */
    static public function getAll()
    {
      return self::$values;
    }

    /* Sets the value of the specified config variable */
    static public function set($key, $value)
    {
      /* First we make sure we have a valid variable name */
      self::validateKey($key);

      /* Then we set the value */
      Fset::set(self::$values, $key, $value);
    }

    /*
      Sets the value of the specified config variable if it has not been
      provided yet. If you use 'set', the new value always overrides the old
      one, but with 'setDefault', the old value, if it exists, will be kept.
    */
    static public function setDefault($key, $value)
    {
      /* If there is no such variable yet, set the value */
      if(!self::has($key))
      {
        self::set($key, $value);
      }
    }

    /* Throws an exception if the specified variable name is not valid */
    static protected function validateKey($key)
    {
      /*
        The variable name must be a string and has to consist of one or more
        groups of lowercase alphanumeric characters, separated by a dot.
      */
      if(!is_string($key) || !preg_match("/^([a-z0-9_]+\.)*[a-z0-9_]+$/", $key))
      {
        throw new Exception("Invalid configuration key");
      }
    }
  }
