<?php

  /* Index page */
  Routing::match("", "controller", "action");

  /*
    A few examples for routes:

    Simple paths
      Routing::match("about", "info", "about");
        This routes /about to InfoController::about.

    Multiple path segments
      Routing::match("subfolder\/page", "example", "hello");
        This routes /subfolder/page to ExampleController::hello.

    Simple regular expressions
      Routing::match("users\/[0-9]+\/details", "user", "details");
        This routes /users/102/details to UserController::details, for example.

    Capturing route parameters
      Routing::match("users\/(?P<id>[0-9]+)\/details", "user", "details");
        This does the same as above, but now the [0-9]+ expression is put in a
        named capture group: (?P<name> ... ), making the captured number
        available as a route parameter. All route parameters are passed as an
        array to the controller function as its first argument, and they are
        also retrievable using Routing::get( name ).

    Storing frequently used patterns in a variable
      $id = "(?P<id>[0-9]+)";
      Routing::match("users\/" . $id,             "user", "show");
      Routing::match("users\/" . $id . "/edit",   "user", "edit");
      Routing::match("users\/" . $id . "/delete", "user", "delete");

    Passing additional parameters to the controller
      Routing::match("posts\/trending", "post", "index", array("type" => "trending"))
      Routing::match("posts\/archived", "post", "index", array("type" => "archived"))
        Here, the controller and action are the same for both routing rules,
        so the only way for us to let the controller know whether to display
        trending or archived posts is to pass an additional parameter to it.
        These parameters are also present in the array passed to the controller
        function and are also retrievable using Routing::get( name ).

    Note that routes are evaluated in order from top to bottom, and the first
    route that matches the request URI will be executed. Therefore it is best
    to put more specific routes to the top and generic ones to the bottom.
  */
