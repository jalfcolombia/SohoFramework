<?php

namespace soho;

use soho\shView as view;

/**
 * Description of componentClass
 *
 * @author Julian Lasso <ingeniero.julianlasso@gmail.com>
 */
class shComponent {

  private $view;
  private $module;
  protected $arg;

  public function __construct($args = array()) {
    if (count($args) > 0) {
      foreach ($args as $key => $value) {
        $this->$key = $value;
      }
    }
  }

  public function setArgs($args) {
    $this->arg = $args;
  }

  public function defineView($view, $module) {
    $this->view = $view;
    $this->module = $module;
  }

  public function renderComponent() {
    view::renderComponent($this->module . DIRECTORY_SEPARATOR . $this->view, $this->arg);
  }

}
