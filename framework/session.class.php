<?php

	class Session
	{
		protected static $flash = array();
		
		public static function initialize()
		{
			ini_set("session.name", Config::get("session.cookie_name"));
			ini_set("session.save_path", Application::root() . "/sessions");
			ini_set("session.cookie_lifetime", Config::get("session.cookie_expires"));
			ini_set("session.gc_maxlifetime", Config::get("session.cookie_expires") ? Config::get("session.cookie_expires") : 24 * 3600);
			ini_set("session.cookie_domain", Config::get("session.cookie_domain"));
			ini_set("session.cookie_secure", Config::get("session.cookie_secure"));
			ini_set("session.cookie_httponly", true);
			
			session_start();
			
			if(self::has("flash"))
			{
				self::$flash = self::get("flash");
			}
			
			self::set("flash", array());
			
			if(is_null(self::csrfToken()))
			{
				self::generateCsrfToken();
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
		}
		
		public static function delete($key)
		{
			unset($_SESSION[$key]);
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
			self::generateCsrfToken();
		}
		
		public static function generateCsrfToken()
		{
			self::set("csrf_token", sha1(uniqid(srand(), true)));
		}
		
		public static function verifyCsrfToken($token)
		{
			if(self::csrfToken() !== $token)
			{
				throw new CsrfException("Invalid CSRF token.");
			}
		}
		
		public static function csrfToken()
		{
			return self::get("csrf_token");
		}
	}
	
	class CsrfException extends Exception { }

?>