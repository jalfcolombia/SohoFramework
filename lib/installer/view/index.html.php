<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title></title>
  </head>
  <body>
    PHP 5.4 o superior <?php echo PHP_VERSION ?><br>
    json - <?php echo get_loaded_extensions()[array_search('json', get_loaded_extensions())] ?><br>
    PDO - <?php echo get_loaded_extensions()[array_search('PDO', get_loaded_extensions())] ?><br>
    pdo_pgsql - <?php echo get_loaded_extensions()[array_search('pdo_pgsql', get_loaded_extensions())] ?><br>
    pdo_mysql - <?php echo get_loaded_extensions()[array_search('pdo_mysql', get_loaded_extensions())] ?><br>
    <a href="index.php?step=2">Siguiente</a>
  </body>
</html>
