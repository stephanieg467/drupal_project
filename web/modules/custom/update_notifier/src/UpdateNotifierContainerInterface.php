<?php

namespace Drupal\update_notifier;

/**
 * Interface UpdateNotifierContainerInterface
 * Defines the update notifier service interface.
 *
 * @package Drupal\update_notifier
 */
interface UpdateNotifierContainerInterface {


  /**
   * Allows user to follow product.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Account interface which represents the current user.
   * @param \Drupal\commerce_product\Entity\Product $product_followed
   *   The product being followed.
   * @param bool $notify__price_change
   *   Whether the user wants price change notifications.
   *   TRUE to get notified if product price changes, FALSE to not get notified if product price changes.
   * @param bool $notify__on_sale
   *   Whether the user wants on sale notifications.
   *   TRUE to get notified if the product is on sale, FALSE to not get notified if the product is on sale.
   * @param bool $notify__promotion
   *   Whether the user wants product promotion notifications.
   *   TRUE to get notified if the product has a promotion, FALSE to not get notified if the product has a promotion.
   * @param bool $notify__in_stock
   *   Whether the user wants to get notified if product is in stock.
   *   TRUE to get notified if the product is in stock, FALSE to not get notified if the product is in stock.
   *
   * @return int|bool
   *   FALSE if user is already following the product. Otherwise, returns the update notifier id.
   */
  public function follow($account, $product_followed, $notify__price_change, $notify__on_sale, $notify__promotion, $notify__in_stock);

  /**
   * @param \Drupal\Core\Session\AccountInterface   $account
   * @param \Drupal\commerce_product\Entity\Product $product_followed
   *
   * @return bool
   */
  public function unfollow($account, $product_followed);

  /**
   * @param \Drupal\Core\Session\AccountInterface $account
   *
   * @return array
   *   The update_notifier_entity belonging to the current user.
   */
  public function userUpdateNotifierEntity($account);

  /**
   * @param \Drupal\Core\Session\AccountInterface   $account
   * @param \Drupal\commerce_product\Entity\Product $product_followed
   *
   * @return int
   *   Returns id of update notifier entity.
   */
  public function isFollowing($account, $product_followed);

  /**
   * @param \Drupal\Core\Session\AccountInterface   $account
   * @param \Drupal\commerce_product\Entity\Product $product_followed
   *
   * @return array
   *   Array containing the names of notification types selected.
   */
  public function getSelectedNotifications($account, $product_followed);

}
