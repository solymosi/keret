<?php

  class Autoload
  {
    /* Holds the paths registered for autoloading */
    static protected $paths = array();

    /* Initializes autoloading by registering the autoloader function */
    static public function initialize()
    {
      spl_autoload_register("self::load");
    }

    /* Registers a path for autoloading by adding it to the path list */
    static public function registerPath($path)
    {
      self::$paths[] = $path;
    }

    /*
      Callback function for the autoloader which loads the specified class
      This function is called automatically by PHP every time it encounters
      a class which is not yet loaded. It goes through all registered paths
      in the order of their registration and checks whether a file exists there
      with the specified class name. If it does, that file is included.
    */
    static public function load($class)
    {
      foreach(self::$paths as $path)
      {
        /*
          We check for two different file names: one where only the first
          letter of the class name is turned into lowercase and another where
          the entire class name is lowercased. This is because there are some
          classes (such as 'DB') where lowercasing only the first letter
          would not result in the correct file name ('dB.class.php' would be
          incorrect, 'db.class.php' is the actual file name).
        */
        foreach(array(lcfirst($class), strtolower($class), $class) as $current)
        {
          /* Build the file path to check */
          $file = $path . "/" . $current . ".class.php";

          /* Include it and exit if a file exists at that location */
          if(is_file($file))
          {
            require $file;
            return;
          }
        }
      }
    }
  }
