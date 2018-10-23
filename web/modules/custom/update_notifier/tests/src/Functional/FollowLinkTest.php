<?php

namespace Drupal\Tests\update_notifier\Functional;

use Drupal\Core\Url;
use Drupal\commerce_product\Entity\Product;
use Drupal\update_notifier\Entity\UpdateNotifierEntity;
use Drupal\Tests\BrowserTestBase;

// Getting error message:
// Drupal\Component\Plugin\Exception\PluginNotFoundException: The
//    &quot;commerce_product&quot; entity type does not exist.

/**
 * Test that follow link is displayed properly.
 *
 *
 * @group update_notifier
 *
 * @ingroup update_notifier
 */
class FollowLinkTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'update_notifier',
    'commerce',
    'commerce_product',
    ];

  /**
   * The product to test against.
   *
   * @var \Drupal\commerce_product\Entity\ProductInterface
   */
  protected $product;

  /**
   * The product to test against.
   *
   * @var \Drupal\commerce_product\Entity\ProductInterface
   */
  protected $updateNotifierEntity;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->product = Product::create([
      'product_id' => 6,
      'uid' => 1,
      'title' => 'Test Product',
    ]);
    $this->product->save();

    // Create update notifier entity
    $this->updateNotifierEntity = UpdateNotifierEntity::create([
      'product_followed' => $this->product,
    ]);
    $this->updateNotifierEntity->save();

  }

  /**
   * Test the output of the follow link on product page for authenticated user.
   */
  public function testFollowLinkProductAuthenticated() {

    // Create authenticated user with follow product permission.
    $user = $this->drupalCreateUser(['follow product'], 'Test User');
    $this->drupalLogin($user);

    $this->updateNotifierEntity->setOwnerId($user->id());

    // Test that the product page is accessible.
    $product_page = Url::fromRoute('entity.commerce_product.canonical', ['commerce_product' => $this->product]);
    // $product_page = Url::fromRoute('entity.commerce_product.canonical', ['commerce_product' => $this->product->id()]);
    $this->drupalGet($product_page);
    $this->assertSession()->statusCodeEquals(200);

    // Verify the page contains the follow button.
    $this->assertSession()->LinkExists('Follow');

  }

}
