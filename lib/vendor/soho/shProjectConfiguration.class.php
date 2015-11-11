<?php

namespace soho;

/**
 * The current soho version.
 */
define('SOHO_VERSION', '1.0.0');

/**
 * Description of shProjectConfiguration
 *
 * @author julianlasso
 */
class shProjectConfiguration {

  static protected $instance = null;
  protected $baseDir;

  protected function __construct() {
    $this->baseDir = realpath(dirname(__FILE__) . '/..');
  }

  /**
   *
   * @return shProjectConfiguration
   */
  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * 
   */
  public function autoLoad() {
    $classes = array(
        '/soho/interface/shController.interface.php',
        '/soho/interface/shControllerAction.interface.php',
        '/soho/interface/shHook.interface.php',
        '/soho/interface/shI18n.interface.php',
        '/soho/interface/shRequest.interface.php',
        '/soho/interface/shRouting.interface.php',
        '/soho/interface/shSession.interface.php',
        '/soho/interface/tableInterface.php',
        '/soho/shCacheManager.class.php',
        '/soho/shCamelCase.class.php',
        '/soho/shComponent.class.php',
        '/soho/shController.class.php',
        '/soho/shConfig.class.php',
        '/../../config/myConfig.class.php',
        '/../../config/config.php',
        '/soho/shDispatch.class.php',
        '/soho/shHook.class.php',
        '/soho/shI18n.class.php',
        '/soho/shModel.class.php',
        '/soho/shRequest.class.php',
        '/soho/shRouting.class.php',
        '/soho/shSession.class.php',
        '/soho/shValidator.class.php',
        '/soho/shView.class.php',
        '/soho/tableBaseClass.php',
        '/yaml/sfYaml.php'
    );
    foreach ($classes as $class) {
      require_once $this->baseDir . $class;
    }
  }

}
