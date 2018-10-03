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
  public function follow($account, $product_followed, $notify__price_change, $notify__on_sale, $notify__promotion, $notify__in_stock) {

    // Check if this product is already being followed to avoid duplicates.
    $follow_id = $this->isFollowing($account, $product_followed);
    if (!$follow_id) {
      $values = [
        'user_id' => $account->id(),
        'product_followed' => $product_followed,
        'notify__price_change' => $notify__price_change,
        'notify__on_sale' => $notify__on_sale,
        'notify__promotion' => $notify__promotion,
        'notify__in_stock' => $notify__in_stock,
      ];
      /** @var UpdateNotifierEntity $update_notifier_entity */
      $update_notifier_entity = UpdateNotifierEntity::create($values);
      $update_notifier_entity->save();
      return $update_notifier_entity->id();
    }

    drupal_flush_all_caches();

    return FALSE;

  }

  /**
   * @inheritdoc
   */
  public function unfollow($account, $product_followed) {
    // $update_notifier_entity_id is the id of the UpdateNotifierEntity
    // that is being used by the user ($account) following the product ($product_followed).
    $update_notifier_entity_id = \Drupal::entityQuery('update_notifier_entity')
      ->condition('user_id', $account->id())
      ->condition('product_followed', $product_followed->id())
      ->execute();
    /** @var UpdateNotifierEntity $UpdateNotifierEntity */
    $UpdateNotifierEntity = UpdateNotifierEntity::load($update_notifier_entity_id);
    $UpdateNotifierEntity->delete();

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
  public function isFollowing($account, $product_followed) {
    $query = \Drupal::service('entity.query');
    return $query->get('update_notifier_entity')
      ->condition('user_id', $account->id())
      ->condition('product_followed', $product_followed->id())
      ->execute();

  }

}
