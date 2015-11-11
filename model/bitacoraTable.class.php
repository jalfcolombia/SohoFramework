<?php

use soho\myConfig as config;

require_once config::getPathAbsolute() . 'model/base/bitacoraBaseTable.class.php';

use soho\model\base\bitacoraBaseTable;

/**
 * Description of bitacoraTable
 *
 * @author nombre completo <su@correo.com>
 * @package soho
 * @subpackage model
 * @subpackage table
 * @version 1.0.0
 */
class bitacoraTable extends bitacoraBaseTable {

  static public function getAll() {
    $conn = self::getConnection();
    $sql = 'SELECT id, accion, usuario_id, observacion, tabla, registro, created_at FROM bitacora ORDER BY created_at ASC';
    $answer = $conn->prepare($sql);
    $answer->execute();
    return ($answer->rowCount() > 0) ? $answer->fetchAll(PDO::FETCH_OBJ) : false;
  }

  static public function getById($id) {
    $conn = self::getConnection();
    $sql = 'SELECT id, accion, usuario_id, observacion, tabla, registro, created_at '
            . 'FROM bitacora '
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
    $sql = 'INSERT INTO bitacora (accion, usuario_id, observacion, tabla, registro) VALUES (:accion, :usuario_id, :observacion, :tabla, :registro)';
    $params = array(
        ':accion' => $this->getAccion(),
        ':usuario_id' => $this->getUsuarioId(),
        ':observacion' => $this->getObservacion(),
        ':tabla' => $this->getTabla(),
        ':registro' => $this->getRegistro()
    );
    $answer = $conn->prepare($sql);
    $answer->execute($params);
    return $conn->lastInsertId(self::_SEQUENCE);
  }

  public function update() {
    $conn = self::getConnection();
    $sql = 'UPDATE bitacora SET accion = :accion, usuario_id = :usuario_id, observacion = :observacion, tabla = :tabla, registro = :registro WHERE id = :id';
    $params = array(
        ':accion' => $this->getAccion(),
        ':usuario_id' => $this->getUsuarioId(),
        ':observacion' => $this->getObservacion(),
        ':tabla' => $this->getTabla(),
        ':registro' => $this->getRegistro(),
        ':id' => $this->getId()
    );
    $answer = $conn->prepare($sql);
    $answer->execute($params);
    return true;
  }

  public function delete() {
    $conn = self::getConnection();
    $sql = 'DELETE FROM bitacora WHERE id = :id';
    $params = array(
        ':id' => $this->getId()
    );
    $answer = $conn->prepare($sql);
    $answer->execute($params);
    return true;
  }

}
