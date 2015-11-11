<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>WARNING</title>
    <link rel="stylesheet" href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>/css/soho.css">
  </head>
  <body>
    <div class="warning">
      <h1><i class="shicon-warning"></i> WARNING!!!</h1>
      <div>
        <div>
          <p><b>Message:</b> <?php echo $exc->getMessage() ?></p>
          <p><b>File:</b> <?php echo $exc->getFile() ?></p>
          <p><b>Line:</b> <?php echo $exc->getLine() ?></p>
          <p>
            <b>Trace:</b>
          </p>
          <?php $trace = $exc->getTrace() ?>
          <?php $trace[0] = $exc->getTrace()[0]['args'][4] ?>
          <?php if(extension_loaded('xdebug') === false): ?>
          <pre><?php print_r($trace) ?></pre>
          <?php else: ?>
          <?php var_dump($trace) ?>
          <?php endif ?>
        </div>
      </div>
    </div>
  </body>
</html>
