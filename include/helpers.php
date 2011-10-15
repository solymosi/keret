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
				$uri = '';
				if(!empty($_SERVER['PATH_INFO']))
				{
					$uri = $_SERVER['PATH_INFO'];
				}
				else
				{
					if(isset($_SERVER['REQUEST_URI']))
					{
						$uri = parse_url(self::getScheme() . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], PHP_URL_PATH);
					}
					else if(isset($_SERVER['PHP_SELF']))
					{
						$uri = $_SERVER['PHP_SELF'];
					}
					else
					{
						throw new Exception('Unable to detect request URI');
					}
				}
				if(self::getBaseUri() !== '' && strpos($uri, self::getBaseUri()) === 0)
				{
					$uri = substr($uri, strlen(self::getBaseUri()));
				}
				self::$uri = '/' . ltrim($uri, '/');
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
			$content = self::getContent("templates/" . $module . ".php", $args);
			print self::getContent("templates/layout.php", array_merge($args, array("content" => $content)));
			exit;
		}
		
		public static function getContent($file, $args = array())
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