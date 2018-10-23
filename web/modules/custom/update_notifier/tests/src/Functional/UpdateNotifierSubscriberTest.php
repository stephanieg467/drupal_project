<?php

namespace Drupal\Tests\update_notifier\Functional;

use Drupal\Core\Url;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\commerce_product\Entity\Product;
use Drupal\Tests\BrowserTestBase;

/**
 * Test the functionality of UpdateNotifierSubscriber.
 *
 * @group update_notifier
 *
 * @ingroup update_notifier
 */
class UpdateNotifierSubscriberTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'update_notifier',
    'commerce',
    'commerce_product',  ];

  /**
   * Test that UpdateNotifierSubscriber is listening to product event.
   */
  public function testUpdateNotifierSubscriberListening() {

    $values = [
      'uid' => 1,
      'title' => 'Test Product',
      'id' => 6,
    ];
    $product = $this->createEntity('commerce_product', $values);

    $commerce_product_form = Url::fromRoute('entity.commerce_product.edit_form', ['commerce_product' => $product->id()]);

    $values_form = [];
    $this->drupalPostForm($commerce_product_form, $values_form,'Save');
    $this->assertSession()->pageTextContains('Successfully saved.');

  }

  /**
   * Creates a new entity.
   *
   * @param string $entity_type
   *   The entity type to be created.
   * @param array $values
   *   An array of settings.
   *   Example: 'id' => 'foo'.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   A new entity.
   */
  protected function createEntity($entity_type, array $values) {
    /** @var \Drupal\Core\Entity\EntityStorageInterface $storage */
    $storage = \Drupal::service('entity_type.manager')->getStorage($entity_type);
    $entity = $storage->create($values);
    $status = $entity->save();
    $this->assertEquals(SAVED_NEW, $status, new FormattableMarkup('Created %label entity %type.', [
      '%label' => $entity->getEntityType()->getLabel(),
      '%type' => $entity->id(),
    ]));
    // The newly saved entity isn't identical to a loaded one, and would fail
    // comparisons.
    $entity = $storage->load($entity->id());

    return $entity;
  }

}
