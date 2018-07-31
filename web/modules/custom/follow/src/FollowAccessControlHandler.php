<?php

namespace Drupal\follow;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Follow entity.
 *
 * @see \Drupal\follow\Entity\Follow.
 */
class FollowAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\follow\Entity\FollowInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished follow entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published follow entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit follow entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete follow entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add follow entities');
  }

}
