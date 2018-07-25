<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class HelloWorldContrller.
 *
 * Provide output for the Hello World module.
 */
class HelloWorldController extends ControllerBase {

  /**
   * Returns a render-able array for a test page.
   */
  public function content() {

    $build = [
      '#markup' => 'Hello, World!',
    ];
    return $build;
  }

}