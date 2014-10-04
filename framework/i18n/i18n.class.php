<?php

	abstract class I18n
	{
		static protected $currentLocale;
		static protected $locales = array();
		
		static public function initialize()
		{
			foreach(Config::get("i18n.locales") as $locale)
			{
				self::$locales[$locale] = new LocaleInstance($locale);
			}
			self::setLocale(Config::get("i18n.default_locale"));
		}
		
		static public function locale()
		{
			return self::$currentLocale;
		}
		
		static public function locales()
		{
			return self::$locales;
		}
		
		static public function setLocale($locale)
		{
			if(!array_key_exists($locale, self::locales()))
			{
				throw new Exception("Locale '" . $locale . "' is not supported.");
			}
			self::$currentLocale = self::$locales[$locale];
		}
		
		static public function languages()
		{
			$languages = array();
			foreach(self::locales() as $locale)
			{
				$languages[] = $locale->getPrimaryLanguage();
			}
			return array_unique($languages);
		}
		
		static public function regions()
		{
			$regions = array();
			foreach(self::locales() as $locale)
			{
				$regions[] = $locale->getRegion();
			}
			return array_unique($regions);
		}
	}