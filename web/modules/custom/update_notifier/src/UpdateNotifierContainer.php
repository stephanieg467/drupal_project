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
      drupal_flush_all_caches();
      return $update_notifier_entity->id();
    }

    drupal_flush_all_caches();

    return FALSE;

  }

  /**
   * @inheritdoc
   */
  public function unfollow($account, $product_followed) {

    if ($update_notifier_entity_id = $this->isFollowing($account, $product_followed)) {
      $update_notifier_entity = UpdateNotifierEntity::load(reset($update_notifier_entity_id));

      $update_notifier_entity->delete();

      drupal_flush_all_caches();

      return TRUE;
    }
    else
      return FALSE;

  }

  /**
   * @inheritdoc
   */
  public function followedProducts($account) {
    //followedProducts is returning the id of the un entity associated to this user
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

  /**
   * @inheritdoc
   */
  public function getSelectedNotifications($account, $product_followed) {
    $update_notifier_entity_id = $this->isFollowing($account, $product_followed);
    $update_notifier_entity = UpdateNotifierEntity::load(reset($update_notifier_entity_id));
    $notifications = [];
    if($update_notifier_entity->getNotifyPriceChange())
      $notifications['price_change'] = 'price_change';
    if($update_notifier_entity->getNotifyOnSale())
      $notifications['on_sale'] = 'on_sale';
    if($update_notifier_entity->getNotifyPromotion())
      $notifications['promotion'] = 'promotion';
    if($update_notifier_entity->getNotifyInStock())
      $notifications['in_stock'] = 'in_stock';
    return $notifications;
  }

  /**
   * @inheritdoc
   */
  public function priceChanged($product_followed) {
    $price_changed = FALSE;
    /** @var  \Drupal\commerce_product\Entity\ProductVariationInterface[] $product_variations */
    $product_variations = $product_followed->getVariations();
    foreach($product_variations as $product_variation) {
      // original might not work??
      if (!$product_variation->isNew() && $product_variation->get('price')->getValue() != $product_variation->original->get('price')->getValue()) {
        $price_changed = TRUE;
      }
    }
    return $price_changed;

  }

}
