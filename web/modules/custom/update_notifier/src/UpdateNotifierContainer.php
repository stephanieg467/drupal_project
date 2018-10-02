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

    // Check if this product is already being followed to avoid duplicates.
    /*
    $follow_id = $this->isFollowing($account, $product_followed);
    if (!$follow_id) {
      $values = [
        'user_id' => $account->id(),
        'product_followed' => $product_followed,
        'notifications' => $notifications,
      ];
      /** @var UpdateNotifierEntity $update_notifier_entity */
    /*
      $update_notifier_entity = UpdateNotifierEntity::create($values);
      $update_notifier_entity->save();
      return $update_notifier_entity->id();
    }
    */
    xdebug_break();
    $values = [
      'user_id' => $account->id(),
      'product_followed' => $product_followed,
      'notifications' => $notifications,
    ];
    /** @var UpdateNotifierEntity $update_notifier_entity */
    $update_notifier_entity = UpdateNotifierEntity::create($values);
    $update_notifier_entity->save();
    return $update_notifier_entity->id();

    // TODO probably a more elegant way to clear the cache on this entity view.
    // Issue is that it doesn't update the button until after a cache clear.
    //drupal_flush_all_caches();

    //return FALSE;

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
  //problem here
  public function isFollowing($account, $product_followed) {
    $is_following = \Drupal::entityQuery('update_notifier_entity')
      ->condition('user_id', $account->id())
      ->condition('product_followed', $product_followed)
      ->execute();
    if ($is_following) {
      return TRUE;
    }
    else
      return FALSE;
  }

}
