<?php

	class Helpers
	{
		// Gyorsítótárazott elemek
		public static $baseUri = null;
		public static $scheme = null;
		public static $uri = null;
		
		// Lekéri a front controller (index.php) URL címét (ezt fel lehet használni URL-generáláskor)
		// Példa: https://apro.kozgaz.net/index.php
		// Forrás: Slim PHP5 framework
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
		
		// Lekéri az aktuális protokollt (http vs https)
		// Forrás: Slim PHP5 framework
		public static function getScheme( $reload = false )
		{
			if($reload || is_null(self::$scheme))
			{
				self::$scheme = (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') ? 'http' : 'https';
			}
			return self::$scheme;
		}
		
		// Lekéri az index.php után lévő részt
		// Példa: https://apro.kozgaz.net/index.php/login  =>  /login
		// Forrás: Slim PHP5 framework
		public static function getUri( $reload = false )
		{
			if ($reload || is_null(self::$uri))
			{
				self::$uri = '/' . ltrim(isset($_SERVER["PATH_INFO"]) ? $_SERVER["PATH_INFO"] : "", '/');
			}
			return self::$uri;
		}
		
		// Lekéri az aktuális oldallekérés method-ját
		// Lehetséges értékek: GET, POST, PUT, HEAD, DELETE, PATCH, stb.
		public static function getMethod()
		{
			return strtolower($_SERVER["REQUEST_METHOD"]);
		}
		
		// Igazat ad vissza, ha az aktuális oldallekérés method-ja megegyezik a megadottal
		public static function isMethod($method)
		{
			return strtolower($method) == self::getMethod();
		}
		
		// A megadott rendszeren belüli URI-t abszolút linkké konvertálja
		// Példa: /login  =>  https://apro.kozgaz.net/index.php/login
		public static function link($uri)
		{
			return self::getBaseUri() . $uri;
		}
		
		// Átirányítja a user böngészőjét egy rendszeren belüli URI-ra
		public static function redirect($uri, $permanent = false)
		{
			self::externalRedirect(self::link($uri), $permanent);
		}
		
		// Átirányítja a user böngészőjét egy tetszőleges URL-re, és megszakítja a program futását
		public static function externalRedirect($url, $permanent = false)
		{
			Helpers::clearOutput();
			Helpers::setStatusCode($permanent ? "301 Moved Permanently" : "302 Found");
			header("Location: " . $url);
			print('You are being redirected <a href="' . $url . '">here</a>.');
			ob_end_flush();
			exit;
		}
		
		// Végtelen ciklus megakadályozása az exception értesítéseknél, ha az exception az üzenet küldése közben keletkezett
		private static $sending = false;
		
		public static function sendMail($to, $subject, $body, $additionalHeaders = "")
		{
			if(!self::$sending)
			{
				self::$sending = true;
				mail($to, "=?UTF-8?B?" . base64_encode($subject) . "?=", $body, "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\nFrom: " . MAIL_FROM . "\r\n" . $additionalHeaders);
				self::$sending = false;
			}
		}
		
		// Megjeleníti a 404-es hibaüzenetet, és leállítja a program futását
		public static function notFound($message = "The requested resource was not found")
		{
			throw new NotFoundException($message);
		}
		
		// Beállítja a megadott HTTP állapotüzenetet a böngészőnek küldendő válaszban
		public static function setStatusCode($code)
		{
			header($_SERVER["SERVER_PROTOCOL"] . " " . $code);
		}
		
		// Visszaadja a megadott asset (kép, css, js) fájl abszolút URL-jét
		// Példa: img/logo.png  =>  https://apro.kozgaz.net/assets/img/logo.png
		public static function asset($name)
		{
			return ASSETS_URL . "/" . $name;
		}
		
		// Escapeli a megadott szövegben lévő HTML-specifikus karaktereket (XSS védelem)
		// MINDEN FELHASZNÁLÓTÓL JÖVŐ, MAJD KIPRINTELT ADATNAK ÁT KELL MENNIE EZEN KÖZVETLENÜL A PRINTELÉS ELŐTT!
		// Példa: <script>alert('XSS FAIL')</script>  =>  &lt;script&gt;alert('XSS FAIL')&lt;/script&gt;
		public static function h($content)
		{
			return htmlentities($content, ENT_QUOTES, "UTF-8");
		}
		
		// Töröl minden eddig kiprintelt tartalmat a kimeneti gyorsítótárból
		public static function clearOutput()
		{
			// Az összes gyorsítótár-szint tartalmát töröljük
			if(@ob_get_level())
			{
				while(@ob_end_clean());
			}
			
			// Újraindítjuk a gyorsítótárazást az üres gyorsítótárral
			ob_start();
		}
		
		// Átirányítja a böngészőt az index.php-ra, ha az valami miatt nem szerepel az URL-ben (ez azért kell, mert a .htaccess csak akkor engedélyezi a lekérést, ha az index.php-val kezdődik)
		public static function ensureCorrectBaseUri()
		{
			if(!preg_match("/^.*\/index\.php$/", self::getBaseUri()))
			{
				self::externalRedirect(self::getBaseUri() . "/index.php" . self::getUri(), true);
			}
		}
		
		public static function getFileExtension($fileName)
		{
			preg_match("/^.*\.([a-zA-Z0-9]+)$/", $fileName, $matches);
			return count($matches) > 0 ? $matches[1] : null;
		}
		
		public static function randomToken() 
		{
			return sha1(microtime(true) . mt_rand(10000, 90000));
		}
		
	}
	
	class NotFoundException extends Exception { }

?>