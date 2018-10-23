<?php

namespace Drupal\Tests\update_notifier\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\update_notifier\UpdateNotifierContainer;

/**
 * Make sure the the Container can be loaded and has the appropriate methods.
 *
 * @group update_notifier
 */
class UpdateNotifierContainerTest extends UnitTestCase {

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests to make sure the container can be initialized.
   */
  public function testContainer() {
    $container = new UpdateNotifierContainer();

    $this->assertNotNull($container);
  }

  /**
   * Make sure the container has the follow() method.
   */
  public function testFollowMethod() {
    $container = new UpdateNotifierContainer();

    $this->assertEquals(TRUE, method_exists($container, 'follow'));
  }

}
