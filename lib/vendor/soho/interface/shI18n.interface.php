<?php

namespace soho\interfaces;

interface shI18n {

  static public function __($message, $culture = null, $dictionary = 'default');

  static public function setCulture($culture);

  static public function getCulture();
}
