<?php

use soho\interfaces\shControllerAction as controllerActionInterface;
use soho\shController as controller;
use soho\myConfig as config;
use soho\shRequest as request;
use soho\shRouting as routing;
use soho\shSession as session;
use soho\shI18n as i18n;

/**
 * Description of exceptionActionClass
 *
 * @author Julian Lasso <ingeniero.julianlasso@gmail.com>
 */
class exceptionActionClass extends controller implements controllerActionInterface {

  public function execute() {
    if (session::getInstance()->hasFlash('exc')) {
      $this->exc = session::getInstance()->getFlash('exc');
      $this->defineView('exception', 'shfSecurity', session::getInstance()->getFormatOutput());
    } else {
      routing::getInstance()->redirect(config::getDefaultModule(), config::getDefaultAction());
    }
  }

}
