<?php

	class Helpers
	{
		public static $baseUri = null;
		public static $scheme = null;
		public static $uri = null;
		
		public static function getBaseUri($reload = false)
		{
			if($reload || is_null(self::$baseUri))
			{
				$requestUri = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : $_SERVER["PHP_SELF"];
				$scriptName = $_SERVER["SCRIPT_NAME"];
				$baseUri = strpos($requestUri, $scriptName) === 0 ? $scriptName : str_replace('\\', '/', dirname($scriptName));
				self::$baseUri = self::getScheme() . '://' . $_SERVER['HTTP_HOST'] . rtrim($baseUri, "/");
			}
			return self::$baseUri;
		}
		
		public static function getScheme( $reload = false )
		{
			if($reload || is_null(self::$scheme))
			{
				self::$scheme = (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') ? 'http' : 'https';
			}
			return self::$scheme;
		}
		
		public static function getUri( $reload = false )
		{
			if ($reload || is_null(self::$uri))
			{
				self::$uri = '/' . ltrim(isset($_SERVER["PATH_INFO"]) ? $_SERVER["PATH_INFO"] : "", '/');
			}
			return self::$uri;
		}
		
		public static function link($uri)
		{
			return self::getBaseUri() . $uri;
		}
		
		public static function redirect($uri, $permanent = false)
		{
			self::externalRedirect(self::link($uri), $permanent);
		}
		
		public static function externalRedirect($url, $permanent = false)
		{
			Helpers::clearOutput();
			Helpers::setStatusCode($permanent ? "301 Moved Permanently" : "302 Found");
			header("Location: " . $url);
			print('You are being redirected <a href="' . $url . '">here</a>.');
			ob_end_flush();
			exit;
		}
		
		public static function notFound($message = "The requested resource was not found")
		{
			throw new NotFoundException($message);
		}
		
		public static function setStatusCode($code)
		{
			header($_SERVER["SERVER_PROTOCOL"] . " " . $code);
		}
		
		public static function asset($name)
		{
			return ASSETS_URL . "/" . $name;
		}
		
		public static function h($content)
		{
			return htmlentities($content, ENT_QUOTES, "UTF-8");
		}
		
		public static function clearOutput()
		{
			while(ob_get_level() > 0)
			{
				ob_end_clean();
			}
			
			ob_start();
		}
		
		public static function ensureCorrectBaseUri()
		{
			if(!preg_match("/^.*\/index\.php$/", self::getBaseUri()))
			{
				header("Location: " . self::getBaseUri() . "/index.php" . self::getUri());
				exit;
			}
		}
	}
	
	class NotFoundException extends Exception { }

?>