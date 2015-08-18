<?php

	class Routing
	{
		/*
			This class is responsible for the routing of incoming requests to their
			matching controller functions. Routing rules are a series of calls to the
			Routing::match function which are listed in the 'routes.php' file of the
			application. These rules are evaluated in order from top to bottom.
			
			Each rule consists of a regex pattern (to which the internal URI in the
			request is matched), a corresponding controller and action definition
			(the former specifies the controller class, the latter the controller
			function, or the "action" in other words), and an optional set of
			parameters which are passed to the controller when the route is matched.
			Examples of routes are found in the 'routes.php' of the app skeleton.
			
			The router supports the registration of filters as well, which are
			custom functions that are executed before every controller action.
			You can use filters to implement centralized access control, for example.
		*/
		
		/* Holds all registered filter functions */
		static protected $filters = array();
		
		/* Stores the routing parameters */
		static protected $params = array();
		
		/*
			Executes the specified controller function if the provided regex pattern
			matches the current request URI, optionally passing the specified extra
			parameters to the controller as well. The controller to execute can be
			specified either by a class name (controller) and a function name (action)
			or by passing a callable as the second parameter. For example:
			
			Specifying a controller and an action
				Routing::match("about", "info", "about");
					This is the default way to specify the controller, routing the
					URL /about to InfoController::about.
			
			Passing in a function (or other callable) directly
				Routing::match("hello", function($params) {
					print("Hello World!");
				});
		*/
		static public function match($pattern, $controller, $action = null, $params = array())
		{
			/* First we make sure that an action is defined */
			if(!is_callable($controller) && is_null($action))
			{
				throw new Exception("No action has been specified.");
			}
			
			/* Add the provided parameters to the routing parameters */
			self::addParams($params);
			
			/*
				Match the provided regex pattern to the request URI after inserting
				the necessary 'beginning of string' and 'end of string' anchors.
				If there are named capture groups in the regex, their values will
				be stored in the $matches variable.
			*/
			if(preg_match("/^\/" . $pattern . "$/", Helpers::getUri(), $matches))
			{
				/*
					Save the controller and action definitions as routing parameters,
					making them accessible later by calling Routing::get("_controller").
				*/
				self::set("_controller", $controller);
				self::set("_action", $action);
				
				/* Remove empty matches and save the rest as routing parameters */
				$matches = array_filter($matches);
				self::addParams($matches);
				
				/* Execute all registered filter functions */
				foreach(self::$filters as $filter)
				{
					call_user_func($filter, self::getParams());
				}
				
				if(is_callable($controller))
				{
					/* A callable was passed in directly, so we execute it */
					call_user_func($controller, self::getParams());
				}
				else
				{
					/*
						A controller and action were passed in, so we assemble a callable
						array with the corresponding controller class and action function
						names, and then call that array, passing in the parameters.
					*/
					call_user_func(
						array(
							/* Controller class the action is located in */
							ucfirst(self::get("_controller")) . "Controller",
							/* Action function to call */
							self::get("_action")
						),
						/* Pass in all routing parameters */
						self::getParams()
					);
				}
				
				/* Throw an exception to prevent the processing of further rules */
				throw new ProcessingFinished();
			}
		}
		
		/* Registers a filter function to run before every controller action */
		static public function registerFilter($callback)
		{
			/* Make sure we were given a proper callable */
			if(!is_callable($callback))
			{
				throw new Exception("Filter method must be callable.");
			}
			
			/* Add the callable to the list of filters */
			self::$filters[] = $callback;
		}
		
		/* Returns whether a routing parameter exists under the specified name */
		static public function has($key)
		{
			return isset(self::$params[$key]);
		}
		
		/* Returns the value of the routing parameter under the specified name */
		static public function get($key)
		{
			return self::has($key) ? self::$params[$key] : null;
		}
		
		/* Sets the value of the routing parameter under the specified name */
		static public function set($key, $value)
		{
			self::$params[$key] = $value;
		}
		
		/* Returns all routing parameters in an associative array */
		static public function getParams()
		{
			return self::$params;
		}
		
		/* Replaces all existing routing parameters with the provided new ones */
		static public function setParams($params)
		{
			/* Make sure we were given an array */
			if(!is_array($params))
			{
				throw new Exception("The variable passed to setParams() must be an array");
			}
			
			/* Remove elements with numeric keys */
			$params = array_intersect_key($params, array_flip(array_filter(array_keys($params), "is_string")));
			
			/* Set the new routing parameters */
			self::$params = $params;
		}
		
		/* Adds the provided values as routing parameters */
		static public function addParams($params)
		{
			/* Make sure we were given an array */
			if(!is_array($params))
			{
				throw new Exception("The variable passed to addParams() must be an array");
			}
			
			/* Merge the provided array with the existing one, and save the result */
			self::setParams(array_merge(self::$params, $params));
		}
	}
	
	/* Exception class for signaling the end of a controller's execution */
	class ProcessingFinished extends Exception { }

?>