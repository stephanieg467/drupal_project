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
   * @param string $product_followed
   *   The title of the product being followed.
   * @param array $notifications
   *   The notifications the user wishes to receive.
   *
   * @return int|bool
   *   False if it was a duplicate. Returns the entity id otherwise.
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
   * @param \Drupal\Core\Session\AccountInterface $account
   * @param string                                $entity_type
   * @param int                                   $entity_id
   *
   * @return bool
   *   TRUE if the user is already following this entity.
   */
  public function isFollowing($account, $entity_type, $entity_id);
}
