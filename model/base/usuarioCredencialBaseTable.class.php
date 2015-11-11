<?php

namespace soho\model\base;

use soho\shModel as model;

/**
 * Description of usuarioCredencialBaseTable
 *
 * @author nombre completo <su@correo.com>
 * @package soho
 * @subpackage model
 * @subpackage base
 * @version 1.0.0
 */
class usuarioCredencialBaseTable extends model {

  const ID = 'id';
  const USUARIO_ID = 'usuario_id';
  const CREDENCIAL_ID = 'credencial_id';
  const CREATED_AT = 'created_at';
  const _SEQUENCE = 'usuario_credencial_id_seq';
  const _TABLE = 'usuario_credencial';

  private $id;
  private $usuario_id;
  private $credencial_id;
  private $created_at;

  public function __construct($id = null, $usuario_id = null, $credencial_id = null, $created_at = null) {
    $this->id = $id;
    $this->usuario_id = $usuario_id;
    $this->credencial_id = $credencial_id;
    $this->created_at = $created_at;
  }

  public function getId() {
    return $this->id;
  }

  public function getUsuarioId() {
    return $this->usuario_id;
  }

  public function getCredencialId() {
    return $this->credencial_id;
  }

  public function getCreatedAt() {
    return $this->created_at;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function setUsuarioId($usuario_id) {
    $this->usuario_id = $usuario_id;
  }

  public function setCredencialId($credencial_id) {
    $this->credencial_id = $credencial_id;
  }

  public function setCreatedAt($created_at) {
    $this->created_at = $created_at;
  }

}
