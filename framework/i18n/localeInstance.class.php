<?php

  class LocaleInstance
  {
    protected $code;

    public function __construct($code)
    {
      if(!is_string($code))
      {
        throw new Exception("Locale code must be a string.");
      }

      $this->code = $code;
    }

    public function getCode()
    {
      return $this->code;
    }

    public function __call($name, $arguments)
    {
      $arguments = array_map(function($var) {
        return $var instanceof LocaleInstance ? $var->getCode() : $var;
      }, $arguments);
      return call_user_func_array(array("Locale", $name), array_merge(array($this->code), $arguments));
    }

    /* Static members */

    static public function getLocales()
    {
      $locales = array();
      foreach(Config::get("i18n.locales") as $code)
      {
        $locales[] = new self($code);
      }
      return $locales;
    }

    static public function getDefaultLocale()
    {
      return Config::get("i18n.default_locale");
    }
  }
