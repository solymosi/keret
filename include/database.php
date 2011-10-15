<?php

	class DB {

		// Az aktív adatbáziskapcsolat
		public static $link;

		// Kapcsolódik az adatbázishoz és beállítja a karakterkódolást
		public static function connect()
		{
			if(!self::$link = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD))
			{
				throw new Exception("MySQL error: " . mysql_error());
			}
			if(!@mysql_select_db(DB_DATABASE, self::$link))
			{
				throw new Exception("MySQL error: " . mysql_error());
			}
			DB::query("set names utf8");
		}
		
		// Előkészít egy lekérdezést a futtatáshoz (escapeli a megadott paramétereket és beszúrja őket a megfelelő helyre)
		public static function prepare($sql, $params = array())
		{
			$params = array_merge(array($sql), array_map("mysql_real_escape_string", $params));
			return call_user_func_array("sprintf", $params);
		}
		
		// Futtat egy lekérdezést. Az SQL sztring után még paramétereket is meg lehet adni. Ezek automatikusan escapelve lesznek, és be lesznek szúrva az SQL sztringbe az %s-sel megjelölt helyekre.
		public static function query($sql)
		{
			$params = array();
			for($i = 1; $i < func_num_args(); $i++)
			{
				$params[] = func_get_arg($i);
			}
			$sql = self::prepare($sql, $params);
			if(!$result = @mysql_query($sql, self::$link))
			{
				throw new Exception("MySQL error: " . mysql_error() . ". The query was: " . $sql);
			}
			return $result;
		}
		
		// Lekéri egy lekérdezési eredményben a sorok számát
		public static function rows($result)
		{
			return @mysql_num_rows($result);
		}
		
		// Visszaadja a soron következő elemet a lekérdezés eredményében, vagy false-t, ha nincs több
		public static function fetch($result)
		{
			return @mysql_fetch_assoc($result);
		}
		
		// Visszaadja a legutóbbi MySQL hibaüzenetet
		public static function lastError()
		{
			return @mysql_error();
		}

	}

?>