<!DOCTYPE html>
<html lang="<?= I18n::locale()->getPrimaryLanguage() ?>">
  <head>

    <meta charset="UTF-8" />
    <meta name="description" content="<?= __("layout.meta_description") ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" media="all" href="<?= Helpers::asset("css/all.css") ?>" />
    <link rel="shortcut icon" href="<?= Helpers::asset("images/favicon.ico") ?>" />

    <script type="text/javascript" src="<?= Helpers::asset("js/all.js") ?>"></script>

    <title><?= Config::get("app.title") ?></title>

  </head>

  <body>

    <?= $this->content ?>

    <?php if(ini_get("display_errors") == true): ?>
      <!--
        <?php foreach(DB::$preparedQueries as $query): ?>

          <?= $query ?>

        <?php endforeach; ?>
      -->
    <?php endif; ?>

  </body>
</html>
