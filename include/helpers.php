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
		
		public static function redirect($uri)
		{
			self::externalRedirect(self::link($uri));
		}
		
		public static function externalRedirect($url)
		{
			ob_end_clean();
			header("Location: " . $url);
			print('You are being redirected <a href="' . $url . '">here</a>.');
			exit;
		}
		
		public static function asset($name)
		{
			return ASSETS_URL . "/" . $name;
		}
		
		public static function h($content)
		{
			return htmlentities($content, ENT_QUOTES, "UTF-8");
		}
		
		public static function ensureCorrectBaseUri()
		{
			if(!preg_match("/^.*\/index\.php$/", self::getBaseUri()))
			{
				header("Location: " . self::getBaseUri() . "/index.php" . self::getUri());
				exit;
			}
		}
		
		public static function render($module, $args = array())
		{
			$content = self::getContent("templates/" . $module . ".php", $args, $vars);
			print self::getContent("templates/layout.php", array_merge($args, $vars, array("content" => $content)));
		}
		
		public static function getContent($file, $args = array(), &$vars = array())
		{
			extract($args);
			ob_start();
			include($file);
			$content = ob_get_contents();
			ob_clean();
			return $content;
		}
	}

?>