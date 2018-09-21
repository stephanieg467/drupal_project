<?php

namespace Drupal\update_notifier\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Class UpdateNotifierController.
 *
 * Provide output for the Update Notifier module.
 */
class UpdateNotifierController extends ControllerBase {

  /**
   * Returns a render-able array for printing user information.
   */
  public function content(AccountInterface $user) {

    $response = print_r($user, true);
    $build = [
      '#markup' => '<pre>'. $response .'</pre>',
    ];
    return $build;

  }

  /**
   * Returns a render-able array for a test admin page.
   */
  public function system_admin() {

    $build = [
      '#markup' => 'Hello, World, and Admin!',
    ];
    return $build;
  }

  /**
   * Returns a render-able array for a full name page.
   */
  public function full_name(string $first,string $last) {

    $build = [
      '#markup' => 'Hello, ' . $first . ' ' . $last . '.',
    ];
    return $build;
  }

}
