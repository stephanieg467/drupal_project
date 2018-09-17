<?php

namespace Drupal\update_notifier;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Update notifier entity entity.
 *
 * @see \Drupal\update_notifier\Entity\UpdateNotifierEntity.
 */
class UpdateNotifierEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\update_notifier\Entity\UpdateNotifierEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished update notifier entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published update notifier entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit update notifier entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete update notifier entity entities');

      case 'follow':
        return AccessResult::allowedIfHasPermission($account, 'follow product');

    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add update notifier entity entities');
  }

}
