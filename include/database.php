<?php

	class DB
	{
		public static $link;

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
			DB::query("set time_zone = '%s'", date_default_timezone_get());
		}
		
		public static function prepare($sql, $params = array())
		{
			$params = array_merge(array($sql), array_map(array("self", "escape"), $params));
			return call_user_func_array("sprintf", $params);
		}
		
		public static function escape($text)
		{
			return mysql_real_escape_string($text);
		}
		
		public static function quote($text)
		{
			return "'" . DB::escape($text) . "'";
		}
		
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
		
		public static function rows($result)
		{
			return @mysql_num_rows($result);
		}
		
		public static function fetch($result)
		{
			return @mysql_fetch_assoc($result);
		}
		
		public static function fetchAll($result)
		{
			$rows = array();
			while($row = self::fetch($result))
			{
				$rows[] = $row;
			}
			return $rows;
		}
		
		public static function get()
		{
			$fga = func_get_args();
			return self::fetch(call_user_func_array(array("self", "query"), $fga));
		}
		
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
		
		public static function lastID()
		{
			$row = self::get("select last_insert_id() as id");
			return $row["id"];
		}
		
		public static function lastError()
		{
			return @mysql_error();
		}
	}

?>