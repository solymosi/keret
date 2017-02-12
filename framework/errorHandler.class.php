<?php

  class ErrorHandler
  {
    /*
      The main purpose of this class is to convert all PHP errors, warnings
      notices and other messages to exceptions. Throwing an exception every
      time a PHP error (or warning, notice, etc.) occurs lets us handle these
      in a centralized fashion, instead of passing the job of detecting PHP
      errors and informing the user onto you, the application developer.

      In order to encourage the writing of clean and error-free code, the
      framework employs the philosophy of never skipping over PHP errors (or
      warnings, notices, etc.), no matter how insignificant they are.
      Therefore, the execution of the entire application is halted and an
      'internal error' screen is displayed to the user if even the smallest
      error occurs. This gives you the peace of mind that your application
      will never continue running in an erroneous state.

      For example, if one of your database queries fails, the execution of
      your application stops immediately, so you never have to check whether
      a query was successful or not just to prevent the continued execution
      of your app in case of an error. And if by any chance you *do* want to
      continue regardless of the error, you can always catch and handle the
      exceptions yourself (but if you do this, please be careful).

      Without converting errors to exceptions
        $result = DB::query("delete * from users");
        if($result === false) {
          die("Database query failed.");  // prevent further execution
        }
        This is time-consuming if you have lots of queries, not to mention
        that exiting the app using 'die' is terrible user experience.

      With error-to-exception conversion
        DB::query("delete * from users");  // and that's it
        No need to check for errors because they are turned into exceptions,
        which automatically halt the execution and display a nice error screen.
    */

    /* Installs the error handler that turns PHP errors into exceptions */
    static public function install()
    {
      set_error_handler("ErrorHandler::handle");
    }

    /* Callback function for PHP errors */
    static public function handle($errno, $errstr, $errfile, $errline)
    {
      /* Verify whether we should report this error at all */
      if(!(error_reporting() & $errno))
      {
        return;
      }

      /* Throw an exception with the error details */
      throw new Exception($errstr . " [" . $errfile . " @ line " . $errline . "]");
      return true;
    }

    /* Assembles and sends an exception notification email to the technical contact */
    static public function mailException($e)
    {
      Helpers::sendMail(
        /* Recipient of the mail */
        Config::get("exceptions.mail_to"),
        /* Subject line */
        "[" . Config::get("app.internal_name") . "] " . $e->getMessage(),
        /* Contents of the email */
        "An unhandled " . get_class($e) . " occured on " . date("l, j F Y H:i:s A") . ":\r\n\r\n" .
          $e->getMessage() . "\r\n" .
          "Request URI: " . $_SERVER["REQUEST_URI"] . "\r\n\r\n" .
          print_r(@$_REQUEST, true)
      );
    }
  }
