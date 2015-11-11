<?php

function fatal_handler() {
  $errfile = "unknown file";
  $errstr = "shutdown";
  $errno = E_CORE_ERROR;
  $errline = 0;
  $error = error_get_last();
  if ($error !== NULL) {
    $errno = $error["type"];
    $errfile = $error["file"];
    $errline = $error["line"];
    $errstr = $error["message"];
    ?>
    <!DOCTYPE html>
    <html>
      <head>
        <meta charset="UTF-8">
        <title>ERROR</title>
        <link rel="stylesheet" href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>/css/soho.css">
      </head>
      <body>
        <div class="error">
          <h1><i class="shicon-bug"></i> ERROR!!!</h1>
          <div>
            <div>
              <p><b>Message:</b> <?php echo $errstr ?></p>
              <p><b>File:</b> <?php echo $errfile ?></p>
              <p><b>Line:</b> <?php echo $errline ?></p>
            </div>
          </div>
        </div>
      </body>
    </html>
    <?php
    exit();
  }
}

function sohoErrorHandler($errno, $errstr, $errfile, $errline, array $errcontext) {
  if (!(error_reporting() & $errno)) {
    return;
  }
  throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

ini_set('error_reporting', E_ALL);
ini_set('display_errors', false);
set_error_handler('sohoErrorHandler');
register_shutdown_function("fatal_handler");
