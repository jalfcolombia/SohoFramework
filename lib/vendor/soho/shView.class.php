<?php

namespace soho;

use soho\shConfig as config;
use soho\shSession as session;
use soho\shCacheManager as cacheManager;

/**
 * Description of viewClass - vyo͞o
 *
 * @author Julian Lasso <ingeniero.julianlasso@gmail.com>
 */
class shView {

  static public function includeHandlerMessage() {
    include_once config::getPathAbsolute() . 'libs/vendor/soho/view/handlerMessage.php';
  }

  static public function getMessageError($key) {
    include config::getPathAbsolute() . 'libs/vendor/soho/view/messageError.php';
  }

  static public function includePartial($partial, $variables = null) {
    if ($variables !== null and is_array($variables) and count($variables) > 0) {
      extract($variables);
    }
    include_once config::getPathAbsolute() . 'view/' . $partial . '.php';
  }

  static public function includeComponent($module, $component, $variables = array()) {
    include_once config::getPathAbsolute() . 'controller/' . $module . '/' . $component . 'ComponentClass.php';
    $componentClass = $component . 'ComponentClass';
    $objComponent = new $componentClass($variables);
    $objComponent->component();
    $objComponent->setArgs((array) $objComponent);
    $objComponent->renderComponent();
  }

  static public function genMetas() {
    $module = session::getInstance()->getModule();
    $action = session::getInstance()->getAction();
    $metas = '';
    $includes = cacheManager::getInstance()->loadYaml(config::getPathAbsolute() . 'config/view.yml', 'viewYaml');
    if (isset($includes['all']['meta'])) {
      foreach ($includes['all']['meta'] as $include) {
        $metas .= '<meta ' . $include . '>';
      }
    }

    if (isset($includes['all']['link'])) {
      foreach ($includes['all']['link'] as $include) {
        $metas .= '<link ' . $include . '>';
      }
    }

    if (isset($includes[$module][$action]['meta'])) {
      session::getInstance()->setFlash('meta' . $module . '.' . $action, true);
      foreach ($includes[$module][$action]['meta'] as $include) {
        if (is_array($include) === true and session::getInstance()->hasFlash('meta' . $include[0]) === false) {
          session::getInstance()->setFlash('meta' . $include[0], true);
          $entity = explode('.', $include[0]);
          $metas = self::genMetaLink($includes, $entity, $metas, 'meta');
        } else if (is_array($include) === false) {
          $metas .= '<meta ' . $include . '>';
        }
      }
    }

    if (isset($includes[$module][$action]['link'])) {
      session::getInstance()->setFlash('link' . $module . '.' . $action, true);
      foreach ($includes[$module][$action]['link'] as $include) {
        if (is_array($include) === true and session::getInstance()->hasFlash('link' . $include[0]) === false) {
          session::getInstance()->setFlash('link' . $include[0], true);
          $entity = explode('.', $include[0]);
          $metas = self::genMetaLink($includes, $entity, $metas, 'link');
        } else if (is_array($include) === false) {
          $metas .= '<link ' . $include . '>';
        }
      }
    }

    return $metas;
  }

  static private function genMetaLink($includes, $entity, $metaLink, $label) {
    foreach ($includes[$entity[0]][$entity[1]][$label] as $include) {
      if (is_array($include) === true and session::getInstance()->hasFlash($label . $include[0]) === false) {
        session::getInstance()->setFlash($label . $include[0], true);
        $entity2 = explode('.', $include[0]);
        $metaLink = self::genMetaLink($includes, $entity2, $metaLink, $label);
      } else if (is_array($include) === false) {
        $metaLink .= '<' . $label . ' ' . $include . '>';
      }
    }
    return $metaLink;
  }

  static public function genStylesheet() {
    $module = session::getInstance()->getModule();
    $action = session::getInstance()->getAction();
    $stylesheet = '';
    $includes = cacheManager::getInstance()->loadYaml(config::getPathAbsolute() . 'config/view.yml', 'viewYaml');
    foreach ($includes['all']['stylesheet'] as $include) {
      $stylesheet .= '<link rel="stylesheet" href="' . config::getUrlBase() . 'css/' . $include . '">';
    }
    if (isset($includes[$module][$action]['stylesheet'])) {
      session::getInstance()->setFlash('css' . $module . '.' . $action, true);
      foreach ($includes[$module][$action]['stylesheet'] as $include) {
        if (is_array($include) === true and session::getInstance()->hasFlash('css' . $include[0]) === false) {
          session::getInstance()->setFlash('css' . $include[0], true);
          $entity = explode('.', $include[0]);
          $stylesheet = self::genStylesheetLink($includes, $entity, $stylesheet);
        } else if (is_array($include) === false) {
          $stylesheet .= '<link rel="stylesheet" href="' . config::getUrlBase() . 'css/' . $include . '">';
        }
      }
    }
    return $stylesheet;
  }

