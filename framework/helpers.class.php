<?php

	class Helpers
	{
		public static $baseUri = null;
		public static $scheme = null;
		public static $uri = null;

		// Get front controller URL
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
		
		// Get the server protocol (HTTP or HTTPS)
		public static function getScheme( $reload = false )
		{
			if($reload || is_null(self::$scheme))
			{
				self::$scheme = (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') ? 'http' : 'https';
			}
			return self::$scheme;
		}
		
		// Get the URI of the current page, e.g. everything that is after the front controller URL
		public static function getUri( $reload = false )
		{
			if ($reload || is_null(self::$uri))
			{
				self::$uri = '/' . ltrim(isset($_SERVER["PATH_INFO"]) ? $_SERVER["PATH_INFO"] : (isset($_SERVER["ORIG_PATH_INFO"]) ? $_SERVER["ORIG_PATH_INFO"] : ""), '/');
			}
			return self::$uri;
		}
		
		// Get current HTTP method (GET, POST, etc.)
		public static function getMethod()
		{
			return strtolower($_SERVER["REQUEST_METHOD"]);
		}
		
		// Check the current HTTP method
		public static function isMethod($method)
		{
			return strtolower($method) == self::getMethod();
		}
		
		// Convert an internal URI to an absolute URL for links
		public static function link($uri)
		{
			return self::getBaseUri() . Helpers::escapeHtml($uri);
		}
		
		// Redirect the browser to a URI within this site
		public static function redirect($uri, $permanent = false)
		{
			self::externalRedirect(self::link($uri), $permanent);
		}
		
		// Signal the browser that we are returning JavaScript content
		public static function returnsJavascript()
		{
			View::setLayout(false);
			header("Content-Type: text/javascript");
		}
		
		// Check whether this is an ajax request
		public static function isAjaxRequest()
		{
			return !empty($_SERVER["HTTP_X_REQUESTED_WITH"])&& strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest";
		}
		
		// Redirect the browser to a URL and terminate
		public static function externalRedirect($url, $permanent = false)
		{
			Helpers::clearOutput();
			Helpers::setStatusCode($permanent ? "301 Moved Permanently" : "302 Found");
			header("Location: " . $url);
			print('You are being redirected <a href="' . $url . '">here</a>.');
			ob_end_flush();
			exit;
		}
		
		// Send an email from the default address
		public static function sendMail($to, $subject, $body, $additionalHeaders = array())
		{
			$headers = array(
				"MIME-Version" => "1.0",
				"Content-Type" => "text/plain; charset=UTF-8",
				"From" => Config::get("mail.default_from"),
			);
			
			mail($to, "=?UTF-8?B?" . base64_encode($subject) . "?=", $body, self::buildHeaders(array_merge($headers, $additionalHeaders)));
		}
		
		// Assemble header string from array
		public static function buildHeaders($headers)
		{
			$parts = array();
			foreach($headers as $name => $value)
			{
				$parts[] = $name . ": " . $value;
			}
			return implode("\r\n", $parts);
		}
		
		// Throw a not found exception
		public static function notFound($message = "The requested resource was not found")
		{
			throw new NotFoundException($message);
		}
		
		// Set the status code in the response
		public static function setStatusCode($code)
		{
			header($_SERVER["SERVER_PROTOCOL"] . " " . $code);
		}
		
		// Returns the full URL for an asset
		public static function asset($name)
		{
			return Config::get("assets.url_prefix") . "/" . Helpers::escapeHtml($name);
		}
		
		// Escape an HTML value to prevent XSS vulnerabilities
		public static function escapeHtml($content)
		{
			return htmlentities($content, ENT_QUOTES, "UTF-8");
		}
		
		// Escape a JS value to prevent XSS vulnerabilities
		public static function escapeJs($content)
		{
			if(!is_string($content))
			{
				throw new Exception("The provided value is not a string.");
			}
			$result = json_encode($content);
			return mb_strpos($result, 1, mb_strlen($result) - 2);
		}
		
		// Clears all content from the output buffers and restarts output buffering
		public static function clearOutput()
		{
			if(@ob_get_level())
			{
				while(@ob_end_clean());
			}
			ob_start();
		}
		
		// Returns whether the parameter is an associative array
		public static function isAssoc($array)
		{
			return is_array($array) && count(array_filter(array_keys($array), "is_string")) > 0;
		}
		
		// Returns a value from an array, or a default value if it does not exist
		public static function select($what, $from, $default = null)
		{
			return isset($from[$what]) ? $from[$what] : $default;
		}
		
		// Truncate text with ellipses
		public static function truncateText($input, $length, $ellipses = true)
		{
			return mb_strlen($input) <= $length ? $input : (mb_substr($input, 0, mb_strrpos(mb_substr($input, 0, $length), " ")) . ($ellipses ? "..." : ""));
		}
		
		// Return the extension of a file
		public static function getFileExtension($fileName)
		{
			preg_match("/^.+\.([^.]+)$/", $fileName, $matches);
			return count($matches) > 0 ? $matches[1] : null;
		}
		
		// Generate a random SHA-1 token
		public static function randomToken() 
		{
			return sha1(microtime(true) . mt_rand(10000, 90000));
		}
	}
	
	class NotFoundException extends Exception { }
	
	function link_to($uri)
	{
		return Helpers::link($uri);
	}
	
	function asset($name)
	{
		return Helpers::asset($name);
	}
	
	function html($content)
	{
		return Helpers::escapeHtml($content);
	}
	
	function js($content)
	{
		return Helpers::escapeJs($content);
	}
	
	function select($what, $from, $default = null)
	{
		return Helpers::select($what, $from, $default);
	}

?>