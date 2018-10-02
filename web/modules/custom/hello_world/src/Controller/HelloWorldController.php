<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Class HelloWorldController.
 *
 * Provide output for the Hello World module.
 */
class HelloWorldController extends ControllerBase {

  /**
   * Returns a render-able array for printing user information.
   */
  public function content(AccountInterface $user) {

    $response = print_r($user, true);
    $uid = $user->id();
    $build = [
      '#markup' => '<pre>'. $response .'</pre>',
    ];

    return $build;


  }

  /**
   * Returns a render-able array for printing user information.
   */
  public function link_render() {

    $build = [
      '#type' => 'link',
      '#title' => $this->t('A link to example.com'),
      '#url' => Url::fromUri('https://example.com'),
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
