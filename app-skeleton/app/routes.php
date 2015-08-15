<?php
	
	/* Index page */
	Routing::match("", "controller", "action");
	
	/*
		A few examples for routes:
		
		= Simple paths =
		Routing::match("about", "info", "about");
		This routes /about to InfoController::about.
		
		= Multiple path segments =
		Routing::match("subfolder\/page", "example", "hello");
		This routes /subfolder/page to ExampleController::hello.
		
		= Simple regular expressions =
		Routing::match("users\/[0-9]+\/details", "user", "details");
		This routes /users/102/details to UserController::details, for example.
		
		= Capture route parameters =
		Routing::match("users\/(?P<id>[0-9]+)\/details", "user", "details");
		This does the same as above, but now the [0-9]+ expression is put in a
		named capture group: (?P<name> ... ), making the captured number
		available as a route parameter. All route parameters are passed as an
		array to the controller function as its first argument, and they are
		also retrievable using Routing::get( name ).
		
		= Storing frequently used patterns in a variable =
		$id = "(?P<id>[0-9]+)";
		Routing::match("users\/" . $id,             "user", "show");
		Routing::match("users\/" . $id . "/edit",   "user", "edit");
		Routing::match("users\/" . $id . "/delete", "user", "delete");
	*/