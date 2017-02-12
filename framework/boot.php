<?php

  /*
    This file boots the framework. We really only need to set up autoloading
    here, which will then take care of loading the rest of the framework.
  */

  /* Manually include the Autoload class */
  require(dirname(__FILE__) . "/autoload.class.php");

  /* Initialize the autoloader */
  Autoload::initialize();

  /* Register framework folders for autoloading */
  Autoload::registerPath(dirname(__FILE__));
  Autoload::registerPath(dirname(__FILE__) . "/form");
  Autoload::registerPath(dirname(__FILE__) . "/form/fields");
  Autoload::registerPath(dirname(__FILE__) . "/form/renderers");
  Autoload::registerPath(dirname(__FILE__) . "/form/validators");
  Autoload::registerPath(dirname(__FILE__) . "/i18n");
  Autoload::registerPath(dirname(__FILE__) . "/vendor");
