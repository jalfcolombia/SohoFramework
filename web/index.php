<?php

$GLOBALS['timeIni'] = microtime(true);

require_once '../config/projectConfiguration.class.php';
require_once '../lib/vendor/soho/helper/initHelper.php';

use soho\projectConfiguration;

try {
  projectConfiguration::getInstance()->autoLoad();
  soho\shDispatch::getInstance()->main();
} catch (ErrorException $exc) {
  require '../lib/vendor/soho/view/warning/warning.html.php';
}

//$GLOBALS['timeIni'] = microtime(true);
//session_name('mvcSite');
//session_start();
//ob_start();
//
//if (is_file('../config/config.php') !== true) {
//  include_once '../lib/installer/installerClass.php';
//  $installer = new installerClass();
//  $installer->install();
//} else {
//  include_once __DIR__ . '/../lib/vendor/soho/autoLoadClass.php';
//  mvc\autoload\autoLoadClass::getInstance()->autoLoad();
//  mvc\dispatch\dispatchClass::getInstance()->main();
//}