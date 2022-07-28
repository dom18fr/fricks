<?php

namespace Drupal\fricks\Controller;

use Drupal\Core\Controller\ControllerBase;

class AppController extends ControllerBase {

  function content() {
    return [
      '#markup' => '<div data-react="fricks"></div>',
    ];
  }
}
