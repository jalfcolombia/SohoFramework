<?php

namespace soho;

use soho\shConfig as config;
use soho\shCacheManager as cacheManager;

/**
 * Description of hookClass
 *
 * @author Julian Lasso <ingeniero.julianlasso@gmail.com>
 */
class shHook {

  private static $listHooks;

  public static function hooksIni() {
    if (!self::$listHooks) {
      self::$listHooks = cacheManager::getInstance()->loadYaml(config::getPathAbsolute() . 'config/hooks.yml', 'hooksYaml');
    }
    self::loadHooksAndExecute(self::$listHooks['ini'], ((isset(self::$listHooks['configLoader'])) ? self::$listHooks['configLoader'] : null));
  }

  public static function hooksEnd() {
    if (!self::$listHooks) {
      self::$listHooks = cacheManager::getInstance()->loadYaml(config::getPathAbsolute() . 'config/hooks.yml', 'hooksYaml');
    }
    self::loadHooksAndExecute(self::$listHooks['end'], ((isset(self::$listHooks['configLoader'])) ? self::$listHooks['configLoader'] : null));
  }

  private static function loadHooksAndExecute($listHooks, $filesToLoad = null) {
    if ($filesToLoad !== null and is_array($listHooks) and count($listHooks) > 0) {
      foreach ($listHooks as $hook) {
        if (isset($filesToLoad[$hook]) and is_array($filesToLoad[$hook])) {
          foreach ($filesToLoad[$hook] as $file) {
            require_once config::getPathAbsolute() . $file;
          }
        }
        $hookFileClass = $hook . 'Hook.class';
        $hookClass = $hook . 'Hook';
        require_once config::getPathAbsolute() . 'lib/hooks/' . $hookFileClass . '.php';
        $hookObj = '\\hook\\' . $hook . '\\' . $hookClass;
        $hookObj::hook();
      }
    }
  }

}