  static private function genStylesheetLink($includes, $entity, $stylesheet) {
    foreach ($includes[$entity[0]][$entity[1]]['stylesheet'] as $include) {
      if (is_array($include) === true and session::getInstance()->hasFlash('css' . $include[0]) === false) {
        session::getInstance()->setFlash('css' . $include[0], true);
        $entity2 = explode('.', $include[0]);
        $stylesheet = self::genStylesheetLink($includes, $entity2, $stylesheet);
      } else if (is_array($include) === false) {
        $stylesheet .= '<link rel="stylesheet" href="' . config::getUrlBase() . 'css/' . $include . '">';
      }
    }
    return $stylesheet;
  }

  static public function genJavascript() {
    $module = session::getInstance()->getModule();
    $action = session::getInstance()->getAction();
    $javascript = '';
    $includes = cacheManager::getInstance()->loadYaml(config::getPathAbsolute() . 'config/view.yml', 'viewYaml');
    foreach ($includes['all']['javascript'] as $include) {
      $javascript .= '<script src="' . config::getUrlBase() . 'js/' . $include . '"></script>';
    }
    if (isset($includes[$module][$action]['javascript'])) {
      session::getInstance()->setFlash('js' . $module . '.' . $action, true);
      foreach ($includes[$module][$action]['javascript'] as $include) {
        if (is_array($include) === true and session::getInstance()->hasFlash('js' . $include[0]) === false) {
          session::getInstance()->setFlash('js' . $include[0], true);
          $entity = explode('.', $include[0]);
          $javascript = self::genJavascriptLink($includes, $entity, $javascript);
        } else if (is_array($include) === false) {
          $javascript .= '<script src="' . config::getUrlBase() . 'js/' . $include . '"></script>';
        }
      }
    }
    return $javascript;
  }

  static private function genJavascriptLink($includes, $entity, $javascript) {
    foreach ($includes[$entity[0]][$entity[1]]['javascript'] as $include) {
      if (is_array($include) === true and session::getInstance()->hasFlash('js' . $include[0]) === false) {
        session::getInstance()->setFlash('js' . $include[0], true);
        $entity2 = explode('.', $include[0]);
        $javascript = self::genJavascriptLink($includes, $entity2, $stylesheet);
      } else if (is_array($include) === false) {
        $javascript .= '<script src="' . config::getUrlBase() . 'js/' . $include . '"></script>';
      }
    }
    return $javascript;
  }

  /**
   * Funcion estatica publica que incluye un favicon en las vistas del sistema
   * @author Leonardo Betancourt Caicedo <leobetacai@gmail.com>
   * @return string
   */
  static public function genFavicon() {
    $includes = cacheManager::getInstance()->loadYaml(config::getPathAbsolute() . 'config/view.yml', 'viewYaml');
    $favicon = '<link rel="icon" href="' . config::getUrlBase() . 'img/' . $includes['all']['favicon'] . '" type="image/x-icon">';
    return $favicon;
  }

  /**
   * Funcion diseñada para integrar un titulo a cada vista de el sistema de el portal
   * @author Leonardo Betancourt Caicedo <leobetacai@gmail.com>
   * @return string
   */
  public static function genTitle() {
    $module = session::getInstance()->getModule();
    $action = session::getInstance()->getAction();
    $title = '';
    $includes = cacheManager::getInstance()->loadYaml(config::getPathAbsolute() . 'config/view.yml', 'viewYaml');
    if (isset($includes[$module][$action]['title'])) {
      $title = '<title>' . $includes[$module][$action]['title'] . '</title>';
    } else if (isset($includes['all']['title'])) {
      $title = '<title>' . $includes['all']['title'] . '</title>';
    }
    return $title;
  }

  static public function renderComponent($component, $arg = array()) {
    if (isset($component)) {
      if (count($arg) > 0) {
        extract($arg);
      }
      include config::getPathAbsolute() . "view/$component.php";
    }
  }

  static public function renderHTML($module, $template, $typeRender, $arg = array()) {
    if (isset($module) and isset($template)) {
      if (count($arg) > 0) {
        extract($arg);
      }
      switch ($typeRender) {
        case 'html':
          header(config::getHeaderHtml());
          include_once config::getPathAbsolute() . 'lib/vendor/soho/view/head.php';
          include_once config::getPathAbsolute() . "view/$module/$template.html.php";
          include_once config::getPathAbsolute() . 'lib/vendor/soho/view/foot.php';
          break;
        case 'json':
          header(config::getHeaderJson());
          include_once config::getPathAbsolute() . "view/$module/$template.json.php";
          break;
        case 'pdf':
          //header(config::getHeaderPdf());
          include_once config::getPathAbsolute() . "view/$module/$template.pdf.php";
          break;
        case 'javascript':
          header(config::getHeaderJavascript());
          include_once config::getPathAbsolute() . "view/$module/$template.js.php";
          break;
        case 'xml':
          header(config::getHeaderXml());
          include_once config::getPathAbsolute() . "view/$module/$template.xml.php";
          break;
        case 'excel2003':
          header(config::getHeaderExcel2003());
          include_once config::getPathAbsolute() . "view/$module/$template.xls.php";
          break;
        case 'excel2007':
          header(config::getHeaderExcel2007());
          include_once config::getPathAbsolute() . "view/$module/$template.xlsx.php";
          break;
      }
    }
  }

}
