<?php

	abstract class LocaleProvider
	{
		static public function getLocales()
		{
			return Config::get("i18n.locales");
		}
		
		static public function getDefaultLocale()
		{
			return Config::get("i18n.default_locale");
		}
	}