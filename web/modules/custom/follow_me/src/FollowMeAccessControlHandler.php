<?php

namespace Drupal\follow_me;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Follow me entity.
 *
 * @see \Drupal\follow_me\Entity\FollowMe.
 */
class FollowMeAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\follow_me\Entity\FollowMeInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished follow me entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published follow me entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit follow me entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete follow me entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add follow me entities');
  }

}
