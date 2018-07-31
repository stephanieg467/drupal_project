<?php

namespace Drupal\Tests\follow\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Class HelloWorldRouteTest.
 *
 * Test the routes defined by the hello_world module.
 *
 * @group hello_world
 */
class LoadTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['follow'];

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
  public function testFollowPage() {
    $this->drupalLogin($this->user);
    $this->drupalGet('follow');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Hello, World!');
    $this->assertSession()->pageTextNotContains('Something else');
  }

}
