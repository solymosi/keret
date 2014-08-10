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
				self::$uri = '/' . ltrim(isset($_SERVER["PATH_INFO"]) ? $_SERVER["PATH_INFO"] : (isset($_SERVER["ORIG_PATH_INFO"]) ? $_SERVER["ORIG_PATH_INFO"] : ""), '/');
			}
			return self::$uri;
		}
		
		public static function getMethod()
		{
			return strtolower($_SERVER["REQUEST_METHOD"]);
		}
		
		public static function isMethod($method)
		{
			return strtolower($method) == self::getMethod();
		}
		
		public static function link($uri)
		{
			return self::getBaseUri() . Helpers::h($uri);
		}
		
		public static function redirect($uri, $permanent = false)
		{
			self::externalRedirect(self::link($uri), $permanent);
		}
		
		public static function returnsJavascript()
		{
			header("Content-Type: text/javascript");
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
		
		private static $sending = false;
		
		public static function sendMail($to, $subject, $body, $contentType = "text/plain", $additionalHeaders = "")
		{
			if(!self::$sending)
			{
				self::$sending = true;
				mail($to, "=?UTF-8?B?" . base64_encode($subject) . "?=", $body, "MIME-Version: 1.0\r\nContent-Type: " . $contentType . "; charset=UTF-8\r\nFrom: " . MAIL_FROM . "\r\n" . $additionalHeaders);
				self::$sending = false;
			}
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
			return ASSETS_URL . "/" . Helpers::h($name);
		}
		
		public static function h($content)
		{
			return htmlentities($content, ENT_QUOTES, "UTF-8");
		}
		
		public static function clearOutput()
		{
			if(@ob_get_level())
			{
				while(@ob_end_clean());
			}
			
			ob_start();
		}
		
		public static function ensureCorrectBaseUri()
		{
			if(!preg_match("/^.*\/index\.php$/", self::getBaseUri()))
			{
				self::externalRedirect(self::getBaseUri() . "/index.php" . self::getUri(), true);
			}
		}
		
		public static function isAssoc($array)
		{
			return count(array_filter(array_keys($array), "is_string")) > 0;
		}
		
		public static function s($what, $from, $default = null)
		{
			return isset($from[$what]) ? $from[$what] : $default;
		}
		
		public static function plural($num, $zero, $one, $more)
		{
			return $num == 0 ? $zero : ($num == 1 ? $one : $more);
		}
		
		public static function truncateText($input, $length, $ellipses = true)
		{
			return mb_strlen($input) <= $length ? $input : (mb_substr($input, 0, mb_strrpos(mb_substr($input, 0, $length), " ")) . ($ellipses ? "..." : ""));
		}
		
		public static function getFileExtension($fileName)
		{
			preg_match("/^.+\.([^.]+)$/", $fileName, $matches);
			return count($matches) > 0 ? $matches[1] : null;
		}
		
		public static function randomToken() 
		{
			return sha1(microtime(true) . mt_rand(10000, 90000));
		}
		
	}
	
	class NotFoundException extends Exception { }

?>