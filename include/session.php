<?php

	class Session
	{
		protected static $flash = array();
		
		// Megváltoztatjuk a PHP session-beállításait, és elindítjuk a session-kezelőt
		public static function initialize()
		{
			ini_set("session.name", SESSION_COOKIE_NAME);
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
		
		// Lekéri a megadott session-bejegyzést. Kényelmi funkció azzal az extrával, hogy nem dob notice-t, ha a key nem létezik.
		public static function get($key)
		{
			return self::has($key) ? $_SESSION[$key] : null;
		}
		
		// Beállítja a megadott session-bejegyzést. Kényelmi funkció.
		public static function set($key, $value)
		{
			$_SESSION[$key] = $value;
			return true;
		}
		
		// Törli a megadott session-bejegyzést
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
		
		public static function setFlash($key, $value)
		{
			$_SESSION["flash"][$key] = $value;
			return true;
		}
		
		public static function deleteFlash($key)
		{
			unset($_SESSION["flash"][$key]);
			return true;
		}
		
		// Törli az összes session-bejegyzést
		public static function reset()
		{
			$_SESSION = array();
		}
		
		public static function regenerate()
		{
			session_regenerate_id();
			self::generateCSRFToken();
		}
		
		// Generál egy CSRF tokent, és eltárolja a session-ben
		public static function generateCSRFToken()
		{
			self::set("csrf_token", sha1(uniqid(srand(), true)));
		}
		
		// Összeveti az eltárolt CSRF tokent a megadottal, és exception-t dob, ha nem egyezik
		public static function verifyCSRFToken($token)
		{
			if(self::CSRFToken() !== $token)
			{
				throw new Exception("Invalid CSRF token");
			}
		}
		
		// Visszaadja az eltárolt CSRF tokent, ha van
		public static function CSRFToken()
		{
			return self::get("csrf_token");
		}
	}

?>