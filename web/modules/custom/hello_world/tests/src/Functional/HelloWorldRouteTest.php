<?php

namespace Drupal\Tests\hello_world\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Class HelloWorldRouteTest.
 *
 * Test the routes defined by the hello_world module.
 *
 * @group hello_world
 */
class HelloWorldRouteTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['hello_world'];

  /**
   * A user with permission to administer site configuration.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->user = $this->drupalCreateUser(array('access content'));
  }

  /**
   * Make sure the hello_world page returns http 200.
   */
  public function testHelloWorldPage() {
    $this->drupalLogin($this->user);
    $this->drupalGet('hello_world');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Hello, World!');
    $this->assertSession()->pageTextNotContains('Something else');
  }

}