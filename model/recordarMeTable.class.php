<?php

use soho\myConfig as config;

require_once config::getPathAbsolute() . 'model/base/recordarMeBaseTable.class.php';

use soho\model\base\recordarMeBaseTable;

/**
 * Description of recordarMeTable
 *
 * @author nombre completo <su@correo.com>
 * @package soho
 * @subpackage model
 * @subpackage table
 * @version 1.0.0
 */
class recordarMeTable extends recordarMeBaseTable {

  static public function getAll() {
    $conn = self::getConnection();
    $sql = 'SELECT id, usuario_id, ip_address, hash_cookie, created_at FROM recordar_me ORDER BY created_at ASC';
    $answer = $conn->prepare($sql);
    $answer->execute();
    return ($answer->rowCount() > 0) ? $answer->fetchAll(PDO::FETCH_OBJ) : false;
  }

  static public function getById($id) {
    $conn = self::getConnection();
    $sql = 'SELECT id, usuario_id, ip_address, hash_cookie, created_at '
            . 'FROM recordar_me '
            . 'AND id = :id';
    $params = array(
        ':id' => $id
    );
    $answer = $conn->prepare($sql);
    $answer->execute($params);
    return ($answer->rowCount() > 0) ? $answer->fetchAll(PDO::FETCH_OBJ) : false;
  }

  public function save() {
    $conn = self::getConnection();
    $sql = 'INSERT INTO recordar_me (usuario_id, ip_address, hash_cookie) VALUES (:usuario_id, :ip_address, :hash_cookie)';
    $params = array(
        ':usuario_id' => $this->getUsuarioId(),
        ':ip_address' => $this->getIpAddress(),
        ':hash_cookie' => $this->getHashCookie()
    );
    $answer = $conn->prepare($sql);
    $answer->execute($params);
    return $conn->lastInsertId(self::_SEQUENCE);
  }

  public function update() {
    $conn = self::getConnection();
    $sql = 'UPDATE recordar_me SET usuario_id = :usuario_id, ip_address = :ip_address, hash_cookie = :hash_cookie WHERE id = :id';
    $params = array(
        ':usuario_id' => $this->getUsuarioId(),
        ':ip_address' => $this->getIpAddress(),
        ':hash_cookie' => $this->getHashCookie(),
        ':id' => $this->getId()
    );
    $answer = $conn->prepare($sql);
    $answer->execute($params);
    return true;
  }

  public function delete() {
    $conn = self::getConnection();
    $sql = 'DELETE FROM recordar_me WHERE id = :id';
    $params = array(
        ':id' => $this->getId()
    );
    $answer = $conn->prepare($sql);
    $answer->execute($params);
    return true;
  }

}
