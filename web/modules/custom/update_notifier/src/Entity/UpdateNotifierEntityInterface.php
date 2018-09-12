<?php

namespace Drupal\update_notifier\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Update notifier entity entities.
 *
 * @ingroup update_notifier
 */
interface UpdateNotifierEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the product followed.
   *
   * @return \Drupal\commerce_product\Entity\Product
   *   The product entity
   */
  public function getProductFollowed();

  /**
   * Sets the product followed.
   *
   * @param \Drupal\profile\Entity\ProfileInterface $product_followed
   *   The product followed.
   *
   * @return $this
   */
  public function setProductFollowed($product_followed);

  /**
   * Gets the notifications.
   *
   * @return string
   *   The notifications.
   */
  public function getNotifications();

  /**
   * Sets the notifications.
   *
   * @param string $notifications
   *   The notifications.
   *
   * @return $this
   */
  public function setNotifications($notifications);

  /**
   * Gets the Update notifier entity name.
   *
   * @return string
   *   Name of the Update notifier entity.
   */
  public function getName();

  /**
   * Sets the Update notifier entity name.
   *
   * @param string $name
   *   The Update notifier entity name.
   *
   * @return \Drupal\update_notifier\Entity\UpdateNotifierEntityInterface
   *   The called Update notifier entity entity.
   */
  public function setName($name);

  /**
   * Gets the Update notifier entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Update notifier entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Update notifier entity creation timestamp.
   *
   * @param int $timestamp
   *   The Update notifier entity creation timestamp.
   *
   * @return \Drupal\update_notifier\Entity\UpdateNotifierEntityInterface
   *   The called Update notifier entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Update notifier entity published status indicator.
   *
   * Unpublished Update notifier entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Update notifier entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Update notifier entity.
   *
   * @param bool $published
   *   TRUE to set this Update notifier entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\update_notifier\Entity\UpdateNotifierEntityInterface
   *   The called Update notifier entity entity.
   */
  public function setPublished($published);

}
