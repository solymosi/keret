<?php

  class Helpers
  {
    /*
      This file contains generic helper methods for use in the framework and
      also in your application if you need them. If you need to add your custom
      helper methods, put those into the 'Misc' class in the 'lib' folder of
      your application instead of this class.
    */

    /* These variables cache the result of the corresponding functions */
    public static $baseUri = null;
    public static $scheme = null;
    public static $uri = null;

    /*
      Returns the base URL of the application (or the URL of the front controller)
      Example:  http://example.org/one/two  =>  http://example.org
    */
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

    /* Returns the server protocol (HTTP or HTTPS) */
    public static function getScheme( $reload = false )
    {
      if($reload || is_null(self::$scheme))
      {
        self::$scheme = (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') ? 'http' : 'https';
      }
      return self::$scheme;
    }

    /*
      Returns the URI of the current page, i.e. everything after the base URL
      Example:  http://example.org/one/two  =>  /one/two
    */
    public static function getUri( $reload = false )
    {
      if ($reload || is_null(self::$uri))
      {
        self::$uri = '/' . ltrim(isset($_SERVER["PATH_INFO"]) ? $_SERVER["PATH_INFO"] : (isset($_SERVER["ORIG_PATH_INFO"]) ? $_SERVER["ORIG_PATH_INFO"] : ""), '/');
      }
      return self::$uri;
    }

    /* Returns the current HTTP method (GET, POST, etc.) */
    public static function getMethod()
    {
      return strtolower($_SERVER["REQUEST_METHOD"]);
    }

    /* Returns true if the current HTTP method equals the specified method */
    public static function isMethod($method)
    {
      return strtolower($method) == self::getMethod();
    }

    /*
      Assembles an absolute URL from the specified internal URI and an optional
      array of parameters for the query string.

      Example:  Helpers::link("/one/two", array("hello" => "world"))
      Returns:  http://example.org/one/two?hello=world
    */
    public static function link($uri, $query = array())
    {
      return
        self::getBaseUri() .
        self::escapeHtml($uri) .
        (count($query) > 0 ?
          "?" . implode("&",
            array_map(function($key) use ($query) {
              return $key . "=" . urlencode($query[$key]);
            }, array_keys($query))
          ) :
          ""
        );
    }

    /*
      Redirects the browser of the user to a URL generated using the specified
      internal URI and an optional array of query parameters. By default, a
      temporary (302) redirect is issued; to use a permanent (301) redirect,
      set the third parameter to true.

      Example:       Helpers::redirect("/one/two", array("hello" => "world"))
      Redirects to:  http://example.org/one/two?hello=world
    */
    public static function redirect($uri, $query = array(), $permanent = false)
    {
      self::externalRedirect(self::link($uri, $query), $permanent);
    }

    /*
      Redirects the browser to the specified URL and terminates the execution
      of the application. By default, a temporary (302) redirect is issued;
      to use a permanent (301) redirect, set the second parameter to true.
    */
    public static function externalRedirect($url, $permanent = false)
    {
      /* Clear the output buffer */
      Helpers::clearOutput();

      /* Set the proper status code */
      Helpers::setStatusCode($permanent ? "301 Moved Permanently" : "302 Found");

      /* Set the location header (this will instruct the browser to redirect) */
      header("Location: " . $url);

      /* Just to be on the safe side, include a message in the response as well */
      print('You are being redirected <a href="' . $url . '">here</a>.');

      /* Send the output buffer contents to the browser and exit */
      ob_end_flush();
      exit;
    }

    /* Signals the browser that we are returning JavaScript content */
    public static function returnsJavascript()
    {
      /* We don't need to use a layout when rendering a JS template */
      View::setLayout(false);

      /* Set the proper Content-Type header */
      header("Content-Type: text/javascript");
    }

    /* Signals the browser that we are returning JSON content */
    public static function returnsJson()
    {
      /* Let's disable the layout just in case */
      View::setLayout(false);

      /* Set the proper Content-Type header */
      header("Content-Type: application/json");
    }

    /* Returns true if this is an ajax request */
    public static function isAjaxRequest()
    {
      return !empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest";
    }

    /*
      Sends an email from the default 'from' address, paying extra attention to
      using UTF-8 encoding both in the subject line and in the message body.
    */
    public static function sendMail($to, $subject, $body, $additionalHeaders = array())
    {
      /* Create an array with the default headers */
      $headers = array(
        "MIME-Version" => "1.0",
        "Content-Type" => Config::get("mail.content_type"),
        "From" => Config::get("mail.default_from"),
      );

      /* Send the email using the built-in 'mail' function */
      mail($to, "=?UTF-8?B?" . base64_encode($subject) . "?=", $body, self::buildHeaders(array_merge($headers, $additionalHeaders)));
    }

    /* Turns an array of email headers into a header string */
    public static function buildHeaders($headers)
    {
      $parts = array();
      foreach($headers as $name => $value)
      {
        $parts[] = $name . ": " . $value;
      }
      return implode("\r\n", $parts);
    }

    /* Throws a not found exception with the specified message */
    public static function notFound($message = "The requested resource was not found")
    {
      throw new NotFoundException($message);
    }

    /* Sets the status code in the response */
    public static function setStatusCode($code)
    {
      header($_SERVER["SERVER_PROTOCOL"] . " " . $code);
    }

    /*
      Returns the full URL for the specified asset path
      Example:  Helpers::asset("images/logo.png")
      Returns:  http://example.org/assets/images/logo.png
    */
    public static function asset($name)
    {
      return Config::get("assets.url_prefix") . "/" . Helpers::escapeHtml($name);
    }

    /*
      Escapes an HTML value to prevent XSS vulnerabilities. Always use this
      function when you are displaying potentially unsafe values (i.e. values
      that come from the user or their browser) in HTML templates.

      Example:  Helpers::escapeHtml("<script> alert('Hacked!'); </script>")
      Returns:  &lt;script&gt; alert('Hacked!'); &lt;/script&gt;

      Since all "special" HTML characters (such as '<' and '>') are substituted
      with the corresponding entities ('&lt;' and '&gt;'), the browser will
      display the '<' and '>' characters to the user (which is harmless),
      instead of treating them as HTML code and executing the script.

      The 'html' function is a shorthand for this one, for use in templates:
      <p><?= html($unsafe_value) ?></p>
    */
    public static function escapeHtml($content)
    {
      return htmlentities($content, ENT_QUOTES, "UTF-8");
    }

    /*
      Escapes an JavaScript value to prevent XSS vulnerabilities. Always use
      this function when you are inserting potentially unsafe values (i.e.
      values that come from the user or their browser) into JavaScript code.

      The 'js' function is a shorthand for this one, for use in templates:
      var name = '<?= js($unsafe_value) ?>';
    */
    public static function escapeJs($content)
    {
      /* Require a string value because json_encode could fail otherwise */
      if(!is_string($content))
      {
        throw new Exception("The provided value is not a string.");
      }

      /*
        The main purpose of the json_encode function is not the escaping of
        JavaScript strings, but we can use it for this purpose as well.
        Unfortunately, the escaped string is wrapped in single quotes ('),
        which we need to explicitly remove before returning the result.
      */
      $result = json_encode($content);
      return mb_substr($result, 1, mb_strlen($result) - 2);
    }

    /* Clears all content from the output buffers */
    public static function clearOutput()
    {
      /* Clear all levels of output buffering */
      if(@ob_get_level())
      {
        while(@ob_end_clean());
      }

      /* Restart output buffering */
      ob_start();
    }

    /*
      Returns whether the parameter is an associative array. Arrays are
      considered associative unless their items have numeric keys in ascending
      order, starting at zero, and without any gaps.
    */
    public static function isAssoc($array)
    {
      return is_array($array) && array_keys($array) !== range(0, count($array) - 1);
    }

    /*
      Returns a value from an array with the specified key ($what), or a
      default value if no value exists with that key. If you do not provide
      a default value, null will be used as the default. This method can save
      you from having to perform 'isset' checks before fetching array values
      that might not exist.
    */
    public static function select($what, $from, $default = null)
    {
      return isset($from[$what]) ? $from[$what] : $default;
    }

    /*
      Turns a regular array of arrays into an associative array using the
      specified subkey. For example, this is useful if you have a regular array
      of database rows and you want to turn that into an associative array
      where the keys are the database IDs of their respective rows.
    */
    public static function toAssociative($array, $key, $removeKey = false)
    {
      $result = array();
      foreach($array as $item)
      {
        $k = $item[$key];
        if($removeKey)
        {
          unset($item[$key]);
        }
        $result[$k] = $item;
      }
      return $result;
    }

    /*
      Flattens a multi-dimensional array into a one-dimensional array. Uses a
      custom method for merging arrays instead of array_merge because of the
      differences in what array_merge considers as a numeric array and what
      the correct behavior should be in most cases.
    */
    public static function flatten($array)
    {
      $mergers = array(
        "value" => function($array, $key, $value) {
          $array[$key] = $value;
          return $array;
        },
        "numeric" => function($array, $key, $value) {
          return array_reduce($value, function($result, $item) {
            $result[] = $item;
            return $result;
          }, $array);
        },
        "associative" => function($array, $key, $value) {
          return array_reduce(array_keys($value), function($result, $key) use($value) {
            $result[$key] = $value[$key];
            return $result;
          }, $array);
        },
      );

      return array_reduce(array_keys($array), function($result, $key) use($array, $mergers) {
        $value = $array[$key];
        return call_user_func($mergers[
          is_array($value) ? (self::isAssoc($value) ? "associative" : "numeric") : "value"
        ], $result, $key, $value);
      }, array());
    }

    /*
      Returns one of the specified parameters (one, more or none) based on the
      plurality of the provided number. Examples for pluralization:

      Simple pluralization
        You have <?= $count ?> unread <?= pluralize($count, "email", "emails") ?>

      Separate 'zero' value
        <?= pluralize($ct, $ct . " message", $ct . " messages", "No messages") ?>
    */
    public static function pluralize($count, $one, $more, $none = null)
    {
      /* If no separate 'zero' value is specified, use the 'plural' value */
      if(is_null($none))
      {
        $none = $more;
      }
      /* Zero value */
      if($count == 0)
      {
        return $none;
      }
      /* Singular value */
      if($count == 1)
      {
        return $one;
      }
      /* Plural value */
      return $more;
    }

    /*
      Interpolates the provided parameters into the specified text, using
      placeholders in the following format: #{name}

      Example:  Helpers::interpolate("Welcome, #{name}!", array("name" => "Joe"))
      Returns:  Welcome, Joe!

      Placeholders without corresponding values in the parameters array will
      not be replaced but left in the string as they are.
    */
    public static function interpolate($text, $params)
    {
      /* Define arrays for the search patterns and the replacement values */
      $find = $replace = array();

      /* Add a search pattern and the replacement value for each parameter */
      foreach($params as $name => $value)
      {
        $find[] = "/#{" . str_replace(".", "\.", $name) . "}/i";
        $replace[] = $value;
      }

      /* Perform a regex replace using the two arrays we built */
      return preg_replace($find, $replace, $text);
    }

    /* Truncates the specified text on a word boundary using ellipses (...) */
    public static function truncateText($input, $length, $ellipses = true)
    {
      return mb_strlen($input) <= $length ? $input : (mb_substr($input, 0, mb_strrpos(mb_substr($input, 0, $length), " ")) . ($ellipses ? "..." : ""));
    }

    /* Returns the extension part of the specified file name */
    public static function getFileExtension($fileName)
    {
      preg_match("/^.+\.([^.]+)$/", $fileName, $matches);
      return count($matches) > 0 ? $matches[1] : null;
    }

    /* Generates a random SHA-1 token */
    public static function randomToken()
    {
      return sha1(microtime(true) . mt_rand(10000, 90000));
    }

    /*
      Throws an exception if the specified expression is truthy. This lets you
      perform various validations in one line instead of having to write an
      'if' statement and manually throw an exception if the validation fails:
      Helpers::when(is_null($value), "The specified value cannot be null.");
    */
    static public function when($expression, $message)
    {
      if($expression)
      {
        throw new Exception($message);
      }
    }

    /*
      Throws an exception if the specified expression is falsy:
      Helpers::whenNot(is_array($value), "An array is required.");
    */
    static public function whenNot($expression, $message)
    {
      self::when(!$expression, $message);
    }

    /* Returns the provided value if it is truthy, otherwise returns null */
    static public function nullify($value, $trim_strings = true)
    {
      $value = $trim_strings && is_string($value) ? trim($value) : $value;
      return $value ? $value : null;
    }
  }

  /* Exception class for 'page not found' errors */
  class NotFoundException extends Exception { }

  /* Shorthand for Helpers::link */
  function link_to($uri, $query = array())
  {
    return Helpers::link($uri, $query);
  }

  /* Shorthand for Helpers::asset */
  function asset($name)
  {
    return Helpers::asset($name);
  }

  /* Shorthand for I18n::translate */
  function __($id, $params = array(), $language = null)
  {
    return I18n::translate($id, $params, $language);
  }

  /* Shorthand for Helpers::escapeHtml */
  function html($content)
  {
    return Helpers::escapeHtml($content);
  }

  /* Shorthand for Helpers::escapeJs */
  function js($content)
  {
    return Helpers::escapeJs($content);
  }

  /* Shorthand for Helpers::select */
  function select($what, $from, $default = null)
  {
    return Helpers::select($what, $from, $default);
  }

  /* Shorthand for Helpers::pluralize */
  function pluralize($count, $one, $more, $none = null)
  {
    return Helpers::pluralize($count, $one, $more, $none);
  }
