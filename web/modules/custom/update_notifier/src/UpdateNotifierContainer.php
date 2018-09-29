<?php

namespace Drupal\update_notifier;

use Drupal\update_notifier\Entity\UpdateNotifierEntity;

/**
 * Class UpdateNotifierContainer.
 */
class UpdateNotifierContainer implements UpdateNotifierContainerInterface {

  /**
   * @inheritdoc
   */
  public function follow($account, $product_followed, $notifications) {

  }


  public function add($account, $entity_type, $entity_id) {
    // Check if this entity exists already to avoid duplicates.
    $follow_id = $this->isFollowing($account, $entity_type, $entity_id);
    if (!$follow_id) {
      $values = [
        'user_id' => $account->id(),
        'entity_type' => $entity_type,
        'entity_id' => $entity_id,
      ];
      /** @var FollowMe $follow */
      $follow = FollowMe::create($values);
      $follow->save();
      return $follow->id();
    }

    // TODO probably a more elegant way to clear the cache on this entity view.
    // Issue is that it doesn't update the button until after a cache clear.
    drupal_flush_all_caches();

    return FALSE;
  }

  /**
   * @inheritdoc
   */
  public function delete($account, $entity_type, $entity_id) {
    $follow_ids = \Drupal::entityQuery('follow_me')
      ->condition('user_id', $account->id())
      ->condition('entity_type', $entity_type)
      ->condition('entity_id', $entity_id)
      ->execute();
    $followMeEntities = FollowMe::loadMultiple($follow_ids);
    /** @var FollowMe $follow */
    foreach ($followMeEntities as $follow) {
      $follow->delete();
    }

    // TODO probably a more elegant way to clear the cache on this entity view.
    // Issue is that it doesn't update the button until after a cache clear.
    drupal_flush_all_caches();

    return TRUE;
  }

  /**
   * @inheritdoc
   */
  public function followedProducts($account) {
    //Get update notifier entity for current user
    $followed_products_ids = \Drupal::entityQuery('update_notifier_entity')
      ->condition('user_id', $account->id())
      ->execute();
    return UpdateNotifierEntity::loadMultiple($followed_products_ids);
  }

  /**
   * @inheritdoc
   */
  public function isFollowing($account, $entity_type, $entity_id) {
    return \Drupal::entityQuery('follow_me')
      ->condition('user_id', $account->id())
      ->condition('entity_type', $entity_type)
      ->condition('entity_id', $entity_id)
      ->execute();
  }

}
