<?php

	class Session
	{
		/*
			This class implements a thin object-oriented wrapper on PHP's session
			management functions, with a few additional features built in.
			
			One of these features is the ability to set "flash" values using
			Session::setFlash, which are only persisted in the session until the
			next request. These are perfect for saving confirmation messages that
			need to be displayed on the next page ("your changes have been saved").
			
			Another feature helps in preventing CSRF vulnerabilities. When the
			session module is initialized, a unique token is persisted in the session
			under the 'csrf_token' key. In your application you should require this
			security token to be provided with every potentially damaging request
			(such as those that change something in the database), and then verify
			its correctness using the Session::verifyCsrfToken method. By default,
			forms generated using the form framework do this for you already.
		*/
		
		/*
			Contains flash values which were set in the last request and will
			disappear after the current request has been processed.
		*/
		protected static $flash = array();
		
		/* Initializes and configures the session module */
		public static function initialize()
		{
			/* Configure the session cookie settings */
			ini_set("session.name", Config::get("session.cookie_name"));
			ini_set("session.save_path", Config::get("session.save_path"));
			ini_set("session.cookie_lifetime", Config::get("session.cookie_expires"));
			ini_set("session.gc_maxlifetime", Config::get("session.cookie_expires") ? Config::get("session.cookie_expires") : 24 * 3600);
			ini_set("session.cookie_domain", Config::get("session.cookie_domain"));
			ini_set("session.cookie_secure", Config::get("session.cookie_secure"));
			ini_set("session.cookie_httponly", true);
			
			/* Start the session */
			session_start();
			
			/*
				Move flash values set in the previous request to a separate variable
				so that they are available in this request but will be gone afterwards.
			*/
			if(self::has("flash"))
			{
				self::$flash = self::get("flash");
			}
			
			/* Clear out all flash values from the session */
			self::set("flash", array());
			
			/* Generate a new CSRF token if there isn't one yet */
			if(is_null(self::csrfToken()))
			{
				self::generateCsrfToken();
			}
		}
		
		/* Returns true if the specified session variable exists and is not null */
		public static function has($key)
		{
			/* Let's make sure we have a valid variable name */
			self::validateKey($key);
			
			/* Fset::get returns null if the specified variable does not exist */
			$value = Fset::get($_SESSION, $key);
			return !is_null($value);
		}
		
		/* Returns the value of the specified session variable */
		public static function get($key)
		{
			/* Fetch and return the value of the variable */
			return Fset::get($_SESSION, $key);
		}
		
		/* Returns the contents of the entire session in an array */
		static public function getAll()
		{
			return $_SESSION;
		}
		
		/* Sets the value of the specified session variable */
		public static function set($key, $value)
		{
			/* First we make sure we have a valid variable name */
			self::validateKey($key);
			
			/* Then we set the value */
			Fset::set($_SESSION, $key, $value);
		}
		
		/* Deletes the specified session variable */
		public static function delete($key)
		{
			/* First we make sure we have a valid variable name */
			self::validateKey($key);
			
			/* Then we set the value */
			Fset::del($_SESSION, $key);
		}
		
		/* Returns true if the specified flash variable exists */
		public static function hasFlash($key)
		{
			return isset(self::$flash[$key]);
		}
		
		/* Returns the value of the specified flash variable */
		public static function getFlash($key)
		{
			return self::hasFlash($key) ? self::$flash[$key] : null;
		}
		
		/*
			Sets the value of the specified flash variable. If the 'now' argument
			is set to 'true', the flash value is made available in the current
			request; otherwise, it will be accessible during the next request.
		*/
		public static function setFlash($key, $value, $now = false)
		{
			if($now)
			{
				self::$flash[$key] = $value;
			}
			else
			{
				$_SESSION["flash"][$key] = $value;
			}
			return true;
		}
		
		/* Deletes the specified flash variable */
		public static function deleteFlash($key)
		{
			unset($_SESSION["flash"][$key]);
			return true;
		}
		
		/* Removes all session variables */
		public static function reset()
		{
			$_SESSION = array();
		}
		
		/* Regenerates the session ID and the CSRF token */
		public static function regenerate()
		{
			session_regenerate_id(true);
			self::generateCsrfToken();
		}
		
		/* Generates a new unique CSRF token and saves it into the session */
		public static function generateCsrfToken()
		{
			self::set("csrf_token", sha1(uniqid(srand(), true)));
		}
		
		/*
			Throws an exception if the specified token does not match the CSRF
			token currently stored in the session.
		*/
		public static function verifyCsrfToken($token)
		{
			if(self::csrfToken() !== $token)
			{
				throw new CsrfException("Invalid CSRF token.");
			}
		}
		
		/* Returns the CSRF token currently stored in the session */
		public static function csrfToken()
		{
			return self::get("csrf_token");
		}
		
		/* Throws an exception if the specified variable name is not valid */
		static protected function validateKey($key)
		{
			/*
				The variable name must be a string and has to consist of one or more
				groups of lowercase alphanumeric characters, separated by a dot.
			*/
			if(!is_string($key) || !preg_match("/^([a-z0-9_]+\.)*[a-z0-9_]+$/", $key))
			{
				throw new Exception("Invalid session key");
			}
		}
	}
	
	/* Exception class for CSRF token verification errors */
	class CsrfException extends Exception { }

?>