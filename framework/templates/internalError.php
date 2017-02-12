<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Server Error</title>
    <style type="text/css">
      body  { font-family: arial, helvetica, sans-serif; }
      h1    { color: #D00; font-size: 1.5em;             }
      small { color: #666; font-size: 0.7em              }
    </style>
  </head>

  <body>

    <h1>Houston, we have a problem!</h1>

    <p><strong>An unexpected error has occured while loading this page.</strong></p>
    <p>We have been notified about the error and will attempt to fix it as soon as possible.<br />Please return to the <a href="javascript:window.history.back()">previous page</a>.</p>
    <p><strong>We are very sorry for the trouble.</strong></p>

    <?php if(ini_get("display_errors") == true): ?>
      <p>
        <small>
          <strong><?= html($e->getMessage()) ?></strong><br />
          <?= nl2br(html($e->getTraceAsString())) ?>
        </small>
      </p>

      <!--
        <?php foreach(DB::$preparedQueries as $query): ?>

          <?= $query ?>

        <?php endforeach; ?>
      -->
    <?php endif; ?>

  </body>
</html>
