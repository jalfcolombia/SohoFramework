<?php

try {
  $argv = $GLOBALS['argv'];
  if (isset($argv[1]) === false) {
    throw new Exception(colorize(
            "\n+--------------------------------------------------------+\n"
            . "| ERROR!!!                                               |\n"
            . "+--------------------------------------------------------+\n"
            . "| Debes de pasar una tarea a ejecutar [directriz:accion] |\n"
            . "| Ejemplo: modelo:generar                                |\n"
            . "+--------------------------------------------------------+\n"
            . "\n\n", 'FAILURE'
    ));
  }
  $data = explode(':', $argv[1]);
  $task = 'lib/vendor/soho/task/tasks/' . $data[0] . '/' . $data[1] . 'Task.php';
  if (is_file($task) === false) {
    throw new Exception(colorize(
            "\n+--------------------------------------------------------+\n"
            . "| ADVERTENCIA!!!                                         |\n"
            . "+--------------------------------------------------------+\n"
            . "| La tarea a ejecutar no existe                          |\n"
            . "+--------------------------------------------------------+\n"
            . "\n\n", 'WARNING'
    ));
  }
  require_once $task;
} catch (Exception $exc) {
  echo $exc->getMessage();
}
