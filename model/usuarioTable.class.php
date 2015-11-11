<?php

use soho\myConfig as config;

require_once config::getPathAbsolute() . 'model/base/usuarioBaseTable.class.php';

use soho\model\base\usuarioBaseTable;

/**
 * Description of usuarioTable
 *
 * @author nombre completo <su@correo.com>
 * @package soho
 * @subpackage model
 * @subpackage table
 * @version 1.0.0
 */
class usuarioTable extends usuarioBaseTable {

  static public function getAll() {
    $conn = self::getConnection();
    $sql = 'SELECT id, user_name, password, actived, last_login_at, created_at, updated_at, deleted_at FROM usuario WHERE deleted_at IS NULL ORDER BY created_at ASC';
    $answer = $conn->prepare($sql);
    $answer->execute();
    return ($answer->rowCount() > 0) ? $answer->fetchAll(PDO::FETCH_OBJ) : false;
  }

  static public function getById($id) {
    $conn = self::getConnection();
    $sql = 'SELECT id, user_name, password, actived, last_login_at, created_at, updated_at, deleted_at '
            . 'FROM usuario WHERE deleted_at IS NULL '
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
    $sql = 'INSERT INTO usuario (user_name, password, actived, last_login_at) VALUES (:user_name, :password, :actived, :last_login_at)';
    $params = array(
        ':user_name' => $this->getUserName(),
        ':password' => $this->getPassword(),
        ':actived' => $this->getActived(),
        ':last_login_at' => $this->getLastLoginAt()
    );
    $answer = $conn->prepare($sql);
    $answer->execute($params);
    return $conn->lastInsertId(self::_SEQUENCE);
  }

  public function update() {
    $conn = self::getConnection();
    $sql = 'UPDATE usuario SET user_name = :user_name, password = :password, actived = :actived, last_login_at = :last_login_at WHERE id = :id';
    $params = array(
        ':user_name' => $this->getUserName(),
        ':password' => $this->getPassword(),
        ':actived' => $this->getActived(),
        ':last_login_at' => $this->getLastLoginAt(),
        ':id' => $this->getId()
    );
    $answer = $conn->prepare($sql);
    $answer->execute($params);
    return true;
  }

  public function delete($deleteLogical = true) {
    $conn = self::getConnection();
    $params = array(
        ':id' => $this->getId()
    );
    switch ($deleteLogical) {
      case true:
        $sql = 'UPDATE usuario SET deleted_at = now() WHERE id = :id';
        break;
      case false:
        $sql = 'DELETE FROM usuario WHERE id = :id';
        break;
      default:
        throw new PDOException('Por favor indique un dato coherente para el borrado lógico o físico');
    }
    $answer = $conn->prepare($sql);
    $answer->execute($params);
    return true;
  }

}
