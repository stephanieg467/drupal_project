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
   * @param array $notifications
   *   The notifications the user wishes to receive.
   *
   * @return int|bool
   *   False if it was a duplicate. Returns the update notifier id otherwise.
   */
  public function follow($account, $product_followed, $notifications);

  /**
   * @param \Drupal\Core\Session\AccountInterface $account
   * @param string                                $entity_type
   * @param int                                   $entity_id
   *
   * @return bool
   */
  public function delete($account, $entity_type, $entity_id);

  /**
   * @param \Drupal\Core\Session\AccountInterface $account
   *
   * @return array
   *   All products followed by the user.
   */
  public function followedProducts($account);

  /**
   * @param \Drupal\Core\Session\AccountInterface   $account
   * @param \Drupal\commerce_product\Entity\Product $product_followed
   *
   * @return bool
   *   TRUE if the user is already following this product.
   */
  public function isFollowing($account, $product_followed);
}
