<?php

use soho\interfaces\shControllerAction as controllerActionInterface;
use soho\shController as controller;
use soho\myConfig as config;
use soho\shRequest as request;
use soho\shRouting as routing;
use soho\shSession as session;
use hook\log\logHookClass as log;
use hook\security\securityHook as security;
use soho\shI18n as i18n;

/**
 * Description of logoutActionClass
 *
 * @author Julian Lasso <ingeniero.julianlasso@gmail.com>
 */
class logoutActionClass extends controller implements controllerActionInterface {

  public function execute() {
    try {
      log::register('salida del sistema', 'NINGUNA', null, null, session::getInstance()->getUserId());
      session::getInstance()->setUserAuthenticate(false);
      session::getInstance()->setUserId(null);
      session::getInstance()->setUserName(null);
      session::getInstance()->deleteCredentials();
      if (request::getInstance()->hasCookie(config::getCookieNameRememberMe()) === true) {
        recordarMeTableClass::deleteSession(request::getInstance()->getCookie(config::getCookieNameRememberMe()), request::getInstance()->getServer('REMOTE_ADDR'));
        setcookie(config::getCookieNameRememberMe(), '', time() - config::getCookieTime(), config::getCookiePath());
      }
      routing::getInstance()->redirect(config::getDefaultModule(), config::getDefaultAction());
    } catch (PDOException $exc) {
      session::getInstance()->setFlash('exc', $exc);
      routing::getInstance()->forward('shfSecurity', 'exception');
    }
  }

}
