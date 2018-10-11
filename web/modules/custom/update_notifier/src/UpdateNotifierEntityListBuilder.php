<?php

namespace Drupal\update_notifier;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Update notifier entity entities.
 *
 * @ingroup update_notifier
 */
class UpdateNotifierEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {

    $header['id'] = $this->t('Update notifier entity ID');
    $header['customer'] = $this->t('Customer');
    $header['product_followed'] = $this->t('Product followed');

    return $header + parent::buildHeader();

  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {

    /* @var $entity \Drupal\update_notifier\Entity\UpdateNotifierEntity */

    $row['id'] = Link::createFromRoute(
      $entity->id(),
      'entity.update_notifier_entity.edit_form',
      ['update_notifier_entity' => $entity->id()]
    );
    $row['customer']['data'] = [
      '#theme' => 'username',
      '#account' => $entity->getOwner(),
    ];
    $row['product_followed'] = Link::createFromRoute(
      $entity->getProductFollowed()->getTitle(),
      'entity.commerce_product.canonical',
      ['commerce_product' => $entity->getProductFollowed()->id()]
    );

    return $row + parent::buildRow($entity);

  }

}
