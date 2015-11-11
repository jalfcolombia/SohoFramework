<?php

namespace soho;

use soho\shView as view;

/**
 * Description of controllerClass
 *
 * @author Julian Lasso <ingeniero.julianlasso@gmail.com>
 */
class shController {

  private $view;
  private $module;
  private $format;
  protected $arg;

  public function __construct() {
    $this->arg = array();
  }

  public function setArgs($args) {
    $this->arg = $args;
  }

  public function defineView($view, $module, $format) {
    $this->view = $view . 'Template';
    $this->module = $module;
    $this->format = $format;
  }

  public function renderView() {
    view::renderHTML($this->module, $this->view, $this->format, $this->arg);
  }

}
