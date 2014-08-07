<?php

	class Session
	{
		protected static $flash = array();
		
		public static function initialize()
		{
			ini_set("session.name", SESSION_COOKIE_NAME);
			
			ini_set("session.save_path", ROOT_PATH . "/session");
			ini_set("session.gc_maxlifetime", SESSION_COOKIE_EXPIRES ? SESSION_COOKIE_EXPIRES : 3600);
			
			ini_set("session.cookie_lifetime", SESSION_COOKIE_EXPIRES);
			ini_set("session.cookie_domain", SESSION_COOKIE_DOMAIN);
			ini_set("session.cookie_httponly", true);
			ini_set("session.cookie_secure", SESSION_COOKIE_SECURE);
			
			session_start();
			
			if(self::has("flash"))
			{
				self::$flash = self::get("flash");
			}
			
			self::set("flash", array());
			
			if(is_null(self::CSRFToken()))
			{
				self::generateCSRFToken();
			}
		}
		
		public static function has($key)
		{
			return isset($_SESSION[$key]);
		}
		
		public static function get($key)
		{
			return self::has($key) ? $_SESSION[$key] : null;
		}
		
		public static function set($key, $value)
		{
			$_SESSION[$key] = $value;
			return true;
		}
		
		public static function delete($key)
		{
			unset($_SESSION[$key]);
			return true;
		}
		
		public static function hasFlash($key)
		{
			return isset(self::$flash[$key]);
		}
		
		public static function getFlash($key)
		{
			return self::hasFlash($key) ? self::$flash[$key] : null;
		}
		
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
		
		public static function deleteFlash($key)
		{
			unset($_SESSION["flash"][$key]);
			return true;
		}
		
		public static function reset()
		{
			$_SESSION = array();
		}
		
		public static function regenerate()
		{
			session_regenerate_id();
			self::generateCSRFToken();
		}
		
		public static function generateCSRFToken()
		{
			self::set("csrf_token", sha1(uniqid(srand(), true)));
		}
		
		public static function verifyCSRFToken($token)
		{
			if(self::CSRFToken() !== $token)
			{
				throw new Exception("Invalid CSRF token.");
			}
		}
		
		public static function CSRFToken()
		{
			return self::get("csrf_token");
		}
	}

?>