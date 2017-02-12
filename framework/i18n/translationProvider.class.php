<?php

  abstract class TranslationProvider
  {
    protected static $translations = array();

    static public function getTranslation($id, $language)
    {
      if(!isset(self::$translations[$language]))
      {
        self::$translations[$language] = self::loadLanguage($language);
      }
      return Fset::get(self::$translations[$language], $id);
    }

    static public function loadLanguage($language)
    {
      $content = include(Application::root() . "/translations/" . $language . ".php");
      if(!is_array($content))
      {
        throw new Exception("Translations file for language '" . $language . "' must return an array.");
      }
      return $content;
    }
  }
