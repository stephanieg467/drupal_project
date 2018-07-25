<?php

namespace Drupal\Tests\hello_world\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\hello_world\Controller\HelloWorldController;

/**
 * Make sure the the Controller can be loaded and the output is as expected.
 *
 * @group hello_world
 */
class HelloWorldControllerTest extends UnitTestCase {

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests to make sure the controller can be initialized.
   */
  public function testController() {
    $controller = new HelloWorldController();

    $this->assertNotNull($controller);
  }

  /**
   * Make sure the controller has the content() method.
   */
  public function testContentMethod() {
    $controller = new HelloWorldController();

    $this->assertEquals(TRUE, method_exists($controller, 'content'));
  }

  /**
   * Make sure the controller->content() method contains the #markup key.
   */
  public function testContentMethodOutput() {
    $controller = new HelloWorldController();

    $this->assertArrayHasKey('#markup', $controller->content());
  }

}