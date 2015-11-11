<?php

namespace soho;

use soho\interfaces\shI18n as i18nInterface;
use soho\shConfig as config;
use soho\shCacheManager as cacheManager;

/**
 * Description of i18nClass
 *
 * @author julianlasso
 */
class shI18n implements i18nInterface {

  protected static $culture;

  /**
   * 
   * @param integer $message
   * @param string $culture [optional]
   * @param string $dictionary [optional]
   * @param array $vars [optional] $vars[':nombre'] = 'NOMBRE';
   * @return string
   */
  public static function __($message, $culture = null, $dictionary = 'default', $vars = array()) {
    if ($culture === null) {
      $culture = self::getCulture();
    }
    $__ymlCulture = cacheManager::getInstance()->loadYaml(config::getPathAbsolute() . 'i18n/' . $culture . '.yml', 'i18nYaml'. $culture);
    $rsp = '';
    if (count($vars) > 0) {
      $keys = array_keys($vars);
      $values = array_values($vars);
      $rsp = str_replace($keys, $values, $__ymlCulture['dictionary'][$dictionary][$message]);
    } else {
      $rsp = $__ymlCulture['dictionary'][$dictionary][$message];
    }
    return $rsp;
  }

  public static function getCulture() {
    return self::$culture;
  }

  public static function setCulture($culture) {
    self::$culture = $culture;
  }

}
