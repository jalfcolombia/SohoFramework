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
 * Description of loginActionClass
 *
 * @author Julian Lasso <ingeniero.julianlasso@gmail.com>
 */
class loginActionClass extends controller implements controllerActionInterface {

  public function execute() {
    try {
      if (request::getInstance()->isMethod('POST')) {
        $usuario = request::getInstance()->getPost('inputUser');
        $password = request::getInstance()->getPost('inputPassword');

        if (($objUsuario = usuarioTableClass::verifyUser($usuario, $password)) !== false) {
          security::login($objUsuario);
          if (request::getInstance()->hasPost('chkRememberMe') === true) {
            $chkRememberMe = request::getInstance()->getPost('chkRememberMe');
            $hash = md5($objUsuario[0]->id_usuario . $objUsuario[0]->usuario . date(config::getFormatTimestamp()));
            $data = array(
                recordarMeTableClass::USUARIO_ID => $objUsuario[0]->id_usuario,
                recordarMeTableClass::HASH_COOKIE => $hash,
                recordarMeTableClass::IP_ADDRESS => request::getInstance()->getServer('REMOTE_ADDR'),
                recordarMeTableClass::CREATED_AT => date(config::getFormatTimestamp())
            );
            recordarMeTableClass::insert($data);
            setcookie(config::getCookieNameRememberMe(), $hash, time() + config::getCookieTime(), config::getCookiePath());
          }
          log::register('identificacion', 'NINGUNA');  
          security::redirectUrl();
        } else {
          session::getInstance()->setError('Usuario y contraseña incorrectos');
          routing::getInstance()->redirect(config::getDefaultModuleSecurity(), config::getDefaultActionSecurity());
        }
      } else {
        routing::getInstance()->redirect(config::getDefaultModule(), config::getDefaultAction());
      }
    } catch (PDOException $exc) {
      session::getInstance()->setFlash('exc', $exc);
      routing::getInstance()->forward('shfSecurity', 'exception');
    }
  }

}
