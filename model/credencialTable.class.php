<?php

use soho\myConfig as config;

require_once config::getPathAbsolute() . 'model/base/credencialBaseTable.class.php';

use soho\model\base\credencialBaseTable;

/**
 * Description of credencialTable
 *
 * @author nombre completo <su@correo.com>
 * @package soho
 * @subpackage model
 * @subpackage table
 * @version 1.0.0
 */
class credencialTable extends credencialBaseTable {

  static public function getAll() {
    $conn = self::getConnection();
    $sql = 'SELECT id, nombre, usuario_id, created_at, updated_at, deleted_at FROM credencial WHERE deleted_at IS NULL ORDER BY created_at ASC';
    $answer = $conn->prepare($sql);
    $answer->execute();
    return ($answer->rowCount() > 0) ? $answer->fetchAll(PDO::FETCH_OBJ) : false;
  }

  static public function getById($id) {
    $conn = self::getConnection();
    $sql = 'SELECT id, nombre, usuario_id, created_at, updated_at, deleted_at '
            . 'FROM credencial WHERE deleted_at IS NULL '
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
    $sql = 'INSERT INTO credencial (nombre, usuario_id) VALUES (:nombre, :usuario_id)';
    $params = array(
        ':nombre' => $this->getNombre(),
        ':usuario_id' => $this->getUsuarioId()
    );
    $answer = $conn->prepare($sql);
    $answer->execute($params);
    return $conn->lastInsertId(self::_SEQUENCE);
  }

  public function update() {
    $conn = self::getConnection();
    $sql = 'UPDATE credencial SET nombre = :nombre, usuario_id = :usuario_id WHERE id = :id';
    $params = array(
        ':nombre' => $this->getNombre(),
        ':usuario_id' => $this->getUsuarioId(),
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
        $sql = 'UPDATE credencial SET deleted_at = now() WHERE id = :id';
        break;
      case false:
        $sql = 'DELETE FROM credencial WHERE id = :id';
        break;
      default:
        throw new PDOException('Por favor indique un dato coherente para el borrado lógico o físico');
    }
    $answer = $conn->prepare($sql);
    $answer->execute($params);
    return true;
  }

}
