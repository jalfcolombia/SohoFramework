<?php

use soho\interfaces\shControllerAction as controllerActionInterface;
use soho\shController as controller;
use soho\myConfig as config;
use soho\shRequest as request;
use soho\shRouting as routing;
use soho\shSession as session;
use hook\log\logHook as log;
use hook\security\securityHook as security;
use soho\shI18n as i18n;

/**
 * Description of noPermissionActionClass
 *
 * @author Julian Lasso <ingeniero.julianlasso@gmail.com>
 */
class noPermissionActionClass extends controller implements controllerActionInterface {

  public function execute() {
    try {
      $this->defineView('noPermission', 'shfSecurity', session::getInstance()->getFormatOutput());
    } catch (PDOException $exc) {
      session::getInstance()->setFlash('exc', $exc);
      routing::getInstance()->forward('shfSecurity', 'exception');
    }
  }

}
