<?php

	class DB
	{
		/*
			This is the database module of the Keret framework. It currently only
			supports MySQL connections and requires the 'php_pdo' and 'php_pdo_mysql'
			PHP extensions to be enabled. There is built-in support for preventing
			SQL injection vulnerabilities using prepared queries, but that requires
			you to issue queries containing parameters in a certain way.
			
			Connecting to the database
				The framework does this automatically for you and it also makes sure
				that the correct character set (UTF-8) is used for the connection.
			
			Retrieving a single row from the database
				Instead of manually issuing your query and then fetching the first row,
				you can use the DB::get method to do this in one go:
				DB::get("select * from products p limit 1");  // get the first product
			
			Retrieving multiple rows from the database
				Same deal as with DB::get, but here you get back all rows in an array:
				DB::getAll("select * from users");            // get all users
			
			Retrieving a single value from the database
				Here you get back the value in the first column of the first row:
				DB::getValue("select count(*) from products") // get product count
			
			Performing other kinds of queries
				You can execute insert, update and delete queries using DB::query:
				DB::query("delete from users")                // delete all users
			
			Passing parameters into queries in a safe way
				You surely know that passing potentially unsafe values (that come from
				the user for example) into queries by simple concatenation is not safe
				and should always be avoided (if you didn't know this, please do not
				write a single line of code until you read more about SQL injection).
				This class provides an easy way to pass potentially dangerous
				parameters into your query in a safe manner. Just substitute your
				query parameters with placeholders (like ':user_id'), and provide the
				actual values in an array as the second parameter. This works for
				every query method provided by this class:
				DB::get(
					"select * from users where id = :user_id",  // the query
					array("user_id" => $some_id)                // query parameters
				);
				DB::getAll(
					"select * from posts where title = :title and author = :author",
					array("title" => $params["title"], "author" => $params["author"])
				);
				DB::getValue(
					"select count(*) from posts where author = :some_guy",
					array("some_guy" => $user["id"])
				);
				DB::query(
					"update users set name = :u_name where id = :u_id",
					array("u_id" => $id, "u_name" => $new_name)
				);
			
			This is all you need to know to get started. Read the individual comments
			below to learn about a few additional features this class provides.
		*/
		
		/* Contains the database link object returned by PDO */
		public static $link;
		
		/*
			Holds a history of all queries executed during this request. The queries
			and their parameter arrays are stored in their raw form.
		*/
		public static $queries = array();
		
		/*
			Holds a history of all queries executed during this request, but here
			the parameter values are substituted into the query, resulting in a
			query history that is better suited for debugging purposes.
		*/
		public static $preparedQueries = array();

		/* Connects to the database and configures the PDO client */
		public static function connect()
		{
			self::$link = new PDO(
				/* Connection string explicitly specifying UTF-8 encoding */
				"mysql:host=" . Config::get("database.host") . ";dbname=" . Config::get("database.name") . ";charset=utf8",
				/* Database credentials */
				Config::get("database.user"),
				Config::get("database.password"),
				array(
					/* Use "real" prepared statements instead of emulated ones */
					PDO::ATTR_EMULATE_PREPARES => false,
					/* Always throw exceptions instead of errors */
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				)
			);
		}
		
		/* Prepares a parameterized query for execution */
		public static function prepare($sql, $params = array())
		{
			/* Make sure that the parameter array is an actual array */
			if(!is_array($params))
			{
				throw new Exception("Query parameters must be passed in an array.");
			}
			
			/* Call PDO::prepare to get a statement object for the query */
			$stmt = self::$link->prepare($sql);
			
			/* Bind the parameter values to the statement object */
			foreach($params as $name => $value)
			{
				/*
					We only bind parameters for which there is a placeholder in the query.
					This is necessary because PDO freaks out if there are bound parameter
					values without corresponding placeholders, and many app developers
					would appreciate the added flexibility of being able to pass in extra
					parameters without triggering an exception.
				*/
				if(mb_strpos($sql, ":" . $name) !== false)
				{
					$stmt->bindValue(":" . $name, $value, self::paramType($value));
				}
			}
			
			/* Return the prepared query statement */
			return $stmt;
		}
		
		/* Returns the PDO parameter type to use for a value, based on its type */
		protected static function paramType($param)
		{
			/* Decide based on the type of the parameter value */
			switch(strtolower(gettype($param)))
			{
				/* Treat booleans, integers and null values separately */
				case "boolean": return PDO::PARAM_BOOL;
				case "integer": return PDO::PARAM_INT;
				case "null": return PDO::PARAM_NULL;
				/* Every other type is considered a string parameter */
				default: return PDO::PARAM_STR;
			}
		}
		
		/* Prepares and executes a query with the specified parameters */
		public static function query($sql, $params = array())
		{
			/* Add this query to the raw query history */
			self::$queries[] = array(
				"sql" => $sql,
				"params" => $params
			);
			
			/* Add it to the prepared query history as well, after a 'fake' preparation */
			self::$preparedQueries[] = array_reduce(array_keys($params), function($str, $param) use ($params) {
				return str_replace(":" . $param, self::quote($params[$param]), $str);
			}, $sql);
			
			/* Perform the actual preparation and retrieve the query statement */
			$stmt = self::prepare($sql, $params);
			
			/* Execute the query */
			$stmt->execute();
			
			/* Return the statement object */
			return $stmt;
		}
		
		/* Fetches the next row from the result as an associative array */
		public static function fetch($stmt)
		{
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		
		/* Fetches all rows from the result as an associative array */
		public static function fetchAll($stmt)
		{
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		/*
			Fetches the value in the specified column of the next row. The value
			in the first column is returned by default.
		*/
		public static function fetchValue($stmt, $column = 0)
		{
			return $stmt->fetchColumn($column);
		}
		
		/* Executes the specified query and returns the first row of the result */
		public static function get($sql, $params = array())
		{
			return self::fetch(self::query($sql, $params));
		}
		
		/* Executes the specified query and returns all rows of the result */
		public static function getAll($sql, $params = array())
		{
			return self::fetchAll(self::query($sql, $params));
		}
		
		/*
			Executes the provided query and returns the value in the specified column
			of the first row in the result. The first column is returned by default.
		*/
		public static function getValue($sql, $params = array(), $column = 0)
		{
			return self::fetchValue(self::query($sql, $params), $column);
		}
		
		/*
			Manually quotes sensitive characters in the specified string. You should
			not need to use this method unless you are preparing queries on your own.
		*/
		public static function quote($str)
		{
			return self::$link->quote($str);
		}
		
		/*
			Builds a comma-separated assignment list for use in 'insert' or 'update'
			queries, based on the keys of the specified associative array. Only the
			keys of the array will be used, the array values are discarded.
			An optional second parameter lets you pass in a list of allowed keys,
			which will be used to filter the key list of the first array.
			
			Example without filter
				DB::buildAssignment(
					array("name" => "Joe", "age" => 30, "title" => "General Manager")
				)
				Returns: name = :name, age = :age, title = :title
			
			Example with allowed keys filter
				DB::buildAssignment(
					array("name" => "Joe", "age" => 30, "title" => "General Manager"),
					array("name", "title")
				)
				Returns: name = :name, title = :title
			
			This function can be especially useful if you have form input values in
			an associative array and you want to insert all of them into an 'insert'
			or 'update' query. For example:
			
			$values = $form->getValues();  // returns an associative array
			DB::query(
				"insert into users set " . DB::buildAssignment($values),
				$values  // same array is used as the query parameters array
			);
			
			This will execute the following query:
			insert into users set name = :name, age = :age, title = :title
			Using the same $values array as the query parameters array provides the
			necessary values for the generated placeholders.
		*/
		public static function buildAssignment($data, $filter = null)
		{
			/* If we have a filter, remove items whose key is not on the whitelist */
			if(!is_null($filter))
			{
				$data = array_intersect_key($data, array_flip($filter));
			}
			
			/* Fill up a new array with the individual assignment strings */
			$parts = array();
			foreach($data as $field => $value)
			{
				$parts[] = $field . " = :" . $field;
			}
			
			/* Concatenate the assignments using commas and return the result */
			return implode(", ", $parts);
		}
		
		/* Returns the auto-generated ID of the most recently inserted row */
		public static function lastId()
		{
			return self::$link->lastInsertId();
		}
		
		/* Begins a database transaction */
		public static function beginTransaction()
		{
			self::$link->beginTransaction();
		}
		
		/* Saves changes made during the currently active transaction */
		public static function commit()
		{
			self::$link->commit();
		}
		
		/* Discards any changes made during the currently active transaction */
		public static function rollback()
		{
			self::$link->rollBack();
		}
		
		/*
			Begins a transaction, executes the specified callback function and then
			commits the transaction. If an exception is raised during the execution
			of the callback function, the transaction is rolled back instead.
			This function automates the use of the previous three and lets you
			conveniently wrap multiple database queries into a single transaction,
			especially if you use an anonymous callback function, as in this example:
			
			DB::transaction(function() {
				// Insert user (1st query)
				DB::query("insert into users set name = :name", array("name" => "Joe"));
				// Get ID of inserted user
				$id = DB::lastId();
				// Create profile record for the user (2nd query)
				DB::query("insert into profiles set user_id = :id", array("id" => $id));
			});
		*/
		public static function transaction($callback)
		{
			/* Begin the transaction */
			self::beginTransaction();
			
			try
			{
				/* Execute the callback function */
				call_user_func($callback);
				
				/* There were no exceptions, so let's commit the transaction */
				self::commit();
			}
			catch(Exception $e)
			{
				/* Roll back the transaction if an error occurs */
				self::rollback();
				
				/* Rethrow the exception because we don't want to swallow it */
				throw $e;
			}
		}
	}

?>