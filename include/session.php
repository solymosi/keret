<?php

	class Session
	{
		public static function initialize()
		{
			ini_set("session.name", SESSION_COOKIE_NAME);
			ini_set("session.cookie_lifetime", SESSION_COOKIE_EXPIRES);
			ini_set("session.cookie_domain", SESSION_COOKIE_DOMAIN);
			ini_set("session.cookie_httponly", true);
			ini_set("session.cookie_secure", SESSION_COOKIE_SECURE);
			session_start();
			session_regenerate_id();
			
			if(is_null(self::CSRFToken()))
			{
				self::generateCSRFToken();
			}
		}
		
		public static function get($key)
		{
			return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
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
		
		public static function reset()
		{
			$_SESSION = array();
		}
		
		public static function generateCSRFToken()
		{
			self::set("csrf_token", sha1(uniqid(srand(), true)));
		}
		
		public static function verifyCSRFToken($token)
		{
			if(self::CSRFToken() !== $token)
			{
				throw new Exception("Invalid CSRF token");
			}
		}
		
		public static function CSRFToken()
		{
			return self::get("csrf_token");
		}
	}

?>