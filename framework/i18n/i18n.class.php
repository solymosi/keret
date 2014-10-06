<?php

	abstract class I18n
	{
		static protected $currentLocale;
		static protected $defaultLocale;
		static protected $locales = array();
		
		static public function initialize()
		{
			$provider = Config::get("i18n.locale_provider_class");
			foreach($provider::getLocales() as $locale)
			{
				self::$locales[$locale] = new LocaleInstance($locale);
			}
			$default = $provider::getDefaultLocale();
			if(!in_array($default, array_keys(self::$locales)))
			{
				throw new Exception("Default locale '" . $default . "' does not exist.");
			}
			self::$defaultLocale = self::$locales[$default];
			self::setLocale(self::$defaultLocale);
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
			if($locale instanceof LocaleInstance)
			{
				$locale = $locale->getCode();
			}
			if(!is_string($locale))
			{
				throw new Exception("Locale code must be a string.");
			}
			if(!array_key_exists($locale, self::locales()))
			{
				throw new Exception("Locale '" . $locale . "' is not supported.");
			}
			self::$currentLocale = self::$locales[$locale];
		}
		
		static public function defaultLocale()
		{
			return self::$defaultLocale;
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
		
		static public function detectBrowserLocale()
		{
			$languages = array();
			if(isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
			{
				$regex = "/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i";
				preg_match_all($regex, $_SERVER['HTTP_ACCEPT_LANGUAGE'], $results);
				
				for($i = 0; $i < count($results[1]); $i++)
				{
					$code = strtolower($results[1][$i]);
					$code = str_replace("-", "_", $code);
					$factor = $results[4][$i] === "" ? 1.0 : (float)$results[4][$i];
					$languages[$code] = $factor;
				}
				
				arsort($languages, SORT_NUMERIC);
			}
			
			$match = null;
			foreach(array_keys($languages) as $language)
			{
				$parts = explode("_", $language);
				foreach(self::locales() as $code => $locale)
				{
					if(strtolower($code) == $language)
					{
						return $locale;
					}
					elseif(is_null($match) && strpos(strtolower($code), $parts[0]) === 0)
					{
						$match = $locale;
					}
				}
			}
			
			return is_null($match) ? self::defaultLocale() : $match;
		}
		
		static public function setBrowserLocale()
		{
			$locale = self::detectBrowserLocale();
			self::setLocale($locale);
		}
	}