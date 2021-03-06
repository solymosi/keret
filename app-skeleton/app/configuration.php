<?php

  /* Turn on debug mode */
  Config::set("debug", true);

  /* Title of the application */
  Config::set("app.title", "Application");

  /* Internal name of application; use only a-z, 0-9 and underscore (_) */
  Config::set("app.internal_name", "application");

  /* Domain name the application is running on */
  Config::set("app.domain", "localhost");

  /* Set default time zone */
  Config::set("default_timezone", "Europe/Zurich");

  /* Database settings */
  Config::set("database.host", "localhost");
  Config::set("database.user", "");
  Config::set("database.password", "");
  Config::set("database.name", "");

  /* Address of SMTP server for sending emails */
  Config::set("mail.smtp_server", "localhost");

  /* Email address of site administrator */
  Config::set("mail.admin_email", "Administrator <administrator@example.org>");

  /* Email content type */
  Config::set("mail.content_type", "text/html; charset=UTF-8");

  /* Use custom template class */
  Config::set("view.template_class", "CustomTemplate");

  /* Supported locales */
  Config::set("i18n.locales", array("en_US"));

  /*
    You can also add custom config variables:
    Config::set("custom.variable", "value");

    Retrieving these custom config values is done as follows:
    $value = Config::get("custom.variable");
  */
