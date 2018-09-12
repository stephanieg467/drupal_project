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
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\update_notifier\Entity\UpdateNotifierEntity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.update_notifier_entity.edit_form',
      ['update_notifier_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
