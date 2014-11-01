<?php

	class DB
	{
		public static $link;
		public static $queries = array();
		public static $preparedQueries = array();

		public static function connect()
		{
			self::$link = new PDO(
				"mysql:host=" . Config::get("database.host") . ";dbname=" . Config::get("database.name") . ";charset=utf8",
				Config::get("database.user"),
				Config::get("database.password"),
				array(
					PDO::ATTR_EMULATE_PREPARES => false,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				)
			);
		}
		
		public static function prepare($sql, $params = array())
		{
			if(!is_array($params))
			{
				throw new Exception("Query parameters must be passed in an array.");
			}
			
			$stmt = self::$link->prepare($sql);
			
			foreach($params as $name => $value)
			{
				if(mb_strpos($sql, ":" . $name) !== false)
				{
					$stmt->bindValue(":" . $name, $value, self::paramType($value));
				}
			}
			
			return $stmt;
		}
		
		protected static function paramType($param)
		{
			switch(strtolower(gettype($param)))
			{
				case "boolean": return PDO::PARAM_BOOL;
				case "integer": return PDO::PARAM_INT;
				case "null": return PDO::PARAM_NULL;
				default: return PDO::PARAM_STR;
			}
		}
		
		public static function query($sql, $params = array())
		{
			self::$queries[] = array(
				"sql" => $sql,
				"params" => $params
			);
			
			self::$preparedQueries[] = array_reduce(array_keys($params), function($str, $param) use ($params) {
				return str_replace(":" . $param, self::quote($params[$param]), $str);
			}, $sql);
			
			$stmt = self::prepare($sql, $params);
			$stmt->execute();
			
			return $stmt;
		}
		
		public static function fetch($stmt)
		{
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		
		public static function fetchAll($stmt)
		{
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public static function fetchValue($stmt, $column = 0)
		{
			return $stmt->fetchColumn($column);
		}
		
		public static function get($sql, $params = array())
		{
			return self::fetch(self::query($sql, $params));
		}
		
		public static function getAll($sql, $params = array())
		{
			return self::fetchAll(self::query($sql, $params));
		}
		
		public static function getValue($sql, $params = array(), $column = 0)
		{
			return self::fetchValue(self::query($sql, $params), $column);
		}
		
		public static function quote($str)
		{
			return self::$link->quote($str);
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
				$parts[] = $field . " = :" . $field;
			}
			
			return implode(", ", $parts);
		}
		
		public static function lastID()
		{
			return self::$link->lastInsertId();
		}
	}

?>