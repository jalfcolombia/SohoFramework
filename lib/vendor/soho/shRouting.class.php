<?php

namespace soho;

use soho\interfaces\shRouting as routingInterface;
use soho\shSession as session;
use soho\shRequest as request;
use soho\shDispatch as dispatch;
use soho\shConfig as config;
use soho\shI18n as i18n;
use soho\shCacheManager as cacheManager;

/**
 * Description of routingClass
 *
 * @author Julian Lasso <ingeniero.julianlasso@gmail.com>
 */
class shRouting implements routingInterface {

  private static $instance;

  /**
   *
   * @return routingClass
   */
  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * $module = '@default_index';
   * o
   * $module = 'default'; $action = 'index';
   *
   * @param string $module
   * @param string $action [optional]
   * @return array
   */
  public function validateRouting($module, $action = null) {
    $yamlRouting = cacheManager::getInstance()->loadYaml(config::getPathAbsolute() . 'config/routing.yml', 'routingYaml');
    if (preg_match('/^@\w+/', $module) === 1 and $action === null) {
      if (!isset($yamlRouting[substr($module, 1)])) {
        throw new \Exception('La ruta "' . $module . '" no está definida');
      } else {
        $answer = $yamlRouting[substr($module, 1)];
      }
    } else {
      $flag = true;
      foreach ($yamlRouting as $routing) {
        if ($routing['param']['module'] === $module and $routing['param']['action'] === $action) {
          $flag = false;
          $answer = $routing;
          break;
        }
      }
      if ($flag === true) {
        throw new \Exception('El módulo "' . $module . '" y acción "' . $action . '"no está definido');
      }
    }
    return $answer;
  }

  /**
   * $module = '@default_index';
   * $action = array('id' => 12);
   * o
   * $module = 'default'; $action = 'index';
   *
   * @param string $module
   * @param string|array $action [optional]
   */
  public function forward($module, $action = null) {
    if (preg_match('/^@\w+/', $module) === 1) {
      $routing = $this->validateRouting($module);
      $module = $routing['param']['module'];
      $action = $routing['param']['action'];
    } else {
      $routing = $this->validateRouting($module, $action);
    }
    dispatch::getInstance()->main($module, $action);
    exit();
  }

  public function getUrlCss($css) {
    return config::getUrlBase() . 'css/' . $css;
  }

  public function getUrlImg($image) {
    return config::getUrlBase() . 'img/' . $image;
  }

  public function getUrlJs($javascript) {
    return config::getUrlBase() . 'js/' . $javascript;
  }

  /**
   * $module = '@default_index';
   * $action = array('id' => 12);
   * o
   * $module = 'default'; $action = 'index';
   *
   * $variabls = array('id' => 12);
   * @param string $module
   * @param string|array $action [optional]
   * @param array $variables [optional]
   */
  public function getUrlWeb($module, $action = null, $variables = null) {
    if (preg_match('/^@\w+/', $module) === 1) {
      $routing = $this->validateRouting($module);
      $module = $routing['param']['module'];
      $variables = $this->genVariables($action);
      $action = $routing['param']['action'];
    } else {
      $routing = $this->validateRouting($module, $action);
    }
    return config::getUrlBase() . config::getIndexFile() . $routing['url'] . $this->genVariables($variables);
  }

  /**
   * $module = '@default_index';
   * $action = array('id' => 12);
   * o
   * $module = 'default'; $action = 'index';
   *
   * $variabls = array('id' => 12);
   * @param string $module
   * @param string|array $action [optional]
   * @param array $variables [optional]
   */
  public function redirect($module, $action = null, $variables = null) {
    if (preg_match('/^@\w+/', $module) === 1) {
      $routing = $this->validateRouting($module);
      $module = $routing['param']['module'];
      $variables = $this->genVariables($action);
      $action = $routing['param']['action'];
      header('Location: ' . $this->getUrlWeb($module, $action, $variables));
      exit();
    } else {
      header('Location: ' . $this->getUrlWeb($module, $action, $variables));
      exit();
    }
  }

  public function registerModuleAndAction($module = null, $action = null) {
    if ($module !== null and $action !== null) {
      $yamlRouting = cacheManager::getInstance()->loadYaml(config::getPathAbsolute() . 'config/routing.yml', 'routingYaml');
      $flag = false;
      foreach ($yamlRouting as $routing) {
        if ($module === $routing['param']['module'] and $action === $routing['param']['action']) {
          session::getInstance()->setModule($routing['param']['module']);
          session::getInstance()->setAction($routing['param']['action']);
          session::getInstance()->setLoadFiles(((isset($routing['load'])) ? $routing['load'] : null));
          session::getInstance()->setFormatOutput($routing['param']['format']);
          $flag = true;
          break;
        }
      }
      if ($flag === false) {
        throw new \Exception(i18n::__(00002, null, 'errors'), 00002);
      }
      return true;
    } elseif (request::getInstance()->hasServer('PATH_INFO')) {
      $data = explode('/', request::getInstance()->getServer('PATH_INFO'));
      if (($data[0] === '' and ! isset($data[1])) or ( $data[0] === '' and $data[1] === '')) {
        $this->registerDefaultModuleAndAction();
      } else {
        $url = '/^(';
        foreach ($data as $key => $value) {
          $url .= (($value === '' and $key === 0)) ? '' : $value;
          $url .= (isset($data[($key + 1)])) ? '\/' : '';
        }
        $url .= ')/';
        $yamlRouting = cacheManager::getInstance()->loadYaml(config::getPathAbsolute() . 'config/routing.yml', 'routingYaml');
        $flag = false;
        foreach ($yamlRouting as $routing) {
          if (preg_match($url, $routing['url']) === 1) {
            session::getInstance()->setModule($routing['param']['module']);
            session::getInstance()->setAction($routing['param']['action']);
            session::getInstance()->setLoadFiles(((isset($routing['load'])) ? $routing['load'] : null));
            session::getInstance()->setFormatOutput($routing['param']['format']);
            $flag = true;
            break;
          }
        }
        if ($flag === false) {
          throw new \Exception(i18n::__(00002, null, 'errors'), 00002);
        }
        return true;
      }
    } else {
      $this->registerDefaultModuleAndAction();
    }
  }

  private function registerDefaultModuleAndAction() {
    $yamlRouting = cacheManager::getInstance()->loadYaml(config::getPathAbsolute() . 'config/routing.yml', 'routingYaml');
    session::getInstance()->setModule($yamlRouting['homepage']['param']['module']);
    session::getInstance()->setAction($yamlRouting['homepage']['param']['action']);
    session::getInstance()->setLoadFiles(((isset($yamlRouting['homepage']['load'])) ? $yamlRouting['homepage']['load'] : false));
    session::getInstance()->setFormatOutput($yamlRouting['homepage']['param']['format']);
  }

  /**
   *
   * @param array $variables
   * @return boolean|string
   */
  private function genVariables($variables) {
    $answer = false;
    if (is_array($variables)) {
      $answer = '?';
      foreach ($variables as $key => $value) {
        $answer .= $key . '=' . $value . '&';
      }
      $answer = substr($answer, 0, (strlen($answer) - 1));
    } else {
      $answer = $variables;
    }
    return $answer;
  }

}
