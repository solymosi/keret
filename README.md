About Keret
===========

Keret is a lightweight MVC framework written in PHP. What it provides is nothing too fancy, just a few ready-made components and helper functions for building simple MVC applications. Its main features are the following:

* **Front controller**: you do not need to write your own `index.php` to handle incoming requests; it is already done for you
* **Router**: a simple router, configurable using regular expressions (which may seem very cryptic at first, but once you understand them, they become a very powerful tool for matching internal URIs)
* **Configuration engine**: every application needs configuration (if nothing else, at least the database credentials need to be stored somewhere); the framework provides a simple way to configure these and comes with a sensible default configuration out of the box
* **Autoloading**: the framework automatically loads the necessary PHP files; you should not need to use *include* or *require* anywhere
* **Database module**: a bunch of easy-to-use functions are provided for talking to the database in a secure (no SQL injection) and convenient fashion (such as retrieving multiple rows in an array with a single function call); the framework also connects to the database automatically
* **Proper error handling**: if even the smallest PHP error occurs, the execution of the application is immediately halted, a custom “sorry for the inconvenience” message is displayed to the users and a notification email with the details of the error is sent to the developer’s email address
* **Session management**: sessions are automatically configured to be secure and reliable and there is built-in support for preventing CSRF attacks
* **View layer**: supports template-layout separation and partials (reusable snippets that you can include into any template or layout), rendering a template and decorating it with a layout is done with a single function call
* **Form framework**: a sophisticated framework for creating, displaying and validating forms; it has several built-in form field types and validators but you can also add your own modules to it
* **Internationalization**: the framework supports the easy translation of applications by the creation of translation files and also has useful functions for formatting numbers and dates according to the local rules of each country
* **Helper methods**: lots of additional helper methods for common things that are useful in most web applications

Installation
============

Getting Keret up and running is really easy. Just follow these steps:

1. Create a copy of the `app-skeleton` folder somewhere. This folder contains the default folder structure of Keret applications and will serve as the project folder.
2. Initialize a new Git repository in this folder (optional).
3. If you use Git, add the Keret framework as a submodule into the `vendor/keret` folder of your newly created application: `git submodule add http://github.com/solymosi/keret.git ./vendor/keret`. Otherwise, just download the whole Keret repository manually into `vendor/keret`.

Configuration
=============

You can configure your application in `app/configuration.php`. By default, this file contains some config variables already. To see what else you can configure, check out `framework/application.class.php` in the Keret framework.

In addition to built-in config variables, you can also set your own ones using `Config::set(name, value)`, which you can subsequently retrieve from within your app using `Config::get(name)`.

Folder structure
================

* `app/autoload.php`: Use this file to register custom folders for autoloading. An example is provided in the file.
* `app/configuration.php`: As already mentioned, you should put the config variables of your app in this file.
* `app/routes.php`: This file contains the routing rules for your application. A couple of examples are provided in the file.
* `app/assets`: This folder should contain the assets (images, stylesheets, scripts, fonts, etc.) of the application. This is the only subfolder which is explicitly configured to be accessible in the `.htaccess` file, so if you put assets anywhere else, those will not be accessible from the outside by default. The `Helpers::asset` helper function also assumes the use of this folder when generating asset URLs.
* `app/controllers`: This folder holds the controller classes of your application.
* `app/lib`: If you need to create custom classes in your application, put those here. They will be autoloaded as long as their file names are set correctly (a class with the name `Example` should be in `example.class.php`).
* `app/lib/misc.class.php`: Put your custom helper methods in this class as static functions.
* `app/lib/customTemplate.class.php`: You can use this class to make custom template-related functions available in your templates. Sure, you could put them in the `Misc` class instead, but the advantage of using this class is that you can access the current template instance from within your functions (using `$this`). This lets you read and write the template variables.
* `app/models`: If you want separate model classes (usually not required for smaller apps), you can put them here. They will be autoloaded as long as their file names are set correctly (a class with the name `Example` should be in `example.class.php`).
* `app/sessions`: Your application is automatically configured to store session data in this folder.
* `app/templates`: Template and layout files should go here. They can be organized into subfolders in any way you see fit.
* `app/templates/invalidToken.php`: This template is displayed when the framework detects an invalid CSRF token.
* `app/templates/notFound.php`: This template is displayed when a "page not found" error occurs (either there are no matching routes or the error is triggered manually using `Helpers::notFound`).
* `app/templates/layout.php`: Default layout for decorating templates.
* `app/translations`: If your app is translated into multiple languages, the translation files are stored in this folder.
* `app/vendor`: Stores vendor modules used by your application.