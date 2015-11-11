<?php

use soho\interfaces\shControllerAction as controllerActionInterface;
use soho\shController as controller;
use soho\myConfig as config;
use soho\shRequest as request;
use soho\shRouting as routing;
use soho\shSession as session;
use soho\shI18n as i18n;

/**
 * Description of indexActionClass
 *
 * @author Julian Lasso <ingeniero.julianlasso@gmail.com>
 */
class indexActionClass extends controller implements controllerActionInterface {

  public function execute() {
    try {
      if (session::getInstance()->isUserAuthenticated()) {
        routing::getInstance()->redirect(config::getDefaultModule(), config::getDefaultAction());
      } else {
        $this->defineView('loginForm', 'shfSecurity', session::getInstance()->getFormatOutput());
      }
    } catch (PDOException $exc) {
      session::getInstance()->setFlash('exc', $exc);
      routing::getInstance()->forward('shfSecurity', 'exception');
    }
  }

}
