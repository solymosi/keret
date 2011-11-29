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
		}
		
		public static function prepare($sql, $params = array())
		{
			$params = array_merge(array($sql), array_map("mysql_real_escape_string", $params));
			return call_user_func_array("sprintf", $params);
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
		
		public static function lastError()
		{
			return @mysql_error();
		}

	}

?>