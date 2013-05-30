<?php

	class DB
	{

		// Az aktív adatbáziskapcsolat
		public static $link;

		// Kapcsolódik az adatbázishoz és beállítja az UTF-8-as karakterkódolást
		public static function connect()
		{
			if(!self::$link = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD))
			{
				throw new Exception("MySQL error: " . self::lastError());
			}
			if(!@mysql_select_db(DB_DATABASE, self::$link))
			{
				throw new Exception("MySQL error: " . self::lastError());
			}
			DB::query("set names utf8");
		}
		
		// Előkészít egy paraméterezett lekérdezést a futtatáshoz (escapeli a megadott paramétereket és beszúrja őket a megfelelő helyre)
		public static function prepare($sql, $params = array())
		{
			$params = array_merge(array($sql), array_map(array("self", "escape"), $params));
			return call_user_func_array("sprintf", $params);
		}
		
		// Escapeli a megadott sztringet
		public static function escape($text)
		{
			return mysql_real_escape_string($text);
		}
		
		// Idézőjelek közé veszi és escapeli a megadott sztringet
		public static function quote($text)
		{
			return "'" . DB::escape($text) . "'";
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
				throw new Exception("MySQL error: " . self::lastError() . ". The query was: " . $sql);
			}
			return $result;
		}
		
		// Lekéri a megadott lekérdezési eredményben lévő sorok számát
		public static function rows($result)
		{
			return @mysql_num_rows($result);
		}
		
		// Visszaadja a soron következő sort a lekérdezés eredményében, vagy false-t, ha nincs több
		public static function fetch($result)
		{
			return @mysql_fetch_assoc($result);
		}
		
		// Visszaadja az összes sort a lekérdezés eredményéből egy tömbben
		public static function fetchAll($result)
		{
			$rows = array();
			while($row = self::fetch($result))
			{
				$rows[] = $row;
			}
			return $rows;
		}
		
		// Futtatja a megadott lekérdezést és visszaadja az első sort (DB::query és DB::fetch együtt)
		public static function get()
		{
			$fga = func_get_args();
			return self::fetch(call_user_func_array(array("self", "query"), $fga));
		}
		
		// Futtatja a megadott lekérdezést és visszaadja az összes sort (DB::query és DB::fetchAll együtt)
		public static function getAll()
		{
			$fga = func_get_args();
			return self::fetchAll(call_user_func_array(array("self", "query"), $fga));
		}
		
		public static function buildAssignment($data, $filter = null)
		{
			if(!is_null($filter))
			{
				$data = array_intersect_key($data, array_flip($filter));
			}
			
			$parts = array();
			foreach($data as $field => $value)
			{
				$parts[] = $field . " = " . (is_null($value) ? "null" : "'" . str_replace("%", "%%", self::escape($value)) . "'");
			}
			
			return implode(", ", $parts);
		}
		
		// Visszaadja a legutóbb beszúrt sor ID-jét
		public static function lastID()
		{
			$row = self::get("select last_insert_id() as id");
			return $row["id"];
		}
		
		// Visszaadja a legutóbbi MySQL hibaüzenetet
		public static function lastError()
		{
			return @mysql_error();
		}

	}

?>