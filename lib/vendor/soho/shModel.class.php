<?php

namespace soho;

use soho\shConfig as config;
use soho\shSession as session;

/**
 * Description of modelClass
 *
 * @author Julian Lasso <ingeniero.julianlasso@gmail.com>
 */
class shModel {

  static private $instance = null;

  /**
   * el constructor es privado por lo que nadie puede crear
   * una nueva instancia utilizando new
   */
  private function __construct() {
    
  }

  /**
   * Al igual que el constructor, hacemos __clone privada
   * para que nadie pueda clonar la instancia
   */
  private function __clone() {
    
  }

  /**
   *
   * @return \PDO
   */
  static protected function getInstance() {
    if (!self::$instance) {
      self::connect();
    }
    if (session::getInstance()->hasAttribute('mvcDbQuery')) {
      session::getInstance()->setAttribute('mvcDbQuery', session::getInstance()->getAttribute('mvcDbQuery') + 1);
    } else {
      session::getInstance()->setAttribute('mvcDbQuery', 1);
    }
    return self::$instance;
  }

  static protected function getConnection() {
    try {
      // conexión a la DB
      self::$instance = new \PDO(config::getDbDsn(), config::getDbUser(), config::getDbPassword());
      // PDO::ATTR_ERRMODE: Reporte de errores
      // PDO::ERRMODE_EXCEPTION: Lanza exceptions.
      self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $exc) {
      throw $exc;
    }
  }

}
